<?php
session_start();
// الاتصال بقاعدة البيانات
include 'db_connection.php';
include 'header.php';

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if user data is available in the session
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_id'] = $user['id'];
}


if (isset($_GET['message'])) {
    $message = $_GET['message'];
    $message_type = $_GET['message_type'];
    echo "<div id='message' class='alert alert-$message_type'>$message</div>";

    // إضافة كود JavaScript لإخفاء الرسالة بعد 3 ثوانٍ
    echo "
    <script>
        setTimeout(function() {
            var messageDiv = document.getElementById('message');
            if (messageDiv) {
                messageDiv.classList.add('fade-out-hidden');
                setTimeout(function() {
                    messageDiv.style.display = 'none';
                }, 1000); // انتظر حتى تنتهي عملية الاختفاء
            }
        }, 3000); // 3000 مللي ثانية = 3 ثوانٍ
    </script>
    ";
}



// Initialize message variables
$message = "";
$message_type = "";

// Check if form is submitted for updating user details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Check if all fields are filled
    if (!empty($_POST['user_id']) && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['role'])) {
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null; // Hash the new password if provided
 
        
        // Check if email already exists for another user
        $sql = "SELECT email FROM users WHERE email = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $message = "   Email already exists for another User  ";
            $message_type = "error";
        } else {
            // Update the user in the database
            if ($password) {
                $sql = "UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $name, $email, $password, $role, $user_id);
            } else {
                // Update without changing the password
                $sql = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $name, $email, $role, $user_id);
            }

            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $message = "User information updated successfully";
                $message_type = "success";
            } else {
                $message = "Error updating information: " . $conn->error;
                $message_type = "error";
            }

            $stmt->close();
        }
    } else {
        $message = " Please fill in all fields.  ";
        $message_type = "error";
    }
}


// جلب قائمة المستخدمين من قاعدة البيانات مع إمكانية الفلترة
$search_name = isset($_POST['search_name']) ? $_POST['search_name'] : '';
$search_role = isset($_POST['search_role']) ? $_POST['search_role'] : '';
$search_email = isset($_POST['search_email']) ? $_POST['search_email'] : '';

// إعداد استعلام الفلترة
$sql_users = "SELECT id, name, email, role FROM users WHERE 1=1";
$params = [];
$types = '';

if (!empty($search_name)) {
    $sql_users .= " AND name LIKE ?";
    $params[] = "%$search_name%";
    $types .= 's';
}

if (!empty($search_role)) {
    $sql_users .= " AND role = ?";
    $params[] = $search_role;
    $types .= 's';
}

if (!empty($search_email)) {
    $sql_users .= " AND email LIKE ?";
    $params[] = "%$search_email%";
    $types .= 's';
}

$stmt = $conn->prepare($sql_users);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result_users = $stmt->get_result();
$stmt->close();
?>

<!-- Include the templates based on the user's role -->
<?php
include 'templates_html/header.html';

if (isset($_SESSION['user_role'])) {
    switch ($_SESSION['user_role']) {
        case 'admin':
            include 'main-nav-bar.php';
            include 'templates_html/main-grid-content-2columns.html';
            include 'templates_html/admin-side-bar.html';
            break;
        // Add additional user roles as necessary
        default:
            include 'templates_html/home-nav-bar.html';
            include 'templates_html/main-grid-content-1column.html';
    }
} else {
    include 'templates_html/main-grid-content-1column.html';
}

include 'templates_html/main-content.html';
?>

<!-- Display message -->
<?php if (!empty($message)): ?>
    <div id="message" class="alert alert-<?php echo $message_type; ?> fade-out">
        <?php echo $message; ?>
    </div>
    <script>
        // Hide the message after 3 seconds with a fade-out effect
        setTimeout(function() {
            var messageDiv = document.getElementById("message");
            if (messageDiv) {
                messageDiv.classList.add("fade-out-hidden");
                setTimeout(function() {
                    messageDiv.style.display = "none";
                }, 1000); // Wait for the fade-out transition to complete
            }
        }, 3000); // 3000 milliseconds = 3 seconds
    </script>
