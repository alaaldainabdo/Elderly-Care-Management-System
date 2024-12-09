<?php
include 'db_connection.php';

// افترض أن employeeID يتم تمريره عبر GET
$employeeID = $_GET['id'];

// استعلام لجلب المهام الخاصة بالموظف
$sql = "SELECT id, description, status, due_date FROM tasks WHERE employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$result = $stmt->get_result();

$tasks = []; // مصفوفة لتخزين المهام
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

$stmt->close();
$conn->close();

// إرجاع البيانات بتنسيق JSON
echo json_encode($tasks);
?>
