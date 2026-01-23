<?php

use Slim\Routing\RouteCollectorProxy;

$app->group('/admin', function (RouteCollectorProxy $group) {
    $group->get('/topic', 'App\Managers\AdminController:topicList');
    $group->put('/topic', 'App\Managers\AdminController:topicUpdate');
    $group->post('/topic', 'App\Managers\AdminController:topicUpload');
    $group->put('/bagcolor', 'App\Managers\AdminController:updateBagColorById');
    $group->post('/bagcolor', 'App\Managers\AdminController:insertBagColorByUserId');
    $group->get('/bagcolor/{userid}', 'App\Managers\AdminController:colorBagByUserId');
    $group->get('/finduser/bagcolor/', 'App\Managers\AdminController:findUserBagColor');
    $group->get('/finduser/bagcolor/{username}', 'App\Managers\AdminController:findUserBagColor');
    $group->get('/secretcode/list', 'App\Managers\AdminController:listVipcode');
    $group->post('/secretcode/list', 'App\Managers\AdminController:addVipcode');
    $group->get('/notifications/send-bag-colors', 'App\Managers\NotificationController:sendBagColors');

    // Mobile Admin Article Routes
    $group->get('/articles/list', 'App\Managers\AdminController:listArticlesJson');
    $group->post('/articles/save', 'App\Managers\AdminController:saveArticleJson');
    $group->post('/articles/delete', 'App\Managers\AdminController:deleteArticleJson');
    $group->post('/articles/upload-image', 'App\Managers\AdminController:uploadArticleImage');
});

$app->group('/lucky', function (RouteCollectorProxy $group) {
    $group->post('/number', 'App\Managers\NewsController:postLuckyNumber');
    $group->get('/number', 'App\Managers\NewsController:getLuckyNumber');
    $group->post('/number/v2', 'App\Managers\NewsController:postLuckyNumberV2');
    $group->get('/number/v2', 'App\Managers\NewsController:getLuckyNumberV2');
});

$app->group('/news', function (RouteCollectorProxy $group) {
    $group->get('/topicall/{newsidtype}', 'App\Managers\NewsController:newsTypeAll');
    $group->get('/topic24', 'App\Managers\NewsController:newsTop24');
    $group->get('/topicdetail/{number}', 'App\Managers\NewsController:newsNumberViewDetail');
    $group->get('/api/article/{id}', 'App\Managers\NewsController:getArticleJson');
});

$app->group('/member', function (RouteCollectorProxy $group) {
    $group->get('/agecal', 'App\Managers\PersonController:ageCal');
    $group->get('/currenttime', 'App\Managers\UserController:currentTime');
    $group->get('/mirado/{activity}/{birthday}/{today}', 'App\Managers\UserController:miraDo');
    $group->get('/miradoV2/{activity}/{birthday}/{today}/{currentday}', 'App\Managers\UserController:miraDoV2');
    $group->get('/dresscolor/{days}', 'App\Managers\UserController:dressColor');
    $group->get('/dresscoloranti/{days}', 'App\Managers\UserController:dressColorAnti');
    $group->get('/wanpra/{wandate}', 'App\Managers\UserController:wanpra');
    $group->get('/lengyam', 'App\Managers\UserController:lengyamList');
    $group->get('/wanspecial/{wandate}', 'App\Managers\UserController:wanspecial');
    $group->get('/bagcolor/{memberid}/{age1}/{age2}', 'App\Managers\UserController:bagColor');
    $group->post('/update', 'App\Managers\UserController:memberUpdate');
    $group->post('/register/successcf', 'App\Managers\UserController:successInsertConfirm');
    $group->post('/register', 'App\Managers\UserController:userRegister');
    $group->post('/register/v2', 'App\Managers\UserController:userRegisterV2');
    $group->post('/login', 'App\Managers\UserController:userLogin');
    $group->post('/vipcode', 'App\Managers\UserController:userAddVipCode');
    $group->post('/updateToken', 'App\Managers\UserController:updateFcmToken');
});

