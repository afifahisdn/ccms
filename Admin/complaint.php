<!DOCTYPE html>
<html lang="en">

<?php
/*
* admin/complaint.php
*
* Main page for Admins/Staff to view and manage all complaints.
*/

include "pages/header.php"; // Includes assets, connection, get
include "admin.php";      // Includes session check, gets $logged_in_user_role, $logged_in_staff_id

// Get the logged-in user's role (already defined in admin.php)
// $logged_in_user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

// Process search query
$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    // Basic validation to check if it's a number
    if (ctype_digit($_GET['search'])) {
        $search_query = mysqli_real_escape_string($con, $_GET['search']);
    } else {
        // Optional: Show an error message if the search is not numeric
        // This would require iziToast to be loaded, which it is in header.php
        echo "<script>document.addEventListener('DOMContentLoaded', function() { errorMessage('Search query must be a numeric Complaint ID.'); });</script>";
    }
}

// Function to get filtered complaint data (local to this page)
function getFilteredComplaints($search_query, $con)
{
    // Updated SQL Query: Join complaint with student and dormitory
    $sql = "SELECT c.*, s.name as student_name, d.dormitory_name 
            FROM complaint c
            LEFT JOIN student s ON s.student_id = c.student_id 
            LEFT JOIN dormitory d ON d.dormitory_id = c.dormitory_id
            WHERE c.is_deleted = 0 
            AND c.complaint_id LIKE ?
            ORDER BY c.created_at DESC";

    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        $search_param = "%$search_query%";
        mysqli_stmt_bind_param($stmt, "s", $search_param);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    } else {
        error_log("Error preparing getFilteredComplaints statement: " . mysqli_error($con));
        return false;
    }
}

// Function to get all complaint data (local to this page, uses function from get.php)
// We use the one from get.php directly: getAllComplaints()


