<?php
session_start();
$host = 'localhost';
$dbname = 'smart_quran_schooli';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}

// تحديد دور المستخدم
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'أستاذ';

// جلب الإعلانات المناسبة للأستاذ
$stmt = $conn->prepare("SELECT * FROM announcements WHERE target_role IN ('all', 'teacher') ORDER BY created_at DESC");
$stmt->execute();
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الإعلانات - الأستاذ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="teacher_style.css">
    <style>
        :root { --main-green: #0d3c1a; --gold: #bfa15f; --bg-gray: #f8f9fa; }
        body { font-family: 'Cairo', sans-serif; background-color: var(--bg-gray); margin: 0; display: flex; }

        .sidebar {
            width: 250px;
            position: fixed;
            height: 100vh;
            transition: all 0.4s ease;
            z-index: 1000;
            background-color: #0d3c1a;
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            background-blend-mode: multiply;
            background-repeat: repeat;
            background-size: 100px;
        }
        
        .sidebar.sidebar-closed {
            transform: translateX(100%);
            width: 0;
            overflow: hidden;
        }
        
        .main-content {
            flex: 1;
            margin-right: 260px;
            transition: 0.4s;
            padding: 25px;
        }
        
        .main-content.full-width {
            margin-right: 0;
        }

        .announcements-wrapper { max-width: 850px; margin: 0 auto; }
        
        .page-header { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; border-bottom: 3px solid var(--gold); padding-bottom: 10px; }
        .page-header i { color: var(--gold); font-size: 1.8rem; }
        .page-header h2 { color: var(--main-green); margin: 0; }

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
        
        .ann-card.urgent { border-right-color: #d9534f; }
        .ann-card.event { border-right-color: #5bc0de; }
        .ann-card.admin { border-right-color: #1976d2; }
        .ann-card.general { border-right-color: var(--gold); }

        .ann-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .ann-date { font-size: 0.85rem; color: #999; }
        .ann-tag { font-size: 0.75rem; padding: 4px 10px; border-radius: 15px; background: #f0f0f0; color: #666; }
        
        .ann-title { color: var(--main-green); margin: 0 0 10px 0; font-size: 1.2rem; display: flex; align-items: center; gap: 8px; }
        .ann-body { color: #555; line-height: 1.7; font-size: 0.95rem; }
        
        .new-badge { position: absolute; top: -10px; left: -10px; background: #d9534f; color: white; padding: 5px 10px; border-radius: 8px; font-size: 0.7rem; font-weight: bold; transform: rotate(-10deg); }

        .toggle-btn {
            display: none;
            position: fixed;
            top: 15px;
            right: 15px;
            background: #0d3c1a;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            z-index: 1100;
        }

        @media (max-width: 768px) {
            .toggle-btn { display: block; }
            .main-content { margin-right: 0; padding-top: 60px; }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
        <div class="sidebar-header">
            <button id="close-sidebar" class="close-sidebar-btn"><i class="fas fa-arrow-right"></i></button>
            <div style="padding: 25px 15px; text-align: center; border-bottom: 1px solid rgba(191, 161, 95, 0.2);">
                <i class="fas fa-chalkboard-teacher" style="font-size: 45px; color: #bfa15f; margin-bottom: 12px; display: block;"></i>
                <h3 style="color: #bfa15f; font-size: 20px; font-family: 'Cairo', sans-serif; font-weight: bold; margin: 0;">لوحة الأستاذ</h3>
            </div>
        </div>

        <ul class="nav-links">
            <li onclick="location.href='teacher_groups.php'" class="<?php echo ($current_page == 'teacher_groups.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-home"></i> الرئيسية
            </li>
            <li onclick="location.href='groups_list.php'" class="<?php echo ($current_page == 'groups_list.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-users"></i> قوائم التلاميذ
            </li>
            <li onclick="location.href='exam_history.php'" class="<?php echo ($current_page == 'exam_history.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-file-invoice"></i> سجل الامتحانات
            </li>
            <li onclick="location.href='general_schedule.php'" class="<?php echo ($current_page == 'general_schedule.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-calendar-alt"></i> البرنامج العام
            </li>
            <li onclick="location.href='announcements.php'" class="<?php echo ($current_page == 'announcements.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-bullhorn"></i> الإعلانات
            </li>
            <li onclick="location.href='teacher_leave.php'" class="<?php echo ($current_page == 'teacher_leave.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-calendar-times"></i> طلب عطلة
            </li>
            <li onclick="location.href='profile.php'" class="<?php echo ($current_page == 'profile.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-user-cog"></i> الملف الشخصي
            </li>
            <li><i class="fas fa-sign-out-alt"></i> خروج</li>
        </ul>
    </aside>

    <main class="main-content" id="mainContent">
        <button id="open-sidebar-btn" class="toggle-btn">
            <i class="fas fa-bars"></i>
        </button>

        <div class="announcements-wrapper">
            <div class="page-header">
                <i class="fas fa-bullhorn"></i>
                <h2>إعلانات المدرسة</h2>
            </div>

            <?php if (count($announcements) > 0): ?>
                <?php foreach ($announcements as $ann): ?>
                    <?php
                    // حساب الفرق بالأيام
                    $created_date = new DateTime($ann['created_at']);
                    $now = new DateTime();
                    $diff_days = $now->diff($created_date)->days;
                    $is_new = ($diff_days < 3);
                    
                    // تحديد نوع الإعلان
                    $type_class = $ann['type'];
                    $type_label = $ann['type'] == 'urgent' ? 'هام جداً' : ($ann['type'] == 'admin' ? 'إداري' : 'عام');
                    ?>
                    <div class="ann-card <?php echo $type_class; ?>">
                        <?php if ($is_new): ?>
                            <span class="new-badge">جديد</span>
                        <?php endif; ?>
                        <div class="ann-meta">
                            <span class="ann-tag"><?php echo $type_label; ?></span>
                            <span class="ann-date">
                                <i class="far fa-clock"></i> 
                                <?php echo date('d/m/Y | H:i', strtotime($ann['created_at'])); ?>
                            </span>
                        </div>
                        <h4 class="ann-title"><?php echo htmlspecialchars($ann['title']); ?></h4>
                        <p class="ann-body"><?php echo nl2br(htmlspecialchars($ann['content'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="ann-card">
                    <div class="ann-meta">
                        <span class="ann-tag">تنبيه</span>
                    </div>
                    <h4 class="ann-title">لا توجد إعلانات حالياً</h4>
                    <p class="ann-body">سوف يتم نشر الإعلانات هنا عند الحاجة. تابع الصفحة باستمرار.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.getElementById('mainContent');
        const closeBtn = document.getElementById('close-sidebar');
        const openBtn = document.getElementById('open-sidebar-btn');

        closeBtn.onclick = function() {
            sidebar.classList.add('sidebar-closed');
            mainContent.classList.add('full-width');
            openBtn.style.display = 'block';
        };

        openBtn.onclick = function() {
            sidebar.classList.remove('sidebar-closed');
            mainContent.classList.remove('full-width');
            openBtn.style.display = 'none';
        };
    </script>
    
</body>
</html>