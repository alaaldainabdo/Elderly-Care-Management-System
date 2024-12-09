<?php
session_start();
include 'db_connection.php';
include 'header.php';

// TEMPLATES
include 'templates_html/header.html';

switch ($_SESSION['user_role']) {
    case '1':
        include 'main-nav-bar.php';
        include 'templates_html/main-grid-content-2columns.html';
        include 'templates_html/admin-side-bar.html';
        break;
    case '2':
        include 'main-nav-bar.php';
        include 'templates_html/main-grid-content-2columns.html';
        include 'templates_html/supervisor-side-bar.html';
        break;
    case '3':
        include 'main-nav-bar.php';
        include 'templates_html/main-grid-content-2columns.html';
        include 'templates_html/doctor-side-bar.html';
        break;
    case '4':
        include 'main-nav-bar.php';
        include 'templates_html/main-grid-content-2columns.html';
        include 'templates_html/caregiver-side-bar.html';
        break;
    case '5':
        include 'main-nav-bar.php';
        include 'templates_html/main-grid-content-2columns.html';
        include 'templates_html/patient-side-bar.html';
        break;
    case '6':
        include 'main-nav-bar.php';
        include 'templates_html/main-grid-content-2columns.html';
        include 'templates_html/familyMember-side-bar.html';
        break;
    default:
        include 'templates_html/alert-dashboard.html';
        include 'templates_html/home-nav-bara.html';
        include 'templates_html/main-grid-content-1column.html';
}

include 'templates_html/main-content.html';

// Fetch elderly profiles from the database for the dropdown
$result = $conn->query(query: "SELECT id, name FROM elderly_profiles");

// متغيرات لتخزين الرسائل
$errorMessage = "";
$successMessage = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $health_condition = $_POST['health_condition'];
    $medical_history = $_POST['medical_history'];
    $current_medications = $_POST['current_medications'];
    $additional_notes = $_POST['additional_notes'];

    // استعلام SQL لإدراج البيانات
    $sql = "INSERT INTO elderly_profiles (full_name, age, gender, address, phone, health_condition, medical_history, current_medications, additional_notes)
            VALUES ('$full_name', '$age', '$gender', '$address', '$phone', '$health_condition', '$medical_history', '$current_medications', '$additional_notes')";

    if (mysqli_query($conn, $sql)) {
        echo "تم إضافة بيانات كبير السن بنجاح!";
    } else {
        echo "خطأ: " . $sql . "<br>" . mysqli_error($conn);
    }

    // إغلاق الاتصال بقاعدة البيانات
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة ملف كبير السن</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styleall.css">
</head>
<body>
    <h1>إضافة ملف كبير السن</h1>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <input type="text" name="name" placeholder="الاسم" required>
        <input type="number" name="age" placeholder="العمر" required>
        <textarea name="medical_history" placeholder="التاريخ الطبي"></textarea>
        <input type="text" name="emergency_contact" placeholder="جهة الاتصال الطارئة" required>
        <button type="submit">إضافة ملف كبير السن</button>
    </form>
    
    <a href="dashboard.php">العودة إلى لوحة التحكم</a>
</body>
</html>