$app->get('/', function ($request, $response) use ($container) {
    $db = $container->get('db');
    $sql = "SELECT * FROM articles WHERE is_published = 1 ORDER BY pin_order DESC, published_at DESC LIMIT 7";
    $stmt = $db->query($sql);
    $pinnedArticles = $stmt->fetchAll(PDO::FETCH_OBJ);

    // ดึงข้อมูลยอดดาวน์โหลดแบบ Dynamic
    $appStats = \App\Managers\PlayStoreScraper::getStats('com.numberniceic');

    return $container->get('view')->render($response, 'web_index.php', [
        'pinnedArticles' => $pinnedArticles,
        'appStats' => $appStats
    ]);
});

$app->get('/services', function ($request, $response) use ($container) {
    return $container->get('view')->render($response, 'web_services.php');
});

$app->group('/changenum', function (RouteCollectorProxy $group) {
    $group->get('/home', 'App\Managers\HomePageManager:home');
    $group->get('/phone', 'App\Managers\HomePageManager:phone');
    $group->get('/tabian', 'App\Managers\HomePageManager:tabian');
    $group->get('/namesur', 'App\Managers\HomePageManager:namesur');
    $group->get('/namenick', 'App\Managers\HomePageManager:namenick');
});

$app->group('/tambon', function (RouteCollectorProxy $group) {
    $group->get('/sunday', 'App\Managers\HomePageManager:sunday');
    $group->get('/monday', 'App\Managers\HomePageManager:monday');
    $group->get('/tuesday', 'App\Managers\HomePageManager:tuesday');
    $group->get('/wednesday', 'App\Managers\HomePageManager:wednesday');
    $group->get('/thursday', 'App\Managers\HomePageManager:thursday');
    $group->get('/friday', 'App\Managers\HomePageManager:friday');
    $group->get('/saturday', 'App\Managers\HomePageManager:saturday');
    $group->get('/rahuu', 'App\Managers\HomePageManager:rahuu');
    $group->get('/love', 'App\Managers\HomePageManager:love');
    $group->get('/chataa', 'App\Managers\HomePageManager:chataa');
});

$app->group('/person', function (RouteCollectorProxy $group) {
    $group->post('/haircut', 'App\Managers\PersonController:haircut');
});

$app->group('/phone', function (RouteCollectorProxy $group) {
    $group->get('/main/sell/all', 'App\Managers\Telephone\PhoneNumberSellManager:mainPhoneSell');
    $group->get('/main/{phoneNumber}', 'App\Managers\PhoneController:main');
    $group->get('/{phoneNumber}', 'App\Managers\Telephone\PairController:mainPhone');
});

$app->get('/shopsell/main', 'App\Managers\Telephone\PhoneNumberSellManager:mainPhoneSell');

$app->get('/tabian/main/{carid}', 'App\Managers\TabianController:main');
$app->get('/name/main/{name}/{surname}/{birthday}', 'App\Managers\NameController:main');
$app->get('/nickname/main/{nickname}/{birthday}', 'App\Managers\NickNameController:main');
$app->get('/home/main/{homeid}', 'App\Managers\HomeController:main');

$app->group('/nickname/list', function (RouteCollectorProxy $group) {
    $group->get('/{usetable}/{day}/{charx}/{prefix}', 'App\Managers\NickNameVipController:listNickNameByAll');
    $group->get('/{usetable}/{day}/{charx}/{prefix}/{lastid}', 'App\Managers\NickNameVipController:listNameVip');
});

$app->post('/admin/addnickname', 'App\Managers\AdminController:addNickName');
$app->post('/admin/addrealname', 'App\Managers\AdminController:addRealName');

$app->get('/vipcode/{vipcode}', 'App\Managers\UserController:vipCode');
$app->get('/privacy-policy', 'App\Managers\HomePageManager:privacyPolicy');
$app->get('/delete-account', 'App\Managers\HomePageManager:deleteAccount');

$app->get('/miracle', 'App\Managers\HomePageManager:miracle');
$app->get('/miracle/{day}', 'App\Managers\HomePageManager:miracleByDay');

