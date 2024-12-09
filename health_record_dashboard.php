<?php
session_start();
include 'db_connection.php';
include 'header.php';

$message = '';
$message_type = '';

// إذا كانت الرسالة موجودة في الجلسة، قم بتعيينها للمتغيرات
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? 'success'; // افتراضي على أنه نجاح
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
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

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جدول السجلات الصحية</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .success-message {
            color: green;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            border: 1px solid green;
            border-radius: 5px;
            background-color: #e8f9e9;
        }
        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            border: 1px solid red;
            border-radius: 5px;
            background-color: #f8d7da;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
            text-align: center;
            padding: 10px;
        }
        th {
            background-color: #f4f4f4;
        }
        button {
            padding: 5px 10px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        button.delete-btn { background-color: #dc3545; }
        button.edit-btn { background-color: #007bff; }
        button:hover { opacity: 0.9; }
        /* نمط الموديل */
        .modal {
            display: none; /* يتم إخفاؤه بشكل افتراضي */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%; /* عرض كامل */
            height: 100%; /* ارتفاع كامل */
            overflow: auto; /* السماح بالتمرير إذا لزم الأمر */
            background-color: rgba(0,0,0,0.4); /* شفافية الخلفية */
        }
                /* تعديل نمط الموديل */
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto; /* يظل من أعلى 10% وauto من الجانبين */
            padding: 20px;
            border: 1px solid #888;
            width: 400px; /* عرض 400px للنموذج */
            border-radius: 8px; /* زوايا دائرية */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* ظل خفيف للنموذج */
        }

        /* تحسين تنسيق الزر داخل النموذج */
        #healthRecordForm button {
            background-color: #28a745; /* لون زر إضافة السجل الصحي */
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s; /* تأثير تدرج للون عند التحويم */
        }

        #healthRecordForm button:hover {
            background-color: #218838; /* لون الزر عند التحويم */
        }

        /* نمط الحقول داخل النموذج */
        #healthRecordForm select,
        #healthRecordForm input[type="date"],
        #healthRecordForm textarea {
            width: 100%; /* جعل العرض 100% */
            padding: 8px; /* إضافة حشوة */
            margin: 10px 0; /* إضافة هوامش */
            border: 1px solid #ccc; /* إضافة حدود */
            border-radius: 4px; /* زوايا دائرية */
        }

        /* تحسين الحواف والتباعد */
        h2 {
            text-align: center; /* توسيط العنوان */
        }

        p{
            text-align: center; /* توسيط العنوان */
 
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

               /* أنماط البحث */
               .search-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-container input {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
        }

        .search-container button {
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }

        /* جدول */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
            text-align: center;
            padding: 10px;
        }

        th {
            background-color: #f4f4f4;
        }

        
    </style>
    
