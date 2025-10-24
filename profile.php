<!DOCTYPE html>
<html lang="en">
<?php include "pages/header.php"; ?>
<?php include "auth.php"; ?>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="200">

    <!-- <div class="site-wrap"> -->

    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close mt-3">
                <span class="icon-close2 js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>

    <header class="site-navbar js-site-navbar site-navbar-target" role="banner" id="site-navbar">
        <!-- Top Header for Quick Links -->
        <div class="top-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dropdown-container float-right">
                            <button class="dropdown-toggle btn btn-translate" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="server/uploads/settings/Translate.png" alt="Translate" style="max-width: 30px;">
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="?lang=en">English</a>
                            </div>
                        </div>
                        <a href="admin/logout.php" class="quick-link">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
        <div class="row align-items-center">
            <div class="col-11 col-xl-2 site-logo">
                <a href="index.php" class="text-white h5 mb-0">
                <img src="server/uploads/settings/logo.png" alt="Logo" style="max-width: 130px;">
                </a>
            </div>
            <div class="col-12 col-md-10 d-none d-xl-block">
                <nav class="site-navigation position-relative text-right" role="navigation">
                    <ul class="site-menu js-clone-nav mx-auto d-none d-lg-block">
                        <li><a href="index.php" class="nav-link">Home</a></li>
                        <li><a href="tracking.php" class="nav-link">Track & Trace</a></li>
                        <li><a href="request.php" class="nav-link">Request Pick-Up</a></li> 
                        <li><a href="profile.php" class="nav-link">Edit Profile</a></li> 
                    </ul>
                </nav>
            </div>
            <div class="d-inline-block d-xl-none ml-md-0 mr-auto py-3" style="position: relative; top: 3px;">
                <a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu h3"></span></a>
            </div>
        </div>
        </div>
    </header>

    <div class="site-section bg-light" id="section-contact">
        <div class="container">
            <div class="row justify-content-center mb-5" style="margin-top: 5rem;">
                <div class="col-md-10 text-center border-primary">
                    <h2 class="font-weight-light text-primary">Edit Profile</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-5">
                    <?php
                    $getall = getAllcustomerById($_SESSION["customer"]);
                    $row = mysqli_fetch_assoc($getall);
                    $customer_id = $row["customer_id"];
                    ?>
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="d-flex flex-column justify-content-center bg-red h-100 p-5">
                                <div class="d-inline-flex border border-white p-2 mb-4">
                                    <h1 class="font-weight-normal text-secondary m-0 mr-3"></h1>
                                    <div class="d-flex flex-column">
                                        <h4 class="text-info">Full Name</h4>
                                        <p class="m-0 text-white"><?php echo $row[
                                            "name"
                                        ]; ?></p>
                                    </div>
                                </div>
                                <div class="d-inline-flex border border-white p-2 mb-4">
                                    <h1 class="font-weight-normal text-secondary m-0 mr-3"></h1>
                                    <div class="d-flex flex-column">
                                        <h4 class="text-info">Email</h4>
                                        <p class="m-0 text-white"><?php echo $row[
                                            "email"
                                        ]; ?></p>
                                    </div>
                                </div>
                                <div class="d-inline-flex border border-white p-2 mb-4">
                                    <h1 class="font-weight-normal text-secondary m-0 mr-3"></h1>
                                    <div class="d-flex flex-column">
                                        <h4 class="text-info">Phone Number</h4>
                                        <p class="m-0 text-white"><?php echo $row[
                                            "phone"
                                        ]; ?></p>
                                    </div>
                                </div>
                                <div class="d-inline-flex border border-white p-2 mb-4">
                                    <h1 class="font-weight-normal text-secondary m-0 mr-3"></h1>
                                    <div class="d-flex flex-column">
                                        <h4 class="text-info">Address</h4>
                                        <p class="m-0 text-white"><?php echo $row[
                                            "address"
                                        ]; ?></p>
                                    </div>
                                </div>
                                <div class="d-inline-flex border border-white p-2 mb-4">
                                    <h1 class="font-weight-normal text-secondary m-0 mr-3"></h1>
                                    <div class="d-flex flex-column">
                                        <h4 class="text-info">Gender</h4>
                                        <p class="m-0 text-white">
                                            <?php if ($row["gender"] == "1") {
                                                echo "Male";
                                            } else {
                                                echo "Female";
                                            } ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 mb-5 my-lg-5 py-5 pl-lg-5">
                        <div class="contact-form">
                            <div id="success"></div>
                            <div class="col-md-12 border-right">
                                <div class="p-3 py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="text-right">Profile Settings</h4>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center experience">
                                        <a class="border px-3 p-1 add-experience" id="change" href="change_email.php">
                                            <i class="fa fa-lock"></i>&nbsp;Change Email
                                        </a>
                                        <a href="change_password.php" class="border px-3 p-1 add-experience">
                                            <i class="fa fa-lock"></i>&nbsp;Change Password
                                        </a>
                                        <button class="border px-3 p-1 add-experience" onclick="deleteDataFromHome(<?php echo ($row['customer_id']); ?>, 'customer', 'customer_id')">
                                            <i class="fa fa-trash"></i>&nbsp;Delete
                                        </button>
                                    </div>
                                    <br>
                                    <form method="post" class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                                        <div class="col-md-12 mt-2">
                                            <label for="new_name" class="form-label">Name</label>
                                            <input type="text" class="form-control" name="new_name" id="new_name" placeholder="Your name" value="<?php echo $row["name"]; ?>" required>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label for="new_phone" class="form-label">Mobile Number</label>
                                            <input type="text" class="form-control" name="new_phone" id="new_phone" placeholder="Enter phone number" value="<?php echo $row["phone"]; ?>" required>
                                        </div>

                                        <div class="col-md-12 mt-2">
                                            <label for="new_address" class="form-label">Address</label>
                                            <input type="text" class="form-control" name="new_address" id="new_address" placeholder="Enter address" value="<?php echo $row["address"]; ?>" required>
                                        </div>

                                        <div class="col-md-12 mt-2">
                                            <label for="new_gender" class="form-label">Gender</label>
                                            <select id="new_gender" class="form-control norad tx12" name="new_gender" required>
                                                <option value="1" <?php if ($row["gender"] == "1") echo "selected"; ?>>Male</option>
                                                <option value="2" <?php if ($row["gender"] == "2") echo "selected"; ?>>Female</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 mt-2">
                                            <input type="hidden" class="form-control" name="customer_id" value="<?php echo $_SESSION["customer"]; ?>" id="customer_id">
                                        </div>

                                        <div class="col-md-12 mt-2">
                                        <button id="saveChangesBtn" type="button" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "pages/footer.php"; ?>
    <!-- </div> -->
    <script>
        // Event listener for Save Changes button
        document.addEventListener("DOMContentLoaded", function() {
            var saveChangesBtn = document.getElementById('saveChangesBtn');
            if (saveChangesBtn) {
                saveChangesBtn.addEventListener('click', function() {
                    var customerId = '<?php echo $_SESSION["customer"]; ?>';
                    profileUpdate(customerId);
                });
            } else {
                console.error('Element with id "saveChangesBtn" not found.');
            }
        });
    </script>
    
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

    <script src="js/main.js"></script>

</body>

</html>