// Web Routes
$app->group('/web', function (RouteCollectorProxy $group) use ($container) {
    // Note: closures now use $container->get()

    // API for username check
    $group->get('/api/check-username', function ($request, $response, $args) use ($container) {
        $params = $request->getQueryParams();
        $username = $params['username'] ?? '';

        $sql = "SELECT username FROM membertb WHERE username = :username";
        $stmt = $container->get('db')->prepare($sql);
        $stmt->execute([':username' => $username]);

        $exists = $stmt->rowCount() > 0;
        $response->getBody()->write(json_encode(['exists' => $exists]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('/login', function ($request, $response, $args) use ($container) {
        return $container->get('view')->render($response, 'web_login.php', []);
    });

    $group->post('/login', function ($request, $response, $args) use ($container) {
        $body = $request->getParsedBody();
        $username = filter_var($body['username'] ?? '', FILTER_SANITIZE_STRING);
        $password = filter_var($body['password'] ?? '', FILTER_SANITIZE_STRING);

        $sql = "SELECT * FROM membertb WHERE username = :username AND password = :password";
        $stmt = $container->get('db')->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user) {
            $_SESSION['user'] = $user;
            return $container->get('view')->render($response, 'web_dashboard.php', ['user' => $user]);
        } else {
            return $container->get('view')->render($response, 'web_login.php', ['error' => 'Invalid username or password']);
        }
    });

    $group->get('/register', function ($request, $response, $args) use ($container) {
        return $container->get('view')->render($response, 'web_register.php', []);
    });

    $group->post('/register', function ($request, $response, $args) use ($container) {
        $body = $request->getParsedBody();
        $realname = filter_var($body['realname'] ?? '', FILTER_SANITIZE_STRING);
        $surname = filter_var($body['surname'] ?? '', FILTER_SANITIZE_STRING);
        $username = filter_var($body['username'] ?? '', FILTER_SANITIZE_STRING);
        $password = filter_var($body['password'] ?? '', FILTER_SANITIZE_STRING);

        $sql = "SELECT username FROM membertb WHERE username = :username";
        $stmt = $container->get('db')->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $container->get('view')->render($response, 'web_register.php', ['error' => 'Username already exists']);
        }

        $sql = "INSERT INTO membertb (realname, surname, username, password, status, vipcode) VALUES (:realname, :surname, :username, :password, 'active', 'normal')";
        $stmt = $container->get('db')->prepare($sql);
        $stmt->bindParam(':realname', $realname);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            return $container->get('view')->render($response, 'web_login.php', ['error' => 'Registration successful! Please login.']);
        } else {
            return $container->get('view')->render($response, 'web_register.php', ['error' => 'Registration failed. Try again.']);
        }
    });

    $group->get('/dashboard', function ($request, $response, $args) use ($container) {
        if (!isset($_SESSION['user'])) {
            return $response->withHeader('Location', '/web/login')->withStatus(302);
        }
        return $container->get('view')->render($response, 'web_dashboard.php', ['user' => $_SESSION['user']]);
    });

    $group->get('/logout', function ($request, $response, $args) {
        session_destroy();
        return $response->withHeader('Location', '/web/login')->withStatus(302);
    });

    // Admin Routes
    $group->group('/admin', function (RouteCollectorProxy $adminGroup) use ($container) {

        $ensureAdmin = function () {
            if (!isset($_SESSION['user']) || (strtolower($_SESSION['user']->vipcode) !== 'admin' && strtolower($_SESSION['user']->vipcode) !== 'administrator')) {
                return false;
            }
            return true;
        };

        $adminGroup->get('/users', function ($request, $response) use ($container, $ensureAdmin) {
            if (!$ensureAdmin())
                return $response->withHeader('Location', '/web/login')->withStatus(302);

            $sql = "SELECT memberid, username, realname, surname, status, vipcode, birthday FROM membertb ORDER BY memberid DESC LIMIT 100";
            $stmt = $container->get('db')->query($sql);
            $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $container->get('view')->render($response, 'web_admin_users.php', ['users' => $users, 'user' => $_SESSION['user']]);
        });

        $adminGroup->get('/users/{id}', function ($request, $response, $args) use ($container, $ensureAdmin) {
            if (!$ensureAdmin())
                return $response->withHeader('Location', '/web/login')->withStatus(302);

            $id = $args['id'];
            $sql = "SELECT * FROM membertb WHERE memberid = :id";
            $stmt = $container->get('db')->prepare($sql);
            $stmt->execute([':id' => $id]);
            $editUser = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$editUser)
                return $response->withHeader('Location', '/web/admin/users')->withStatus(302);

            return $container->get('view')->render($response, 'web_admin_user_edit.php', ['editUser' => $editUser, 'user' => $_SESSION['user']]);
        });

        $adminGroup->post('/users/{id}', function ($request, $response, $args) use ($container, $ensureAdmin) {
            if (!$ensureAdmin())
                return $response->withHeader('Location', '/web/login')->withStatus(302);

            $id = $args['id'];
            $body = $request->getParsedBody();
            $status = filter_var($body['status'], FILTER_SANITIZE_STRING);
            $birthday = filter_var($body['birthday'], FILTER_SANITIZE_STRING);
            $vipcode = filter_var($body['vipcode'], FILTER_SANITIZE_STRING);

            $sql = "UPDATE membertb SET status = :status, birthday = :birthday, vipcode = :vipcode WHERE memberid = :id";
            $stmt = $container->get('db')->prepare($sql);
            $stmt->execute([':status' => $status, ':birthday' => $birthday, ':vipcode' => $vipcode, ':id' => $id]);

            return $response->withHeader('Location', '/web/admin/users')->withStatus(302);
        });


        // API List Images
        $adminGroup->get('/api/images', function ($request, $response) {
            $dir = __DIR__ . '/../public/uploads';
            $images = [];
            if (is_dir($dir)) {
                $files = scandir($dir);
                foreach ($files as $f) {
                    if ($f !== '.' && $f !== '..' && !is_dir("$dir/$f")) {
                        if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $f)) {
                            $images[] = $f;
                        }
                    }
                }
            }
            $response->getBody()->write(json_encode($images));
            return $response->withHeader('Content-Type', 'application/json');
        });

        // Send Notifications

        // ----------------------------------------------------
        // IMAGE LIBRARY ROUTES
        // ----------------------------------------------------

        // 1. View Library
        $adminGroup->get('/images', function ($request, $response) use ($container) {
            if (session_status() == PHP_SESSION_NONE)
                session_start();

            // Check Admin
            if (!isset($_SESSION['user']) || (!in_array(strtolower($_SESSION['user']->vipcode ?? ''), ['admin', 'administrator']))) {
                return $response->withHeader('Location', '/web/login')->withStatus(302);
            }

            $dir = __DIR__ . '/../public/uploads';
            $images = [];

            // ⚡ Bolt: Optimized image loading to prevent N+1 I/O bottleneck.
            // Using glob to find all images directly, reducing filesystem calls.
            // The previous implementation used scandir() and then checked each file,
            // resulting in excessive stat calls (filesize, filemtime) inside a loop.
            // This is significantly faster for directories with many files.
            // The glob pattern is expanded to be case-insensitive to match files with extensions like .JPG or .PNG.
            $image_files = glob($dir . '/*.{jpg,JPG,jpeg,JPEG,png,PNG,gif,GIF,webp,WEBP}', GLOB_BRACE) ?: [];

            foreach ($image_files as $file) {
                // Using stat() is more efficient than calling filesize() and filemtime() separately.
                $stat = stat($file);
                $images[] = [
                    'name' => basename($file),
                    'url' => '/uploads/' . basename($file),
                    'size' => $stat['size'],
                    'time' => $stat['mtime']
                ];
            }
            // Sort by Date (Newest first)
            usort($images, function ($a, $b) {
                return $b['time'] - $a['time'];
            });

            $view = new \Slim\Views\PhpRenderer(__DIR__ . '/../views');
            return $view->render($response, 'web_admin_images.php', ['images' => $images]);
        });

        // 2. Upload Image
        $adminGroup->post('/images/upload', function ($request, $response) {
            $directory = __DIR__ . '/../public/uploads';
            $uploadedFiles = $request->getUploadedFiles();
            $uploadedFile = $uploadedFiles['image'] ?? null;

            if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
                $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                // Random name to prevent conflicts and encoding issues
                $basename = bin2hex(random_bytes(8));
                $filename = sprintf('%s.%s', $basename, $extension);
                $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
            }
            return $response->withHeader('Location', '/web/admin/images')->withStatus(302);
        });

        // 3. Delete Image
        $adminGroup->get('/images/delete/{name}', function ($request, $response, $args) {
            // Check Admin logic again if strict security needed, but assuming admin group middleware handles basic auth context
            // Better to re-check inside sensitive op
            if (session_status() == PHP_SESSION_NONE)
                session_start();
            if (!isset($_SESSION['user']) || (!in_array(strtolower($_SESSION['user']->vipcode ?? ''), ['admin', 'administrator']))) {
                die("Access Denied");
            }

            $name = $args['name'];
            // Prevent Path Traversal
            if (strpos($name, '/') !== false || strpos($name, '\\') !== false || strpos($name, '..') !== false) {
                die("Invalid filename");
            }

            $path = __DIR__ . '/../public/uploads/' . $name;
            if (file_exists($path)) {
                unlink($path);
            }
            return $response->withHeader('Location', '/web/admin/images')->withStatus(302);
        });

        // 4. Image Delete Confirmation
        // 4. Image Delete Confirmation
        $adminGroup->get('/images/confirm-delete', function ($request, $response, $args) use ($container) {
            if (session_status() == PHP_SESSION_NONE)
                session_start();
            if (!isset($_SESSION['user']) || (!in_array(strtolower($_SESSION['user']->vipcode ?? ''), ['admin', 'administrator']))) {
                return $response->withHeader('Location', '/web/login')->withStatus(302);
            }

            $name = $request->getQueryParams()['name'] ?? '';
            if (empty($name)) {
                return $response->withHeader('Location', '/web/admin/images')->withStatus(302);
            }

            $imageUrl = '/uploads/' . $name;

            echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";
            echo "<h2 style='color:red;'>ยืนยันการลบรูปภาพ (Confirm Delete Image)</h2>";
            echo "<div style='text-align:center;'><img src='$imageUrl' style='max-height:300px; max-width:90%; border:1px solid #ddd; margin:20px; box-shadow:0 0 10px rgba(0,0,0,0.1); border-radius:8px;'></div>";
            echo "<h3 style='color:#555;'>$name</h3>";
            echo "<div style='margin-top:30px;'>";
            echo "<a href='/web/admin/images/exec-delete?name=" . urlencode($name) . "' style='background:#dc3545; color:white; padding:15px 30px; text-decoration:none; border-radius:5px; font-weight:bold;'>ยืนยัน ลบรูปนี้ (Yes, Delete)</a>";
            echo "&nbsp;&nbsp;&nbsp;";
            echo "<a href='/web/admin/images' style='background:#6c757d; color:white; padding:15px 30px; text-decoration:none; border-radius:5px; font-weight:bold;'>ยกเลิก (Cancel)</a>";
            echo "</div></div>";
            return $response;
        });

        // 5. Image Delete Execution
        // 5. Image Delete Execution
        $adminGroup->get('/images/exec-delete', function ($request, $response, $args) {
            if (session_status() == PHP_SESSION_NONE)
                session_start();
            if (!isset($_SESSION['user']) || (!in_array(strtolower($_SESSION['user']->vipcode ?? ''), ['admin', 'administrator']))) {
                die("Access Denied");
            }

            $name = $request->getQueryParams()['name'] ?? '';
            if (empty($name))
                die("Invalid filename");

            if (strpos($name, '/') !== false || strpos($name, '\\') !== false || strpos($name, '..') !== false) {
                die("Invalid filename");
            }

            $path = __DIR__ . '/../public/uploads/' . $name;
            if (file_exists($path)) {
                unlink($path);
            }
            return $response->withHeader('Location', '/web/admin/images')->withStatus(302);
        });



        // ----------------------------------------------------
        // BAG COLOR MANAGEMENT ROUTES
        // ----------------------------------------------------

        // 1. Search Users for Bag Color
        $adminGroup->get('/bag-colors', function ($request, $response) use ($container, $ensureAdmin) {
            if (!$ensureAdmin())
                return $response->withHeader('Location', '/web/login')->withStatus(302);

            $search = $_GET['search'] ?? '';
            $users = [];

            if ($search) {
                // Search by id, username, or realname
                $sql = "SELECT memberid, username, realname, surname, birthday FROM membertb WHERE username LIKE :s OR realname LIKE :s OR surname LIKE :s OR memberid = :exact LIMIT 50";
                $stmt = $container->get('db')->prepare($sql);
                // Handle exact match for ID slightly differently or just loose match
                $stmt->execute([':s' => "%$search%", ':exact' => $search]);
                $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            } else {
                // Default: Show latest 50 users
                $sql = "SELECT memberid, username, realname, surname, birthday FROM membertb ORDER BY memberid DESC LIMIT 50";
                $stmt = $container->get('db')->query($sql);
                $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            }

            return $container->get('view')->render($response, 'web_admin_bag_colors.php', [
                'user' => $_SESSION['user'],
                'users' => $users,
                'search' => $search
            ]);
        });

        // 2. Edit Bag Color for User
        $adminGroup->get('/bag-colors/edit/{memberid}', function ($request, $response, $args) use ($container, $ensureAdmin) {
            if (!$ensureAdmin())
                return $response->withHeader('Location', '/web/login')->withStatus(302);

            $memberid = $args['memberid'];

            // Get User Info
            $stmt = $container->get('db')->prepare("SELECT * FROM membertb WHERE memberid = :id");
            $stmt->execute([':id' => $memberid]);
            $targetUser = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$targetUser) {
                return $response->withHeader('Location', '/web/admin/bag-colors')->withStatus(302);
            }

            // Get Existing Bag Colors
            $stmt = $container->get('db')->prepare("SELECT * FROM bagcolortb WHERE memberid = :id ORDER BY CAST(age as UNSIGNED) ASC");
            $stmt->execute([':id' => $memberid]);
            $bagColors = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $container->get('view')->render($response, 'web_admin_bag_color_edit.php', [
                'user' => $_SESSION['user'],
                'targetUser' => $targetUser,
                'bagColors' => $bagColors
            ]);
        });

        // 3. Save Bag Color
        $adminGroup->post('/bag-colors/save', function ($request, $response) use ($container, $ensureAdmin) {
            if (!$ensureAdmin())
                return $response->withHeader('Location', '/web/login')->withStatus(302);

            $body = $request->getParsedBody();
            $memberid = $body['memberid'];
            $age = $body['age'];

            // Colors 1-4
            $c1 = $body['c1'] ?? '#FFFFFF';
            $c2 = $body['c2'] ?? '#FFFFFF';
            $c3 = $body['c3'] ?? '#FFFFFF';
            $c4 = $body['c4'] ?? '#FFFFFF';
            // Colors 5-6 (Optional/Backward Compat)
            $c5 = $body['c5'] ?? '#FFFFFF';
            $c6 = $body['c6'] ?? '#FFFFFF';

            // Check exist
            $stmt = $container->get('db')->prepare("SELECT bag_id FROM bagcolortb WHERE memberid = :mid AND age = :age");
            $stmt->execute([':mid' => $memberid, ':age' => $age]);
            $exist = $stmt->fetch();

            if ($exist) {
                $sql = "UPDATE bagcolortb SET bag_color1=:c1, bag_color2=:c2, bag_color3=:c3, bag_color4=:c4, bag_color5=:c5, bag_color6=:c6 WHERE bag_id=:bid";
                $stmt = $container->get('db')->prepare($sql);
                $stmt->execute([':c1' => $c1, ':c2' => $c2, ':c3' => $c3, ':c4' => $c4, ':c5' => $c5, ':c6' => $c6, ':bid' => $exist['bag_id']]);
            } else {
                $sql = "INSERT INTO bagcolortb (memberid, age, bag_color1, bag_color2, bag_color3, bag_color4, bag_color5, bag_color6, bag_desc) VALUES (:mid, :age, :c1, :c2, :c3, :c4, :c5, :c6, 'active')";
                $stmt = $container->get('db')->prepare($sql);
                $stmt->execute([':mid' => $memberid, ':age' => $age, ':c1' => $c1, ':c2' => $c2, ':c3' => $c3, ':c4' => $c4, ':c5' => $c5, ':c6' => $c6]);
            }

            return $response->withHeader('Location', "/web/admin/bag-colors/edit/$memberid")->withStatus(302);
        });

        // Articles Management Routes
        $adminGroup->get('/articles', function ($request, $response) use ($container, $ensureAdmin) {
            if (!$ensureAdmin())
                return $response->withHeader('Location', '/web/login')->withStatus(302);

            $sql = "SELECT * FROM articles ORDER BY art_id DESC";
            $stmt = $container->get('db')->query($sql);
            $articles = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $container->get('view')->render($response, 'web_admin_articles.php', ['articles' => $articles, 'user' => $_SESSION['user']]);
        });

        $adminGroup->get('/articles/create', function ($request, $response) use ($container, $ensureAdmin) {
            if (!$ensureAdmin())
                return $response->withHeader('Location', '/web/login')->withStatus(302);
            return $container->get('view')->render($response, 'web_admin_article_form.php', ['user' => $_SESSION['user']]);
        });

        $adminGroup->post('/articles/create', function ($request, $response) use ($container, $ensureAdmin) {
            if (!$ensureAdmin())
                return $response->withHeader('Location', '/web/login')->withStatus(302);

            $body = $request->getParsedBody();
            $title = $body['title'] ?? '';
            $slug = $body['slug'] ?? '';
            if (empty($slug))
                $slug = uniqid('art_'); // Fallback slug
            $excerpt = $body['excerpt'] ?? '';
            $category = $body['category'] ?? '';
            $content = $body['content'] ?? '';
            $is_published = isset($body['is_published']) ? 1 : 0;
            $published_at = !empty($body['published_at']) ? $body['published_at'] : date('Y-m-d H:i:s');
            $title_short = $body['title_short'] ?? '';
            $image_url = $body['image_url'] ?? '';

            $sql = "INSERT INTO articles (slug, title, excerpt, category, content, is_published, published_at, title_short, image_url) VALUES (:slug, :title, :excerpt, :category, :content, :is_published, :published_at, :title_short, :image_url)";
            $stmt = $container->get('db')->prepare($sql);
            $stmt->execute([
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
            return $response->withHeader('Location', '/web/admin/articles')->withStatus(302);
        });

        $adminGroup->get('/articles/{id}', function ($request, $response, $args) use ($container, $ensureAdmin) {
            if (!$ensureAdmin())
                return $response->withHeader('Location', '/web/login')->withStatus(302);
            $id = $args['id'];
            $stmt = $container->get('db')->prepare("SELECT * FROM articles WHERE art_id = :id");
            $stmt->execute([':id' => $id]);
            $article = $stmt->fetch(PDO::FETCH_OBJ);
            if (!$article)
                return $response->withHeader('Location', '/web/admin/articles')->withStatus(302);

            return $container->get('view')->render($response, 'web_admin_article_form.php', ['article' => $article, 'user' => $_SESSION['user']]);
        });

        $adminGroup->post('/articles/{id}', function ($request, $response, $args) use ($container, $ensureAdmin) {
            if (!$ensureAdmin())
                return $response->withHeader('Location', '/web/login')->withStatus(302);
            $id = $args['id'];
            $body = $request->getParsedBody();

            $title = $body['title'] ?? '';
            $slug = $body['slug'] ?? '';
            $excerpt = $body['excerpt'] ?? '';
            $category = $body['category'] ?? '';
            $content = $body['content'] ?? '';
            $is_published = isset($body['is_published']) ? 1 : 0;
            $title_short = $body['title_short'] ?? '';
            $image_url = $body['image_url'] ?? '';

            $sql = "UPDATE articles SET slug=:slug, title=:title, excerpt=:excerpt, category=:category, content=:content, is_published=:is_published, title_short=:title_short, image_url=:image_url WHERE art_id=:id";
            $stmt = $container->get('db')->prepare($sql);
            $stmt->execute([
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
            return $response->withHeader('Location', '/web/admin/articles')->withStatus(302);
        });

        $adminGroup->get('/article-delete-action/{id}', function ($request, $response, $args) use ($container, $ensureAdmin) {
            // Debugging
            $id = $args['id'];
            echo "<h1>Debug Delete</h1>";
            echo "Deleting Article ID: " . htmlspecialchars($id) . "<br>";

            if (!$ensureAdmin()) {
                echo "Error: Not Admin or Logged out.<br>";
                // return $response->withHeader('Location', '/web/login')->withStatus(302);
                die();
            }

            try {
                $stmt = $container->get('db')->prepare("DELETE FROM articles WHERE art_id = :id");
                $stmt->execute([':id' => $id]);
                $count = $stmt->rowCount();

                if ($count > 0) {
                    echo "Success: Deleted $count row(s).<br>";
                } else {
                    echo "Warning: No rows deleted. ID might not exist.<br>";
                }
            } catch (Exception $e) {
                echo "Error Exception: " . $e->getMessage();
            }

            echo "<br><a href='/web/admin/articles'>Back to List</a>";
            return $response;
        });

        // Send Bag Color Notification (for web admin)
        $adminGroup->get('/notifications/send-bag-colors', 'App\Managers\NotificationController:sendBagColors');

    });

});


