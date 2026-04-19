<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البرنامج الزمني العام | مدرسة أسرتي القرآنية</title>
    
    <link rel="stylesheet" href="teacher_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* تنسيقات إضافية خاصة بالجدول الكلاسيكي */
        .schedule-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-top: 20px;
        }

        .classic-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .classic-table th {
            background-color: #0d3c1a; /* أخضر المدرسة */
            color: white;
            padding: 15px;
            border: 1px solid #3d4d40;
        }

        .classic-table td {
            padding: 12px;
            border: 1px solid #eee;
            text-align: center;
            vertical-align: middle;
        }

        .day-column {
            background-color: #fdfbf7;
            font-weight: bold;
            color: #4a5d4e;
            width: 100px;
        }

        /* تنسيق الحصة الخاصة بالأستاذ */
        .my-session {
            background-color: #fff9eb;
            border: 1px solid #f0e6d2;
            padding: 8px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .group-name { font-weight: bold; color: #0d3c1a; font-size: 0.95rem; }
        .room-name { color: #bfa15f; font-size: 0.85rem; }

        /* تنسيق حصص الزملاء */
        .other-session {
            background-color: #f9f9f9;
            color: #aaa;
            padding: 8px;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        /* الحصة الفارغة (نقاط رمادية) */
        .empty-slot {
            color: #ddd;
            letter-spacing: 5px;
            font-weight: bold;
        }

        .tabs-nav {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab-btn {
            padding: 10px 20px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 10px;
            cursor: pointer;
            color: #4a5d4e;
            font-weight: bold;
            transition: 0.3s;
        }

        .tab-btn.active {
            background: #bfa15f;
            color: white;
            border-color: #bfa15f;
        }
        /* تنسيق حصص الأساتذة الآخرين في البرنامج العام */
.other-session {
    background-color: #f4f4f4; /* رمادي خفيف جداً */
    border: 1px dashed #ddd;
    padding: 8px;
    border-radius: 8px;
    line-height: 1.4;
}

.prof-name {
    color: #666;
    font-weight: bold;
    font-size: 0.9rem;
}

.room-tag {
    color: #bfa15f; /* اللون الذهبي لرقم القاعة */
    font-weight: 500;
    display: block;
    margin-top: 2px;
}

.empty-slot {
    color: #eee;
    letter-spacing: 5px;
    font-size: 1.2rem;
}
    </style>
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
                 <i class="fas fa-clock" style="color: #bfa15f; font-size: 1.5rem;"></i>
                <h2 style="margin-right: 10px;"> جداول التوقيت </h2>
            </div>
            
        </header>

        <div class="tabs-nav">
            <button class="tab-btn active" onclick="switchTable('personal')">برنامجي الشخصي</button>
            <button class="tab-btn" onclick="switchTable('school')">برنامج المدرسة </button>
        </div>

        <div id="personal-table" class="schedule-container">
            
            <table class="classic-table">
                <thead>
                    <tr>
                        <th>اليوم</th>
                        <th>08:00 - 10:00</th>
                        <th>10:00 - 12:00</th>
                        <th>14:00 - 16:00</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="day-column">السبت</td>
                        <td><div class="my-session"><span class="group-name">فوج النور</span><span class="room-name">قاعة 01</span></div></td>
                        <td class="empty-slot">.........</td>
                        <td><div class="my-session"><span class="group-name">فوج الفردوس</span><span class="room-name">قاعة 03</span></div></td>
                    </tr>
                    <tr>
                        <td class="day-column">الأحد</td>
                        <td class="empty-slot">.........</td>
                        <td><div class="my-session"><span class="group-name">فوج اليقين</span><span class="room-name">قاعة 02</span></div></td>
                        <td class="empty-slot">.........</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="school-table" class="schedule-container" style="display: none;">
    
    <table class="classic-table">
        <thead>
            <tr>
                <th>اليوم / الوقت</th>
                <th>08:00 - 10:00</th>
                <th>10:00 - 12:00</th>
                <th>14:00 - 16:00</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="day-column">السبت</td>
                <td>
                    <div class="other-session">
                        <span class="prof-name">أ. محمد</span><br>
                        <small class="room-tag">قاعة 01</small>
                    </div>
                </td>
                <td>
                    <div class="empty-slot">.........</div>
                </td>
                <td>
                    <div class="other-session">
                        <span class="prof-name">أ. سارة</span><br>
                        <small class="room-tag">قاعة 03</small>
                    </div>
                </td>
            </tr>

            <tr>
                <td class="day-column">الأحد</td>
                <td>
                    <div class="empty-slot">.........</div>
                </td>
                <td>
                    <div class="other-session">
                        <span class="prof-name">أ. ليلى</span><br>
                        <small class="room-tag">قاعة 02</small>
                    </div>
                </td>
                <td>
                    <div class="empty-slot">.........</div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
    </main>

    <script>
        function switchTable(type) {
            // إخفاء الجداول
            document.getElementById('personal-table').style.display = 'none';
            document.getElementById('school-table').style.display = 'none';
            
            // إظهار المطلوب
            document.getElementById(type + '-table').style.display = 'block';
            
            // تحديث الأزرار
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');
        }
    </script>
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