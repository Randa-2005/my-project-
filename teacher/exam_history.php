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

// جلب قائمة الأفواج الخاصة بهذا الأستاذ فقط
$stmt = $conn->prepare("SELECT id, group_name FROM groups WHERE teacher_name = :teacher ORDER BY group_name");
$stmt->execute([':teacher' => $teacher_name]);
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

// معالجة طلب البحث (AJAX)
$is_ajax = isset($_GET['ajax']);
if ($is_ajax) {
    header('Content-Type: application/json');
    
    $group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;
    $search_name = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%%';
    $filter_date = isset($_GET['date']) ? $_GET['date'] : '';
    
    $sql = "
        SELECT 
            e.exam_date,
            u.full_name as student_name,
            er.hifz_score,
            er.ahkam_score,
            er.makharij_score,
            er.total_score,
            g.group_name
        FROM exam_results er
        JOIN exams e ON er.exam_id = e.id
        JOIN users u ON er.student_id = u.id
        JOIN groups g ON e.group_id = g.id
        WHERE g.teacher_name = :teacher
    ";
    
    $params = [':teacher' => $teacher_name];
    
    if ($group_id > 0) {
        $sql .= " AND e.group_id = :group_id";
        $params[':group_id'] = $group_id;
    }
    
    if (!empty($search_name) && $search_name != '%%') {
        $sql .= " AND u.full_name LIKE :search";
        $params[':search'] = $search_name;
    }
    
    if (!empty($filter_date)) {
        $sql .= " AND e.exam_date = :date";
        $params[':date'] = $filter_date;
    }
    
    $sql .= " ORDER BY e.exam_date DESC, u.full_name";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($results);
    exit();
}

// جلب الإحصائيات
$stats_sql = "
    SELECT 
        COUNT(DISTINCT e.id) as total_exams,
        MAX(er.total_score) as max_score
    FROM exam_results er
    JOIN exams e ON er.exam_id = e.id
    JOIN groups g ON e.group_id = g.id
    WHERE g.teacher_name = :teacher
