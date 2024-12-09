<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $elderly_id = $_GET['elderly_id']; // الحصول على معرف كبير السن

    // استعلام لجلب التنبيهات الخاصة بكبير السن
    $sql = "SELECT * FROM emergency_alerts WHERE elderly_id = '$elderly_id' ORDER BY alert_date DESC";
    $result = $conn->query($sql);

    $alerts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $alerts[] = $row;
        }
    }

    // تحويل البيانات إلى JSON وإرجاعها
    echo json_encode($alerts);
}

$conn->close();
?>
