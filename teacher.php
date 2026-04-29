<?php
include 'db1.php'; // استدعاء الاتصال بقاعدة البيانات

// هنا نضع الاستعلام الخاص بالطلاب
$sql = "SELECT * FROM users WHERE role = 'طالب'";
$result = mysqli_query($conn, $sql);
?><!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة كادر جديد - مدرسة أسرتي</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Amiri:wght@700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --royal-green: #064e3b;
            --bright-green: #1b5e20;
            --pure-gold: #d4af37;
            --cream-bg: rgb(241, 249, 243);
            --white: #ffffff;
            --input-bg: #0c4620; /* لون خلفية الحقول الداكن */
        }

        body { 
            margin: 0; 
            font-family: 'Cairo', sans-serif; 
            background-color: rgb(5, 50, 22); 
            display: flex; 
            min-height: 100vh;
        }

        /* 🏰 Sidebar */
        .sidebar {
            width: 280px;
            background-color: var(--primary-green);
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            color: white;
            height: 100vh;
            position: fixed;
            border-left: 5px solid var(--gold);
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px;
            text-align: center;
            background: rgba(13, 60, 26, 0.6);
        }  .admin-logo-icon {
            font-size: 70px;
            color: var(--gold);
            margin-bottom: 15px;
            display: block;
        }

        .nav-links {
            list-style: none;
            padding: 0;
        }

        .nav-links li a {
            display: block;
            padding: 15px 25px;
            color: #eee;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-links li a.active {
            background-color: rgba(212, 175, 55, 0.2);
            color: var(--gold);
        }

        .main-content { 
            margin-right: 280px; 
            width: calc(100% - 280px); 
            padding: 40px; 
            display: flex;
            justify-content: center;
        }

        /* 📜 Card */
        .registration-card {
            background-color: var(--cream-bg);
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            border: 2px solid var(--pure-gold); 
            border-radius: 30px; 
            padding: 40px; 
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            max-width: 900px;
            width: 100%;
        }

        .section-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double var(--pure-gold);
            padding-bottom: 15px;
        }

        /* 🧩 Grid System */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .input-group label {
            font-weight: 700;
            color: var(--royal-green);
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modern-input {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            font-family: 'Cairo', sans-serif;
            font-size: 15px;
            background: var(--input-bg);
            color: white;
            transition: 0.3s;
        }

        .modern-input:focus {
            outline: none;
            border-color: var(--pure-gold);
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
        }

        /* 👁️ Password Wrapper */
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input { width: 100%; }

        .toggle-password {
            position: absolute;
            left: 12px;
            cursor: pointer;
            color: #d4af37;
            font-size: 16px;
        }

        /* 🔘 Radio Box - إصلاح التنسيق الأفقي */
        .role-box {
            background: rgba(12, 70, 32, 0.05);
            border: 2px dashed var(--pure-gold);
            border-radius: 20px;
            padding: 20px;
            margin: 25px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px; /* مسافة بين الخيارات */
            flex-wrap: wrap;
        }.role-title {
            font-weight: 800;
            color: var(--royal-green);
            font-size: 17px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-weight: 700;
            color: var(--bright-green);
            transition: 0.3s;
        }

        .radio-option input {
            width: 20px;
            height: 20px;
            accent-color: var(--royal-green);
        }

        /* 🏆 Submit Button - إصلاح العرض والتمركز */
        .btn-submit {
            background: linear-gradient(135deg, var(--royal-green), var(--bright-green));
            color: var(--pure-gold);
            border: 2px solid var(--pure-gold);
            padding: 16px;
            border-radius: 15px;
            font-size: 20px;
            font-weight: bold;
            width: 100%; /* يأخذ العرض كاملاً */
            max-width: 500px; /* طول أنيق */
            display: block;
            margin: 20px auto 0; /* تمركز في المنتصف */
            cursor: pointer;
            transition: 0.4s;
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(212, 175, 55, 0.3);
            filter: brightness(1.1);
        }
    </style>
</head>
<body>
 <?php include 'sidebar.php'; ?>
    <main class="main-content">
        <div class="registration-card">
            <div class="section-header">
                <h2><i class="fas fa-user-tie"></i> نظام إضافة الموظفين والأساتذة</h2>
            </div>

            <form action="process_staff.php" method="POST">
                <div class="form-grid">
                    <div class="input-group">
                        <label><i class="fas fa-id-card"></i> الاسم الكامل</label>
                        <input type="text" name="full_name" class="modern-input" placeholder="ادخل الاسم الكامل" required>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-calendar-day"></i> تاريخ الميلاد</label>
                        <input type="date" name="birth_date" class="modern-input" required>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-phone-alt"></i> رقم الهاتف</label>
                        <input type="tel" name="phone" id="phone" class="modern-input" placeholder="0XXXXXXXXX" required pattern="0[0-9]{9}">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <label><i class="fas fa-lock"></i> كلمة المرور الأولية</label>
                        <div class="password-wrapper">
                            <input type="password" id="pass1" name="password" class="modern-input" placeholder="****" required minlength="8">
                            <i class="fas fa-eye-slash toggle-password" onclick="togglePass('pass1', this)"></i>
                        </div>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-check-double"></i> تأكيد كلمة المرور</label>
                        <div class="password-wrapper">
                            <input type="password" id="pass2" name="c_password" class="modern-input" placeholder="****" required>
                            <i class="fas fa-eye-slash toggle-password" onclick="togglePass('pass2', this)"></i>
                        </div>
                    </div>
                </div>

                <div class="role-box">
                    <span class="role-title">فئة الحساب:</span>
                    <label class="radio-option">
                        <input type="radio" name="role" value="أستاذ" checked> أستاذ محفظ 📖
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="role" value="موظف"> موظف إداري ⚙️
                    </label>
                </div><button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> حفظ البيانات وإصدار الحساب
                </button>
            </form>
        </div>
    </main>

    <script>
        function togglePass(inputId, icon) {
            const field = document.getElementById(inputId);
            if (field.type === "password") {
                field.type = "text";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            } else {
                field.type = "password";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            }
        }
    </script>
</body>
</html>