<?php
// Start session
session_start();
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Include database connection and header
include 'db_connection.php';

// Initialize variables for messages
$message = ""; // Message variable
$message_type = ""; // Message type (success or error)

// Handle GET error parameter
if (isset($_GET['error'])) {
    $message = "Incorrect Email or Password.";
    $message_type = "error";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and password are filled
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if email exists
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on role
                switch ($_SESSION['user_role']) {
                    case 'doctor':
                        header("Location: Doctor_Dashboard.php");
                        break;
                    case 'admin':
                        header("Location: dashboard.php");
                        break;
                    case 'caregiver':
                        header("Location: employee_dashboard.php");
                        break;
                    case 'family':
                        header("Location: form_insert_elder_profile.php");
                        break;
                    default:
                        header("Location: dashboard.php");
                }
                exit();
            } else {
                $message = "The password or Email is incorrect !!!";
                $message_type = "error";
            }
        } else {
            $message = "The password or Email is incorrect !!!";
            $message_type = "error";
        }
    } else {
        $message = "Please fill in all fields.";
        $message_type = "error";
    }
}

 //TEMPLATES
    include 'templates_html/header.html';

    if (isset($_SESSION['user_role'])) {
        switch ($_SESSION['user_role']) {
            default:
            include 'templates_html/alert-message-before-login.html';
            include 'templates_html/home-nav-bar.html';
            include 'templates_html/main-grid-content-1column.html';
         }
    } else {
          include 'templates_html/alert-message-before-login.html';
          include 'templates_html/home-nav-bar.html';
          include 'templates_html/main-grid-content-1column.html';
    }

  


    include 'templates_html/main-content.html';
?>

<main class="form-signing">
    <?php if ($message && $message_type === "error"): ?>
        <div id="error-message" class="alert alert-danger" role="alert">
            <?php echo $message; ?>
        </div>
        <script>
            // Hide the error message after 5 seconds
            setTimeout(function() {
                const errorMessage = document.getElementById('error-message');
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
            }, 5000);
        </script>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <h1 class="h1 mb-3 fw-normal text-center">Login</h1>
        <hr>

        <div class="form-floating mb-3 mt-3">
            <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" required>
            <label for="email">Email address</label>
        </div>

        <div class="form-floating mb-3 mt-3">
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            <label for="password">Password</label>
        </div>

        <div class="checkbox mb-3 mt-3">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>

        <button class="w-100 btn btn-lg btn-info text-light" name="login" id="login" type="submit">Sign in</button>
        <button class="w-100 btn btn-sm btn-secondary text-light mt-1 mb-1" type="reset">Cancel</button>
    </form>

    <p class="mt-3 fw-normal text-center">Don’t have an account? <a class="text-info" href="register.php">Sign Up</a></p>
    <p class="mt-3 fw-normal text-center">To back Home click -->? <a class="text-info" href="index.php">Home</a></p>
</main>

<?php // TEMPLATES
include 'templates_html/end-main-content.html';
include 'templates_html/footer.html';
?>
