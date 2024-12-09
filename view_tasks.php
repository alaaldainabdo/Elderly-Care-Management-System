<?php
session_start();
include 'db_connection.php';
include 'header.php';



// افترض أن employeeID يتم تمريره عبر GET
$employeeID = $_GET['id'];

// استعلام لجلب معلومات الموظف
$employeeSql = "SELECT fName, lName FROM employees WHERE employeeID = ?";
$employeeStmt = $conn->prepare($employeeSql);
$employeeStmt->bind_param("i", $employeeID);
$employeeStmt->execute();
$employeeResult = $employeeStmt->get_result();
$employee = $employeeResult->fetch_assoc();

$tasks = []; // مصفوفة لتخزين المهام

// استعلام لجلب المهام الخاصة بالموظف
$sql = "SELECT id, description, status, due_date FROM tasks WHERE employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

$stmt->close();
$employeeStmt->close();
$conn->close();
?>
<?php //TEMPLATES
    include 'templates_html/header.html';
    if (isset($_SESSION['user_role'])) {
        switch ($_SESSION['user_role']) {
            case 'admin':
                include 'templates_html/main-grid-content-2columns.html';
                include 'templates_html/admin-side-bar.html';
            default:
                include 'templates_html/alert-dashboard.php';
                include 'templates_html/admin.html';
                include 'templates_html/main-grid-content-1column.html';
        }
    } else {
        include 'templates_html/main-grid-content-1column.html';
    }
    

 
    include 'templates_html/main-content.html';
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Employee Tasks</title>
 <link rel="stylesheet" href="path/to/styles.css">
    <!-- إضافة مكتبات Bootstrap و jQuery -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    

    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            margin-top: 50px;
            padding: 20px;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .employee-name {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container table-container">
        <h3 class="employee-name">Employee_Name: <?php echo htmlspecialchars($employee['fName']) . ' ' . htmlspecialchars($employee['lName']); ?></h3>
        <h3>Employee specific tasks</h3>
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>description</th>
                    <th>status</th>
                    <th> due_date</th>
                    <th>procedures</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tasks)): ?>
                    <tr>
                        <td colspan="5" class="text-center">There are no tasks for this employee.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tasks as $index => $task): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($task['description']); ?></td>
                            <td><?php echo htmlspecialchars($task['status']); ?></td>
                            <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                            <td>
                                <button class="btn btn-danger" onclick="openDeleteModal(<?php echo $task['id']; ?>)">Delet</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="text-center">
            <button class="btn btn-primary" onclick="$('#createTaskModal').modal('show')">Create_New_Task</button>
            <button class="btn btn-secondary" onclick="window.location.href='taske_dashboard.php'">TO_Back</button>
        </div>
    </div>

    <!-- نافذة منبثقة لإنشاء مهمة جديدة -->
   <!-- نافذة منبثقة لإنشاء مهمة جديدة -->
<div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Create New Task</h5>
                <button type="button" class="close" onclick="closeModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createTaskForm">
                    <div class="form-group">
                        <label for="taskDescription">Description</label>
                        <input type="text" class="form-control" id="taskDescription" required>
                    </div>
                    <div class="form-group">
                        <label for="taskStatus">Select_Status</label>
                        <select class="form-control" id="taskStatus" required>
                            <option value="">------</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="taskDueDate">Task Due Date</label>
                        <input type="date" class="form-control" id="taskDueDate" required>
                    </div>
                    <input type="hidden" id="employeeID" value="<?php echo $employeeID; ?>">
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createTask()">Create Task</button>
            </div>
        </div>
    </div>
</div>


    <!-- نافذة تأكيد الحذف -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm_Delet</h5>
                    <button type="button" class="close" onclick="closeDeleteModal()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this task?</p>
                    <input type="hidden" id="deleteTaskID" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delet</button>
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                    </div>
            </div>
        </div>
    </div>

    <script>
        // وظيفة لفتح نافذة تأكيد الحذف
        function openDeleteModal(taskID) {
            $('#deleteTaskID').val(taskID); // ضع ID المهمة في الحقل المخفي
            $('#confirmDeleteModal').modal('show'); // افتح النافذة المنبثقة
        }

        function closeDeleteModal() {
            $('#confirmDeleteModal').modal('hide'); // إغلاق الموديل
        }

        // وظيفة لتأكيد الحذف
        function confirmDelete() {
            const taskID = $('#deleteTaskID').val();
            $.ajax({
                url: 'delete_task.php?id=' + taskID,
                type: 'GET',
                success: function() {
                    alert("The task has been successfully deleted!");
                    location.reload(); // إعادة تحميل الصفحة بعد الحذف
                },
                error: function() {
                    alert('An error occurred while deleting the task.');         
                }
            });
        }

        // وظيفة لإنشاء مهمة جديدة
         // دالة لفتح موديل إنشاء مهمة جديدة
         function openCreateTaskModal() {
            $('#createTaskModal').modal('show'); // استخدم jQuery لفتح الموديل
        }

        // دالة لإغلاق الموديل
        function closeModal() {
            $('#createTaskModal').modal('hide'); // استخدم jQuery لإغلاق الموديل
        }

        // دالة لإنشاء مهمة جديدة
        function createTask() {
            const description = $('#taskDescription').val().trim();
            const status = $('#taskStatus').val();
            const dueDate = $('#taskDueDate').val();
            const employeeID = $('#employeeID').val();

            if (!description) {
                alert('Description field must be filled in.');
                return;
            }
            if (!status) {
                alert('You must select the status.');
                return;
            }
            if (!dueDate) {
                alert('Due date must be specified.');
                return;
            }

            $.ajax({
                url: 'create_task.php',
                type: 'POST',
                data: {
                    description: description,
                    status: status,
                    due_date: dueDate,
                    employee_id: employeeID
                },
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        alert(result.message);
                        closeModal(); // أغلق الموديل بعد إنشاء المهمة
                        location.reload(); // إعادة تحميل الصفحة بعد إنشاء المهمة
                    } else {
                        alert(result.message);
                    }
                },
                error: function() {
                    alert('An error occurred while creating the task.');
                }
            });
        }


    </script>
</body>
</html>
<?php // TEMPLATES
  include 'templates_html/end-main-content.html';
  include 'templates_html/footer.html';
?>