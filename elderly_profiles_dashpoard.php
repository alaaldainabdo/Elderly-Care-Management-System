<?php
session_start();

// Display message if it exists in the URL
if (isset($_GET['message'])) {
    $_SESSION['message'] = htmlspecialchars($_GET['message']);
    
    // بعد تخزين الرسالة، نعيد توجيه المستخدم لإزالة المتغير من URL
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit; // إنهاء التنفيذ بعد إعادة التوجيه
} 

// Check if there is a message in the session
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;

// Clear the message after displaying it
if ($message) {
    unset($_SESSION['message']); // Clear the message for the next load
}


include 'db_connection.php';
include 'header.php';


// TEMPLATES
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



// Fetch elderly profiles from the database
$sql = "SELECT id, name, age, medical_history, emergency_contact, full_name, gender, address, phone, health_condition, current_medications, additional_notes, created_at 
        FROM elderly_profiles";
$result = $conn->query($sql);

// متغيرات لتخزين الرسائل
$errorMessage = "";
$successMessage = "";

?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض ملفات كبار السن</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styleall.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- إضافة مكتبة Font Awesome -->
    <style>
        /* تنسيق الجدول */
        table {
            width: 100%; /* تأكد من أن الجدول يأخذ العرض الكامل */
            table-layout: auto; /* تسهيل توزيع الأعمدة */
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1em;
            text-align: right;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px; /* إضافة زوايا دائرية */
            overflow: hidden; /* مخفي للحواف الدائرية */
        }

        table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: center;
        }

        table th, table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        /* تغيير لون الخلفية لصفوف الجدول */
        table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        table tbody tr:nth-of-type(odd) {
            background-color: #ffffff;
        }

        /* إضافة تأثير تمرير الماوس على الصفوف */
        table tbody tr:hover {
            background-color: #e2f7f7; /* لون أفتح عند المرور */
            cursor: pointer; /* تغيير المؤشر */
        }

        /* تنسيق الأزرار في الجدول */
        table button, table a {
            padding: 4px 10px;
            border-radius: 8px; /* إضافة زوايا دائرية */
            background-color: #4A90E2;
            color: white;
            border: none;
            border-radius: px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s; /* إضافة تأثير */
        }

      
       

        a:hover {
            background-color: #007b5e;
        }

        /* تأثيرات الرسوم المتحركة */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* تنسيق الأزرار في شريط البحث */
        .search-container button {
            padding: 10px 20px;
            background-color: #4A90E2;
            color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
            }
            .button-group {
        display: flex; /* استخدام Flexbox لجعل الأزرار متجاورة */
        justify-content: center; /* توسيط الأزرار */
        gap: 10px; /* إضافة مسافة بين الأزرار */
    }

    .action-button {
        padding: 8px 12px;
        background-color: #4A90E2;
        color: white;
        border: none;
        border-radius: 3px;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .action-button:hover {
        background-color: #357ABD; /* تغيير لون الخلفية عند التمرير */
    }


            .search-container button:hover {
                background-color: #357ABD;
            }
            

            /* تنسيق عام */
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f4;
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

        .search-container input:focus, .search-container select:focus {
            border-color: #4A90E2;
        }

        /* تصميم الموديل */
.modal {
    display: none; /* مخفي في البداية */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4); /* خلفية شفافة */
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 300px;
    text-align: center;
    border-radius: 8px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}


  .alert {
            padding: 15px;
            margin: 20px 0;
            border: 1px solid #d4edda;
            border-radius: 5px;
            background-color: #d4edda;
            color: #155724;
        }
       
        .message {
            display: none; /* إخفاء الرسالة بشكل افتراضي */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333;
        }

    </style>
    <script>
        // دالة للبحث المباشر في الجدول
        function filterTable() {
            const nameInput = document.querySelector('input[name="search_name"]').value.toLowerCase();
            const genderInput = document.querySelector('select[name="gender_filter"]').value.toLowerCase();
            const dateInput = document.querySelector('input[name="created_at_filter"]').value;
            const healthConditionInput = document.querySelector('select[name="health_condition_filter"]').value.toLowerCase();

            const table = document.querySelector('table');
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                const gender = row.cells[6].textContent.toLowerCase();
                const createdAt = row.cells[12].textContent; // assuming this is the created_at date column
                const healthCondition = row.cells[9].textContent.toLowerCase();

                const nameMatch = name.includes(nameInput);
                const genderMatch = genderInput === '' || gender === genderInput;
                const dateMatch = dateInput === '' || createdAt.includes(dateInput);
                const healthConditionMatch = healthConditionInput === '' || healthCondition === healthConditionInput;

                if (nameMatch && genderMatch && dateMatch && healthConditionMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
    </script>
<script>
    window.onload = function() {
        setTimeout(function() {
            var message = document.getElementById('message');
            if (message) {
                message.style.display = 'none';
            }
        }, 3000); // 3000 ملي ثانية (3 ثواني)
    };
</script>
</head>
<body>
    
    <h1>Elderly_Profiles_Dashpoard</h1>
       <!-- Display the message -->
       <?php if ($message): ?>
        <div class='alert' id='message' style='color: green; text-align: center; margin: 0 auto; width: 100%;'><?= $message ?></div>
        <?php endif; ?>
    
    <div class="search-container">
        <input type="text" placeholder=" search_name " name="search_name" oninput="filterTable()">
        <select name="gender_filter" onchange="filterTable()">
            <option value="">Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <input type="date" name="created_at_filter" placeholder=" Created_at" onchange="filterTable()">
        <select name="health_condition_filter" onchange="filterTable()">
            <option value="">health_condition </option>
            <option value="Stable">Stable</option>
            <option value="Controlled">Controlled</option>
            <option value="Moderate">Moderate</option>
            <option value="Mild">Mild</option>
            <option value="Good">Good</option>
        </select>
        <button onclick="location.reload();">Update</button>
        <!-- إضافة زر Add_elder_profile لفتح الموديل -->
        <button onclick="openAddModal();">Add Elder Profile</button>
    </div>

<!-- موديل لإضافة Elder Profile جديد -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h2>Add New Elderly Profile</h2>
        <form action="add_elderly_profile.php" method="post">
            <label for="add_name">Name:</label>
            <input type="text" id="add_name" name="name" required><br>

            <label for="add_age">Date of Birth:</label>
            <input type="date" id="add_age" name="age" required><br>

            <label for="add_medical_history">Medical History:</label>
            <input type="text" id="add_medical_history" name="medical_history"><br>

            <label for="add_emergency_contact">Emergency Contact:</label>
            <input type="text" id="add_emergency_contact" name="emergency_contact" required><br>

            <label for="add_full_name">Full Name:</label>
            <input type="text" id="add_full_name" name="full_name" required><br>

            <label for="add_gender">Gender:</label>
            <select id="add_gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select><br>

            <label for="add_address">Address:</label>
            <input type="text" id="add_address" name="address"><br>

            <label for="add_phone">Phone Number:</label>
            <input type="text" id="add_phone" name="phone"><br>

            <label for="add_health_condition">Health Condition:</label>
            <input type="text" id="add_health_condition" name="health_condition"><br>

            <label for="add_current_medications">Current Medications:</label>
            <input type="text" id="add_current_medications" name="current_medications"><br>

            <label for="add_additional_notes">Additional Notes:</label>
            <input type="text" id="add_additional_notes" name="additional_notes"><br>

            <button type="submit" class="action-button">Add Profile</button>
            <button id="cancelAddBtn">cancel</button>

        </form>
    </div>
</div>
    
<!-- موديل تأكيد الحذف -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>Are you sure you want to delete this file?</p>
        <button id="confirmDeleteBtn" class="action-button">Yes</button><br>
        <button class="action-button" id="cancelDeleteBtn" onclick="closeModal()">Cancel</button>
    </div>
</div>

<!-- Modal لتعديل البيانات -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Elderly Profile</h2>
        <form action="update_elderly.php" method="post">
            <input type="hidden" id="elderly_id" name="elderly_id">
            <label for="edit_name">Name:</label>
            <input type="text" id="edit_name" name="name"><br>

            <label for="edit_age">Date of Birth:</label>
            <input type="date" id="edit_age" name="age"><br>

            <label for="edit_medical_history">Medical History:</label>
            <input type="text" id="edit_medical_history" name="medical_history"><br>

            <label for="edit_emergency_contact">Emergency Contact:</label>
            <input type="text" id="edit_emergency_contact" name="emergency_contact"><br>

            <label for="edit_full_name">Full Name:</label>
            <input type="text" id="edit_full_name" name="full_name"><br>

            <label for="edit_gender">Gender:</label>
            <input type="text" id="edit_gender" name="gender"><br>

            <label for="edit_address">Address:</label>
            <input type="text" id="edit_address" name="address"><br>

            <label for="edit_phone">Phone Number:</label>
            <input type="text" id="edit_phone" name="phone"><br>

            <label for="edit_health_condition">Health Condition:</label>
            <input type="text" id="edit_health_condition" name="health_condition"><br>

            <label for="edit_current_medications">Current Medications:</label>
            <input type="text" id="edit_current_medications" name="current_medications"><br>

            <label for="edit_additional_notes">Additional Notes:</label>
            <input type="text" id="edit_additional_notes" name="additional_notes"><br>

            <button type="submit" class="action-button">Save Changes</button>
        </form>
    </div>
</div>


<?php
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<thead>
            <tr>
                <th>id</th>
                <th>name</th>
                <th>Date_of_birth</th>
                <th>medical_history</th>
                <th>emerge_contact</th>
                <th>full_name</th>
                <th>gender</th>
                <th>address</th>
                <th>phone_NO</th>
                <th>health_condition</th>
                <th>current_medicen</th>
                <th>additional_notes</th>
                <th>Date_of_created</th>
                <th>procedures</th>
            </tr>
          </thead>";
    echo "<tbody>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['age']}</td>
                <td>{$row['medical_history']}</td>
                <td>{$row['emergency_contact']}</td>
                <td>{$row['full_name']}</td>
                <td>{$row['gender']}</td>
                <td>{$row['address']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['health_condition']}</td>
                <td>{$row['current_medications']}</td>
                <td>{$row['additional_notes']}</td>
                <td>{$row['created_at']}</td>
  <td>
    <div class='button-group'>
        <a href='#' onclick='openEditModal(
            \"" . addslashes($row['id']) . "\",
            \"" . addslashes($row['name']) . "\",
            \"" . addslashes($row['age']) . "\",
            \"" . addslashes($row['medical_history']) . "\",
            \"" . addslashes($row['emergency_contact']) . "\",
            \"" . addslashes($row['full_name']) . "\",
            \"" . addslashes($row['gender']) . "\",
            \"" . addslashes($row['address']) . "\",
            \"" . addslashes($row['phone']) . "\",
            \"" . addslashes($row['health_condition']) . "\",
            \"" . addslashes($row['current_medications']) . "\",
            \"" . addslashes($row['additional_notes']) . "\"
        )'><i class='fas fa-edit'></i> Edit</a> 
        <a href='#' onclick=\"openDeleteModal('delete_elderly.php?id={$row['id']}')\"><i class='fas fa-trash'></i> Delete</a>
    </div>
</td>

            </tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>There are no elderly files to display.</p>";
}

