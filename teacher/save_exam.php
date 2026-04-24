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

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];
$group_id = intval($_POST['group_id']);
$exam_title = $_POST['exam_title'];
$exam_date = $_POST['exam_date'];
$exam_type = $_POST['exam_type'];

$stmt = $conn->prepare("INSERT INTO exams (exam_title, group_id, teacher_id, exam_date, exam_type) 
                        VALUES (:title, :group_id, :teacher_id, :exam_date, :type)");
$stmt->execute([
    ':title' => $exam_title,
    ':group_id' => $group_id,
    ':teacher_id' => $teacher_id,
    ':exam_date' => $exam_date,
    ':type' => $exam_type
]);

$exam_id = $conn->lastInsertId();

// جلب تلاميذ الفوج
$stmt = $conn->prepare("SELECT id FROM users WHERE group_id = :group_id AND (role = 'student' OR role = 'طالب')");
$stmt->execute([':group_id' => $group_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// إنشاء نتائج فارغة لكل تلميذ (يمكن تحديثها لاحقاً)
$insert = $conn->prepare("INSERT INTO exam_results (exam_id, student_id, hifz_score, ahkam_score, makharij_score, total_score) 
                          VALUES (:exam_id, :student_id, 0, 0, 0, 0)");
foreach ($students as $student) {
    $insert->execute([':exam_id' => $exam_id, ':student_id' => $student['id']]);
}

header("Location: manage_session.php?group_id=$group_id");
exit();
?>