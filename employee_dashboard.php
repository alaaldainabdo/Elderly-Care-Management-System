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

if (strtolower($user['role']) === 'doctor' || strtolower($user['role']) === 'caregiver' || strtolower($user['role']) === 'supervisor') {
    $sql = "SELECT employeeID, fName, lName, role, salary, email, DOB, phone, hire_date 
            FROM employees WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
    } else {
        $employee = null;
        $error_message = "No data found for this user.";
    }

    // Get user details for settings
    $sql_user = "SELECT name, email, password FROM users WHERE id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $user_info = $result_user->fetch_assoc();
    
} else {
    echo "<h2 class='error'>You do not have permission to access this page.</h2>";
    exit();
}

// Include templates
include 'templates_html/header.html';
if (isset($_SESSION['user_role'])) {
    switch ($_SESSION['user_role']) {
        case 'admin':
            include 'templates_html/main-grid-content-2columns.html';
            include 'templates_html/admin-side-bar.html';
            break; // Add break statement
        default:
            include 'templates_html/alert-dashboard.php';
            include 'templates_html/employee.php';
            include 'templates_html/main-grid-content-1column.html';
    }
} else {
    include 'templates_html/main-grid-content-1column.html';
}

include 'templates_html/main-content.html';

?>
 <h1 class="text-center pb-5">ElderlyCare Management System</h1>
        <hr>

        <ul class="nav col-13 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
         
            <li><a href="health_record_dashboard.php" class="nav-link px-2 text-dark"> health_records management</a></li>
            <li><a href="elderly_profiles_dashpoard.php" class="nav-link px-2 text-dark"> Elderly_Profiles_management</a></li>
            <li><a href="insert_medication_schedule.php" class="nav-link px-2 text-dark"> medication_Schedule_management</a></li>
            <li><a href="emergency_dashboard.php" class="nav-link px-2 text-dark"> Emergency_Alerts_management</a></li>
            <li><a href="daily_tasks.php" class="nav-link px-2 text-dark">Daily Tasks</a></li> <!-- Added Button -->

          
        </ul>
        <hr>
        <br>

      <div class="mb-5 text-start p-5">
        <h2>Overview</h2>
        <br>
        <h3> Advanced Software Project 01</h3>

        <p>This project aims to replicate the workflow of a 
        real-world development agency by planning, designing, 
        and implementing a functional web application based 
        on client needs and organizational methodologies. It 
        integrates all the skills and concepts we've developed 
        throughout this year.</p>

        <h4>Timeline</h4>
        <p>The project spans five weeks. Each week begins with a 
        strategic planning session to organize tasks and 
        evaluate the progress of the previous week's release.</p>
        <hr>
        <h3>Technologies</h2>
        <ul>
          <li>HTML5</li>
          <li>CSS3</li>
          <li>JavaScript</li>
          <li>PHP</li>
          <li>phpMyAdmin</li>
          <li>MySQL</li>  
          <li>Agile Process</li>
          <li>Feature Branch Git Workflow</li>
        </ul>
        <hr>
      <h3>Software Engineer</h2>
        <ul>
          <li>Ala Al_Dain</li>
            
       
         <h6>Contact Us</<h6>
         <li>917558575378</li>
        
      
          <hr>
    </div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile</title>
    <!-- FontAwesome for the profile icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            direction: ltr;
        }

        /* Profile icon design */
        .profile-container {
    position: fixed; /* تثبيت الحاوية في الزاوية */
    top: 20px; /* المسافة من الأعلى */
    right: 20px; /* المسافة من اليمين */
    text-align: center; /* محاذاة النص في المنتصف */
}

.profile-icon {
    font-size: 24px; /* حجم الأيقونة */
    color: white; /* لون الأيقونة */
    cursor: pointer; /* تغيير المؤشر عند المرور فوق الأيقونة */
    background-color: rgba(0, 0, 0, 0.5); /* خلفية داكنة مع شفافية (اختياري) */
    padding: 10px; /* إضافة padding */
    border-radius: 10px; /* لجعل الخلفية مستديرة */
}

