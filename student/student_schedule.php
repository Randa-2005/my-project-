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

// ✅ تعيين بيانات الطالبة مباشرة (بدون تسجيل دخول)
$student_name = "Amira";

$stmt = $conn->prepare("SELECT id, full_name, group_id FROM users WHERE full_name = :name OR full_name LIKE :name_like");
$stmt->execute([':name' => $student_name, ':name_like' => '%' . $student_name . '%']);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("الطالبة '$student_name' غير موجودة في قاعدة البيانات");
}

$student_id = $student['id'];
$student_group_id = $student['group_id'];

// ========== جلب جدول الحصص الخاص بفوج الطالبة ==========
$stmt = $conn->prepare("
    SELECT s.day, s.start_time, s.end_time, g.group_name, s.teacher_name
    FROM schedules s
    JOIN groups g ON s.group_id = g.id
    WHERE s.group_id = :group_id AND s.status = 'active'
    ORDER BY FIELD(s.day, 'السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس'), s.start_time
");
$stmt->execute([':group_id' => $student_group_id]);
$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// تعريف الفترات (صباحية / مسائية)
function getPeriod($time) {
    $hour = date('H', strtotime($time));
    if ($hour < 12) return ['صباحية', 'morning-green'];
    elseif ($hour < 18) return ['بعد الظهر', 'afternoon-blue'];
    else return ['مسائية', 'evening-gold'];
}

include 'student_sidebar.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>توقيت الحلقات - مدرسة أسرتي</title>
    <link rel="stylesheet" href="student_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f7f6;
            display: flex;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            margin-right: 260px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h2 {
            color: #2e7d32;
            margin-bottom: 5px;
        }

        .page-header p {
            color: #666;
        }

        /* ✅ الجدول يأخذ العرض الكامل */
        .table-container {
            background: white;
            border-radius: 15px;
            overflow-x: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            width: 100%;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        .modern-table th {
            background: #2e7d32;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 1rem;
        }

        .modern-table td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
            font-size: 0.95rem;
        }

        .period-tag {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
            display: inline-block;
        }

        .morning-green {
            background: #c8e6c9;
            color: #2e7d32;
        }

        .afternoon-blue {
            background: #e3f2fd;
            color: #1565c0;
        }

        .evening-gold {
            background: #fff3e0;
            color: #ef6c00;
        }

        /* ✅ بطاقة الملاحظات تحت الجدول */
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            width: 100%;
            margin-top: 0;
        }

        .info-card h4 {
            color: #2e7d32;
            margin-bottom: 15px;
            border-right: 4px solid #bfa15f;
            padding-right: 10px;
        }

        .info-card ul {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .info-card ul li {
            padding: 8px 0;
            color: #555;
            flex: 1;
            min-width: 200px;
        }

        .info-card ul li i {
            color: #bfa15f;
            margin-left: 8px;
        }

        .toggle-menu-btn {
            display: none;
            position: fixed;
            top: 15px;
            right: 15px;
            background: #2e7d32;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            z-index: 1100;
        }

        .close-sidebar-btn { visibility: visible !important; opacity: 1 !important; }

        @media (max-width: 768px) {
            .toggle-menu-btn { display: block; }
            .main-content { margin-right: 0 !important; padding-top: 60px; }
            .info-card ul { flex-direction: column; gap: 0; }
            .modern-table th, .modern-table td { padding: 8px; font-size: 12px; }
        }
    </style>
</head>
<body>

    <?php include 'student_sidebar.php'; ?>
    <button class="toggle-menu-btn" id="open-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <main class="main-content">
        <header class="page-header">
            <h2><i class="fas fa-calendar-alt"></i> البرنامج الأسبوعي للحلقات</h2>
            <p>جدول يوضح مواعيد الحصص المباشرة مع الأساتذة</p>
        </header>

        <!-- ✅ الجدول -->
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
                    <?php if (count($schedules) > 0): ?>
                        <?php foreach ($schedules as $schedule):
                            $period_info = getPeriod($schedule['start_time']);
                            $period_text = $period_info[0];
                            $period_class = $period_info[1];
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($schedule['day']); ?></td>
                                <td><span class="period-tag <?php echo $period_class; ?>"><?php echo $period_text; ?></span></td>
                                <td><?php echo date('H:i', strtotime($schedule['start_time'])); ?> - <?php echo date('H:i', strtotime($schedule['end_time'])); ?></td>
                                <td><?php echo htmlspecialchars($schedule['group_name']); ?></td>
                                <td><?php echo htmlspecialchars($schedule['teacher_name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">لا توجد حصص مسجلة لفوجك</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ✅ الملاحظات تحت الجدول -->
        <div class="info-card">
            <h4><i class="fas fa-info-circle"></i> ملاحظات هامة</h4>
            <ul>
                <li><i class="fas fa-clock"></i> يرجى الحضور قبل موعد الحلقة بـ 5 دقائق.</li>
                <li><i class="fas fa-map-marker-alt"></i> التوقيت المذكور أعلاه حسب توقيت الجزائر.</li>
                <li><i class="fas fa-bell"></i> في حال غياب الأستاذ، سيتم إعلامكم عبر الإشعارات.</li>
            </ul>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            if (sidebar) {
                sidebar.classList.toggle('collapsed');
                if (mainContent) mainContent.classList.toggle('expanded');
            }
        }
    </script>
</body>
</html>