</head>
<body>
    <h1>Elder Health Records </h1>
    <hr>
    <?php if (!empty($message)): ?>
    <div class="<?= $message_type == 'error' ? 'error-message' : 'success-message' ?>">
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <button id="openModalBtn">Add_New_Record</button><br><br>
    

   <!-- حاوية البحث -->
   <div class="search-container">
        
        <label for="alert_date">RecordID :</label>
        <input type="text" id="searchRecordId" placeholder="SearchRecordID" oninput="filterRecords()">
        <label for="alert_date">SearchName :</label>
        <input type="text" id="searchName" placeholder=" ElderName" oninput="filterRecords()">
        <label for="alert_date">SearchDOB :</label>
        <input type="date" id="searchDateOfBirth" placeholder=" DOB" oninput="filterRecords()">
        <label for="alert_date">RecordDate :</label>
        <input type="date" id="searchRecordDate" placeholder="RecordDate" oninput="filterRecords()">
        <label for="alert_date">HealthCondition :</label>
        <input type="text" id="searchHealthCondition" placeholder="HealthCondition" oninput="filterRecords()">
        <button onclick="location.reload();">Update</button>

    </div>

    <table id="healthRecordsTable">
        <thead>
            <tr>
                <th>RecordNO</th>
                <th> ElderName</th>
                <th>Date_Of_Birth</th>
                <th>RecordDate</th>
                <th>Vital_signs</th>
                <th>Health_status</th>
                <th>Doctor_Prescriptions</th>
                <th>Notes</th>
                <th>Procedures</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // استعلام لجلب البيانات من health_records واسم كبير السن وتاريخ الميلاد من elderly_profiles
            $sql = "SELECT hr.id, ep.name AS elderly_name, ep.age AS date_of_birth, hr.date, hr.vital_signs, hr.notes, hr.updated_by, hr.health_condition, hr.prescriptions 
                    FROM health_records hr 
                    JOIN elderly_profiles ep ON hr.elderly_id = ep.id";
            $result = $conn->query($sql);
            // عرض السجلات في الجدول
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['elderly_name'] . "</td>";
                    echo "<td>" . $row['date_of_birth'] . "</td>"; // عرض تاريخ الميلاد
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['vital_signs'] . "</td>";
                    echo "<td>" . $row['health_condition'] . "</td>";
                    echo "<td>" . $row['prescriptions'] . "</td>";
                    echo "<td>" . $row['notes'] . "</td>";
                    echo "<td>";
                    echo "<button class='edit-btn' onclick='openEditRecordModal(" . json_encode($row) . ")'>Edit</button>"; // استدعاء جافا سكريبت مع بيانات السجل
                    echo "<button class='delete-btn' onclick='openConfirmModal(" . $row['id'] . ")'>Delet</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>There Is NO Records </td></tr>";
            }
            ?>
            
        </tbody>
    </table>

        <!--موديل تعديل السجل  -->
           <!-- Modal تعديل السجل -->
            <div id="editRecordModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeEditRecordModal()">&times;</span>
                    <h2>Edit_RecordModa </h2>
                    <form id="editRecordForm" method="POST" action="update_health_record.php">
                        <input type="hidden" id="editRecordId" name="id">

                        <label for="editElderlyName"> ElderName:</label>
                        <input type="text" id="editElderlyName" name="elderly_name" readonly><br><br>

                        <label for="editDateOfBirth">Date_Of_Birth:</label>
                        <input type="date" id="editDateOfBirth" name="date_of_birth" readonly><br><br>

                        <label for="editDate">Edit_Date:</label>
                        <input type="date" id="editDate" name="date" required><br><br>

                        <label for="editVitalSigns">Vital_signs:</label>
                        <input type="text" id="editVitalSigns" name="vital_signs" required><br><br>

                        <label for="editHealthCondition">Health_status:</label>
                        <input type="text" id="editHealthCondition" name="health_condition" required><br><br>

                        <label for="editPrescriptions">Doctor_Prescriptions:</label>
                        <input type="text" id="editPrescriptions" name="prescriptions"><br><br>

                        <label for="editNotes">Notes:</label>
                        <textarea id="editNotes" name="notes"></textarea><br><br>
                        <div style="text-align: center;">
                            <button type="submit">Edit</button>
                            <button type="button" onclick="closeEditRecordModal()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                // فتح المودال مع تعبئة البيانات
                function openEditRecordModal(record) {
                    document.getElementById('editRecordModal').style.display = 'block';

                    // تعبئة البيانات في النموذج
                    document.getElementById('editRecordId').value = record.id;
                    document.getElementById('editElderlyName').value = record.elderly_name;
                    document.getElementById('editDateOfBirth').value = record.date_of_birth;
                    document.getElementById('editDate').value = record.date;
                    document.getElementById('editVitalSigns').value = record.vital_signs;
                    document.getElementById('editHealthCondition').value = record.health_condition;
                    document.getElementById('editPrescriptions').value = record.prescriptions;
                    document.getElementById('editNotes').value = record.notes;
                }

                // إغلاق المودال
                function closeEditRecordModal() {
                    document.getElementById('editRecordModal').style.display = 'none';
                }

                        // إغلاق الموديل عند الضغط على أي مكان خارج الموديل
                window.onclick = function(event) {
                    if (event.target == document.getElementById('editRecordModal')) {
                        closeModal();
                    }
                }
            

                    
            </script>

        <!-- الموديل لتأكيد الحذف -->
        <div id="confirmDeleteModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeConfirmModal()">&times;</span>
                <h2>Confirm deletion!!</h2>
                <p>Are you sure you want to delete this record?</p>
                <div style="text-align: center;">
                    <button id="confirmDeleteBtn" class="delete-btn">Yes</button>
                    <button class="edit-btn" onclick="closeConfirmModal()">Cancel</button>
                </div>
            </div>
        </div>

    <script>
        let recordIdToDelete;

        function openConfirmModal(recordId) {
            recordIdToDelete = recordId; // حفظ الـ ID للسجل الذي سيتم حذفه
            document.getElementById('confirmDeleteModal').style.display = 'block';
        }

        function closeConfirmModal() {
            document.getElementById('confirmDeleteModal').style.display = 'none';
        }

        // تنفيذ عملية الحذف عند تأكيد الحذف
        document.getElementById('confirmDeleteBtn').onclick = function() {
            window.location.href = 'delete_record.php?id=' + recordIdToDelete; // إعادة توجيه إلى صفحة الحذف
        }

        
    </script>


    <script>
        function filterRecords() {
            const recordId = document.getElementById("searchRecordId").value.toLowerCase();
            const name = document.getElementById("searchName").value.toLowerCase();
            const dateOfBirth = document.getElementById("searchDateOfBirth").value.toLowerCase();
            const recordDate = document.getElementById("searchRecordDate").value.toLowerCase();
            const healthCondition = document.getElementById("searchHealthCondition").value.toLowerCase();

            const table = document.getElementById("healthRecordsTable");
            const rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let showRow = true;
                const cells = rows[i].getElementsByTagName("td");

                if (recordId && cells[0].innerText.toLowerCase() !== recordId) {
                    showRow = false;
                }
                if (name && !cells[1].innerText.toLowerCase().includes(name)) {
                    showRow = false;
                }
                if (dateOfBirth && cells[2].innerText.toLowerCase() !== dateOfBirth) {
                    showRow = false;
                }
                if (recordDate && cells[3].innerText.toLowerCase() !== recordDate) {
                    showRow = false;
                }
                if (healthCondition && !cells[5].innerText.toLowerCase().includes(healthCondition)) {
                    showRow = false;
                }

                rows[i].style.display = showRow ? "" : "none"; // إظهار أو إخفاء الصف
            }
        }
    </script>


    <div id="healthRecordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Add_Health_Record</h2>
            <form id="healthRecordForm" action="add_health_record.php" method="POST">
                <label for="elderly_id">Select_Elder_name:</label>
                <select name="elderly_id" required>
                    <option value="">-----</option>
                    <?php
                    // جلب أسماء كبار السن من قاعدة البيانات
                    $sql = "SELECT id, name FROM elderly_profiles";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . " (ID: " . $row['id'] . ")</option>";
                        }
                    } else {
                        echo "<option value=''>There is No Elder ..... </option>";
                    }
                    ?>
                </select>
                <label for="alert_date">DOB :</label>
                <input type="date" name="date" required>
                <label for="alert_date">Vital_signs :</label>
                <textarea name="vital_signs" placeholder=" vital_signs" required></textarea>
                <label for="alert_date">Health status :</label>
                <textarea name="health_condition" placeholder=" Health status" required></textarea>
                <label for="alert_date">Doctor prescription :</label>
                <textarea name="prescriptions" placeholder=" prescriptions" ></textarea>
                <label for="alert_date">Notes :</label>
                <textarea name="notes" placeholder="notes"></textarea>
                <div style="text-align: center;">
                    <button type="submit" style="padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">ِAdd</button>
                    <button type="button" onclick="closeModal()" style="padding: 10px 15px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>
                </div>

            </form>
        </div>
    </div>

    <script>    
        // إخفاء الرسالة بعد 4 ثواني (4000 مللي ثانية)
        setTimeout(function() {
            var message = document.querySelector('.success-message, .error-message');
            if (message) {
                message.style.display = 'none';
            }
        }, 4000);

        // فتح الموديل
        document.getElementById('openModalBtn').onclick = function() {
            document.getElementById('healthRecordModal').style.display = 'block';
        }

        // إغلاق الموديل
        function closeModal() {
            document.getElementById('healthRecordModal').style.display = 'none';
        }

        // إغلاق الموديل عند الضغط على أي مكان خارج الموديل
        window.onclick = function(event) {
            if (event.target == document.getElementById('healthRecordModal')) {
                closeModal();
            }
        }
    </script>
    <script>
        
    </script>
</body>
</html>
<?php // TEMPLATES
  include 'templates_html/end-main-content.html';
  include 'templates_html/footer.html';
?>

