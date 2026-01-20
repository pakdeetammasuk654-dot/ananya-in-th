<?php

namespace App\Managers;


class AdminController extends Manager
{

    public function topicList($request, $response)
    {
        $sql = "SELECT * FROM topictb ORDER BY topic_id DESC";
        $result = $this->db->prepare($sql);
        if ($result->execute()) {
            $data = $result->fetchAll(\PDO::FETCH_OBJ);

            $realArray = array();

            foreach ($data as $row) {

                if ($row->photo1 != null || !empty($row->photo1)) {
                    $photoReal1 = 'https://www.ananya.in.th/public/photo/' . $row->photo1 . '.png';
                } else {
                    $photoReal1 = null;
                }
                ;

                if ($row->photo2 != null || !empty($row->photo1)) {
                    $photoReal2 = 'https://www.ananya.in.th/public/photo/' . $row->photo2 . '.png';
                } else {
                    $photoReal2 = null;
                }
                ;

                if ($row->photo3 != null || !empty($row->photo1)) {
                    $photoReal3 = 'https://www.ananya.in.th/public/photo/' . $row->photo3 . '.png';
                } else {
                    $photoReal3 = null;
                }
                ;


                array_push($realArray, array('topic_id' => $row->topic_id, 'head_text' => $row->head_text, 'tag_phone' => $row->tag_phone, 'tag_tabian' => $row->tag_tabian, 'tag_home' => $row->tag_home, 'tag_namesur' => $row->tag_namesur, 'paragraph1' => $row->paragraph1, 'paragraph2' => $row->paragraph2, 'paragraph3' => $row->paragraph3, 'photo1' => $photoReal1, 'photo2' => $photoReal2, 'photo3' => $photoReal3, 'topic_date' => $row->topic_date, 'topic_date_update' => $row->topic_date_update, 'public_status' => $row->public_status, 'auth_name' => $row->topic_auth));
            }

            $data_array = array("topic_list" => $realArray);

            echo json_encode($data_array);
        }
    }

