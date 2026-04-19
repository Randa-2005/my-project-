<?php
// 1. الاتصال بقاعدة البيانات
include 'db1.php';

// 2. التحقق من وصول البيانات عبر POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. استقبال وتأمين البيانات المدخلة
    $id     = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $role   = mysqli_real_escape_string($conn, $_POST['role']);

    // 4. منطق المعالجة (حذف أو تحديث)
    if ($status === 'closed') {
        $sql = "DELETE FROM users WHERE id = '$id'";
    } else {
        $sql = "UPDATE users SET status = '$status' WHERE id = '$id'";
    }

    // 5. إعداد نوع الرد ليكون JSON
    header('Content-Type: application/json');

    // 6. تنفيذ العملية وإرسال النتيجة
    if (mysqli_query($conn, $sql)) {
        echo json_encode([
            "status" => "success", 
            "new_status" => $status
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => mysqli_error($conn)
        ]);
    }
    exit; // إنهاء الملف هنا لضمان عدم إرسال أي نص إضافي

} else {
    // في حال الدخول المباشر للملف
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => "Access Denied"]);
    exit;
}