<!DOCTYPE html>
<html lang="en">
<?php
/*
* profile.php
* Allows a logged-in student to view and update their profile information.
* Also links to change_email.php and change_password.php.
*/

// Includes session_start, db connection, settings fetch ($res)
include "pages/header.php";
// Includes student session check and fetches $student_data and $student_id
include "auth.php";
// Make sure get.php is included (it's also in header.php, but include_once is safe)
include_once "server/inc/get.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - CCMS</title>
    <!-- CSS is included from pages/header.php -->
    <!-- Font Awesome for icons (included via pages/assets.php) -->
    <!-- iziToast CSS (included via pages/assets.php) -->

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
            color: #17a2b8;
            /* text-info */
        }

        .profile-info-item p {
            margin-bottom: 0;
            color: #fff;
            /* text-white */
        }

        .bg-dark {
            /* Using dark color for the info box */
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
                        <a href="admin/logout.php" class="quick-link d-inline-block">Logout</a> <!-- Link to student logout -->
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

    <div class="site-section bg-light" id="section-profile">
        <div class="container">
            <div class="row justify-content-center mb-5" style="margin-top: 5rem;">
                <div class="col-md-10 text-center border-primary">
                    <h2 class="font-weight-light text-primary">Edit Profile</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-5">
                    <?php
                    // Data is already fetched in auth.php as $student_data
                    // $student_id is also available from auth.php
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
                                    <div class="profile-info-item">
                                        <strong class="text-info">Address:</strong>
                                        <p><?php echo htmlspecialchars($student_data["address"]); ?></p>
                                    </div>
                                    <div class="profile-info-item">
                                        <strong class="text-info">Gender:</strong>
                                        <p><?php echo ($student_data["gender"] == "1") ? "Male" : "Female"; ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side: Edit Form -->
                            <div class="col-lg-7">
                                <div class="p-4 bg-white rounded shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="text-primary">Profile Settings</h4>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteDataFromHome(<?php echo $student_id; ?>, 'student', 'student_id')">
                                            <i class="fa fa-trash"></i> Delete Account
                                        </button>
                                    </div>

                                    <div class="mb-4 text-center">
                                        <a class="btn btn-outline-secondary mr-2" href="change_email.php">
                                            <i class="fa fa-envelope"></i> Change Email
                                        </a>
                                        <a href="change_password.php" class="btn btn-outline-secondary">
                                            <i class="fa fa-lock"></i> Change Password
                                        </a>
                                    </div>

                                    <hr class="mb-4">

                                    <form method="post" class="needs-validation" novalidate>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="new_name" class="form-label">Name</label>
                                                <input type="text" class="form-control" name="new_name" id="new_name" placeholder="Your name" value="<?php echo htmlspecialchars($student_data["name"]); ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="student_id_number" class="form-label">Student ID Number</label>
                                                <input type="text" class="form-control" name="student_id_number" id="student_id_number" placeholder="e.g., STU12345" value="<?php echo htmlspecialchars($student_data["student_id_number"]); ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="new_phone" class="form-label">Mobile Number</label>
                                                <input type="tel" class="form-control" name="new_phone" id="new_phone" placeholder="e.g., 0123456789" value="<?php echo htmlspecialchars($student_data["phone"]); ?>" required pattern="[0-9]{10,11}">
                                                <small class="form-text text-muted">Enter 10 or 11 digits.</small>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="new_address" class="form-label">Address</label>
                                                <textarea class="form-control" name="new_address" id="new_address" placeholder="Enter full address" rows="3" required><?php echo htmlspecialchars($student_data["address"]); ?></textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="room_number" class="form-label">Room Number</label>
                                                <input type="text" class="form-control" name="room_number" id="room_number" placeholder="e.g., B-205" value="<?php echo htmlspecialchars($student_data["room_number"]); ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="new_gender" class="form-label">Gender</label>
                                                <select id="new_gender" class="form-control" name="new_gender" required>
                                                    <option value="1" <?php if ($student_data["gender"] == "1") echo "selected"; ?>>Male</option>
                                                    <option value="2" <?php if ($student_data["gender"] == "2") echo "selected"; ?>>Female</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Hidden student_id -->
                                        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>" id="student_id">

                                        <div class="mt-4 text-center">
                                            <!-- Call updateStudentProfile from homejs.js -->
                                            <button id="saveChangesBtn" type="button" class="btn btn-primary btn-lg px-5">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php } // End else for student_data check 
                        ?>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "pages/footer.php"; ?>

    <!-- JavaScript for Save Changes -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var saveChangesBtn = document.getElementById('saveChangesBtn');
            if (saveChangesBtn) {
                saveChangesBtn.addEventListener('click', function() {
                    var studentId = document.getElementById('student_id').value; // Get ID from hidden input
                    // Call the updated function name from homejs.js
                    updateStudentProfile(studentId);
                });
            } else {
                console.error('Element with id "saveChangesBtn" not found.');
            }
        });
    </script>

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
    <!-- <script src="admin/assets/js/include/alerts.js"></script> -->
    <!-- <script src="admin/assets/js/include/validation.js"></script> -->
    <!-- <script src="admin/assets/js/include/homejs.js"></script> -->
    <!-- <script src="admin/assets/js/include/delete.js"></script> -->

    <script src="js/main.js"></script> <!-- General template JS -->

</body>
</html>