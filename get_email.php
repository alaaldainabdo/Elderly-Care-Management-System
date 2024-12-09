<?php
include 'db_connection.php';

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    $sql = "SELECT email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();

    echo $email; // إرجاع البريد الإلكتروني
}
?>
