<?php
include 'db1.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $birth = $_POST['birth_date'];
    $role = $_POST['role'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sub = isset($_POST['subscription']) ? $_POST['subscription'] : 'لا يوجد';

    $sql = "INSERT INTO users (full_name, phone, birth_date, password, role, status, subscription) 
            VALUES ('$full_name', '$phone', '$birth', '$pass', '$role', 'pending', '$sub')";

    if ($conn->query($sql) === TRUE) {
        echo "
        <body style='background:#1b5e20; display:flex; justify-content:center; align-items:center; height:100vh; font-family:Amiri;'>
        <div style='text-align:center; background:white; padding:40px; border-radius:20px; border:4px solid #d4af37; width:450px;'>
            <div style='font-size:60px; color:#28a745; margin-bottom:20px;'>✔️</div>
            <h1 style='color:#1b5e20;'>تم إرسال الطلب بنجاح!</h1>
            <p style='font-size:20px; color:#555;'>أهلاً بك معنا يا <b>$full_name</b>.</p>
            <p style='font-size:18px;'>حسابك كـ (<b>$role</b>) قيد المراجعة الآن.</p>
            <div style='background:#f9f9f9; padding:10px; border-radius:10px; margin:20px 0;'>
                سيتم توجيهك لصفحة الدخول خلال <span id='timer' style='font-weight:bold; color:#d4af37; font-size:24px;'>5</span> ثوانٍ
            </div>
        </div>
        <script>
            var sec = 5;
            setInterval(function(){
                sec--;
                document.getElementById('timer').innerHTML = sec;
                if (sec <= 0) { window.location.href = 'login.php?role=$role'; }
            }, 1000);
        </script>
        </body>";
    } else {
        echo "خطأ: " . $conn->error;
    }
}
?>