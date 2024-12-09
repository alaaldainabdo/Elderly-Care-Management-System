<?php
session_start();
include 'db_connection.php';

// التحقق من وجود معرف (ID) الملف للحذف
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // إعداد استعلام الحذف
    $sql = "DELETE FROM employees WHERE employeeID = ?";

    // استخدام Prepared Statements للحماية من هجمات SQL Injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id); // ربط المعاملات (parameter binding)

        // تنفيذ الاستعلام
        if ($stmt->execute()) {
            // إعادة توجيه إلى صفحة عرض الملفات مع رسالة نجاح
            $message = "File deleted successfully" ; // "File deleted successfully"
            $message_type = "success"; // Set message type for styling
        } else {
            // إعادة توجيه إلى صفحة عرض الملفات مع رسالة خطأ
            $message = " Error deleting file" . $stmt->error; // "Error deleting file"
            $message_type = "error"; // Set message type for styling
        }
    } else {
        // إعادة توجيه إلى صفحة عرض الملفات مع رسالة خطأ في إعداد الاستعلام
        $message = "Error preparing delete statement" . $conn->error; // "Error preparing delete statement"
        $message_type = "error"; // Set message type for styling
    }
} else {
    // إعادة توجيه إلى صفحة عرض الملفات مع رسالة معرف غير صالح
    $message = "Invalid ID"; // "Invalid ID"
    $message_type = "error"; // Set message type for styling
}

// إعادة توجيه إلى صفحة عرض الملفات مع الرسالة
header("Location: taske_dashboard.php?message=" . urlencode($message) . "&message_type=" . urlencode($message_type));

// إغلاق الاتصال بقاعدة البيانات بعد الانتهاء من جميع العمليات
$conn->close();
exit(); // إنهاء تنفيذ السكربت
?>
