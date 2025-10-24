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
                <li><a href="profile.php" class="nav-link">Profile</a></li> 
            </ul>
          </nav>
        </div>
                <div class="d-inline-block d-xl-none ml-md-0 mr-auto py-3" style="position: relative; top: 3px;"><a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu h3"></span></a></div>

            </div>
        </div>
        </div>

    </header>

    <div class="site-section bg-light" id="section-contact">
        <div class="container">
            <div class="row justify-content-center mb-5" style="margin-top: 5rem;">
                <div class="col-md-10 text-center border-primary">
                    <h2 class="font-weight-light text-primary">Edit Profile - Change Email</h2>
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
                                    <form method="POST" class="row g-3 needs-validation" novalidate
                                          enctype="multipart/form-data">
                                        <div class="col-md-12 mt-2">
                                            <label for="current_email" class="form-label">Current Email Address</label>
                                            <input type="email" class="form-control" name="current_email" id="current_email"
                                                   placeholder="Current Email Address" required>
                                        </div>

                                        <div class="col-md-12 mt-2">
                                            <label for="new_email" class="form-label">New Email Address</label>
                                            <input type="email" class="form-control" name="new_email" id="new_email"
                                                   placeholder="New Email Address" required>
                                        </div>

                                        <div class="col-md-12 mt-2">
                                            <input type="hidden" class="form-control" name="customer_id"
                                                   value="<?php echo $_SESSION["customer"]; ?>" id="customer_id">

                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <button type="button" onclick="changeEmail(this.form)" class="btn btn-info">Save changes</button>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <a href="profile.php" class="btn btn-primary" data-bs-dismiss="modal">Back to Profile</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "pages/footer.php"; ?>

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