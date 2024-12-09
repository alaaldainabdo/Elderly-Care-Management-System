<?php
// بدء الجلسة
session_start();

// الاتصال بقاعدة البيانات
include 'db_connection.php';
include 'header.php';
// التحقق مما إذا كان المستخدم قد سجل الدخول وأن دوره هو "admin"
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    // إذا لم يكن المستخدم مسؤولاً، إعادة توجيهه إلى صفحة تسجيل الدخول
    header("Location: login.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <link rel="stylesheet" href="style.css"> <!-- إضافة ملف CSS هنا -->
    <link rel="stylesheet" href="styleall.css"> <!-- إضافة ملف CSS هنا -->
</head>
<body>
    <h1>لوحة التحكم المسؤول</h1>
    
    <!-- محتوى لوحة التحكم هنا -->
    
    <p>مرحبًا، <?php echo $_SESSION['user_name']; ?>! هذه لوحة التحكم الخاصة بك.</p>
    <a href="logout.php">تسجيل الخروج</a> <!-- رابط لتسجيل الخروج -->
    <p class="mt-3 fw-normal text-center">To back Home clike -->? <a class="text-info" href="dashboard.php">Home</a></p>

</body>
</html>
