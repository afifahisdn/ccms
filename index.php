<!DOCTYPE html>
<html lang="en">
<?php
/*
* index.php
* Main homepage for the CCMS.
* Shows "Check Status" form for guests.
* Shows "Student Dashboard" for logged-in students.
*/

// Includes session_start() via assets.php, db connection, and fetches settings ($res)
include "pages/header.php";

// Check if the user has submitted the complaint status form
if (isset($_POST["complaint_id"])) {
    $complaint_id = $_POST["complaint_id"];

    // Basic validation (ensure it's numeric)
    if (is_numeric($complaint_id)) {
        header("Location: complaint_status.php?complaint_id=$complaint_id");
        exit();
    } else {
        $error_message = "Invalid Complaint ID format. Please enter numbers only.";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title is set in pages/header.php -->
    <script>
        // Prevent back button navigation after logout
        if (window.history && window.history.pushState) {
            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, null, window.location.href);
            };
        }
    </script>
    <!-- All CSS is included from pages/header.php -->
</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="200">

    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close mt-3">
                <span class="icon-close2 js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>

    <header class="site-navbar js-site-navbar site-navbar-target" role="banner" id="site-navbar">

        <!-- Top Header Section -->
        <div class="top-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <!-- Translate Button (Optional) -->
                        <div class="dropdown-container float-right d-inline-block ml-3">
                            <button class="dropdown-toggle btn btn-translate" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="server/uploads/settings/Translate.png" alt="Translate" style="max-width: 25px;">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="?lang=en">English</a>
                            </div>
                        </div>

                        <?php if (!isset($_SESSION["student_id"])) : // Check for student_id 
                        ?>
                            <!-- Guest Links -->
                            <a href="admin/register.php" class="quick-link d-inline-block">Signup</a>
                            <a href="admin/login.php" class="quick-link d-inline-block">Login</a>
                        <?php else : ?>
                            <!-- Logged-in Student Links -->
                            <a href="admin/logout.php" class="quick-link d-inline-block">Logout</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Top Header Section -->

        <!-- Main Navigation -->
        <div class="container main-nav-container">
            <div class="row align-items-center">

                <div class="col-6 col-xl-2 site-logo">
                    <a href="index.php" class="text-white h5 mb-0">
                        <img src="server/uploads/settings/logo.png" alt="CCMS Logo" style="max-width: 70px; padding: 8px;">
                    </a>
                </div>

                <div class="col-6 col-md-10 d-xl-none text-right">
                    <!-- Mobile Menu Toggle -->
                    <a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu h3"></span></a>
                </div>


                <div class="col-12 col-xl-10 d-none d-xl-block">
                    <!-- Desktop Navigation -->
                    <nav class="site-navigation position-relative text-right" role="navigation">
                        <ul class="site-menu js-clone-nav mx-auto d-none d-lg-block">
                            <?php if (isset($_SESSION["student_id"])) : // Check for student_id 
                            ?>
                                <!-- Logged-in Student Menu -->
                                <li><a href="index.php#section-customer" class="nav-link">Home</a></li>
                                <li><a href="complaints.php" class="nav-link">My Complaints</a></li>
                                <li><a href="submit_complaint.php" class="nav-link">Submit Complaint</a></li>
                                <li><a href="profile.php" class="nav-link">Profile</a></li>
                                <li><a href="index.php#section-contact" class="nav-link">Contact</a></li>
                            <?php else : ?>
                                <!-- Guest Menu -->
                                <li><a href="index.php#section-track-and-trace" class="nav-link">Check Complaint Status</a></li>
                                <li><a href="admin/login.php" class="nav-link">Submit Complaint</a></li>
                                <li><a href="index.php#section-about" class="nav-link">About Us</a></li>
                                <li><a href="index.php#section-contact" class="nav-link">Contact</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
        <!-- End Main Navigation -->

    </header>
    <?php if (isset($_SESSION["student_id"])) { // Check for student_id 
    ?>
        <!-- =================================== -->
        <!-- LOGGED-IN STUDENT DASHBOARD SECTION -->
        <!-- =================================== -->
        <div class="site-blocks-cover overlay" style="background-image: url(<?php echo $subheader_src; ?>);" data-aos="fade" data-stellar-background-ratio="0.5" id="section-customer">
            <div class="container">
                <div class="row align-items-center justify-content-center text-center">
                    <div class="col-md-12" data-aos="fade-up" data-aos-delay="400">
                        <div class="index-margin">
                            <div class="text-center pb-1 border-primary mb-4">
                                <h2 class="text-white text-uppercase font-weight-bold" data-aos="fade-up">Student Dashboard</h2>
                            </div>
                        </div>

                        <div class="content d-flex justify-content-center align-items-center flex-wrap">
                            <div class="btnMenu">
                                <a href="complaints.php" class="nav-link">
                                    <div class="icon">
                                        <img src="Menu/Complaint.png" alt="My Complaints Icon">
                                    </div>
                                    <h4 class="text-dark mt-3"><b>My Complaints</b></h4>
                                </a>
                            </div>
                            <div class="btnMenu">
                                <a href="submit_complaint.php" class="nav-link">
                                    <div class="icon">
                                        <img src="Menu/Submit.png" alt="Submit Complaint Icon">
                                    </div>
                                    <h4 class="text-dark mt-3" style="width:max-content;"><b>Submit Complaint</b></h4>
                                </a>
                            </div>
                            <div class="btnMenu">
                                <a href="profile.php" class="nav-link">
                                    <div class="icon">
                                        <img src="Menu/Profile.png" alt="Profile Icon">
                                    </div>
                                    <h4 class="text-dark mt-3"><b>Profile</b></h4>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php } else { ?>
        <!-- =================================== -->
        <!-- GUEST "CHECK STATUS" SECTION      -->
        <!-- =================================== -->
        <div class="site-blocks-cover overlay" style="background-image: url(<?php echo $header_src; ?>);" data-aos="fade" data-stellar-background-ratio="0.5" id="section-track-and-trace">
            <div class="container">
                <div class="row align-items-center justify-content-center text-center">
                    <div class="col-md-8" data-aos="fade-up" data-aos-delay="400">
                        <h1 class="text-white font-weight-light mb-4" style="margin-top: 7rem" data-aos="fade-up"><?php echo htmlspecialchars($res['header_title'] ?? 'Complaint Management System'); ?></h1>
                        <p class="mb-4 text-white" data-aos="fade-up" data-aos-delay="100"><?php echo htmlspecialchars($res["header_desc"] ?? 'Check the status of your complaint below.'); ?></p>

                        <?php if (isset($error_message)) : ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>

                        <!-- Form points to self, PHP at top handles redirect -->
                        <form method="post" action="index.php#section-track-and-trace">
                            <div class="form-group">
                                <input type="text" id="complaint_id" name="complaint_id" class="form-control form-control-lg mb-3" placeholder="Enter your Complaint ID">
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg py-3 px-5 text-white">Check Status</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<!-- =================================== -->
    <!-- ABOUT SECTION                       -->
    <!-- =================================== -->
    <div class="site-section" id="section-about">
        <div class="container">
            <div class="row mb-5 align-items-center" style="margin-top: 6rem">
                <div class="col-md-5 ml-auto mb-5 order-md-2" data-aos="fade-left" data-aos-delay="100">
                    <img src="<?php echo $about_src; ?>" alt="About CCMS" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-6 order-md-1" data-aos="fade-right">
                    <div class="text-left pb-1 border-primary mb-4">
                        <h2 class="text-primary"><?php echo htmlspecialchars($res["about_title"] ?? 'About Our System'); ?></h2>
                    </div>
                    <p class="lead"><?php echo nl2br(htmlspecialchars($res["about_desc"] ?? 'Description about the complaint system.')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- =================================== -->
    <!-- CONTACT / FEEDBACK SECTION -->
    <!-- =================================== -->
    <div class="site-section bg-light" id="section-contact">
        <div class="container">
            <div class="row justify-content-center mb-5" style="margin-top: 4rem">
                <div class="col-md-7 text-center border-primary">
                    <h2 class="font-weight-light text-primary">Contact Us / Give Feedback</h2>
                </div>
            </div>
            <div class="row">

                <!-- This section now checks if the student is logged in -->
                <?php if (isset($_SESSION["student_id"])) : ?>
                    <!-- IF LOGGED IN: Show Feedback Form -->
                    <div class="col-md-7 mb-5">
                        <form class="p-5 bg-white rounded shadow" method="post" onsubmit="return false;">                            
                            <div class="row form-group mb-3">
                                <div class="col-md-12">
                                    <label class="text-black" for="subject">Subject</label>
                                    <input type="text" name="subject" id="subject" class="form-control" required>
                                </div>
                            </div>
                            <div class="row form-group mb-3">
                                <div class="col-md-12">
                                    <label class="text-black" for="message">Message / Feedback</label>
                                    <textarea name="message" id="message" cols="30" rows="5" class="form-control" required></textarea>
                                </div>
                            </div>
                            <!-- Hidden student_id to send with the form -->
                            <input type="hidden" name="student_id" id="student_id_feedback" value="<?php echo htmlspecialchars($_SESSION["student_id"]); ?>">

                            <div class="row form-group">
                                <div class="col-md-12">
                                    <!-- Updated onclick function -->
                                    <input type="button" onclick="addFeedback(this.form)" value="Send Feedback" class="btn btn-primary py-2 px-4 text-white">
                                </div>
                            </div>
                        </form>
                    </div>
                <?php else : ?>
                    <!-- IF GUEST: Show "Please log in" message -->
                    <div class="col-md-7 mb-5">
                        <div class="p-5 bg-white rounded shadow text-center">
                            <h4 class="text-primary">Want to submit feedback?</h4>
                            <p class="lead">Please log in to your student account to send us feedback or suggestions.</p>
                            <a href="admin/login.php" class="btn btn-primary py-2 px-4 text-white">Log In</a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Contact Info Box (Visible to all) -->
                <div class="col-md-5">
                    <div class="p-4 mb-3 bg-white rounded shadow">
                        <p class="mb-2 font-weight-bold">Address</p>
                        <p class="mb-4"><?php echo htmlspecialchars($res["company_address"] ?? 'College Address'); ?></p>

                        <p class="mb-2 font-weight-bold">Phone</p>
                        <p class="mb-4"><a href="tel:<?php echo htmlspecialchars($res["company_phone"] ?? ''); ?>"><?php echo htmlspecialchars($res["company_phone"] ?? 'College Phone'); ?></a></p>

                        <p class="mb-2 font-weight-bold">Email Address</p>
                        <p class="mb-0"><a href="mailto:<?php echo htmlspecialchars($res["company_email"] ?? ''); ?>"><?php echo htmlspecialchars($res["company_email"] ?? 'College Email'); ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "pages/footer.php"; ?>

    <!-- Standard JS Includes -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/bootstrap-datepicker.min.js"></script>
    <script src="js/aos.js"></script>

    <!-- Custom JS files (loaded via pages/header.php -> assets.php) -->
    <!-- <script src="admin/assets/js/include/validation.js"></script> -->
    <!-- <script src="admin/assets/js/include/alerts.js"></script> -->
    <!-- <script src="admin/assets/js/include/homejs.js"></script> -->

    <script src="js/main.js"></script> <!-- General template JS -->

</body>
</html>