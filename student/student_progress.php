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

// ========== 1️⃣ حساب إجمالي السور المحفوظة ==========
$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT surah_name) as total_surahs 
    FROM daily_evaluation 
    WHERE student_id = :id
");
$stmt->execute([':id' => $student_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total_surahs = $result['total_surahs'] ?? 0;

// ========== 2️⃣ حساب المستوى الحالي بناءً على متوسط الدرجات ==========
$stmt = $conn->prepare("
    SELECT AVG(memorization_score) as avg_score 
    FROM daily_evaluation 
    WHERE student_id = :id
");
$stmt->execute([':id' => $student_id]);
$avg_result = $stmt->fetch(PDO::FETCH_ASSOC);
$avg_score = $avg_result['avg_score'] ?? 0;

if ($avg_score >= 18) $level = 'ممتاز';
elseif ($avg_score >= 15) $level = 'جيد جداً';
elseif ($avg_score >= 12) $level = 'جيد';
elseif ($avg_score >= 8) $level = 'مقبول';
else $level = 'يحتاج إلى تحسين';

// ========== 3️⃣ آخر مراجعة ==========
$stmt = $conn->prepare("
    SELECT evaluation_date 
    FROM daily_evaluation 
    WHERE student_id = :id 
    ORDER BY evaluation_date DESC 
    LIMIT 1
");
$stmt->execute([':id' => $student_id]);
$last_review = $stmt->fetch(PDO::FETCH_ASSOC);

if ($last_review) {
    $last_review_date = new DateTime($last_review['evaluation_date']);
    $today = new DateTime();
    $diff = $today->diff($last_review_date);
    $days_diff = $diff->days;
    
    if ($days_diff == 0) $last_review_text = 'اليوم';
    elseif ($days_diff == 1) $last_review_text = 'أمس';
    else $last_review_text = 'منذ ' . $days_diff . ' يوم';
} else {
    $last_review_text = 'لا توجد مراجعات';
}

// ========== 4️⃣ آخر 5 حصص حفظ ==========
$stmt = $conn->prepare("
    SELECT evaluation_date, surah_name, from_ayah, to_ayah, memorization_score
    FROM daily_evaluation 
    WHERE student_id = :id 
    ORDER BY evaluation_date DESC 
    LIMIT 5
");
$stmt->execute([':id' => $student_id]);
$recent_sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ========== 5️⃣ قائمة السور وحالتها ==========
// جلب السور من جدول surahs
$stmt = $conn->prepare("SELECT id, surah_name_ar FROM surahs ORDER BY surah_number");
$stmt->execute();
$all_surahs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// جلب السور التي حفظها الطالب
$stmt = $conn->prepare("
    SELECT DISTINCT surah_name 
    FROM daily_evaluation 
    WHERE student_id = :id
");
$stmt->execute([':id' => $student_id]);
$saved_surahs = $stmt->fetchAll(PDO::FETCH_COLUMN);

// السورة الحالية من student_progress
$stmt = $conn->prepare("SELECT current_surah FROM student_progress WHERE student_id = :id");
$stmt->execute([':id' => $student_id]);
$progress = $stmt->fetch(PDO::FETCH_ASSOC);
$current_surah = $progress['current_surah'] ?? 'الضحى';

// تصنيف السور
$surah_status = [];
foreach ($all_surahs as $surah) {
    $surah_name = $surah['surah_name_ar'];
    if (in_array($surah_name, $saved_surahs)) {
        $surah_status[$surah_name] = 'saved';
    } elseif ($surah_name == $current_surah) {
        $surah_status[$surah_name] = 'current';
    } else {
        $surah_status[$surah_name] = 'remaining';
    }
}

include 'student_sidebar.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سجل حفظي - مدرسة أسرتي القرآنية</title>
    <link rel="stylesheet" href="student_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
            gap: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-bottom: 4px solid #bfa15f;
            transition: transform 0.3s;
            cursor: pointer;
        }
        
        .stat-card.clickable-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            background: rgba(191, 161, 95, 0.1);
            color: #bfa15f;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-info h3 {
            color: #2e7d32;
            font-size: 1.8rem;
            margin: 0;
        }
        
        .stat-info p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .stat-info small {
            color: #999;
            font-size: 0.7rem;
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .table-header-main {
            background: #2e7d32;
            color: white;
            padding: 15px 20px;
        }
        
        .table-header-main h3 {
            margin: 0;
        }
        
        .custom-table-responsive {
            overflow-x: auto;
        }
        
        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .modern-table th, .modern-table td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        
        .modern-table th {
            background: #f5f5f5;
            color: #2e7d32;
            font-weight: bold;
        }
        
        .rank-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .rank-badge.excellent {
            background: #c8e6c9;
            color: #2e7d32;
        }
        
        .rank-badge.good {
            background: #e3f2fd;
            color: #1565c0;
        }
        
        .rank-badge.average {
            background: #fff3e0;
            color: #ef6c00;
        }
        
        .rank-badge.poor {
            background: #ffebee;
            color: #c62828;
        }
        
        /* ========== نافذة السور ========== */
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            justify-content: center;
            align-items: center;
        }
        
        .custom-modal.active {
            display: flex;
        }
        
        .modal-content.style-premium {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 700px;
            max-height: 80vh;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .modal-header-premium {
            background: #2e7d32;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header-premium .modal-title {
            margin: 0;
        }
        
        .close-modal {
            font-size: 28px;
            cursor: pointer;
            color: white;
        }
        
        .modal-body-scrollable {
            padding: 20px;
            max-height: 60vh;
            overflow-y: auto;
        }
        
        .status-legend {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
        }
        
        .legend-item .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        
        .legend-item .dot.green { background: #4caf50; }
        .legend-item .dot.orange { background: #ff9800; }
        .legend-item .dot.gray { background: #9e9e9e; }
        
        .surahs-grid-new {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
        }
        
        .surah-item {
            padding: 10px;
            text-align: center;
            border-radius: 10px;
            font-weight: bold;
            cursor: default;
        }
        
        .surah-item.saved {
            background: #c8e6c9;
            color: #2e7d32;
        }
        
        .surah-item.current {
            background: #fff3e0;
            color: #ef6c00;
            animation: pulse 1.5s infinite;
        }
        
        .surah-item.remaining {
            background: #f5f5f5;
            color: #999;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }
        
        .modal-footer-box {
            background: #f5f5f5;
            padding: 15px;
            text-align: center;
            color: #666;
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
            .stats-container { grid-template-columns: 1fr; }
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
            <h2><i class="fas fa-book-reader"></i> سجل الحفظ والمراجعة</h2>
            <p>تتبع مسار حفظك ومراجعتك اليومية بالتفصيل</p>
        </header>

        <div class="stats-container">
            <div class="stat-card clickable-card" onclick="openSurahsModal()">
                <div class="stat-icon"><i class="fas fa-book-open"></i></div>
                <div class="stat-info">
                    <p>إجمالي الحفظ</p>
                    <h3><?php echo $total_surahs; ?> سورة</h3>
                    <small>إضغط لعرض التفاصيل ✨</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-star"></i></div>
                <div class="stat-info">
                    <p>المستوى الحالي</p>
                    <h3><?php echo $level; ?></h3>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <p>آخر مراجعة</p>
                    <h3><?php echo $last_review_text; ?></h3>
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
                        <?php if (count($recent_sessions) > 0): ?>
                            <?php foreach ($recent_sessions as $session):
                                $amount = "الآية " . $session['from_ayah'] . " - " . $session['to_ayah'];
                                $score = $session['memorization_score'];
                                if ($score >= 18) $rating_class = 'excellent';
                                elseif ($score >= 15) $rating_class = 'good';
                                elseif ($score >= 12) $rating_class = 'average';
                                else $rating_class = 'poor';
                                
                                if ($score >= 18) $rating_text = 'ممتاز';
                                elseif ($score >= 15) $rating_text = 'جيد جداً';
                                elseif ($score >= 12) $rating_text = 'جيد';
                                else $rating_text = 'يحتاج تحسين';
                            ?>
                            <tr>
                                <td><?php echo date('d F Y', strtotime($session['evaluation_date'])); ?></td>
                                <td><?php echo htmlspecialchars($session['surah_name']); ?></td>
                                <td><?php echo $amount; ?></td>
                                <td>حفظ جديد</td>
                                <td><span class="rank-badge <?php echo $rating_class; ?>"><?php echo $rating_text; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">لا توجد حصص حفظ مسجلة</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- نافذة السور المحفوظة -->
    <div id="surahModal" class="custom-modal">
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
                    <?php foreach ($all_surahs as $surah):
                        $surah_name = $surah['surah_name_ar'];
                        $status = $surah_status[$surah_name] ?? 'remaining';
                    ?>
                        <div class="surah-item <?php echo $status; ?>"><?php echo htmlspecialchars($surah_name); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer-box">
                <p>بالتوفيق يا بطل، واصل اجتهادك! ✨</p>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            if (sidebar) {
                sidebar.classList.toggle('collapsed');
                if (mainContent) mainContent.classList.toggle('expanded');
            }
        }

        function openSurahsModal() {
            document.getElementById('surahModal').classList.add('active');
        }

        function closeSurahsModal() {
            document.getElementById('surahModal').classList.remove('active');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('surahModal');
            if (event.target == modal) {
                closeSurahsModal();
            }
        }
    </script>
</body>
</html>