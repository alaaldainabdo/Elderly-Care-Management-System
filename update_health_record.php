<?php
// الاتصال بقاعدة البيانات
include('db_connection.php'); // تأكد من تضمين ملف الاتصال بقاعدة البيانات
session_start(); // بدء الجلسة

// التحقق من إرسال البيانات عبر POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // جلب البيانات من النموذج
    $id = $_POST['id'];
    $date = $_POST['date'];
    $vital_signs = $_POST['vital_signs'];
    $health_condition = $_POST['health_condition'];
    $prescriptions = $_POST['prescriptions'];
    $notes = $_POST['notes'];

    // إعداد جملة SQL لتحديث السجل
    $sql = "UPDATE health_records SET date = ?, vital_signs = ?, health_condition = ?, prescriptions = ?, notes = ? WHERE id = ?";
    
    // استخدام prepared statements لتفادي هجمات SQL injection
    if ($stmt = $conn->prepare($sql)) {
        // ربط المعلمات
        $stmt->bind_param("sssssi", $date, $vital_signs, $health_condition, $prescriptions, $notes, $id);

        // تنفيذ الجملة
        if ($stmt->execute()) {
            // إذا نجحت عملية التحديث
            $_SESSION['message'] = "The Record Was Updated Successfully!";
            $_SESSION['message_type'] = 'success'; // نوع الرسالة: نجاح
        } else {
            // في حالة الفشل
            $_SESSION['message'] = "An error occurred while updating the record:" . $conn->error;
            $_SESSION['message_type'] = 'error'; // نوع الرسالة: خطأ
        }

        // إغلاق البيان
        $stmt->close();
    } else {
        $_SESSION['message'] ="Sentence setup error:" . $conn->error;
        $_SESSION['message_type'] = 'error'; // نوع الرسالة: خطأ
    }

    // إعادة توجيه إلى صفحة السجلات الصحية
    header("Location: health_record_dashboard.php");
    exit(); // تأكد من إنهاء السكربت بعد إعادة التوجيه
}

// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>

 