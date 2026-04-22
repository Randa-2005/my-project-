<?php 
  $current_page = 'dash'; 
  include 'reception_sidebar.php'; 
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قوائم التلاميذ  </title>
    <link rel="stylesheet" href="reception_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-green: #1a472a; --bg-gray: #f4f7f6; --sidebar-width: 260px; } /* حددنا عرض السيدبار هنا */
        
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: var(--bg-gray); 
            margin: 0; 
            display: flex; /* للسماح للسيدبار والمحتوى بالبقاء بجانب بعض */
        }

        /* تعديل المحتوى الرئيسي ليبدأ بعد السيدبار */
        .main-content { 
            flex: 1; 
            margin-right: var(--sidebar-width); /* دفع المحتوى لليسار بمقدار عرض السيدبار */
            padding: 30px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        .main-container { 
            max-width: 1000px; 
            margin: auto; 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            padding: 25px; 
        }

        /* منطقة التحكم */
        .controls-section { 
            display: flex; 
            align-items: flex-end; 
            gap: 15px; 
            margin-bottom: 25px; 
            background: #fff; 
            padding: 20px; 
            border-radius: 10px; 
            border: 1px solid #eee; 
        }

        .filter-group { flex: 1; }
        .filter-group label { display: block; margin-bottom: 8px; font-weight: bold; color: var(--primary-green); }
        .input-field { width: 100%; height: 45px; border: 1px solid #ddd; border-radius: 8px; padding: 0 10px; }

        .btn-print { 
            background: var(--primary-green); 
            color: white; 
            border: none; 
            height: 45px; 
            padding: 0 25px; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: bold;
            display: flex; 
            align-items: center; 
            gap: 10px;
        }

        /* رأس القائمة الاحترافي */
        .list-header-info { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            background: var(--primary-green); 
            color: white; 
            padding: 15px 25px; 
            border-radius: 8px 8px 0 0;
            margin-top: 20px;
        }

        /* الجدول */
        .students-table { width: 100%; border-collapse: collapse; background: white; border: 1px solid #eee; }
        .students-table th { background: #f8f9fa; color: var(--primary-green); padding: 15px; border: 1px solid #eee; font-size: 0.95rem; }
        .students-table td { padding: 12px; border: 1px solid #eee; text-align: center; color: #333; }

        /* ستايل الطباعة - يخفي السيدبار تماماً */
        @media print {
            .main-content { margin-right: 0 !important; padding: 0 !important; }
            .sidebar, .controls-section, .btn-print, .reception-sidebar { display: none !important; }
            body { background: white; }
            .main-container { box-shadow: none; width: 100%; max-width: 100%; }
        }

        /* للهواتف: إذا كان السيدبار يختفي */
        @media (max-width: 768px) {
            .main-content { margin-right: 0; padding: 15px; }
            .controls-section { flex-direction: column; align-items: stretch; }
            .btn-print { width: 100%; justify-content: center; }
        }
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
        <div class="main-container">
            <h2 style="color: var(--primary-green); border-right: 5px solid var(--primary-green); padding-right: 15px; margin-bottom: 25px;">
                <i class="fas fa-list-ul"></i> قوائم تلاميذ الأفواج
            </h2>

            <div class="controls-section">
                <div class="filter-group">
                    <label>تصفية حسب الفوج:</label>
                    <select id="groupSelect" class="input-field" style="flex: 1; height: 45px; margin: 0;" onchange="loadStudentList()">
                        <option value="">-- اختر الفوج لعرض القائمة --</option>
                        <option value="g1">فوج 1 إناث</option>
                        <option value="g2">فوج 2 ذكور</option>
                    </select>
                </div>
                <button class="btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> طباعة القائمة
                </button>
            </div>

            <div id="listInfo" style="display: none;">
                <div class="list-header-info">
                    <div id="displayGroupName"><i class="fas fa-users"></i> الفوج: ---</div>
                    <div id="displayTeacherName"><i class="fas fa-chalkboard-teacher"></i> الأستاذ: ---</div>
                </div>

                <table class="students-table">
                    <thead>
                        <tr>
                            <th style="width: 20%;">رقم التسجيل</th>
                            <th style="width: 50%;">الاسم واللقب</th>
                            <th style="width: 30%;">تاريخ الازدياد</th>
                        </tr>
                    </thead>
                    <tbody id="studentsBody">
                        </tbody>
                </table>
            </div>
        </div>
    </main>

<script>
    const studentsData = {
        g1: {
            groupName: "فوج 1 إناث",
            teacher: "أ. بن قاسم ياسين",
            students: [
                { regNum: "REG-2026-01", name: "نور الهدى بوقرة", birthDate: "2010-01-15" },
                { regNum: "REG-2026-02", name: "إيمان بن ساعد", birthDate: "2009-05-22" }
            ]
        },
        g2: {
            groupName: "فوج 2 ذكور",
            teacher: "أ. مراد بوعلام",
            students: [
                { regNum: "REG-2026-10", name: "محمد علي جبار", birthDate: "2011-03-10" },
                { regNum: "REG-2026-11", name: "سامي بوعبد الله", birthDate: "2010-12-05" }
            ]
        }
    };

    function loadStudentList() {
        const val = document.getElementById('groupSelect').value;
        const container = document.getElementById('listInfo');
        const body = document.getElementById('studentsBody');
        
        if (val && studentsData[val]) {
            container.style.display = 'block';
            const data = studentsData[val];
            
            document.getElementById('displayGroupName').innerHTML = `<i class="fas fa-users"></i> الفوج: ${data.groupName}`;
            document.getElementById('displayTeacherName').innerHTML = `<i class="fas fa-chalkboard-teacher"></i> الأستاذ: ${data.teacher}`;

            body.innerHTML = data.students.map(s => `
                <tr>
                    <td><strong>${s.regNum}</strong></td>
                    <td style="text-align: right; padding-right: 25px;">${s.name}</td>
                    <td>${s.birthDate}</td>
                </tr>
            `).join('');
        } else {
            container.style.display = 'none';
        }
    }
</script>

</body>
</html>