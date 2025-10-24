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
                <div class="d-inline-block d-xl-none ml-md-0 mr-auto py-3" style="position: relative; top: 3px;"><a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu h3"></span></a></div>

            </div>
        </div>
        </div>

    </header>

    <div class="site-section bg-light" id="section-contact">
        <div class="container">
            <div class="row justify-content-center mb-5" style="margin-top: 5rem;">
                <div class="col-md-7 text-center border-primary">
                    <h2 class="font-weight-light text-primary">Make Request</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-5">
                    <form action="#" class="p-5 bg-white" method="post">
                    <h4>Parcel Details</h4>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="row form-group">
                                    <div class="col-md-12 mb-3 mb-md-0">
                                        <label class="text-black" for="fname">Sending Location</label>
                                        <select id="send_location" class='form-control norad tx12' name="send_location" type='text'>
                                            <option>Please Select</option>
                                            <?php
                                            $getall = getAllArea();
                                            while (
                                                $row = mysqli_fetch_assoc(
                                                    $getall
                                                )
                                            ) { ?>
                                                <option value="<?php echo $row[
                                                    "area_id"
                                                ]; ?>">
                                                    <?php echo $row[
                                                        "area_name"
                                                    ]; ?>
                                                </option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row form-group">
                                    <div class="col-md-12 mb-3 mb-md-0">
                                        <label class="text-black" for="fname">Pick Up Location</label>
                                        <select id="end_location" onchange="calculation(this)" class='form-control norad tx12' name="end_location" type='text'>
                                            <option>Please Select</option>
                                            <?php
                                            $getall = getAllArea();
                                            while (
                                                $row = mysqli_fetch_assoc(
                                                    $getall
                                                )
                                            ) { ?>
                                                <option value="<?php echo $row[
                                                    "area_id"
                                                ]; ?>">
                                                    <?php echo $row[
                                                        "area_name"
                                                    ]; ?>
                                                </option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row form-group">
                                    <div class="col-md-12 mb-3 mb-md-0">
                                        <label class="text-black" for="weight">Weight</label>
                                        <input type="number" name="weight" id="weight" class="form-control"  onblur="calculation()">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top: 2.2rem">
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-2">
                                                Price :
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" disabled name="total" id="total" class="form-control">
                                                <input type="hidden" name="total_fee" id="total_fee" class="form-control">
                                                <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $_SESSION[
                                                    "customer"
                                                ]; ?>" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4 class="mt-5">Sender Details</h4>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="row form-group">
                                    <div class="col-md-12 mb-3 mb-md-0">
                                        <label class="text-black" for="sender_name">Sender Name</label>
                                        <input type="text" name="sender_name" id="sender_name" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row form-group">
                                    <div class="col-md-12 mb-3 mb-md-0">
                                        <label class="text-black" for="send_phone">Phone Number</label>
                                        <input type="text" name="sender_phone" id="sender_phone" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="rec_address">Sender Address</label>
                                <textarea name="sender_address" id="sender_address" cols="30" rows="7" class="form-control" onblur="validateSenderDetails()"></textarea>
                            </div>
                            </div>
                        <h4 class="mt-5">Receiver Details</h4>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="row form-group">
                                    <div class="col-md-12 mb-3 mb-md-0">
                                        <label class="text-black" for="rec_name">Receiver Name</label>
                                        <input type="text" name="rec_name" id="rec_name" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row form-group">
                                    <div class="col-md-12 mb-3 mb-md-0">
                                        <label class="text-black" for="rec_phone">Phone Number</label>
                                        <input type="text" name="rec_phone" id="rec_phone" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="rec_address">Receiver Address</label>
                                <textarea name="rec_address" id="rec_address" cols="30" rows="7" class="form-control" onblur="validateReceiverDetails()"></textarea>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <input type="button" onclick="addRequest(this.form)" value="Send Request" class="btn btn-primary py-2 px-4 text-white">
                            </div>
                        </div>
                    </form>
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