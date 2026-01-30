<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ananya</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: sans-serif;
            background-color: #f0f2f5;
            margin: 0;
        }

        .main-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 80px);
            padding: 2rem;
        }

        .container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #3CA7E6;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1rem;
        }

        button:hover {
            background-color: #2b8ac4;
        }

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
                    <input type="text" id="username" name="username" required autocomplete="username">
                </div>
                <div class="form-group">
                    <label for="password">รหัสผ่าน</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" required autocomplete="current-password" style="padding-right: 40px;">
                        <button type="button" id="togglePassword" aria-label="แสดงรหัสผ่าน"
                            style="position: absolute; right: 0; top: 0; height: 100%; width: 40px; margin: 0; background: none; color: #666; border: none; cursor: pointer;">
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
        document.getElementById('togglePassword').addEventListener('click', function() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            const isPass = pwd.type === 'password';
            pwd.type = isPass ? 'text' : 'password';
            icon.className = isPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
            this.setAttribute('aria-label', isPass ? 'ซ่อนรหัสผ่าน' : 'แสดงรหัสผ่าน');
        });
    </script>
</body>

</html>