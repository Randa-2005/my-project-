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

$stmt = $conn->prepare("SELECT id, full_name FROM users WHERE full_name = :name OR full_name LIKE :name_like");
$stmt->execute([':name' => $student_name, ':name_like' => '%' . $student_name . '%']);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("الطالبة '$student_name' غير موجودة في قاعدة البيانات");
}

$student_id = $student['id'];
$student_display_name = $student['full_name'];

// ========== 1️⃣ جلب سجل نتائج الاختبارات ==========
$stmt = $conn->prepare("
    SELECT e.exam_date, 
           er.hifz_score, 
           er.ahkam_score, 
           er.makharij_score, 
           er.total_score
    FROM exam_results er
    JOIN exams e ON er.exam_id = e.id
    WHERE er.student_id = :student_id
    ORDER BY e.exam_date DESC
");
$stmt->execute([':student_id' => $student_id]);
$exam_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ========== 2️⃣ حساب الإحصائيات ==========
$total_exams = count($exam_results);
$last_avg = 0;
if ($total_exams > 0) {
    $last_avg = $exam_results[0]['total_score'] ?? 0;
}

include 'student_sidebar.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>كشف نقاطي - مدرسة أسرتي القرآنية</title>
    <link rel="stylesheet" href="student_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #1a472a;
            --light-green: #f0f4f1;
            --gold: #bfa15f;
            --white: #ffffff;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            display: flex;
        }

        .main-content {
            flex: 1;
            margin-right: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--white);
            padding: 15px 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }

        .top-bar h2 { color: var(--primary-green); margin: 0; }

        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-box {
            background: var(--white);
            padding: 20px;
            border-radius: 12px;
            flex: 1;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .stat-box i { font-size: 2rem; color: var(--gold); }
        .stat-box .info h4 { margin: 0; color: #666; font-size: 0.9rem; }
        .stat-box .info p { margin: 5px 0 0; font-size: 1.2rem; font-weight: bold; color: var(--primary-green); }

        .table-wrapper {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table th {
            background: var(--primary-green);
            color: var(--white);
            padding: 18px;
            text-align: center;
        }

        .history-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: #444;
            text-align: center;
        }

        .score-tag {
            background: var(--light-green);
            padding: 5px 12px;
            border-radius: 8px;
            font-weight: bold;
            color: var(--primary-green);
            border: 1px solid #d4edda;
            display: inline-block;
        }

        .final-grade {
            font-weight: bold;
            color: var(--primary-green);
            font-size: 1.1rem;
        }

        .sidebar {
            position: fixed;
            right: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--primary-green);
            color: white;
            z-index: 1000;
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            background-blend-mode: multiply;
            background-repeat: repeat;
            background-size: 100px;
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

        @media (max-width: 768px) {
            .toggle-menu-btn { display: block; }
            .main-content { margin-right: 0 !important; padding-top: 60px; }
            .stats-container { flex-direction: column; }
            .history-table th, .history-table td { padding: 10px; font-size: 12px; }
        }
    </style>
</head>
<body>

    <?php include 'student_sidebar.php'; ?>
    <button class="toggle-menu-btn" id="open-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <main class="main-content">
        <header class="top-bar">
            <h2><i class="fas fa-award"></i> سجل نتائج اختباراتي</h2>
            <div class="student-meta">
                <span>المستوى: <strong><?php echo htmlspecialchars($student_display_name); ?></strong></span>
            </div>
        </header>

        <div class="stats-container">
            <div class="stat-box">
                <i class="fas fa-file-signature"></i>
                <div class="info">
                    <h4>عدد الاختبارات</h4>
                    <p><?php echo $total_exams; ?> اختبارات</p>
                </div>
            </div>
            <div class="stat-box">
                <i class="fas fa-star"></i>
                <div class="info">
                    <h4>آخر معدل</h4>
                    <p><?php echo $last_avg; ?> / 20</p>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>حفظ (8)</th>
                        <th>أحكام (8)</th>
                        <th>مخارج (4)</th>
                        <th>المجموع</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($exam_results) > 0): ?>
                        <?php foreach ($exam_results as $result): ?>
                            <tr>
                                <td><?php echo date('Y-m-d', strtotime($result['exam_date'])); ?></td>
                                <td><span class="score-tag"><?php echo $result['hifz_score']; ?></span></td>
                                <td><span class="score-tag"><?php echo $result['ahkam_score']; ?></span></td>
                                <td><span class="score-tag"><?php echo $result['makharij_score']; ?></span></td>
                                <td class="final-grade"><?php echo $result['total_score']; ?> / 20</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">لا توجد نتائج اختبارات مسجلة</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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