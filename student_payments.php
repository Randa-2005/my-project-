<?php 
  include 'db_connect.php';

// هنا يمكنكِ لاحقاً إضافة الكود الخاص بجلب البيانات من قاعدة البيانات
  $current_page = 'dash'; 
  include 'reception_sidebar.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سجل مداخيل التلاميذ </title>
    <link rel="stylesheet" href="reception_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
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
        <div class="top-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-family: 'Cairo'; color: #1a472a;">سجل مداخيل التلاميذ</h2>
            <div class="search-box" style="position: relative;">
                <input type="text" placeholder="ابحث عن اسم تلميذ..." class="cairo-input" style="width: 350px; padding-right: 40px;">
                <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #bfa15f;"></i>
            </div>
        </div>

        <div class="stats-container" style="gap: 15px; margin-bottom: 30px;">
            <div class="stat-card" style="border-right: 5px solid #28a745;">
                <div class="stat-info">
                    <h3>مجموع المقبوضات (اليوم)</h3>
                    <p class="stat-number">45,000 د.ج</p>
                </div>
                <i class="fas fa-money-bill-trend-up" style="color: #28a745;"></i>
            </div>
            
            <div class="stat-card" style="border-right: 5px solid #dc3545;">
                <div class="stat-info">
                    <h3>الديون المتبقية</h3>
                    <p class="stat-number">12,500 د.ج</p>
                </div>
                <i class="fas fa-hand-holding-dollar" style="color: #dc3545;"></i>
            </div>
        </div>

        <div class="table-section" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
            <table class="requests-table">
                <thead>
                    <tr>
                        <th>رقم الوصل</th>
                        <th>اسم التلميذ</th>
                        <th>البيان (نوع الدفعة)</th>
                        <th>المبلغ المدفوع</th>
                        <th>التاريخ والوقت</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#REC-2026-001</td>
                        <td>محمد بن علي</td>
                        <td>دفع شهر أفريل (رياضيات)</td>
                        <td style="font-weight: bold; color: #28a745;">3,500 د.ج</td>
                        <td>11/04/2026 | 10:30</td>
                        <td>
                            <button class="confirm-btn" style="padding: 5px 12px; background: #bfa15f;">
                                <i class="fas fa-print"></i> طباعة
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        // هنا يمكنكِ إضافة سكريبت الفلترة والبحث
    </script>
</body>
</html>