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

$stmt = $conn->prepare("SELECT id, group_name FROM groups WHERE teacher_name = :teacher ORDER BY group_name");
$stmt->execute([':teacher' => $teacher_name]);
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

$selected_group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;
$students = [];
$sessions = [];

if ($selected_group_id > 0) {
    $stmt = $conn->prepare("SELECT id, full_name, birth_date, academic_level FROM users 
                            WHERE group_id = :group_id AND (role = 'student' OR role = 'طالب') 
                            ORDER BY full_name");
    $stmt->execute([':group_id' => $selected_group_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = $conn->prepare("SELECT id, session_date, session_number FROM sessions WHERE group_id = :group_id ORDER BY session_number ASC LIMIT 10");
    $stmt->execute([':group_id' => $selected_group_id]);
    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $attendance_data = [];
    $evaluation_data = [];
    
    foreach ($students as $student) {
        foreach ($sessions as $session) {
            $stmt = $conn->prepare("SELECT status FROM attendance WHERE student_id = :student_id AND date = :date");
            $stmt->execute([':student_id' => $student['id'], ':date' => $session['session_date']]);
            $att = $stmt->fetch(PDO::FETCH_ASSOC);
            $attendance_data[$student['id']][$session['id']] = $att['status'] ?? 'غائب';
            
            $stmt = $conn->prepare("SELECT memorization_score FROM daily_evaluation WHERE student_id = :student_id AND evaluation_date = :date");
            $stmt->execute([':student_id' => $student['id'], ':date' => $session['session_date']]);
            $eval = $stmt->fetch(PDO::FETCH_ASSOC);
            $evaluation_data[$student['id']][$session['id']] = $eval['memorization_score'] ?? '-';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>استعراض قوائم الطلبة</title>
    <link rel="stylesheet" href="teacher_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ========== تنسيق السايدبار والمحتوى ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            display: flex;
            background: #f4f7f6;
            font-family: 'Cairo', sans-serif;
        }
        
        /* السايدبار مع النقش الإسلامي */
        .sidebar {
            width: 260px;
            position: fixed;
            right: 0;
            top: 0;
            height: 100vh;
            background-color: #0d3c1a;
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            background-blend-mode: multiply;
            background-repeat: repeat;
            background-size: 100px;
            z-index: 1000;
            transition: 0.3s;
        }
        
        .sidebar.sidebar-closed {
            transform: translateX(100%);
        }
        
        /* المحتوى الرئيسي */
        .main-content {
            flex: 1;
            margin-right: 260px;
            padding: 20px;
            transition: 0.3s;
            width: calc(100% - 260px);
        }
        
        .main-content.full-width {
            margin-right: 0;
            width: 100%;
        }
        
        /* زر فتح القائمة (الثلاث خطوط) */
        .toggle-btn {
            background: #1a472a;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            margin-left: 15px;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            transition: 0.3s;
        }
        
        .toggle-btn:hover {
            background: #2e7d32;
        }
        
        /* زر إغلاق القائمة */
        .close-sidebar-btn {
            background: none;
            border: none;
            color: #bfa15f;
            font-size: 1.2rem;
            cursor: pointer;
            margin: 10px;
        }
        
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        .custom-select {
            padding: 8px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 1rem;
            min-width: 200px;
        }
        
        .tabs-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .tab-link {
            padding: 10px 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            color: #666;
        }
        
        .tab-link.active {
            color: #1a472a;
            border-bottom: 3px solid #1a472a;
            font-weight: bold;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* ========== الجدول ========== */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            background: white;
        }
        
        .report-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            min-width: 600px;
        }
        
        .report-table th, 
        .report-table td {
            padding: 10px 8px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        
        .report-table th {
            background: #1a472a;
            color: white;
            font-weight: bold;
        }
        
        .report-table th:first-child, 
        .report-table td:first-child {
            width: 100px;
        }
        
        .report-table th:nth-child(2), 
        .report-table td:nth-child(2) {
            min-width: 150px;
        }
        
        /* الألوان */
        .present-badge {
            background: #c8e6c9;
            color: #2e7d32;
            font-weight: bold;
            display: inline-block;
            width: 30px;
            border-radius: 50%;
            padding: 4px;
        }
        
        .absent-badge {
            background: #ffcdd2;
            color: #c62828;
            font-weight: bold;
            display: inline-block;
            width: 30px;
            border-radius: 50%;
            padding: 4px;
        }
        
        .attendance-rate {
            font-weight: bold;
            background: #e8f5e9;
        }
        
        .score-cell {
            font-weight: bold;
            color: #1a472a;
        }
        
        .print-footer {
            text-align: center;
            margin-top: 20px;
        }
        
        .print-btn-action {
            background: #1a472a;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            cursor: pointer;
        }
        
        .card-box {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        /* قائمة الروابط في السايدبار */
        .nav-links {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        
        .nav-links li {
            padding: 12px 20px;
            cursor: pointer;
            color: white;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .nav-links li:hover {
            background: rgba(191, 161, 95, 0.3);
        }
        
        .nav-links li.active-gold {
            background: #bfa15f;
            color: #0d3c1a;
        }
        
        .sidebar-header {
            padding: 20px 15px;
            text-align: center;
            border-bottom: 1px solid rgba(191, 161, 95, 0.2);
        }
        
        @media print {
            .sidebar, .top-bar, .filter-section, .tabs-container, .print-footer, .toggle-btn {
                display: none !important;
            }
            .main-content {
                margin-right: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-right: 0;
                width: 100%;
            }
            .sidebar {
                transform: translateX(100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .report-table th, .report-table td {
                padding: 6px 4px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

    <!-- السايدبار مع النقش الإسلامي -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <button id="close-sidebar" class="close-sidebar-btn">
                <i class="fas fa-arrow-right"></i>
            </button>
            <i class="fas fa-chalkboard-teacher" style="font-size: 45px; color: #bfa15f;"></i>
            <h3 style="color: #bfa15f; margin-top: 10px;">لوحة الأستاذ</h3>
        </div>
        <ul class="nav-links">
            <li onclick="location.href='teacher_groups.php'" class="<?php echo ($current_page == 'teacher_groups.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-home"></i> الرئيسية
            </li>
            <li onclick="location.href='groups_list.php'" class="active-gold">
                <i class="fas fa-users"></i> قوائم التلاميذ
            </li>
            <li onclick="location.href='exam_history.php'">
                <i class="fas fa-file-invoice"></i> سجل الامتحانات
            </li>
            <li onclick="location.href='general_schedule.php'">
                <i class="fas fa-calendar-alt"></i> البرنامج العام
            </li>
            <li onclick="location.href='announcements.php'">
                <i class="fas fa-bullhorn"></i> الإعلانات
            </li>
            <li onclick="location.href='teacher_leave.php'">
                <i class="fas fa-calendar-times"></i> طلب عطلة
            </li>
            <li onclick="location.href='profile.php'">
                <i class="fas fa-user-cog"></i> الملف الشخصي
            </li>
            <li>
                <i class="fas fa-sign-out-alt"></i> خروج
            </li>
        </ul>
    </aside>

    <main class="main-content" id="mainContent">
        <header class="top-bar">
            <div style="display: flex; align-items: center;">
                <button id="open-sidebar" class="toggle-btn">
                    <i class="fas fa-bars"></i>
                </button>
                <i class="fas fa-users" style="font-size: 1.5rem; color: #1a472a; margin-right: 10px;"></i>
                <h2>استعراض قوائم التلاميذ</h2>
            </div>
        </header>

        <section class="filter-section">
            <div style="display: flex; align-items: center; gap: 15px;">
                <label>اختر الفوج:</label>
                <select class="custom-select" id="groupSelect" onchange="location.href='groups_list.php?group_id='+this.value">
                    <option value="">-- اختر الفوج --</option>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?php echo $group['id']; ?>" <?php echo ($selected_group_id == $group['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($group['group_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </section>

        <?php if ($selected_group_id > 0 && count($students) > 0 && count($sessions) > 0): ?>
        <div class="tabs-container">
            <button class="tab-link active" onclick="openTab(event, 'general')">القائمة الرئيسية</button>
            <button class="tab-link" onclick="openTab(event, 'attendance')">قائمة الحضور</button>
            <button class="tab-link" onclick="openTab(event, 'evaluation')">قائمة التقييم</button>
        </div>

        <!-- القائمة الرئيسية -->
        <div id="general" class="tab-content active">
            <div class="card-box">
                <div class="table-wrapper">
                    <table class="report-table">
                        <thead><tr><th>رقم التسجيل</th><th>الاسم واللقب</th><th>تاريخ الميلاد</th><th>المستوى الدراسي</th></tr></thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                            <tr><td><?php echo str_pad($student['id'], 6, '0', STR_PAD_LEFT); ?></td><td><?php echo htmlspecialchars($student['full_name']); ?></td><td><?php echo date('d/m/Y', strtotime($student['birth_date'])); ?></td><td><?php echo htmlspecialchars($student['academic_level'] ?? 'غير محدد'); ?></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="print-footer"><button class="print-btn-action" onclick="window.print()"><i class="fas fa-print"></i> طباعة</button></div>
            </div>
        </div>

        <!-- قائمة الحضور -->
        <div id="attendance" class="tab-content">
            <div class="card-box">
                <div class="table-wrapper">
                    <table class="report-table">
                        <thead><tr><th>رقم التسجيل</th><th>الاسم واللقب</th><?php foreach ($sessions as $session): ?><th>حصة <?php echo $session['session_number']; ?></th><?php endforeach; ?><th>نسبة الحضور</th></tr></thead>
                        <tbody>
                            <?php foreach ($students as $student): $present_count = 0; $total = count($sessions); ?>
                            <tr><td><?php echo str_pad($student['id'], 6, '0', STR_PAD_LEFT); ?></td><td><?php echo htmlspecialchars($student['full_name']); ?></td>
                            <?php foreach ($sessions as $session): $status = $attendance_data[$student['id']][$session['id']] ?? 'غائب'; $is_present = ($status == 'حاضر'); if ($is_present) $present_count++; ?>
                            <td><span class="<?php echo $is_present ? 'present-badge' : 'absent-badge'; ?>"><?php echo $is_present ? '✓' : '✗'; ?></span></td>
                            <?php endforeach; ?>
                            <td class="attendance-rate"><?php echo round(($present_count / $total) * 100); ?>%</td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="print-footer"><button class="print-btn-action" onclick="window.print()"><i class="fas fa-print"></i> طباعة</button></div>
            </div>
        </div>

        <!-- قائمة التقييم -->
        <div id="evaluation" class="tab-content">
            <div class="card-box">
                <div class="table-wrapper">
                    <table class="report-table">
                        <thead><tr><th>رقم التسجيل</th><th>الاسم واللقب</th><?php foreach ($sessions as $session): ?><th>حصة <?php echo $session['session_number']; ?></th><?php endforeach; ?><th>المعدل</th></tr></thead>
                        <tbody>
                            <?php foreach ($students as $student): $total_score = 0; $score_count = 0; ?>
                            <tr><td><?php echo str_pad($student['id'], 6, '0', STR_PAD_LEFT); ?></td><td><?php echo htmlspecialchars($student['full_name']); ?></td>
                            <?php foreach ($sessions as $session): $score = $evaluation_data[$student['id']][$session['id']] ?? '-'; if (is_numeric($score)) { $total_score += $score; $score_count++; } ?>
                            <td class="score-cell"><?php echo $score; ?></td>
                            <?php endforeach; ?>
                            <td class="score-cell"><?php echo ($score_count > 0) ? round($total_score / $score_count, 1) : '-'; ?></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="print-footer"><button class="print-btn-action" onclick="window.print()"><i class="fas fa-print"></i> طباعة</button></div>
            </div>
        </div>
        
        <?php elseif ($selected_group_id > 0 && count($students) == 0): ?>
            <div class="card-box"><p>لا يوجد تلاميذ مسجلين في هذا الفوج</p></div>
        <?php elseif ($selected_group_id > 0 && count($sessions) == 0): ?>
            <div class="card-box"><p>لم يتم إنشاء حصص لهذا الفوج بعد</p></div>
        <?php endif; ?>
    </main>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) { tabcontent[i].style.display = "none"; }
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tablinks.length; i++) { tablinks[i].className = tablinks[i].className.replace(" active", ""); }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        
        // التحكم في السايدبار
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        
        if (openBtn) {
            openBtn.onclick = function() {
                sidebar.classList.remove('sidebar-closed');
                mainContent.classList.remove('full-width');
            };
        }
        
        if (closeBtn) {
            closeBtn.onclick = function() {
                sidebar.classList.add('sidebar-closed');
                mainContent.classList.add('full-width');
            };
        }
    </script>
</body>
</html>