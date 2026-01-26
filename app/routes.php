<?php
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Load Routes
$app->get('/', \App\Managers\HomePageManager::class . ':index');

// --- Member & Auth ---
$app->post('/member/login', \App\Managers\UserController::class . ':userLogin');
$app->post('/member/updateToken', \App\Managers\UserController::class . ':updateFcmToken');
$app->get('/member/currenttime', \App\Managers\UserController::class . ':currentTime');

// --- Bag Color & Dress Color & Fortune (Person Section) ---
$app->get('/member/bagcolor/{memberid}/{age1}/{age2}', \App\Managers\UserController::class . ':bagColor');
$app->get('/member/dresscolor/{days}', \App\Managers\UserController::class . ':dressColor');
$app->get('/member/wanpra/{wandate}', \App\Managers\UserController::class . ':wanPra');
$app->get('/member/wanspecial/{wandate}', \App\Managers\UserController::class . ':wanSpecial');
$app->get('/member/miradoV2/{activity}/{birthday}/{today}/{currentday}', \App\Managers\UserController::class . ':miraDoV2');
$app->get('/member/mirado/{activity}/{birthday}/{today}', \App\Managers\UserController::class . ':miraDo');
$app->get('/member/lengyam', \App\Managers\UserController::class . ':lengyamList');

// --- News ---
$app->get('/news/topic24', \App\Managers\NewsController::class . ':newsTop24');
$app->get('/news/topicall/{newsidtype}', \App\Managers\NewsController::class . ':newsTypeAll');
$app->get('/news/topic/{newsidtype}', \App\Managers\NewsController::class . ':newsTypeAll');
$app->get('/news/api/article/{number}', \App\Managers\NewsController::class . ':newsNumberViewDetail');

// --- Fortune & Tools ---
$app->get('/home/main/{homeid}', \App\Managers\HomeController::class . ':main');
$app->get('/tabian/main/{carid}', \App\Managers\TabianController::class . ':main');
$app->get('/phone/{phoneNumber}', \App\Managers\Telephone\PairController::class . ':mainPhone');
$app->get('/shopsell/main', \App\Managers\Telephone\PhoneNumberSellManager::class . ':mainPhoneSell');
$app->get('/tabian/sell/all', \App\Managers\TabianController::class . ':listTabianSell');
$app->get('/nickname/main/{nickname}/{birthday}', \App\Managers\NickNameController::class . ':main');
$app->get('/name/main/{name}/{surname}/{birthday}', \App\Managers\NameController::class . ':main');
$app->get('/nickname/list/{usetable}/{day}/{charx}/{prefix}', \App\Managers\NickNameVipController::class . ':listNickNameByAll');
$app->get('/nickname/list/{usetable}/{day}/{charx}/{prefix}/{lastid}', \App\Managers\NickNameVipController::class . ':listNameVip');

// --- Admin & Assignments ---
$app->get('/admin/finduser/bagcolor/[{username}]', \App\Managers\AdminController::class . ':findUserBagColor');
$app->get('/admin/buddha/pangs', \App\Managers\BuddhaPangController::class . ':getBuddhaPangsApi');
$app->get('/admin/temple/api', \App\Managers\SacredTempleController::class . ':getTemplesApi');
$app->post('/admin/merit/assign', \App\Managers\SacredTempleController::class . ':assignMerit');
$app->post('/admin/buddha/assign', \App\Managers\BuddhaPangController::class . ':assignToUser');
$app->post('/admin/temple/assign', \App\Managers\SacredTempleController::class . ':assignToUser');
$app->get('/member/buddha/assigned/{memberid}', \App\Managers\BuddhaPangController::class . ':getAssigned');
$app->get('/admin/temple/assigned/{memberid}', \App\Managers\SacredTempleController::class . ':getAssigned');

// --- Admin Web Pages (Missing) ---
$app->get('/admin/buddha', \App\Managers\BuddhaPangController::class . ':viewList');
$app->get('/admin/buddha/add', \App\Managers\BuddhaPangController::class . ':viewAdd');
$app->get('/admin/buddha/edit/{id}', \App\Managers\BuddhaPangController::class . ':viewEdit');
$app->post('/admin/buddha/save', \App\Managers\BuddhaPangController::class . ':save');
$app->get('/admin/buddha/delete/{id}', \App\Managers\BuddhaPangController::class . ':delete');

$app->get('/admin/temple', \App\Managers\SacredTempleController::class . ':viewList');
$app->get('/admin/temple/add', \App\Managers\SacredTempleController::class . ':viewAdd');
$app->get('/admin/temple/edit/{id}', \App\Managers\SacredTempleController::class . ':viewEdit');
$app->post('/admin/temple/save', \App\Managers\SacredTempleController::class . ':save');
$app->get('/admin/temple/delete/{id}', \App\Managers\SacredTempleController::class . ':delete');

