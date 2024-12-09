<?php
session_start(); // Start the session

// Check if there is a message in the URL and store it in the session
if (isset($_GET['message'])) {
    $_SESSION['message'] = htmlspecialchars($_GET['message']);
    $_SESSION['message_type'] = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'success'; // default to 'success' if no type is provided

    // Redirect to remove the message from the URL
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit; // Stop script execution after redirect
}

// Initialize message variables
$message = null;
$message_type = null;

// Check if there is a message in the session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];

    // Clear the message and type from the session after displaying
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Include necessary files
include 'db_connection.php';
include 'header.php';
?>



<?php


// جلب البيانات من جدول elderly_profiles مع جدول health_records وجدول users لجلب اسم المستخدم الذي قام بالتحديث ودوره
$sql = "SELECT e.*, h.vital_signs, h.health_condition, h.prescriptions, h.notes, h.date, 
               u.name AS updated_by_name, u.role AS updated_by_role, 
               f.name AS family_member_name
        FROM elderly_profiles e
        LEFT JOIN health_records h ON e.id = h.elderly_id
        LEFT JOIN users u ON h.updated_by = u.id
        LEFT JOIN users f ON e.created_by = f.id AND f.role = 'family'";


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
                    include 'templates_html/employee.php';
                    include 'templates_html/main-grid-content-1column.html';
            }
        } else {
            include 'templates_html/main-grid-content-1column.html';
        }
        
    
    
        include 'templates_html/main-content.html';
    ?>



<script>
// جلب اسم المستخدم من المتغير المرسل بواسطة PHP
let username = "<?php echo $username; ?>";

// تحديث اسم المستخدم في الرسالة
document.getElementById("username").innerText = username;
</script> 






<!DOCTYPE html>
<html lang="ar">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency alert-dashboard </title>
    <!-- تضمين ملفات CSS الخاصة بك -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styleall.css">

 <!-- تضمين مكتبات Bootstrap وjQuery -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- إضافة مكتبة Font Awesome -->



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

</head>

<style>
        
/* تنسيق عام */
body {
    font-family: 'Cairo', sans-serif;
    background-color: #f4f4f9;
    color: #333;
}

/* تنسيق العنوان */
h1 {
    text-align: center;
    color: #4A90E2;
    margin-bottom: 20px;
}

/* تنسيق شريط البحث */
.search-container {
    text-align: center;
    margin-bottom: 20px;
}

.search-container input, .search-container select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 200px;
    margin-right: 10px;
}

.search-container button {
    background-color: #4A90E2;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.search-container button:hover {
    background-color: #357ABD;
}

.refresh-button {
    position: absolute; /* لجعل الزر متحركاً داخل الحاوية */
    left: 20px; /* تعديل هذا الرقم حسب المسافة التي تريدها من الحافة اليسرى */
    top: 20px; /* تعديل المسافة من الحافة العلوية إذا أردت تحريكه رأسياً */
    padding: 10px 20px; /* حجم الزر */
    background-color: #4CAF50; /* لون خلفية الزر */
    color: white; /* لون النص */
    border: none; /* إزالة الحدود */
    border-radius: 5px; /* جعل الزر ذو حواف مستديرة */
    cursor: pointer; /* إظهار اليد عند مرور الفأرة */
    
}

.refresh-button:hover {
    background-color: #45a049; /* لون عند مرور الفأرة */
}


/* تنسيق الجدول */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: center;
    vertical-align: middle;
}

th {
    background-color: #4A90E2;
    color: white;
}

