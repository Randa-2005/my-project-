<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'طالب') {
    header("Location: login.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>منطقة الطالب - مدرسة أسرتي</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #e8f5e9; margin: 0; }
        .nav { background: #2e7d32; color: white; padding: 15px; text-align: center; }
        .content { padding: 50px; text-align: center; }
    </style>
</head>
<body>
    <div class="nav"><h2><i class="fas fa-user-graduate"></i> بوابة الطالب</h2></div>
    <div class="content">
        <h1>مرحباً بك يا <?php echo $_SESSION['user_name']; ?> في حلقتك القرآنية</h1>
        <p>هنا ستجد دروسك ومواعيد الحفظ الخاصة بك.</p>
    </div>
</body>
</html>