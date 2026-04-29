<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مدرسة أسرتي - اختر صفة الدخول</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .role-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 30px;
        }
        .role-button {
            background: #fff;
            border: 2px solid #d4af37;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            text-decoration: none;
            color: #1b5e20;
            font-weight: bold;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        .role-button:hover {
            background: #1b5e20;
            color: #d4af37;
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        .role-button i { font-size: 35px; margin-bottom: 15px; display: block; }
    </style>
</head>
<body>

<div class="card">
    <i class="fas fa-mosque fa-4x" style="color: #d4af37; margin-bottom: 20px;"></i>
    <h1 style="color: #1b5e20;">مدرسة أسرتي القرآنيـة</h1>
    <p>أهلاً بك، يرجى اختيار صفة الدخول:</p>

    <div class="role-grid">
        <a href="login.php?role=مدير" class="role-button">
            <i class="fas fa-user-tie"></i> مدير
        </a>
        <a href="login.php?role=أستاذ" class="role-button">
            <i class="fas fa-chalkboard-teacher"></i> أستاذ
        </a>
        <a href="login.php?role=طالب" class="role-button">
            <i class="fas fa-user-graduate"></i> طالب
        </a>
        <a href="login.php?role=موظف" class="role-button">
            <i class="fas fa-user-cog"></i> موظف
        </a>
    </div>
</div>

</body>
</html>