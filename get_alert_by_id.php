<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $alert_id = $_GET['id'];

    $sql = "SELECT alert_message, elderly_id FROM emergency_alerts WHERE id = '$alert_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $alert = $result->fetch_assoc();
        echo json_encode($alert);
    } else {
        echo json_encode([]);
    }
}

$conn->close();
?>
