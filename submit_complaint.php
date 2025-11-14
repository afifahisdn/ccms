<!DOCTYPE html>
<html lang="en">
<?php
/*
* submit_complaint.php
*
* Form for logged-in students to submit a new complaint.
* Requires auth.php.
*/

// Includes session_start, db connection, settings fetch ($res)
include "pages/header.php";
// Includes student session check and fetches $student_data and $student_id (VARCHAR)
include "auth.php";
// Make sure get.php is included (it's also in header.php, but include_once is safe)
include_once "server/inc/get.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint - CCMS</title>
    <!-- CSS is included from pages/header.php -->
    <!-- Font Awesome for icons (included via pages/assets.php) -->
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
                            <li><a href="submit_complaint.php" class="nav-link active">Submit Complaint</a></li> <!-- Active Link -->
                            <li><a href="profile.php" class="nav-link">Profile</a></li>
                            <li><a href="index.php#section-contact" class="nav-link">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content: Complaint Form -->
    <div class="site-section bg-light" id="section-submit-complaint">
        <div class="container">
            <div class="row justify-content-center mb-5" style="margin-top: 5rem;">
                <div class="col-md-8 text-center border-primary">
                    <h2 class="font-weight-light text-primary">Submit a New Complaint</h2>
                    <p class="lead">Please provide details about the issue you are experiencing.</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10 mb-5">
                    <!-- Add enctype for file uploads -->
                    <form class="p-5 bg-white rounded shadow needs-validation" method="post" enctype="multipart/form-data" novalidate onsubmit="return false;">

                        <h4>Complaint Details</h4>
                        <hr class="mb-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-black" for="dormitory_id">Dormitory <span class="text-danger">*</span></label>
                                <select id="dormitory_id" class='form-control' name="dormitory_id" required>
                                    <option value="">-- Select Dormitory --</option>
                                    <?php
                                    // Use new function
                                    $getallDorms = getAllDormitory();
                                    if ($getallDorms) {
                                        while ($dorm_row = mysqli_fetch_assoc($getallDorms)) { ?>
                                            <option value="<?php echo $dorm_row["dormitory_id"]; ?>">
                                                <?php echo htmlspecialchars($dorm_row["dormitory_name"]); ?> (<?php echo htmlspecialchars($dorm_row["dormitory_code"]); ?>)
                                            </option>
                                    <?php }
                                    } ?>
                                </select>
                                <div class="invalid-feedback">Please select a dormitory.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-black" for="room_number">Room Number <span class="text-danger">*</span></label>
                                <!-- Pre-fill from session data -->
                                <input type="text" name="room_number" id="room_number" class="form-control" value="<?php echo htmlspecialchars($student_data['room_number'] ?? ''); ?>" placeholder="e.g., A-101" required>
                                <div class="invalid-feedback">Please enter your room number.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="text-black" for="complaint_title">Complaint Title <span class="text-danger">*</span></label>
                                <input type="text" name="complaint_title" id="complaint_title" class="form-control" placeholder="Brief summary, e.g., Leaking Faucet in Bathroom" required>
                                <div class="invalid-feedback">Please provide a brief title.</div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- === UPDATED CATEGORY DROPDOWN === -->
                            <div class="col-md-6 mb-3">
                                <label class="text-black" for="category_id">Category <span class="text-danger">*</span></label>
                                <!-- The name is now "category_id" -->
                                <select id="category_id" class='form-control' name="category_id" required>
                                    <option value="">-- Select Category --</option>
                                    <?php
                                    // Use new function to get categories from DB
                                    $getallCategories = getAllCategories();
                                    if ($getallCategories) {
                                        while ($cat_row = mysqli_fetch_assoc($getallCategories)) {
                                            echo '<option value="' . $cat_row["category_id"] . '">'
                                                . htmlspecialchars(ucfirst($cat_row["category_name"]))
                                                . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">Please select a category.</div>
                            </div>
                            <!-- Urgency Level Dropdown -->
                            <div class="col-md-6 mb-3">
                                <label class="text-black" for="urgency_level">Urgency Level <span class="text-danger">*</span></label>
                                <select id="urgency_level" class='form-control' name="urgency_level" required>
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group mb-3">
                            <div class="col-md-12">
                                <label class="text-black" for="complaint_description">Description</label>
                                <textarea name="complaint_description" id="complaint_description" cols="30" rows="5" class="form-control" placeholder="Please provide as much detail as possible about the issue..."></textarea>
                            </div>
                        </div>

                        <div class="row form-group mb-4">
                            <div class="col-md-12">
                                <label class="text-black" for="photo">Upload Photo (Optional)</label>
                                <input type="file" name="photo" id="photo" class="form-control-file">
                                <small class="form-text text-muted">Max file size: 5MB. Allowed types: JPG, PNG, GIF.</small>
                            </div>
                        </div>

                        <!-- Hidden student_id (VARCHAR PK) -->
                        <input type="hidden" name="student_id" id="student_id" value="<?php echo htmlspecialchars($_SESSION["student_id"]); ?>">

                        <div class="row form-group mt-4">
                            <div class="col-md-12 text-center">
                                <!-- Call addComplaint from homejs.js -->
                                <input type="button" onclick="addComplaint(this.form)" value="Submit Complaint" class="btn btn-primary btn-lg py-3 px-5 text-white">
                            </div>
                        </div>
                    </form>
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

    <!-- Inline script for form validation -->
    <script>
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    var submitButton = form.querySelector('input[type="button"][onclick^="addComplaint"]');
                    if (submitButton) {
                        submitButton.addEventListener('click', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                                if (typeof errorMessage === 'function') {
                                    errorMessage('Please fill out all required fields correctly.');
                                }
                            }
                            form.classList.add('was-validated')
                        }, false)
                    }
                })
        })()
    </script>

</body>
</html>