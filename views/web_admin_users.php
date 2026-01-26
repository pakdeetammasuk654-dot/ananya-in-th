<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
        }

        .main-wrapper {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin-top: 0;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #555;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .btn {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
            font-size: 0.8rem;
        }

        .bg-success {
            background-color: #28a745;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .bg-warning {
            background-color: #ffc107;
            color: black;
        }

        .bg-secondary {
            background-color: #6c757d;
        }
    </style>
</head>

<body>
    <?php include 'web_menu.php'; ?>
    <div class="main-wrapper">
        <a href="/web/dashboard" class="btn btn-back">← กลับหน้าหลัก</a>
        <div class="card">
            <h2>จัดการผู้ใช้ระบบ</h2>
            <?php include 'web_admin_toolbar.php'; ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>สถานะ</th>
                        <th>VIP</th>
                        <th>วันเกิด</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo $u->memberid; ?></td>
                            <td><?php echo htmlspecialchars($u->username); ?></td>
                            <td><?php echo htmlspecialchars($u->realname . ' ' . $u->surname); ?></td>
                            <td>
                                <span
                                    class="badge <?php echo ($u->status == 'active' || $u->status == 'activie') ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo ($u->status == 'active' || $u->status == 'activie') ? 'ใช้งาน' : $u->status; ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $vipClass = 'bg-secondary';
                                if ($u->vipcode == 'admin')
                                    $vipClass = 'bg-danger';
                                elseif ($u->vipcode != 'normal')
                                    $vipClass = 'bg-warning';
                                ?>
                                <span
                                    class="badge <?php echo $vipClass; ?>"><?php echo htmlspecialchars($u->vipcode); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($u->birthday); ?></td>
                            <td>
                                <a href="/web/admin/users/<?php echo $u->memberid; ?>" class="btn btn-edit">แก้ไข</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>