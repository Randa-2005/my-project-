<?php
// جلب نوع الحساب من الرابط (أستاذ، طالب، مدير، عامل)
$role = isset($_GET['role']) ? $_GET['role'] : 'طالب';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل دخول - (<?php echo $role; ?>) - مدرسة أسرتي القرآنية</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Cairo', sans-serif;
            background-color: #0d3c1a; 
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            direction: rtl;
        }

        .login-card {
            background-color: #ffffff;
            padding: 40px 50px;
            border-radius: 25px;
            border: 8px double #d4af37; 
            width: 450px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.6);
            text-align: center;
        }

        .header-logo {
            color: #d4af37;
            margin-bottom: 20px;
        }
        .mosque-icon { font-size: 55px; }
        .school-title {
            font-family: 'Amiri', serif;
            font-size: 24px;
            color: #1b5e20;
            margin: 5px 0 0 0;
        }

        .role-icon {
            font-size: 45px;
            color: #d4af37;
            margin-top: 15px;
            background: #f9f9f9;
            width: 80px; height: 80px;
            line-height: 80px; border-radius: 50%;
            margin-left: auto; margin-right: auto;
            border: 2px solid #eee;
        }

        h2 {
            font-family: 'Amiri', serif;
            color: #333; font-size: 26px;
            margin: 15px 0 25px 0;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: right;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #444;
        }

        .input-group input {
            width: 100%;
            padding: 14px 15px;
            padding-left: 45px; 
            border: 2px solid #ddd;
            border-radius: 10px;
            box-sizing: border-box;
            font-family: 'Cairo', sans-serif;
            font-size: 16px;
        }

        .toggle-password {
            position: absolute;
            left: 15px;
            top:60px; /* تم الضبط ليكون موازياً لوسط الحقل */
            transform: translate(-5%);
            color: #999;
            cursor: pointer;
            font-size: 18px;
            transition: color 0.3s;
        }
        .toggle-password:hover { color: #d4af37; }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #0d3c1a;
            color: #d4af37;
            border: 3px solid #d4af37;
            border-radius: 12px;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-submit:hover { background-color: #1b5e20; transform: translateY(-2px); }

        .footer-links {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .footer-links a { text-decoration: none; font-size: 14px; font-weight: 700; }
        .link-main { color: #666; }
        .link-register { color: #d4af37; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="header-logo">
            <i class="fas fa-mosque mosque-icon"></i>
            <h1 class="school-title">My Family's Quranic School</h1>
        </div><div class="role-icon">
            <?php 
                switch($role) {
                    case 'أستاذ': echo '<i class="fas fa-chalkboard-user"></i>'; break;
                    case 'طالب': echo '<i class="fas fa-user-graduate"></i>'; break;
                    case 'مدير': echo '<i class="fas fa-user-tie"></i>'; break;
                    case 'عامل': echo '<i class="fas fa-user-gear"></i>'; break;
                    default: echo '<i class="fas fa-user"></i>';
                }
            ?>
        </div>

        <h2>تسجيل دخول (<?php echo $role; ?>)</h2>

        <form action="login_process.php" method="POST">
            <input type="hidden" name="role" value="<?php echo $role; ?>">

            <div class="input-group">
                <label>الاسم الكامل:</label>
                <input type="text" name="u_full_name" required placeholder="أدخل اسمك كما هو مسجل">
            </div>

            <div class="input-group">
                <label>كلمة المرور:</label>
                <input type="password" name="u_pass" id="passInput" required placeholder="****">
                <i class="fas fa-eye-slash toggle-password" id="eyeIcon"></i>
            </div>

            <button type="submit" class="btn-submit">دخول للمنصة</button>
        </form>

        <div class="footer-links">
            <a href="index.php" class="link-main">🏠 الواجهة الرئيسية</a>
            <?php if ($role == 'طالب'): ?>
                <a href="register.php?role=<?php echo $role; ?>" class="link-register">✨ حساب جديد</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const eyeIcon = document.getElementById('eyeIcon');
        const passInput = document.getElementById('passInput');

        eyeIcon.addEventListener('click', () => {
            if (passInput.type === 'password') {
                passInput.type = 'text'; // إظهار الحروف
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye'); // تظهر العين المفتوحة عند ظهور الحروف
            } else {
                passInput.type = 'password'; // إخفاء الحروف
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash'); // تظهر العين المشطوبة عند اختفاء الحروف
            }
        });
    </script>
</body>
</html>