<?php
session_start();
// الاتصال بقاعدة البيانات
include 'db_connection.php';

// تحقق مما إذا كان delete_user_id موجودًا
if (isset($_POST['delete_user_id'])) {
    $user_id = $_POST['delete_user_id'];

    // إعداد جملة SQL لحذف المستخدم
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    // تنفيذ البيان والتحقق من النجاح
    if ($stmt->execute()) {
        header("Location: manage_users.php?message=تم حذف المستخدم بنجاح&message_type=success");
        exit();
    } else {
        header("Location: manage_users.php?message=خطأ في حذف المستخدم: " . $conn->error . "&message_type=error");
        exit();
    }
}
$conn->close();
?>
