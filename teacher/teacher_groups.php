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

// اسم الأستاذ ثابت
$teacher_name = "أ. فاطمة الزهراء";

// جلب أفواج الأستاذة
$stmt = $conn->prepare("SELECT id, group_name, academic_level FROM groups WHERE teacher_name = :teacher ORDER BY group_name");
$stmt->execute([':teacher' => $teacher_name]);
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

// جلب آخر الإعلانات
$stmt = $conn->prepare("SELECT title, content, created_at FROM announcements WHERE target_role IN ('all', 'teacher') ORDER BY created_at DESC LIMIT 3");
$stmt->execute();
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم الأستاذة</title>
    <link rel="stylesheet" href="teacher_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-right: 5px solid #28a745;
        }
        .stat-card i {
            font-size: 2.5rem;
            color: #28a745;
        }
        .stat-info h4 {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }
        .stat-info p {
            margin: 5px 0 0;
            font-size: 1.8rem;
            font-weight: bold;
            color: #1a472a;
        }
        .groups-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        .group-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.3s;
            border-right: 5px solid #28a745;
        }
        .group-card:hover {
            transform: translateY(-5px);
        }
        .group-card h4 {
            margin: 0 0 10px 0;
            font-size: 1.3rem;
            color: #1a472a;
        }
        .group-card p {
            color: #666;
            margin-bottom: 15px;
        }
        .enter-btn {
            display: inline-block;
            background: #1a472a;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s;
        }
        .enter-btn:hover {
            background: #2e7d32;
        }
        .ann-container-horizontal {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        .ann-item {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            flex: 1;
            min-width: 250px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            border-right: 4px solid #bfa15f;
        }
        .ann-item span {
            font-size: 0.75rem;
            color: #999;
            display: block;
            margin-bottom: 8px;
        }
        .ann-item p {
            margin: 0;
            color: #333;
            font-weight: bold;
        }
        .full-width-announcements {
            margin-top: 35px;
        }
        .welcome-badge {
            background: #e8f5e9;
            padding: 15px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-right: 5px solid #1a472a;
        }
    </style>
</head>
<body class="dashboard-body">

    <aside class="sidebar">
        <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
        <div class="sidebar-header">
            <button id="close-sidebar" class="close-sidebar-btn"><i class="fas fa-arrow-right"></i></button>
            <div style="padding: 25px 15px; text-align: center; border-bottom: 1px solid rgba(191, 161, 95, 0.2);">
                <i class="fas fa-chalkboard-teacher" style="font-size: 45px; color: #bfa15f; margin-bottom: 12px; display: block;"></i>
                <h3 style="color: #bfa15f; font-size: 20px;">لوحة الأستاذة</h3>
            </div>
        </div>
        <ul class="nav-links">
            <li onclick="location.href='teacher_groups.php'" class="active-gold"><i class="fas fa-home"></i> الرئيسية</li>
            <li onclick="location.href='groups_list.php'"><i class="fas fa-users"></i> قوائم التلاميذ</li>
            <li onclick="location.href='exam_history.php'"><i class="fas fa-file-invoice"></i> سجل الامتحانات</li>
            <li onclick="location.href='general_schedule.php'"><i class="fas fa-calendar-alt"></i> البرنامج العام</li>
            <li onclick="location.href='announcements.php'"><i class="fas fa-bullhorn"></i> الإعلانات</li>
            <li onclick="location.href='teacher_leave.php'"><i class="fas fa-calendar-times"></i> طلب عطلة</li>
            <li onclick="location.href='profile.php'"><i class="fas fa-user-cog"></i> الملف الشخصي</li>
            <li><i class="fas fa-sign-out-alt"></i> خروج</li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="top-bar">
            <div style="display: flex; align-items: center;">
                <button id="open-sidebar" class="toggle-btn"><i class="fas fa-bars"></i></button>
                <i class="fas fa-home home-icon-main"></i>
                <h2 style="margin-right: 10px;">الرئيسية</h2>
            </div>
            <div class="user-info">أهلاً، <?php echo $teacher_name; ?></div>
        </header>

        <div class="welcome-badge">
            <i class="fas fa-smile-wink"></i> مرحباً بك في لوحة التحكم
        </div>

        <div class="groups-section">
            <h3><i class="fas fa-chalkboard-teacher"></i> أفواجي</h3>
            <div class="groups-grid">
                <?php if (count($groups) > 0): ?>
                    <?php foreach ($groups as $group): ?>
                        <div class="group-card">
                            <h4><i class="fas fa-users"></i> <?php echo htmlspecialchars($group['group_name']); ?></h4>
                            <p><i class="fas fa-graduation-cap"></i> <?php echo $group['academic_level'] ?? 'جميع المستويات'; ?></p>
                            <a href="manage_session.php?group_id=<?php echo $group['id']; ?>" class="enter-btn">
                                <i class="fas fa-door-open"></i> دخول الحلقة
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="group-card" style="text-align: center;">
                        <p>لا توجد أفواج مسجلة</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <section class="full-width-announcements">
            <h3><i class="fas fa-bullhorn"></i> آخر الإعلانات</h3>
            <div class="ann-container-horizontal">
                <?php if (count($announcements) > 0): ?>
                    <?php foreach ($announcements as $ann): ?>
                        <div class="ann-item">
                            <span><i class="far fa-calendar-alt"></i> <?php echo date('Y/m/d', strtotime($ann['created_at'])); ?></span>
                            <p><?php echo htmlspecialchars($ann['title']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="ann-item">
                        <span>لا توجد إعلانات</span>
                        <p>لا توجد إعلانات حالياً</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');
        const body = document.body;

        openBtn.onclick = function() { body.classList.remove('sidebar-closed'); }
        closeBtn.onclick = function() { body.classList.add('sidebar-closed'); }
    </script>
</body>
</html>