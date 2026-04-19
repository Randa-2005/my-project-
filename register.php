<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل جديد - مدرسة أسرتي</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card { width: 550px; padding: 40px; border: 3px solid #d4af37; }
        .input-group { position: relative; margin-bottom: 15px; display: flex; align-items: center; }
        input, select { font-size: 20px !important; padding: 12px 45px !important; height: 55px; width: 100%; border-radius: 10px; border: 1.5px solid #d4af37; font-family: 'Amiri'; }
        .main-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #d4af37; font-size: 20px; }
        .toggle-password { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #1b5e20; font-size: 22px; z-index: 10; }
        .row-container { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        label { display: block; text-align: right; font-weight: bold; color: #1b5e20; margin-top: 10px; font-size: 16px; }
    </style>
</head>
<body>

<div class="card">
    <i class="fas fa-user-plus fa-3x" style="color: #d4af37; margin-bottom: 15px;"></i>
    <?php $type = isset($_GET['type']) ? $_GET['type'] : 'طالب'; ?>
    <h2 style="color: #1b5e20;">انضمام <?php echo $type; ?> جديد</h2>

    <form action="register_process.php" method="POST" id="regForm">
        <input type="hidden" name="role" value="<?php echo $type; ?>">

        <label>الاسم الكامل:</label>
        <div class="input-group">
            <i class="fas fa-user main-icon"></i>
            <input type="text" name="full_name" placeholder="الاسم الكامل " required>
        </div>

        <div class="row-container">
            <div>
                <label>رقم الهاتف:</label>
                <input type="text" name="phone" pattern="0[0-9]{9}" title="10 أرقام تبدأ بـ 0" required>
            </div>
            <div>
                <label>تاريخ الميلاد:</label>
                <input type="date" name="birth_date" required>
            </div>
        </div>

        <?php if ($type == "طالب"): ?>
            <label>مدة الاشتراك:</label>
            <div class="input-group">
                <i class="fas fa-clock main-icon"></i>
                <select name="subscription" required>
                    <option value="شهر">شهر واحد</option>
                    <option value="3 أشهر">3 أشهر</option>
                    <option value="6 أشهر">6 أشهر</option>
                    <option value="سنة">عام كامل</option>
                </select>
            </div>
        <?php endif; ?>

        <label>كلمة المرور:</label>
        <div class="input-group">
            <i class="fas fa-lock main-icon"></i>
            <input type="password" name="password" id="p1" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" required>
            <i class="fas fa-eye-slash toggle-password" onclick="togglePass('p1', this)"></i>
        </div>

        <label>تأكيد كلمة المرور:</label>
        <div class="input-group">
            <i class="fas fa-check-double main-icon"></i>
            <input type="password" name="confirm_password" id="p2" required>
            <i class="fas fa-eye-slash toggle-password" onclick="togglePass('p2', this)"></i>
        </div>

        <button type="submit">إرسال طلب الانضمام</button>
    </form>

    <div class="footer-link" style="margin-top: 20px;">
        لديك حساب بالفعل؟ <a href="index.php" style="color: #d4af37; font-weight: bold; text-decoration: none;">سجل دخولك من هنا</a>
    </div>
</div>

<script>
    function togglePass(id, icon) {
        const input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text"; icon.classList.replace("fa-eye-slash", "fa-eye");
        } else {
            input.type = "password"; icon.classList.replace("fa-eye", "fa-eye-slash");
        }
    }
</script>
</body>
</html>