";
$stmt = $conn->prepare($stats_sql);
$stmt->execute([':teacher' => $teacher_name]);
$stats = $stmt->fetch(PDO::FETCH_ASSOC);
$total_exams = $stats['total_exams'] ?? 0;
$max_score = $stats['max_score'] ?? 0;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سجل نتائج الاختبارات</title>
    <link rel="stylesheet" href="teacher_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #1a472a;
            --light-green: #f0f4f1;
            --gold: #ffc107;
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
            padding: 25px;
            min-height: 100vh;
            transition: all 0.3s ease;
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
            padding: 15px 20px;
            border-radius: 12px;
            flex: 1;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .table-wrapper {
            width: 100%;
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
            padding: 15px;
            text-align: center;
        }

        .history-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            color: #444;
            text-align: center;
        }

        .score-tag {
            background: var(--light-green);
            padding: 4px 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .final-grade {
            color: var(--primary-green);
            font-weight: bold;
        }

        .status-pass {
            background: #d4edda;
            color: #155724;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-block;
        }

        .status-fail {
            background: #f8d7da;
            color: #721c24;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-block;
        }

        .btn-print, .btn-home {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-print { background: var(--gold); color: #333; }
        .btn-home { background: var(--primary-green); color: white; margin-right: 10px; }

        .sidebar {
            position: fixed;
            right: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            z-index: 1000;
        }

        .filter-section {
            display: flex;
            gap: 15px;
            background: var(--white);
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            align-items: center;
            flex-wrap: wrap;
        }

        .group-filter, .search-box, .date-filter {
            position: relative;
            flex: 1;
            min-width: 180px;
        }

        .group-filter i, .search-box i, .date-filter i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-green);
        }

        .group-filter select, .search-box input, .date-filter input {
            width: 100%;
            padding: 10px 35px 10px 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
        }

        .btn-reset {
            background: #e9ecef;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            color: #495057;
            transition: 0.3s;
        }

        .btn-reset:hover { background: #dee2e6; }

        .loading {
            text-align: center;
            padding: 30px;
            color: #999;
        }

        @media (max-width: 768px) {
            .main-content { margin-right: 0; }
            .filter-section { flex-direction: column; }
            .group-filter, .search-box, .date-filter { width: 100%; }
        }

        @media print {
            .sidebar, .top-bar .btn-print, .filter-section, .stats-container {
                display: none !important;
            }
            .main-content { margin-right: 0 !important; padding: 0 !important; }
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
                <h3 style="color: #bfa15f; font-size: 20px;">لوحة الأستاذ</h3>
            </div>
        </div>   
        <ul class="nav-links">
            <li onclick="location.href='teacher_groups.php'" class="<?php echo ($current_page == 'teacher_groups.php') ? 'active-gold' : ''; ?>"><i class="fas fa-home"></i> الرئيسية</li>
            <li onclick="location.href='groups_list.php'"><i class="fas fa-users"></i> قوائم التلاميذ</li>
            <li onclick="location.href='exam_history.php'" class="active-gold"><i class="fas fa-file-invoice"></i> سجل الامتحانات</li>
            <li onclick="location.href='general_schedule.php'"><i class="fas fa-calendar-alt"></i> البرنامج العام</li>
            <li onclick="location.href='announcements.php'"><i class="fas fa-bullhorn"></i> الإعلانات</li>
            <li onclick="location.href='teacher_leave.php'"><i class="fas fa-calendar-times"></i> طلب عطلة</li>
            <li onclick="location.href='profile.php'"><i class="fas fa-user-cog"></i> الملف الشخصي</li>
            <li><i class="fas fa-sign-out-alt"></i> خروج</li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="top-bar">
            <h2><i class="fas fa-history"></i> سجل نتائج الاختبارات</h2>
            <button class="btn-print" onclick="window.print()"><i class="fas fa-print"></i> طباعة السجل</button>
        </header>

        <div class="stats-container">
            <div class="stat-box">
                <i class="fas fa-clipboard-list"></i>
                <span>إجمالي الاختبارات: <strong id="totalExams"><?php echo $total_exams; ?></strong></span>
            </div>
            <div class="stat-box">
                <i class="fas fa-star" style="color: #ffc107;"></i>
                <span>أعلى معدل: <strong id="maxScore"><?php echo $max_score; ?></strong></span>
            </div>
        </div>

        <div class="filter-section">
            <div class="group-filter">
                <i class="fas fa-layer-group"></i>
                <select id="groupSelect" onchange="loadResults()">
                    <option value="">كل الأفواج</option>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['group_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="studentSearch" placeholder="ابحث عن اسم الطالب..." onkeyup="loadResults()">
            </div>
            <div class="date-filter">
                <i class="fas fa-calendar-day"></i>
                <input type="date" id="dateSearch" onchange="loadResults()">
            </div>
            <button class="btn-reset" onclick="resetFilters()"><i class="fas fa-undo"></i> إعادة ضبط</button>
        </div>

        <div class="table-wrapper">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>اسم الطالب</th>
                        <th>الفوج</th>
                        <th>حفظ (8)</th>
                        <th>أحكام (8)</th>
                        <th>مخارج (4)</th>
                        <th>المجموع</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody id="resultsBody">
                    <tr class="loading"><td colspan="8">جاري التحميل...</td></tr>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        function loadResults() {
            const groupId = document.getElementById('groupSelect').value;
            const searchName = document.getElementById('studentSearch').value;
            const filterDate = document.getElementById('dateSearch').value;
            
            let url = '?ajax=1';
            if (groupId) url += '&group_id=' + groupId;
            if (searchName) url += '&search=' + encodeURIComponent(searchName);
            if (filterDate) url += '&date=' + filterDate;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('resultsBody');
                    const totalExamsSpan = document.getElementById('totalExams');
                    const maxScoreSpan = document.getElementById('maxScore');
                    
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">لا توجد نتائج مسجلة</td></tr>';
                        if (totalExamsSpan) totalExamsSpan.innerText = '0';
                        if (maxScoreSpan) maxScoreSpan.innerText = '0';
                        return;
                    }
                    
                    // حساب الإحصائيات من البيانات المستلمة
                    const uniqueExams = new Set();
                    let maxTotal = 0;
                    
                    let html = '';
                    data.forEach(row => {
                        uniqueExams.add(row.exam_date + row.group_name);
                        if (row.total_score > maxTotal) maxTotal = row.total_score;
                        
                        const isPass = row.total_score >= 10;
                        const statusClass = isPass ? 'status-pass' : 'status-fail';
                        const statusText = isPass ? 'ناجح' : 'راسب';
                        
                        html += `
                            <tr>
                                <td>${row.exam_date}</td>
                                <td>${row.student_name}</td>
                                <td>${row.group_name}</td>
                                <td><span class="score-tag">${row.hifz_score}</span></td>
                                <td><span class="score-tag">${row.ahkam_score}</span></td>
                                <td><span class="score-tag">${row.makharij_score}</span></td>
                                <td class="final-grade">${row.total_score} / 20</td>
                                <td><span class="${statusClass}">${statusText}</span></td>
                            </tr>
                        `;
                    });
                    
                    tbody.innerHTML = html;
                    if (totalExamsSpan) totalExamsSpan.innerText = uniqueExams.size;
                    if (maxScoreSpan) maxScoreSpan.innerText = maxTotal;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('resultsBody').innerHTML = '<tr><td colspan="8" style="text-align:center; color:red;">خطأ في تحميل البيانات</td></tr>';
                });
        }
        
        function resetFilters() {
            document.getElementById('studentSearch').value = '';
            document.getElementById('dateSearch').value = '';
            document.getElementById('groupSelect').value = '';
            loadResults();
        }
        
        // تحميل النتائج عند فتح الصفحة
        document.addEventListener('DOMContentLoaded', loadResults);
        
        // التحكم في السايدبار
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        
        if (openBtn) openBtn.onclick = () => sidebar.classList.remove('sidebar-closed');
        if (closeBtn) closeBtn.onclick = () => sidebar.classList.add('sidebar-closed');
    </script>
</body>
</html>