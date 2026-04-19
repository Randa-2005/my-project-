<?php
$payment_status = "danger";
include('student_sidebar.php'); // لضمان بقاء القائمة الجانبية
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الملف الشخصي - مدرسة أسرتي القرآنية</title>
    <link rel="stylesheet" href="student_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --main-green: #0d3c1a; --gold: #bfa15f; --bg-gray: #f4f4f4; }
        body { font-family: 'Cairo', sans-serif; background-color: var(--bg-gray); margin: 0; display: flex; }
        
        /* تنسيق السايدبار */
        .sidebar {
    width: 250px;
    position: fixed;
    height: 100vh;
    transition: all 0.4s ease;
    z-index: 1000;
    
    /* اللون الأخضر الذي اخترناه */
    background-color: #0d3c1a; 
    
    /* رابط لنقش إسلامي هندسي واضح جداً */
    background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
    
    /* هذه الخاصية ضرورية جداً لدمج النقش مع الأخضر */
    background-blend-mode: multiply; 
    
    /* لتكرار النقش بشكل صحيح */
    background-repeat: repeat;
    background-size: 100px; /* جربي تغيير هذا الرقم (200px أو 400px) حتى يظهر الحجم كما في الصورة */
}
        .sidebar.sidebar-closed { width: 0; }
        
        /* المحتوى الرئيسي */
        .main-content { flex: 1; margin-right: 260px; transition: 0.4s; padding: 20px; }
        .main-content.full-width { margin-right: 0; }

        /* زر الثلاث خطوط (يظهر عند اختفاء السايدبار) */
        #open-sidebar { display: none; cursor: pointer; font-size: 1.8rem; color: var(--main-green); margin-bottom: 20px; }

        /* حاوية الملف الشخصي */
        .profile-card { max-width: 650px; margin: 40px auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .card-header { background: var(--main-green); padding: 15px 25px; display: flex; align-items: center; gap: 15px; border-bottom: 4px solid var(--gold); }
        
        /* تنسيق البيانات (تحت بعضها) */
        .info-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; }
        .info-row label { color: var(--gold); font-weight: bold; }
        .info-row span { color: var(--main-green); font-weight: 500; }

        /* المدخلات في وضع التعديل */
        .input-field { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-top: 5px; box-sizing: border-box; }
        
        /* الأزرار */
        .btn-edit { background: var(--gold); color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: bold; }
        .btn-save { background: var(--main-green); color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; }
        /* تأكدي أن هذا التنسيق موجود في ملف الـ CSS أو في وسم <style> */
#open-sidebar {
    cursor: pointer;
    font-size: 1.8rem;
    color: #2e7d32;
    padding: 10px;
    z-index: 1100; /* لضمان ظهوره فوق كل شيء */
    transition: 0.3s;
}

/* هذا السطر السحري: إذا وجد كلاس sidebar-closed، أظهر الزر فوراً */
.sidebar-closed .sidebar {
    right: -260px; /* دفع القائمة خارج الشاشة */
}

.sidebar-closed .main-content {
    margin-right: 0;
    width: 100%;
}

.sidebar, .main-content {
    transition: all 0.3s ease; /* حركة ناعمة عند الإغلاق */
}
.sidebar.sidebar-closed {
    transform: translateX(100%);
/* تأكدي أن هذا التنسيق موجود في ملف الـ CSS أو في وسم <style> */
#open-sidebar {
    cursor: pointer;
    font-size: 1.8rem;
    color: #4a5d4e;
    padding: 10px;
    z-index: 1100; /* لضمان ظهوره فوق كل شيء */
    transition: 0.3s;
}

/* هذا السطر السحري: إذا وجد كلاس sidebar-closed، أظهر الزر فوراً */
.sidebar-closed ~ .main-content #open-sidebar,
.sidebar-closed ~ #open-sidebar {
    display: inline-block !important;
}
/* التنسيق الذي يظهر الإطار الذهبي عند الضغط على القائمة */

    </style>