    public function topicUpdate($request, $response)
    {

        $body = $request->getParsedBody();

        $topic_id = filter_var($body['topic_id'], FILTER_SANITIZE_STRING);
        $header_text = filter_var($body['header_text'], FILTER_SANITIZE_STRING);
        $desc_text = filter_var($body['desc_text'], FILTER_SANITIZE_STRING);

        $tag_phone = filter_var($body['hag_phone'], FILTER_SANITIZE_STRING);
        $tag_tabian = filter_var($body['hag_tabian'], FILTER_SANITIZE_STRING);
        $tag_home = filter_var($body['hag_home'], FILTER_SANITIZE_STRING);
        $tag_namesur = filter_var($body['hag_namesur'], FILTER_SANITIZE_STRING);

        $photo1 = filter_var($body['photo1'], FILTER_SANITIZE_STRING);
        $photo2 = filter_var($body['photo2'], FILTER_SANITIZE_STRING);
        $photo3 = filter_var($body['photo3'], FILTER_SANITIZE_STRING);

        $paragraph1 = filter_var($body['paragraph1_text'], FILTER_SANITIZE_STRING);
        $paragraph2 = filter_var($body['paragraph2_text'], FILTER_SANITIZE_STRING);
        $paragraph3 = filter_var($body['paragraph3_text'], FILTER_SANITIZE_STRING);

        $auth_name = filter_var($body['auth_name'], FILTER_SANITIZE_STRING);
        $state_topic = filter_var($body['state_topic'], FILTER_SANITIZE_STRING);

        $topicDate = date("Y-m-d h:i:s");

        $photoName1 = '';
        $photoName2 = '';
        $photoName3 = '';

        if ($photo1 != "") {
            $imageName = time();//or Anything You Need
            $path = 'public/photo/' . $imageName . ".png";
            $status = file_put_contents($path, base64_decode($photo1));

            if ($status) {
                $photoName1 = $imageName;
            }
        }

        if ($photo2 != "") {
            $imageName = time() + 1;//or Anything You Need
            $path = 'public/photo/' . $imageName . ".png";
            $status = file_put_contents($path, base64_decode($photo2));

            if ($status) {
                $photoName2 = $imageName;
            }
        }

        if ($photo3 != "") {
            $imageName = time() + 2;//or Anything You Need
            $path = 'public/photo/' . $imageName . ".png";
            $status = file_put_contents($path, base64_decode($photo3));

            if ($status) {
                $photoName3 = $imageName;
            }
        }


        $sql = "UPDATE topictb SET head_text = '$header_text', desc_text = '$desc_text', tag_phone = '$tag_phone', tag_tabian = '$tag_tabian', tag_home = '$tag_home', tag_namesur = '$tag_namesur', paragraph1 = '$paragraph1', paragraph2 = '$paragraph2', paragraph3 = '$paragraph3', photo1 = '$photoName1', photo2 = '$photoName2', photo3 = '$photoName3', topic_auth = '$auth_name', topic_date = '$topicDate', public_status = '$state_topic' WHERE topic_id = '$topic_id'";
        $result = $this->db->prepare($sql);

        if ($result->execute()) {

            $sql = "SELECT * FROM topictb ORDER BY topic_id DESC LIMIT 0, 1";
            $result = $this->db->prepare($sql);

            if ($result->execute()) {

                $rowObj = $result->fetch(\PDO::FETCH_OBJ);
                if (is_object($rowObj)) {
                    if ($rowObj->photo1 != null)
                        $photo1Url = 'https://www.ananya.in.th/public/photo/' . $rowObj->photo1 . '.png';
                    else
                        $photo1Url = null;
                    if ($rowObj->photo2 != null)
                        $photo2Url = 'https://www.ananya.in.th/public/photo/' . $rowObj->photo2 . '.png';
                    else
                        $photo2Url = null;
                    if ($rowObj->photo3 != null)
                        $photo3Url = 'https://www.ananya.in.th/public/photo/' . $rowObj->photo3 . '.png';
                    else
                        $photo3Url = null;

                    $arrObj = array('topic_id' => $rowObj->topic_id, 'topic_date' => $rowObj->topic_date, 'topic_auth' => $rowObj->topic_auth, 'heade_text' => $rowObj->head_text, 'desc_text' => $rowObj->desc_text, 'tag_phone' => $rowObj->tag_phone, 'tag_tabian' => $rowObj->tag_tabian, 'tag_home' => $rowObj->tag_home, 'tag_namesur' => $rowObj->tag_namesur, 'paragraph1' => $rowObj->paragraph1, 'paragraph2' => $rowObj->paragraph2, 'paragraph3' => $rowObj->paragraph3, 'photo1' => $photo1Url, 'photo2' => $photo2Url, 'photo3' => $photo3Url, 'state_topic' => $rowObj->public_status);


                    $response->getBody()->write(json_encode($arrObj));
                    return $response->withHeader('Content-Type', 'application/json');
                }
            }
        }


    }

