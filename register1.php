<?php
// الاتصال بقاعدة البيانات
include 'db_connection.php';

// Initialize message variables
$message = "";
$message_type = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all fields are filled
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['role'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $role = $_POST['role'];

        // Check if email already exists
        $sql = "SELECT email FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $message = "البريد الإلكتروني موجود بالفعل.";
            $message_type = "error";
        } else {
            // Insert the new user into the database
            $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $password, $role);
            if ($stmt->execute()) {
                $message = "تم تسجيل المستخدم بنجاح.";
                $message_type = "success";
            } else {
                $message = "خطأ في التسجيل: " . $conn->error;
                $message_type = "error";
            }
            $stmt->close();
        }
    } else {
        $message = "يرجى ملء جميع الحقول.";
        $message_type = "error";
    }
}

// Redirect back to the previous page with message
header("Location: manage_users.php?message=$message&message_type=$message_type");
exit();



?>
