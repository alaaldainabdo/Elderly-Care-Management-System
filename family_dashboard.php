<?php
session_start(); // بدء الجلسة في أعلى الصفحة

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // إعادة التوجيه إلى صفحة تسجيل الدخول إذا لم يكن المستخدم مسجل الدخول
    exit();
}

// بقية الكود...
include 'db_connection.php';
include 'header.php';

// تيمبلت
include 'templates_html/header.html';

// تيمبلت
include 'templates_html/header.html';

if (isset($_SESSION['user_role'])) {
    switch ($_SESSION['user_role']) {
        case 'admin':
            include 'main-nav-bar.php';
            include 'templates_html/main-grid-content-2columns.html';
            include 'templates_html/admin-side-bar.html';
            break;
        // إضافة أدوار المستخدمين الأخرى حسب الحاجة
        default:
            include 'templates_html/employee.php';
            include 'templates_html/alert-dashboard.php';
            include 'templates_html/main-grid-content-1column.html';
    }
} else {
    include 'templates_html/main-grid-content-1column.html';
}

include 'templates_html/main-content.html';

// جلب معرف المستخدم من الجلسة
$user_id = $_SESSION['user_id'];

// جلب التنبيهات الخاصة بالمستخدم الحالي من جدول emergency_alerts
$sql_alerts = "SELECT * FROM emergency_alerts WHERE sent_to = $user_id";
$result_alerts = $conn->query($sql_alerts);


// جلب بيانات كبار السن
$sql_profiles = "SELECT * FROM elderly_profiles WHERE created_by = $user_id";
$result_profiles = $conn->query($sql_profiles);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elderly Profiles Dashboard</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }
    header {
        background-color: #333;
        color: #fff;
        padding-top: 30px;
        min-height: 70px;
        border-bottom: #0779e4 3px solid;
    }
    header h1 {
        color: #fff; /* تأكيد أن لون الخط في العنوان أبيض */
    }
    header a {
        color: #fff;
        text-decoration: none;
        text-transform: uppercase;
        font-size: 16px;
    }
    header ul {
        padding: 0;
        list-style: none;
    }
    header ul li {
        display: inline;
        padding: 0 20px 0 20px;
    }
    .alert-banner.new-alert {
    background-color: #ff0000; /* اللون الأحمر إذا كان هناك تنبيه جديد */
    color: white;
}

