<?php

session_start();
// Check if there is a message and message type in the URL
$message = ""; // Initialize message variable
$message_class = ""; // Initialize message class variable

if (isset($_GET['message']) && isset($_GET['message_type'])) {
    $message = $_GET['message'];
    $message_class = ($_GET['message_type'] === 'success') ? 'alert alert-success' : 'alert alert-danger';
}

// الاتصال بقاعدة البيانات
include 'db_connection.php';
include 'header.php';





// جلب بيانات الموظفين
$sql = "SELECT e.*, u.name AS user_name FROM employees e JOIN users u ON e.user_id = u.id";
$result = $conn->query($sql);
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

<?php
if (isset($_SESSION['success_message'])) {
    echo '<div id="message" class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // حذف الرسالة بعد عرضها لمنع تكرارها في التحديث التالي
    
}
?>



<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!-- إضافة ملف CSS هنا -->
    <!-- تضمين مكتبات Bootstrap وjQuery -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- إضافة مكتبة Font Awesome -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- استخدم النسخة الأحدث فقط -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- HTML for the message display -->
<div class="container">
    <?php if (!empty($message)): ?>
        <div id="flash-message" class="<?php echo $message_class; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
</div>

    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 40px;
        background-color: #f4f4f4;
    }
    h1 {
        text-align: center;
        color: #333;
    }
    .search-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    .search-container input {
        width: 150px;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    th, td,p {
        padding: 12px;
        text-align: center;
    }
    th {
        background-color: #007BFF;
        color: white;
    }
    tr:hover {
        background-color: #f5f5f5;
    }
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    .action-buttons .btn {
        padding: 5px 10px;
        font-size: 13px;
    }
    .modal-text {
    text-align: center; /* محاذاة النص في المنتصف */
    margin-top: 20px; /* إضافة مسافة من الأعلى لتوسيط النص رأسياً */
    font-size: 18px; /* حجم خط مناسب */
}

.modal-title {
    text-align: center;
}

    </style>

<!-- JavaScript for hiding the message and removing it from URL -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            // إخفاء الرسالة بعد 5 ثوانٍ
            setTimeout(function() {
                flashMessage.style.display = 'none';

                // إزالة الرسالة من عنوان URL
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.delete('message');
                urlParams.delete('message_type');
                // تحديث عنوان URL بدون إعادة تحميل الصفحة
                history.replaceState(null, '', window.location.pathname + '?' + urlParams.toString());
            }, 5000); // 5000 milliseconds = 5 seconds
        }
    });
</script>
    <script>
    // JavaScript لتصفية النتائج بناءً على القيم المدخلة في خانات البحث
    function filterTable() {
        const searchFirstName = document.getElementById('searchFirstName').value.toLowerCase();
        const searchRole = document.getElementById('searchRole').value.toLowerCase();
        const searchSalary = document.getElementById('searchSalary').value.toLowerCase();
        const searchDOB = document.getElementById('searchDOB').value.toLowerCase();
        const searchHireDate = document.getElementById('searchHireDate').value.toLowerCase();
        const generalSearch = document.getElementById('generalSearch').value.toLowerCase();

        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const firstName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const role = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const salary = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
            const dob = row.querySelector('td:nth-child(7)').textContent.toLowerCase();
            const hireDate = row.querySelector('td:nth-child(9)').textContent.toLowerCase();
            const allText = row.textContent.toLowerCase();

            const matchesSearch = (firstName.includes(searchFirstName) &&
                                  role.includes(searchRole) &&
                                  salary.includes(searchSalary) &&
                                  dob.includes(searchDOB) &&
                                  hireDate.includes(searchHireDate)) &&
                                  allText.includes(generalSearch);

            row.style.display = matchesSearch ? '' : 'none';
        });
    }
    </script>
</head>
<body>
<h1>Staff List</h1>
<hr><br>
    <div class="search-container">
        <input type="text" id="searchFirstName" onkeyup="filterTable()" placeholder="Search for first name">
        <input type="text" id="searchRole" onkeyup="filterTable()" placeholder="Search Role">
        <input type="text" id="searchSalary" onkeyup="filterTable()" placeholder="Search Salary">
        <input type="text" id="searchDOB" onkeyup="filterTable()" placeholder="Search DOB">
        <input type="text" id="searchHireDate" onkeyup="filterTable()" placeholder="Search HireDate">
        <input type="text" id="generalSearch" onkeyup="filterTable()" placeholder="General Search">
        
        <!-- زر تحديث -->
        <button class="btn btn-secondary" id="refresh-button" onclick="location.reload();">
            <i class="fas fa-sync-alt"></i> Update
        </button>
    
        <!-- زر إضافة -->
        <button class="btn btn-success" id="add-button" onclick="window.location.href='add_employes.php';">
            <i class="fas fa-plus-circle"></i> Add_Employee
        </button>
    </div>

    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>fName</th>
            <th>lName</th>
            <th>Role</th>
            <th>Email Address</th>
            <th>Salary</th>
            <th> Date of Birth</th>
            <th>Contact NO</th>
            <th>Hire_date</th>
            <th>user_name</th>
            <th>Procedures</th>
        </tr>
    </thead>
    <tbody>
    <?php
