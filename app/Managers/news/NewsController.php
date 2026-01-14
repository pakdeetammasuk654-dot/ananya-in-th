<?php

namespace App\Managers;

class NewsController extends Manager
{

    public function wanpraTomoro()
    {
        return json_encode(array('current_time' => date("H:i")));
    }

    public function getLuckyNumber()
    {
        $sql = "SELECT * FROM luckynumber ORDER BY lucky_id DESC LIMIT 1";
        $result = $this->db->prepare($sql);
        $result->execute();
        $obj = $result->fetch(\PDO::FETCH_OBJ);

        $dtime = new \DateTime($obj->lucky_date);
        $fDate = $dtime->format("Y-m-d");



        return json_encode(array('lucky_date'=>$fDate, 'numbers'=>$obj->numbers, 'active'=>$obj->active));
    }

    public function postLuckyNumber($request, $response)
    {
        $body = $request->getParsedBody();
        $numbers = filter_var($body['numbers'], FILTER_SANITIZE_STRING);
        $active = filter_var($body['active'], FILTER_SANITIZE_STRING);

        $sql = "INSERT INTO luckynumber (numbers, active) VALUES ('{$numbers}','{$active}')";
        $result = $this->db->prepare($sql);
        if ($result->execute()) {
            echo json_encode(array('activity' => 'insert_lucky', 'message' => 'success'));
        } else {
            echo json_encode(array('activity' => 'insert_lucky', 'message' => 'fail'));

        }

    }

    public function newsTypeAll($req,$res){
         $newsIdType = $req->getAttribute('newsidtype');



         if ($newsIdType != null && $newsIdType != ''){


             switch ($newsIdType){
                 case "1": {
                     $sql = "SELECT newsid, fix, news_headline, news_pic_header, news_desc FROM news WHERE hashtag1 = 1 ORDER BY newsid DESC"; break;
                 }
                 case "2": {
                     $sql = "SELECT newsid, fix, news_headline, news_pic_header, news_desc FROM news WHERE hashtag2 = 1 ORDER BY newsid DESC"; break;
                 }
                 case "3": {
                     $sql = "SELECT newsid, fix, news_headline, news_pic_header, news_desc FROM news WHERE hashtag3 = 1 ORDER BY newsid DESC"; break;
                 }
                 case "4": {
                     $sql = "SELECT newsid, fix, news_headline, news_pic_header, news_desc FROM news WHERE hashtag4 = 1 ORDER BY newsid DESC"; break;
                 }
                 case "5": {
                     $sql = "SELECT newsid, fix, news_headline, news_pic_header, news_desc FROM news WHERE hashtag5 = 1 ORDER BY newsid DESC"; break;
                 }
                 case "6": {
                     $sql = "SELECT newsid, fix, news_headline, news_pic_header, news_desc FROM news WHERE hashtag6 = 1 ORDER BY newsid DESC"; break;
                 }
             }




             $result = $this->db->prepare($sql);
             $result->execute();
             $data = $result->fetchAll(\PDO::FETCH_ASSOC);

             return json_encode(array("type_id"=>"$newsIdType", "news_all_type"=>$data));
         }
     }

    public function newsTop24()
    {

        $sql = "SELECT newsid, fix, news_headline, news_pic_header, news_desc FROM news WHERE fix = 1 || fix = 2 || fix = 3 || fix = 4 || fix = 5 ORDER BY newsid DESC LIMIT 5";
        $result = $this->db->prepare($sql);
        $result->execute();
        $dataHot = $result->fetchAll(\PDO::FETCH_ASSOC);

        $sql = "SELECT newsid, fix, news_headline, news_pic_header, news_desc FROM news WHERE hashtag1 = 1 ORDER BY newsid DESC LIMIT 4";
        $result = $this->db->prepare($sql);
        $result->execute();
        $dataFeedback = $result->fetchAll(\PDO::FETCH_ASSOC);

        $sql = "SELECT newsid, fix, news_headline, news_pic_header, news_desc FROM news WHERE hashtag2 = 1 ORDER BY newsid DESC LIMIT 4";
        $result = $this->db->prepare($sql);
        $result->execute();
        $dataPhoneNum = $result->fetchAll(\PDO::FETCH_ASSOC);

        $sql = "SELECT newsid, fix, news_headline, news_pic_header, news_desc FROM news WHERE hashtag3 = 1 ORDER BY newsid DESC LIMIT 4";
        $result = $this->db->prepare($sql);
        $result->execute();
        $dataNameSur = $result->fetchAll(\PDO::FETCH_ASSOC);

        $sql = "SELECT newsid, fix, news_headline, news_pic_header, news_desc FROM news WHERE hashtag4 = 1 ORDER BY newsid DESC LIMIT 4";
        $result = $this->db->prepare($sql);
        $result->execute();
        $dataTabian = $result->fetchAll(\PDO::FETCH_ASSOC);

        $sql = "SELECT newsid, fix, news_headline, news_pic_header, news_desc FROM news WHERE hashtag5 = 1 ORDER BY newsid DESC LIMIT 4";
        $result = $this->db->prepare($sql);
        $result->execute();
        $dataHomeNum = $result->fetchAll(\PDO::FETCH_ASSOC);


        $sql = "SELECT newsid, fix, news_headline, news_pic_header, news_desc FROM news WHERE hashtag6 = 1 ORDER BY newsid DESC LIMIT 4";
        $result = $this->db->prepare($sql);
        $result->execute();
        $dataConcept = $result->fetchAll(\PDO::FETCH_ASSOC);


        return json_encode(array("news_hot"=>$dataHot, "news_feedback"=>$dataFeedback, "news_phonenum"=>$dataPhoneNum, "news_namesur"=>$dataNameSur, "news_tabian"=>$dataTabian, "news_homenum"=>$dataHomeNum, "news_concept"=>$dataConcept));
    }

    public function newsNumberViewDetail($request, $response)
    {
        $newsid = $request->getAttribute('number');
        $sql = "SELECT * FROM news WHERE newsid = $newsid";

        $result = $this->db->prepare($sql);
        $result->execute();
        $data = $result->fetch(\PDO::FETCH_ASSOC);
        $vars['data'] = $data;

        return $this->view->render($response, 'newsdetail.phtml', $vars);

    }

}
