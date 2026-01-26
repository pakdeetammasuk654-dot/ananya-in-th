<?php

namespace App\Managers;

use PDO;
use Slim\Psr7\Response;
use Slim\Views\PhpRenderer;

class BuddhaPangController extends Manager
{
    public function viewList($request, $response)
    {
        // Sort by Day (1-7), then 8, then others (90+)
        $stmt = $this->db->query("SELECT * FROM buddha_pang_tb ORDER BY CASE WHEN buddha_day BETWEEN 1 AND 7 THEN buddha_day WHEN buddha_day = 8 THEN 8 ELSE 99 END, buddha_day ASC");
        $items = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $this->container->get('view')->render($response, 'admin_buddha_list.php', [
            'items' => $items
        ]);
    }

    public function viewAdd($request, $response)
    {
        return $this->container->get('view')->render($response, 'admin_buddha_form.php', [
            'item' => null
        ]);
    }

    public function viewEdit($request, $response, $args)
    {
        $id = $args['id'];
        $stmt = $this->db->prepare("SELECT * FROM buddha_pang_tb WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$item) {
            return $response->withHeader('Location', '/admin/buddha')->withStatus(302);
        }

        return $this->container->get('view')->render($response, 'admin_buddha_form.php', [
            'item' => $item
        ]);
    }

