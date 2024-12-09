<?php
// الاتصال بقاعدة البيانات
include 'db_connection.php';
include 'header.php';

// Initialize message variables
$message = "";
$message_type = "";


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['reset'])) {
    // Check if all fields are filled
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['role'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $role = $_POST['role'];

        // Check if email already exists
        $sql = "SELECT email FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $message = "Email already exists";
            $message_type = "error";
        } else {
            // Insert the user into the database
            $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $password, $role);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $message = "User added successfully";
                $message_type = "success";
            } else {
                $message = "error: " . $sql . "<br>" . $conn->error;
                $message_type = "error";
            }

            $stmt->close();
        }
    } else {
        $message = "Please fill in all fields";
        $message_type = "error";
    }
}
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
        case 'doctor':
            include 'main-nav-bar.php';
            include 'templates_html/main-grid-content-2columns.html';
            include 'templates_html/doctor-side-bar.html';
            break;
        case 'caregiver':
            include 'main-nav-bar.php';
            include 'templates_html/main-grid-content-2columns.html';
            include 'templates_html/caregiver-side-bar.html';
            break;
        case 'family':
            include 'main-nav-bar.php';
            include 'templates_html/main-grid-content-2columns.html';
            include 'templates_html/familyMember-side-bar.html';
            break;
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
    <div id="message" class="alert alert-<?php echo $message_type; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<main class="form-signing">
    <form action="register.php" method="POST" id="form1">

        <h1 class="h1 mb-3 fw-normal text-center">Sign Up</h1>
        <hr>

        <!-- Role Selection -->
        <label for="role">Choose a role:</label>
        <select name="role" required>
            <option value="" disabled selected>Choose a role</option>
            <option value="admin">admin</option>
            <option value="doctor">doctor</option>
            <option value="caregiver">caregiver </option>
            <option value="family">family</option>
        </select>

        <!-- Form fields for all users -->
        <div class="form-floating mb-3 mt-3">
            <input type="text" id="name" name="name" class="form-control" placeholder="full Name" required>
            <label for="fName">Full Name</label>
        </div>

        <div class="form-floating mb-3 mt-3">
            <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" required>
            <label for="email">Email address</label>
        </div>

        <div class="form-floating mb-3 mt-3">
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            <label for="password">Password</label>
        </div>

        <!-- Submit and Cancel Buttons -->
        <button class="w-100 btn btn-lg btn-info text-light" type="submit">Register</button>
        <button class="w-100 btn btn-sm btn-secondary text-light mt-1 mb-1" type="button" onclick="resetForm()">Cancel</button>

        <hr>
    </form>

    <!-- Link to Login -->
    <p class="mt-3 fw-normal text-center">Have an account? <a class="text-info" href="login.php">Log In</a></p>
</main>

<!-- JavaScript to hide message and reset form -->
<script>
    
// إخفاء الرسالة بعد 5 ثوانٍ
setTimeout(function() {
    var messageElement = document.getElementById("message");
    if (messageElement) {
        messageElement.style.display = "none";
    }
}, 5000);

// تفريغ النموذج عند الضغط على زر "Cancel"
function resetForm() {
    document.getElementById("form1").reset();
}
</script>

<!-- Include the footer -->
<?php
include 'templates_html/end-main-content.html';
include 'templates_html/footer.html';
?>
