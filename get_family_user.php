<?php
include 'db_connection.php';

$elderly_id = $_GET['elderly_id'];

// استعلام للحصول على المستخدمين الذين لديهم دور "عائلة" والمرتبطين بسجل elderly_profiles
$sql = "SELECT u.id, u.name 
        FROM users u 
        JOIN elderly_profiles ep ON ep.created_by = u.id 
        WHERE ep.id = ? AND u.role = 'عائلة'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $elderly_id);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row; // إضافة كل مستخدم إلى المصفوفة
}

$stmt->close();
$conn->close();

// إرجاع بيانات المستخدمين كـ JSON
header('Content-Type: application/json');
echo json_encode($users);
?>