// ----------------------------------------------------
// DELETE CONFIRMATION & EXECUTION ROUTES (Root Level)
// ----------------------------------------------------

// 1. Confirm Page
$app->get('/web/admin/confirm-delete/{id}', function ($request, $response, $args) use ($container) {
    if (session_status() == PHP_SESSION_NONE)
        session_start();
    $id = $args['id'];

    // Check Admin
    if (!isset($_SESSION['user']) || (!in_array(strtolower($_SESSION['user']->vipcode ?? ''), ['admin', 'administrator']))) {
        return $response->withHeader('Location', '/web/login')->withStatus(302);
    }

    // Get Article Info for better confirm message
    $stmt = $container->get('db')->prepare("SELECT title FROM articles WHERE art_id = :id");
    $stmt->execute([':id' => $id]);
    $art = $stmt->fetch(PDO::FETCH_OBJ);
    $title = $art ? $art->title : "Unknown Article";

    echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px; padding:20px;'>";
    echo "<div style='border:1px solid #ccc; max-width:600px; margin:0 auto; padding:40px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1);'>";
    echo "<h1 style='color:#dc3545; margin-bottom:20px;'>ยืนยันการลบ (Confirm Deletion)</h1>";
    echo "<h3 style='margin-bottom:10px;'>บทความ ID: $id</h3>";
    echo "<h2 style='color:#333; margin-bottom:30px;'>Title: " . htmlspecialchars($title) . "</h2>";
    echo "<p style='color:#666; margin-bottom:40px;'>คุณแน่ใจหรือไม่ที่จะลบ? การกระทำนี้ไม่สามารถย้อนกลับได้</p>";

    echo "<a href='/web/admin/exec-delete/$id' style='background:#dc3545; color:white; padding:12px 30px; text-decoration:none; font-size:18px; border-radius:5px; margin-right:20px;'>ยืนยันลบ (Confirm Delete)</a>";
    echo "<a href='/web/admin/articles' style='background:#6c757d; color:white; padding:12px 30px; text-decoration:none; font-size:18px; border-radius:5px;'>ยกเลิก (Cancel)</a>";
    echo "</div></div>";

    return $response;
});

