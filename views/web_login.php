<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ananya</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&family=Sarabun:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
        }

        h2 { font-family: 'Kanit', sans-serif; text-align: center; margin-bottom: 1.5rem; color: #333; }
        .main-wrapper { display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 80px); padding: 2rem; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
        }

        input { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-family: 'Sarabun', sans-serif; transition: border-color 0.2s; }
        input:focus-visible, button:focus-visible { outline: 2px solid #3CA7E6; outline-offset: 1px; }
        .password-wrapper { position: relative; }
        #password { padding-right: 2.5rem; }
        .password-toggle { position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #666; display: flex; align-items: center; transition: color 0.2s; }
        .password-toggle:hover { color: #3CA7E6; }
        button[type="submit"] { width: 100%; padding: 0.75rem; background-color: #3CA7E6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; margin-top: 1rem; font-family: 'Kanit', sans-serif; transition: background-color 0.2s; }
        button[type="submit"]:hover { background-color: #2b8ac4; }

        .alert {
            padding: 0.75rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            display: none;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .link a {
            color: #3CA7E6;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <?php include 'web_menu.php'; ?>
    <div class="main-wrapper">
        <div class="container">
            <h2>เข้าสู่ระบบ</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-error" style="display: block;"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="/web/login" method="POST">
                <div class="form-group">
                    <label for="username">ชื่อผู้ใช้</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">รหัสผ่าน</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                        <button type="button" class="password-toggle" id="togglePassword" aria-label="แสดงรหัสผ่าน">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>
                <button type="submit">เข้าสู่ระบบ</button>
            </form>
            <div class="link">
                ยังไม่มีบัญชี? <a href="/web/register">สมัครสมาชิกที่นี่</a>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const t = document.querySelector('#togglePassword'), p = document.querySelector('#password'), i = document.querySelector('#eyeIcon');
            if (t && p && i) t.addEventListener('click', () => {
                const isPass = p.type === 'password';
                p.type = isPass ? 'text' : 'password';
                i.className = isPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
                t.setAttribute('aria-label', isPass ? 'ซ่อนรหัสผ่าน' : 'แสดงรหัสผ่าน');
            });
        });
    </script>
</body>

</html>