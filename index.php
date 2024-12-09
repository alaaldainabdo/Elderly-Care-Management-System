<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElderlyCare Management System</title>
    <!-- تضمين Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        /* إعدادات أساسية */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        h1, h2, h3 {
            color: #2c3e50;
            text-align: center;
        }

        .content-background {
            background: rgba(255, 255, 255, 0.9); /* لون خلفية فاتح مع شفافية */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin: 20px auto;
            max-width: 1200px;
        }

        .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .image-gallery img {
            width: 100%;
            max-width: 250px; /* الحد الأقصى لحجم الصورة */
            border-radius: 10px;
            transition: transform 0.3s;
        }

        .image-gallery img:hover {
            transform: scale(1.05);
        }

        .text-background {
            background: rgba(255, 255, 255, 0.8); /* خلفية نصية شفافة */
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        ul {
            margin: 10px 0;
            padding-left: 20px;
            list-style-type: disc;
        }

        p {
            font-size: 1.1em;
            margin-bottom: 20px;
            text-align: justify;
            color: #333; /* لون النص */
        }

        h6 {
            margin-top: 20px;
            font-weight: bold;
            color: #333; /* لون النص */
        }

        /* استجابة للأحجام الصغيرة */
        @media (max-width: 768px) {
            .image-gallery img {
                max-width: 100%; /* الصور تشغل العرض الكامل على الشاشات الصغيرة */
            }

            .content-background {
                padding: 15px;
            }

            p, ul {
                font-size: 1em;
            }
        }

        @media (max-width: 480px) {
            h1, h2, h3 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>
    <?php
    include 'db_connection.php';
    session_start();

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

    <!-- إضافة الخلفية للقسم النصي -->
    <div class="content-background">
        <h2 class="animate__animated animate__fadeIn">General Info</h2>
        
        <div class="image-gallery">
            <img src="images/elder1.jpeg" alt="Elderly Care 1" class="img-fluid animate__animated animate__fadeIn">
            <img src="images/elder2.jpeg" alt="Elderly Care 2" class="img-fluid animate__animated animate__fadeIn">
            <img src="images/elder3.jpeg" alt="Elderly Care 3" class="img-fluid animate__animated animate__fadeIn">
            <img src="images/elder4.jpeg" alt="Elderly Care 3" class="img-fluid animate__animated animate__fadeIn">
        </div>

        <p class="animate__animated animate__fadeIn text-background">The goal of the Elderly Care website is to provide information and resources necessary to support individuals and communities in effectively caring for seniors. By offering helpful tips and guidelines, the website aims to achieve the following objectives:</p>
        <ul class="animate__animated animate__fadeIn">
            <li><strong>Raise Awareness:</strong> Educate the public about the importance of senior care and their specific needs.</li>
            <li><strong>Provide Information:</strong> Offer reliable information on best practices for senior care, including mental and physical health.</li>
            <li><strong>Enhance Communication:</strong> Create a platform for sharing experiences and ideas among caregivers and family members.</li>
            <li><strong>Offer Support:</strong> Provide resources and support for caregivers to help them deal with daily challenges.</li>
            <li><strong>Improve Quality of Life:</strong> Work to enhance the quality of life for seniors by offering advice on health care, nutrition, and social activities.</li>
            <li><strong>Facilitate Access to Services:</strong> Provide information about available services for seniors, such as medical care and psychological support.</li>
        </ul>
        <p class="animate__animated animate__fadeIn text-background">Overall, the website aims to create a supportive community focused on the well-being of seniors and to provide an environment that enhances their quality of life.</p>
    
    <hr>
    
    <h3 class="animate__animated animate__fadeIn">Guidelines for Elderly Care</h3>
    <p class="animate__animated animate__fadeIn text-background">Caring for the elderly is an essential responsibility that requires compassion, patience, and understanding. Whether you're a family member, caregiver, or friend, supporting seniors in maintaining their dignity and quality of life is crucial.</p>
    
    <hr>
    
    <h3 class="animate__animated animate__fadeIn">Key Tips and Guidelines:</h3>
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
    include 'templates_html/end-main-content.html';
    include 'templates_html/footer.html';
    ?>
</body>
</html>



    
