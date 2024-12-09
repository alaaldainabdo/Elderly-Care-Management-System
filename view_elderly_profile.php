<?php
include 'db_connection.php';
include 'header.php';

// جلب البيانات من جدول elderly_profiles
$sql = "SELECT * FROM elderly_profiles";
$result = $conn->query($sql);

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
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background-color: #333;
            color: #fff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #0779e4 3px solid;
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
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Elderly Profiles Dashboard</h1>
        </div>
    </header>

    <div class="container">
        <h2>List of Elderly Profiles</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th><th>Age</th><th>Medical History</th><th>Emergency Contact</th><th>Created By</th><th>Phone</th><th>Health Condition</th><th>Current Medications</th><th>Additional Notes</th><th>Created At</th></tr>";
            
            // عرض البيانات في الجدول
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["age"] . "</td>";
                echo "<td>" . $row["medical_history"] . "</td>";
                echo "<td>" . $row["emergency_contact"] . "</td>";
                echo "<td>" . $row["created_by"] . "</td>";
                echo "<td>" . $row["phone"] . "</td>";
                echo "<td>" . $row["health_condition"] . "</td>";
                echo "<td>" . $row["current_medications"] . "</td>";
                echo "<td>" . $row["additional_notes"] . "</td>";
                echo "<td>" . $row["created_at"] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No profiles found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
