<?php
session_start();
// Uncomment and adjust the session check as needed
/*
if(!isset($_SESSION['level'])) header('location:home.php');
if(isset($_SESSION['level'])) {
    if($_SESSION['level'] != 1 && $_SESSION['level'] != 2) header('location:extras/transfer.php');
};
*/
include 'db_connection.php';

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



// Fetch elderly profiles from the database for the dropdown
$result = $conn->query("SELECT id, name FROM elderly_profiles");

// متغيرات لتخزين الرسائل
$errorMessage = "";
$successMessage = "";

// التعامل مع تقديم النموذج لإضافة مواعيد الأدوية
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_medication'])) {
    $elderly_id = $_POST['elderly_id'];
    $morning_medication = trim($_POST['morning_medication']);
    $lunch_medication = trim($_POST['lunch_medication']);
    $dinner_medication = trim($_POST['dinner_medication']);

    // التحقق مما إذا كان ID المسن موجودًا
    $checkQuery = "SELECT id, name FROM elderly_profiles WHERE id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $elderly_id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows == 0) {
        $errorMessage ="Error: The specified elder_name does not exist.";
    } else {
        // احصل على اسم المسن
        $checkStmt->bind_result($elderly_id, $name);
        $checkStmt->fetch();
        
        // إدخال مواعيد الأدوية
        $medications = [
            'after_morning' => !empty($morning_medication) ? $morning_medication : 'NO_thing',
            'after_lunch' => !empty($lunch_medication) ? $lunch_medication : 'NO_thing',
            'after_dinner' => !empty($dinner_medication) ? $dinner_medication : 'NO_thing',
        ];

        // استعلام SQL لإدخال مواعيد الأدوية
        $insertQuery = "INSERT INTO medication_schedule (elderly_id, after_morning, after_lunch, after_dinner) VALUES (?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("isss", $elderly_id, $medications['after_morning'], $medications['after_lunch'], $medications['after_dinner']);
        
        if ($insertStmt->execute()) {
            $successMessage = "Medication schedules added successfully.";
        } else {
            $errorMessage = "Error: Medication schedules not added !!." . $conn->error;
        }

        $insertStmt->close();
    }

    $checkStmt->close();
}
?>

<!-- عرض الرسائل -->
<div class="mb-5 mt-5 text-center">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
         <!-- عرض الرسائل -->
         <?php if (!empty($errorMessage)): ?>
            <div class='alert alert-danger' id='message'><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class='alert alert-success' id='message'><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <h1 class="text-center">Add medication schedules to the Elder</h1>
        <hr>

       
        <label for="elderly_id">Select Elder_Name</label>
        <br>
        <select name="elderly_id" required>
            <option value="">------</option>
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?> (ID: <?php echo $row['id']; ?>)</option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label for="morning_medication">Morning_Medi</label>
        <br>
        <input type="text" id="morning_medication" name="morning_medication" placeholder=" Morning_Medi " required>
        <br><br>

        <label for="lunch_medication">lunch_Medi</label>
        <br>
        <input type="text" id="lunch_medication" name="lunch_medication" placeholder="lunch_Medi" required>
        <br><br>

        <label for="dinner_medication"> Dinner_Medi</label>
        <br>
        <input type="text" id="dinner_medication" name="dinner_medication" placeholder="Dinner_Medi" required>
        <br><br>

        <input type="hidden" name="action" value="add_medication">
        <button class="w-50 btn btn-sm btn-info text-light mt-1 mb-1" type="submit" name="add_medication"> Add medication </button>
        <button class="w-50 btn btn-sm btn-secondary text-light mt-1 mb-1" type="reset">Cancel </button>
        <button class="w-50 btn btn-sm btn-info text-light mt-1 mb-1" type="button" onclick="window.location.href='dashboard.php';">Home</button>

    </form> 
</div>

<script>
    // تأخير لإخفاء الرسالة بعد 3 ثوانٍ
    window.onload = function() {
        var messageDiv = document.getElementById('message');
        if (messageDiv) {
            setTimeout(function() {
                messageDiv.style.display = 'none';
            }, 3000); // الوقت بالملي ثانية (3000 ملي ثانية = 3 ثوانٍ)
        }
    };
</script>

</form>
<hr>
<hr>

<!-- البحث عن مريض أو دواء -->
<div class="mb-5 mt-5 text-center">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <h3> Search for a Elder or medicine </h3>
        <input type="text" name="search_term" placeholder=" Elder_name or medicine " required>
        <input type="hidden" name="action" value="search">
        <button class="w-45 btn btn-sm btn-info text-light mt-1 mb-2" type="submit">Search</button>
        <button class="w-45 btn btn-sm btn-secondary mt-1 mb-2" type="button" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">to update</button>
    </form>
</div>
<!-- عرض قائمة مواعيد الأدوية -->
<h2>Medication Schedule List</h2>
<!-- إضافة تنسيق للجدول -->
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 15px; /* إضافة مسافة داخل الخلايا */
        text-align: center;
    }

    th {
        background-color: #f2f2f2; /* لون خلفية للعناوين */
    }

    td {
        background-color: #fff; /* لون خلفية للخلايا */
    }

    tr:nth-child(even) {
        background-color: #f9f9f9; /* تلوين الصفوف بالتناوب */
    }
</style>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th> Elder_Name</th>
            <th>Medicine after morning</th>
            <th>Medicine after lunch</th>
            <th>Medicine after dinner</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'search') {
                $search_term = trim($_POST['search_term']);
                $searchQuery = "SELECT ms.id AS medication_id, ep.name AS patient_name, ms.after_morning, ms.after_lunch, ms.after_dinner 
                                FROM medication_schedule ms
                                JOIN elderly_profiles ep ON ms.elderly_id = ep.id
                                WHERE ep.name LIKE ? OR ms.after_morning LIKE ? OR ms.after_lunch LIKE ? OR ms.after_dinner LIKE ?";
                $search_term_wildcard = "%$search_term%";
                $searchStmt = $conn->prepare($searchQuery);
                $searchStmt->bind_param("ssss", $search_term_wildcard, $search_term_wildcard, $search_term_wildcard, $search_term_wildcard);
                $searchStmt->execute();
                $searchResult = $searchStmt->get_result();

                if ($searchResult->num_rows > 0) {
                    while ($row = $searchResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['medication_id']; ?></td>
                            <td><?php echo $row['patient_name']; ?></td>
                            <td><?php echo $row['after_morning']; ?></td>
                            <td><?php echo $row['after_lunch']; ?></td>
                            <td><?php echo $row['after_dinner']; ?></td>
                            <td><a href="edit_medication.php?id=<?php echo $row['medication_id']; ?>">Edit</a></td>
                            <td><a href="delete_medication.php?id=<?php echo $row['medication_id']; ?>" onclick="return confirm(' Are you sure you want to delete this medication? ');">Delete</a></td>
                        </tr>
                    <?php endwhile;
                } else {
                    echo "<tr><td colspan='7'>There is no data to display.</td></tr>";
                }
            }
        }
        ?>
    </tbody>
</table>




<?php // TEMPLATES
include 'templates_html/end-main-content.html';
include 'templates_html/footer.html';
?>