td {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

/* تنسيق الأزرار في الجدول */
table button, table a {
    padding: 8px 12px;
    background-color: #4A90E2;
    color: white;
    border: none;
    border-radius: 3px;
    text-decoration: none;
    cursor: pointer;
}

table button:hover, table a:hover {
    background-color: #357ABD;
}

/* نافذة منبثقة */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 60%;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.3s;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover, .close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

/* تأثيرات الرسوم المتحركة */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* تأثير الاختفاء التدريجي */
.fade-out {
    transition: opacity 1s ease-in-out;
}

.fade-out-hidden {
    opacity: 0;
}
.message {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 10px 20px;
    border-radius: 5px;
    z-index: 1000; /* تأكد من أن الرسالة تظهر فوق المحتوى الآخر */
    color: #fff;
}

.message.success {
    background-color: #4CAF50; /* لون أخضر للنجاح */
    top: 50%; /* يجعل الرسالة في منتصف الصفحة عموديًا */
    left: 50%; /* يجعل الرسالة في منتصف الصفحة أفقيًا */
    transform: translate(-50%, -50%); /* لضبط المحاذاة في المنتصف بالضبط */
    color: white; /* لون النص */
    padding: 20px; /* حجم المساحة حول النص */
    border-radius: 10px; /* حواف مستديرة */
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* إضافة ظل خفيف */
    font-size: 18px; /* حجم النص */
    display: none; /* إخفاء الرسالة افتراضيًا */
    z-index: 9999; /* لضمان أنها تكون فوق باقي المحتويات */

}
.center-heading {
    text-align: center; /* لتوسيط النص أفقياً */
    margin-top: 20px; /* إضافة مسافة من الأعلى (اختياري) */
}

.message.error {
    background-color: #f44336; /* لون أحمر للخطأ */
    top: 50%; /* يجعل الرسالة في منتصف الصفحة عموديًا */
    left: 50%; /* يجعل الرسالة في منتصف الصفحة أفقيًا */
    transform: translate(-50%, -50%); /* لضبط المحاذاة في المنتصف بالضبط */
    color: white; /* لون النص */
    padding: 20px; /* حجم المساحة حول النص */
    border-radius: 10px; /* حواف مستديرة */
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* إضافة ظل خفيف */
    font-size: 18px; /* حجم النص */
    display: none; /* إخفاء الرسالة افتراضيًا */
    z-index: 9999; /* لضمان أنها تكون فوق باقي المحتويات */

}



.modal-text {
    text-align: center; /* محاذاة النص في المنتصف */
    margin-top: 20px; /* إضافة مسافة من الأعلى لتوسيط النص رأسياً */
    font-size: 18px; /* حجم خط مناسب */
}


</style>
<body>
    <button id="refresh-button" onclick="location.reload();">Update</button>


    <h1>Health_Records and Emergency Alert-Dashboard</h1><br>
    <!-- Filters -->
    <div class="filters">
    <div class="filter-column">
        <label for="search">Search:</label>
        <input type="text" id="search" placeholder="Search for text...">
    </div>
    
    <div class="filter-column">
        <label for="updated_by_filter">Updated_by:</label>
        <input type="text" id="updated_by_filter" placeholder=" Updated_by.....">
    </div>

    <div class="filter-column">
        <label for="date_filter">Date_filter:</label>
        <input type="date" id="date_filter">
    </div>

</div>





   <!-- Table to show elderly information -->
<table>
<thead>
    <tr>
        <th>ID</th>
        <th>Elderly_Name</th>
        <th>Emergency_contact</th>
        <th>Vital_signs</th>
        <th>Health_condition</th>
        <th>Prescriptions</th>
        <th>Notes</th>
        <th>Update date</th>
        <th>Updated_by</th>
        <th> User Role </th>
        <th>Family_Member_Name</th> <!-- إضافة حقل لاسم عضو العائلة -->
        <th>Procedures</th>
    </tr>
</thead>
    <tbody>
    <?php
        if ($result->num_rows > 0) {
            $serial_number = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $serial_number++ . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['emergency_contact'] . "</td>";
                echo "<td>" . $row['vital_signs'] . "</td>";
                echo "<td>" . $row['health_condition'] . "</td>";
                echo "<td>" . $row['prescriptions'] . "</td>";
                echo "<td>" . $row['notes'] . "</td>";
                echo "<td>" . $row['date'] . "</td>";
                echo "<td>" . $row['updated_by_name'] . "</td>";
                echo "<td>" . $row['updated_by_role'] . "</td>";
                echo "<td>" . $row['family_member_name'] . "</td>"; //<!-- عرض اسم عضو العائلة -->
                echo "<td>";
                echo "<button onclick='showAlerts(" . $row['id'] . ")'>ShowAlerts</button> ";
               // echo "<a href='edit_elderly.php?id=" . $row['id'] . "'>تعديل</a> | ";
                //echo "<a href='delete_elderly.php?id=" . $row['id'] . "' onclick='return confirm(\"هل أنت متأكد من الحذف؟\");'>حذف</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='12'>لا توجد بيانات لعرضها.</td></tr>";// <!-- تعديل العدد ليشمل الحقل الجديد -->
        }
    ?>



 </tbody>

</table>

<!-- Modal for showing alerts 
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">رسائل التنبيهات</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>رقم التنبيه</th>
                            <th>رسالة التنبيه</th>
                            <th>تاريخ ووقت التنبيه</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="alertList">
                        !-- سيتم ملء هذا القسم بواسطة AJAX --
                    </tbody>
                </table>
                <button id="addNewAlertBtn" onclick="openNewAlertModal()">إضافة تنبيه جديد</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>-->

<script>
    // Function to filter the table based on search inputs
function filterTable() {
    const searchInput = document.getElementById("search").value.toLowerCase();
    const updatedByFilter = document.getElementById("updated_by_filter").value.toLowerCase();
    const dateFilter = document.getElementById("date_filter").value;
    const table = document.querySelector("table tbody");
    const rows = table.getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
        const name = rows[i].getElementsByTagName("td")[1].innerText.toLowerCase(); // اسم كبير السن
        const updatedByName = rows[i].getElementsByTagName("td")[8].innerText.toLowerCase(); // اسم المستخدم الذي قام بالتحديث
        const date = rows[i].getElementsByTagName("td")[7].innerText; // تاريخ التحديث

        let showRow = true;

        // Search across all fields
        if (searchInput && !name.includes(searchInput) && !updatedByName.includes(searchInput)) {
            showRow = false;
        }

        // Filter by updated_by_filter
        if (updatedByFilter && !updatedByName.includes(updatedByFilter)) {
            showRow = false;
        }

        // Filter by date
        if (dateFilter && date !== dateFilter) {
            showRow = false;
        }

        // Show or hide row based on the filters
        rows[i].style.display = showRow ? "" : "none";
    }
}

