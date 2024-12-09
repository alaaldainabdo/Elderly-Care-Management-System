<?php
include 'db_connection.php';

// التحقق من أن معرف المهمة قد تم تمريره
if (isset($_GET['id'])) {
    $taskID = $_GET['id'];

    // استعلام لحذف المهمة
    $sql = "DELETE FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $taskID);

    if ($stmt->execute()) {
        // التحقق مما إذا تم حذف أي صفوف
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'The task has been successfully deleted!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'The task was not found.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'An error occurred while deleting the task.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Task ID does not exist.']);
}

$conn->close();
?>
