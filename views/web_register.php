<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Ananya</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&family=Sarabun:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
        }

        h2, button, label {
            font-family: 'Kanit', sans-serif;
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
            transition: border-color 0.2s;
        }

        input:focus-visible, button:focus-visible {
            outline: 2px solid #3CA7E6;
            outline-offset: 2px;
        }

        .password-container {
            position: relative;
        }

        .password-input {
            padding-right: 2.5rem !important;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            width: auto !important;
            padding: 4px !important;
            margin: 0 !important;
            display: flex;
            align-items: center;
            justify-content: center;
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
            <h2>สมัครสมาชิก</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-error" style="display: block;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form action="/web/register" method="POST" id="registerForm">
                <div class="form-group">
                    <label for="realname">ชื่อจริง</label>
                    <input type="text" id="realname" name="realname" required>
                </div>
                <div class="form-group">
                    <label for="surname">นามสกุล</label>
                    <input type="text" id="surname" name="surname" required>
                </div>
                <div class="form-group">
                    <label for="username">ชื่อผู้ใช้</label>
                    <input type="text" id="username" name="username" required>
                    <small id="username-error"
                        style="color: #dc2626; display: none; font-size: 0.85rem; margin-top: 4px;">ชื่อผู้ใช้นี้ถูกใช้แล้ว</small>
                    <small id="username-success"
                        style="color: #28a745; display: none; font-size: 0.85rem; margin-top: 4px;">ชื่อผู้ใช้นี้ว่าง</small>
                </div>
                <div class="form-group">
                    <label for="password">รหัสผ่าน</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" class="password-input" required>
                        <button type="button" class="toggle-password" data-target="password" aria-label="แสดงรหัสผ่าน">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">ยืนยันรหัสผ่าน</label>
                    <div class="password-container">
                        <input type="password" id="confirm_password" name="confirm_password" class="password-input" required>
                        <button type="button" class="toggle-password" data-target="confirm_password" aria-label="แสดงรหัสผ่าน">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <small id="password-error"
                        style="color: #dc2626; display: none; font-size: 0.85rem; margin-top: 4px;">รหัสผ่านไม่ตรงกัน</small>
                </div>
                <button type="submit">สมัครสมาชิก</button>
            </form>
            <div class="link">
                มีบัญชีแล้ว? <a href="/web/login">เข้าสู่ระบบ</a>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = this.querySelector('i');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                    this.setAttribute('aria-label', 'ซ่อนรหัสผ่าน');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                    this.setAttribute('aria-label', 'แสดงรหัสผ่าน');
                }
            });
        });

        const form = document.getElementById('registerForm');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');
        const usernameError = document.getElementById('username-error');
        const usernameSuccess = document.getElementById('username-success');
        const passwordError = document.getElementById('password-error');

        usernameInput.addEventListener('blur', function () {
            const val = this.value.trim();
            if (val.length === 0) return;

            fetch('/web/api/check-username?username=' + encodeURIComponent(val))
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        usernameError.style.display = 'block';
                        usernameSuccess.style.display = 'none';
                        usernameInput.style.borderColor = '#dc2626';
                    } else {
                        usernameError.style.display = 'none';
                        usernameSuccess.style.display = 'block';
                        usernameInput.style.borderColor = '#28a745';
                    }
                })
                .catch(err => console.error(err));
        });

        form.addEventListener('submit', function (e) {
            let valid = true;

            // Check passwords
            if (passwordInput.value !== confirmInput.value) {
                passwordError.style.display = 'block';
                confirmInput.style.borderColor = '#dc2626';
                valid = false;
            } else {
                passwordError.style.display = 'none';
            }

            if (!valid) e.preventDefault();
        });

        confirmInput.addEventListener('input', () => {
            if (passwordInput.value === confirmInput.value) {
                passwordError.style.display = 'none';
                confirmInput.style.borderColor = '#28a745';
            } else {
                confirmInput.style.borderColor = '#ddd';
            }
        });
    </script>
</body>

</html>