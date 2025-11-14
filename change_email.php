<!DOCTYPE html>
<html lang="en">
<?php
/*
* change_email.php
*
* Form for logged-in students to change their email.
*/

// Includes session_start, db connection, settings fetch ($res)
include "pages/header.php";
// Includes student session check and fetches $student_data and $student_id (VARCHAR)
include "auth.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Email - CCMS</title>
    <!-- CSS is included from pages/header.php -->
    <!-- Font Awesome for icons (included via pages/assets.php) -->

    <!-- Inline Styles for Profile Info -->
    <style>
        .profile-info-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 0.75rem;
            margin-bottom: 0.75rem;
        }
        .profile-info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .profile-info-item strong {
            display: block;
            margin-bottom: 0.25rem;
            font-size: 0.9em;
            opacity: 0.8;
            color: #17a2b8; /* text-info */
        }
        .profile-info-item p {
            margin-bottom: 0;
            color: #fff; /* text-white */
        }
        .bg-dark {
            background-color: #343a40 !important;
        }
    </style>
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
        <!-- Top Header -->
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
                        <a href="admin/logout.php" class="quick-link d-inline-block">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Navigation -->
        <div class="container main-nav-container">
            <div class="row align-items-center">
                <div class="col-6 col-xl-2 site-logo">
                    <a href="index.php" class="text-white h5 mb-0">
                        <img src="server/uploads/settings/logo.png" alt="CCMS Logo" style="max-width: 70px; padding: 8px;">
                    </a>
                </div>
                <div class="col-6 col-md-10 d-xl-none text-right">
                    <a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu h3"></span></a>
                </div>
                <div class="col-12 col-xl-10 d-none d-xl-block">
                    <nav class="site-navigation position-relative text-right" role="navigation">
                        <ul class="site-menu js-clone-nav mx-auto d-none d-lg-block">
                            <li><a href="index.php#section-customer" class="nav-link">Home</a></li>
                            <li><a href="complaints.php" class="nav-link">My Complaints</a></li>
                            <li><a href="submit_complaint.php" class="nav-link">Submit Complaint</a></li>
                            <li><a href="profile.php" class="nav-link active">Profile</a></li> <!-- Active Link -->
                            <li><a href="index.php#section-contact" class="nav-link">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <div class="site-section bg-light" id="section-change-email">
        <div class="container">
            <div class="row justify-content-center mb-5" style="margin-top: 5rem;">
                <div class="col-md-10 text-center border-primary">
                    <h2 class="font-weight-light text-primary">Edit Profile - Change Email</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-5">
                    <?php
                    // Data is already fetched in auth.php as $student_data
                    if (!$student_data) {
                        echo "<p class='text-danger text-center'>Could not load student data.</p>";
                    } else {
                    ?>
                        <div class="row">
                            <!-- Left Side: Display Info -->
                            <div class="col-lg-5 mb-4 mb-lg-0">
                                <div class="p-4 bg-dark text-white rounded shadow-sm h-100 d-flex flex-column">
                                    <h4 class="text-info mb-4">Current Information</h4>
                                    <div class="profile-info-item">
                                        <strong class="text-info">Full Name:</strong>
                                        <p><?php echo htmlspecialchars($student_data["name"]); ?></p>
                                    </div>
                                    <div class="profile-info-item">
                                        <strong class="text-info">Student ID:</strong>
                                        <p><?php echo htmlspecialchars($student_data["student_id"]); ?></p>
                                    </div>
                                    <div class="profile-info-item">
                                        <strong class="text-info">Current Email:</strong>
                                        <p><?php echo htmlspecialchars($student_data["email"]); ?></p>
                                    </div>
                                    <div class="profile-info-item">
                                        <strong class="text-info">Phone Number:</strong>
                                        <p><?php echo htmlspecialchars($student_data["phone"]); ?></p>
                                    </div>
                                    <div class="profile-info-item">
                                        <strong class="text-info">Room Number:</strong>
                                        <p><?php echo htmlspecialchars($student_data["room_number"]); ?></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Right Side: Change Email Form -->
                            <div class="col-lg-7">
                                <div class="p-4 bg-white rounded shadow-sm">
                                    <h4 class="text-primary mb-4">Update Email Address</h4>
                                    <form method="POST" class="needs-validation" novalidate onsubmit="return false;">
                                        <div class="mb-3">
                                            <label for="current_email" class="form-label">Verify Current Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="current_email" id="current_email" placeholder="Enter your current email" required>
                                            <div class="invalid-feedback">Please enter your current email.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="new_email" class="form-label">New Email Address (@college.edu) <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="new_email" id="new_email" placeholder="Enter your new @college.edu email" required>
                                            <div class="invalid-feedback">Please enter a valid new email address.</div>
                                        </div>

                                        <!-- Hidden student_id (VARCHAR PK) -->
                                        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>" id="student_id">

                                        <div class="mt-4 d-flex justify-content-between">
                                            <a href="profile.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Profile</a>
                                            <!-- Call changeStudentEmail from homejs.js -->
                                            <button type="button" onclick="changeStudentEmail(this.form)" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } // End else for student_data check 
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include "pages/footer.php"; ?>

    <!-- JS Includes (loaded via pages/header.php -> assets.php) -->
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

    <!-- Custom JS files (loaded via assets.php) -->
    <!-- <script src="admin/assets/js/include/alerts.js"></script> -->
    <!-- <script src="admin/assets/js/include/validation.js"></script> -->
    <!-- <script src="admin/assets/js/include/homejs.js"></script> -->

    <script src="js/main.js"></script> <!-- General template JS -->

    <!-- Simple JS for form validation -->
    <script>
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    var saveButton = form.querySelector('button[onclick^="changeStudentEmail"]');
                    if (saveButton) {
                        saveButton.addEventListener('click', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                                if (typeof errorMessage === 'function') {
                                    errorMessage('Please fill out all fields correctly.');
                                }
                            }
                            form.classList.add('was-validated')
                        }, false);
                    }
                })
        })()
    </script>

</body>

</html>