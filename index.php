<!DOCTYPE html>
<html lang="en">
<?php include "pages/header.php"; ?>
<?php // Check if the user has submitted the tracking form
  if (isset($_POST["tracking_id"])) {
      // Get the tracking ID from the POST request
      $tracking_id = $_POST["tracking_id"];

      // Redirect the user to the track.php file
      header("Location: track.php?tracking_id=$tracking_id");
      exit();
  } ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script>
        // Prevent back button navigation after logout
        if (window.history && window.history.pushState) {
            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, null, window.location.href);
            };
        }
    </script>
</head>
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
                          <a href="admin/register.php" class="quick-link">Signup</a>
                          <a href="admin/login.php" class="quick-link">Login</a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  <?php } else { ?>
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
              <?php if (isset($_SESSION["customer"])): ?>
                <li><a href="#section-home" class="nav-link">Home</a></li>
                <li><a href="tracking.php" class="nav-link">Track & Trace</a></li>
                <li><a href="request.php" class="nav-link">Request Pick-Up</a></li> 
                <li><a href="profile.php" class="nav-link">Edit Profile</a></li> 
                <li><a href="#section-contact" class="nav-link">Contact</a></li>
              <?php else: ?>
                <li><a href="#section-home" class="nav-link">Track & Trace</a></li>
                <li><a href="admin/login.php" class="nav-link">Request Pick-Up</a></li> 
                <li><a href="#section-about" class="nav-link">About Us</a></li>
                <li><a href="#section-contact" class="nav-link">Contact</a></li>
              <?php endif; ?>
            </ul>
          </nav>
        </div>

        <div class="d-inline-block d-xl-none ml-md-0 mr-auto py-3" style="position: relative; top: 3px;"><a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu h3"></span></a></div>

      </div>

    </div>
    </div>

  </header>
  
  <?php if (isset($_SESSION["customer"])) { ?>
  <div class="site-blocks-cover overlay" style="background-image: url(<?php echo $subheader_src; ?>);" data-aos="fade" data-stellar-background-ratio="0.5" id="section-customer">
  <div class="container">
  <div class="row align-items-center justify-content-center text-center">
    <div class="col-md-12" data-aos="fade-up" data-aos-delay="400">
    <div class="index-margin">
      <div class="text-center pb-1 border-primary mb-4">
        <h2 class="text-white text-uppercase font-weight-bold" data-aos="fade-up">Customer Dashboard</h2>
      </div>
    </div>
    
      <div class="content";>
        <div class="btnMenu">
          <a href="tracking.php" class="nav-link">
          <div class="icon">
            <img src="Menu/Track.png" alt="Track & Trace">
          </div>
          <h4 class="text-dark" style="margin-top: 1rem"><b>Track & Trace</b></h4>
          </a>
        </div>
        <div class="btnMenu">
          <a href="request.php" class="nav-link">
          <div class="icon">
            <img src="Menu/Request.png" alt="Request">
          </div>
          <h4 class="text-dark" style="margin-top: 1rem"><b>Request Pick-Up</b></h4>
          </a>
        </div>
        <div class="btnMenu">
          <a href="profile.php" class="nav-link">
          <div class="icon">
            <img src="Menu/Profile.png" alt="Profile">
          </div>
          <h4 class="text-dark" style="margin-top: 1rem"><b>Edit Profile</b></h4>
          </a>  
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
  
  <?php } else { ?>
  <div class="site-blocks-cover overlay" style="background-image: url(<?php echo $header_src; ?>);" data-aos="fade" data-stellar-background-ratio="0.5" id="section-track-and-trace">
  <div class="container">
    <div class="row align-items-center justify-content-center text-center">

      <div class="col-md-20" data-aos="fade-up" data-aos-delay="400">

        <h1 class="text-white font-weight-light" style="margin-top: 7rem" data-aos="fade-up"><?php echo $res['header_title']; ?></h1>
        <p class="mb-1" data-aos="fade-up" data-aos-delay="100"><?php echo $res["header_desc"]; ?></p>
        <form method="post">
          <label for="tracking_id" ></label>
          <input type="text" id="tracking_id" name="tracking_id" class="form-control" placeholder=" Enter your tracking ID:">
          <input type="hidden" name="tracking_info" value="<?php echo $tracking_info; ?>">
          <button type="submit" class="btn btn-primary py-3 px-5 text-white">Track</button>
        </form>
      </div>
    </div>
  </div>
  </div>
  <?php } ?>

  <div class="site-section" id="section-about">
    <div class="container">
      <div class="row mb-5" style="margin-top: 6rem">

        <div class="col-md-5 ml-auto mb-5 order-md-2" data-aos="fade-up" data-aos-delay="100">
          <img src="<?php echo $about_src; ?>" alt="Image" class="img-fluid rounded">
        </div>
        <div class="col-md-6 order-md-1" data-aos="fade-up">
          <div class="text-left pb-1 border-primary mb-4">
            <h2 class="text-primary"><?php echo $res["about_title"]; ?></h2>
          </div>
          <p><?php echo $res["about_desc"]; ?></p>
        </div>

      </div>
    </div>
  </div>

  <div class="site-section bg-light" id="section-contact">
    <div class="container">
      <div class="row justify-content-center mb-5" style="margin-top: 4rem">
        <div class="col-md-7 text-center border-primary">
          <h2 class="font-weight-light text-primary">Contact Us</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-md-7 mb-5">
          <form action="#" class="p-5 bg-white" method="post">
            <div class="row form-group">
              <div class="col-md-12 mb-3 mb-md-0">
                <label class="text-black" for="fname">Name</label>
                <input type="text" name="name" id="name" class="form-control">
              </div>
            </div>

            <div class="row form-group">

              <div class="col-md-12">
                <label class="text-black" for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control">
              </div>
            </div>

            <div class="row form-group">

              <div class="col-md-12">
                <label class="text-black" for="subject">Subject</label>
                <input type="subject" name="subject" id="subject" class="form-control">
              </div>
            </div>

            <div class="row form-group">
              <div class="col-md-12">
                <label class="text-black" for="message">Message</label>
                <textarea name="message" name="message" id="message" cols="30" rows="4" class="form-control"></textarea>
              </div>
            </div>

            <div class="row form-group">
              <div class="col-md-12">
                <input type="button" onclick="addContactMessage(this.form)" value="Send Message" class="btn btn-primary py-2 px-4 text-white">
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-5">

          <div class="p-4 mb-3 bg-white">
            <p class="mb-0 font-weight-bold">Address</p>
            <p class="mb-4"><?php echo $res["company_address"]; ?></p>

            <p class="mb-0 font-weight-bold">Phone</p>
            <p class="mb-4"><a href="#"><?php echo $res[
                "company_phone"
            ]; ?></a></p>

            <p class="mb-0 font-weight-bold">Email Address</p>
            <p class="mb-0"><a href="#"><?php echo $res[
                "company_email"
            ]; ?></a></p>

          </div>

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

</body>

</html>