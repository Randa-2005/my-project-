<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>استعراض قوائم الطلبة | مدرسة أسرتي القرآنية</title>
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
                <i class="fas fa-users home-icon-main"></i>
                <h2 style="margin-right: 10px;">استعراض قوائم التلاميذ</h2>
            </div>
        </header>

        <section class="filter-section card-box">
            <div style="display: flex; align-items: center; gap: 15px;">
                <label style="color: #4a5d4e; font-weight: bold;">اختر الفوج:</label>
                <select class="custom-select">
                    <option>فوج النور</option>
                    <option>فوج الفردوس</option>
                </select>
            </div>
        </section>

        <div class="tabs-container">
            <button class="tab-link active" onclick="openTab(event, 'general')"> القائمة الرئيسية</button>
            <button class="tab-link" onclick="openTab(event, 'attendance')">قائمة الحضور</button>
            <button class="tab-link" onclick="openTab(event, 'evaluation')">قائمة التقييم</button>
        </div>

        <div id="general" class="tab-content active">
            <div class="table-container card-box">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>رقم التسجيل</th>
                            <th>اللقب</th>
                            <th>الاسم</th>
                            <th>تاريخ الميلاد</th>
                            <th>المستوى الدراسي</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024001</td>
                            <td>بن محمد</td>
                            <td>أحمد</td>
                            <td>2010/05/12</td>
                            <td>متوسط</td>
                        </tr>
                    </tbody>
                </table>
                <div class="print-footer">
                    <button class="print-btn-action" onclick="window.print()"><i class="fas fa-print"></i>طباعة</button>
                </div>
            </div>
        </div>

        <div id="attendance" class="tab-content">
            <div class="table-container card-box">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>رقم التسجيل</th>
                            <th>الاسم واللقب</th>
                            <th>04/01</th><th>04/02</th><th>04/03</th><th>04/04</th>
                            <th>نسبة الحضور</th>
                        </tr>
</thead>
<tbody>
                        <tr>
                            <td>2024001</td>
                            <td>أحمد بن محمد</td>
                            <td><span class="p-status">ح</span></td>
                            <td><span class="a-status">غ</span></td>
                            <td><span class="p-status">ح</span></td>
                            <td><span class="p-status">ح</span></td>
                            <td class="attendance-rate">75%</td>
                        </tr>
                    </tbody>
                </table>
                <div class="print-footer">
                    <button class="print-btn-action" onclick="window.print()"><i class="fas fa-print"></i> طباعة</button>
                </div>
            </div>
        </div>

        <div id="evaluation" class="tab-content">
            <div class="table-container card-box">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>رقم التسجيل</th>
                            <th>الاسم واللقب</th>
                            <th>04/01</th><th>04/02</th><th>04/03</th><th>04/04</th>
                            <th style="background: #f0e6d2;">المعدل العام</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024001</td>
                            <td>أحمد بن محمد</td>
                            <td>08</td><td>09</td><td>07</td><td>10</td>
                            <td style="font-weight: bold; color: #b89548;">08.5</td>
                        </tr>
                    </tbody>
                </table>
                <div class="print-footer">
                    <button class="print-btn-action" onclick="window.print()"><i class="fas fa-print"></i> طباعة  </button>
                </div>
            </div>
        </div>
    </main>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) { tabcontent[i].style.display = "none"; }
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tablinks.length; i++) { tablinks[i].className = tablinks[i].className.replace(" active", ""); }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');
        const body = document.body;
        openBtn.onclick = () => body.classList.remove('sidebar-closed');
        closeBtn.onclick = () => body.classList.add('sidebar-closed');
    </script>
 
</body>
</html>