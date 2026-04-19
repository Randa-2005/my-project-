<?php
// تأكدي من تضمين ملف الاتصال والـ Sidebar في البداية
 
include('student_sidebar.php'); 
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سجل حفظي - مدرسة أسرتي القرآنية</title>
    <link rel="stylesheet" href="student_style.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="main-content"> <header class="page-header">
        <h2><i class="fas fa-book-reader"></i> سجل الحفظ والمراجعة</h2>
        <p>تتبع مسار حفظك ومراجعتك اليومية بالتفصيل</p>
    </header>

    <div class="stats-container">
        
        <div class="stat-card clickable-card" onclick="openSurahsModal()">
            <div class="stat-icon"><i class="fas fa-book-open"></i></div>
            <div class="stat-info">
                <p>إجمالي الحفظ</p>
                <h3>45 سورة</h3>
                <small>إضغط لعرض التفاصيل ✨</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
            <div class="stat-info">
                <p>المستوى الحالي</p>
                <h3>ممتاز</h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <p>آخر مراجعة</p>
                <h3>منذ يومين</h3>
            </div>
        </div>
    </div>

 <div class="table-container">
    <div class="table-header-main">
        <h3><i class="fas fa-history"></i> آخر حصص الحفظ</h3>
    </div>
    
    <div class="custom-table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>السورة</th>
                    <th>المقدار</th>
                    <th>نوع الحصة</th>
                    <th>التقييم</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>09 أبريل 2026</td>
                    <td>الضحى</td>
                    <td>كاملة</td>
                    <td>حفظ جديد</td>
                    <td><span class="rank-badge excellent">ممتاز</span></td>
                </tr>
                </tbody>
        </table>
    </div>
</div>   

</div> <div id="surahModal" class="custom-modal">
    <div class="modal-content style-premium">
        
        <div class="modal-header-premium">
            <h3 class="modal-title">سجل السور المحفوظة 📖</h3>
            <span class="close-modal" onclick="closeSurahsModal()">&times;</span>
        </div>
        
        <div class="modal-body-scrollable">
            <div class="status-legend">
                <div class="legend-item saved"><span class="dot green"></span> محفوظة</div>
                <div class="legend-item current"><span class="dot orange"></span> قيد الحفظ</div>
                <div class="legend-item remaining"><span class="dot gray"></span> لم تحفظ</div>
            </div>

            <div class="surahs-grid-new">
                <div class="surah-item saved">الفاتحة</div>
                <div class="surah-item saved">البقرة</div>
                <div class="surah-item saved">آل عمران</div>
                <div class="surah-item current pulse-glow">الضحى</div>
                <div class="surah-item remaining">النبأ</div>
                <div class="surah-item remaining">النازعات</div>
                <div class="surah-item remaining">عبس</div>
            </div>
        </div>

        <div class="modal-footer-box">
            <p>بالتوفيق يا بطل، واصل اجتهادك! ✨</p>
        </div>
    </div>
</div>

<script>
function openSurahsModal() {
    const modal = document.getElementById('surahModal');
    modal.classList.add('active'); // إضافة كلاس الإظهار
}

function closeSurahsModal() {
    const modal = document.getElementById('surahModal');
    modal.classList.remove('active'); // إزالة كلاس الإظهار
}

// إغلاق عند الضغط خارج النافذة
window.onclick = function(event) {
    const modal = document.getElementById('surahModal');
    if (event.target == modal) {
        closeSurahsModal();
    }
}
</script>
</body>
</html>