<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إعدادات الحساب - مدرسة أسرتي</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Amiri:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #0d3c1a;
            --gold: #d4af37;
            --bg-light: #f4f7f6;
        } .sidebar {
            width: 280px;
            background-color: var(--primary-green);
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            color: white; height: 100vh; position: fixed; border-left: 5px solid var(--gold);overflow-y:auto;
        }

        .sidebar-header { padding: 30px; text-align: center; background: rgba(13, 60, 26, 0.6); }
        .admin-logo-icon { font-size: 70px; color: var(--gold); margin-bottom: 15px; display: block; }

        .nav-links { list-style: none; padding: 0; margin: 0; }

        .nav-links li a { 
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 25px; color: #eee; text-decoration: none; 
            font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: 0.3s;
        }

        .nav-links li a:hover { background-color: rgba(212, 175, 55, 0.15); color: var(--gold); }
        .nav-links li a.active { background-color: rgba(212, 175, 55, 0.2); color: var(--gold); }


        body { margin: 0; font-family: 'Cairo', sans-serif; background-color: var(--bg-light); display: flex; }

        .main-content { margin-right: 280px; width: calc(100% - 280px); padding: 40px; }

        .settings-card {
            background: white; padding: 30px; border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 650px; margin: 0 auto;
        }

        .section-title {
            font-family: 'Amiri', serif; color: var(--primary-green);
            border-bottom: 2px solid var(--gold); padding-bottom: 10px; margin-bottom: 25px;
        }

        .form-group { margin-bottom: 20px; position: relative; }
        
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        
        input {
            width: 100%; padding: 12px 12px 12px 40px; /* مساحة لليمين للأيقونات */
            border: 1px solid #ddd; border-radius: 8px;
            box-sizing: border-box; font-family: 'Cairo';
        }

        .toggle-password {
            position: absolute; left:15px; top:55px; 
            cursor: pointer; color: var(--primary-green);
        }

        .btn-update {
            background-color: var(--primary-green); color: white; border: none;
            padding: 15px; border-radius: 8px; cursor: pointer;
            font-size: 18px; width: 100%; font-weight: bold; margin-top: 20px;
        }
    </style>
</head>
<body>
  <?php include 'sidebar.php'; ?>
    <main class="main-content" style="margin-right: auto; margin-left: auto;">
        <div class="settings-card">
            <h2 class="section-title"><i class="fas fa-user"></i> المعلومات الشخصية</h2>
            
            <div class="form-group">
                <label>اسم المستخدم / المدير</label>
                <input type="text" id="admin-name" value="مدير المدرسة">
            </div>

            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input type="email" id="admin-email" value="admin@osrati.com">
            </div>
   
   <div class="form-group">
    <label>رقم الهاتف</label>
    <div style="position: relative;">
        <input type="tel" id="admin-phone" 
               placeholder="0XXXXXXXXX" 
               maxlength="10"minlength="10" 
               pattern="0[0-9]{9}" 
               title="يجب أن يبدأ الرقم بـ 0 ويتكون من 10 أرقام"
               oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
               required>
        <i class="fas fa-phone"style="position: absolute; left: 15px; top: 12px; color: var(--primary-green);"></i>
    </div>
</div>

            <h2 class="section-title" style="margin-top: 30px;"><i class="fas fa-shield-alt"></i> تغيير كلمة المرور</h2>
            
            <div class="form-group">
                <label>كلمة المرور الجديدة</label>
                <input type="password" id="new-password" placeholder="اتركها فارغة إذا لم ترد التغيير">
                <i class="fas fa-eye-slash toggle-password" onclick="togglePass('new-password', this)"></i>
            </div>

          <div class="form-group">
    <label>تأكيد كلمة المرور</label>
    <div style="position: relative;">
        <input type="password" id="confirm-password" placeholder="أعد كتابة كلمة المرور" required>
        <i class="fas fa-eye-slash toggle-password" 
           style="position: absolute; left: 15px; top: 12px; cursor: pointer; color: var(--primary-green);" 
           onclick="togglePass('confirm-password', this)"></i>
    </div>


            <button type="button" class="btn-update" onclick="saveAll()">حفظ كافة التغييرات</button>
        </div></div>
    </main>

    <script>
        // دالة إظهار وإخفاء كلمة المرور
        function togglePass(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            }
        }

        function saveAll() {
            alert("تم إرسال طلب التحديث للنظام بنجاح! ✅");
        }
    </script>
</body>
</html> 