$app->get('/web/admin/news', \App\Managers\AdminNewsController::class . ':index');
$app->get('/web/admin/news/create', \App\Managers\AdminNewsController::class . ':create');
$app->post('/web/admin/news/store', \App\Managers\AdminNewsController::class . ':store');
$app->get('/web/admin/news/edit/{id}', \App\Managers\AdminNewsController::class . ':edit');
$app->post('/web/admin/news/update/{id}', \App\Managers\AdminNewsController::class . ':update');
$app->get('/web/admin/news/delete/{id}', \App\Managers\AdminNewsController::class . ':delete');

// --- Admin Spells & Special Warnings ---
$app->get('/web/admin/spells', \App\Managers\AdminSpellController::class . ':index');
$app->get('/web/admin/spells/create', \App\Managers\AdminSpellController::class . ':create');
$app->post('/web/admin/spells/store', \App\Managers\AdminSpellController::class . ':store');
$app->get('/web/admin/spells/edit/{id}', \App\Managers\AdminSpellController::class . ':edit');
$app->post('/web/admin/spells/update/{id}', \App\Managers\AdminSpellController::class . ':update');
$app->get('/web/admin/spells/delete/{id}', \App\Managers\AdminSpellController::class . ':delete');

// --- Lucky Number ---
$app->get('/lucky/number', \App\Managers\NewsController::class . ':getLuckyNumber');

// --- Spells API ---
$app->get('/api/spell/latest', \App\Managers\SpellAPIController::class . ':latest');
$app->get('/api/spell/list', \App\Managers\SpellAPIController::class . ':getAll');
$app->get('/api/spell/one/{id}', \App\Managers\SpellAPIController::class . ':getById');
$app->post('/admin/spell/assign', \App\Managers\SpellAPIController::class . ':assign');

// Merit View Route (Calling original .phtml files)
$app->get('/merit/view', function ($request, $response) use ($container) {
    $params = $request->getQueryParams();
    $id = $params['id'] ?? '1';

    $map = [
        '1' => 'sunday',
        '2' => 'monday',
        '3' => 'tuesday',
        '4' => 'wednesday',
        '5' => 'thursday',
        '6' => 'friday',
        '7' => 'saturday',
        '8' => 'rahuu',
        'love' => 'love',
        'chataa' => 'chataa'
    ];

    $viewFile = $map[$id] ?? 'sunday';
    $fullPath = "tambon/" . $viewFile . ".phtml";
    $path = "http://" . $_SERVER['HTTP_HOST'] . "/";

    return $container->get('view')->render($response, $fullPath, ['PATH' => $path]);
});

// Change Number Route
$app->get('/changenum/view', function ($request, $response) use ($container) {
    $params = $request->getQueryParams();
    $id = $params['id'] ?? 'phone';

    $map = [
        'phone' => 'phone',
        'namenick' => 'namenick',
        'namesur' => 'namesur',
        'tabian' => 'tabian',
        'home' => 'home'
    ];

    $viewFile = $map[$id] ?? 'phone';
    $fullPath = "changenum/" . $viewFile . ".phtml";
    $path = "http://" . $_SERVER['HTTP_HOST'] . "/";

    return $container->get('view')->render($response, $fullPath, ['PATH' => $path]);
});

// Web Routes
$app->get('/web/login', function ($request, $response) use ($container) {
    return $container->get('view')->render($response, 'web_login.php', []);
});

$app->post('/web/login', function ($request, $response) use ($container) {
    $data = $request->getParsedBody();
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    $db = $container->get('db');
    $sql = "SELECT * FROM membertb WHERE username = :username AND password = :password";
    $stmt = $db->prepare($sql);
    $stmt->execute([':username' => $username, ':password' => $password]);
    $user = $stmt->fetch(\PDO::FETCH_OBJ);

    if ($user) {
        $_SESSION['user'] = $user;
        return $response->withHeader('Location', '/web/dashboard')->withStatus(302);
    } else {
        $view = $container->get('view');
        return $view->render($response, 'web_login.php', ['error' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง']);
    }
});

$app->get('/web/register', function ($request, $response) use ($container) {
    return $container->get('view')->render($response, 'web_register.php', []);
});

$app->get('/web/api/check-username', function ($request, $response) use ($container) {
    try {
        $username = $request->getQueryParams()['username'] ?? '';
        $db = $container->get('db');
        $stmt = $db->prepare("SELECT count(*) as c FROM membertb WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        $exists = ($row['c'] > 0);

        $response->getBody()->write(json_encode(['exists' => $exists]));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (\Exception $e) {
        $response->getBody()->write(json_encode(['exists' => false, 'error' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json');
    }
});

$app->get('/web/dashboard', function ($request, $response) use ($container) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $user = $_SESSION['user'] ?? null;
    return $container->get('view')->render($response, 'web_dashboard.php', ['user' => $user]);
});
