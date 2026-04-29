<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'موظف') {
    header("Location: login.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الموظف الإداري - مدرسة أسرتي</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { margin:0; font-family:'Segoe UI'; background:#f9f9f9; }
        .nav { background:#1b5e20; color:white; padding:15px; display:flex; justify-content:space-between; align-items:center; }
        .container { padding:40px; }
        .stat-box { background:white; padding:20px; border-radius:10px; border-right:8px solid #d4af37; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="nav">
        <span><i class="fas fa-user-cog"></i> قسم الشؤون الإدارية</span>
        <a href="logout.php" style="color:white; text-decoration:none;">خروج</a>
    </div>
    <div class="container">
        <div class="stat-box">
            <h2 style="color:#1b5e20;">إدارة الاشتراكات</h2>
            <p>مرحباً بك، يمكنك هنا مراجعة دفع الرسوم ومدة اشتراكات الطلاب.</p>
        </div>
    </div>
</body>
</html>