.alert-banner.opened-alert {
    background-color: #007bff; /* اللون الأزرق عند فتح التنبيه */
    color: white;
}

    table {
        width: 100%;
        margin: 20px 0;
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    th, td {
        padding: 12px;
        text-align: left;
    }
    th {
        background-color: #333;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    /* استايل نموذج الإدخال */
    .form-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 20px 0;
    }
    .form-container input,
    .form-container select,
    .form-container textarea {
        width: 100%;
        padding: 10px;
        margin: 5px 0 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .form-container button {
        background-color: #0779e4;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .form-container button:hover {
        background-color: #055a94;
    }
</style>

</head>
<body>
    <header>
        <div class="container">
            <h1>Elderly Profiles Dashboard</h1>
        </div>
    </header>

    <div class="container">
        <hr>
        
        <?php
  // عرض إشعار التنبيهات الطارئة إذا كانت موجودة
if ($result_alerts->num_rows > 0) {
    echo "<div class='alert-banner new-alert' onclick='toggleAlerts()'>You have important emergency alerts!</div>";
    echo "<div id='alerts-details' style='display: none;'>";
    echo "<h3>Emergency Alerts</h3>";
    echo "<table>";
    echo "<tr><th>Alert Message</th><th>Date</th><th>Elderly Name</th></tr>";

    while ($row_alert = $result_alerts->fetch_assoc()) {
        // جلب اسم الشخص المسن
        $elderly_id = $row_alert['elderly_id'];
        $sql_elderly = "SELECT name FROM elderly_profiles WHERE id = $elderly_id";
        $result_elderly = $conn->query($sql_elderly);
        $elderly_name = ($result_elderly->num_rows > 0) ? $result_elderly->fetch_assoc()['name'] : 'Unknown';


        // تحديد اللون بناءً على حالة التنبيه
        $alert_class = ($row_alert['is_opened'] == 1) ? 'opened-alert' : 'new-alert'; // استخدام حالة التنبيه

        // تحديد اللون بناءً على حالة التنبيه
        //$alert_class = ($row_alert['is_opened'] == 'opened') ? 'opened-alert' : 'new-alert'; // استخدام حالة التنبيه

        echo "<tr class='$alert_class' onclick='toggleAlerts({$row_alert['id']})'>"; // إضافة معرف التنبيه لتمريره إلى دالة toggleAlerts
        echo "<td>" . $row_alert['alert_message'] . "</td>";
        echo "<td>" . $row_alert['alert_date'] . "</td>";
        echo "<td>" . $elderly_name . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>"; // نهاية div التنبيهات
} else {
    echo "<p>No emergency alerts found.</p>";
}

        // عرض بيانات كبار السن
        if ($result_profiles->num_rows > 0) {
            echo "<h3>Elderly Profiles</h3>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th><th>Date of Birth</th><th>Medical History</th><th>Emergency Contact</th><th>Phone</th><th>Health Condition</th><th>Current Medications</th><th>Additional Notes</th><th>Created At</th></tr>";
            
            while($row_profile = $result_profiles->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_profile["id"] . "</td>";
                echo "<td>" . $row_profile["name"] . "</td>";
                echo "<td>" . $row_profile["age"] . "</td>";
                echo "<td>" . $row_profile["medical_history"] . "</td>";
                echo "<td>" . $row_profile["emergency_contact"] . "</td>";
                echo "<td>" . $row_profile["phone"] . "</td>";
                echo "<td>" . $row_profile["health_condition"] . "</td>";
                echo "<td>" . $row_profile["current_medications"] . "</td>";
                echo "<td>" . $row_profile["additional_notes"] . "</td>";
                echo "<td>" . $row_profile["created_at"] . "</td>";
                echo "</tr>";

                // جلب مواعيد الأدوية لكل شخص مسن
                $profile_id = $row_profile["id"];
                $sql_medications = "SELECT * FROM medication_schedule WHERE elderly_id = $profile_id";
                $result_medications = $conn->query($sql_medications);

                if ($result_medications->num_rows > 0) {
                    echo "<tr><td colspan='10'>";
                    echo "<h3>Medication Schedule for " . $row_profile["name"] . "</h3>";
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Morning Medication</th><th>Lunch Medication</th><th>Dinner Medication</th></tr>";
                    
                    while($row_medication = $result_medications->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row_medication["id"] . "</td>";
                        echo "<td>" . $row_medication["after_morning"] . "</td>";
                        echo "<td>" . $row_medication["after_lunch"] . "</td>";
                        echo "<td>" . $row_medication["after_dinner"] . "</td>";
                        echo "</tr>";
                    }
                    
                    echo "</table>";
                    echo "</td></tr>";
                } else {
                    echo "<tr><td colspan='10'><p>No medication schedule available for " . $row_profile["name"] . ".</p></td></tr>";
                }

                // جلب السجل الصحي لكل شخص مسن
                $sql_health_records = "SELECT * FROM health_records WHERE elderly_id = $profile_id";
                $result_health_records = $conn->query($sql_health_records);

                if ($result_health_records->num_rows > 0) {
                    echo "<tr><td colspan='10'>";
                    echo "<h3>Health Records for " . $row_profile["name"] . "</h3>";
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Date</th><th>Notes</th><th>Health Condition</th><th>Prescriptions</th></tr>";
                
                    while($row_health = $result_health_records->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row_health["id"] . "</td>";
                        echo "<td>" . $row_health["date"] . "</td>"; 
                        echo "<td>" . $row_health["notes"] . "</td>"; // عرض
                        echo "<td>" . $row_health["health_condition"] . "</td>";
                        echo "<td>" . $row_health["prescriptions"] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                    echo "</td></tr>";
                } else {
                    echo "<tr><td colspan='10'><p>No health records available for " . $row_profile["name"] . ".</p></td></tr>";
                }
            }

            echo "</table>";
        } else {
            echo "<p>No profiles found.</p>";
        }

        $conn->close();
        ?>
    </div>

    <button class="W-50 btn btn-sm btn-info text-light mt-1 mb-1" type="button" onclick="window.location.href='logout.php';">Logout</button>

    <script>
 function toggleAlerts(alertId) {
    var alertsDetails = document.getElementById('alerts-details');
    var alertBanner = document.querySelector('.alert-banner');

    if (alertsDetails.style.display === 'none') {
        alertsDetails.style.display = 'block';
        alertBanner.classList.remove('new-alert');
        alertBanner.classList.add('opened-alert');

        // إرسال تحديث إلى قاعدة البيانات ليتم تسجيل أن التنبيه قد تم فتحه
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_alert_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
        // إضافة سجل الأخطاء
        console.log("Sending ID: " + alertId + ", is_opened: 1");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log(xhr.responseText); // طباعة استجابة الخادم
                } else {
                    console.error("Error: " + xhr.status); // طباعة الخطأ إذا حدث
                }
            }
        };

        xhr.send("id=" + alertId + "&is_opened=1"); // إرسال id والتنبيه كـ 1
        
    } else {
        alertsDetails.style.display = 'none';
    }
}




    </script>
</body>
</html>
<?php // TEMPLATES
  include 'templates_html/end-main-content.html';
  include 'templates_html/footer.html';
?>