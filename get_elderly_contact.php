<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $elderly_id = $_GET['id'];

    $sql = "SELECT emergency_contact FROM elderly_profiles WHERE id = '$elderly_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $contact = $result->fetch_assoc();
        echo json_encode($contact);
    } else {
        echo json_encode([]);
    }
}

$conn->close();
?>
    