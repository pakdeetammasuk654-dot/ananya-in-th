<?php
namespace App\Managers;

class HomePageManager extends Manager
{

    public function index($req, $res)
    {

        $vars['mng_message'] = 'Message From Manager!!!';
        return $this->view->render($res, 'index.phtml', $vars);
    }





    public function home($req, $res)
    {

        return $this->view->render($res, 'changenum/home.phtml');
    }

    public function tabian($req, $res)
    {

        return $this->view->render($res, 'changenum/tabian.phtml');
    }

    public function phone($req, $res)
    {

        return $this->view->render($res, 'changenum/phone.phtml');
    }

    public function namenick($req, $res)
    {

        return $this->view->render($res, 'changenum/namenick.phtml');
    }

    public function namesur($req, $res)
    {

        return $this->view->render($res, 'changenum/namesur.phtml');
    }

    public function love($req, $res)
    {

        return $this->view->render($res, 'tambon/love.phtml');
    }

    public function chataa($req, $res)
    {

        return $this->view->render($res, 'tambon/chataa.phtml');
    }




    public function sunday($req, $res)
    {

        return $this->view->render($res, 'tambon/sunday.phtml');
    }

    public function monday($req, $res)
    {

        return $this->view->render($res, 'tambon/monday.phtml');
    }

    public function tuesday($req, $res)
    {

        return $this->view->render($res, 'tambon/tuesday.phtml');
    }

    public function wednesday($req, $res)
    {

        return $this->view->render($res, 'tambon/wednesday.phtml');
    }

    public function thursday($req, $res)
    {

        return $this->view->render($res, 'tambon/thursday.phtml');
    }

    public function friday($req, $res)
    {

        return $this->view->render($res, 'tambon/friday.phtml');
    }




    public function saturday($req, $res)
    {

        return $this->view->render($res, 'tambon/saturday.phtml');
    }

    public function rahuu($req, $res)
    {

        return $this->view->render($res, 'tambon/rahuu.phtml');
    }

    public function privacyPolicy($req, $res)
    {

        return $this->view->render($res, 'privacy-policy.phtml');
    }

    public function deleteAccount($req, $res)
    {

        return $this->view->render($res, 'delete-account.phtml');
    }

}