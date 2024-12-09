<div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-center">

        <!-- Logo Section -->
        <div class="me-5 pe-5">
            <a href="<?php 
                if (isset($_SESSION['user_role']) && isset($_SESSION['user_id'])) {
                    // توجيه المستخدم حسب دوره
                    switch ($_SESSION['user_role']) {
                        case 'admin':
                            echo 'dashboard.php';
                            break;
                        case 'doctor':
                            echo 'Doctor_Dashboard.php';
                            break;
                        case 'caregiver':
                            echo 'employee_dashboard.php';
                            break;
                        case 'family':
                            echo 'form_insert_elder_profile.php';
                            break;
                        default:
                            echo 'default_dashboard.php'; // يمكن توجيههم إلى لوحة افتراضية
                            break;
                    }
                } else {
                    echo 'login.php'; // إعادة التوجيه إذا لم يكن المستخدم مسجل الدخول
                }
            ?>">
                <img src="images/elderlycare-logo.png" alt="ElderlyCare Management System Logo">
            </a>
        </div>

        <!-- LOGIN/LOGOUT BUTTONS -->
        <div class="ms-5 ps-5">
            <button type="button" class="btn btn-outline-light me-2" value="Logout" onclick="window.location.href='logout.php';">Logout</button>

            <!-- زر Home مع التوجيه حسب الدور -->
            <button type="button" class="btn btn-info text-light" 
                onclick="window.location.href='<?php 
                    if (isset($_SESSION['user_role']) && isset($_SESSION['user_id'])) {
                        // توجيه المستخدم حسب دوره
                        switch ($_SESSION['user_role']) {
                            case 'admin':
                                echo 'dashboard.php';
                                break;
                            case 'doctor':
                                echo 'Doctor_Dashboard.php';
                                break;
                            case 'caregiver':
                                echo 'employee_dashboard.php';
                                break;
                            case 'family':
                                echo 'form_insert_elder_profile.php';
                                break;
                            default:
                                echo 'default_dashboard.php'; // صفحة افتراضية في حالة وجود دور غير معروف
                                break;
                        }
                    } else {
                        echo 'login.php'; // إعادة التوجيه إلى صفحة تسجيل الدخول إذا لم يكن المستخدم مسجل الدخول
                    }
                ?>';">
                Home
            </button>
        </div>
    </div>
</div>