// Get data based on search or all data if no search query
if (!empty($search_query)) {
    $getall = getFilteredComplaints($search_query, $con);
    $show_back_button = true;
} else {
    $getall = getAllComplaints(); // Use the existing function from get.php
    $show_back_button = false;
}
?>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between mt-0 px-2">
                        <a href="index.php">
                            <img src="assets/images/logo.png" alt="CCMS Logo">
                            <div class="mt-1 mb-2 border-bottom pb-2"></div>
                            <?php if ($logged_in_user_role == 'admin') : ?>
                                <h6>‎ ‎ ‎<b>ADMINISTRATOR PAGE</b></h6>
                            <?php else : ?>
                                <h6>‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎<b>STAFF PAGE</b></h6>
                            <?php endif; ?>
                            <div class="border-bottom"></div>
                        </a>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-item">
                            <a href="index.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <?php if ($logged_in_user_role == 'admin') : ?>
                            <li class="sidebar-item ">
                                <a href="student.php" class='sidebar-link'>
                                    <i class="bi bi-people-fill"></i>
                                    <span>Students</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <!-- Complaints link active -->
                        <li class="sidebar-item active">
                            <a href="complaint.php" class='sidebar-link'>
                                <i class="bi bi-exclamation-octagon-fill"></i>
                                <span>Complaints</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="feedback.php" class='sidebar-link'>
                                <i class="bi bi-chat-left-dots-fill"></i>
                                <span>Feedback</span>
                            </a>
                        </li>
                        <?php if ($logged_in_user_role == 'admin') : ?>
                            <li class="sidebar-item">
                                <a href="department.php" class='sidebar-link'>
                                    <i class="bi bi-building"></i>
                                    <span>Departments</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logged_in_user_role == "admin") : ?>
                            <li class="sidebar-item">
                                <a href="staff.php" class='sidebar-link'>
                                    <i class="bi bi-person-badge-fill"></i>
                                    <span>Staff</span>
                                </a>
                            </li>
                        <?php else : ?>
                            <li class="sidebar-item">
                                <a href="staff_edit.php?staff_id=<?php echo htmlspecialchars($logged_in_staff_id); ?>" class='sidebar-link'>
                                    <i class="bi bi-person-fill"></i>
                                    <span>Profile</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logged_in_user_role == 'admin') : ?>
                            <li class="sidebar-item">
                                <a href="dormitory.php" class='sidebar-link'>
                                    <i class="bi bi-house-door-fill"></i>
                                    <span>Dormitories</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="sidebar-item">
                            <a href="settings.php" class='sidebar-link'>
                                <i class="bi bi-gear-fill"></i>
                                <span>Settings</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="logout.php" class='sidebar-link'>
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Log Out</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <h3>Complaints</h3>
                <div class="mt-3 mb-3 border-bottom pb-2"></div>
            </div>

            <div class="row">
                <div class="col-lg-9">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Complaint List</li>
                        </ol>
                    </nav>
                </div>
                <?php if ($logged_in_user_role == 'admin') : ?>
                    <div class="col-lg-3 text-lg-end"> <!-- Aligned button to the right on large screens -->
                        <a href="add_complaint.php" class="btn btn-primary"> Add New Complaint</a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="page-content">
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <form action="complaint.php" method="GET" class="d-flex">
                            <input class="form-control me-2" type="search" name="search" placeholder="Search by Complaint ID" aria-label="Search" value="<?php echo htmlspecialchars($search_query); ?>">
                            <button class="btn btn-outline-primary" type="submit">Search</button>
                            <?php if ($show_back_button) : ?>
                                <a href="complaint.php" class="btn btn-outline-secondary ms-2">Show All</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                <?php
                if ($getall && mysqli_num_rows($getall) > 0) {
                    while ($row = mysqli_fetch_assoc($getall)) {
                        $complaint_id = $row["complaint_id"];
                        $complaint_status = (int)$row["complaint_status"];
                ?>
                        <article class="card mt-4 shadow-sm">
                            <header class="card-header text-white bg-dark d-flex justify-content-between align-items-center">
                                <span>Complaint ID: #<?php echo $complaint_id; ?></span>
                                <span>Submitted: <?php echo date("Y-m-d H:i", strtotime($row["created_at"])); ?></span>
                            </header>
                            <div class="card-body mt-3">
                                
                                <article class="card mb-4">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-4"> <strong>Student Name:</strong>
                                                <br><?php echo htmlspecialchars($row["student_name"] ?? 'N/A'); ?>
                                            </div>
                                            <div class="col-md-4"> <strong>Dormitory:</strong>
                                                <br><?php echo htmlspecialchars($row["dormitory_name"] ?? 'N/A'); ?>
                                            </div>
                                            <div class="col-md-4"> <strong>Room Number:</strong>
                                                <br><?php echo htmlspecialchars($row["room_number"]); ?>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row mb-3">
                                            <div class="col-md-12"> <strong>Title:</strong>
                                                <br><?php echo htmlspecialchars($row["complaint_title"]); ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12"> <strong>Description:</strong>
                                                <br><?php echo nl2br(htmlspecialchars($row["complaint_description"])); ?>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3"> <strong>Category:</strong>
                                                <br><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $row["complaint_category"]))); ?>
                                            </div>
                                            <div class="col-md-3"> <strong>Urgency:</strong>
                                                <br><span class="urgency-<?php echo strtolower(htmlspecialchars($row['urgency_level'])); ?>"><?php echo htmlspecialchars(ucfirst($row["urgency_level"])); ?></span>
                                            </div>
                                            <div class="col-md-3"> <strong>Last Updated:</strong>
                                                <br><?php echo date("Y-m-d H:i", strtotime($row["date_updated"])); ?>
                                            </div>
                                            <div class="col-md-3"> <strong>Assigned Staff:</strong>
                                                <br><?php echo htmlspecialchars($row["assigned_staff_id"] ?? 'Not Assigned'); ?>
                                                <!-- You might want to join staff table to get name -->
                                            </div>
                                        </div>
                                        <?php if (!empty($row["photo"])) : ?>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <strong>Photo:</strong><br>
                                                    <a href="../<?php echo htmlspecialchars($row["photo"]); ?>" target="_blank">View Photo</a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($row["resolution_notes"])) : ?>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <strong class="text-success">Resolution Notes:</strong><br>
                                                    <p class="text-muted" style="white-space: pre-wrap;"><?php echo htmlspecialchars($row["resolution_notes"]); ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </article>

                                <!-- Status Tracking Bar -->
                                <div class="track mb-5">
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
                                <!-- Action Row -->
                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <label for="complaint_status_<?php echo $complaint_id; ?>" class="form-label">Update Status:</label>
                                        <select onchange='updateData(this, "<?php echo $complaint_id; ?>","complaint_status", "complaint", "complaint_id")' id="complaint_status_<?php echo $complaint_id; ?>" class='form-select' name="complaint_status" <?php echo ($complaint_status == 4) ? 'disabled' : ''; ?>>
                                            <option value="1" <?php if ($complaint_status == 1) echo "selected"; ?>>Open</option>
                                            <option value="2" <?php if ($complaint_status == 2) echo "selected"; ?>>In Progress</option>
                                            <option value="3" <?php if ($complaint_status == 3) echo "selected"; ?>>Resolved</option>
                                            <option value="4" <?php if ($complaint_status == 4) echo "selected"; ?>>Closed</option>
                                        </select>
                                    </div>
                                    <?php if ($logged_in_user_role == 'admin') : ?>
                                        <div class="col-md-4">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <button type="button" onclick="deleteData(<?php echo $complaint_id; ?>,'complaint', 'complaint_id')" class="btn btn-danger"> <i class="fa-solid fa-trash"></i> Delete</button>
                                        </div>
                                    <?php endif; ?>
                                    <div class="col-md-<?php echo ($logged_in_user_role == 'admin') ? '4' : '8'; ?> text-md-end">
                                        <label class="form-label d-block">&nbsp;</label>
                                        <a href="get_complaint_report.php?complaint_id=<?php echo $complaint_id; ?>" target="_blank" class="btn btn-info py-2 px-4 text-white"><i class="fa-solid fa-print"></i> Print</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                <?php
                    } // End while loop
                } else {
                    echo "<div class='alert alert-info mt-4'>No complaints found.</div>";
                }
                ?>
            </div>

            <?php include "pages/footer.php"; ?>
        </div>
    </div>

    <!-- JS Includes -->
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!-- Apexcharts not needed for this page -->
    <!-- <script src="assets/vendors/apexcharts/apexcharts.js"></script> -->
    <script src="assets.js"></script> <!-- This should be in header.php -->
    <script src="assets/js/main.js"></script>
