<?php
// بدء الجلسة
session_start();

include 'db_connection.php';
include 'header.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // إعادة التوجيه إلى صفحة تسجيل الدخول إذا لم يكن المستخدم مسجل الدخول
    exit();
}

// جلب معرف المستخدم المسجل حالياً
$user_id = $_SESSION['user_id'];

// التحقق إذا كان المستخدم قد أنشأ ملفًا سابقاً
$sql_check = "SELECT * FROM elderly_profiles WHERE created_by = $user_id";
$result = $conn->query($sql_check);

// إذا كان المستخدم قد أدخل بياناته مسبقاً، إعادة توجيهه إلى صفحة أخرى
if ($result->num_rows > 0) {
    header("Location: family_dashboard.php"); // إعادة توجيه إلى لوحة التحكم أو أي صفحة أخرى
    exit();
}

// التحقق من إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // جلب البيانات من النموذج مع الحماية من SQL Injection
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $age = mysqli_real_escape_string($conn, $_POST['age']); // استخدام تاريخ الميلاد بدلاً من العمر
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $health_condition = mysqli_real_escape_string($conn, $_POST['health_condition']);
    $medical_history = mysqli_real_escape_string($conn, $_POST['medical_history']);
    $current_medications = mysqli_real_escape_string($conn, $_POST['current_medications']);
    $additional_notes = mysqli_real_escape_string($conn, $_POST['additional_notes']);
    $emergency_contact = mysqli_real_escape_string($conn, $_POST['emergency_contact']);

    // إعداد SQL لإدخال البيانات
    $sql = "INSERT INTO elderly_profiles 
        (name, age, gender, address, phone, health_condition, medical_history, current_medications, additional_notes, emergency_contact, created_by) 
        VALUES ('$full_name', '$age', '$gender', '$address', '$phone', '$health_condition', '$medical_history', '$current_medications', '$additional_notes', '$emergency_contact', $user_id)";


    // تنفيذ الاستعلام والتحقق
    if ($conn->query($sql) === TRUE) {
        // إخفاء النموذج بعد النجاح
        echo "<div style='color: green; text-align: center;'>New elderly profile created successfully!</div>";
        
        // إعادة التوجيه إلى صفحة أخرى بعد الإرسال الناجح
        header("Location: family_dashboard.php");
        exit();
    } else {
        echo "<div style='color: red; text-align: center;'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elderly Profile Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Elderly Profile Form</h2>
    <form action="" method="POST" id="one-time-form">
        <label for="full_name">Elder Full Name:</label>
        <input type="text" id="full_name" name="full_name" required>

        <label for="age">DOB</label>
        <input type="date" id="age" name="age" required>
<br><br>

        <label for="gender">gender:</label>
        <select id="gender" name="gender" required>
            <option value="" disabled selected> select_gender</option>
            <option value="male">male</option>
            <option value="female">female</option>
        </select>
<br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required>

        <label for="health_condition">Health Condition:</label>
        <textarea id="health_condition" name="health_condition"></textarea>

        <label for="medical_history">Medical History:</label>
        <textarea id="medical_history" name="medical_history"></textarea>

        <label for="current_medications">Current Medications:</label>
        <textarea id="current_medications" name="current_medications"></textarea>

        <label for="additional_notes">Additional Notes:</label>
        <textarea id="additional_notes" name="additional_notes"></textarea>

        <label for="emergency_contact">Emergency Contact number:</label>
        <input type="text" id="emergency_contact" name="emergency_contact" required><br>
<p>Make sure you enter the WhatsApp number with the country code correctly without entering the symbols (000, +):</p><br><br>
        <input type="submit" value="Submit">
    </form>
</div>

</body>
</html>