// بدء الترقيم التسلسلي من 1
$serial_number = 1;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$serial_number}</td>
            <td>{$row['fName']}</td>
            <td>{$row['lName']}</td>
            <td>{$row['role']}</td>
            <td>{$row['email']}</td>
            <td>{$row['salary']}</td>
            <td>{$row['DOB']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['hire_date']}</td>
            <td>{$row['user_name']}</td>
            <td class='action-buttons'>
                <a href='javascript:void(0);' class='btn btn-info' onclick=\"window.location.href='view_tasks.php?id={$row['employeeID']}';\">
                    <i class='fas fa-tasks'></i>  View_tasks
                </a>

                <a href='javascript:void(0);' class='btn btn-warning' onclick=\"openeditEmployeeModal('{$row['employeeID']}', '{$row['fName']}', '{$row['lName']}', '{$row['role']}', '{$row['email']}', '{$row['salary']}', '{$row['DOB']}', '{$row['phone']}', '{$row['hire_date']}');\">
                    <i class='fas fa-edit'></i> Edit
                </a>
                <a href='javascript:void(0);' class='btn btn-danger' onclick=\"deleteModal('{$row['employeeID']}');\">
                    <i class='fas fa-trash'></i> Delet
                </a>
            </td>
        </tr>";
        $serial_number++;
    }
} else {
    echo "<tr><td colspan='11'>لا توجد بيانات للموظفين</td></tr>";
}
?>



<!-- موديل تأكيد الحذف -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="deleteConfirmationLabel">Delete_Confirmation</h5>                                                                                                                                                                                                                                                                     


            <button type="button" class="close" onclick="closeDeleteModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this record?!!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                <a href="#" id="confirmDeleteButton" class="btn btn-danger">Delet</a>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteModal(employeeID) {
        // تحديث رابط الحذف في زر التأكيد
        document.getElementById('confirmDeleteButton').href = 'delete_employee.php?id=' + employeeID;
        
        // عرض الموديل
        $('#deleteConfirmationModal').modal('show');
    }

    function closeDeleteModal() {
        // إغلاق الموديل

        $('#deleteConfirmationModal').modal('hide');
    }
</script>

</tbody>

<!-- مودال عرض المهام 
<div class="modal fade" id="viewTasksModal" tabindex="-1" role="dialog" aria-labelledby="viewTasksModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTasksModalLabel">المهام الخاصة بالموظف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>الوصف</th>
                            <th>الحالة</th>
                            <th>تاريخ الاستحقاق</th>
                        </tr>
                    </thead>
                    <tbody>
                        <//?php if (!empty($tasks)) : ?>
                            <//?php foreach ($tasks as $task) : ?>
                                <tr>
                                     <td><//?//php echo htmlspecialchars($task['description']); ?></td>
                                    <td><//?php echo htmlspecialchars($task['status']); ?></td>
                                    <td><//?php echo htmlspecialchars($task['due_date']); ?></td>
                                </tr>
                            <//?php endforeach; ?>
                        <//?php else : ?>
                            <tr>
                                <td colspan="3">لا توجد مهام لهذا الموظف.</td>
                            </tr>
                        <//?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div> -->


<!-- موديل التعديل -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeeModalLabel"> Edit_Employee_Record</h5>
                <hr>
                <button type="button" class="close" onclick="closeEditEmployeeModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                
            </div>
            <div class="modal-body">
            <form id="editEmployeeForm" action="update_employee.php" method="POST">
                    <input type="hidden" id="employeeID" name="employeeID">
                    <div class="form-group">
                        <label for="fName">fName</label>
                        <input type="text" class="form-control" id="fName" name="fName" required>
                    </div>
                    <div class="form-group">
                        <label for="lName"> lName</label>
                        <input type="text" class="form-control" id="lName" name="lName" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <input type="text" class="form-control" id="role" name="role" required>
                    </div>
                    <div class="form-group">
                        <label for="email"> Email_Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="salary">Salary</label>
                        <input type="number" class="form-control" id="salary" name="salary" required>
                    </div>
                    <div class="form-group">
                        <label for="DOB">Date_of_Brith </label>
                        <input type="date" class="form-control" id="DOB" name="DOB" required>
                    </div>
                    <div class="form-group">
                        <label for="phone"> Contact_NO</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="hire_date">Hire_date</label>
                        <input type="date" class="form-control" id="hire_date" name="hire_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditEmployeeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" form="editEmployeeForm">Save_Edit</button>
            </div>
        </div>
    </div>
</div>



<script>
// JavaScript لتحديث البيانات في الموديل
function closeEditEmployeeModal() {
    document.getElementById('editEmployeeModal').style.display = 'none';
}

function closeEditEmployeeModal() {
        $('#editEmployeeModal').modal('hide'); // إغلاق الموديل
}

function openeditEmployeeModal(employeeID, fName, lName, role, email, salary, DOB, phone, hire_date) {
    // تعيين القيم إلى العناصر في النموذج
    document.getElementById('employeeID').value = employeeID;
    document.getElementById('fName').value = fName;
    document.getElementById('lName').value = lName;
    document.getElementById('role').value = role;
    document.getElementById('email').value = email;
    document.getElementById('salary').value = salary;
    document.getElementById('DOB').value = DOB;
    document.getElementById('phone').value = phone;
    document.getElementById('hire_date').value = hire_date;

    // فتح الموديل
    $('#editEmployeeModal').modal('show'); // استخدم jQuery لفتح الموديل
}

</script>


</table>

    <a class="btn btn-secondary" href="dashboard.php">Go To Dashboard</a>
</body>
</html>
<?php // TEMPLATES
  include 'templates_html/end-main-content.html';
  include 'templates_html/footer.html';
?>