</head>
<body class="dashboard-body">


    <main class="main-content" id="mainContent">
        
       <button id="open-sidebar-btn" class="toggle-btn" style="display: none; border: none; background: none;">
    <i class="fas fa-bars" style="font-size: 1.5rem; color: #4a5d4e; cursor: pointer;"></i>
</button>

        <div class="profile-card">
            <div class="card-header">
                <i class="fas fa-user-circle" style="color: var(--gold); font-size: 2rem;"></i>
                <h3 style="color: var(--gold); margin: 0; font-size: 1.2rem;">إعدادات الحساب الشخصي</h3>
            </div>

            <div id="view-section" style="padding: 30px;">
                <div class="info-row"><label>الاسم واللقب:</label><span>نور الجزائرية</span></div>
                <div class="info-row"><label>تاريخ الازدياد:</label><span>15/06/1995</span></div>
                <div class="info-row"><label>البريد الإلكتروني:</label><span>nour@example.com</span></div>
                <div class="info-row"><label>رقم الهاتف:</label><span>0661000000</span></div>
                <div class="info-row" style="border:none;"><label>تاريخ الانضمام:</label><span>01/01/2024</span></div>
                 <div class="payment-alert-box <?php echo $payment_status; ?>">
    <div class="payment-main-info">
        <i class="fas fa-money-check-alt"></i>
        <span>وضعية الاشتراك الحالي: <strong>3 أشهر</strong></span>
    </div>
    <div class="payment-date-info">
        <i class="fas fa-calendar-day"></i>
        تاريخ انتهاء الصلاحية: <span>15/05/2026</span>
    </div>
</div>
                <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                    <button class="btn-edit" onclick="toggleEdit(true)">
                        <i class="fas fa-user-edit"></i> تعديل بياناتي
                    </button>
                </div>
            </div>

            <div id="edit-section" style="padding: 30px; display: none;">
                <h4 style="color: var(--main-green); margin-bottom: 20px;">تحديث معلومات التواصل</h4>
                <form action="update_profile.php" method="POST" onsubmit="return validatePasswords()">
                    <div style="margin-bottom: 15px;">
                        <label>البريد الإلكتروني الجديد</label>
                        <input type="email" name="email" value="nour@example.com" class="input-field">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>رقم الهاتف الجديد</label>
                        <input type="text" name="phone" value="0661000000" class="input-field">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                        <div>
                            <label>كلمة السر الجديدة</label>
                            <input type="password" id="new_pass" name="new_pass" class="input-field">
                        </div>
                        <div>
                            <label>تأكيد كلمة السر</label>
                            <input type="password" id="confirm_pass" class="input-field">
                        </div>
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" onclick="toggleEdit(false)" style="padding:10px 20px; border:none; border-radius:8px; cursor:pointer;">إلغاء</button>
                        <button type="submit" class="btn-save">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    const icon = document.getElementById('toggleIcon') || document.getElementById('fixedToggleIcon');

    if (sidebar) {
        sidebar.classList.toggle('collapsed');
        if (mainContent) {
            mainContent.classList.toggle('expanded');
        }
        
        // تغيير شكل الأيقونة عند الإغلاق والفتح
        if (sidebar.classList.contains('collapsed')) {
            icon.classList.replace('fa-arrow-right', 'fa-bars');
        } else {
            icon.classList.replace('fa-bars', 'fa-arrow-right');
        }
    }
}


        // وظائف تبديل العرض/التعديل
        function toggleEdit(isEdit) {
            document.getElementById('view-section').style.display = isEdit ? 'none' : 'block';
            document.getElementById('edit-section').style.display = isEdit ? 'block' : 'none';
        }

        // فحص كلمة السر
        function validatePasswords() {
            const p1 = document.getElementById('new_pass').value;
            const p2 = document.getElementById('confirm_pass').value;
            if (p1 !== "" && p1 !== p2) {
                alert("كلمات السر غير متطابقة!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>