// Close connection
$conn->close();
?>
<script>
    // دالة لفتح الموديل الخاص بإضافة ملف جديد
    function openAddModal() {
        document.getElementById("addModal").style.display = "block";
    }

    // دالة لإغلاق الموديل الخاص بإضافة ملف جديد
    function closeAddModal() {
        document.getElementById("addModal").style.display = "none";
    }


    // إغلاق الموديل عند الضغط على زر الإلغاء أو X
    var cancelAddBtn = document.getElementById("cancelAddBtn"); // تأكد من وجود زر إلغاء بالمعرف هذا
    var closeModalBtn = document.getElementsByClassName("close")[0]; // تأكد من وجود عنصر X بالصفحة

    if (cancelAddBtn) {
        cancelAddBtn.onclick = closeAddModal;
    }
    if (closeModalBtn) {
        closeModalBtn.onclick = closeAddModal;
    }
</script>



<script>
    // المتغيرات للموديل
    let deleteModal = document.getElementById('deleteModal');
    let confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    let deleteUrl = ''; // متغير لتخزين رابط الحذف

    // دالة لفتح الموديل وتعيين رابط الحذف
    function openDeleteModal(url) {
        deleteUrl = url;
        deleteModal.style.display = 'block'; // إظهار الموديل
    }

    // دالة لإغلاق الموديل
    function closeModal() {
        deleteModal.style.display = 'none'; // إخفاء الموديل
    }

    // تنفيذ الحذف عند الضغط على زر تأكيد الحذف
    confirmDeleteBtn.onclick = function() {
        window.location.href = deleteUrl;
    };

    // إغلاق الموديل عند الضغط خارج المحتوى
    window.onclick = function(event) {
        if (event.target == deleteModal) {
            closeModal();
        }
    };