// Attach event listeners for input fields to trigger the filter function
document.getElementById("search").addEventListener("input", filterTable);
document.getElementById("updated_by_filter").addEventListener("input", filterTable);
document.getElementById("date_filter").addEventListener("change", filterTable);

</script>
 
 <!-- زر لفتح نافذة عرض التنبيهات -->
 <!-- <button id="openAlertModalBtn" onclick="showAlerts(1)">عرض التنبيهات</button> مثال: عرض التنبيهات لكبير السن رقم 1 -->

<!-- نافذة منبثقة لعرض التنبيهات -->
<div id="alertModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 class="center-heading">Alert_Messages</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Alert_Messages</th>
                    <th>Alert_date_and_time</th>
                    <th>Procedures</th>
                </tr>
            </thead>
            <tbody id="alertList">
                <!-- سيتم ملء هذا القسم بواسطة AJAX -->
            </tbody>
        </table>
        <button id="addAlertBtn" onclick="openNewAlertModal()">Add new alert</button><br>
        <button type="button" class="btn btn-primary" onclick="closeModal()">Cancel</button>
    </div>
</div>


<!-- موديل تأكيد الحذف -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeDeleteModal()">&times;</span> <!-- زر X -->
        <p class="modal-text">Are you sure you want to delete this alert?</p>
        <button id="confirmDeleteBtn" class="action-button">Yes</button><br>
        <button class="action-button" id="cancelDeleteBtn" onclick="closeDeleteModal()">Cancel</button> <!-- زر إلغاء -->
    </div>
</div>

<script>
    // المتغيرات للموديل
    let deleteModal = document.getElementById('deleteModal');
    let confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    let deleteId = ''; // متغير لتخزين معرف التنبيه للحذف

    // دالة لفتح الموديل وتعيين معرف التنبيه
    function openDeleteModal(id) {
        deleteId = id; // تخزين معرف التنبيه
        deleteModal.style.display = 'block'; // إظهار الموديل
    }

    // دالة لإغلاق الموديل
    function closeDeleteModal() {
        deleteModal.style.display = "none"; // إغلاق موديل الحذف
    }

    // دالة لإظهار رسالة النجاح أو الخطأ
    function showMessage(message, type) {
        const messageBox = document.createElement('div');
        messageBox.className = `message ${type}`; // أضف فئة خاصة بالرسالة
        messageBox.textContent = message;
        document.body.appendChild(messageBox);

        // إزالة الرسالة بعد 3 ثوانٍ
        setTimeout(function() {
            messageBox.classList.add("fade-out-hidden");
            setTimeout(function() {
                messageBox.style.display = "none";
            }, 1000); // انتظر حتى تكتمل الانتقال
        }, 3000);
    }

    // تنفيذ الحذف عند الضغط على زر تأكيد الحذف
    confirmDeleteBtn.onclick = function() {
        // إرسال طلب حذف عبر AJAX
        fetch('delete_alert.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: deleteId })
        })
        .then(response => response.json())
        .then(data => {
            // تحقق من حالة الحذف
            if (data.status === "success") {
                showMessage(data.message, "success"); // إظهار رسالة النجاح
                location.reload(); // إعادة تحميل الصفحة لتحديث البيانات
            } else {
                showMessage(data.message, "error"); // إظهار رسالة الخطأ
            }
        })
        .catch(error => console.error('Error deleting alert:', error))
        .finally(() => closeDeleteModal()); // إغلاق موديل الحذف بعد تنفيذ الطلب
    };
</script>




<!-- نافذة منبثقة لإضافة تنبيه جديد -->
<div id="newAlertModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeNewAlertModal()">&times;</span>
        <h2 class="center-heading">Add new Alert for Elder.</h2>
        <form id="alertForm" action="add_alert.php" method="POST">
            <input type="hidden" name="elderly_id" id="elderly_id">
            <label for="alert_message">alert_message:</label>
            <textarea name="alert_message" id="alert_message" placeholder="alert_message " required></textarea>
            <label for="alert_date"> alert_date :</label>
            <input type="datetime-local" name="alert_date" id="alert_date" required><br><br>

            <button type="submit">Add_Alert</button>
            <button type="button" onclick="closeNewAlertModal()">Cancel</button>
        </form>
    </div>