<?php endif; ?>

<main class="container mt-5">
    <h2 class="text-center"> User Management</h2>
    <hr>
    <!-- Search Filters -->
    <form method="POST" class="mb-4" id="searchForm">
        <div class="row">
            <div class="col-md-4">
                <input type="text" class="form-control" name="search_name" placeholder="Search by name" value="<?php echo htmlspecialchars($search_name); ?>">
            </div>
            <div class="col-md-4">
                <select name="search_role" class="form-select">
                    <option value="">select_role </option>
                    <option value="admin" <?php echo ($search_role == 'admin') ? 'selected' : ''; ?>>admin</option>
                    <option value="doctor" <?php echo ($search_role == 'doctor') ? 'selected' : ''; ?>>doctor</option>
                    <option value="caregiver" <?php echo ($search_role == 'caregiver') ? 'selected' : ''; ?>>caregiver </option>
                    <option value="family" <?php echo ($search_role == 'family') ? 'selected' : ''; ?>>familyMumber</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="email" class="form-control" name="search_email" placeholder="Email Search" value="<?php echo htmlspecialchars($search_email); ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Search</button>
        <button type="button" class="btn btn-secondary mt-2" onclick="resetSearch()">Cancel</button>
        <button type="button" class="btn btn-primary mt-2" onclick="showNewUserModal()">New User</button>
        <button type="button" class="btn btn-danger mt-2" onclick="window.location.href='dashboard.php';">Back</button>

        </form>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email_Address </th>
                <th>Role</th>
                <th>Procedures</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result_users->num_rows > 0): ?>
        <?php while ($user = $result_users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['role']; ?></td>
                <td>
                    <button type="button" class="btn btn-info btn-sm" onclick="editUser(<?php echo $user['id']; ?>, '<?php echo $user['name']; ?>', '<?php echo $user['email']; ?>', '<?php echo $user['role']; ?>')">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteUser(<?php echo $user['id']; ?>)">Delete</button>

                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="text-center"> .. There's No Users .. </td>
        </tr>
    <?php endif; ?>
</tbody>

    </table>
</main>

<script>
// وظيفة لإعادة تعيين حقول البحث عند النقر على زر "إلغاء"
function resetSearch() {
    document.getElementById("searchForm").reset(); // إعادة تعيين النموذج
    window.location.href = window.location.pathname; // تحديث الصفحة
}


// وظيفة لفتح نافذة تعديل المستخدم
function editUser(id, name, email, role) {
    // قم بملء الحقول في نموذج التعديل
    document.getElementById("user_id").value = id;
    document.getElementById("name").value = name;
    document.getElementById("email").value = email;
    document.getElementById("role").value = role;

    // قم بإظهار النموذج
    document.getElementById("editUserModal").style.display = "block";
}

// وظيفة لفتح نافذة تأكيد الحذف
function deleteUser(id) {
    document.getElementById('delete_user_id').value = id; // قم بملء الحقل المخفي برقم المستخدم

    // عرض نافذة التأكيد
    var modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    modal.show();
}

// وظيفة تأكيد الحذف
function confirmDelete() {
    const id = document.getElementById('delete_user_id').value;

    // إنشاء نموذج غير مرئي لإرسال طلب الحذف
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "delete_user.php"; // URL الذي تقوم بإرسال الطلب إليه

    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "delete_user_id";
    input.value = id;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit(); // إرسال الطلب
}

// ... باقي الكود ...

// إغلاق نافذة التعديل
function closeEditModal() {
    document.getElementById("editUserModal").style.display = "none";
}

// وظيفة لتحديث بيانات المستخدم
function updateUser() {
    const id = document.getElementById("user_id").value;
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const role = document.getElementById("role").value;

    // يمكنك هنا إرسال البيانات إلى الخادم باستخدام AJAX أو أي وسيلة أخرى
    console.log(`Updating user: ${id}, ${name}, ${email}, ${role}`);
    
    // إغلاق نافذة التعديل بعد التحديث
    closeEditModal();
}

</script>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password (leave empty if you don't want to change it)</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="edit_password" name="password">
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword" onclick="togglePasswordVisibility()">👁️</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="doctor">Doctor</option>
                            <option value="caregiver">Caregiver</option>
                            <option value="family">Family</option>
                        </select>
                    </div>
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Function to toggle password visibility
function togglePasswordVisibility() {
    const passwordField = document.getElementById('edit_password');
    const passwordToggleBtn = document.getElementById('togglePassword');

    if (passwordField.type === 'password') {
        passwordField.type = 'text'; // Show password
        passwordToggleBtn.innerHTML = '🙈'; // Change icon to hide
    } else {
        passwordField.type = 'password'; // Hide password
        passwordToggleBtn.innerHTML = '👁️'; // Change icon to show
    }
}
</script>
<style>
    /* تصغير حجم نص التسمية الخاصة بزر إظهار/إخفاء كلمة السر */
    .input-group .btn {
        font-size: 0.7rem; /* تغيير الحجم كما ترغب */
    }
</style>

<!-- New User Modal -->
<div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newUserModalLabel">Register New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newUserForm" method="POST" action="register1.php">
                    <div class="mb-3">
                        <label for="new_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="new_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="new_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="password">
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword" onclick="togglePasswordVisibility()">👁️</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_role" class="form-label">Role</label>
                        <select class="form-select" id="new_role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="doctor">Doctor</option>
                            <option value="caregiver">Caregiver</option>
                            <option value="family">Family</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

// Function to toggle password visibility
function togglePasswordVisibility() {
    const passwordField = document.getElementById('new_password');
    const passwordToggleBtn = document.getElementById('togglePassword');

    if (passwordField.type === 'password') {
        passwordField.type = 'text'; // Show password
        passwordToggleBtn.innerHTML = '🙈'; // Change icon to hide
    } else {
        passwordField.type = 'password'; // Hide password
        passwordToggleBtn.innerHTML = '👁️'; // Change icon to show
    }
}

function showNewUserModal() {
    var modal = new bootstrap.Modal(document.getElementById('newUserModal'));
    modal.show();
}
</script>


<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?!</p>
                <input type="hidden" name="delete_user_id" id="delete_user_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete</button>
                
            </div>
        </div>
    </div>
</div>

<script>
    
function editUser(id, name, email, role) {
    // Fill the modal with user data
    document.getElementById('user_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;
    

    // Show the modal
    var modal = new bootstrap.Modal(document.getElementById('editUserModal'));
    modal.show();
}

function deleteUser(id) {
    document.getElementById('delete_user_id').value = id;

    // Show the delete modal
    var modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    modal.show();
}


</script>
<!-- Include the footer -->
<?php
include 'templates_html/end-main-content.html';
include 'templates_html/footer.html';
?>
<!-- CSS لجعل الزر جذابًا -->
<style>
/* Styles for the password toggle button */
#togglePassword {
    width: 100%; /* Make the button full width */
    transition: background-color 0.3s, color 0.3s; /* Smooth transition */
}

#togglePassword:hover {
    background-color: #007bff; /* Change background color on hover */
    color: white; /* Change text color on hover */
}

.fade-out {
    transition: opacity 1s ease; /* Smooth transition for opacity */
}

.fade-out-hidden {
    opacity: 0; /* Set opacity to 0 for fade-out effect */
}

.fade-out {
    transition: opacity 1s ease; /* Smooth transition for opacity */
}

.fade-out-hidden {
    opacity: 0; /* Set opacity to 0 for fade-out effect */
}

</style>
