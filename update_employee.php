<?php
session_start();
include 'db_connection.php';

$message = ""; // Initialize message variable
$message_type = ""; // Initialize message type variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // استرجاع البيانات من النموذج
    $employeeID = $_POST['employeeID'];
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $salary = $_POST['salary'];
    $DOB = $_POST['DOB'];
    $phone = $_POST['phone'];
    $hire_date = $_POST['hire_date'];

    // إعداد استعلام التحديث
    $stmt = $conn->prepare("UPDATE employees SET fName=?, lName=?, role=?, email=?, salary=?, DOB=?, phone=?, hire_date=? WHERE employeeID=?");
    $stmt->bind_param("ssssdssss", $fName, $lName, $role, $email, $salary, $DOB, $phone, $hire_date, $employeeID);

    // تنفيذ الاستعلام
    if ($stmt->execute()) {
        // Success message
        $message = "تم تعديل البيانات بنجاح"; // "Data updated successfully"
        $message_type = "success"; // Set message type for styling
    } else {
        // Error message
        $message = "حدث خطأ أثناء تعديل البيانات: " . $stmt->error; // "Error updating data"
        $message_type = "error"; // Set message type for styling
    }

    $stmt->close();
}

$conn->close();

// Redirect back to the previous page with message
header("Location: taske_dashboard.php?message=" . urlencode($message) . "&message_type=" . urlencode($message_type));
exit();
?>
