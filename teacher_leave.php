
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقديم طلب عطلة </title>
    <link rel="stylesheet" href="teacher_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #1a472a; --accent: #ffc107; }
        
        .leave-card { 
            background: white; 
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            max-width: 900px; 
            margin: 30px auto;
            border-top: 8px solid var(--primary);
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px; }
        .full-width { grid-column: span 2; }

        .input-group { display: flex; flex-direction: column; gap: 10px; }
        .input-group label { font-weight: bold; color: var(--primary); font-size: 0.95rem; }

        /* توحيد حجم الحقول لضمان التناسق */
        .input-field { 
            padding: 12px 15px; 
            border: 2px solid #e1e1e1; 
            border-radius: 10px; 
            width: 100%;
            box-sizing: border-box; /* لضمان عدم خروج الحقل عن الإطار */
        }

        /* توسيع مساحة السبب */
        textarea.input-field { height: 120px; resize: none; }

        .upload-zone {
            border: 2px dashed #ccc;
            padding: 30px;
            text-align: center;
            border-radius: 12px;
            background: #fafafa;
            cursor: pointer;
        }

        /* مجموعة الأزرار */
        .btn-group { display: flex; gap: 15px; margin-top: 10px; }
        .submit-btn { 
            flex: 2; 
            background: var(--primary); 
            color: white; 
            padding: 15px; 
            border: none; 
            border-radius: 10px; 
            font-weight: bold; 
            cursor: pointer;
        }
        .cancel-btn { 
            flex: 1; 
            background: #f4f4f4; 
            color: #666; 
            padding: 15px; 
            border: 1px solid #ddd; 
            border-radius: 10px; 
            font-weight: bold; 
            cursor: pointer;
        }
        .sidebar-closed ~ .main-content #open-sidebar {
    display: inline-block !important;
}
/* تأكدي أن هذا التنسيق موجود في ملف الـ CSS أو في وسم <style> */
#open-sidebar {
    cursor: pointer;
    font-size: 1.8rem;
    color: #0d3c1a;
    padding: 10px;
    z-index: 9999; /* لضمان ظهوره فوق كل شيء */
    transition: 0.3s;
}


/* هذا السطر السحري: إذا وجد كلاس sidebar-closed، أظهر الزر فوراً */
.sidebar-closed ~ .main-content #open-sidebar,
.sidebar-closed ~ #open-sidebar {
    display: inline-block !important;
}
    </style>
</head>
<body>

    <aside class="sidebar">
         <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
        <div class="sidebar-header">
    <button id="close-sidebar" class="close-sidebar-btn"><i class="fas fa-arrow-right"></i></button>
       <div class="sidebar-header" style="padding: 25px 15px; text-align: center; border-bottom: 1px solid rgba(191, 161, 95, 0.2);">
        
        <i class="fas fa-chalkboard-teacher" style="font-size: 45px; color: #bfa15f; margin-bottom: 12px; display: block; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));"></i>
        
        <h3 style="color: #bfa15f; font-size: 20px; font-family: 'Cairo', sans-serif; font-weight: bold; margin: 0; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
            لوحة الأستاذ
        </h3>
    </div>
</div>
      <ul class="nav-links">
    <li onclick="location.href='teacher_groups.php'" 
        class="<?php echo ($current_page == 'teacher_groups.php') ? 'active-gold' : ''; ?>">
        <i class="fas fa-home"></i> الرئيسية
    </li>

    <li onclick="location.href='groups_list.php'" 
        class="<?php echo ($current_page == 'groups_list.php' || $current_page == 'manage_session.php') ? 'active-gold' : ''; ?>">
        <i class="fas fa-users"></i> قوائم التلاميذ
    </li>
    <li onclick="location.href='exam_history.php'" 
       class="<?php echo ($current_page == 'exam_history.php') ? 'active-gold' : ''; ?>">
       <i class="fas fa-file-invoice"></i> <span>سجل الامتحانات</span>
   </li>

    <li onclick="location.href='general_schedule.php'" 
        class="<?php echo ($current_page == 'general_schedule.php') ? 'active-gold' : ''; ?>">
        <i class="fas fa-calendar-alt"></i> البرنامج العام
    </li>

    <li onclick="location.href='announcements.php'" 
        class="<?php echo ($current_page == 'announcements.php') ? 'active-gold' : ''; ?>">
        <i class="fas fa-bullhorn"></i> الإعلانات
    </li>
    <li onclick="location.href='teacher_leave.php'" 
    class="<?php echo ($current_page == 'teacher_leave.php') ? 'active-gold' : ''; ?>">
    <i class="fas fa-calendar-times"></i>
    <span>طلب عطلة</span>