</div>


<script>
    // Function to open the modal for displaying alerts
// Function to open the modal for displaying alerts
function showAlerts(elderlyId) {
    document.getElementById('elderly_id').value = elderlyId;
    const alertModal = document.getElementById("alertModal");
    const alertList = document.getElementById("alertList");
    alertList.innerHTML = ''; // مسح محتوى القائمة

    // Fetch alerts using AJAX
    fetch('get_alerts.php?elderly_id=' + elderlyId)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach((alert, index) => {
                    alertList.innerHTML += `
                        <tr>
                            <td>${index + 1}</td> <!-- رقم التنبيه -->
                            <td>${alert.alert_message}</td> <!-- رسالة التنبيه -->
                            <td>${alert.alert_date}</td> <!-- تاريخ ووقت التنبيه -->
                            <td>
                                <button onclick="resendAlert(${alert.id})">Resend_Alert</button>
                                <a href='#' onclick="openDeleteModal(${alert.id})"><i class='fas fa-trash'></i> Delet</a>
                            </td>
                        </tr>
                    `;
                });
            } else {
                alertList.innerHTML = '<tr><td colspan="4">There is No Emergency Aler...</td></tr>';
            }
            alertModal.style.display = "block"; // عرض النافذة المنبثقة
        })
        .catch(error => console.error('Error fetching alerts:', error));
}



    // Function to close the alert modal
    function closeModal() {
        document.getElementById("alertModal").style.display = "none";
    }

    // Function to open the modal for adding a new alert
    function openNewAlertModal() {
        document.getElementById("newAlertModal").style.display = "block";
    }

    // Function to close the modal for adding a new alert
    function closeNewAlertModal() {
        document.getElementById("newAlertModal").style.display = "none";
    }

        /*// إضافة التنبيه الجديد باستخدام AJAX
        document.getElementById("alertForm").addEventListener("submit", function(event) {
            event.preventDefault(); // منع إعادة تحميل الصفحة
            const elderlyId = document.getElementById("elderly_id").value;
            const alertMessage = document.getElementById("alert_message").value;
            const alertDate = document.getElementById("alert_date").value;

            // إرسال البيانات باستخدام AJAX
            fetch('add_alert.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    elderly_id: elderlyId,
                    alert_message: alertMessage,
                    alert_date: alertDate
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("تم إضافة التنبيه بنجاح");
                    closeNewAlertModal(); // إغلاق النافذة المنبثقة
                    showAlerts(elderlyId); // تحديث قائمة التنبيهات
                } else {
                    alert("حدث خطأ أثناء إضافة التنبيه");
                }
            })
            .catch(error => console.error('Error adding alert:', error));
        });*/

      
        // إعادة إرسال التنبيه باستخدام AJAX
      // دالة لإعادة إرسال التنبيه
function resendAlert(alertId) {
    // جلب التنبيه بناءً على ID
    fetch('get_alert_by_id.php?id=' + alertId)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch alert');
            }
            return response.json();
        })
        .then(alert => {
            // جلب رقم الاتصال من جدول elderly_profiles باستخدام alert.elderly_id
            return fetch('get_elderly_contact.php?id=' + alert.elderly_id)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch contact');
                    }
                    return response.json();
                })
                .then(contact => {
                    // التأكد من وجود رقم الاتصال
                    if (contact && contact.emergency_contact) {
                        // إعداد رابط واتساب
                        const whatsappLink = `https://web.whatsapp.com/send?phone=${contact.emergency_contact}&text=${encodeURIComponent("🛑 Emergency alert!!! : " + alert.alert_message)}`;
                        // إعادة توجيه إلى رابط واتساب ويب
                        window.open(whatsappLink, '_blank');
                    } else {
                        console.error('Contact number not available!!!');
                        alert('Contact number not available!!!');
                    }
                });
        })
        .catch(error => {
            console.error('Error retrieving alert or contact:', error);
            alert('Error retrieving alert or contact: ' + error.message);
        });
}


        // إغلاق النوافذ المنبثقة عند النقر خارج النافذة
        window.onclick = function(event) {
            const alertModal = document.getElementById("alertModal");
            const newAlertModal = document.getElementById("newAlertModal");
            if (event.target === alertModal) {
                alertModal.style.display = "none";
            }
            if (event.target === newAlertModal) {
                newAlertModal.style.display = "none";
            }
        }
    </script>
</body>
</html>
<?php // TEMPLATES
  include 'templates_html/end-main-content.html';
  include 'templates_html/footer.html';
?>