    public function topicUpload($request, $response)
    {

        $body = $request->getParsedBody();
        $header_text = filter_var($body['header_text'], FILTER_SANITIZE_STRING);
        $desc_text = filter_var($body['desc_text'], FILTER_SANITIZE_STRING);

        $tag_phone = filter_var($body['hag_phone'], FILTER_SANITIZE_STRING);
        $tag_tabian = filter_var($body['hag_tabian'], FILTER_SANITIZE_STRING);
        $tag_home = filter_var($body['hag_home'], FILTER_SANITIZE_STRING);
        $tag_namesur = filter_var($body['hag_namesur'], FILTER_SANITIZE_STRING);

        $photo1 = filter_var($body['photo1'], FILTER_SANITIZE_STRING);
        $photo2 = filter_var($body['photo2'], FILTER_SANITIZE_STRING);
        $photo3 = filter_var($body['photo3'], FILTER_SANITIZE_STRING);


        $auth_name = filter_var($body['auth_name'], FILTER_SANITIZE_STRING);

        $state_topic = filter_var($body['state_topic'], FILTER_SANITIZE_STRING);


        $topicDate = date("Y-m-d h:i:s");


        $photoName1 = '';
        $photoName2 = '';
        $photoName3 = '';

        if ($photo1 != "") {
            $imageName = time();//or Anything You Need
            $path = 'public/photo/' . $imageName . ".png";
            $status = file_put_contents($path, base64_decode($photo1));

            if ($status) {
                $photoName1 = $imageName;
            }
        }

        if ($photo2 != "") {
            $imageName = time() + 1;//or Anything You Need
            $path = 'public/photo/' . $imageName . ".png";
            $status = file_put_contents($path, base64_decode($photo2));

            if ($status) {
                $photoName2 = $imageName;
            }
        }

        if ($photo3 != "") {
            $imageName = time() + 2;//or Anything You Need
            $path = 'public/photo/' . $imageName . ".png";
            $status = file_put_contents($path, base64_decode($photo3));

            if ($status) {
                $photoName3 = $imageName;
            }
        }

        $paragraph1 = filter_var($body['paragraph1_text'], FILTER_SANITIZE_STRING);
        $paragraph2 = filter_var($body['paragraph2_text'], FILTER_SANITIZE_STRING);
        $paragraph3 = filter_var($body['paragraph3_text'], FILTER_SANITIZE_STRING);

        $sql = "INSERT INTO topictb (head_text, desc_text, tag_phone, tag_tabian, tag_home, tag_namesur, paragraph1, paragraph2, paragraph3, photo1, photo2, photo3, topic_date, topic_auth, public_status) VALUES ('$header_text', '$desc_text', '$tag_phone', '$tag_tabian','$tag_home', '$tag_namesur','$paragraph1','$paragraph2','$paragraph3', '$photoName1', '$photoName2', '$photoName3', '$topicDate', '$auth_name', '$state_topic')";

        $result = $this->db->prepare($sql);
        if ($result->execute()) {

            $sql = "SELECT * FROM topictb ORDER BY topic_id DESC LIMIT 0, 1";
            $result = $this->db->prepare($sql);

            if ($result->execute()) {

                $rowObj = $result->fetch(\PDO::FETCH_OBJ);
                if (is_object($rowObj)) {

                    if ($rowObj->photo1 != null)
                        $photo1Url = 'https://www.ananya.in.th/public/photo/' . $rowObj->photo1 . '.png';
                    else
                        $photo1Url = null;
                    if ($rowObj->photo2 != null)
                        $photo2Url = 'https://www.ananya.in.th/public/photo/' . $rowObj->photo2 . '.png';
                    else
                        $photo2Url = null;
                    if ($rowObj->photo3 != null)
                        $photo3Url = 'https://www.ananya.in.th/public/photo/' . $rowObj->photo3 . '.png';
                    else
                        $photo3Url = null;

                    $arrObj = array('topic_id' => $rowObj->topic_id, 'topic_date' => $rowObj->topic_date, 'topic_auth' => $rowObj->topic_auth, 'heade_text' => $rowObj->head_text, 'desc_text' => $rowObj->desc_text, 'tag_phone' => $rowObj->tag_phone, 'tag_tabian' => $rowObj->tag_tabian, 'tag_home' => $rowObj->tag_home, 'tag_namesur' => $rowObj->tag_namesur, 'paragraph1' => $rowObj->paragraph1, 'paragraph2' => $rowObj->paragraph2, 'paragraph3' => $rowObj->paragraph3, 'photo1' => $photo1Url, 'photo2' => $photo2Url, 'photo3' => $photo3Url, 'state_topic' => $rowObj->public_status);


                    $response->getBody()->write(json_encode($arrObj));
                    return $response->withHeader('Content-Type', 'application/json');
                }

            }

        } else {
            return null;
        }

        return null;

    }