.profile-text {
    color: white; /* لون النص */
    font-size: 16px; /* حجم النص */
    margin-top: 5px; /* إضافة مسافة بين الأيقونة والنص */
    display: block; /* جعل النص كتلة ليكون في سطر جديد */
}



        /* Sidebar design */
        .profile-sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f4f4f4;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            z-index: 9999;
        }

        .profile-sidebar table {
            width: 100%;
            padding: 10px;
            border-collapse: collapse;
        }

        .profile-sidebar table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        /* Close button for the sidebar */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 36px;
            cursor: pointer;
        }

        /* Sidebar active state */
        .profile-sidebar.active {
            width: 300px;
        }

        /* Settings icon */
        .settings-icon {
            margin-top: 20px;
            font-size: 20px;
            cursor: pointer;
        }

        /* Hidden section for user settings */
        .settings-section {
            display: none;
            padding: 10px;
        }

        .settings-section.active {
            display: block;
        }

        .error {
            color: red;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Profile icon -->
<!-- Profile icon container -->
<div class="profile-container">
    <div class="profile-icon" onclick="toggleSidebar()">
        <i class="fas fa-user"></i>
    </div>
    <span class="profile-text">Profile</span> <!-- نص "Profile" -->
</div>

<!-- Profile sidebar -->
<div id="profileSidebar" class="profile-sidebar">
    <span class="close-btn" onclick="toggleSidebar()">&times;</span>

    <?php if (isset($employee)) { ?>
    <table>
        <tr>
            <th>Employee ID</th>
            <td><?php echo $employee['employeeID']; ?></td>
        </tr>
        <tr>
            <th>First Name</th>
            <td><?php echo $employee['fName']; ?></td>
        </tr>
        <tr>
            <th>Last Name</th>
            <td><?php echo $employee['lName']; ?></td>
        </tr>
        <tr>
            <th>Role</th>
            <td><?php echo $employee['role']; ?></td>
        </tr>
        <tr>
            <th>Salary</th>
            <td><?php echo $employee['salary']; ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo $employee['email']; ?></td>
        </tr>
        <tr>
            <th>Date of Birth</th>
            <td><?php echo $employee['DOB']; ?></td>
        </tr>
        <tr>
            <th>Phone</th>
            <td><?php echo $employee['phone']; ?></td>
        </tr>
        <tr>
            <th>Hire Date</th>
            <td><?php echo $employee['hire_date']; ?></td>
        </tr>
    </table>

    <!-- Settings Icon -->
    <div class="settings-icon" onclick="toggleSettings()">
        <i class="fas fa-cog"></i> Settings
    </div>

    <!-- Settings Section -->
  <!-- Settings Section -->
<div id="settingsSection" class="settings-section">
    <h3>User Settings</h3>
    <p>
        <strong>Username:</strong> 
        <input type="text" id="usernameInput" value="<?php echo $user_info['name']; ?>" style="display:none;">
        <span id="usernameDisplay"><?php echo $user_info['name']; ?></span>
    </p>
    <p>
        <strong>Email:</strong> 
        <input type="email" id="emailInput" value="<?php echo $user_info['email']; ?>" style="display:none;">
        <span id="emailDisplay"><?php echo $user_info['email']; ?></span>
    </p>
    <p>
    <strong>Password:</strong>
    <input type="password" id="passwordInput" style="display:none;" placeholder="Enter new password">
    <span id="passwordDisplay">********</span> 
    <i id="togglePassword" class="fas fa-eye-slash" onclick="togglePasswordVisibility()" style="cursor:pointer;"></i>
</p>



    <button id="editButton" onclick="editSettings()">Edit</button>
    <button id="saveButton" onclick="saveSettings()" style="display:none;">Save</button>
    <button id="cancelButton" onclick="cancelSettings()" style="display:none;">Cancel</button>
</div>

<script>
    // Function to toggle the settings section
    function toggleSettings() {
        var settingsSection = document.getElementById("settingsSection");
        settingsSection.classList.toggle("active");
    }

    function editSettings() {
    // Show input fields and buttons
    document.getElementById("usernameInput").style.display = 'inline';
    document.getElementById("emailInput").style.display = 'inline';
    document.getElementById("passwordInput").style.display = 'inline';
    document.getElementById("usernameDisplay").style.display = 'none';
    document.getElementById("emailDisplay").style.display = 'none';
    document.getElementById("editButton").style.display = 'none';
    document.getElementById("saveButton").style.display = 'inline';
    document.getElementById("cancelButton").style.display = 'inline';

    // Show the password toggle icon when editing
    document.getElementById("togglePassword").style.display = 'inline';
    }

    function saveSettings() {
        var newUsername = document.getElementById("usernameInput").value;
        var newEmail = document.getElementById("emailInput").value;
        var newPassword = document.getElementById("passwordInput").value;

        // Here you would typically send the new username, email, and password to the server via AJAX or form submission.
        // For now, just update the displayed values (this should be replaced with an actual save mechanism).
        if (newUsername && newEmail) {
            document.getElementById("usernameDisplay").textContent = newUsername;
            document.getElementById("emailDisplay").textContent = newEmail;
            document.getElementById("passwordDisplay").textContent = '********'; // Don't show the actual password
            document.getElementById("usernameInput").style.display = 'none';
            document.getElementById("emailInput").style.display = 'none';
            document.getElementById("passwordInput").style.display = 'none';
            document.getElementById("editButton").style.display = 'inline';
            document.getElementById("saveButton").style.display = 'none';
            document.getElementById("cancelButton").style.display = 'none';
            alert('Settings saved successfully!'); // Replace with a proper success message
        } else {
            alert('Please fill in all fields.');
        }
    }

    function cancelSettings() {
    // Reset input fields and hide them
    document.getElementById("usernameInput").style.display = 'none';
    document.getElementById("emailInput").style.display = 'none';
    document.getElementById("passwordInput").style.display = 'none';
    document.getElementById("usernameDisplay").style.display = 'inline';
    document.getElementById("emailDisplay").style.display = 'inline';
    document.getElementById("editButton").style.display = 'inline';
    document.getElementById("saveButton").style.display = 'none';
    document.getElementById("cancelButton").style.display = 'none';

    // Hide the password toggle icon when canceling
    document.getElementById("togglePassword").style.display = 'none';
}

function togglePasswordVisibility() {
    var passwordInput = document.getElementById("passwordInput");
    var toggleIcon = document.getElementById("togglePassword");

    // Check if the password is currently hidden
    if (passwordInput.type === "password") {
        // Show the actual password entered by the user
        passwordInput.type = "text";
        toggleIcon.classList.remove("fa-eye-slash"); // Change to "show" icon
        toggleIcon.classList.add("fa-eye"); // Add "hide" icon
    } else {
        // Hide the password and show '********'
        passwordInput.type = "password";
        toggleIcon.classList.remove("fa-eye"); // Change to "hide" icon
        toggleIcon.classList.add("fa-eye-slash"); // Add "show" icon
    }
}


</script>


    <?php } else { ?>
    <div class="error"><?php echo $error_message; ?></div>
    <?php } ?>
</div>

<script>
    // Function to toggle the sidebar
    function toggleSidebar() {
        var sidebar = document.getElementById("profileSidebar");
        sidebar.classList.toggle("active");
    }

    // Function to toggle the settings section
    function toggleSettings() {
        var settingsSection = document.getElementById("settingsSection");
        settingsSection.classList.toggle("active");
    }
</script>

</body>
</html>

<?php // TEMPLATES
  include 'templates_html/end-main-content.html';
  include 'templates_html/footer.html';
?>
