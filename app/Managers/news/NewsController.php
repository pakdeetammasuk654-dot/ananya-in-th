<?php

namespace App\Managers;

class NewsController extends Manager
{

    public function wanpraTomoro($request, $response)
    {
        $data = json_encode(array('current_time' => date("H:i")));
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getLuckyNumber($request, $response)
    {
        // 1. Check if we have manual numbers for today in V2 table
        $today = date('Y-m-d');
        $sqlV2 = "SELECT * FROM luckynumber_v2 WHERE lucky_date = :today";
        $stmtV2 = $this->db->prepare($sqlV2);
        $stmtV2->execute([':today' => $today]);
        $manual = $stmtV2->fetch(\PDO::FETCH_ASSOC);

        if ($manual) {
            $lucky_numbers = [];
            for ($i = 1; $i <= 6; $i++) {
                if (!empty($manual["num$i"])) {
                    $lucky_numbers[] = $manual["num$i"];
                }
            }
            $numbers_str = implode(' ', $lucky_numbers);
        } else {
            // 2. Fallback to random logic if no manual numbers
            $now = time();
            $offset = 9 * 3600; // 9ชั่วโมง
            $seed = (int) date('Ymd', $now - $offset);

            $sql = "SELECT pairnumber FROM numbers WHERE pairtype IN ('D10', 'D8', 'D5') ORDER BY RAND($seed) LIMIT 6";
            $result = $this->db->prepare($sql);
            $result->execute();
            $rows = $result->fetchAll(\PDO::FETCH_OBJ);

            $lucky_numbers = [];
            foreach ($rows as $row) {
                $lucky_numbers[] = $row->pairnumber;
            }
            $numbers_str = implode(' ', $lucky_numbers);
        }

        $vars = [
            'lucky_date' => $today,
            'numbers' => $numbers_str,
            'active' => '1'
        ];

        // Check if browser request
        $accept = $request->getHeaderLine('Accept');
        if (strpos($accept, 'text/html') !== false) {
            return $this->view->render($response, 'lucky_number.phtml', $vars);
        }

        $data = json_encode(array('lucky_date' => $today, 'numbers' => $numbers_str, 'active' => '1'));
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function postLuckyNumber($request, $response)
    {
        $body = $request->getParsedBody();
        $numbers = filter_var($body['numbers'], FILTER_SANITIZE_STRING);
        $active = filter_var($body['active'], FILTER_SANITIZE_STRING);

        $sql = "INSERT INTO luckynumber (numbers, active) VALUES ('{$numbers}','{$active}')";
        $result = $this->db->prepare($sql);
        if ($result->execute()) {
            $data = json_encode(array('activity' => 'insert_lucky', 'message' => 'success'));
        } else {
            $data = json_encode(array('activity' => 'insert_lucky', 'message' => 'fail'));
        }
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function postLuckyNumberV2($request, $response)
    {
        $body = $request->getParsedBody();
        $date = filter_var($body['date'], FILTER_SANITIZE_STRING);
        $num1 = filter_var($body['num1'], FILTER_SANITIZE_STRING);
        $num2 = filter_var($body['num2'], FILTER_SANITIZE_STRING);
        $num3 = filter_var($body['num3'], FILTER_SANITIZE_STRING);
        $num4 = filter_var($body['num4'], FILTER_SANITIZE_STRING);
        $num5 = filter_var($body['num5'], FILTER_SANITIZE_STRING);
        $num6 = filter_var($body['num6'], FILTER_SANITIZE_STRING);

        // UPSERT logic: UPDATE if exists, else INSERT
        $sql = "INSERT INTO luckynumber_v2 (lucky_date, num1, num2, num3, num4, num5, num6) 
                VALUES (:date, :n1, :n2, :n3, :n4, :n5, :n6)
                ON DUPLICATE KEY UPDATE num1=:n1, num2=:n2, num3=:n3, num4=:n4, num5=:n5, num6=:n6";

        $stmt = $this->db->prepare($sql);
        $params = [
            ':date' => $date,
            ':n1' => $num1,
            ':n2' => $num2,
            ':n3' => $num3,
            ':n4' => $num4,
            ':n5' => $num5,
            ':n6' => $num6
        ];

        if ($stmt->execute($params)) {
            $response->getBody()->write(json_encode(['activity' => 'insert_lucky_v2', 'message' => 'success']));
        } else {
            $response->getBody()->write(json_encode(['activity' => 'insert_lucky_v2', 'message' => 'fail']));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getLuckyNumberV2($request, $response)
    {
        $params = $request->getQueryParams();
        $date = $params['date'] ?? date('Y-m-d');

        $sql = "SELECT * FROM luckynumber_v2 WHERE lucky_date = :date";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':date' => $date]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            $response->getBody()->write(json_encode(['status' => 'success', 'data' => $data]));
        } else {
            // Fallback to V1 logic or return empty
            $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'No numbers for this date']));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function newsTypeAll($req, $res)
    {
        $newsIdType = $req->getAttribute('newsidtype');
        if ($newsIdType != null && $newsIdType != '') {
            // ดึงบทความจาก articles เพื่อให้สอดคล้องกันทั้งแอป
            $sql = "SELECT art_id as newsid, image_url as news_pic_header, title as news_headline, excerpt as news_desc 
                    FROM articles 
                    WHERE is_published = 1 
                    ORDER BY pin_order DESC, published_at DESC LIMIT 50";

            $result = $this->db->prepare($sql);
            $result->execute();
            $data = $result->fetchAll(\PDO::FETCH_ASSOC);

            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
            $host = $protocol . "://" . $_SERVER['HTTP_HOST'];
            foreach ($data as &$art) {
                if (!empty($art['news_pic_header']) && strpos($art['news_pic_header'], 'http') !== 0) {
                    $path = ltrim($art['news_pic_header'], '/');
                    $art['news_pic_header'] = $host . '/' . $path;
                }
            }

            $json = json_encode(array("type_id" => "$newsIdType", "news_all_type" => $data));
            $res->getBody()->write($json);
            return $res->withHeader('Content-Type', 'application/json');
        }
        return $res;
    }

    public function newsTop24($request, $response)
    {
        // แผนเดิมดึงจากตาราง 'news' แต่คุณต้องการให้ดึงจาก 'articles' ที่จัดการผ่านเว็บได้
        // เพื่อให้ Android ทำงานต่อได้โดยไม่ต้องแก้ Model เราจะดัดแปลงข้อมูลให้เข้ากับ JSON เดิม
        $sql = "SELECT art_id as newsid, image_url as news_pic_header, title as news_headline, title_short as news_title_short, excerpt as news_desc, category 
                FROM articles 
                WHERE is_published = 1 
                ORDER BY pin_order DESC, art_id DESC LIMIT 20";
        $result = $this->db->prepare($sql);
        $result->execute();
        $articles = $result->fetchAll(\PDO::FETCH_ASSOC);

        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $host = $protocol . "://" . $_SERVER['HTTP_HOST'];
        foreach ($articles as &$art) {
            if (!empty($art['news_pic_header']) && strpos($art['news_pic_header'], 'http') !== 0) {
                $path = ltrim($art['news_pic_header'], '/');
                $art['news_pic_header'] = $host . '/' . $path;
            }
        }

        // แยกข้อมูลให้ตรงกับหัวข้อใน App พัฒนาตามโครงสร้างเดิม
        $dataHot = array_slice($articles, 0, 7); // ใช้ 7 บทความแรกสำหรับหน้า Portal ใหม่
        $dataFeedback = array_slice($articles, 0, 4);
        $dataPhoneNum = array_slice($articles, 0, 4);
        $dataNameSur = array_slice($articles, 0, 4);
        $dataTabian = array_slice($articles, 0, 4);
        $dataHomeNum = array_slice($articles, 0, 4);
        $dataConcept = array_slice($articles, 0, 4);

        $data = json_encode(array(
            "news_hot" => $dataHot,
            "news_feedback" => $dataFeedback,
            "news_phonenum" => $dataPhoneNum,
            "news_namesur" => $dataNameSur,
            "news_tabian" => $dataTabian,
            "news_homenum" => $dataHomeNum,
            "news_concept" => $dataConcept
        ));
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function newsNumberViewDetail($request, $response)
    {
        $art_id = $request->getAttribute('number');
        $sql = "SELECT * FROM articles WHERE art_id = :id";

        $result = $this->db->prepare($sql);
        $result->execute([':id' => $art_id]);
        $article = $result->fetch(\PDO::FETCH_OBJ);

        if (!$article) {
            return $response->withHeader('Location', '/articles')->withStatus(302);
        }

        return $this->view->render($response, 'web_article_detail.php', [
            'article' => $article
        ]);
    }

    public function getArticleJson($request, $response)
    {
        $art_id = $request->getAttribute('id');
        $sql = "SELECT art_id as newsid, 
                       image_url as news_pic_header, 
                       title as news_headline, 
                       title_short as news_title_short, 
                       excerpt as news_desc, 
                       content as news_detail,
                       category,
                       published_at
                FROM articles 
                WHERE art_id = :id AND is_published = 1";

        $result = $this->db->prepare($sql);
        $result->execute([':id' => $art_id]);
        $article = $result->fetch(\PDO::FETCH_ASSOC);

        if (!$article) {
            $response->getBody()->write(json_encode(['error' => 'Article not found']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        // Convert relative image URL to absolute
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $host = $protocol . "://" . $_SERVER['HTTP_HOST'];
        if (!empty($article['news_pic_header']) && strpos($article['news_pic_header'], 'http') !== 0) {
            $path = ltrim($article['news_pic_header'], '/');
            $article['news_pic_header'] = $host . '/' . $path;
        }

        $response->getBody()->write(json_encode($article));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
