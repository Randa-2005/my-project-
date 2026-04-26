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

$teacher_name = "أ. فاطمة الزهراء";

// الأيام والفترات الزمنية
$days_order = ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس'];
$time_slots = [
    '08:00-10:00' => '08:00 - 10:00',
    '10:00-12:00' => '10:00 - 12:00',
    '14:00-16:00' => '14:00 - 16:00'
];

// ========== 1. جلب البرنامج الشخصي ==========
$personal_schedule = [];
foreach ($days_order as $day) {
    foreach ($time_slots as $slot_key => $slot_display) {
        $personal_schedule[$day][$slot_display] = null;
    }
}

$stmt = $conn->prepare("
    SELECT s.day, s.start_time, s.end_time, g.group_name, r.room_number 
    FROM schedules s
    JOIN groups g ON s.group_id = g.id
    JOIN rooms r ON s.room_id = r.id
    WHERE s.teacher_name = :teacher AND s.status = 'active'
");
$stmt->execute([':teacher' => $teacher_name]);
$teacher_sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($teacher_sessions as $session) {
    $day = $session['day'];
    $start = substr($session['start_time'], 0, 5);
    $end = substr($session['end_time'], 0, 5);
    $time_key = $start . ' - ' . $end;
    
    if (in_array($time_key, array_values($time_slots))) {
        $personal_schedule[$day][$time_key] = [
            'group_name' => $session['group_name'],
            'room_number' => $session['room_number']
        ];
    }
}

// ========== 2. جلب البرنامج العام ==========
$school_schedule = [];
foreach ($days_order as $day) {
    foreach ($time_slots as $slot_key => $slot_display) {
        $school_schedule[$day][$slot_display] = [];
    }
}

$stmt = $conn->prepare("
    SELECT s.day, s.start_time, s.end_time, g.group_name, r.room_number, s.teacher_name 
    FROM schedules s
    JOIN groups g ON s.group_id = g.id
    JOIN rooms r ON s.room_id = r.id
    WHERE s.status = 'active'
");
$stmt->execute();
$all_sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($all_sessions as $session) {
    $day = $session['day'];
    $start = substr($session['start_time'], 0, 5);
    $end = substr($session['end_time'], 0, 5);
    $time_key = $start . ' - ' . $end;
    
    if (in_array($time_key, array_values($time_slots))) {
        $school_schedule[$day][$time_key][] = [
            'group_name' => $session['group_name'],
            'room_number' => $session['room_number'],
            'teacher_name' => $session['teacher_name']
        ];
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>البرنامج الزمني العام | مدرسة أسرتي القرآنية</title>
    <link rel="stylesheet" href="teacher_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .schedule-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-top: 20px;
            overflow-x: auto;
        }
        .classic-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }
        .classic-table th {
            background-color: #0d3c1a;
            color: white;
            padding: 15px;
            border: 1px solid #3d4d40;
        }
        .classic-table td {
            padding: 12px;
            border: 1px solid #eee;
            text-align: center;
            vertical-align: middle;
        }
        .day-column {
            background-color: #fdfbf7;
            font-weight: bold;
            color: #4a5d4e;
            width: 100px;
        }
        .my-session {
            background-color: #fff9eb;
            border: 1px solid #f0e6d2;
            padding: 8px;
            border-radius: 8px;
        }
        .group-name { font-weight: bold; color: #0d3c1a; display: block; }
        .room-name { color: #bfa15f; font-size: 0.85rem; display: block; }
        .other-session {
            background-color: #f4f4f4;
            border: 1px dashed #ddd;
            padding: 8px;
            border-radius: 8px;
            margin-bottom: 5px;
        }
        .other-session:last-child { margin-bottom: 0; }
        .prof-name { color: #666; font-weight: bold; display: block; }
        .room-tag { color: #bfa15f; font-size: 0.8rem; display: block; }
        .empty-slot { color: #ddd; letter-spacing: 5px; }
        .tabs-nav {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .tab-btn {
            padding: 10px 20px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        .tab-btn.active {
            background: #bfa15f;
            color: white;
            border-color: #bfa15f;
        }
        body.dashboard-body { display: flex; margin: 0; font-family: 'Cairo', sans-serif; background: #f4f7f6; }
        .main-content { flex: 1; margin-right: 260px; padding: 25px; min-height: 100vh; }
        .sidebar {
            width: 260px;
            position: fixed;
            right: 0;
            top: 0;
            height: 100vh;
            background: #0d3c1a;
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            background-blend-mode: multiply;
            z-index: 1000;
            transition: 0.3s;
        }
        .sidebar.sidebar-closed { transform: translateX(100%); }
        .main-content.full-width { margin-right: 0; }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .toggle-btn {
            background: none;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            margin-left: 15px;
            color: #1a472a;
        }
        .close-sidebar-btn {
            background: none;
            border: none;
            color: #bfa15f;
            font-size: 1.2rem;
            cursor: pointer;
            margin: 10px;
        }
        .nav-links { list-style: none; padding: 0; margin: 0; }
        .nav-links li {
            padding: 12px 20px;
            cursor: pointer;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
        }
        .nav-links li:hover { background: rgba(191, 161, 95, 0.3); }
        .nav-links li.active-gold { background: #bfa15f; color: #0d3c1a; border-radius: 8px; }
        @media (max-width: 768px) {
            .main-content { margin-right: 0; }
            .sidebar { transform: translateX(100%); }
        }
        @media print {
            .sidebar, .top-bar, .tabs-nav { display: none !important; }
            .main-content { margin-right: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="dashboard-body">

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <button id="close-sidebar" class="close-sidebar-btn"><i class="fas fa-arrow-right"></i></button>
            <div style="padding: 25px 15px; text-align: center; border-bottom: 1px solid rgba(191, 161, 95, 0.2);">
                <i class="fas fa-chalkboard-teacher" style="font-size: 45px; color: #bfa15f; margin-bottom: 12px; display: block;"></i>
                <h3 style="color: #bfa15f; font-size: 20px;">لوحة الأستاذ</h3>
            </div>
        </div>   
        <ul class="nav-links">
            <li onclick="location.href='teacher_groups.php'"><i class="fas fa-home"></i> الرئيسية</li>
            <li onclick="location.href='groups_list.php'"><i class="fas fa-users"></i> قوائم التلاميذ</li>
            <li onclick="location.href='exam_history.php'"><i class="fas fa-file-invoice"></i> سجل الامتحانات</li>
            <li onclick="location.href='general_schedule.php'" class="active-gold"><i class="fas fa-calendar-alt"></i> البرنامج العام</li>
            <li onclick="location.href='announcements.php'"><i class="fas fa-bullhorn"></i> الإعلانات</li>
            <li onclick="location.href='teacher_leave.php'"><i class="fas fa-calendar-times"></i> طلب عطلة</li>
            <li onclick="location.href='profile.php'"><i class="fas fa-user-cog"></i> الملف الشخصي</li>
            <li><i class="fas fa-sign-out-alt"></i> خروج</li>
        </ul>
    </aside>

    <main class="main-content" id="mainContent">
        <header class="top-bar">
            <div style="display: flex; align-items: center;">
                <button id="open-sidebar" class="toggle-btn"><i class="fas fa-bars"></i></button>
                <i class="fas fa-clock" style="color: #bfa15f; font-size: 1.5rem;"></i>
                <h2 style="margin-right: 10px;">جداول التوقيت</h2>
            </div>
        </header>

        <div class="tabs-nav">
            <button class="tab-btn active" onclick="switchTable('personal')">📋 برنامجي الشخصي</button>
            <button class="tab-btn" onclick="switchTable('school')">🏫 برنامج المدرسة العام</button>
        </div>

        <!-- البرنامج الشخصي -->
        <div id="personal-table" class="schedule-container">
            <table class="classic-table">
                <thead><tr><th>اليوم</th><?php foreach ($time_slots as $display): ?><th><?php echo $display; ?></th><?php endforeach; ?></tr></thead>
                <tbody>
                    <?php foreach ($days_order as $day): ?>
                    <tr>
                        <td class="day-column"><?php echo $day; ?></td>
                        <?php foreach ($time_slots as $display): ?>
                        <td>
                            <?php if (isset($personal_schedule[$day][$display]) && !is_null($personal_schedule[$day][$display])): ?>
                                <div class="my-session">
                                    <span class="group-name"><?php echo htmlspecialchars($personal_schedule[$day][$display]['group_name']); ?></span>
                                    <span class="room-name">قاعة <?php echo htmlspecialchars($personal_schedule[$day][$display]['room_number']); ?></span>
                                </div>
                            <?php else: ?>
                                <span class="empty-slot">•••••••</span>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- البرنامج العام -->
        <div id="school-table" class="schedule-container" style="display: none;">
            <table class="classic-table">
                <thead><tr><th>اليوم / الوقت</th><?php foreach ($time_slots as $display): ?><th><?php echo $display; ?></th><?php endforeach; ?></tr></thead>
                <tbody>
                    <?php foreach ($days_order as $day): ?>
                    <tr>
                        <td class="day-column"><?php echo $day; ?></td>
                        <?php foreach ($time_slots as $display): ?>
                        <td>
                            <?php if (isset($school_schedule[$day][$display]) && count($school_schedule[$day][$display]) > 0): ?>
                                <?php foreach ($school_schedule[$day][$display] as $session): ?>
                                    <div class="other-session">
                                        <span class="prof-name"><?php echo htmlspecialchars($session['teacher_name']); ?></span>
                                        <span class="room-tag">قاعة <?php echo htmlspecialchars($session['room_number']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="empty-slot">•••••••</span>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        function switchTable(type) {
            document.getElementById('personal-table').style.display = type === 'personal' ? 'block' : 'none';
            document.getElementById('school-table').style.display = type === 'school' ? 'block' : 'none';
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');
        }

        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        
        if (openBtn) openBtn.onclick = () => { sidebar.classList.remove('sidebar-closed'); mainContent.classList.remove('full-width'); };
        if (closeBtn) closeBtn.onclick = () => { sidebar.classList.add('sidebar-closed'); mainContent.classList.add('full-width'); };
    </script>
</body>
</html>