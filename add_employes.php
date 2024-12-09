<?php
// الاتصال بقاعدة البيانات
include 'db_connection.php';
include 'header.php';

// متغير لتخزين رسالة النجاح أو الخطأ
$message = '';

// التحقق من إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التحقق من تعبئة جميع الحقول
    if (!empty($_POST['fName']) && !empty($_POST['lName']) && !empty($_POST['salary']) && !empty($_POST['user_id']) && !empty($_POST['hire_date'])) {
        $fName = $_POST['fName'];
        $lName = $_POST['lName'];
        $salary = $_POST['salary'];
        $DOB = $_POST['DOB'];
        $phone = $_POST['phone'];
        $user_id = $_POST['user_id'];

        // جلب الدور من جدول المستخدمين بناءً على معرف المستخدم
        $sql = "SELECT role, email FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $role = $user['role']; // الدور من جدول المستخدمين
            $email = $user['email']; // تعبئة الإيميل تلقائيًا من جدول المستخدمين

            // تحقق إذا كان للمستخدم سجل موظف بالفعل
            $check_sql = "SELECT employeeID FROM employees WHERE user_id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("i", $user_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $check_stmt->close();

            if ($check_result->num_rows > 0) {
                $message = "<div class='alert error' id='message'>المستخدم لديه بالفعل سجل موظف.</div>";
            } else {
                // إدخال الموظف في جدول الموظفين
                $sql = "INSERT INTO employees (fName, lName, role, salary, email, DOB, phone, hire_date, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssssi", $fName, $lName, $role, $salary, $email, $DOB, $phone, $_POST['hire_date'], $user_id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    $message = "<div class='alert success' id='message'>Employee added successfully</div>";
                } else {
                    $message = "<div class='alert error' id='message'>Error: " . $conn->error . "</div>";
                }

                $stmt->close();
            }
        } else {
            $message = "<div class='alert error' id='message'>User not found!!</div>";
        }
    } else {
        $message = "<div class='alert error' id='message'>Please fill in all fields!!</div>";
    }

    // إعادة التوجيه بعد عرض الرسالة (بغض النظر عن النتيجة)
    header("Refresh: 1; url=".$_SERVER['PHP_SELF']);
}

// جلب المستخدمين الذين لديهم أدوار Doctor, Caregiver, Supervisor, admin
$sql = "SELECT id, name FROM users WHERE role IN ('Doctor', 'Caregiver', 'Supervisor', 'admin')";
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

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add_New_Employee</title>
    <link rel="stylesheet" href="style.css"> <!-- إضافة ملف CSS هنا -->
    <style>
       body {
        font-family: Arial, sans-serif;
        margin: 40px;
        background-color: #f4f4f4;
    }

        h1 {
            margin-bottom: 20px;
            text-align: center;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 400px; /* Increased the width */
            max-width: 90%;
            margin: auto;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        input[type="email"],
        button {
            width: 95%; /* Slightly increased width for better centering */
            padding: 12px;
            margin: 10px auto;
            display: block;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box; /* Ensures padding is included in the width */
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        label {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px; /* Reduced margin */
            display: block; /* Ensure labels are block elements for better spacing */
        }

        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .primary-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            border-radius: 4px;
        }

        .primary-btn:hover {
            background-color: #0056b3;
        }

        .link-btn {
            display: inline-block;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 12px;
            font-size: 18px;
            text-align: center;
            border-radius: 4px;
            width: 100%; /* Make the link button full width */
            margin-top: 8px;
        }

        .link-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>New_Employee</h1>
        <hr>
        <?php if ($message): ?>
            <?php echo $message; ?>
            <script>
                // إخفاء الرسالة بعد 7 ثوانٍ
                setTimeout(function() {
                    var message = document.getElementById('message');
                    if (message) {
                        message.style.display = 'none';
                    }
                }, 7000);
            </script>
        <?php endif; ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="user_id">Select_User:</label>
            <select name="user_id" id="user_id" required>
                <option value="">----------</option>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No users available!!</option>";
                }
                ?>
            </select>

            <label for="fName">fName:</label>
            <input type="text" name="fName" placeholder="fName" required>

            <label for="lName">lName:</label>
            <input type="text" name="lName" placeholder="lName" required>

            <label for="salary">Salary:</label>
            <input type="number" name="salary" placeholder="salary" required>

            <label for="DOB">DOB:</label>
            <input type="date" name="DOB" required>

            <label for="phone">Contact_NO:</label>
            <input type="text" name="phone" placeholder=" Contact_NO" required>

            <label for="email"> Email_Address:</label>
            <input type="email" id="email" name="email" placeholder="Email_Address " readonly required>

            <label for="hire_date"> Hire_date:</label>
            <input type="date" name="hire_date" required><br>
            <hr>

            <button type="submit" class="primary-btn">Add_New_Employee</button>

            <a href="taske_dashboard.php" class="link-btn">Go to Control Panel</a>

        </form>

        <script>
            // عند تغيير المستخدم المختار، تعبئة البريد الإلكتروني تلقائيًا
            document.getElementById('user_id').addEventListener('change', function () {
                var userId = this.value;

                if (userId) {
                    // إجراء طلب لجلب البريد الإلكتروني من الخادم
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', 'get_email.php?user_id=' + userId, true);
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            document.getElementById('email').value = xhr.responseText;
                        }
                    };
                    xhr.send();
                } else {
                    document.getElementById('email').value = '';
                }
            });
        </script>

    </div>
</body>
</html>
<?php // TEMPLATES
  include 'templates_html/end-main-content.html';
  include 'templates_html/footer.html';
?>
