<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الطالب - مدرسة أسرتي</title>
    <link rel="stylesheet" href="student_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --main-green: #2e7d32;
            --gold: #bfa15f;
            --soft-white: #f8f9fa;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--soft-white);
            margin: 0;
            display: flex;
        }

        /* حاوية البطاقات العلوية */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-bottom: 4px solid var(--gold);
            transition: transform 0.3s;
        }

        .stat-card:hover { transform: translateY(-5px); }

        .stat-info h3 { margin: 0; color: var(--main-green); font-size: 1.8rem; }
        .stat-info p { margin: 5px 0 0; color: #666; font-size: 0.9rem; }
        .stat-icon {
            background: rgba(191, 161, 95, 0.1);
            color: var(--gold);
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* شريط التقدم */
        .progress-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .progress-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .progress-bar-bg { background: #eee; height: 12px; border-radius: 10px; overflow: hidden; }
        .progress-bar-fill { 
            background: linear-gradient(90deg, var(--main-green), var(--gold)); 
            height: 100%; 
            width: 65%; /* هذه ستتغير مستقبلاً بالـ PHP */
            border-radius: 10px;
        }

        /* القسم السفلي (الملاحظة والجدول) */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .info-box {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .info-box h4 { 
            color: var(--main-green); 
            border-right: 4px solid var(--gold); 
            padding-right: 10px; 
            margin-top: 0;
        }

        .teacher-note {
            background: #fdfaf3;
            border-radius: 10px;
            padding: 15px;
            border-right: 4px solid var(--gold);
            font-style: italic;
            color: #555;
        }

    /* لضمان عدم وجود أي شيء يغطي الزر في الصفحة الرئيسية */
    .close-sidebar-btn { visibility: visible !important; opacity: 1 !important; }
 
    </style>
</head>
<body>

    <?php include 'student_sidebar.php'; ?>
    <button class="toggle-menu-btn" id="open-sidebar">
    <i class="fas fa-bars"></i>
</button>

    <main class="main-content" id="mainContent" style="flex: 1; padding: 30px; margin-right: 260px;">
        
        <header style="margin-bottom: 30px;">
            <h2 style="color: var(--main-green);">أهلاً بك يا بطل، نور! ✨</h2>
            <p style="color: #666;">إليك ملخص إنجازاتك في حفظ القرآن الكريم</p>
        </header>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>15</h3>
                    <p>حزباً محفوظاً</p>
                    </div>
                <div class="stat-icon"><i class="fas fa-book-open"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>98%</h3>
                    <p>نسبة الحضور</p>
                </div>
                <div class="stat-icon"><i class="fas fa-user-check"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>الأول</h3>
                    <p>ترتيبك بالفوج</p>
                </div>
                <div class="stat-icon"><i class="fas fa-trophy"></i></div>
            </div>
        </div>

        <div class="progress-section">
            <div class="progress-header">
                <span style="font-weight: bold; color: var(--main-green);">التقدم نحو الحزب (16)</span>
                <span style="color: var(--gold); font-weight: bold;">65%</span>
            </div>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill"></div>
            </div>
            <p style="font-size: 0.8rem; color: #888; margin-top: 10px;">بقي لك سورتين لإتمام هذا الحزب، واصل الاجتهاد!</p>
        </div>

        <div class="dashboard-grid">
            <div class="info-box">
                <h4><i class="fas fa-comment-dots"></i> آخر ملاحظة من الأستاذ</h4>
                <div class="teacher-note">
                    "ما شاء الله يا نور، قراءتك لسورة الضحى كانت ممتازة اليوم. انتبهي فقط لمخارج حرف الضاد في الحصة القادمة."
                </div>
                <p style="text-align: left; font-size: 0.8rem; color: #999; margin-top: 10px;">- الأستاذة: مريم • 09 أفريل 2026</p>
            </div>

            <div class="info-box">
                <h4><i class="fas fa-calendar-alt"></i> الحصة القادمة</h4>
                <p style="margin-bottom: 5px;"><b>اليوم:</b> السبت</p>
                <p style="margin-bottom: 5px;"><b>الساعة:</b> 08:30 صباحاً</p>
                <p><b>القاعة:</b> رقم 02 (حلقة النور)</p>
            </div>
        </div>

    </main>
<script>
function toggleSidebar() {
    // جلب العناصر باستخدام الـ IDs التي وضعناها
    const sidebar = document.getElementById('sidebar'); 
    const mainContent = document.querySelector('.main-content');
    const icon = document.getElementById('toggleIcon');

    // 1. تبديل كلاس الإغلاق للسايدبار
    sidebar.classList.toggle('collapsed');

    // 2. تبديل كلاس التوسيع للمحتوى الرئيسي
    if (mainContent) {
        mainContent.classList.toggle('expanded');
    }

    // 3. تغيير شكل الأيقونة عند الإغلاق والفتح
    if (sidebar.classList.contains('collapsed')) {
        // إذا اختفى السايدبار، حولي السهم إلى أيقونة القائمة (ثلاث خطوط)
        icon.classList.replace('fa-arrow-right', 'fa-bars');
    } else {
        // إذا ظهر السايدبار، أرجعي السهم مكانه
        icon.classList.replace('fa-bars', 'fa-arrow-right');
    }
}
</script>
</body>
</html>