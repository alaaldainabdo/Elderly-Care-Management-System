<?php
session_start();
include 'db_connection.php';

// التحقق من وجود بيانات POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // الحصول على البيانات من الطلب
    $input = json_decode(file_get_contents('php://input'), true);
    $alert_id = $input['id'];

    // التحقق من أن المعرف هو عدد صحيح
    if (is_numeric($alert_id)) {
        // إعداد استعلام الحذف
        $stmt = $conn->prepare("DELETE FROM emergency_alerts WHERE id = ?");
        $stmt->bind_param("i", $alert_id); // ربط المعاملات (parameter binding)

        // تنفيذ الاستعلام
        if ($stmt->execute()) {
            // رسالة النجاح
            $response = [
                "message" => "Alert successfully deleted!",
                "status" => "success"
            ];
        } else {
            // رسالة الخطأ
            $response = [
                "message" => " An error occurred:" . $stmt->error,
                "status" => "error"
            ];
        }
        $stmt->close();
    } else {
        $response = [
            "message" => "Invalid ID.",
            "status" => "error"
        ];
    }

    // إعادة استجابة JSON
    
    header('Content-Type: application/json');
    echo json_encode($response);
    
}

$conn->close();
exit();
?>
