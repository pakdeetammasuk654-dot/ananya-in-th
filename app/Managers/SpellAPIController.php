<?php
namespace App\Managers;

use PDO;

class SpellAPIController extends Manager
{
    public function latest($request, $response)
    {
        // Allow CORS if needed, or rely on global middleware

        $sql = "SELECT * FROM spells_warnings ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->query($sql);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            // Adjust photo path to be absolute URL if needed
            if (!empty($item['photo'])) {
                // Assuming photo stored as '/uploads/spells/...'
                // Force domain to avoid SSL IP mismatch issues on Android
                $domain = "https://numberniceic.online";
                $item['photo_url'] = $domain . $item['photo'];

                // Debug info
                $item['debug_photo_path'] = $item['photo'];
            }

            $data = [
                'status' => 'success',
                'data' => $item
            ];
        } else {
            $data = [
                'status' => 'empty',
                'data' => null
            ];
        }

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getById($request, $response, $args)
    {
        $id = $args['id'];
        $sql = "SELECT * FROM spells_warnings WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            if (!empty($item['photo'])) {
                $domain = "https://numberniceic.online";
                $item['photo_url'] = $domain . $item['photo'];
            }
            $data = ['status' => 'success', 'data' => $item];
        } else {
            $data = ['status' => 'error', 'message' => 'Not found'];
        }
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getAll($request, $response)
    {
        $sql = "SELECT * FROM spells_warnings ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as &$item) {
            if (!empty($item['photo'])) {
                $domain = "https://numberniceic.online";
                $item['photo_url'] = $item['photo'];
                if (strpos($item['photo'], 'http') !== 0) {
                    $item['photo_url'] = $domain . $item['photo'];
                }
            } else {
                $item['photo_url'] = "";
            }
        }

        $response->getBody()->write(json_encode(['status' => 'success', 'data' => $items]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function assign($request, $response)
    {
        $body = $request->getParsedBody();
        $memberId = $body['memberid'] ?? null;
        $spellId = $body['spell_id'] ?? null;

        if (!$memberId || !$spellId) {
            $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Missing param']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $sql = "SELECT * FROM spells_warnings WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $spellId]);
        $spell = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$spell) {
            $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Spell not found']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $userSql = "SELECT fcm_token FROM membertb WHERE memberid = :mid";
        $uStmt = $this->db->prepare($userSql);
        $uStmt->execute([':mid' => $memberId]);
        $user = $uStmt->fetch(PDO::FETCH_ASSOC);

        if ($user && !empty($user['fcm_token'])) {
            $title = ($spell['type'] == 'warning') ? "คำเตือนพิเศษ" : "คาถาและคำเตือนพิเศษ";
            $bodyText = "คุณนินแนะนำ : " . $spell['title'] . ' คุณดูได้ที่แถบ "คาถาและคำเตือนพิเศษ" หน้า VIP';

            $dataPayload = [
                'type' => 'webview_spell',
                'url' => (string) $spellId, // Standardize usage of 'url' field for ID
                'spell_id' => (string) $spellId,
                'title' => $title,
                'body' => $bodyText
            ];

            $res = $this->_sendFcmReal($user['fcm_token'], $title, $bodyText, $dataPayload);
            if ($res) {
                $response->getBody()->write(json_encode(['status' => 'success']));
            } else {
                $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'FCM Failed']));
            }
        } else {
            $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'User has no token']));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function _sendFcmReal($token, $title, $body, $data = [])
    {
        $serviceAccountPath = __DIR__ . '/../../configs/service-account.json';
        if (!file_exists($serviceAccountPath))
            return false;

        try {
            $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
            $credentials = new \Google\Auth\Credentials\ServiceAccountCredentials($scopes, $serviceAccountPath);
            $accessToken = $credentials->fetchAuthToken(\Google\Auth\HttpHandler\HttpHandlerFactory::build());
            if (!isset($accessToken['access_token']))
                return false;

            $tokenValue = $accessToken['access_token'];
            $jsonKey = json_decode(file_get_contents($serviceAccountPath), true);
            $projectId = $jsonKey['project_id'];
            $url = "https://fcm.googleapis.com/v1/projects/" . $projectId . "/messages:send";

            $dataPayload = array_merge(['title' => $title, 'body' => $body], $data);
            foreach ($dataPayload as $key => $val) {
                $dataPayload[$key] = (string) $val;
            }

            $message = [
                'message' => [
                    'token' => $token,
                    'data' => $dataPayload
                ]
            ];
            $json = json_encode($message);
            $headers = ['Authorization: Bearer ' . $tokenValue, 'Content-Type: application/json'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $rawResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return $httpCode === 200;
        } catch (\Exception $e) {
            return false;
        }
    }
}
