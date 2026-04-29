<?php
session_start();
error_reporting(0);

$host = "localhost";
$user = "root";
$pass = "";
$db_name = "smart_quran_school_ossrati";
$port = 3307;

$conn = mysqli_connect($host, $user, $pass, $db_name, $port);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u_name = mysqli_real_escape_string($conn, $_POST['u_name']);
    $u_pass = $_POST['u_pass'];

    // 1. البحث عن المستخدم بالاسم الكامل
    $query = "SELECT * FROM users WHERE full_name = '$u_name'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        // 2. التحقق من كلمة السر أولاً
        if (password_verify($u_pass, $row['password'])) {
            
            // 3. كلمة السر صحيحة.. الآن نتحقق من الحالة (Status)
            if ($row['status'] == 'active') {
                // الحالة: مفعل (الترحيب في مدرسة أسرتي)
                $_SESSION['user_name'] = $row['full_name'];
                $_SESSION['user_status'] = 'active';
                header("Location: dashboard.php"); // سيتوجه للوحة الترحيب تلقائياً
                exit();
            } else {
                // الحالة: غير مفعل (لم يسدد الاشتراك)
                showCard("Subscription Required", "Your account is pending. Please complete your subscription payment to access the school.", "#e67e22", "✕");
            }

        } else {
            // الحالة: كلمة السر خطأ
            showCard("Invalid Password", "The password you entered is incorrect. Please try again.", "#dc3545", "✕");
        }
    } else {
        // الحالة: الاسم غير موجود أصلاً
        showCard("User Not Found", "This name is not registered in our school records.", "#dc3545", "✕");
    }
}

// دالة عرض البطاقات الإنجليزية الأنيقة
function showCard($title, $message, $color, $icon) {
    echo "
    <div style='font-family:\"Segoe UI\", sans-serif; background:#f0f4f8; display:flex; justify-content:center; align-items:center; height:100vh; margin:0;'>
        <div style='background:white; padding:40px; border-radius:20px; text-align:center; border-top:10px solid $color; width:400px; box-shadow:0 10px 30px rgba(0,0,0,0.1);'>
            <div style='background:$color; color:white; width:60px; height:60px; line-height:60px; border-radius:50%; font-size:30px; margin:0 auto 20px;'>$icon</div>
            <h2 style='color:$color; margin-bottom:10px;'>$title</h2>
            <p style='color:#64748b; line-height:1.6;'>$message</p>
            <a href='login.php' style='display:inline-block; margin-top:25px; padding:12px 30px; background:$color; color:white; text-decoration:none; border-radius:8px; font-weight:bold;'>Return to Login</a>
        </div>
    </div>";
}
?>