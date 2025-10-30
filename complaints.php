<?php
/*
* complaints.php
* Displays a list of all complaints submitted by the logged-in student.
* Requires auth.php to be included first.
*/

// Includes session_start, db connection, settings fetch ($res)
include "pages/header.php";
// Includes student session check and fetches $student_data
include "auth.php";
// Make sure get.php is included if not already by header/auth
include_once "server/inc/get.php";
?>

<head>
    <!-- Add head content if needed, like title override -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Complaints - CCMS</title>
    <!-- CSS is included from pages/header.php -->
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
                            <li><a href="complaints.php" class="nav-link active">My Complaints</a></li> <!-- Active Link -->
                            <li><a href="submit_complaint.php" class="nav-link">Submit Complaint</a></li>
                            <li><a href="profile.php" class="nav-link">Profile</a></li>
                            <li><a href="index.php#section-contact" class="nav-link">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Section -->
    <div class="site-section bg-light" id="section-my-complaints">
        <div class="container">
            <!-- Page Title -->
            <div class="row justify-content-center mb-5" style="margin-top: 5rem;">
                <div class="col-md-7 text-center border-primary">
                    <h2 class="font-weight-light text-primary">My Submitted Complaints</h2>
                </div>
            </div>

            <!-- Complaints List -->
            <div class="row">
                <div class="col-md-12 mb-5">

                    <?php
                    // Fetch complaints for the logged-in student
                    // Ensure getAllComplaintsByStudentID joins with dormitory table
                    $getall = getAllComplaintsByStudentID($_SESSION["student_id"]);

                    if (!$getall) {
                        // Query failed
                        echo "<div class='alert alert-danger text-center'>Error fetching complaints. Please try again later.</div>";
                    } elseif (mysqli_num_rows($getall) == 0) {
                        // No complaints found
                        echo "<h4 style='color: #666; text-align: center; margin-top: 50px; margin-bottom: 20px;'>You haven't submitted any complaints yet.</h4>";
                        echo "<div class='text-center'><a href='submit_complaint.php' class='btn btn-primary'>Submit a Complaint</a></div>";
                    } else {
                        // Loop through complaints
                        while ($row = mysqli_fetch_assoc($getall)) {
                            $complaint_id = $row["complaint_id"];
                            $complaint_status = (int)$row["complaint_status"]; // Store status for easier access
                    ?>
                            <article class="card mt-4 mb-4 shadow-sm complaint-card status-<?php echo $complaint_status; ?>">
                                <header class="card-header text-white bg-dark d-flex justify-content-between align-items-center">
                                    <span>Complaint ID: #<?php echo htmlspecialchars($complaint_id); ?></span>
                                    <span>Submitted: <?php echo date("d M Y, H:i", strtotime($row["created_at"])); ?></span>
                                </header>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['complaint_title']); ?></h5>

                                    <div class="row mb-3 complaint-details">
                                        <div class="col-md-4">
                                            <strong>Dormitory:</strong> <?php echo htmlspecialchars($row['dormitory_name'] ?? 'N/A'); ?><br>
                                            <strong>Room:</strong> <?php echo htmlspecialchars($row['room_number']); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Category:</strong> <?php echo ucwords(str_replace('_', ' ', htmlspecialchars($row['complaint_category']))); ?><br>
                                            <strong>Urgency:</strong> <span class="urgency-<?php echo strtolower(htmlspecialchars($row['urgency_level'])); ?>"><?php echo ucfirst(htmlspecialchars($row['urgency_level'])); ?></span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Current Status:</strong>
                                            <span class="status-<?php echo $complaint_status; ?>">
                                                <?php
                                                // Display status text based on number
                                                switch ($complaint_status) {
                                                    case 1:
                                                        echo "Open";
                                                        break;
                                                    case 2:
                                                        echo "In Progress";
                                                        break;
                                                    case 3:
                                                        echo "Resolved";
                                                        break;
                                                    case 4:
                                                        echo "Closed";
                                                        break;
                                                    default:
                                                        echo "Unknown";
                                                        break;
                                                }
                                                ?>
                                            </span>
                                            <br>
                                            <?php if (!empty($row['date_resolved'])) : ?>
                                                <strong>Resolved On:</strong> <?php echo date("d M Y", strtotime($row["date_resolved"])); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if (!empty($row['complaint_description'])) : ?>
                                        <p class="mb-2"><strong>Description:</strong></p>
                                        <p class="text-muted" style="white-space: pre-wrap;"><?php echo htmlspecialchars($row['complaint_description']); ?></p>
                                    <?php endif; ?>

                                    <?php if (!empty($row['photo'])) : ?>
                                        <p class="mb-2"><strong>Photo:</strong> <a href="<?php echo htmlspecialchars($row['photo']); ?>" target="_blank">View Attached Photo</a></p>
                                    <?php endif; ?>

                                    <?php if (!empty($row['resolution_notes'])) : ?>
                                        <p class="resolution-notes mt-3"><strong>Resolution Notes:</strong><br><?php echo nl2br(htmlspecialchars($row['resolution_notes'])); ?></p>
                                    <?php endif; ?>

                                    <!-- Status Tracking Bar -->
                                    <div class="track mt-4">
                                        <div class="step <?php if ($complaint_status >= 1) echo "active open"; ?>">
                                            <span class="icon"> <i class="fa fa-folder-open"></i> </span>
                                            <span class="text">Open</span>
                                        </div>
                                        <div class="step <?php if ($complaint_status >= 2) echo "active inprogress"; ?>">
                                            <span class="icon"> <i class="fa fa-cogs"></i> </span>
                                            <span class="text">In Progress</span>
                                        </div>
                                        <div class="step <?php if ($complaint_status >= 3) echo "active resolved"; ?>">
                                            <span class="icon"> <i class="fa fa-check-circle"></i> </span>
                                            <span class="text">Resolved</span>
                                        </div>
                                        <div class="step <?php if ($complaint_status >= 4) echo "active closed"; ?>">
                                            <span class="icon"> <i class="fa fa-archive"></i> </span>
                                            <span class="text">Closed</span>
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- Action Button -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if ($complaint_status == 1) { // Only show withdraw if status is 'Open' 
                                            ?>
                                                <button type="button" onclick="withdrawComplaint(<?php echo $complaint_id; ?>)" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-times-circle"></i> Withdraw Complaint
                                                </button>
                                            <?php } else { ?>
                                                <p class="text-muted small">This complaint can no longer be withdrawn.</p>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                            </article>
                    <?php
                        } // end while loop
                    } // end else (complaints exist)
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
    <!-- <script src="admin/assets/js/include/alerts.js"></script>  -->
    <!-- <script src="admin/assets/js/include/homejs.js"></script>  -->
    <!-- <script src="admin/assets/js/include/delete.js"></script> -->

    <script src="js/main.js"></script> <!-- General template JS -->


    <!-- Custom CSS for Status Bar and Complaint Card -->
    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');

        /* Basic Card Styling */
        .complaint-card {
            border: 1px solid #e0e0e0;
            border-left: 5px solid #6c757d;
            /* Default border color */
        }

        .complaint-card .card-header {
            background-color: #343a40 !important;
            /* Dark header */
        }

        .complaint-card .card-title {
            margin-bottom: 1rem;
            color: #333;
        }

        .complaint-details strong {
            display: inline-block;
            min-width: 80px;
            /* Align labels */
        }

        .resolution-notes {
            background-color: #f8f9fa;
            border-left: 3px solid #17a2b8;
            /* Info color */
            padding: 10px;
            margin-top: 10px;
            font-size: 0.9em;
        }

        /* Urgency Colors */
        .urgency-low {
            color: #28a745;
            font-weight: bold;
        }

        /* Green */
        .urgency-medium {
            color: #ffc107;
            font-weight: bold;
        }

        /* Yellow */
        .urgency-high {
            color: #fd7e14;
            font-weight: bold;
        }

        /* Orange */
        .urgency-critical {
            color: #dc3545;
            font-weight: bold;
        }

        /* Red */

        /* Status Colors (for text and card border) */
        .status-1 {
            color: #007bff;
        }

        /* Blue for Open */
        .status-2 {
            color: #ffc107;
        }

        /* Yellow for In Progress */
        .status-3 {
            color: #28a745;
        }

        /* Green for Resolved */
        .status-4 {
            color: #6c757d;
        }

        /* Grey for Closed */
        .complaint-card.status-1 {
            border-left-color: #007bff;
        }

        .complaint-card.status-2 {
            border-left-color: #ffc107;
        }

        .complaint-card.status-3 {
            border-left-color: #28a745;
        }

        .complaint-card.status-4 {
            border-left-color: #6c757d;
        }

        /* Tracking Bar Styles */
        .track {
            position: relative;
            background-color: #e9ecef;
            /* Lighter grey */
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
            /* The line connecting steps */
            height: 7px;
            position: absolute;
            content: "";
            width: 100%;
            left: 0;
            top: 18px;
            background-color: #e9ecef;
            /* Default line color */
            z-index: 1;
        }

        .track .step:first-child::before {
            /* Remove line before the first step */
            display: none;
        }

        .track .step .icon {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            /* Center icon vertically */
            position: relative;
            border-radius: 50%;
            background: #adb5bd;
            /* Default icon background (grey) */
            color: #fff;
            z-index: 2;
            /* Ensure icon is above the line */
            font-size: 1.1em;
        }

        .track .step .text {
            display: block;
            margin-top: 7px;
            color: #6c757d;
            /* Default text color */
            font-size: 0.9em;
        }

        /* Active Step Styles */
        .track .step.active .icon {
            background: #6c757d;
            /* Default active color */
        }

        .track .step.active .text {
            font-weight: 600;
            /* Bolder text */
            color: #495057;
            /* Darker text */
        }

        /* Color the line before the active step */
        .track .step.active::before {
            background: #6c757d;
            /* Default active color */
        }


        /* Specific Status Colors for Bar */
        .track .step.active.open .icon,
        .track .step.active.open::before {
            background: #007bff;
        }

        /* Blue */
        .track .step.active.inprogress .icon,
        .track .step.active.inprogress::before {
            background: #ffc107;
        }

        /* Yellow */
        .track .step.active.resolved .icon,
        .track .step.active.resolved::before {
            background: #28a745;
        }

        /* Green */
        .track .step.active.closed .icon,
        .track .step.active.closed::before {
            background: #6c757d;
        }

        /* Grey */

        /* Ensure the last active step's line color applies */
        .track .step.active.open~.step::before {
            background: #e9ecef;
        }

        /* Reset following lines */
        .track .step.active.inprogress~.step::before {
            background: #e9ecef;
        }

        .track .step.active.resolved~.step::before {
            background: #e9ecef;
        }

        .track .step.active.closed~.step::before {
            background: #e9ecef;
        }

        /* Although none follow */
    </style>
</body>
</html>