<?php
// بيانات الاتصال بقاعدة البيانات من Clever Cloud
$host = "bz7t4vc32tsmwyq2nvaf-mysql.services.clever-cloud.com"; 
$db_name = "bz7t4vc32tsmwyq2nvaf"; // Database name
$username = "uqh7ggu2zhofmn4i"; // User
$password = "eEZnyd32j2Zoff8g0ZrE"; // Password

try {
    // إنشاء الاتصال باستخدام تقنية PDO
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    
    // ضبط وضع الأخطاء (مهم جداً أثناء البرمجة)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // سأترك هذه الجملة مؤقتاً للتأكد من نجاح الاتصال
    // echo "تم الاتصال بنجاح!"; 
    
} catch(PDOException $e) {
    echo "فشل الاتصال: " . $e->getMessage();
}
?>