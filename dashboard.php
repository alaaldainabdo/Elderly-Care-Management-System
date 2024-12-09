<?php

session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if user data is available in the session
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_id'] = $user['id'];
}

include 'db_connection.php';



/*session_start();
if(isset($_SESSION['level'])){
  header('location:extras/transfer.php?error=2');
}else{
  session_destroy();
}
*/
    
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
                include 'templates_html/admin.html';
                include 'templates_html/main-grid-content-1column.html';
        }
    } else {
        include 'templates_html/main-grid-content-1column.html';
    }
    

  


    include 'templates_html/main-content.html';
?>

        <h1 class="text-center pb-5">Elderly Care Management System</h1>
        <hr>

        <ul class="nav col-13 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
         
            <li><a href="manage_users.php"class="nav-link px-2 text-dark"> User_Management</a></li>
            <li><a href="taske_dashboard.php" class="nav-link px-2 text-dark">Employees_management_and_Tasks</a></li>
            <li><a href="health_record_dashboard.php" class="nav-link px-2 text-dark"> health_records management</a></li>
            <li><a href="emergency_dashboard.php" class="nav-link px-2 text-dark"> Emergency_Alerts_management</a></li>
            <li><a href="elderly_profiles_dashpoard.php" class="nav-link px-2 text-dark"> Elderly_Profiles_management</a></li>
            <li><a href="insert_medication_schedule.php" class="nav-link px-2 text-dark"> medication_Schedule_management</a></li>
          
        </ul>
        <hr>
        <br>

      <div class="mb-5 text-start p-5">
      <h2>Overview</h2>
        <br>
        <h3> Advanced Software Project 01</h3>

        <p>This project aims to replicate the workflow of a 
        real-world development agency by planning, designing, 
        and implementing a functional web application based 
        on client needs and organizational methodologies. It 
        integrates all the skills and concepts we've developed 
        throughout this year.</p>

        <h4>Timeline</h4>
        <p>The project spans five weeks. Each week begins with a 
        strategic planning session to organize tasks and 
        evaluate the progress of the previous week's release.</p>
        <hr>
        <h3>Technologies</h2>
        <ul>
          <li>HTML5</li>
          <li>CSS3</li>
          <li>JavaScript</li>
          <li>PHP</li>
          <li>phpMyAdmin</li>
          <li>MySQL</li>  
          <li>Agile Process</li>
          <li>Feature Branch Git Workflow</li>
        </ul>
        <hr>
      <h3>Software Engineer</h2>
        <ul>
          <li>Ala Al_Dain</li>
            
       
         <h6>Contact Us</<h6>
         <li>917558575378</li>
        
      
          <hr>
    </div>

<?php // TEMPLATES
  include 'templates_html/end-main-content.html';
  include 'templates_html/footer.html';
?>
