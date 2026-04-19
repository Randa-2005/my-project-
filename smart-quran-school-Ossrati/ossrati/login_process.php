<?php
session_start();
include 'db1.php';

// دالة الرسائل الأنيقة (تظهر في المنتصف تماماً)
function show_elegant_msg($title, $message, $color, $icon, $redirect_url, $button_text) {
    echo "
    <body style='margin:0; display:flex; justify-content:center; align-items:center; height:100vh; font-family:\"Amiri\", serif; direction:rtl; background-color: #064e3b; background-image: url(\"https://www.transparenttextures.com/patterns/arabesque.png\");'>
    
    <div style='text-align:center; background: #fff; padding:50px; border-radius:20px; border: 5px solid #d4af37; width:480px; box-shadow: 0 30px 60px rgba(0,0,0,0.6);'>
        
        <div style='color: #d4af37; font-size: 70px; margin-bottom: 20px;'>$icon</div>
        
        <h1 style='color:#1b5e20; font-size:32px; margin-bottom:15px;'>$title</h1>
        
        <p style='font-size:22px; color:#333; line-height:1.6; margin-bottom:25px; font-weight:bold;'>$message</p>
        
        <div style='background:#f9f9f9; padding:15px; border-radius:10px; margin-bottom:25px; border: 1px solid #ddd;'>
            سيتم توجيهك تلقائياً خلال <span id='timer' style='font-weight:bold; color:#d4af37; font-size:28px;'>5</span> ثوانٍ
        </div>
        
        <a href='$redirect_url' style='display:block; padding:12px; background:#1b5e20; color:#d4af37; text-decoration:none; border:2px solid #d4af37; border-radius:8px; font-size:18px; font-weight:bold;'>$button_text</a>
    </div>

    <script>
        var sec = 5;
        var countdown = setInterval(function(){
            sec--;
            document.getElementById(\"timer\").innerHTML = sec;
            if (sec <= 0) { 
                clearInterval(countdown); 
                window.location.href = '$redirect_url'; 
            }
        }, 1000);
    </script>
    </body>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['u_full_name'];
    $pass = $_POST['u_pass'];
    $role_from_form = $_POST['role']; 

    // رابط العودة التلقائي (يحافظ على نوع الدخول: أستاذ أو طالب)
    $error_redirect = "login.php?role=" . $role_from_form;

    $result = $conn->query("SELECT * FROM users WHERE full_name='$name'");
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($pass, $user['password'])) {
            
            // التحقق الصارم: هل الصفة في القاعدة تطابق صفة الدخول؟
            if ($user['role'] !== $role_from_form) {
                show_elegant_msg("دخول غير مصرح 🚫", "هذا الحساب مسجل بصفة (<b>" . $user['role'] . "</b>). يرجى الدخول من البوابة المخصصة لك.", "#c0392b", "🚫", $error_redirect, "العودة لصفحة الدخول");
            }

            $_SESSION['user'] = $user;

            // التوجيه الناجح
            if ($user['role'] == 'طالب') {
                $target = ($user['status'] == 'active') ? "student_dashboard.php" : "payment_page.php";
                $msg = ($user['status'] == 'active') ? "مرحباً بك في فضائك التعليمي." : "يرجى إتمام السداد لتفعيل الحساب.";
                show_elegant_msg("تم التحقق 🎓", $msg, "#28a745", "✨", $target, "دخول");
            } else {
                $target = ($user['role'] == 'أستاذ') ? "teacher_dashboard.php" : "admin_dashboard.php";
                show_elegant_msg("أهلاً بك 🕌", "جاري توجيهك للوحة التحكم.", "#d4af37", "📜", $target, "دخول");
            }
        } else {
            show_elegant_msg("خطأ في البيانات ❌", "كلمة السر التي أدخلتها غير صحيحة.", "#c0392b", "❌", $error_redirect, "إعادة المحاولة");
        }
    } else {
        show_elegant_msg("غير مسجل 🔍", "لم نجد هذا الاسم مسجلاً بصفة (<b>$role_from_form</b>).", "#7f8c8d", "🔍", $error_redirect, "العودة");
    }
}
?>