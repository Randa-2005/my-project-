<?php
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>برنامج الأفواج والقاعات </title>
    <link rel="stylesheet" href="reception_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-green: #1a472a; --accent-gold: #ffc107; }
        .main-container { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; }
        
        /* شريط العنوان الديناميكي */
        .status-bar { display: flex; justify-content: space-between; align-items: center; background: var(--primary-green); color: white; padding: 15px 25px; }

        .schedule-table { width: 100%; border-collapse: collapse; }
        .schedule-table th { background: #f8f9fa; color: var(--primary-green); padding: 12px; border: 1px solid #eee; font-size: 0.9rem; }
        .schedule-table td { padding: 8px; border: 1px solid #eee; text-align: center; vertical-align: top; width: 14%; }

        /* بطاقة الحصة في العرض العام */
        .general-card { background: #f0f4f1; border-bottom: 3px solid var(--primary-green); padding: 8px; border-radius: 4px; margin-bottom: 5px; font-size: 0.85rem; }
        .general-card .group-name { font-weight: bold; color: #333; display: block; }
        .general-card .room-label { color: var(--primary-green); font-weight: bold; font-size: 0.8rem; }

        /* بطاقة الحصة في العرض المخصص */
        .special-card { background: #fff3cd; border-right: 4px solid var(--accent-gold); padding: 10px; border-radius: 5px; text-align: right; }
        
        .time-label { background: #fdfdfd; font-weight: bold; color: var(--primary-green); width: 90px !important; }
        /* تنسيق زر القائمة */
.open-sidebar-btn {
    display: none; /* مخفي افتراضياً في الشاشات الكبيرة */
    position: fixed;
    top: 15px;
    right: 15px; /* يظهر من جهة اليمين لأن اللغة عربية */
    background: #1a472a;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.2rem;
    z-index: 1001; /* ليكون فوق كل العناصر */
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

/* إظهار الزر عند تصغير الشاشة (أقل من 768px) */
@media (max-width: 768px) {
    .open-sidebar-btn {
        display: block;
    }
    
    .main-content {
        margin-right: 0 !important; /* إلغاء الهامش في الشاشات الصغيرة */
        padding-top: 60px; /* ترك مساحة للزر في الأعلى */
    }
}
    </style>
</head>
<body>

    <?php include 'reception_sidebar.php'; ?>

    <main class="main-content">
        <button id="open-sidebar" class="open-sidebar-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="header">
            <h2><i class="fas fa-chalkboard-teacher"></i> توزيع الأفواج والقاعات</h2>
        </div>

        <div class="content-wrapper" style="padding: 20px;">
            
            <div class="stat-card" style="display: flex; align-items: flex-end; gap: 20px; margin-bottom: 20px; background: white; padding: 20px; border-radius: 12px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">تصفية حسب الفوج (أو اتركها للعرض العام):</label>
                    <select id="groupSelector" class="input-field" style="flex: 1; height: 45px; margin: 0;" onchange="toggleView()">
                        <option value="all">-- عرض البرنامج العام (كل الأفواج) --</option>
                        <option value="g1">الفوج 1 إناث</option>
                        <option value="g2">الفوج 2 ذكور</option>
                    </select>
                </div>
                
                <button class="confirm-btn" onclick="printSchedule()"><i class="fas fa-print"></i> طباعة البرنامج</button>
            </div>

            <div class="main-container">
                <div class="status-bar">
                    <div id="viewTitle"><i class="fas fa-globe"></i> البرنامج العام للقاعات</div>
                    <div id="viewDetail">كل الأفواج | السبت - الخميس</div>
                </div>

                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>الوقت</th>
                            <th>السبت</th>
                            <th>الأحد</th>
                            <th>الاثنين</th>
                            <th>الثلاثاء</th>
                            <th>الأربعاء</th>
                            <th>الخميس</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleBody">
                        </tbody>
                </table>
            </div>
        </div>
    </main>
    <script>
    const scheduleData = {
        all: `
            <tr>
                <td class="time-label">08:00 - 10:00</td>
                <td><div class="general-card"><span class="group-name">فوج 1 إناث</span><span class="room-label">قاعة 01</span></div></td>
                <td><div class="general-card"><span class="group-name">فوج 3 ذكور</span><span class="room-label">قاعة 05</span></div></td>
                <td><div class="general-card"><span class="group-name">فوج 1 إناث</span><span class="room-label">قاعة 01</span></div></td>
                <td>---</td>
                <td>---</td>
                <td><div class="general-card"><span class="group-name">فوج 2 إناث</span><span class="room-label">مخبر 01</span></div></td>
            </tr>`,
        g1:` 
            <tr>
                <td class="time-label">08:00 - 10:00</td>
                <td><div class="special-card"><b>رياضيات</b><span>أ. بن قاسم</span><br><span class="room-label">قاعة 01</span></div></td>
                <td>---</td>
                <td><div class="special-card"><b>فيزياء</b><span>أ. مراد</span><br><span class="room-label">قاعة 05</span></div></td>
                <td>---</td>
                <td>---</td>
                <td>---</td>
            </tr>`
    };

    function toggleView() {
        const val = document.getElementById('groupSelector').value;
        const body = document.getElementById('scheduleBody');
        const title = document.getElementById('viewTitle');
        const detail = document.getElementById('viewDetail');

        if(val === "all") {
            title.innerHTML = '<i class="fas fa-globe"></i> البرنامج العام للقاعات';
            detail.innerText = "كل الأفواج | السبت - الخميس";
            body.innerHTML = scheduleData.all;
        } else if(val === "g1") {
            title.innerHTML = '<i class="fas fa-user-grad"></i> الفوج 1 إناث';
            detail.innerText = "الأستاذ: بن قاسم ياسين";
            body.innerHTML = scheduleData.g1;
        } else {
            body.innerHTML = '<tr><td colspan="7" style="padding:30px;">جاري تحميل بيانات الفوج...</td></tr>';
        }
    }

    // تشغيل العرض العام عند فتح الصفحة مباشرة
    window.onload = toggleView;
    function printSchedule() {
    // إخفاء الشريط الجانبي والأزرار أثناء الطباعة
    window.print();
    }
    </script>
</body>
</html>