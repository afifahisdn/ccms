<!DOCTYPE html>
<html lang="en">
<?php
/*
* complaint_status.php
*
* Public page to check the status of a single complaint.
*/

// Includes session_start, db connection, settings fetch ($res)
include "pages/header.php";
// Make sure get.php is included
include_once "server/inc/get.php";

// Check if a user (any student) is logged in.
$isLoggedIn = isset($_SESSION["student_id"]);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Status - CCMS</title>
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
        <!-- Top Header Section (Guest View) -->
        <div class="top-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <!-- Translate Button -->
                        <div class="dropdown-container float-right d-inline-block ml-3">
                            <button class="dropdown-toggle btn btn-translate" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="server/uploads/settings/Translate.png" alt="Translate" style="max-width: 25px;">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="?lang=en">English</a>
                            </div>
                        </div>
                        <!-- Guest Links -->
                        <a href="admin/register.php" class="quick-link d-inline-block">Signup</a>
                        <a href="admin/login.php" class="quick-link d-inline-block">Login</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Navigation (Guest View) -->
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
                            <!-- Guest Menu -->
                            <li><a href="index.php#section-track-and-trace" class="nav-link">Check Complaint Status</a></li>
                            <li><a href="login.php" class="nav-link">Submit Complaint</a></li>
                            <li><a href="index.php#section-about" class="nav-link">About Us</a></li>
                            <li><a href="index.php#section-contact" class="nav-link">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Section -->
    <div class="site-section bg-light" id="section-complaint-status-details">
        <div class="container" style="margin-top: 5rem;">
            <div class="row justify-content-center">
                <div class="col-md-10 mb-5">
                    <?php
                    // Get the complaint ID from the URL
                    if (isset($_GET["complaint_id"]) && is_numeric($_GET["complaint_id"])) {
                        $complaint_id = $_GET["complaint_id"];
                    } else {
                        // Show error if ID is missing or invalid
                    ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                iziToast.error({
                                    title: 'Error',
                                    message: 'Invalid or missing Complaint ID.',
                                    position: 'center',
                                    onClosing: function(instance, toast, closedBy) {
                                        setTimeout(function() {
                                            window.location.href = 'index.php';
                                        }, 500);
                                    }
                                });
                            });
                        </script>
                    <?php
                        exit(); // Stop script execution
                    }

                    // Get the complaint information from the database
                    // This function now JOINS categories
                    $getall = getComplaintByID($complaint_id);

                    if (!$getall) {
                        // Query failed
                        echo "<div class='alert alert-danger text-center'>Error fetching complaint details.</div>";
                    } elseif (mysqli_num_rows($getall) > 0) {
                        // Fetch the complaint information
                        $row = mysqli_fetch_assoc($getall);
                        $complaint_status = $row["complaint_status"]; // This is now a string
                    ?>
                        <article class="card shadow-sm complaint-card status-<?php echo strtolower(str_replace(' ', '', $complaint_status)); ?>">
                            <header class="card-header text-white bg-dark d-flex justify-content-between align-items-center">
                                <span>Complaint Status</span>
                                <span>ID: #<?php echo htmlspecialchars($complaint_id); ?></span>
                            </header>
                            <div class="card-body" style="margin-bottom:1.2rem;">
                                <h4 class="card-title mb-3"><?php echo htmlspecialchars($row['complaint_title']); ?></h4>

                                <div class="row mb-3 complaint-details">
                                    <div class="col-md-6">
                                        <strong>Dormitory:</strong> <?php echo htmlspecialchars($row['dormitory_name'] ?? 'N/A'); ?><br>
                                        <strong>Category:</strong> <?php echo htmlspecialchars(ucfirst($row['category_name'] ?? 'N/A')); ?><br>
                                        <strong>Urgency:</strong> <span class="urgency-<?php echo strtolower(htmlspecialchars($row['urgency_level'])); ?>"><?php echo ucfirst(htmlspecialchars($row['urgency_level'])); ?></span>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- === NEW PRIVACY LOGIC === -->
                                        <?php if ($isLoggedIn) : ?>
                                            <strong>Student Name:</strong> <?php echo htmlspecialchars($row['student_name'] ?? 'N/A'); ?><br>
                                            <strong>Student ID:</strong> <?php echo htmlspecialchars($row['student_id'] ?? 'N/A'); ?><br>
                                            <strong>Room:</strong> <?php echo htmlspecialchars($row['room_number']); ?>
                                        <?php else : ?>
                                            <strong>Student Name:</strong> <i>[Details hidden for privacy]</i><br>
                                            <strong>Student ID:</strong> <i>[Details hidden for privacy]</i><br>
                                            <strong>Room:</strong> <i>[Details hidden for privacy]</i>
                                        <?php endif; ?>
                                        <!-- === END PRIVACY LOGIC === -->
                                    </div>
                                </div>

                                <hr>

                                <!-- === NEW PRIVACY LOGIC === -->
                                <?php if ($isLoggedIn) : ?>
                                    <?php if (!empty($row['complaint_description'])) : ?>
                                        <p class="mb-2"><strong>Description:</strong></p>
                                        <p class="text-muted" style="white-space: pre-wrap;"><?php echo htmlspecialchars($row['complaint_description']); ?></p>
                                    <?php endif; ?>

                                    <?php if (!empty($row['photo'])) : ?>
                                        <p class="mb-2"><strong>Photo:</strong> <a href="<?php echo htmlspecialchars($row['photo']); ?>" target="_blank">View Attached Photo</a></p>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <p class="text-muted" style="color: #262323 !important;"><i>Log in as a student to view the full complaint description and any attached photos.</i></p>
                                <?php endif; ?>
                                <!-- === END PRIVACY LOGIC === -->

                                <?php if (!empty($row['resolution_notes'])) : ?>
                                    <p class="resolution-notes mt-3"><strong>Resolution Notes:</strong><br><?php echo nl2br(htmlspecialchars($row['resolution_notes'])); ?></p>
                                <?php endif; ?>


                                <!-- === UPDATED STATUS BAR === -->
                                <div class="track mt-4 mb-4">
                                    <?php
                                    // Logic based on string ENUM values
                                    $isOpen = in_array($complaint_status, ['Open', 'In Progress', 'Resolved', 'Closed']);
                                    $isInProgress = in_array($complaint_status, ['In Progress', 'Resolved', 'Closed']);
                                    $isResolved = in_array($complaint_status, ['Resolved', 'Closed']);
                                    $isClosed = ($complaint_status == 'Closed');
                                    $isWithdrawn = ($complaint_status == 'Withdrawn');

                                    if ($isWithdrawn) {
                                        echo "<p class='text-danger w-100 text-center p-3'><strong>Complaint Withdrawn by Student.</strong></p>";
                                    } else {
                                    ?>
                                        <div class="step <?php if ($isOpen) echo "active open"; ?>">
                                            <span class="icon"> <i class="fa fa-folder-open"></i> </span>
                                            <span class="text">Open</span>
                                        </div>
                                        <div class="step <?php if ($isInProgress) echo "active inprogress"; ?>">
                                            <span class="icon"> <i class="fa fa-cogs"></i> </span>
                                            <span class="text">In Progress</span>
                                        </div>
                                        <div class="step <?php if ($isResolved) echo "active resolved"; ?>">
                                            <span class="icon"> <i class="fa fa-check-circle"></i> </span>
                                            <span class="text">Resolved</span>
                                        </div>
                                        <div class="step <?php if ($isClosed) echo "active closed"; ?>">
                                            <span class="icon"> <i class="fa fa-archive"></i> </span>
                                            <span class="text">Closed</span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </article>
                    <?php
                    } else {
                        // Complaint ID not found
                    ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                iziToast.error({
                                    title: 'Not Found',
                                    message: 'Complaint ID #<?php echo htmlspecialchars($complaint_id); ?> not found.',
                                    position: 'center',
                                    onClosing: function(instance, toast, closedBy) {
                                        setTimeout(function() {
                                            window.location.href = 'index.php';
                                        }, 500);
                                    }
                                });
                            });
                        </script>
                    <?php
                        exit(); // Stop script execution
                    }
                    ?>
                </div>
            </div>
            <div class="row justify-content-center mt-3">
                <div class="col-md-10 text-center">
                    <a href="index.php#section-track-and-trace" class="btn btn-secondary">Check Another Complaint ID</a>
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
    <!-- <script src="admin/assets/js/include/alerts.js"></script> -->

    <script src="js/main.js"></script> <!-- General template JS -->

    <!-- === UPDATED CSS === -->
    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');

        /* Basic Card Styling */
        .complaint-card {
            border: 1px solid #e0e0e0;
            border-left: 5px solid #6c757d;
        }
        .complaint-card .card-header {
            background-color: #343a40 !important;
        }
        .complaint-card .card-title {
            margin-bottom: 1rem;
            color: #333;
        }
        .complaint-details strong {
            display: inline-block;
            min-width: 90px;
            color: #555;
        }
        .resolution-notes {
            background-color: #f8f9fa;
            border-left: 3px solid #17a2b8;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 3rem;
            font-size: 0.9em;
        }

        /* Urgency Colors */
        .urgency-low { color: #28a745;}
        .urgency-medium { color: #ffc107;}
        .urgency-high { color: #fd7e14;}
        .urgency-critical { color: #dc3545;}

        /* Status Colors (for text and card border) */
        .status-open { color: #007bff;}
        .status-inprogress { color: #ffc107;}
        .status-resolved { color: #28a745;}
        .status-closed { color: #6c757d;}
        .status-withdrawn { color: #dc3545;}
        
        .complaint-card.status-open { border-left-color: #007bff; }
        .complaint-card.status-inprogress { border-left-color: #ffc107; }
        .complaint-card.status-resolved { border-left-color: #28a745; }
        .complaint-card.status-closed { border-left-color: #6c757d; }
        .complaint-card.status-withdrawn { border-left-color: #dc3545; }


        /* Tracking Bar Styles */
        .track {
            position: relative;
            background-color: #e9ecef;
            height: 7px;
            display: flex;
            margin-bottom: 60px;
            margin-top: 50px;
            border-radius: 4px;
        }
        .track .step {
            flex-grow: 1;
            width: 25%;
            margin-top: -18px;
            text-align: center;
            position: relative;
        }
        .track .step::before {
            height: 7px;
            position: absolute;
            content: "";
            width: 100%;
            left: 0;
            top: 18px;
            background-color: #e9ecef;
            z-index: 1;
        }
        .track .step:first-child::before {
            display: none;
        }
        .track .step .icon {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            position: relative;
            border-radius: 50%;
            background: #adb5bd;
            color: #fff;
            z-index: 2;
            font-size: 1.1em;
        }
        .track .step .text {
            display: block;
            margin-top: 7px;
            color: #6c757d;
            font-size: 0.9em;
        }

        /* Active Step Styles */
        .track .step.active .icon {
            background: #6c757d;
        }
        .track .step.active .text {
            font-weight: 600;
            color: #495057;
        }
        .track .step.active::before {
            background: #6c757d;
        }

        /* Specific Status Colors for Bar */
        .track .step.active.open .icon,
        .track .step.active.open::before {
            background: #007bff; /* Blue */
        }
        .track .step.active.inprogress .icon,
        .track .step.active.inprogress::before {
            background: #ffc107; /* Yellow */
        }
        .track .step.active.resolved .icon,
        .track .step.active.resolved::before {
            background: #28a745; /* Green */
        }
        .track .step.active.closed .icon,
        .track .step.active.closed::before {
            background: #6c757d; /* Grey */
        }

        /* Ensure lines reset after the last active step */
        .track .step.active.open ~ .step::before { background: #e9ecef; }
        .track .step.active.inprogress ~ .step::before { background: #e9ecef; }
        .track .step.active.resolved ~ .step::before { background: #e9ecef; }
    </style>
</body>
</html>