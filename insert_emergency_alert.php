<?php
ob_start(); // Start output buffering
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

// التحقق من طلب POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // جلب القيم من النموذج
    $elderly_id = $_POST['elderly_id'];
    $alert_message = $_POST['alert_message'];
    $alert_date = $_POST['alert_date'];
    $sent_to = $_POST['sent_to'];

    // استعلام الإدخال
    $sql_insert = "INSERT INTO emergency_alerts (elderly_id, alert_message, alert_date, sent_to)
                   VALUES ('$elderly_id', '$alert_message', '$alert_date', '$sent_to')";
    
    if ($conn->query($sql_insert) === TRUE) {
        echo "تم إضافة التنبيه الطارئ بنجاح!";

        // استعلام لجلب رقم الهاتف من جدول elderly_profiles
        $sql_contact = "SELECT emergency_contact FROM elderly_profiles WHERE id = '$elderly_id'";
        $result_contact = $conn->query($sql_contact);
        $row_contact = $result_contact->fetch_assoc();
            
        // إعداد رقم الاتصال الطارئ
        $to_contact = $row_contact['emergency_contact']; // رقم الاتصال المراد إرسال الإشعار إليه
            
        // إنشاء رابط واتساب
        $whatsapp_link = "https://wa.me/$to_contact?text=" . urlencode("تنبيه طارئ: $alert_message");
            
        // إعادة توجيه المستخدم إلى رابط واتساب
        header("Location: $whatsapp_link");
        exit();
    } else {
        echo "حدث خطأ: " . $conn->error;
    }
}

// جلب قائمة كبار السن من جدول elderly_profiles
$sql_elderly = "SELECT id, name, created_by FROM elderly_profiles"; // إضافة created_by
$result_elderly = $conn->query($sql_elderly);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة تنبيه طارئ</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styleall.css">
</head>
<body>
    <h1>إضافة تنبيه طارئ</h1>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <!-- قائمة منسدلة لاختيار كبير السن -->
        <label for="elderly_id">اختر اسم كبير السن:</label>
        <select name="elderly_id" id="elderly_id" required onchange="updateSentTo(this.value)">
            <option value="">اختر اسم كبير السن</option>
            <?php
            if ($result_elderly->num_rows > 0) {
                while($row_elderly = $result_elderly->fetch_assoc()) {
                    echo "<option value='" . $row_elderly['id'] . "' data-created-by='" . $row_elderly['created_by'] . "'>" . $row_elderly['name'] . "</option>";
                }
            } else {
                echo "<option value=''>لا يوجد كبار السن</option>";
            }
            ?>
        </select>

        <!-- حقل رسالة التنبيه -->
        <label for="alert_message">رسالة التنبيه:</label>
        <textarea name="alert_message" placeholder="رسالة التنبيه" required></textarea>

        <!-- حقل تاريخ ووقت التنبيه -->
        <label for="alert_date">تاريخ ووقت التنبيه:</label>
        <input type="datetime-local" name="alert_date" required>

        <!-- حقل مخفي للمستخدم المرسل إليه -->
        <input type="hidden" name="sent_to" id="sent_to" required>

        <!-- زر الإرسال -->
        <button type="submit">إضافة تنبيه طارئ</button>
    </form>
    
    <a href="dashboard.php">العودة إلى لوحة التحكم</a>

    <script>
        function updateSentTo(elderlyId) {
            const selectedOption = document.querySelector(`option[value='${elderlyId}']`);
            const createdBy = selectedOption.getAttribute('data-created-by');
            document.getElementById('sent_to').value = createdBy; // تعيين قيمة created_by في الحقل المخفي
        }
    </script>
</body>
</html>

<?php
// إغلاق الاتصال بقاعدة البيانات
$conn->close();
ob_end_flush(); // End output buffering and flush output

?>

<?php // TEMPLATES
include 'templates_html/end-main-content.html';
include 'templates_html/footer.html';
?>
