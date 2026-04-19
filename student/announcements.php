<?php
// تأكدي من تضمين ملف الاتصال والـ Sidebar في البداية
 
include('student_sidebar.php'); 
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الإعلانات - مدرسة أسرتي القرآنية</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="student_style.css"> <style>
        :root { --main-green: #0d3c1a; --gold: #bfa15f; --bg-gray: #f8f9fa; }
        body { font-family: 'Cairo', sans-serif; background-color: var(--bg-gray); margin: 0; display: flex; }

        /* تنسيق السايدبار والمحتوى (الموحد) */
        .sidebar {
    width: 250px;
    position: fixed;
    height: 100vh;
    transition: all 0.4s ease;
    z-index: 1000;
    
    /* اللون الأخضر الذي اخترناه */
    background-color: #0d3c1a; 
    
    /* رابط لنقش إسلامي هندسي واضح جداً */
    background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
    
    /* هذه الخاصية ضرورية جداً لدمج النقش مع الأخضر */
    background-blend-mode: multiply; 
    
    /* لتكرار النقش بشكل صحيح */
    background-repeat: repeat;
    background-size: 100px; /* جربي تغيير هذا الرقم (200px أو 400px) حتى يظهر الحجم كما في الصورة */
}
        .sidebar.sidebar-closed { width: 0; }
        .main-content { flex: 1; margin-right: 260px; transition: 0.4s; padding: 25px; }
        .main-content.full-width { margin-right: 0; }

        /* حاوية الإعلانات */
        .announcements-wrapper { max-width: 850px; margin: 0 auto; }
        
        .page-header { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; border-bottom: 3px solid var(--gold); padding-bottom: 10px; }
        .page-header i { color: var(--gold); font-size: 1.8rem; }
        .page-header h2 { color: var(--main-green); margin: 0; }

        /* بطاقة الإعلان */
        .ann-card { 
            background: white; 
            border-radius: 12px; 
            padding: 20px; 
            margin-bottom: 20px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
            border-right: 6px solid var(--gold); 
            transition: 0.3s;
            position: relative;
        }
        .ann-card:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.1); }
        
        /* أنواع الإعلانات بالوان مختلفة */
        .ann-card.urgent { border-right-color: #d9534f; } /* أحمر للتنبيهات العاجلة */
        .ann-card.event { border-right-color: #5bc0de; } /* أزرق للنشاطات */

        .ann-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .ann-date { font-size: 0.85rem; color: #999; }
        .ann-tag { font-size: 0.75rem; padding: 4px 10px; border-radius: 15px; background: #f0f0f0; color: #666; }
        
        .ann-title { color: var(--main-green); margin: 0 0 10px 0; font-size: 1.2rem; display: flex; align-items: center; gap: 8px; }
        .ann-body { color: #555; line-height: 1.7; font-size: 0.95rem; }
        
        /* أيقونة "جديد" */
        .new-badge { position: absolute; top: -10px; left: -10px; background: #d9534f; color: white; padding: 5px 10px; border-radius: 8px; font-size: 0.7rem; font-weight: bold; transform: rotate(-10deg); }
        /* عندما يختفي السايدبار تماماً */
.sidebar.sidebar-closed {
    transform: translateX(100%); /* يخرج من جهة اليمين */
    width: 0;
}

/* توسيع المحتوى الرئيسي ليملأ الشاشة */
.main-content.full-width {
    margin-right: 0 !important;
}

/* إظهار أيقونة الثلاث خطوط فقط عندما يكون السايدبار مغلقاً */
.sidebar-closed ~ .main-content #open-sidebar {
    display: inline-block !important;
}
/* تأكدي أن هذا التنسيق موجود في ملف الـ CSS أو في وسم <style> */
#open-sidebar {
    cursor: pointer;
    font-size: 1.8rem;
    color: #2e7d32;
    padding: 10px;
    z-index: 1100; /* لضمان ظهوره فوق كل شيء */
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



    <main class="main-content" id="mainContent">
           <button id="open-sidebar-btn" class="toggle-btn" style="display: none; border: none; background: none;">
    <i class="fas fa-bars" style="font-size: 1.5rem; color: #4a5d4e; cursor: pointer;"></i>
</button>
        <div class="announcements-wrapper">
            <div class="page-header">
                <i class="fas fa-bullhorn"></i>
                <h2>إعلانات المدرسة</h2>
            </div>

            <div class="ann-card urgent">
                <span class="new-badge">جديد</span>
                <div class="ann-meta">
                    <span class="ann-tag">تنبيه إداري</span>
                    <span class="ann-date"><i class="far fa-clock"></i> منذ ساعة</span>
                </div>
                <h4 class="ann-title">تغيير في مواقيت الدوام المسائي</h4>
                <p class="ann-body">
                    نعلم كافة الأساتذة الكرام أنه سيتم تقديم مواقيت الحصص المسائية بـ 15 دقيقة ابتداءً من يوم الأحد القادم، يرجى الالتزام بالمواعيد الجديدة.
                </p>
            </div>

            <div class="ann-card">
                <div class="ann-meta">
                    <span class="ann-tag">إعلان عام</span>
                    <span class="ann-date"><i class="far fa-clock"></i> أمس</span>
                </div>
                <h4 class="ann-title">فتح باب التسجيل للمسابقة القرآنية الكبرى</h4>
                <p class="ann-body">
                    تم إطلاق استمارة التسجيل الإلكترونية لمسابقة "رتل وارتق" السنوية. يرجى من الأساتذة تشجيع التلاميذ المتميزين على المشاركة.
                </p>
            </div>

            <div class="ann-card event">
                <div class="ann-meta">
                    <span class="ann-tag">نشاطات</span>
                    <span class="ann-date"><i class="far fa-clock"></i> 05 أفريل 2026</span>
                </div>
                <h4 class="ann-title">ندوة تربوية حول طرق التحفيظ الحديثة</h4>
                <p class="ann-body">
                    ستقام ندوة تفاعلية يوم السبت القادم في قاعة المحاضرات الكبرى، الحضور إلزامي لكافة أساتذة المدرسة.
                </p>
            </div>
        </div>
    </main>

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