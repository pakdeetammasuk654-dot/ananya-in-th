<?php

namespace App\Managers;

class UserController extends Manager
{


    public function currentTime($request, $response)
    {
        $response->getBody()->write(json_encode(array('current_time' => date("H:i"))));
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function miraDo($request, $response)
    {
        $activity = $request->getAttribute('activity');
        $birthday = $request->getAttribute('birthday');
        $today = $request->getAttribute('today');
        if (!empty($birthday)) {
            $sql = "SELECT * FROM miracledo LEFT JOIN miracledo_desc ON miracledo.mira_id = miracledo_desc.mira_id WHERE miracledo.activity = '$activity' && miracledo.dayx = '$birthday' && miracledo.dayy = '$today'";

            $result = $this->db->prepare($sql);
            $result->execute();
            $object = $result->fetch(\PDO::FETCH_OBJ);
            if (is_object($object)) {
                $response->getBody()->write(json_encode($object));
                return $response->withHeader('Content-Type', 'application/json');
            }
        }

        $response->getBody()->write(json_encode(null));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function miraDoV2($request, $response)
    {

        $wanpra = false;

        $activity = $request->getAttribute('activity');
        $birthday = $request->getAttribute('birthday');
        $currentday = $request->getAttribute('currentday');
        $today = $request->getAttribute('today');

        if (!empty($birthday)) {
            $sql = "SELECT * FROM miracledo LEFT JOIN miracledo_desc ON miracledo.mira_id = miracledo_desc.mira_id WHERE miracledo.activity = '$activity' && miracledo.dayx = '$birthday' && miracledo.dayy = '$today'";

            $result = $this->db->prepare($sql);
            $result->execute();
            $object = $result->fetch(\PDO::FETCH_OBJ);
            if (is_object($object)) {

                $wanpra = ThaiCalendarHelper::isWanPra($currentday);

                $response->getBody()->write(json_encode(array('wanpra' => $wanpra, 'domira' => $object)));
                return $response->withHeader('Content-Type', 'application/json');
            }
        }

        $response->getBody()->write(json_encode(null));
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function dressColor($request, $response)
    {
        $dayListStr = $request->getAttribute('days');
        $numbDays = (string)$dayListStr;
        $strColor = array();

        if ($numbDays !== '') {
            $chars = str_split($numbDays);
            $uniqueChars = array_unique($chars);

            // Optimization: Single batch query instead of N queries in a loop
            $placeholders = implode(',', array_fill(0, count($uniqueChars), '?'));
            $sql = "SELECT * FROM colortb WHERE colorid IN ($placeholders)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array_values($uniqueChars));

            $colorMap = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $colorMap[$row['colorid']] = $row;
            }

            // Reconstruct results in original order, preserving duplicates
            foreach ($chars as $char) {
                if (isset($colorMap[$char])) {
                    $strColor[] = $colorMap[$char];
                }
            }
        }

        $response->getBody()->write(json_encode(array('cloth_color' => $strColor)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function dressColorAnti($request, $response)
    {
        return $this->dressColor($request, $response);
    }


    public function lengyamList($request, $response)
    {
        $WanSpecial = null;
        $objWanprasx = null;

        $presentDay = date('Y-m-d');

        $auspicious = ThaiCalendarHelper::getAuspiciousStatus($presentDay);
        $isWanPraToday = ThaiCalendarHelper::isWanPra($presentDay);

        $WanSpecial = [
            'dayid' => '1',
            'wan_date' => $presentDay,
            'wan_desc' => $isWanPraToday ? "วันนี้วันพระ" : "",
            'wan_detail' => "",
            'wan_pra' => $isWanPraToday ? "1" : "0",
            'wan_kating' => "0", // Could add kating calculation if desired
            'wan_tongchai' => $auspicious['is_tongchai'] ? "1" : "0",
            'wan_atipbadee' => $auspicious['is_atipbadee'] ? "1" : "0"
        ];

        $arrWanpras = ThaiCalendarHelper::getUpcomingAuspiciousEvents(4);

        $nextWanpra = "";
        foreach ($arrWanpras as $wanpra) {
            $nextWanpra = $this->nextWanpra($wanpra['wanpra_date'], $arrWanpras);
            if ($nextWanpra != "")
                break;
        }

        if ($arrWanpras) {
            $objWanprasx = $arrWanpras;
        } else {
            $objWanprasx = null;
        }

        $response->getBody()->write(json_encode(array("leng_yam" => $WanSpecial, "next_wanpra" => $nextWanpra, "wan_pras" => $objWanprasx)));
        return $response->withHeader('Content-Type', 'application/json');
    }


    private function nextWanpra(string $strWanpra, array $wanpraList): string
    {
        $wanPra = "";

        foreach ($wanpraList as $value) {
            if (date('Y-m-d') <= $value['wanpra_date']) {
                $wanPra = $value['wanpra_date'];
                break;
            }
        }
        return $wanPra;
    }


    public function wanPra($request, $response)
    {
        $wandate = $request->getAttribute('wandate');
        if (!empty($wandate)) {
            $tomorro = (new \DateTime($wandate))->add(new \DateInterval("P1D"))->format('Y-m-d');
            $isWanpra = ThaiCalendarHelper::isWanPra($wandate);
            $wanpraTomorro = ThaiCalendarHelper::isWanPra($tomorro);

            // Get auspicious day status for today
            $auspiciousStatus = ThaiCalendarHelper::getAuspiciousStatus($wandate);

            // Get auspicious day status for tomorrow
            $auspiciousTomorrow = ThaiCalendarHelper::getAuspiciousStatus($tomorro);

            $data = array(
                'activity' => 'wanpra',
                'tomorrow' => $wanpraTomorro,
                'wanpra' => $isWanpra ? (object) ['wanpra_date' => $wandate] : null,
                'wan_special' => [
                    'wan_tongchai' => $auspiciousStatus['is_tongchai'] ? "1" : "0",
                    'wan_atipbadee' => $auspiciousStatus['is_atipbadee'] ? "1" : "0"
                ],
                'wan_special_tomorrow' => [
                    'wan_tongchai' => $auspiciousTomorrow['is_tongchai'] ? "1" : "0",
                    'wan_atipbadee' => $auspiciousTomorrow['is_atipbadee'] ? "1" : "0"
                ]
            );
        } else {
            $data = array('activity' => 'fail', 'tomorrow' => null, 'wanpra' => null, 'wan_special' => null, 'wan_special_tomorrow' => null);
        }

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function wanSpecial($request, $response)
    {
        $wandate = $request->getAttribute('wandate');
        if (!empty($wandate)) {
            $sql = "SELECT * FROM dayspecialtb WHERE wan_date = '$wandate'";

            $result = $this->db->prepare($sql);
            $result->execute();
            $object = $result->fetch(\PDO::FETCH_OBJ);
            if (is_object($object)) {
                $response->getBody()->write(json_encode(array('activity' => 'success', 'wan_special' => $object)));
                return $response->withHeader('Content-Type', 'application/json');
            }
        }

        $response->getBody()->write(json_encode(array('activity' => 'fail', 'wan_special' => null)));
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function bagColor($request, $response)
    {
        $memberid = $request->getAttribute('memberid');
        $age1 = (int) $request->getAttribute('age1');
        $age2 = (int) $request->getAttribute('age2');

        if (!empty($memberid)) {
            $sql = "SELECT * FROM bagcolortb WHERE memberid = '$memberid' && (age = '$age1' || age = '$age2')";

            $result = $this->db->prepare($sql);
            $result->execute();
            $arrObs = $result->fetchAll(\PDO::FETCH_OBJ);

            if (is_array($arrObs)) {
                if (count($arrObs) == 1) {
                    foreach ($arrObs as $obs) {
                        $oldAge = $age1 - 1;
                        $sql = "UPDATE bagcolortb SET bagcolortb.age = '$age1', bagcolortb.bag_color1 = '$obs->bag_color1', bagcolortb.bag_color2 = '$obs->bag_color2', bagcolortb.bag_color3 = '$obs->bag_color3', bagcolortb.bag_color4 = '$obs->bag_color4', bagcolortb.bag_color5 = '$obs->bag_color5', bagcolortb.bag_color6 = '$obs->bag_color6' WHERE bagcolortb.memberid = '$memberid' && bagcolortb.age = '$oldAge'";
                        $result = $this->db->prepare($sql);
                        $result->execute();

                        $sql = "UPDATE bagcolortb SET bagcolortb.age = '$age2', bagcolortb.bag_color1 = '#FFFFFF', bagcolortb.bag_color2 = '#FFFFFF', bagcolortb.bag_color3 = '#FFFFFF', bagcolortb.bag_color4 = '#FFFFFF', bagcolortb.bag_color5 = '#FFFFFF', bagcolortb.bag_color6 = '#FFFFFF' WHERE bagcolortb.bag_id = '$obs->bag_id'";
                        $result = $this->db->prepare($sql);
                        $result->execute();
                    }
                }

                $sql = "SELECT * FROM bagcolortb WHERE memberid = '$memberid' && (age = '$age1' || age = '$age2')";
                $result = $this->db->prepare($sql);
                $result->execute();
                $arrObs = $result->fetchAll(\PDO::FETCH_OBJ);

                $arrx = array();
                foreach ($arrObs as $obs) {
                    $realAge = (int) $obs->age;
                    array_push($arrx, array('bag_id' => $obs->bag_id, 'memberid' => $obs->memberid, 'age' => $realAge, 'bag_color1' => $obs->bag_color1, 'bag_color2' => $obs->bag_color2, 'bag_color3' => $obs->bag_color3, 'bag_color4' => $obs->bag_color4, 'bag_color5' => $obs->bag_color5, 'bag_color6' => $obs->bag_color6, 'bag_desc' => $obs->bag_desc));
                }

                $response->getBody()->write(json_encode(array('activity' => 'success', 'member_bagcolor' => $arrx)));
                return $response->withHeader('Content-Type', 'application/json');
            }
        }

        $response->getBody()->write(json_encode(array('activity' => 'fail', 'member_bagcolor' => null)));
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function memberUpdate($request, $response)
    {
        $body = $request->getParsedBody();
        $memberid = filter_var($body['memberid'], FILTER_SANITIZE_STRING);
        $sday = filter_var($body['sday'] ?? '', FILTER_SANITIZE_STRING);
        $smonth = filter_var($body['smonth'] ?? '', FILTER_SANITIZE_STRING);
        $syear = filter_var($body['syear'] ?? '', FILTER_SANITIZE_STRING);
        $shour = filter_var($body['shour'] ?? '', FILTER_SANITIZE_STRING);
        $sminute = filter_var($body['sminute'] ?? '', FILTER_SANITIZE_STRING);
        $sprovince = filter_var($body['sprovince'] ?? '', FILTER_SANITIZE_STRING);
        $sgender = filter_var($body['sgender'] ?? '', FILTER_SANITIZE_STRING);

        $cYear = $syear - 543;
        $birthdayTs = strtotime("$cYear-$smonth-$sday");
        $manager = new PersonManager();
        $ages = $manager->age($birthdayTs, time());

        $ageyear = $ages['year'] ?? 0;
        $agemonth = $ages['month'] ?? 0;
        $ageweek = $ages['week'] ?? 0;
        $ageday = $ages['day'] ?? 0;

        $birthdays = new \DateTime("$cYear-$smonth-$sday");
        $sBirthday = $birthdays->format('Y-m-d');

        $sql = "UPDATE membertb SET birthday='{$sBirthday}', shour='{$shour}', sminute='{$sminute}', ageyear='{$ageyear}', agemonth='{$agemonth}', ageweek='{$ageweek}', ageday='{$ageday}', sprovince='{$sprovince}', sgender='{$sgender}' WHERE memberid = '{$memberid}'";

        $result = $this->db->prepare($sql);
        if ($result->execute()) {
            $data = array('activity' => 'update', 'message' => 'success');
        } else {
            $data = array('activity' => 'update', 'message' => 'fail');
        }

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function userRegisterV2($request, $response)
    {
        $body = $request->getParsedBody();
        $username = filter_var($body['username'], FILTER_SANITIZE_STRING);

        $sqlr = "SELECT username FROM membertb WHERE username LIKE '{$username}'";
        $result = $this->db->prepare($sqlr);

        if ($result->execute()) {
            $data = $result->fetchAll(\PDO::FETCH_OBJ);
            if (count($data) > 0) {
                $msg = array('activity' => 'register', 'message' => 'dup');
            } else {
                $msg = array('activity' => 'register', 'message' => 'presuccess');
            }
        } else {
            $msg = array('activity' => 'register', 'message' => 'error');
        }

        $response->getBody()->write(json_encode($msg));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function successInsertConfirm($request, $response)
    {
        $body = $request->getParsedBody();

        $realname = filter_var($body['realname'], FILTER_SANITIZE_STRING);
        $surname = filter_var($body['surname'], FILTER_SANITIZE_STRING);

        $username = filter_var($body['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($body['password'], FILTER_SANITIZE_STRING);

        $sday = filter_var($body['sday'], FILTER_SANITIZE_STRING);
        $smonth = filter_var($body['smonth'], FILTER_SANITIZE_STRING);
        $syear = filter_var($body['syear'], FILTER_SANITIZE_STRING);
        $shour = filter_var($body['shour'], FILTER_SANITIZE_STRING);
        $sminute = filter_var($body['sminute'], FILTER_SANITIZE_STRING);
        $sprovince = filter_var($body['sprovince'], FILTER_SANITIZE_STRING);
        $sgender = filter_var($body['sgender'], FILTER_SANITIZE_STRING);

        $cYear = $syear - 543;
        $birthday = strtotime("$cYear-$smonth-$sday");

        $birthdays = new \DateTime("$cYear-$smonth-$sday");
        $sBirthday = $birthdays->format('Y-m-d');

        $manager = new PersonManager();

        $ages = $manager->age($birthday, time());
        if (array_key_exists('year', $ages)) {
            $ageyear = $ages['year'];
        } else {
            $ageyear = 0;
        }
        ;
        if (array_key_exists('month', $ages)) {
            $agemonth = $ages['month'];
        } else {
            $agemonth = 0;
        }
        ;
        if (array_key_exists('week', $ages)) {
            $ageweek = $ages['week'];
        } else {
            $ageweek = 0;
        }
        ;
        if (array_key_exists('day', $ages)) {
            $ageday = $ages['day'];
        } else {
            $ageday = 0;
        }
        ;


        $sql = "INSERT INTO membertb (realname, surname, birthday, shour, sminute, ageyear, agemonth, ageweek, ageday, sprovince, sgender, username, password) VALUES ('{$realname}', '{$surname}', '{$sBirthday}', '{$shour}', '{$sminute}', '{$ageyear}', '{$agemonth}', '{$ageweek}', '{$ageday}', '{$sprovince}', '{$sgender}', '{$username}', '{$password}')";

        $result = $this->db->prepare($sql);
        if ($result->execute()) {
            echo json_encode(array('activity' => 'register', 'message' => 'success'));

        } else {
            echo json_encode(array('activity' => 'register', 'message' => 'fail'));
        }
    }

    public function userRegister($request, $response)
    {

        $body = $request->getParsedBody();

        $realname = filter_var($body['realname'], FILTER_SANITIZE_STRING);
        $surname = filter_var($body['surname'], FILTER_SANITIZE_STRING);
        $username = filter_var($body['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($body['password'], FILTER_SANITIZE_STRING);


        $sqlr = "SELECT username FROM membertb WHERE username LIKE '{$username}'";
        $result = $this->db->prepare($sqlr);

        if ($result->execute()) {

            $data = $result->fetchAll(\PDO::FETCH_OBJ);

            if (count($data) > 0) {

                echo json_encode(array('activity' => 'register', 'message' => 'dup'));

            } else {
                $sql = "INSERT INTO membertb (realname, surname, username, password) VALUES ('{$realname}', '{$surname}', '{$username}', '{$password}')";
                $result = $this->db->prepare($sql);

                if ($result->execute()) {
                    echo json_encode(array('activity' => 'register', 'message' => 'success'));

                } else {
                    echo json_encode(array('activity' => 'register', 'message' => 'fail'));
                }
            }


        }
    }

    public function userLogin($request, $response)
    {
        $body = $request->getParsedBody();
        $username = filter_var($body['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($body['password'], FILTER_SANITIZE_STRING);

        $sql = "SELECT * FROM membertb WHERE username LIKE :username AND password LIKE :password";
        $result = $this->db->prepare($sql);
        $result->execute([':username' => $username, ':password' => $password]);
        $data = $result->fetchAll(\PDO::FETCH_OBJ);

        if (count($data) > 0) {
            $resData = array('serverx' => array('activity' => 'userlogin', 'message' => 'success'), 'userx' => $data[0]);
        } else {
            $resData = array('serverx' => array('activity' => 'userlogin', 'message' => 'wrong'), 'userx' => null);
        }

        $response->getBody()->write(json_encode($resData));
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function userAddVipCode($request, $response)
    {
        $body = $request->getParsedBody();

        $vipCode = filter_var($body['vipcode'], FILTER_SANITIZE_STRING);
        $userId = filter_var($body['userid'], FILTER_SANITIZE_STRING);

        //ตรวจสอบว่ามี vip code นี้ในระบบหรือไม่?
        $realVip = $this->checkRealVipCode($vipCode);

        //ตรวจสอบว่ามีการใช้ vip code นี้แล้วหรือไม่?
        $useVip = $this->checkUseVipCode($vipCode);

        if ($realVip) {

            if (!$useVip) {

                $sql = "SELECT * FROM secretcode WHERE codename LIKE '{$vipCode}'";
                $result = $this->db->prepare($sql);

                if ($result->execute()) {
                    $data = $result->fetchAll(\PDO::FETCH_OBJ);

                    if (count($data) > 0) {
                        $addUser = $this->addUserToVip($data[0]->codetype, $data[0]->codename, $userId);
                    }


                    if ($addUser) {
                        echo json_encode(array('viplevel' => $data[0]->codetype, 'message' => 'success', 'codename' => $data[0]->codename));

                    } else {
                        echo json_encode(array('viplevel' => 'addUserToVip', 'message' => 'wrong_add_user', 'codename' => 'wrong_no_add_user'));
                    }
                } else {
                    echo json_encode(array('viplevel' => 'addUserToVip', 'message' => 'wrong_add_user', 'codename' => 'wrong_no_add_user'));
                }
            } else {
                echo json_encode(array('viplevel' => 'realVip', 'message' => 'wrong', 'wrong_code_used' => 'wrong_code_used'));
            }
        } else {
            echo json_encode(array('viplevel' => 'realVip', 'message' => 'wrong', 'wrong_not_real' => 'wrong_not_real'));
        }

    }

    private function checkRealVipCode(string $vipCode): bool
    {
        $sql = "SELECT * FROM secretcode WHERE codename = '{$vipCode}' && codestatus = 'active'";
        $result = $this->db->prepare($sql);

        if ($result->execute()) {
            $data = $result->fetchAll(\PDO::FETCH_OBJ);

            if (count($data) > 0) {
                return true;
            }

        }

        return false;
    }

    private function checkUseVipCode(string $vipCode): bool
    {
        $sql = "SELECT * FROM memberuse WHERE codename = '{$vipCode}'";
        $result = $this->db->prepare($sql);

        if ($result->execute()) {
            $data = $result->fetchAll(\PDO::FETCH_OBJ);

            if (count($data) > 0) {
                return true;
            }

        }

        return false;
    }

    private function addUserToVip($viptype, $vipCode, $userId): bool
    {

        $datex = getdate();
        $datef = $datex['year'] . '-' . $datex['month'] . '-' . $datex['mday'];

        $sql = "INSERT INTO memberuse (viptype, codename, memberid, dateadd) VALUES ('{$viptype}', '{$vipCode}', '{$userId}', '{$datef}')";
        $result = $this->db->prepare($sql);

        if ($result->execute()) {
            return true;
        }

        return false;
    }


    public function vipActive($request, $response)
    {


        $code = $request->getAttribute('vipcode');
        if ($code != '') {
            $sql = "SELECT * FROM vipcode WHERE vipcode = '{$code}'";
            $result = $this->db->prepare($sql);
            $result->execute();
            $object = $result->fetch(\PDO::FETCH_OBJ);
            if (is_object($object)) {
                return json_encode(
                    array(
                        'member' => 'vip',
                        'vipcode' =>
                            array('vipid' => $object->vipid, 'vipcode' => $object->vipcode, 'userdetial' => $object->userdetial, 'viptype' => $object->viptype, 'vipstatus' => $object->vipstatus)
                    )
                );
            } else {
                return json_encode(array('member' => 'fail', 'vipcode' => null));

            }
        } else {
            return json_encode(array('member' => 'fail', 'vipcode' => null));
        }
    }

    public function vipCode($request, $response)
    {
        //$body = $request->getParsedBody();
        //$vipcode = filter_var($body['vipcode'], FILTER_SANITIZE_STRING);


        $code = $request->getAttribute('vipcode');
        if ($code != '') {
            $sql = "SELECT * FROM vipcode WHERE vipcode = '{$code}'";
            $result = $this->db->prepare($sql);
            $result->execute();
            $object = $result->fetch(\PDO::FETCH_OBJ);
            if (is_object($object)) {
                return json_encode(
                    array(
                        'member' => 'vip',
                        'vipcode' =>
                            array('vipid' => $object->vipid, 'vipcode' => $object->vipcode, 'userdetial' => $object->userdetial, 'viptype' => $object->viptype, 'vipstatus' => $object->vipstatus)
                    )
                );
            } else {
                return json_encode(array('member' => 'fail', 'vipcode' => null));

            }
        } else {
            return json_encode(array('member' => 'fail', 'vipcode' => null));
        }
    }

    public function updateFcmToken($request, $response)
    {
        $body = $request->getParsedBody();
        $memberid = filter_var($body['memberid'] ?? '', FILTER_SANITIZE_STRING);
        $token = filter_var($body['token'] ?? '', FILTER_SANITIZE_STRING);

        if (!empty($memberid)) {
            $sql = "UPDATE membertb SET fcm_token = :token WHERE memberid = :mid";
            $stmt = $this->db->prepare($sql);
            // Use empty string or NULL if token is empty
            $tokenVal = !empty($token) ? $token : null;
            if ($stmt->execute([':token' => $tokenVal, ':mid' => $memberid])) {
                $response->getBody()->write(json_encode(['status' => 'success']));
            } else {
                $response->getBody()->write(json_encode(['status' => 'fail', 'message' => 'database error']));
            }
        } else {
            $response->getBody()->write(json_encode(['status' => 'fail', 'message' => 'missing memberid']));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}