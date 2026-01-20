<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bag Color Management - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Kanit', sans-serif;
            background-color: #f3f4f6;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Navbar */
        nav {
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .nav-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            padding: 1rem 0.5rem;
            text-decoration: none;
        }

        .nav-logo img {
            height: 2rem;
            width: 2rem;
            margin-right: 0.5rem;
        }

        .nav-logo span {
            font-weight: 600;
            color: #6b7280;
            font-size: 1.125rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .nav-link {
            padding: 0.5rem;
            font-weight: 500;
            color: #6b7280;
            text-decoration: none;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #10b981;
        }

        .nav-link.active {
            color: #10b981;
            border-bottom: 4px solid #10b981;
        }

        .nav-link.logout {
            color: white;
            background-color: #ef4444;
            border-radius: 0.25rem;
            padding: 0.5rem;
        }

        .nav-link.logout:hover {
            background-color: #f87171;
        }

        /* Main Content */
        .py-8 {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        h1 {
            font-size: 1.875rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        h2 {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        /* Card */
        .card {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        /* Search Form */
        .search-form {
            display: flex;
            gap: 1rem;
        }

        .search-input {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
        }

        .search-input:focus {
            outline: none;
            border-color: #10b981;
        }

        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 0.25rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-green {
            background-color: #059669;
            color: white;
        }

        .btn-green:hover {
            background-color: #047857;
        }

        .btn-blue {
            background-color: #3b82f6;
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            display: inline-block;
            text-decoration: none;
        }

        .btn-blue:hover {
            background-color: #2563eb;
        }

        /* Table */
        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f3f4f6;
        }

        th {
            padding: 0.75rem 1.25rem;
            border-bottom: 2px solid #e5e7eb;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            padding: 1.25rem;
            border-bottom: 1px solid #e5e7eb;
            background-color: white;
            font-size: 0.875rem;
        }

        td.name {
            color: #111827;
            font-weight: 500;
        }

        .text-center {
            text-align: center;
            padding: 2.5rem;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav>
        <div class="nav-container">
            <div class="nav-flex">
                <a href="/web/dashboard" class="nav-logo">
                    <img src="/loggo.gif" alt="Logo">
                    <span>Admin Panel</span>
                </a>
                <div class="nav-links">
                    <a href="/web/admin/users" class="nav-link">Users</a>
                    <a href="/web/admin/bag-colors" class="nav-link active">Bag Colors</a>
                    <a href="/web/admin/articles" class="nav-link">Articles</a>
                    <a href="/web/logout" class="nav-link logout">Log Out</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-8">
        <h1>จัดการสีกระเป๋ามงคล (Bag Color Management)</h1>

        <!-- Search Form -->
        <div class="card">
            <form action="/web/admin/bag-colors" method="GET" class="search-form">
                <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>"
                    placeholder="ค้นหาด้วย ชื่อ, Username, หรือ User ID..." class="search-input">
                <button type="submit" class="btn btn-green">ค้นหา</button>
            </form>
        </div>

        <!-- Result Table -->
        <h2>
            <?= !empty($search) ? "ผลการค้นหา: " . htmlspecialchars($search) : "รายชื่อผู้ใช้งานล่าสุด (50 รายการ)" ?>
        </h2>
        <?php if (!empty($users)): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Birthday</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= $u->memberid ?></td>
                                <td><?= htmlspecialchars($u->username) ?></td>
                                <td class="name">
                                    <?= htmlspecialchars($u->realname) ?>
                                    <?= htmlspecialchars($u->surname ?? '') ?>
                                </td>
                                <td><?= htmlspecialchars($u->birthday ?? '-') ?></td>
                                <td>
                                    <a href="/web/admin/bag-colors/edit/<?= $u->memberid ?>" class="btn-blue">Manage Colors</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif (isset($_GET['search'])): ?>
            <div class="text-center">ไม่พบ User ที่ค้นหา</div>
        <?php else: ?>
            <div class="text-center">กรุณาค้นหา User เพื่อจัดการสีกระเป๋า</div>
        <?php endif; ?>

    </div>

</body>

</html>