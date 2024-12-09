<?php
session_start();
include 'db_connection.php';

// التحقق من وجود بيانات POST
if (isset($_POST['id']) && isset($_POST['is_opened'])) {
    $alert_id = $_POST['id'];
    $is_opened = $_POST['is_opened'];

    // تحقق من أن $alert_id و $is_opened تحمل قيم صحيحة
    error_log("Alert ID: $alert_id, is_opened: $is_opened");

    $sql_update = "UPDATE emergency_alerts SET is_opened = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql_update);
    
    $stmt->bind_param("ii", $is_opened, $alert_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Alert status updated successfully.";
        } else {
            echo "No changes made.";
        }
    } else {
        echo "Error updating alert status: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No ID or is_opened set.";
}

$conn->close();
?>
