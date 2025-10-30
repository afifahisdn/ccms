<!DOCTYPE html>
<html lang="en">
<?php
/*
* change_password.php
* Page for students to change their password.
* Includes header and auth check.
*/

// Includes session_start, db connection, settings fetch ($res)
include "pages/header.php";
// Includes student session check and fetches $student_data
include "auth.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - CCMS</title>
    <!-- Head content (CSS) is included via pages/header.php -->
    <!-- Font Awesome for icons (included via pages/assets.php) -->
    <!-- iziToast CSS (included via pages/assets.php) -->
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
                        <img src="server/uploads/settings/<?php echo htmlspecialchars($res['header_image'] ?? 'logo.png'); ?>" alt="CCMS Logo" style="max-width: 130px;">
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

    <div class="site-section bg-light" id="section-change-password">
        <div class="container">
            <div class="row justify-content-center mb-5" style="margin-top: 5rem;">
                <div class="col-md-10 text-center border-primary">
                    <h2 class="font-weight-light text-primary">Edit Profile - Change Password</h2>
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
                                        <p><?php echo htmlspecialchars($student_data["student_id_number"]); ?></p>
                                    </div>
                                    <div class="profile-info-item">
                                        <strong class="text-info">Email:</strong>
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
                            <!-- Right Side: Change Password Form -->
                            <div class="col-lg-7">
                                <div class="p-4 bg-white rounded shadow-sm">
                                    <h4 class="text-primary mb-4">Update Password</h4>
                                    <form method="POST" class="needs-validation" novalidate>
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Enter your current password" required>
                                            <div class="invalid-feedback">Please enter your current password.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter a new password" required minlength="6">
                                            <div class="invalid-feedback">Password must be at least 6 characters.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="confirm_new_password" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="confirm_new_password" id="confirm_new_password" placeholder="Confirm your new password" required>
                                            <div class="invalid-feedback">Passwords do not match.</div>
                                        </div>

                                        <!-- Hidden student_id from auth.php -->
                                        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>" id="student_id">

                                        <div class="mt-4 d-flex justify-content-between">
                                            <a href="profile.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Profile</a>
                                            <!-- Call changeStudentPassword from homejs.js -->
                                            <button type="button" onclick="changeStudentPassword(this.form)" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
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

    <!-- JS Includes (jQuery, iziToast, SweetAlert are in assets.php) -->
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
    <!-- <script src="admin/assets/plugin/iziToast-master/dist/js/iziToast.min.js"></script> -->
    <!-- <script src="admin/assets/js/include/alerts.js"></script> -->
    <!-- <script src="admin/assets/js/include/validation.js"></script> -->
    <!-- <script src="admin/assets/js/include/homejs.js"></script> <!-- Contains changeStudentPassword -->
    
    <script src="js/main.js"></script> <!-- General template JS -->

    <!-- Inline Styles -->
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
        }

        .profile-info-item p {
            margin-bottom: 0;
            word-wrap: break-word; /* Ensure long text wraps */
        }

        .bg-dark {
            background-color: #343a40 !important;
        }
    </style>

    <!-- Simple JS for password match validation feedback -->
    <script>
        (function() {
            'use strict'

            // Add confirm password validation
            var newPassword = document.getElementById('new_password');
            var confirmPassword = document.getElementById('confirm_new_password');

            function validatePassword() {
                if (newPassword.value != confirmPassword.value) {
                    confirmPassword.setCustomValidity("Passwords Don't Match");
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }

            if (newPassword && confirmPassword) {
                newPassword.onchange = validatePassword;
                confirmPassword.onkeyup = validatePassword;
            }

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    // Add listener to the SAVE button instead of form submit
                    var saveButton = form.querySelector('button[onclick^="changeStudentPassword"]');
                    if (saveButton) {
                        saveButton.addEventListener('click', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                                // Optionally show an alert or focus the first invalid field
                                if (typeof errorMessage === 'function') { // Check if alerts.js is loaded
                                    errorMessage('Please correct the errors in the form.');
                                } else {
                                    alert('Please correct the errors in the form.');
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

