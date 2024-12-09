<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Validate and process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $elderly_id = $_POST['elderly_id'];
    $date = $_POST['date'];
    $vital_signs = $_POST['vital_signs'];
    $notes = $_POST['notes'];
    $updated_by = $_SESSION['user_id'];
    $health_condition = $_POST['health_condition'];
    $prescriptions = $_POST['prescriptions'];

    // Check if session user_id exists in users table
    $sql_check_user = "SELECT id FROM users WHERE id = ?";
    $stmt_check_user = $conn->prepare($sql_check_user);
    $stmt_check_user->bind_param("i", $updated_by);
    $stmt_check_user->execute();
    $result_user = $stmt_check_user->get_result();

    if ($result_user->num_rows == 0) {
        die("Error: User ID from session does not exist in the users table.");
    }

    // Check if elderly_id exists in elderly_profiles table
    $sql_check_elderly = "SELECT id FROM elderly_profiles WHERE id = ?";
    $stmt_check_elderly = $conn->prepare($sql_check_elderly);
    $stmt_check_elderly->bind_param("i", $elderly_id);
    $stmt_check_elderly->execute();
    $result_elderly = $stmt_check_elderly->get_result();

    if ($result_elderly->num_rows == 0) {
        die("Error: The specified elderly ID does not exist in the elderly_profiles table.");
    }

    // Check if a health record already exists for this elderly ID
    $sql_check_record = "SELECT * FROM health_records WHERE elderly_id = ?";
    $stmt_check_record = $conn->prepare($sql_check_record);
    $stmt_check_record->bind_param("i", $elderly_id);
    $stmt_check_record->execute();
    $result_check = $stmt_check_record->get_result();

    if ($result_check->num_rows > 0) {
        $_SESSION['message'] = "There is already an existing health record for the specified Elder.";
        $_SESSION['message_type'] = "error";
    } else {
        // Insert the new health record
        $sql_insert = "INSERT INTO health_records (elderly_id, date, vital_signs, notes, updated_by, health_condition, prescriptions) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("issssss", $elderly_id, $date, $vital_signs, $notes, $updated_by, $health_condition, $prescriptions);

        if ($stmt_insert->execute()) {
            $_SESSION['message'] = "Health record added successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: " . $stmt_insert->error;
            $_SESSION['message_type'] = "error";
        }
    }

    // Redirect with message
    header("Location: health_record_dashboard.php");
    $conn->close();
}
?>
