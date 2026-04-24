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
    die("فشل الاتصال بالقاعدة: " . $e->getMessage());
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../smart-quran-school-Ossrati/ossrati/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM leave_requests WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute([':user_id' => $user_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

$current_page = 'dash';
include 'reception_sidebar.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>حالة طلبات العطلة</title>
    <link rel="stylesheet" href="reception_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            display: inline-block;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        
        .requests-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }
        .requests-table th, .requests-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        .requests-table th {
            background: #1a472a;
            color: white;
        }
        .new-btn {
            display: inline-block;
            margin-top: 20px;
            background: #1a472a;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
        .new-btn:hover {
            background: #2e7d32;
        }
    </style>
</head>
<body>
    <main class="main-content">
        <div class="container" style="padding: 20px;">
            <h2><i class="fas fa-list-alt"></i> طلبات العطلة الخاصة بي</h2>
            
            <?php if (count($requests) > 0): ?>
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>المدة (أيام)</th>
                            <th>تاريخ البداية</th>
                            <th>تاريخ النهاية</th>
                            <th>السبب</th>
                            <th>الحالة</th>
                            <th>ملاحظات المدير</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $req): ?>
                            <tr>
                                <td>#<?php echo $req['id']; ?></td>
                                <td><?php echo $req['days']; ?></td>
                                <td><?php echo $req['start_date']; ?></td>
                                <td><?php echo $req['end_date']; ?></td>
                                <td><?php echo nl2br(htmlspecialchars($req['reason'])); ?></td>
                                <td>
                                    <?php
                                    $status_class = 'status-pending';
                                    $status_text = '⏳ قيد الانتظار';
                                    if ($req['status'] == 'approved') {
                                        $status_class = 'status-approved';
                                        $status_text = '✅ مقبول';
                                    } elseif ($req['status'] == 'rejected') {
                                        $status_class = 'status-rejected';
                                        $status_text = '❌ مرفوض';
                                    }
                                    ?>
                                    <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                </td>
                                <td><?php echo nl2br(htmlspecialchars($req['admin_notes'] ?? '---')); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; padding: 40px;">📭 لا توجد طلبات عطلة مسجلة.</p>
            <?php endif; ?>
            
            <div style="text-align: center;">
                <a href="leave_request.php" class="new-btn"><i class="fas fa-plus"></i> تقديم طلب جديد</a>
            </div>
        </div>
    </main>
</body>
</html>