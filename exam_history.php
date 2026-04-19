<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سجل نتائج الاختبارات - فوج النور</title>
    <link rel="stylesheet" href="teacher_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #1a472a;
            --light-green: #f0f4f1;
            --gold: #ffc107;
            --white: #ffffff;
            --sidebar-width: 250px; /* تحديد عرض السايدبار هنا */
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0; /* تم تصفير البادينج الأساسي */
            display: flex;
        }

        /* تنظيم المحتوى الرئيسي ليظهر بجانب السايدبار وليس تحته */
        .main-content {
            flex: 1;
            margin-right: var(--sidebar-width); /* هذا الهامش يمنع التداخل مع السايدبار */
            padding: 25px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* الهيدر */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--white);
            padding: 15px 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }

        .top-bar h2 { color: var(--primary-green); margin: 0; }

        /* بطاقات الإحصائيات */
        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-box {
            background: var(--white);
            padding: 15px 20px;
            border-radius: 12px;
            flex: 1;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* الجدول التاريخي */
        .table-wrapper {
            width: 100%; /* يأخذ كامل المساحة المتاحة بجانب السايدبار */
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table th {
            background: var(--primary-green);
            color: var(--white);
            padding: 15px;
            text-align: right;
        }

        .history-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            color: #444;
        }

        .score-tag {
            background: var(--light-green);
            padding: 4px 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .final-grade {
            color: var(--primary-green);
            font-weight: bold;
        }

        .status-pass {
            background: #d4edda;
            color: #155724;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        /* أزرار التحكم */
        .btn-print, .btn-home {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-print { background: var(--gold); color: #333; }
        .btn-home { background: var(--primary-green); color: white; margin-right: 10px; }

        /* تأكيد ثبات السايدبار لكي لا يتحرك مع التمرير */
        .sidebar {
            position: fixed;
            right: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            z-index: 1000;
        }
        .filter-section {
    display: flex;
    gap: 15px;
    background: var(--white);
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    align-items: center;
}

.search-box, .date-filter {
    position: relative;
    flex: 1;
}

.search-box i, .date-filter i {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-green);
}

.search-box input, .date-filter input {
    width: 100%;
    padding: 10px 35px 10px 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
}

.btn-reset {
    background: #e9ecef;
    border: none;
    padding: 10px 15px;
    border-radius: 8px;
    cursor: pointer;
    color: #495057;
    transition: 0.3s;
}

.btn-reset:hover { background: #dee2e6; }
.group-filter {
    position: relative;
    flex: 1;
}

.group-filter i {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-green);
    pointer-events: none;
}

.group-filter select {
    width: 100%;
    padding: 10px 35px 10px 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
    appearance: none; /* لإخفاء سهم المتصفح الافتراضي */
    cursor: pointer;
}
    </style>
</head>
<body>
    <aside class="sidebar">
        <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
        <div class="sidebar-header">
            <button id="close-sidebar" class="close-sidebar-btn"><i class="fas fa-arrow-right"></i></button>
            <div style="padding: 25px 15px; text-align: center; border-bottom: 1px solid rgba(191, 161, 95, 0.2);">
                <i class="fas fa-chalkboard-teacher" style="font-size: 45px; color: #bfa15f; margin-bottom: 12px; display: block; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));"></i>
                <h3 style="color: #bfa15f; font-size: 20px; font-family: 'Cairo', sans-serif; font-weight: bold; margin: 0; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                    لوحة الأستاذ
                </h3>
            </div>
        </div>   

        <ul class="nav-links">
            <li onclick="location.href='teacher_groups.php'" class="<?php echo ($current_page == 'teacher_groups.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-home"></i> الرئيسية
            </li>
            <li onclick="location.href='groups_list.php'" class="<?php echo ($current_page == 'groups_list.php' || $current_page == 'manage_session.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-users"></i> قوائم التلاميذ
            </li>
            <li onclick="location.href='exam_history.php'" class="<?php echo ($current_page == 'exam_history.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-file-invoice"></i> <span>سجل الامتحانات</span>
            </li>
            <li onclick="location.href='general_schedule.php'" class="<?php echo ($current_page == 'general_schedule.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-calendar-alt"></i> البرنامج العام
            </li>
            <li onclick="location.href='announcements.php'" class="<?php echo ($current_page == 'announcements.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-bullhorn"></i> الإعلانات
            </li>
            <li onclick="location.href='teacher_leave.php'" class="<?php echo ($current_page == 'teacher_leave.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-calendar-times"></i> <span>طلب عطلة</span>
            </li>
            <li onclick="location.href='profile.php'" class="<?php echo ($current_page == 'profile.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-user-cog"></i> <span>الملف الشخصي</span>
            </li>
            <li><i class="fas fa-sign-out-alt"></i> خروج</li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="top-bar">
            <h2><i class="fas fa-history"></i> سجل نتائج الاختبارات</h2>
            
                <button class="btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> طباعة السجل
                </button>
                
            
        </header>

        <div class="stats-container">
            <div class="stat-box">
                <i class="fas fa-clipboard-list"></i>
                <span>إجمالي الاختبارات: <strong>12</strong></span>
            </div>
            <div class="stat-box">
                <i class="fas fa-star" style="color: #ffc107;"></i>
                <span>أعلى معدل فوج: <strong>18.5</strong></span>
            </div>
        </div>
        <div class="filter-section">
    <div class="group-filter">
        <i class="fas fa-layer-group"></i>
        <select id="groupSelect" onchange="filterResults()">
            <option value="">كل الأفواج</option>
            <option value="فوج النور">فوج النور</option>
            <option value="فوج الهدى">فوج الهدى</option>
            <option value="فوج الترتيل">فوج الترتيل</option>
        </select>
    </div>

    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="studentSearch" placeholder="ابحث عن اسم الطالب..." onkeyup="filterResults()">
    </div>

    <div class="date-filter">
        <i class="fas fa-calendar-day"></i>
        <input type="date" id="dateSearch" onchange="filterResults()">
    </div>

    <button class="btn-reset" onclick="resetFilters()">
        <i class="fas fa-undo"></i> إعادة ضبط
    </button>
</div>

        <div class="table-wrapper">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>اسم الطالب</th>
                        <th>حفظ (8)</th>
                        <th>أحكام (8)</th>
                        <th>مخارج (4)</th>
                        <th>المجموع</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2026-04-14</td>
                        <td>أحمد محمد</td>
                        <td><span class="score-tag">7</span></td>
                        <td><span class="score-tag">6</span></td>
                        <td><span class="score-tag">4</span></td>
                        <td class="final-grade">17 / 20</td>
                        <td><span class="status-pass">ناجح</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
<script>
  function filterResults() {
    let nameInput = document.getElementById('studentSearch').value.toLowerCase();
    let dateInput = document.getElementById('dateSearch').value;
    let groupInput = document.getElementById('groupSelect').value; // جلب قيمة الفوج المختار
    
    let table = document.querySelector('.history-table');
    let rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        let dateCol = rows[i].getElementsByTagName('td')[0].innerText;
        let nameCol = rows[i].getElementsByTagName('td')[1].innerText.toLowerCase();
        
        // هنا المنطق: إذا كان الفوج فارغاً أو يطابق بيانات مخفية في السطر (سنضعها لاحقاً بالـ PHP)
        let nameMatch = nameCol.includes(nameInput);
        let dateMatch = dateInput === "" || dateCol.includes(dateInput);

        if (nameMatch && dateMatch) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}

function resetFilters() {
    document.getElementById('studentSearch').value = "";
    document.getElementById('dateSearch').value = "";
    filterResults(); // إعادة إظهار كل الأسطر
}

    </script>
</body>
</html>