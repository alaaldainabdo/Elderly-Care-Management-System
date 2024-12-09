<?php
include 'db_connection.php';
session_start();

// التحقق من جلسة المستخدم إذا لزم الأمر
/*
if(isset($_SESSION['level'])){
    header('location:extras/transfer.php?error=2');
    exit();
} else {
    session_destroy();
}
*/

// تضمين القوالب
include 'templates_html/header.html';

if (isset($_SESSION['user_role'])) {
    switch ($_SESSION['user_role']) {
        default:
            include 'templates_html/alert-message-before-login.html';
            include 'templates_html/home-nav-bar.html';
            include 'templates_html/main-grid-content-1column.html';
    }
} else {
    include 'templates_html/alert-message-before-login.html';
    include 'templates_html/home-nav-bar.html';
    include 'templates_html/main-grid-content-1column.html';
}

// المحتوى الرئيسي
include 'templates_html/main-content.html';
?>

<h1 class="text-center pb-5 animate__animated animate__fadeIn">ElderlyCare Management System</h1>
<hr>

<div class="mb-5 text-start p-5">
    <h2 class="animate__animated animate__fadeIn">General info</h2>
    <img src="images/elderly_care.jpg" alt="Elderly Care" class="img-fluid animate__animated animate__fadeIn" style="width: 100%; height: auto;">
    
    <p class="animate__animated animate__fadeIn">The goal of the Elderly Care website is to provide information and resources necessary to support individuals and communities in effectively caring for seniors. By offering helpful tips and guidelines, the website aims to achieve the following objectives:</p>
    <ul class="animate__animated animate__fadeIn">
        <li><strong>Raise Awareness:</strong> Educate the public about the importance of senior care and their specific needs.</li>
        <li><strong>Provide Information:</strong> Offer reliable information on best practices for senior care, including mental and physical health.</li>
        <li><strong>Enhance Communication:</strong> Create a platform for sharing experiences and ideas among caregivers and family members.</li>
        <li><strong>Offer Support:</strong> Provide resources and support for caregivers to help them deal with daily challenges.</li>
        <li><strong>Improve Quality of Life:</strong> Work to enhance the quality of life for seniors by offering advice on health care, nutrition, and social activities.</li>
        <li><strong>Facilitate Access to Services:</strong> Provide information about available services for seniors, such as medical care and psychological support.</li>
    </ul>
    
    <p class="animate__animated animate__fadeIn">Overall, the website aims to create a supportive community focused on the well-being of seniors and to provide an environment that enhances their quality of life.</p>
    
    <hr>
    
    <h3 class="animate__animated animate__fadeIn">Guidelines for Elderly Care</h3>
    <p class="animate__animated animate__fadeIn">Caring for the elderly is an essential responsibility that requires compassion, patience, and understanding. Whether you're a family member, caregiver, or friend, supporting seniors in maintaining their dignity and quality of life is crucial.</p>
    
    <hr>
    
    <h3 class="animate__animated animate__fadeIn">Key tips and guidelines:</h3>
    <ul class="animate__animated animate__fadeIn">
        <li>Respect Their Independence</li>
        <li>Ensure a Safe Environment</li>
        <li>Promote Physical Activity</li>
        <li>Monitor Health Regularly</li>
        <li>Encourage Mental Stimulation</li>
        <li>Maintain a Nutritious Diet</li>
        <li>Foster Social Connections</li>
        <li>Provide Emotional Support</li>
        <li>Adapt Communication Styles</li>
        <li>Be Prepared for Emergencies</li>
    </ul>
    
    <hr>
    
    <h3 class="animate__animated animate__fadeIn">Software Engineer</h3>
    <ul>
        <li>Ala Al_Dain</li>
    </ul>
    <h6 class="animate__animated animate__fadeIn">Contact Us</h6>
    <ul>
        <li>917558575378</li>
    </ul>
    <hr>
</div>

<?php
// تضمين القوالب في النهاية
include 'templates_html/end-main-content.html';
include 'templates_html/footer.html';
?>

<!-- تضمين Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
