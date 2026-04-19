<?php
session_start();

// التحقق هل المستخدم سجل دخوله أم لا؟
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مرحباً بك في مدرسة أسرتي</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            background: #1b5e20 url('https://www.transparenttextures.com/patterns/arabesque.png');
        }
        .welcome-card {
            background: white; padding: 50px; border-radius: 25px; text-align: center;
            border: 3px solid #d4af37; box-shadow: 0 20px 40px rgba(0,0,0,0.5); width: 450px;
        }
        .icon-mosque { color: #1b5e20; font-size: 50px; margin-bottom: 20px; }
        h1 { color: #1b5e20; font-family: 'Amiri', serif; margin-bottom: 15px; }
        p { font-size: 18px; color: #444; line-height: 1.6; }
        .logout-btn { 
            display: inline-block; margin-top: 30px; padding: 10px 25px; 
            background: #b71c1c; color: white; text-decoration: none; 
            border-radius: 8px; font-weight: bold; 
        }
    </style>
</head>
<body>

<div class="welcome-card">
    <div class="icon-mosque"><i class="fas fa-mosque"></i></div>
    <h1>أهلاً بكَ يا <?php echo $_SESSION['user_name']; ?></h1>
    <p>مرحباً بك في رحاب <b>مدرسة أسرتي لتعليم القرآن</b>.</p>
    <p>لقد تم تفعيل حسابك بنجاح، يمكنك الآن البدء في رحلتك المباركة معنا.</p>
    
    <hr style="border: 0; border-top: 1px solid #d4af37; margin: 20px 0;">
    
    <a href="logout.php" class="logout-btn">تسجيل الخروج</a>
</div>

</body>
</html>