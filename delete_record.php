<?php
session_start();
include 'db_connection.php'; // قم بتضمين ملف الاتصال بقاعدة البيانات

// تحقق مما إذا كان الـ ID قد تم إرساله
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // تحويل الـ ID إلى عدد صحيح

    // استعلام لحذف السجل
    $sql = "DELETE FROM health_records WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // تنفيذ الاستعلام
    if ($stmt->execute()) {
        $_SESSION['message'] = 'The Record was deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'An error occurred while deleting the record. Please try again.';
        $_SESSION['message_type'] = 'error';
    }

    $stmt->close();
} else {
    $_SESSION['message'] = 'The record has not been marked for deletion.';
    $_SESSION['message_type'] = 'error';
}

// إعادة توجيه إلى صفحة السجلات الصحية
header("Location: health_record_dashboard.php"); // استبدل باسم الصفحة الخاصة بك
exit();
?>
