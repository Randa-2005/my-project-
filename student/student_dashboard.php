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
$student_display_name = $student['full_name'];
$student_group_id = $student['group_id'];

// جلب أو إنشاء سجل تقدم الطالبة
$stmt = $conn->prepare("SELECT * FROM student_progress WHERE student_id = :id");
$stmt->execute([':id' => $student_id]);
$progress = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$progress) {
    $insert = $conn->prepare("INSERT INTO student_progress (student_id, current_surah) VALUES (:id, 'الفاتحة')");
    $insert->execute([':id' => $student_id]);
    
    $stmt = $conn->prepare("SELECT * FROM student_progress WHERE student_id = :id");
    $stmt->execute([':id' => $student_id]);
    $progress = $stmt->fetch(PDO::FETCH_ASSOC);
}

$current_surah_name = $progress['current_surah'] ?? 'الفاتحة';
$current_ayah = $progress['current_ayah'] ?? 0;

// ✅ تنظيف اسم السورة (إزالة كلمة "سورة" المكررة)
$current_surah_name = str_replace('سورة ', '', $current_surah_name);
$current_surah_name = str_replace('سورة', '', $current_surah_name);

// جلب بيانات التقييم
$stmt = $conn->prepare("
    SELECT SUM(memorization_score) as total_score, 
           COUNT(*) as total_sessions,
           SUM(CASE WHEN status = 'حاضر' THEN 1 ELSE 0 END) as present_count
    FROM daily_evaluation 
    WHERE student_id = :id
");
$stmt->execute([':id' => $student_id]);
$eval_data = $stmt->fetch(PDO::FETCH_ASSOC);

$total_score = $eval_data['total_score'] ?? 0;
$total_sessions = $eval_data['total_sessions'] ?? 1;
$present_count = $eval_data['present_count'] ?? 0;
$attendance_rate = ($total_sessions > 0) ? round(($present_count / $total_sessions) * 100) : 0;

// ========== ✅ حساب عدد الأحزاب بناءً على آخر آية ==========
// التحقق من وجود جدول parts
$check_parts = $conn->query("SHOW TABLES LIKE 'parts'");
if ($check_parts->rowCount() > 0) {
    // عدد الأحزاب المحفوظة من جدول parts
    $stmt = $conn->prepare("
        SELECT COUNT(*) as completed_parts 
        FROM parts 
        WHERE (start_surah < :surah) 
           OR (start_surah = :surah AND start_ayah <= :ayah)
    ");
    $stmt->execute([':surah' => $current_surah_name, ':ayah' => $current_ayah]);
    $parts_result = $stmt->fetch(PDO::FETCH_ASSOC);
    $memorized_parts = $parts_result['completed_parts'] ?? 1;
    $memorized_juz = ceil($memorized_parts / 2);
} else {
    // إذا لم يكن جدول parts موجوداً
    $memorized_parts = 1;
    $memorized_juz = 1;
}

// تحديث جدول التقدم
$update = $conn->prepare("UPDATE student_progress SET memorized_parts = :parts, memorized_juz = :juz, total_score = :score WHERE student_id = :id");
$update->execute([
    ':parts' => $memorized_parts, 
    ':juz' => $memorized_juz,
    ':score' => $total_score, 
    ':id' => $student_id
]);

// جلب ترتيب الطالبة في فوجها
$stmt = $conn->prepare("
    SELECT COUNT(*) + 1 as rank
    FROM users u2
    JOIN student_progress sp ON u2.id = sp.student_id
    WHERE u2.group_id = :group_id
    AND sp.total_score > (SELECT total_score FROM student_progress WHERE student_id = :id)
    LIMIT 1
");
$stmt->execute([':group_id' => $student_group_id, ':id' => $student_id]);
$rank_result = $stmt->fetch(PDO::FETCH_ASSOC);
$student_rank = $rank_result['rank'] ?? 1;

$update = $conn->prepare("UPDATE student_progress SET rank_in_group = :rank WHERE student_id = :id");
$update->execute([':rank' => $student_rank, ':id' => $student_id]);

// جلب آخر ملاحظة
$stmt = $conn->prepare("
    SELECT de.notes, de.evaluation_date, t.full_name as teacher_name
    FROM daily_evaluation de
    JOIN users t ON de.teacher_id = t.id
    WHERE de.student_id = :id AND de.notes IS NOT NULL AND de.notes != ''
    ORDER BY de.evaluation_date DESC
    LIMIT 1
");
$stmt->execute([':id' => $student_id]);
$last_note = $stmt->fetch(PDO::FETCH_ASSOC);


$days_map = [
    'Saturday' => 'السبت',
    'Sunday' => 'الأحد',
    'Monday' => 'الإثنين',
    'Tuesday' => 'الثلاثاء',
    'Wednesday' => 'الأربعاء',
    'Thursday' => 'الخميس',
    'Friday' => 'الجمعة'
];
$current_day_ar = $days_map[date('l')] ?? '';
$current_time = date('H:i:s');

// جلب فوج الطالب أولاً
$stmt = $conn->prepare("SELECT group_id FROM users WHERE id = :id");
$stmt->execute([':id' => $student_id]);
$user_group = $stmt->fetch(PDO::FETCH_ASSOC);
$user_group_id = $user_group['group_id'] ?? 0;

// جلب اسم الفوج
$stmt = $conn->prepare("SELECT group_name FROM groups WHERE id = :id");
$stmt->execute([':id' => $user_group_id]);
$user_group_info = $stmt->fetch(PDO::FETCH_ASSOC);
$user_group_name = $user_group_info['group_name'] ?? '';

// جلب جميع حصص هذا الفوج فقط (وليس كل الحصص)
$stmt = $conn->prepare("
    SELECT s.day, s.start_time, s.end_time, r.room_number, g.group_name
    FROM schedules s
    JOIN groups g ON s.group_id = g.id
    JOIN rooms r ON s.room_id = r.id
    WHERE s.group_id = :group_id AND s.status = 'active'
    ORDER BY 
        FIELD(s.day, 'السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس'),
        s.start_time ASC
");
$stmt->execute([':group_id' => $user_group_id]);
$all_sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$days_order = ['السبت'=>1, 'الأحد'=>2, 'الإثنين'=>3, 'الثلاثاء'=>4, 'الأربعاء'=>5, 'الخميس'=>6];
$today_order = $days_order[$current_day_ar] ?? 7;
$current_time_seconds = strtotime($current_time);

$next_session = null;
$best_day_diff = 999;
$best_time = null;

foreach ($all_sessions as $session) {
    $session_day_order = $days_order[$session['day']] ?? 7;
    $session_time_seconds = strtotime($session['start_time']);
    
    // حساب الفرق بالأيام
    $day_diff = $session_day_order - $today_order;
    if ($day_diff < 0) $day_diff += 7;
    
    // إذا كان اليوم هو نفسه ووقت الحصة مضى، تخطى
    if ($day_diff == 0 && $session_time_seconds <= $current_time_seconds) {
        continue;
    }
    
    // إذا كان الفرق أقل أو نفس الفرق ووقت أبكر
    if ($day_diff < $best_day_diff || 
        ($day_diff == $best_day_diff && $session_time_seconds < ($best_time ?? 99999))) {
        $best_day_diff = $day_diff;
        $best_time = $session_time_seconds;
        $next_session = $session;
    }
}
// ========== حساب التقدم في السورة ==========
// جلب معلومات السورة (عدد الآيات الصحيح)
$stmt = $conn->prepare("SELECT surah_number, total_ayahs, surah_name_ar FROM surahs WHERE surah_name_ar = :surah OR surah_name = :surah");
$stmt->execute([':surah' => $current_surah_name]);
$surah_info = $stmt->fetch(PDO::FETCH_ASSOC);

$total_ayahs = $surah_info['total_ayahs'] ?? 7;
$current_surah_num = $surah_info['surah_number'] ?? 1;
$surah_display_name = $surah_info['surah_name_ar'] ?? $current_surah_name;

// ✅ التأكد من أن الآية لا تتجاوز عدد الآيات
if ($current_ayah > $total_ayahs) {
    $current_ayah = $total_ayahs;
}

$target_progress = ($total_ayahs > 0) ? min(round(($current_ayah / $total_ayahs) * 100), 100) : 0;

// جلب السورة التالية
$next_surah_number = $current_surah_num + 1;
$stmt = $conn->prepare("SELECT surah_name_ar FROM surahs WHERE surah_number = :num");
$stmt->execute([':num' => $next_surah_number]);
$next_surah_info = $stmt->fetch(PDO::FETCH_ASSOC);
$next_surah_name = $next_surah_info['surah_name_ar'] ?? '';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم الطالب - مدرسة أسرتي</title>
    <link rel="stylesheet" href="student_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Cairo',sans-serif;background:#f4f7f6;display:flex}
        .stats-container{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:30px}
        .stat-card{background:#fff;border-radius:15px;padding:20px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 4px 15px rgba(0,0,0,0.05);border-bottom:4px solid #bfa15f}
        .stat-info h3{color:#2e7d32;font-size:1.8rem;margin:0}
        .stat-info p{color:#666;font-size:0.9rem;margin:5px 0 0}
        .stat-icon{background:rgba(191,161,95,0.1);color:#bfa15f;width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem}
        .progress-section{background:#fff;padding:25px;border-radius:15px;margin-bottom:30px}
        .progress-header{display:flex;justify-content:space-between;margin-bottom:10px}
        .progress-bar-bg{background:#eee;height:12px;border-radius:10px;overflow:hidden}
        .progress-bar-fill{background:linear-gradient(90deg,#2e7d32,#bfa15f);height:100%;width:<?php echo $target_progress; ?>%;border-radius:10px}
        .dashboard-grid{display:grid;grid-template-columns:2fr 1fr;gap:20px}
        .info-box{background:#fff;padding:20px;border-radius:15px}
        .info-box h4{color:#2e7d32;border-right:4px solid #bfa15f;padding-right:10px;margin-top:0}
        .teacher-note{background:#fdfaf3;border-radius:10px;padding:15px;border-right:4px solid #bfa15f;font-style:italic;color:#555}
        .surah-badge{background:#e8f5e9;padding:8px 15px;border-radius:25px;color:#2e7d32;font-weight:bold;display:inline-block;margin-top:5px}
        .toggle-menu-btn{display:none;position:fixed;top:15px;right:15px;background:#2e7d32;color:#fff;border:none;padding:10px 15px;border-radius:8px;cursor:pointer;z-index:1100}
        @media (max-width:768px){.toggle-menu-btn{display:block}.main-content{margin-right:0!important;padding-top:60px}.dashboard-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>

    <?php include 'student_sidebar.php'; ?>
    <button class="toggle-menu-btn" id="open-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <main class="main-content" id="mainContent" style="flex:1;padding:30px;margin-right:260px">
        <header style="margin-bottom:30px">
            <h2 style="color:#2e7d32">أهلاً بك يا بطل، <?php echo htmlspecialchars($student_display_name); ?>! ✨</h2>
            <p style="color:#666">إليك ملخص إنجازاتك في حفظ القرآن الكريم</p>
        </header>

        <div class="stats-container">
            <div class="stat-card"><div class="stat-info"><h3><?php echo $progress['memorized_parts']; ?></h3><p>حزباً محفوظاً</p></div><div class="stat-icon"><i class="fas fa-book-open"></i></div></div>
            <div class="stat-card"><div class="stat-info"><h3><?php echo $attendance_rate; ?>%</h3><p>نسبة الحضور</p></div><div class="stat-icon"><i class="fas fa-user-check"></i></div></div>
            <div class="stat-card"><div class="stat-info"><h3><?php echo $student_rank; ?></h3><p>ترتيبك بالفوج</p></div><div class="stat-icon"><i class="fas fa-trophy"></i></div></div>
        </div>

        <div class="progress-section">
            <div class="progress-header">
                <div>
                    <span style="font-weight:bold;color:#2e7d32">التقدم في سورة <?php echo htmlspecialchars($surah_display_name); ?></span>
                    <div class="surah-badge"><i class="fas fa-quran"></i> الآية <?php echo $current_ayah; ?> من <?php echo $total_ayahs; ?></div>
                </div>
                <span style="color:#bfa15f;font-weight:bold"><?php echo $target_progress; ?>%</span>
            </div>
            <div class="progress-bar-bg"><div class="progress-bar-fill"></div></div>
            <p style="font-size:0.8rem;color:#888;margin-top:10px">
                <?php
                $remaining = $total_ayahs - $current_ayah;
                if ($remaining > 0 && $remaining < $total_ayahs) {
                    echo "📖 بقي لك <strong>$remaining آية</strong> لإتمام سورة " . htmlspecialchars($surah_display_name) . ". واصل الاجتهاد!";
                    if (!empty($next_surah_name)) echo " بعدها تبدأ سورة <strong>$next_surah_name</strong> إن شاء الله.";
                } elseif ($current_ayah >= $total_ayahs && $total_ayahs > 0) {
                    echo "🎉 <strong>مبارك!</strong> لقد أتممت سورة " . htmlspecialchars($surah_display_name) . " بنجاح!";
                    if (!empty($next_surah_name)) echo " استعد لبدء سورة <strong>$next_surah_name</strong>.";
                } else {
                    echo "🌟 ابدأ رحلتك مع سورة " . htmlspecialchars($surah_display_name) . "، نتمنى لك التوفيق!";
                }
                ?>
            </p>
        </div>

        <div class="dashboard-grid">
            <div class="info-box">
                <h4><i class="fas fa-comment-dots"></i> آخر ملاحظة من الأستاذ</h4>
                <div class="teacher-note">"<?php echo htmlspecialchars($last_note['notes'] ?? 'لا توجد ملاحظات جديدة. واصل التميز!'); ?>"</div>
                <?php if ($last_note): ?>
                <p style="text-align:left;font-size:0.8rem;color:#999;margin-top:10px">- <?php echo htmlspecialchars($last_note['teacher_name'] ?? 'الأستاذ'); ?> • <?php echo date('d/m/Y', strtotime($last_note['evaluation_date'])); ?></p>
                <?php endif; ?>
            </div>
            <div class="info-box">
                <h4><i class="fas fa-calendar-alt"></i> الحصة القادمة</h4>
                <?php if ($next_session): ?>
                    <p><b>اليوم:</b> <?php echo $next_session['day']; ?></p>
                    <p><b>الساعة:</b> <?php echo date('H:i', strtotime($next_session['start_time'])); ?></p>
                    <p><b>القاعة:</b> <?php echo htmlspecialchars($next_session['room_number']); ?> (<?php echo htmlspecialchars($next_session['group_name']); ?>)</p>
                <?php else: ?>
                    <p>لا توجد حصص مسجلة لهذا اليوم</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar(){const sidebar=document.getElementById('sidebar'),mainContent=document.getElementById('mainContent');if(sidebar){sidebar.classList.toggle('collapsed');if(mainContent)mainContent.classList.toggle('expanded')}}
    </script>
</body>
</html>