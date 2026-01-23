<!DOCTYPE html>
<html lang="en">

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

        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            -webkit-animation: spin 1s ease-in-out infinite;
            vertical-align: middle;
            margin-left: 8px;
        }

        @keyframes spin {
            to {
                -webkit-transform: rotate(360deg);
            }
        }

        @-webkit-keyframes spin {
            to {
                -webkit-transform: rotate(360deg);
            }
        }

        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
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
            <form id="loginForm" action="/web/login" method="POST">
                <div class="form-group">
                    <label for="username">ชื่อผู้ใช้</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">รหัสผ่าน</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" id="submitBtn" aria-busy="false">
                    <span class="btn-text">เข้าสู่ระบบ</span>
                    <span class="spinner" style="display: none;"></span>
                </button>
            </form>
            <div class="link">
                ยังไม่มีบัญชี? <a href="/web/register">สมัครสมาชิกที่นี่</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            var submitBtn = document.getElementById('submitBtn');
            var btnText = submitBtn.querySelector('.btn-text');
            var spinner = submitBtn.querySelector('.spinner');

            submitBtn.disabled = true;
            submitBtn.setAttribute('aria-busy', 'true');
            btnText.classList.add('visually-hidden');
            spinner.style.display = 'inline-block';
        });
    </script>
</body>

</html>