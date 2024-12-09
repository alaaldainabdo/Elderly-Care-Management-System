<?php
include 'db_connection.php';

$message = ""; // Initialize message variable
$message_type = ""; // Initialize message type variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $elderly_id = $_POST['elderly_id'];
    $alert_message = $_POST['alert_message'];
    $alert_date = $_POST['alert_date'];

    // استعلام لإدراج البيانات مع الحصول على created_by من جدول elderly_profiles
    $sql = "INSERT INTO emergency_alerts (elderly_id, alert_message, alert_date, sent_to)
            VALUES (?, ?, ?, (SELECT created_by FROM elderly_profiles WHERE id = ?))";
    
    // تجهيز الاستعلام
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $elderly_id, $alert_message, $alert_date, $elderly_id);

    if ($stmt->execute()) {
        // Success message
        $message = "Alert added successfully"; // "Alert added successfully"
        $message_type = "success"; // Set message type for styling
    } else {
        // Error message
        $message = "There was an error creating the alert:" . $stmt->error; // "Error creating alert"
        $message_type = "error"; // Set message type for styling
    }

    $stmt->close();
}

$conn->close();

// Redirect back to the previous page with message
header("Location: emergency_dashboard.php?message=" . urlencode($message) . "&message_type=" . urlencode($message_type));
exit();
?>
