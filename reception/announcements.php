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

$current_page = 'dash';
include 'reception_sidebar.php';

// معالجة إضافة إعلان
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_announcement'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $type = $_POST['type'];
    $target_role = $_POST['target_role'];
    $created_by = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
    
    if ($title && $content) {
        $stmt = $conn->prepare("INSERT INTO announcements (title, content, type, target_role, created_by) 
                                VALUES (:title, :content, :type, :target_role, :created_by)");
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':type' => $type,
            ':target_role' => $target_role,
            ':created_by' => $created_by
        ]);
        echo "<script>alert('✅ تم نشر الإعلان بنجاح!'); window.location.href = 'reception_announcements.php';</script>";
        exit();
    }
}

// جلب الإعلانات المنشورة
$stmt = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الإعلانات</title>
    <link rel="stylesheet" href="reception_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-green: #1a472a; --urgent-red: #d32f2f; --admin-blue: #1976d2; --general-green: #388e3c; --sidebar-width: 260px; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; margin: 0; display: flex; }
        .main-content { flex: 1; margin-right: var(--sidebar-width); padding: 30px; }
        .announcement-form { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 8px; font-weight: bold; color: var(--primary-green); }
        .input-field, textarea, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 1rem; }
        .btn-post { background: var(--primary-green); color: white; border: none; padding: 12px 30px; border-radius: 8px; cursor: pointer; font-weight: bold; }
        .announcements-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .ann-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); position: relative; }
        .ann-card.urgent { border-right: 6px solid var(--urgent-red); }
        .ann-card.admin { border-right: 6px solid var(--admin-blue); }
        .ann-card.general { border-right: 6px solid var(--general-green); }
        .ann-type-label { font-size: 0.75rem; padding: 3px 8px; border-radius: 15px; color: white; }
        .bg-red { background: var(--urgent-red); }
        .bg-blue { background: var(--admin-blue); }
        .bg-green { background: var(--general-green); }
        .ann-date { font-size: 0.8rem; color: #888; margin-top: 15px; display: block; padding-top: 10px; }
        .delete-btn { background: none; border: none; color: red; cursor: pointer; float: left; }
        .open-sidebar-btn { display: none; position: fixed; top: 15px; right: 15px; background: #1a472a; color: white; padding: 10px 15px; border-radius: 8px; cursor: pointer; z-index: 1001; }
        @media (max-width: 768px) { .open-sidebar-btn { display: block; } .main-content { margin-right: 0 !important; padding-top: 60px; } }
    </style>
</head>
<body>

    <?php include 'reception_sidebar.php'; ?>

    <main class="main-content">
        <button id="open-sidebar" class="open-sidebar-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        <h2 style="color: var(--primary-green);"><i class="fas fa-bullhorn"></i> لوحة تحكم الإعلانات</h2>

        <div class="announcement-form">
            <form method="POST">
                <div style="display: flex; gap: 20px;">
                    <div class="input-group" style="flex: 2;">
                        <label>عنوان الإعلان:</label>
                        <input type="text" name="title" class="input-field" placeholder="مثلاً: موعد الفروض الفصلية" required>
                    </div>
                    <div class="input-group" style="flex: 1;">
                        <label>نوع الإعلان:</label>
                        <select name="type" class="input-field">
                            <option value="general">عام (أخضر)</option>
                            <option value="admin">إداري (أزرق)</option>
                            <option value="urgent">هام جداً (أحمر)</option>
                        </select>
                    </div>
                    <div class="input-group" style="flex: 1;">
                        <label>موجه إلى:</label>
                        <select name="target_role" class="input-field">
                            <option value="all">الجميع</option>
                            <option value="student">الطلاب فقط</option>
                            <option value="teacher">الأساتذة فقط</option>
                            <option value="employee">الموظفون فقط</option>
                            <option value="admin">المدير فقط</option>
                        </select>
                    </div>
                </div>
                <div class="input-group">
                    <label>نص الإعلان:</label>
                    <textarea name="content" rows="3" placeholder="اكتب تفاصيل الإعلان هنا..." required></textarea>
                </div>
                <button type="submit" name="add_announcement" class="btn-post"><i class="fas fa-paper-plane"></i> نشر الإعلان للجميع</button>
            </form>
        </div>

        <h3>الإعلانات المنشورة حالياً:</h3>
        <div class="announcements-list">
            <?php foreach ($announcements as $ann): ?>
                <div class="ann-card <?php echo $ann['type']; ?>">
                    <h4>
                        <?php echo htmlspecialchars($ann['title']); ?>
                        <span class="ann-type-label <?php echo $ann['type'] == 'urgent' ? 'bg-red' : ($ann['type'] == 'admin' ? 'bg-blue' : 'bg-green'); ?>">
                            <?php echo $ann['type'] == 'urgent' ? 'هام' : ($ann['type'] == 'admin' ? 'إداري' : 'عام'); ?>
                        </span>
                    </h4>
                    <p><?php echo nl2br(htmlspecialchars($ann['content'])); ?></p>
                    <small><i class="fas fa-users"></i> موجه إلى: <?php echo $ann['target_role'] == 'all' ? 'الجميع' : ($ann['target_role'] == 'student' ? 'الطلاب' : ($ann['target_role'] == 'teacher' ? 'الأساتذة' : ($ann['target_role'] == 'employee' ? 'الموظفون' : 'المدير'))); ?></small>
                    <span class="ann-date"><i class="far fa-clock"></i> نشر بتاريخ: <?php echo date('d/m/Y', strtotime($ann['created_at'])); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <script>function toggleSidebar() { const sidebar = document.getElementById('sidebar'); if(sidebar) sidebar.classList.toggle('active'); }</script>
</body>
</html>