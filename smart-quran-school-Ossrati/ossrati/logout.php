<?php
session_start();

// 1. مسح جميع بيانات الجلسة
$_SESSION = array();

// 2. تدمير الجلسة تماماً
session_destroy();

// 3. منع التخزين المؤقت لضمان عدم العودة لصفحة محمية
header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 

// 4. التوجيه الصريح للبوابة
header("Location: index.php");
exit();
?>