<head>
  <?php
  include "server/api.php";
  include "pages/assets.php";

  // Fetch settings from the database with error handling
  $setting = getAllSettings();
  if (!$setting) {
      // Handle the error appropriately
      die("Error fetching settings");
  }

  $res = mysqli_fetch_assoc($setting);
  if (!$res) {
      // Handle the error appropriately
      die("Error fetching settings");
  }

  // Sanitize the output to prevent XSS
  $header = htmlspecialchars($res["header_image"]);
  $header_src = "server/uploads/settings/" . $header;

  $subheader = htmlspecialchars($res["sub_image"]);
  $subheader_src = "server/uploads/settings/" . $subheader;

  $about = htmlspecialchars($res["about_image"]);
  $about_src = "server/uploads/settings/" . $about;

  $background_image = htmlspecialchars($res["background_image"]);
  $background_image_src = "server/uploads/settings/" . $background_image;
  ?>

<title>Velocity Express</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,700,900|Playfair+Display:200,300,400,700">
  <link rel="stylesheet" href="fonts/icomoon/style.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/magnific-popup.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
  <link rel="stylesheet" href="css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
  <link rel="stylesheet" href="css/aos.css">
  <link rel="stylesheet" href="css/style.css">
</html>