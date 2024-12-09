<?php
// الاتصال بقاعدة البيانات
include 'db_connection.php';
include 'header.php';

// التحقق من إرسال الفورم
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التحقق من تعبئة جميع الحقول
    if (!empty($_POST['description']) && !empty($_POST['status']) && !empty($_POST['due_date']) && !empty($_POST['employee_id'])) {
        $description = $_POST['description'];
        $status = $_POST['status'];
        $due_date = $_POST['due_date'];
        $employee_id = $_POST['employee_id']; // الاسم لا يزال employee_id

        // التحقق من أن المستخدم موجود
        $sql = "SELECT employeeID FROM employees WHERE employeeID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            // استعلام الإدخال
            $sql = "INSERT INTO tasks (description, status, due_date, employee_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $description, $status, $due_date, $employee_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "تم إضافة المهمة بنجاح";
            } else {
                echo "خطأ: " . $conn->error;
            }

            $stmt->close();
        } else {
            echo "المستخدم غير موجود";
        }
    } else {
        echo "يرجى ملء جميع الحقول";
    }
}

// جلب الموظفين
$sql = "SELECT employeeID, fName, lName FROM employees"; // تعديل الاستعلام لجلب employeeID، fName وlName
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مهمة</title>
    <link rel="stylesheet" href="style.css"> <!-- إضافة ملف CSS هنا -->
    <link rel="stylesheet" href="styleall.css">
</head>
<body>
    <h1>إضافة مهمة</h1>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="text" name="description" placeholder="وصف المهمة" required>
        
        <select name="status" required>
            <option value="pending">معلق</option>
            <option value="completed">مكتمل</option>
        </select>
        
        <input type="date" name="due_date" required>
        
        <select name="employee_id" required>
            <option value="">اختر المستخدم</option>
            <?php
            // عرض الموظفين في القائمة المنسدلة
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // دمج الاسم الأول والاسم الأخير لعرضه في القائمة المنسدلة
                    $fullName = $row['fName'] . ' ' . $row['lName'];
                    echo "<option value='" . $row['employeeID'] . "'>" . $fullName . "</option>";
                }
            } else {
                echo "<option value=''>لا يوجد مستخدمين متاحين</option>";
            }
            ?>
        </select>
        
        <button type="submit">إضافة مهمة</button>
    </form>
    
    <a href="dashboard.php">العودة إلى لوحة التحكم</a>
</body>
</html>
