<?php

include('student_sidebar.php'); // لضمان بقاء القائمة الجانبية
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>توقيت الحلقات - مدرسة أسرتي</title>
    <link rel="stylesheet" href="student_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
 <style>

    .close-sidebar-btn { visibility: visible !important; opacity: 1 !important; }
    </style>
</head>
<body>

<div class="main-content">
    <header class="page-header">
        <h2><i class="fas fa-calendar-alt"></i> البرنامج الأسبوعي للحلقات</h2>
        <p>جدول يوضح مواعيد الحصص المباشرة مع الأساتذة</p>
    </header>

    <div class="schedule-grid">
        <div class="table-container">
    <table class="modern-table">
        <thead>
            <tr>
                <th>اليوم</th>
                <th>الفترة</th>
                <th>الوقت</th>
                <th>اسم الحلقة</th>
                <th>الأستاذ(ة)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>الأحد</td>
                <td><span class="period-tag morning-green">صباحية</span></td>
                <td>08:00 - 10:00</td>
                <td>حلقة الفردوس</td>
                <td>أ. محمد أحمد</td>
            </tr>
            <tr>
                <td>الثلاثاء</td>
                <td><span class="period-tag evening-gold">مسائية</span></td>
                <td>16:30 - 18:30</td>
                <td>حلقة النور</td>
                <td>أ. سارة علي</td>
            </tr>
        </tbody>
    </table>
</div>
      

        <div class="info-card-side">
            <h4><i class="fas fa-info-circle"></i> ملاحظات هامة</h4>
            <ul>
                <li>يرجى الحضور قبل موعد الحلقة بـ 5 دقائق.</li>
                <li>التوقيت المذكور أعلاه حسب توقيت الجزائر.</li>
                <li>في حال غياب الأستاذ، سيتم إعلامكم عبر الإشعارات.</li>
            </ul>
        </div>
    </div>
</div>
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
</script>

</body>
</html>