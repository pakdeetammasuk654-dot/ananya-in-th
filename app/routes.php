<?php


$app->group('/admin', function () {

    $this->get('/topic', 'App\Managers\AdminController:topicList');

    $this->put('/topic', 'App\Managers\AdminController:topicUpdate');
    $this->post('/topic', 'App\Managers\AdminController:topicUpload');

    $this->put('/bagcolor', 'App\Managers\AdminController:updateBagColorById');
    $this->post('/bagcolor', 'App\Managers\AdminController:insertBagColorByUserId');

    $this->get('/bagcolor/{userid}', 'App\Managers\AdminController:colorBagByUserId');
    $this->get('/finduser/bagcolor/{username}', 'App\Managers\AdminController:findUserBagColor');

    $this->get('/secretcode/list', 'App\Managers\AdminController:listVipcode');
    $this->post('/secretcode/list', 'App\Managers\AdminController:addVipcode');

});




$app->group('/lucky', function () {

    $this->post('/number', 'App\Managers\NewsController:postLuckyNumber');
    $this->get('/number', 'App\Managers\NewsController:getLuckyNumber');
});


$app->group('/news', function () {

    $this->get('/topicall/{newsidtype}', 'App\Managers\NewsController:newsTypeAll');
    $this->get('/topic24', 'App\Managers\NewsController:newsTop24');
    $this->get('/topicdetail/{number}', 'App\Managers\NewsController:newsNumberViewDetail');


});


$app->group('/member', function () {

    $this->get('/agecal', 'App\Managers\PersonController:ageCal');

    $this->get('/currenttime', 'App\Managers\UserController:currentTime');

    $this->get('/mirado/{activity}/{birthday}/{today}', 'App\Managers\UserController:miraDo');
    $this->get('/miradoV2/{activity}/{birthday}/{today}/{currentday}', 'App\Managers\UserController:miraDoV2');

    $this->get('/dresscolor/{days}', 'App\Managers\UserController:dressColor');
    $this->get('/dresscoloranti/{days}', 'App\Managers\UserController:dressColorAnti');

    $this->get('/wanpra/{wandate}', 'App\Managers\UserController:wanpra');

    $this->get('/lengyam', 'App\Managers\UserController:lengyamList');

    $this->get('/wanspecial/{wandate}', 'App\Managers\UserController:wanspecial');

    $this->get('/bagcolor/{memberid}/{age1}/{age2}', 'App\Managers\UserController:bagColor');

    $this->post('/update', 'App\Managers\UserController:memberUpdate');
    $this->post('/register/successcf', 'App\Managers\UserController:successInsertConfirm');
    $this->post('/register', 'App\Managers\UserController:userRegister');
    $this->post('/register/v2', 'App\Managers\UserController:userRegisterV2');
    $this->post('/login', 'App\Managers\UserController:userLogin');
    $this->post('/vipcode', 'App\Managers\UserController:userAddVipCode');
});



$app->get('/', 'App\Managers\HomePageManager:index');

$app->group('/changenum', function () {
    $this->get('/home', 'App\Managers\HomePageManager:home');
    $this->get('/phone', 'App\Managers\HomePageManager:phone');
    $this->get('/tabian', 'App\Managers\HomePageManager:tabian');
    $this->get('/namesur', 'App\Managers\HomePageManager:namesur');
    $this->get('/namenick', 'App\Managers\HomePageManager:namenick');


});
$app->group('/tambon', function () {
    $this->get('/sunday', 'App\Managers\HomePageManager:sunday');
    $this->get('/monday', 'App\Managers\HomePageManager:monday');
    $this->get('/tuesday', 'App\Managers\HomePageManager:tuesday');
    $this->get('/wednesday', 'App\Managers\HomePageManager:wednesday');
    $this->get('/thursday', 'App\Managers\HomePageManager:thursday');
    $this->get('/friday', 'App\Managers\HomePageManager:friday');
    $this->get('/saturday', 'App\Managers\HomePageManager:saturday');
    $this->get('/rahuu', 'App\Managers\HomePageManager:rahuu');
    $this->get('/love', 'App\Managers\HomePageManager:love');
    $this->get('/chataa', 'App\Managers\HomePageManager:chataa');

});

$app->group('/person', function () {
    $this->post('/haircut', 'App\Managers\PersonController:haircut');

});


$app->group('/phone', function () {
    $this->get('/main/sell/all', 'App\Managers\Telephone\PhoneNumberSellManager:mainPhoneSell');
    $this->get('/main/{phoneNumber}', 'App\Managers\PhoneController:main');
    $this->get('/{phoneNumber}', 'App\Managers\Telephone\PairController:mainPhone');
});

$app->get('/tabian/main/{carid}', 'App\Managers\TabianController:main');
$app->get('/name/main/{name}/{surname}/{birthday}', 'App\Managers\NameController:main');
$app->get('/nickname/main/{nickname}/{birthday}', 'App\Managers\NickNameController:main');
$app->get('/home/main/{homeid}', 'App\Managers\HomeController:main');



$app->group('/nickname/list', function () {
    $this->get('/{usetable}/{day}/{charx}/{prefix}', 'App\Managers\NickNameVipController:listNickNameByAll');
    $this->get('/{usetable}/{day}/{charx}/{prefix}/{lastid}', 'App\Managers\NickNameVipController:listNameVip');

});


$app->post('/admin/addnickname', 'App\Managers\AdminController:addNickName');
$app->post('/admin/addrealname', 'App\Managers\AdminController:addRealName');

$app->get('/vipcode/{vipcode}', 'App\Managers\UserController:vipCode');

$app->get('/privacy-policy', 'App\Managers\HomePageManager:privacyPolicy');
$app->get('/delete-account', 'App\Managers\HomePageManager:deleteAccount');


