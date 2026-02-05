<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ananya</title>
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

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input {
            padding-right: 2.5rem;
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        button[type="submit"] {
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

        button[type="submit"]:hover {
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
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">รหัสผ่าน</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <button type="button" class="toggle-password" data-target="password" aria-label="แสดงรหัสผ่าน">
                            <i class="fa-solid fa-eye"></i>
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
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-password');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                        this.setAttribute('aria-label', 'ซ่อนรหัสผ่าน');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                        this.setAttribute('aria-label', 'แสดงรหัสผ่าน');
                    }
                });
            });
        });
    </script>
</body>

</html>