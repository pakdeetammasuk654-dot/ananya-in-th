<?php

namespace App\Managers;

class UserController extends Manager
{


    public function currentTime()
    {
        return json_encode(array('current_time' => date("H:i")));
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
                return json_encode($object);
            }
        }

        return null;
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

                $sqlx = "SELECT * FROM wanpra WHERE wanpra_date = '$currentday'";

                $resultx = $this->db->prepare($sqlx);
                $resultx->execute();
                $objectx = $resultx->fetch(\PDO::FETCH_OBJ);

                if (is_object($objectx) && $objectx->wanpra_date != ""){
                    $wanpra = true;
                }


                return json_encode(array('wanpra'=> $wanpra, 'domira' => $object));
            }
        }

        return null;
    }


    public function dressColor($request, $response)
    {

        $numbDays = null;
        $dayListStr = $request->getAttribute('days');
        $size = strlen($dayListStr);

        if ($size == 7) {
            $numbDays = substr($dayListStr, 0, -1);
        } else {
            $numbDays = $dayListStr;
        }

        if (empty($numbDays)) {
            return json_encode(array('cloth_color' => []));
        }

        // ⚡ Bolt Optimization: N+1 Query Fix
        // Replaced a loop of single queries with a single `IN` clause query.
        // This reduces database round-trips from N to 1, significantly improving performance.
        $dayIds = str_split($numbDays);
        $placeholders = implode(',', array_fill(0, count($dayIds), '?'));

        $sql = "SELECT * FROM colortb WHERE colorid IN ($placeholders)";
        $result = $this->db->prepare($sql);
        $result->execute($dayIds);
        $strColor = $result->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode(array('cloth_color' => $strColor));
    }


    public function lengyamList($request, $response)
    {

        $WanSpecial = null;
        $objWanprasx = null;


        $presentDay = date('Y-m-d');

        $sql = "SELECT * FROM dayspecialtb WHERE wan_date = '$presentDay'";
        $result = $this->db->prepare($sql);
        $result->execute();
        $objLengyam = $result->fetch(\PDO::FETCH_OBJ);

        if ($objLengyam) {
            $WanSpecial = $objLengyam;

        } else {
            $WanSpecial = array('dayid'=>null, 'wan_date'=>null, 'wan_desc'=>null, 'wan_detail'=>null, 'wan_pra'=>null, 'wan_kating'=>null, 'wan_tongchai'=>null, 'wan_atipbadee'=>null);
        }


        $sql = "SELECT * FROM wanpra ORDER BY wanpra_date ASC ";
        $result = $this->db->prepare($sql);
        $result->execute();
        $arrWanpras = $result->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($arrWanpras as $wanpra){
            $nextWanpra = $this->nextWanpra($wanpra['wanpra_date'], $arrWanpras);
            if ($nextWanpra != "") break;
        }



        if ($arrWanpras) {
            $objWanprasx = $arrWanpras;
        } else {
            $objWanprasx = null;
        }


        return json_encode(array("leng_yam"=>$WanSpecial, "next_wanpra" => $nextWanpra, "wan_pras"=>$objWanprasx));

    }


    private function nextWanpra(string $strWanpra, array $wanpraList):string {
        $wanPra = "";

        foreach ($wanpraList as $value){
            if (date('Y-m-d') <= $strWanpra  && $strWanpra == $value['wanpra_date']){
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
            $sql = "SELECT * FROM wanpra WHERE wanpra_date = '$wandate' ORDER BY wanpra_id ASC";

            $result = $this->db->prepare($sql);
            $result->execute();
            $object = $result->fetch(\PDO::FETCH_OBJ);


            $tomorro = (new \DateTime($wandate))->add(new \DateInterval("P1D"))
                ->format('Y-m-d');

            $sql = "SELECT * FROM wanpra WHERE wanpra_date = '$tomorro'";
            $result = $this->db->prepare($sql);
            $result->execute();
            $objTomorro = $result->fetch(\PDO::FETCH_OBJ);


            $wanpraTomorro = false;
            if (is_object($objTomorro)) {
                if ($tomorro == $objTomorro->wanpra_date) {
                    $wanpraTomorro = true;
                }
            }


            if (is_object($object)) {
                return json_encode(array('activity' => 'wanpra', 'tomorrow' => $wanpraTomorro, 'wanpra' => $object));
            } else {
                return json_encode(array('activity' => 'wanpra', 'tomorrow' => $wanpraTomorro, 'wanpra' => null));
            }
        }

        return json_encode(array('activity' => 'fail', 'tomorrow' => null, 'wanpra' => null));
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
                return json_encode(array('activity' => 'success', 'wan_special' => $object));
            }
        }

        return json_encode(array('activity' => 'fail', 'wan_special' => null));
    }


    public function bagColor($request, $response)
    {
        $memberid = $request->getAttribute('memberid');
        //อายุที่ส่งมา บวก 1 ปีกับอายุย่าง
        $age1 = (int)$request->getAttribute('age1');
        $age2 = (int)$request->getAttribute('age2');

        if (!empty($memberid)) {
            $sql = "SELECT * FROM bagcolortb WHERE memberid = '$memberid' && (age = '$age1' || age = '$age2')";

            $result = $this->db->prepare($sql);
            $result->execute();
            $arrObs = $result->fetchAll(\PDO::FETCH_OBJ);

            if (is_array($arrObs)) {

                if (count($arrObs) == 1){ //หาได้ 1 record แสดงว่าอายุเลยวันเกิด

                    foreach ($arrObs as $obs){

                        $oldAge = $age1 - 1;  //ถ้าเลยวันเกิดต้องเอาสีอายุย่างให้อายุปัจจุบัน
                        
                        $sql = "UPDATE bagcolortb SET bagcolortb.age = '$age1', bagcolortb.bag_color1 = '$obs->bag_color1', bagcolortb.bag_color2 = '$obs->bag_color2', bagcolortb.bag_color3 = '$obs->bag_color3', bagcolortb.bag_color4 = '$obs->bag_color4', bagcolortb.bag_color5 = '$obs->bag_color5', bagcolortb.bag_color6 = '$obs->bag_color6' WHERE bagcolortb.memberid = '$memberid' && bagcolortb.age = '$oldAge'";
                        $result = $this->db->prepare($sql);
                        $result->execute();

                        //เปลี่ยนอายุย่างเป็นสีขาว
                        $sql = "UPDATE bagcolortb SET bagcolortb.age = '$age2', bagcolortb.bag_color1 = '#FFFFFF', bagcolortb.bag_color2 = '#FFFFFF', bagcolortb.bag_color3 = '#FFFFFF', bagcolortb.bag_color4 = '#FFFFFF', bagcolortb.bag_color5 = '#FFFFFF', bagcolortb.bag_color6 = '#FFFFFF' WHERE bagcolortb.bag_id = '$obs->bag_id'";
                        $result = $this->db->prepare($sql);
                        $result->execute();
                    }
                }

                $currentAge1 = $age1;
                $currentAge2 = $age2;


                $sql = "SELECT * FROM bagcolortb WHERE memberid = '$memberid' && (age = '$currentAge1 ' || age = '$currentAge2')";

                $result = $this->db->prepare($sql);
                $result->execute();
                $arrObs = $result->fetchAll(\PDO::FETCH_OBJ);

                $arrx = array();

                foreach ($arrObs as $obs){
                    $realAge = (int)$obs->age;
                    array_push($arrx, array('bag_id'=>$obs->bag_id, 'memberid'=>$obs->memberid, 'age'=>$realAge, 'bag_color1'=> $obs->bag_color1, 'bag_color2'=> $obs->bag_color2, 'bag_color3'=>$obs->bag_color3, 'bag_color4'=>$obs->bag_color4, 'bag_color5'=>$obs->bag_color5, 'bag_color6'=>$obs->bag_color6, 'bag_desc'=>$obs->bag_desc));
                }

                return json_encode(array('activity' => 'success', 'member_bagcolor' => $arrx));
            }
        }

        return json_encode(array('activity' => 'fail', 'member_bagcolor' => null));
    }


    public function memberUpdate($request, $response)
    {

        $body = $request->getParsedBody();
        $memberid = filter_var($body['memberid'], FILTER_SANITIZE_STRING);
        $sday = filter_var($body['sday'], FILTER_SANITIZE_STRING);
        $smonth = filter_var($body['smonth'], FILTER_SANITIZE_STRING);
        $syear = filter_var($body['syear'], FILTER_SANITIZE_STRING);
        $shour = filter_var($body['shour'], FILTER_SANITIZE_STRING);
        $sminute = filter_var($body['sminute'], FILTER_SANITIZE_STRING);
        $sprovince = filter_var($body['sprovince'], FILTER_SANITIZE_STRING);
        $sgender = filter_var($body['sgender'], FILTER_SANITIZE_STRING);

        $cYear = $syear - 543;
        $birthday = strtotime("$cYear-$smonth-$sday");
        $manager = new PersonManager();
        $ages = $manager->age($birthday, time());

        if (array_key_exists('year', $ages)) {
            $ageyear = $ages['year'];
        } else {
            $ageyear = 0;
        };
        if (array_key_exists('month', $ages)) {
            $agemonth = $ages['month'];
        } else {
            $agemonth = 0;
        };
        if (array_key_exists('week', $ages)) {
            $ageweek = $ages['week'];
        } else {
            $ageweek = 0;
        };
        if (array_key_exists('day', $ages)) {
            $ageday = $ages['day'];
        } else {
            $ageday = 0;
        };

        $birthdays = new \DateTime("$cYear-$smonth-$sday");
        $sBirthday = $birthdays->format('Y-m-d');


        $sql = "UPDATE membertb SET birthday='{$sBirthday}', shour='{$shour}', sminute='{$sminute}', ageyear='{$ageyear}', agemonth='{$agemonth}', ageweek='{$ageweek}', ageday='{$ageday}', sprovince='{$sprovince}', sgender='{$sgender}' WHERE memberid = '{$memberid}'";

        $result = $this->db->prepare($sql);
        if ($result->execute()) {
            echo json_encode(array('activity' => 'update', 'message' => 'success'));

        } else {
            echo json_encode(array('activity' => 'update', 'message' => 'fail'));
        }
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
                echo json_encode(array('activity' => 'register', 'message' => 'dup'));

            } else {
                echo json_encode(array('activity' => 'register', 'message' => 'presuccess'));

            }


        }
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
        };
        if (array_key_exists('month', $ages)) {
            $agemonth = $ages['month'];
        } else {
            $agemonth = 0;
        };
        if (array_key_exists('week', $ages)) {
            $ageweek = $ages['week'];
        } else {
            $ageweek = 0;
        };
        if (array_key_exists('day', $ages)) {
            $ageday = $ages['day'];
        } else {
            $ageday = 0;
        };


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

        $sql = "SELECT * FROM membertb WHERE username LIKE '{$username}' && password LIKE '{$password}'";
        $result = $this->db->prepare($sql);

        if ($result->execute()) {
            $data = $result->fetchAll(\PDO::FETCH_OBJ);
            if (count($data) > 0) {
                echo json_encode(array('serverx' => array('activity' => 'userlogin', 'message' => 'success'), 'userx' => $data[0]));
            } else {
                echo json_encode(array('serverx' => array('activity' => 'userlogin', 'message' => 'wrong'), 'userx' => null));
            }


        } else {
            echo json_encode(array('serverx' => array('activity' => 'userlogin', 'message' => 'wrong'), 'userx' => null));

        }

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
                return json_encode(array('member' => 'vip', 'vipcode' =>
                        array('vipid' => $object->vipid, 'vipcode' => $object->vipcode, 'userdetial' => $object->userdetial, 'viptype' => $object->viptype, 'vipstatus' => $object->vipstatus))
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
                return json_encode(array('member' => 'vip', 'vipcode' =>
                        array('vipid' => $object->vipid, 'vipcode' => $object->vipcode, 'userdetial' => $object->userdetial, 'viptype' => $object->viptype, 'vipstatus' => $object->vipstatus))
                );
            } else {
                return json_encode(array('member' => 'fail', 'vipcode' => null));

            }
        } else {
            return json_encode(array('member' => 'fail', 'vipcode' => null));
        }
    }
}