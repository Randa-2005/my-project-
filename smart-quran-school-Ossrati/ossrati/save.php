<?php
// 1. الاتصال بقاعدة البيانات
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "smart_quran_school_ossrati";
$port = 3307; 

$conn = mysqli_connect($host, $user, $pass, $db_name, $port);

// التأكد من الاتصال
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. جلب البيانات من الفورم
    $f_name     = mysqli_real_escape_string($conn, $_POST['f_name']);
    $l_name     = mysqli_real_escape_string($conn, $_POST['l_name']);
    $u_phone    = mysqli_real_escape_string($conn, $_POST['u_phone']);
    $u_birth    = mysqli_real_escape_string($conn, $_POST['u_birth']); // تاريخ الميلاد
    $u_duration = mysqli_real_escape_string($conn, $_POST['u_duration']); // مدة الاشتراك
    $u_pass     = $_POST['u_pass'];

    $full_name = $f_name . " " . $l_name;

    // 3. تشفير كلمة السر (للحماية العالية)
    $hashed_password = password_hash($u_pass, PASSWORD_DEFAULT);

    // 4. استعلام الإدخال (تأكدي أنكِ نفذتي أمر ALTER TABLE في قاعدة البيانات أولاً)
    $sql = "INSERT INTO users (full_name, phone, birthday, duration, password, status) 
            VALUES ('$full_name', '$u_phone', '$u_birth', '$u_duration', '$hashed_password', 'pending')";

    if (mysqli_query($conn, $sql)) {
        // واجهة النجاح المزخرفة بالنقوش العثمانية
        ?>
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <style>
                body {
                    margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh;
                    background-color: #1b5e20;
                    background-image: url('https://www.transparenttextures.com/patterns/arabesque.png');
                    font-family: 'Segoe UI', sans-serif;
                }
                .success-card {
                    background: white; padding: 40px; border-radius: 20px; text-align: center;
                    border-top: 10px solid #d4af37; width: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);
                }
                .icon { background: #1b5e20; color: white; width: 60px; height: 60px; line-height: 60px; 
                        border-radius: 50%; font-size: 30px; margin: 0 auto 20px; }
                a { display: inline-block; margin-top: 25px; padding: 12px 30px; background: #1b5e20; 
                    color: white; text-decoration: none; border-radius: 8px; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class="success-card">
                <div class="icon">✓</div>
                <h2 style="color: #1b5e20;">تم التسجيل بنجاح!</h2>
                <p>مرحباً بك يا <b><?php echo $full_name; ?></b> في مدرسة أسرتي.</p>
                <p style="color: #666; font-size: 14px;">حسابك الآن في انتظار التفعيل من قبل الإدارة.</p>
                <a href="login.php">العودة لتسجيل الدخول</a>
            </div>
        </body>
        </html>
        <?php
    } else {
        // في حال استمرار الخطأ، سيظهر لكِ السبب هنا
        echo "<div style='color:white; background:red; padding:20px;'>خطأ في قاعدة البيانات: " . mysqli_error($conn) . "</div>";
    }
}
?>