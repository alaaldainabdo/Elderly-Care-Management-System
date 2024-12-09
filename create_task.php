<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // الحصول على البيانات من الطلب
    $description = $_POST['description'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];
    $employee_id = $_POST['employee_id'];

    // استعلام لإدراج المهمة الجديدة
    $sql = "INSERT INTO tasks (description, status, due_date, employee_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $description, $status, $due_date, $employee_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'The task was created successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'An error occurred while creating the task: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
