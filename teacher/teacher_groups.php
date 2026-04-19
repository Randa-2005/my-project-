<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم الأستاذ</title>
    <link rel="stylesheet" href="teacher_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dashboard-body">

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
        <header class="top-bar">
            <div style="display: flex; align-items: center;">
                <button id="open-sidebar" class="toggle-btn"><i class="fas fa-bars"></i></button>
                
                <i class="fas fa-home home-icon-main"></i>
                <h2 style="margin-right: 10px;">  الرئيسية</h2>
            </div>
            <div class="user-info">أهلاً، أستاذ ياسين</div>
        </header>

        <section class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-user-graduate"></i>
                <div class="stat-info">
                    <h4>إجمالي الطلاب</h4>
                    <p>45 طالب</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-book-reader"></i>
                <div class="stat-info">
                    <h4>حصص اليوم</h4>
                    <p>3 حصص</p>
                </div>
            </div>
        </section>

        <div class="groups-section">
            <h3><i class="fas fa-chalkboard-teacher"></i> أفواج اليوم</h3>
            <div class="groups-grid">
                <div class="group-card warning">
                    <div class="badge-test">🔔 اختبار</div>
                    <h4>فوج النور</h4>
                    <p>08:00 - 10:00</p>
                    <a href="manage_session.php" class="enter-btn">دخول الحلقة</a>
                </div>
                <div class="group-card">
                    <h4>فوج الفردوس</h4>
                    <p>10:30 - 12:30</p>
                    <a href="manage_session.php" class="enter-btn">دخول الحلقة</a>
                </div>
            </div>
        </div>

        <section class="full-width-announcements">
            <h3><i class="fas fa-bullhorn"></i> آخر الإعلانات</h3>
            <div class="ann-container-horizontal">
                <div class="ann-item">
                    <span>2026/04/08</span>
                    <p>اجتماع الأساتذة يوم الخميس القادم لتنسيق الاختبارات.</p>
                </div>
                <div class="ann-item warning-ann">
                    <span>2026/04/07</span>
                    <p>يرجى تسليم تقارير الحفظ الشهرية قبل يوم 10.</p>
                </div>
            </div>
        </section>
    </main>

    <script>
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');
        const body = document.body;

        // فتح القائمة
        openBtn.onclick = function() {
            body.classList.remove('sidebar-closed');
        }

        // إغلاق القائمة (السهم)
        closeBtn.onclick = function() {
            body.classList.add('sidebar-closed');
        }
    </script>
</body>
</html>