</script>

    <script>
        // دالة لفتح الموديل وتعبئة البيانات في النموذج
        function openEditModal(id, name, age, medical_history, emergency_contact, full_name, gender, address, phone, health_condition, current_medications, additional_notes) {
            document.getElementById('elderly_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_age').value = age;
            document.getElementById('edit_medical_history').value = medical_history;
            document.getElementById('edit_emergency_contact').value = emergency_contact;
            document.getElementById('edit_full_name').value = full_name;
            document.getElementById('edit_gender').value = gender;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_health_condition').value = health_condition;
            document.getElementById('edit_current_medications').value = current_medications;
            document.getElementById('edit_additional_notes').value = additional_notes;
            document.getElementById('editModal').style.display = 'block';
        }

        // إغلاق الموديل
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // إغلاق الموديل عند النقر خارج الموديل
        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) {
                closeEditModal();
            }
        }
    </script>
<div id="message" class="message"></div>

<script>
    function showMessage(text, isSuccess) {
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = text;

        // تغيير لون الخلفية بناءً على نوع الرسالة
        messageDiv.style.backgroundColor = isSuccess ? '#d4edda' : '#f8d7da'; // أخضر للفوز وأحمر للفشل
        messageDiv.style.color = isSuccess ? '#155724' : '#721c24'; // نص بالأخضر أو الأحمر

        messageDiv.style.display = 'block'; // إظهار الرسالة

        // إخفاء الرسالة بعد 3 ثواني
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    }

    // أمثلة لاستخدام الوظيفة
    // showMessage('تم حذف الملف بنجاح', true); // رسالة نجاح
    // showMessage('خطأ في حذف الملف', false); // رسالة خطأ
</script>
</body>
</html>
<?php // TEMPLATES
  include 'templates_html/end-main-content.html';
  include 'templates_html/footer.html';
?>
    