// 2. Execute Page
// ----------------------------------------------------
// PUBLIC ARTICLE ROUTES
// ----------------------------------------------------

$app->get('/articles', function ($request, $response) use ($container) {
    $db = $container->get('db');
    $sql = "SELECT * FROM articles WHERE is_published = 1 ORDER BY published_at DESC";
    $stmt = $db->query($sql);
    $articles = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $container->get('view')->render($response, 'web_articles.php', [
        'articles' => $articles
    ]);
});

$app->get('/articles/{slug}', function ($request, $response, $args) use ($container) {
    $db = $container->get('db');
    $slug = $args['slug'];

    $sql = "SELECT * FROM articles WHERE slug = :slug AND is_published = 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([':slug' => $slug]);
    $article = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$article) {
        return $response->withHeader('Location', '/articles')->withStatus(302);
    }

    return $container->get('view')->render($response, 'web_article_detail.php', [
        'article' => $article
    ]);
});

$app->get('/web/admin/exec-delete/{id}', function ($request, $response, $args) use ($container) {

    if (session_status() == PHP_SESSION_NONE)
        session_start();
    $id = $args['id'];

    // Check Admin
    if (!isset($_SESSION['user']) || (!in_array(strtolower($_SESSION['user']->vipcode ?? ''), ['admin', 'administrator']))) {
        die("Access Denied");
    }

    try {
        $stmt = $container->get('db')->prepare("DELETE FROM articles WHERE art_id = :id");
        $stmt->execute([':id' => $id]);
        $count = $stmt->rowCount();

        if ($count > 0) {
            // Success - Redirect immediately
            return $response->withHeader('Location', '/web/admin/articles')->withStatus(302);
        } else {
            echo "<h1>Error</h1>";
            echo "Could not delete ID $id. It may not exist.<br>";
            echo "<a href='/web/admin/articles'>Back</a>";
        }
    } catch (Exception $e) {
        echo "Database Error: " . $e->getMessage();
    }
    return $response;
});
