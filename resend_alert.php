<?php
include 'db_connection.php';

if (isset($_GET['alert_id'])) {
    $alertId = intval($_GET['alert_id']);

    // جلب معلومات التنبيه من قاعدة البيانات
    $sql = "SELECT a.alert_message, e.emergency_contact
            FROM alerts a
            JOIN elderly_profiles e ON a.elderly_id = e.id
            WHERE a.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $alertId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $alert = $result->fetch_assoc();
        $message = urlencode($alert['alert_message']); // ترميز الرسالة
        $phone = $alert['emergency_contact'];

        // إنشاء رابط WhatsApp لإرسال الرسالة
        $whatsappUrl = "https://api.whatsapp.com/send?phone={$phone}&text={$message}";

        // يمكنك استخدام cURL أو file_get_contents لإجراء الطلب
        // لكن في هذه الحالة، نقوم بإرجاع الرابط فقط
        echo "تمت إعادة إرسال التنبيه. يمكنك الاطلاع عليه <a href='{$whatsappUrl}' target='_blank'>هنا</a>.";
    } else {
        echo "التنبيه غير موجود.";
    }
} else {
    echo "معرف التنبيه غير صالح.";
}

$stmt->close();
$conn->close();
?>
