<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Query to check the user's role
$sql_role = "SELECT role FROM users WHERE id = ?";
$stmt_role = $conn->prepare($sql_role);
$stmt_role->bind_param("i", $user_id);
$stmt_role->execute();
$result_role = $stmt_role->get_result();
$user = $result_role->fetch_assoc();

// Check if the user has permission to access the tasks page
if (strtolower($user['role']) === 'doctor' || strtolower($user['role']) === 'caregiver' || strtolower($user['role']) === 'supervisor') {
    // Query to fetch tasks specific to the logged-in employee
    $sql_tasks = "SELECT * FROM tasks WHERE employee_id = ?"; // Adjust the query based on your tasks table structure
    $stmt_tasks = $conn->prepare($sql_tasks);
    $stmt_tasks->bind_param("i", $user_id);
    $stmt_tasks->execute();
    $result_tasks = $stmt_tasks->get_result();

    // Fetch all tasks
    $tasks = $result_tasks->fetch_all(MYSQLI_ASSOC);
} else {
    echo "<h2 class='error'>You do not have permission to access this page.</h2>";
    exit();
}   

// Include templates
include 'templates_html/header.html';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Tasks</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1 class="text-center">Daily Tasks</h1>

    <?php if (!empty($tasks)): ?>
        <table>
            <thead>
                <tr>
                    <th>Task ID</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?php echo $task['id']; ?></td>
                        <td><?php echo $task['description']; ?></td>
                        <td><?php echo $task['due_date']; ?></td>
                        <td><?php echo $task['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tasks found for this employee.</p>
    <?php endif; ?>
    
</body>
</html>

<?php
$conn->close();
?>
