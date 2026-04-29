
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الإعلانات - مدرسة أسرتي القرآنية</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="teacher_style.css"> <style>
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
        .sidebar.sidebar-closed { width: 0; overflow: hidden !important; }
        .main-content { flex: 1; margin-right: 260px; transition: 0.4s; padding: 25px; }
        .main-content.full-width { margin-right: 0; }

        #open-sidebar { cursor: pointer; font-size: 1.5rem; color: var(--main-green); display: none; margin-bottom: 20px; }

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
    color: #0d3c1a;
    padding: 10px;
    z-index: 9999; /* لضمان ظهوره فوق كل شيء */
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
    class="<?php echo ($current_page == 'profile.php') ? 'active-gold' : ''; ?>">
    <i class="fas fa-user-cog"></i> <span>الملف الشخصي</span>
</li>
      <li><i class="fas fa-sign-out-alt"></i> خروج</li>
</ul>
    </aside>
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
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.getElementById('mainContent');
    const closeBtn = document.getElementById('close-sidebar');
    const openBtn = document.getElementById('open-sidebar-btn'); // استخدام المعرف الجديد

    // عند الضغط على السهم (إغلاق)
    closeBtn.onclick = function() {
        sidebar.classList.add('sidebar-closed');
        mainContent.classList.add('full-width');
        openBtn.style.display = 'block'; // إظهار الزر
    };

    // عند الضغط على الثلاث خطوط (فتح)
    openBtn.onclick = function() {
        sidebar.classList.remove('sidebar-closed');
        mainContent.classList.remove('full-width');
        this.style.display = 'none'; // إخفاء الزر مرة أخرى
    };
</script>
</body>
</html>