</body>
<style>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');

    .card {
        margin-bottom: 1.5rem;
    }

    .card-header {
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }

    .bg-dark {
        background-color: #343a40 !important;
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

    /* Tracking Bar */
    .track {
        position: relative;
        background-color: #ddd;
        height: 7px;
        display: flex;
        margin-bottom: 60px;
        margin-top: 50px;
        border-radius: 5px;
    }

    .track .step {
        flex-grow: 1;
        width: 25%;
        margin-top: -18px;
        text-align: center;
        position: relative;
    }

    /* Line segments */
    .track .step::before {
        height: 7px;
        position: absolute;
        content: "";
        width: 100%;
        left: -50%;
        top: 18px;
        background: #ddd;
        z-index: 1;
    }

    .track .step:first-child::before {
        display: none;
    }

    /* Icons */
    .track .icon {
        display: inline-block;
        width: 40px;
        height: 40px;
        line-height: 40px;
        position: relative;
        border-radius: 100%;
        background: #ddd;
        color: #fff;
        /* Icon color */
        z-index: 2;
        margin-bottom: 5px;
    }

    /* Text below icons */
    .track .text {
        display: block;
        margin-top: 7px;
        font-size: 0.85rem;
        color: #6c757d;
    }

    /* Active State */
    .track .step.active .icon {
        background: #0d6efd;
        /* Bootstrap primary */
    }

    .track .step.active .text {
        font-weight: 600;
        color: #000;
    }

    .track .step.active::before {
        background: #0d6efd;
    }
    
    /* Specific Colors for Active States */
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
    .track .step.active.open ~ .step::before { background: #ddd; }
    .track .step.active.inprogress ~ .step::before { background: #ddd; }
    .track .step.active.resolved ~ .step::before { background: #ddd; }
    
    /* Active line coloring */
    .track .step.active.open::before { background: #007bff; }
    .track .step.active.inprogress::before { background: #ffc107; }
    .track .step.active.resolved::before { background: #28a745; }
    .track .step.active.closed::before { background: #6c757d; }


    .text-md-end {
        text-align: right !important;
    }

    /* Add space for label alignment */
    .align-items-end .form-label {
        display: block;
        visibility: hidden;
    }
    
    /* Override for the first label */
    .align-items-end .col-md-4:first-child .form-label {
        visibility: visible;
    }
    
</style>
</html>