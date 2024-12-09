<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $medical_history = $_POST['medical_history'];
    $emergency_contact = $_POST['emergency_contact'];
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $health_condition = $_POST['health_condition'];
    $current_medications = $_POST['current_medications'];
    $additional_notes = $_POST['additional_notes'];

    // Insert into database
    $sql = "INSERT INTO elderly_profiles (name, age, medical_history, emergency_contact, full_name, gender, address, phone, health_condition, current_medications, additional_notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssssssss", $name, $age, $medical_history, $emergency_contact, $full_name, $gender, $address, $phone, $health_condition, $current_medications, $additional_notes);

 
    if ($stmt->execute()) {
        // إعادة توجيه إلى صفحة عرض الملفات مع رسالة نجاح
        $message = "Elderly profile added successfully!"; // "File deleted successfully"
        $message_type = "success"; // Set message type for styling
    } else {
        // إعادة توجيه إلى صفحة عرض الملفات مع رسالة خطأ
        $message = "Error adding elderly profile!" . $stmt->error; // "Error deleting file"
        $message_type = "error"; // Set message type for styling
    }

 // إعادة توجيه إلى صفحة عرض الملفات مع الرسالة
 header("Location: elderly_profiles_dashpoard.php?message=" . urlencode($message) . "&message_type=" . urlencode($message_type));

}
 $stmt->close();
 $conn->close();
 
exit();

?>