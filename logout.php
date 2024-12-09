<?php
// بدء الجلسة
session_start();

// إنهاء الجلسة
session_unset(); // إزالة جميع المتغيرات الجلسية
session_destroy(); // تدمير الجلسة الحالية

// إعادة التوجيه إلى صفحة تسجيل الدخول
header("Location: login.php");
exit();
?>
