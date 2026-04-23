<?php
// get_group_students.php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'smart_quran_schooli';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['error' => 'فشل الاتصال بقاعدة البيانات']);
    exit;
}

$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;

if ($group_id <= 0) {
    echo json_encode(['error' => 'معرف الفوج غير صالح']);
    exit;
}

// جلب معلومات الفوج
$stmt = $conn->prepare("SELECT group_name, teacher_name FROM groups WHERE id = :id");
$stmt->execute([':id' => $group_id]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$group) {
    echo json_encode(['error' => 'الفوج غير موجود']);
    exit;
}

// جلب تلاميذ هذا الفوج (role = 'student')
$stmt = $conn->prepare("SELECT id, full_name, birth_date FROM users WHERE group_id = :group_id AND (role = 'student' OR role = 'طالب') ORDER BY full_name");
$stmt->execute([':group_id' => $group_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'group_name' => $group['group_name'],
    'teacher_name' => $group['teacher_name'],
    'students' => $students
]);
?>