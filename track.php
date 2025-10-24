<!DOCTYPE html>
<html lang="en">
<?php
include "pages/header.php";
include_once "server/inc/get.php";
?>

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
    <?php if (!isset($_SESSION["customer"])) { ?>
      <div class="top-header right">
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
              <a href="admin/signup.php" class="quick-link">Signup</a>
              <a href="admin/login.php" class="quick-link">Login</a>
            </div>
          </div>
          </div>
        </div>
      </div>
    <?php } ?>

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
                <li><a href="index.php" class="nav-link">Track & Trace</a></li>
                <li><a href="request.php" class="nav-link">Request Pick-Up</a></li> 
                <li><a href="index.php#section-about" class="nav-link">About Us</a></li>
                <li><a href="index.php#section-contact" class="nav-link">Contact</a></li>   
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
            <div class="row">
                <div class="col-md-12 mb-10">
    <?php
    // Get the tracking ID from the URL
    if (isset($_GET["tracking_id"])) {
        $tracking_id = $_GET["tracking_id"];
    } else {
         ?>
        <script>
            iziToast.error({
                title: 'Error',
                message: 'Invalid tracking ID. Please try again.',
                onClosing: function(instance, toast, closedBy) {
                    // Redirect after iziToast has closed
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 500);
                }
            });
        </script>
        <?php exit();
    }

    // Get the tracking information from the database
    $getall = getAllTrackingByID($tracking_id);

    if (mysqli_num_rows($getall) > 0) {
        // Fetch the tracking information
        while ($row = mysqli_fetch_assoc($getall)) { ?>
            <article class="card mt-5" style="border: 10px solid #2c3e50">
                <header class="card-header text-white" style="background-color: #2c3e50; border-radius: 0px;">
                    Orders / Tracking 
                </header>
                <div class="card-body">
                    <h6>Tracking ID: #<?php echo $row["tracking_id"]; ?> </h6>
                                <article class="card">
                                    <div class="card-body row">
                                        <div class="col"> <strong>Sender Name:</strong>
                                            <br><?php echo $row[
                                                "sender_name"
                                            ]; ?>
                                        </div>
                                        <div class="col"> <strong>Sender Phone:</strong>
                                            <br><?php echo $row[
                                                "sender_phone"
                                            ]; ?>
                                        </div>
                                        <div class="col"> <strong>Send Location</strong>
                                            <br>
                                            <?php
                                            $getLocation = getAllAreabyID(
                                                $row["send_location"]
                                            );
                                            $row2 = mysqli_fetch_assoc(
                                                $getLocation
                                            );
                                            echo $row2["area_name"];
                                            ?>
                                        </div>
                                    </div>
                                    <div class="card-body row">
                                        <div class="col"> <strong>Receiver Name:</strong>
                                            <br><?php echo $row["rec_name"]; ?>
                                        </div>
                                        <div class="col"> <strong>Receiver Phone:</strong>
                                            <br><?php echo $row["rec_phone"]; ?>
                                        </div>
                                        <div class="col"> <strong>End Location</strong>
                                            <br>
                                            <?php
                                            $getLocation = getAllAreabyID(
                                                $row["end_location"]
                                            );
                                            $row2 = mysqli_fetch_assoc(
                                                $getLocation
                                            );
                                            echo $row2["area_name"];
                                            ?>
                                        </div>
                                    </div>
                                    <div class="card-body row">
                                    <?php if ($row["tracking_status"] == 5): ?>
                                        <div class="col"> <strong>Current Status:</strong>
                                        <br>
                                        <?php if (
                                                $row["tracking_status"] == 5
                                            ) {
                                                echo "Canceled";
                                            } ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="col"> <strong>Price:</strong>
                                            <br><?php echo 'RM ', $row["total_fee"]; ?>
                                        </div>
                                    <?php endif; ?>
                                        <div class="col"> <strong>Weight:</strong>
                                            <br><?php echo $row["weight"]; ?>
                                        </div>
                                        <div class="col"> <strong>Requested Date:</strong>
                                            <br><?php echo $row[
                                                "date_updated"
                                            ]; ?>
                                        </div>
                                    </div>
                                </article>
                                <?php if ($row["tracking_status"] != 5) { ?>
                                        <div class="track">
                                            <div class="step <?php
                                                                if ($row["tracking_status"] == 1) {
                                                                    echo "active pending";
                                                                } elseif ($row["tracking_status"] == 2) {
                                                                    echo "active pickedup";
                                                                } elseif ($row["tracking_status"] == 3) {
                                                                    echo "active shipped";
                                                                } elseif ($row["tracking_status"] == 4) {
                                                                    echo "active delivered";
                                                                }
                                                                ?>">
                                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                                <span class="text">Order Pending</span>
                                            </div>
                                            <div class="step <?php
                                                                if ($row["tracking_status"] == 2) {
                                                                    echo "active pickedup";
                                                                } elseif ($row["tracking_status"] == 3) {
                                                                    echo "active shipped";
                                                                } elseif ($row["tracking_status"] == 4) {
                                                                    echo "active delivered";
                                                                }
                                                                ?>">
                                                <span class="icon"> <i class="fa fa-user"></i> </span>
                                                <span class="text">Order Picked Up</span>
                                            </div>
                                            <div class="step <?php
                                                                if ($row["tracking_status"] == 3) {
                                                                    echo "active shipped";
                                                                } elseif ($row["tracking_status"] == 4) {
                                                                    echo "active delivered";
                                                                }
                                                                ?>">
                                                <span class="icon"> <i class="fa fa-truck"></i> </span>
                                                <span class="text">Order Shipped</span>
                                            </div>
                                            <div class="step <?php
                                                                if ($row["tracking_status"] == 4) {
                                                                    echo "active delivered";
                                                                }
                                                                ?>">
                                                <span class="icon"> <i class="fa fa-box"></i> </span>
                                                <span class="text">Delivered</span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </article>       
                <?php }
    } else {
         ?>
                <script>
                    iziToast.error({
                        title: 'Error',
                        message: 'Invalid tracking ID. Please try again.',
                        position: 'center',
                        onClosing: function(instance, toast, closedBy) {
                            setTimeout(function() {
                                window.location.href = 'index.php';
                            }, 500);
                        }
                    });
                </script>
                <?php exit();
    }
    ?>
            </div>
            </div>
        </div>
    </div>

    <?php include "pages/footer.php"; ?>
    <!-- </div> -->

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

    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');

        body {
            background-color: #eeeeee;
            font-family: 'Open Sans', serif
        }


        .card {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.10rem
        }

        .card-header:first-child {
            border-radius: calc(0.37rem - 1px) calc(0.37rem - 1px) 0 0
        }

        .card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1)
        }

        .track {
            position: relative;
            background-color: #ddd;
            height: 7px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            margin-bottom: 60px;
            margin-top: 50px
        }

        .track .step {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            width: 25%;
            margin-top: -18px;
            text-align: center;
            position: relative
        }
        
        .track .step.active.pending .icon {
        background: #981da6;
        color: #fff
        }

        .track .step.active.pending:before {
            background: #981da6;
        }

        .track .step.active.pickedup .icon {
            background: #217dbb;
            color: #fff
        }

        .track .step.active.pickedup:before {
            background: #217dbb;
        }

        .track .step.active.shipped .icon {
            background: #d2c025;
            color: #fff
        }

        .track .step.active.shipped:before {
            background: #d2c025;
        }

        .track .step.active.delivered .icon {
            background: #2da81d;
            color: #fff
        }

        .track .step.active.delivered:before {
            background: #2da81d;
        }


        .track .step.active:before {
            background: #2c3e50
        }

        .track .step::before {
            height: 7px;
            position: absolute;
            content: "";
            width: 100%;
            left: 0;
            top: 18px
        }

        .track .step.active .icon {
            background: #2c3e50;
            color: #fff
        }

        .track .icon {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            position: relative;
            border-radius: 100%;
            background: #ddd
        }

        .track .step.active .text {
            font-weight: 400;
            color: #000
        }

        .track .text {
            display: block;
            margin-top: 7px
        }

        .itemside {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            width: 100%
        }

        .itemside .aside {
            position: relative;
            -ms-flex-negative: 0;
            flex-shrink: 0
        }

        .img-sm {
            width: 80px;
            height: 80px;
            padding: 7px
        }

        ul.row,
        ul.row-sm {
            list-style: none;
            padding: 0
        }

        .itemside .info {
            padding-left: 15px;
            padding-right: 7px
        }

        .itemside .title {
            display: block;
            margin-bottom: 5px;
            color: #212529
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem
        }

        .btn-warning {
            color: #ffffff;
            background-color: #2c3e50;
            border-color: #2c3e50;
            border-radius: 1px
        }

        .btn-warning:hover {
            color: #ffffff;
            background-color: #ff2b00;
            border-color: #ff2b00;
            border-radius: 1px
        }
    </style>
</body>

</html>