    public function photoUpload($request, $response)
    {

        $body = $request->getParsedBody();
        $base64Img = filter_var($body['base64Img'], FILTER_SANITIZE_STRING);
        $image_no = time();//or Anything You Need
        $path = 'public/photo/' . $image_no . ".png";

        $status = file_put_contents($path, base64_decode($base64Img));

        if ($status) {
            $msg = "Successfully Uploaded";
        } else {
            $msg = "Upload failed";
        }

        $response->getBody()->write(json_encode(array("photo_message" => $msg)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function updateBagColorById($request, $response)
    {
        $body = $request->getParsedBody();
        $memberid = filter_var($body['memberid'], FILTER_SANITIZE_STRING);
        $age = filter_var($body['age'], FILTER_SANITIZE_STRING);
        $bagColor1a = filter_var($body['color0'], FILTER_SANITIZE_STRING);
        $bagColor2a = filter_var($body['color1'], FILTER_SANITIZE_STRING);
        $bagColor3a = filter_var($body['color2'], FILTER_SANITIZE_STRING);
        $bagColor4a = filter_var($body['color3'], FILTER_SANITIZE_STRING);
        $bagColor5a = filter_var($body['color4'], FILTER_SANITIZE_STRING);
        $bagColor6a = filter_var($body['color5'], FILTER_SANITIZE_STRING);

        $bagColor1b = filter_var($body['colorb0'], FILTER_SANITIZE_STRING);
        $bagColor2b = filter_var($body['colorb1'], FILTER_SANITIZE_STRING);
        $bagColor3b = filter_var($body['colorb2'], FILTER_SANITIZE_STRING);
        $bagColor4b = filter_var($body['colorb3'], FILTER_SANITIZE_STRING);
        $bagColor5b = filter_var($body['colorb4'], FILTER_SANITIZE_STRING);
        $bagColor6b = filter_var($body['colorb5'], FILTER_SANITIZE_STRING);

        $successUpdateColorA = false;
        $successUpdateColorB = false;

        $age1 = (string) $age;
        $age2 = (string) ((int) $age + 1);
        $sql = "SELECT bagcolortb.bag_id  AS bag_id FROM bagcolortb WHERE bagcolortb.memberid = '$memberid' ORDER BY bagcolortb.memberid DESC LIMIT 1 OFFSET 0";

        $result = $this->db->prepare($sql);
        if ($result->execute()) {
            $userId = $result->fetch(\PDO::FETCH_OBJ);

            $sql = "UPDATE bagcolortb SET bagcolortb.age = '$age1', bagcolortb.bag_color1 = '$bagColor1a', bagcolortb.bag_color2 = '$bagColor2a', bagcolortb.bag_color3 = '$bagColor3a', bagcolortb.bag_color4 = '$bagColor4a', bagcolortb.bag_color5 = '$bagColor5a', bagcolortb.bag_color6 = '$bagColor6a' WHERE bagcolortb.bag_id = '$userId->bag_id'";
            $result = $this->db->prepare($sql);
            if ($result->execute()) {
                $successUpdateColorA = true;
            }
        }


        $sql = "SELECT MAX(bagcolortb.bag_id) AS bag_id FROM bagcolortb WHERE bagcolortb.memberid = '$memberid'";
        $result = $this->db->prepare($sql);
        if ($result->execute()) {
            $userId = $result->fetch(\PDO::FETCH_OBJ);

            $sql = "UPDATE bagcolortb SET bagcolortb.age = '$age2', bagcolortb.bag_color1 = '$bagColor1b', bagcolortb.bag_color2 = '$bagColor2b', bagcolortb.bag_color3 = '$bagColor3b', bagcolortb.bag_color4 = '$bagColor4b', bagcolortb.bag_color5 = '$bagColor5b', bagcolortb.bag_color6 = '$bagColor6b' WHERE bagcolortb.bag_id = '$userId->bag_id'";
            $result = $this->db->prepare($sql);
            if ($result->execute()) {
                $successUpdateColorB = true;

            }
        }


        $response->getBody()->write(json_encode(array('success_update_a' => $successUpdateColorA, 'success_update_b' => $successUpdateColorB)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function insertBagColorByUserId($request, $response)
    {

        $body = $request->getParsedBody();
        $memberid = filter_var($body['memberid'], FILTER_SANITIZE_STRING);
        $age = filter_var($body['age'], FILTER_SANITIZE_STRING);
        $bagColor1a = filter_var($body['bag_color1a'], FILTER_SANITIZE_STRING);
        $bagColor2a = filter_var($body['bag_color2a'], FILTER_SANITIZE_STRING);
        $bagColor3a = filter_var($body['bag_color3a'], FILTER_SANITIZE_STRING);
        $bagColor4a = filter_var($body['bag_color4a'], FILTER_SANITIZE_STRING);
        $bagColor5a = filter_var($body['bag_color5a'], FILTER_SANITIZE_STRING);
        $bagColor6a = filter_var($body['bag_color6a'], FILTER_SANITIZE_STRING);

        $successColor = false;


        $sql = "INSERT INTO bagcolortb (bagcolortb.memberid, bagcolortb.age, bagcolortb.bag_color1, bagcolortb.bag_color2, bagcolortb.bag_color3, bagcolortb.bag_color4, bagcolortb.bag_color5, bagcolortb.bag_color6) VALUES ('$memberid', '$age', '$bagColor1a', '$bagColor2a','$bagColor3a', '$bagColor4a', '$bagColor5a', '$bagColor6a')";

        $result = $this->db->prepare($sql);
        if ($result->execute()) {
            $successColor = true;

        }

        if ($successColor) {
            $response->getBody()->write(json_encode(array('insert_color' => 'success')));
        } else {
            $response->getBody()->write(json_encode(array('insert_color' => 'fail')));
        }
        return $response->withHeader('Content-Type', 'application/json');

    }

    public function colorBagByUserId($request, $response)
    {

        $userId = $request->getAttribute('userid');


        $colorSixA = array();
        $colorSixB = array();
        $sql = "SELECT *  FROM bagcolortb WHERE memberid = '$userId' ORDER BY bag_id ASC LIMIT 1";
        $result = $this->db->prepare($sql);
        if ($result->execute()) {
            $colorSixA = $result->fetch(\PDO::FETCH_OBJ);
        }

        $sql = "SELECT *  FROM bagcolortb WHERE memberid = '$userId' ORDER BY bag_id ASC LIMIT 1 OFFSET 1";
        $result = $this->db->prepare($sql);
        if ($result->execute()) {
            $colorSixB = $result->fetch(\PDO::FETCH_OBJ);
        }

        if (!$colorSixA) {
            $colorSixA = null;
        }

        if (!$colorSixB) {
            $colorSixB = null;
        }

        $response->getBody()->write(json_encode(array('user_id' => $userId, 'color_six_a' => $colorSixA, 'color_six_b' => $colorSixB)));
        return $response->withHeader('Content-Type', 'application/json');

    }

    public function findUserBagColor($request, $response)
    {
        $username = $request->getAttribute('username') ?? '';

        if (empty($username)) {
            // Return latest 50 users
            $sql = "SELECT memberid, username, realname, surname, birthday 
                    FROM membertb 
                    ORDER BY memberid DESC 
                    LIMIT 50";
            $result = $this->db->prepare($sql);
            $result->execute();
        } else {
            // Search by username, realname, surname, or memberid with relevance
            $sql = "SELECT memberid, username, realname, surname, birthday,
                    (CASE 
                        WHEN username = :exact THEN 1
                        WHEN username LIKE :start THEN 2
                        WHEN realname = :exact THEN 3
                        WHEN realname LIKE :start THEN 4
                        WHEN memberid = :exact THEN 5
                        ELSE 6
                    END) AS relevance
                    FROM membertb 
                    WHERE username LIKE :query 
                       OR realname LIKE :query 
                       OR surname LIKE :query 
                       OR memberid LIKE :query
                    ORDER BY relevance ASC, memberid DESC 
                    LIMIT 50";
            $result = $this->db->prepare($sql);
            $result->execute([
                ':query' => "%$username%",
                ':exact' => $username,
                ':start' => "$username%"
            ]);
        }

        $data = $result->fetchAll(\PDO::FETCH_OBJ);

        // Format response to match Android app expectation
        $usernamex = array_map(function ($user) {
            return [
                'member_id' => $user->memberid,
                'username' => $user->username,
                'realname' => $user->realname ?? '',
                'surname' => $user->surname ?? '',
                'birthday' => $user->birthday ?? ''
            ];
        }, $data);

        $response->getBody()->write(json_encode(array('result_userz' => $usernamex)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function addVipcode($request, $response)
    {
        $body = $request->getParsedBody();
        $codetype = filter_var($body['codetype'], FILTER_SANITIZE_STRING);
        $codename = filter_var($body['codename'], FILTER_SANITIZE_STRING);

        $sqlx = "SELECT * FROM secretcode WHERE codename LIKE '{$codename}'";

        $resultx = $this->db->prepare($sqlx);
        if ($resultx->execute()) {
            $data = $resultx->fetchAll(\PDO::FETCH_OBJ);

            if (count($data) > 0) {
                $response->getBody()->write(json_encode(array('activity' => 'insert', 'message' => 'dup')));
            } else {
                $sql = "INSERT INTO secretcode (codetype, codename) VALUES ('{$codetype}','{$codename}')";
                $result = $this->db->prepare($sql);
                if ($result->execute()) {
                    $response->getBody()->write(json_encode(array('activity' => 'insert', 'message' => 'success')));
                } else {
                    $response->getBody()->write(json_encode(array('activity' => 'insert', 'message' => 'fail')));
                }
            }
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function listVipcode($request, $response)
    {
        $sql = "SELECT * FROM secretcode";
        $result = $this->db->prepare($sql);
        if ($result->execute()) {
            $data = $result->fetchAll(\PDO::FETCH_OBJ);

            $data_array = array("data_secret_code_list" => $data);
            $response->getBody()->write(json_encode($data_array));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function addNickName($request, $response)
    {

        $body = $request->getParsedBody();
        $thainame = filter_var($body['thainame'], FILTER_SANITIZE_STRING);
        $reangthai = filter_var($body['reangthai'], FILTER_SANITIZE_STRING);

        $leksat_thai = filter_var($body['leksat_thai'], FILTER_SANITIZE_STRING);
        $shadow = filter_var($body['shadow'], FILTER_SANITIZE_STRING);

        $sqlx = "SELECT thainame FROM nickname WHERE thainame LIKE '{$thainame}'";

        $resultx = $this->db->prepare($sqlx);
        if ($resultx->execute()) {
            $data = $resultx->fetchAll(\PDO::FETCH_OBJ);

            if (count($data) > 0) {
                $response->getBody()->write(json_encode(array('activity' => 'insert', 'message' => 'duplicate data')));
            } else {
                $sql = "INSERT INTO nickname (thainame, reangthai, leksat_thai, shadow) VALUES ('{$thainame}','{$reangthai}','{$leksat_thai}','{$shadow}')";
                $result = $this->db->prepare($sql);
                if ($result->execute()) {
                    $response->getBody()->write(json_encode(array('activity' => 'insert', 'message' => 'success')));
                } else {
                    $response->getBody()->write(json_encode(array('activity' => 'insert', 'message' => 'fail')));
                }
            }
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function addRealName($request, $response)
    {

        $body = $request->getParsedBody();
        $thainame = filter_var($body['thainame'], FILTER_SANITIZE_STRING);
        $reangthai = filter_var($body['reangthai'], FILTER_SANITIZE_STRING);

        $leksat_thai = filter_var($body['leksat_thai'], FILTER_SANITIZE_STRING);
        $shadow = filter_var($body['shadow'], FILTER_SANITIZE_STRING);


        $sqlx = "SELECT thainame FROM realname WHERE thainame LIKE '{$thainame}'";

        $resultx = $this->db->prepare($sqlx);
        if ($resultx->execute()) {
            $data = $resultx->fetchAll(\PDO::FETCH_OBJ);

            if (count($data) > 0) {
                $response->getBody()->write(json_encode(array('activity' => 'insert', 'message' => 'dup')));
            } else {
                $sql = "INSERT INTO realname (thainame, reangthai, leksat_thai, shadow) VALUES ('{$thainame}','{$reangthai}','{$leksat_thai}','{$shadow}')";
                $result = $this->db->prepare($sql);
                if ($result->execute()) {
                    $response->getBody()->write(json_encode(array('activity' => 'insert', 'message' => 'success')));
                } else {
                    $response->getBody()->write(json_encode(array('activity' => 'insert', 'message' => 'fail')));
                }
            }
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function listArticlesJson($request, $response)
    {
        $sql = "SELECT * FROM articles ORDER BY art_id DESC";
        $stmt = $this->db->query($sql);
        $articles = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($articles));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function saveArticleJson($request, $response)
    {
        $body = $request->getParsedBody();
        $id = $body['art_id'] ?? null;
        $title = $body['title'] ?? '';
        $slug = $body['slug'] ?? '';
        if (empty($slug))
            $slug = uniqid('art_');
        $excerpt = $body['excerpt'] ?? '';
        $category = $body['category'] ?? '';
        $content = $body['content'] ?? '';
        $is_published = ($body['is_published'] ?? 1) == 1 ? 1 : 0;
        $published_at = !empty($body['published_at']) ? $body['published_at'] : date('Y-m-d H:i:s');
        $title_short = $body['title_short'] ?? '';
        $image_url = $body['image_url'] ?? '';

        if ($id) {
            $sql = "UPDATE articles SET slug=:slug, title=:title, excerpt=:excerpt, category=:category, content=:content, is_published=:is_published, title_short=:title_short, image_url=:image_url WHERE art_id=:id";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':slug' => $slug,
                ':title' => $title,
                ':excerpt' => $excerpt,
                ':category' => $category,
                ':content' => $content,
                ':is_published' => $is_published,
                ':title_short' => $title_short,
                ':image_url' => $image_url,
                ':id' => $id
            ]);
        } else {
            $sql = "INSERT INTO articles (slug, title, excerpt, category, content, is_published, published_at, title_short, image_url) 
                    VALUES (:slug, :title, :excerpt, :category, :content, :is_published, :published_at, :title_short, :image_url)";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':slug' => $slug,
                ':title' => $title,
                ':excerpt' => $excerpt,
                ':category' => $category,
                ':content' => $content,
                ':is_published' => $is_published,
                ':published_at' => $published_at,
                ':title_short' => $title_short,
                ':image_url' => $image_url
            ]);
        }

        $response->getBody()->write(json_encode(['status' => $success ? 'success' : 'fail']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deleteArticleJson($request, $response)
    {
        $body = $request->getParsedBody();
        $id = $body['art_id'] ?? null;
        if ($id) {
            $stmt = $this->db->prepare("DELETE FROM articles WHERE art_id = :id");
            $success = $stmt->execute([':id' => $id]);
            $response->getBody()->write(json_encode(['status' => $success ? 'success' : 'fail']));
        } else {
            $response->getBody()->write(json_encode(['status' => 'fail', 'message' => 'Missing ID']));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function uploadArticleImage($request, $response)
    {
        $body = $request->getParsedBody();
        $base64Img = $body['base64Img'] ?? '';
        if (empty($base64Img)) {
            $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'No image data']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $image_name = 'art_' . time() . '.jpg';
        $path = 'public/uploads/' . $image_name;

        // Ensure directory exists
        if (!is_dir('public/uploads')) {
            mkdir('public/uploads', 0755, true);
        }

        $status = file_put_contents($path, base64_decode($base64Img));

        if ($status) {
            $url = '/uploads/' . $image_name;
            $response->getBody()->write(json_encode(['status' => 'success', 'url' => $url]));
        } else {
            $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Upload failed']));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}