    public function save($request, $response)
    {
        $post = $request->getParsedBody();
        $id = $post['id'] ?? null;
        $pang_name = $post['pang_name'] ?? '';
        $buddha_day = !empty($post['buddha_day']) ? $post['buddha_day'] : null;
        $description = $post['description'] ?? '';
        $image_url = $post['image_url'] ?? '';

        // Handle file upload if any (skipped for simplicity of URL input, but can be added)
        $files = $request->getUploadedFiles();
        if (isset($files['image_file']) && $files['image_file']->getError() === UPLOAD_ERR_OK) {
            $uploadedFile = $files['image_file'];
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            $filename = sprintf('%s.%s', bin2hex(random_bytes(8)), $extension);
            $directory = __DIR__ . '/../../public/uploads/buddha';

            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
            $image_url = '/uploads/buddha/' . $filename;
        }

        if ($id) {
            $sql = "UPDATE buddha_pang_tb SET pang_name = :n, buddha_day = :d, description = :desc, image_url = :img WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'n' => $pang_name,
                'd' => $buddha_day,
                'desc' => $description,
                'img' => $image_url,
                'id' => $id
            ]);
        } else {
            $sql = "INSERT INTO buddha_pang_tb (pang_name, buddha_day, description, image_url) VALUES (:n, :d, :desc, :img)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'n' => $pang_name,
                'd' => $buddha_day,
                'desc' => $description,
                'img' => $image_url
            ]);
        }

        return $response->withHeader('Location', '/admin/buddha')->withStatus(302);
    }

    public function delete($request, $response, $args)
    {
        $id = $args['id'];
        $stmt = $this->db->prepare("DELETE FROM buddha_pang_tb WHERE id = :id");
        $stmt->execute(['id' => $id]);

        return $response->withHeader('Location', '/admin/buddha')->withStatus(302);
    }

    // API: Assign Buddha Pang to User
    public function assignToUser($request, $response)
    {
        $body = $request->getParsedBody();
        $memberid = $body['memberid'] ?? $body['member_id'] ?? null;
        $buddha_id = $body['buddha_id'] ?? null;
        $custom_desc = $body['custom_description'] ?? '';
        $type = $body['assignment_type'] ?? 'annual';

        if (!$memberid || !$buddha_id) {
            $response->getBody()->write(json_encode(['status' => 'fail', 'message' => 'Missing parameters']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $sql = "INSERT INTO user_buddha_assign (memberid, assignment_type, buddha_id, custom_description, assigned_at) 
                VALUES (:mid, :type, :bid, :desc, NOW()) 
                ON DUPLICATE KEY UPDATE buddha_id = :bid, custom_description = :desc, assigned_at = NOW()";

        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            ':mid' => $memberid,
            ':type' => $type,
            ':bid' => $buddha_id,
            ':desc' => $custom_desc
        ]);

        // Send Notification
        if ($success) {
            $this->notifyUser($memberid, $type);
        }

        $response->getBody()->write(json_encode(['activity' => $success ? 'success' : 'fail']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function notifyUser($memberid, $type)
    {
        try {
            // Get Token
            $stmt = $this->db->prepare("SELECT fcm_token FROM membertb WHERE memberid = :mid");
            $stmt->execute([':mid' => $memberid]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);

            if ($user && !empty($user->fcm_token)) {
                $title = ($type == 'lifetime') ? "แนะนำพระพุทธรูปประจำตัวตลอดชีพ" : "แนะนำพระพุทธรูปประจำปี";
                $body = "แอดมินได้เลือกพระพุทธรูปที่เหมาะสมกับดวงชะตาของคุณแล้ว";

                // Simple FCM Send (Copy logic for reliability)
                $serviceAccountPath = __DIR__ . '/../../configs/service-account.json';
                if (!file_exists($serviceAccountPath))
                    return;

                $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
                $credentials = new \Google\Auth\Credentials\ServiceAccountCredentials($scopes, $serviceAccountPath);
                $accessToken = $credentials->fetchAuthToken(\Google\Auth\HttpHandler\HttpHandlerFactory::build());
                $tokenValue = $accessToken['access_token'] ?? null;
                if (!$tokenValue)
                    return;

                $url = "https://fcm.googleapis.com/v1/projects/" . $credentials->getProjectId() . "/messages:send";
                $message = [
                    'message' => [
                        'token' => $user->fcm_token,
                        'data' => [
                            'type' => 'buddha_assign',
                            'memberid' => (string) $memberid,
                            'title' => $title,
                            'body' => $body
                        ],
                        'notification' => [
                            'title' => $title,
                            'body' => $body
                        ]
                    ]
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $tokenValue, 'Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_exec($ch);
                curl_close($ch);
            }
        } catch (\Exception $e) {
            // Ignore notification errors to not break the API response
        }
    }

    // API: Get assigned Buddha Pang for a User
    public function getAssigned($request, $response, $args)
    {
        $memberid = $args['memberid'];

        $sql = "SELECT b.*, a.custom_description, a.assignment_type, a.assigned_at FROM buddha_pang_tb b 
                JOIN user_buddha_assign a ON b.id = a.buddha_id 
                WHERE a.memberid = :mid";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':mid' => $memberid]);
        $items = $stmt->fetchAll(PDO::FETCH_OBJ);

        $result = [
            'annual' => null,
            'lifetime' => null
        ];

        foreach ($items as $item) {
            if (isset($item->assignment_type) && $item->assignment_type == 'lifetime') {
                $result['lifetime'] = $item;
            } else {
                $result['annual'] = $item;
            }
        }

        if (!$result['annual']) {
            $item = new \stdClass();
            $item->id = 0;
            $item->buddha_day = 0;
            $item->pang_name = "พระพุทธรูปปางประจำวันเกิด";
            $item->description = "แนะนำให้สรงน้ำหรือถวายพระพุทธรูปปางประจำวันเกิดของท่านเพื่อเสริมความเป็นสิริมงคลและความสุขความเจริญตลอดทั้งปี";
            $item->image_url = "/uploads/buddha/default.png";
            $item->custom_description = "โปรดติดต่อเปิดดวงเพื่อถวายพระพุทธรูปปางที่ถูกโฉลกกับคุณโดยแท้จริง";
            $item->assignment_type = 'annual';
            $result['annual'] = $item;
        }

        if (!$result['lifetime']) {
            $lifeItem = new \stdClass();
            $lifeItem->id = 0;
            $lifeItem->buddha_day = 0;
            $lifeItem->pang_name = "พระพุทธรูปประจำตัวตลอดชีพ";
            $lifeItem->description = "พระพุทธรูปที่เป็นมงคลสูงสุดแก่ชีวิตของคุณ";
            $lifeItem->image_url = "/uploads/buddha/default.png";
            $lifeItem->custom_description = "โปรดติดต่อสอบถามเพื่อรับคำแนะนำพระประจำตัวตลอดชีพ";
            $lifeItem->assignment_type = 'lifetime';
            $result['lifetime'] = $lifeItem;
        }

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // API: Get all Buddha Pangs for Mobile App
    public function getBuddhaPangsApi($request, $response)
    {
        // Return all pangs, sorting by Day 1-8 then 90+
        $stmt = $this->db->query("SELECT * FROM buddha_pang_tb ORDER BY CASE WHEN buddha_day BETWEEN 1 AND 8 THEN buddha_day ELSE 99 END, buddha_day ASC");
        $items = $stmt->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($items));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