</li>
    <li onclick="location.href='profile.php'" 
    class="<?php echo ($current_page == 'profile.php') ? 'active-go' : ''; ?>">
    <i class="fas fa-user-cog"></i> <span>الملف الشخصي</span>
</li>
      <li><i class="fas fa-sign-out-alt"></i> خروج</li>
</ul>
    </aside>

    <main class="main-content">
        <button id="open-sidebar-btn" class="toggle-btn" style="display: none; border: none; background: none;">
            <i class="fas fa-bars" style="font-size: 1.5rem; color: #4a5d4e; cursor: pointer;"></i>
       </button>
        <div class="header">
            <h2><i class="fas fa-calendar-alt"></i> طلب غياب رسمي</h2>
        </div>

        <div class="content-wrapper">
            <div class="leave-card">
                <form action="save_request.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="form-grid">
                        <div class="input-group full-width">
                            <label><i class="fas fa-user"></i>  الاسم واللقب:</label>
                            <input type="text" class="input-field" style="background:#f9f9f9;" value="الأستاذ المتعاقد" readonly>
                        </div>

                        <div class="input-group full-width">
                            <label><i class="fas fa-hourglass-half"></i> مدة العطلة (بالأيام):</label>
                            <input type="number" name="days" class="input-field" placeholder="أدخل عدد الأيام">
                        </div>

                        <div class="input-group">
                            <label><i class="fas fa-calendar-day"></i> تاريخ البداية:</label>
                            <input type="date" name="start" class="input-field">
                        </div>
                        <div class="input-group">
                            <label><i class="fas fa-calendar-check"></i> تاريخ النهاية:</label>
                            <input type="date" name="end" class="input-field">
                        </div>
                        <div class="input-group full-width">
                            <label><i class="fas fa-pen"></i> سبب طلب العطلة بالتفصيل:</label>
                            <textarea name="reason" class="input-field" placeholder="اكتب تبريرك الوافي هنا..."></textarea>
                        </div>

                        <div class="input-group full-width">
                            <label><i class="fas fa-paperclip"></i> إرفاق الوثيقة (اختياري):</label>
                            <div class="upload-zone" onclick="document.getElementById('fileInp').click()">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: var(--primary);"></i>
                                <p id="fileMsg">اضغط لتحميل الشهادة الطبية أو المبرر</p>
                                <input type="file" id="fileInp" name="doc" hidden onchange="checkFile()">
                            </div>
                        </div>

                        <div class="full-width btn-group">
                            <button type="submit" class="submit-btn">تأكيد وإرسال الطلب</button>
                            <button type="button" class="cancel-btn" onclick="history.back()">إلغاء العملية</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script>
   
        function checkFile() {
            const inp = document.getElementById('fileInp');
            const msg = document.getElementById('fileMsg');
            if(inp.files.length > 0) {
                msg.innerHTML = "<b>تم اختيار:</b> " + inp.files[0].name;
            }
        }
    // 1. تحديد العناصر بدقة
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content'); // استهداف الكلاس الموجود
    const closeBtn = document.getElementById('close-sidebar');
    const openBtn = document.getElementById('open-sidebar-btn');

    // 2. وظيفة إغلاق السيدبار (السهم)
    closeBtn.onclick = function() {
        sidebar.style.display = 'none'; // إخفاء السيدبار تماماً
        mainContent.style.marginRight = '0'; // توسيع المحتوى ليملأ الشاشة
        openBtn.style.display = 'block'; // إظهار زر الثلاث خطوط
    };

    // 3. وظيفة فتح السيدبار (الثلاث خطوط)
    openBtn.onclick = function() {
        sidebar.style.display = 'block'; // إظهار السيدبار
        mainContent.style.marginRight = '260px'; // إعادة الهامش (حسب عرض السيدبار عندك)
        this.style.display = 'none'; // إخفاء زر الثلاث خطوط
    };

    // وظيفة فحص الملف (الموجودة سابقاً)
    function checkFile() {
        const inp = document.getElementById('fileInp');
        const msg = document.getElementById('fileMsg');
        if(inp.files.length > 0) {
            msg.innerHTML = "<b>تم اختيار:</b> " + inp.files[0].name;
        }
    }
</script>
</body>
</html>