<?php
$host = "localhost";
$user = "root";
$pass = "";
$port = 3307; 
$db_name = "smart_quran_school_ossrati";

// 1. الاتصال بـ MySQL
$conn = mysqli_connect($host, $user, $pass, $db_name, $port);

// 2. إنشاء قاعدة البيانات إن لم توجد
$sql_db = "CREATE DATABASE IF NOT EXISTS $db_name";
mysqli_query($conn, $sql_db);
mysqli_select_db($conn, $db_name);

// 3. إنشاء الجدول (في حال لم يكن موجوداً أصلاً)
$sql_table = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255),
    phone VARCHAR(20),
    password VARCHAR(255),
    role VARCHAR(50),
    status VARCHAR(50),
    is_verified TINYINT DEFAULT 0 
)";
mysqli_query($conn, $sql_table);

// 4. الجزء الأهم: إضافة العمود للجدول "الموجود مسبقاً"
// سنتحقق أولاً هل العمود is_verified موجود؟
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'is_verified'");
$exists = (mysqli_num_rows($check_column)) ? TRUE : FALSE;

if (!$exists) {
    // إذا لم يكن موجوداً، نفذ أمر التعديل (ALTER)
    $sql_alter = "ALTER TABLE users ADD is_verified TINYINT DEFAULT 0";
    mysqli_query($conn, $sql_alter);
}
?>