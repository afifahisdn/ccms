<!DOCTYPE html>
<html lang="en">

<?php
/*
* admin/complaint.php
*
* Main page for Admins/Staff to view and manage all complaints.
*/

include "pages/header.php"; // Includes assets, connection, get
include "admin.php";      // Includes session check, sets $logged_in_user_role, $logged_in_staff_id

// --- POOR MAN'S CRON JOB ---
autoCloseComplaints();
// --- END CRON JOB ---

// --- Process Filters ---
$filters = [];
$search_query_val = ""; // For populating the search box

// General Search Query
if (!empty($_GET['search_query'])) {
    $filters['search_query'] = mysqli_real_escape_string($con, $_GET['search_query']);
    $search_query_val = $filters['search_query'];
}
// Filter by Status
if (!empty($_GET['status'])) {
    $filters['status'] = mysqli_real_escape_string($con, $_GET['status']);
}
// Filter by Category
if (!empty($_GET['cat_id'])) {
    $filters['cat_id'] = (int)$_GET['cat_id'];
}
// Filter by Dormitory
if (!empty($_GET['dorm_id'])) {
    $filters['dorm_id'] = (int)$_GET['dorm_id'];
}

// Get filtered data
$getall = getFilteredComplaints($filters); // This function is from get.php
$show_clear_button = !empty($filters); // Show "Clear" if any filters are set
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
                            <li class="sidebar-item">
                                <a href="categories.php" class='sidebar-link'>
                                    <i class="bi bi-tags-fill"></i>
                                    <span>Categories</span>
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
                <div class="col-lg-7">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Complaint List</li>
                        </ol>
                    </nav>
                </div>
                <?php if ($logged_in_user_role == 'admin') : ?>
                    <div class="col-lg-5 text-lg-end">
                        <a href="categories.php" class="btn btn-info">
                            <i class="bi bi-tags-fill"></i> Manage Categories
                        </a>
                        <a href="add_complaint.php" class="btn btn-primary ms-2">
                            <i class="bi bi-plus-circle"></i> Add New Complaint
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="page-content">
                <div class="card">
                    <div class="card-body">
                        <form action="complaint.php" method="GET">
                            <!-- Top Row: Search Bar -->
                            <div class="row mb-3 text-lg-end">
                                <div class="col-3">
                                    <input class="form-control" type="search" name="search_query" id="search_query" 
                                           placeholder="Search" 
                                           value="<?php echo htmlspecialchars($search_query_val); ?>">
                                </div>
                            </div>
                            
                            <!-- Bottom Row: Filters & Buttons -->
                            <div class="row g-3 align-items-end">
                                <!-- Status Filter -->
                                <div class="col-md-3">
                                    <select name="status" id="status" class="form-select">
                                        <option value="">All Statuses</option>
                                        <option value="Open" <?php if (($filters['status'] ?? '') == 'Open') echo 'selected'; ?>>Open</option>
                                        <option value="In Progress" <?php if (($filters['status'] ?? '') == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                        <option value="Resolved" <?php if (($filters['status'] ?? '') == 'Resolved') echo 'selected'; ?>>Resolved</option>
                                        <option value="Closed" <?php if (($filters['status'] ?? '') == 'Closed') echo 'selected'; ?>>Closed</option>
                                        <option value="Withdrawn" <?php if (($filters['status'] ?? '') == 'Withdrawn') echo 'selected'; ?>>Withdrawn</option>
                                    </select>
                                </div>
                                <!-- Category Filter -->
                                <div class="col-md-3">
                                    <select name="cat_id" id="cat_id" class="form-select">
                                        <option value="">All Categories</option>
                                        <?php
                                        $cats = getAllCategories();
                                        if ($cats) {
                                            while ($cat_row = mysqli_fetch_assoc($cats)) {
                                                $selected = (($filters['cat_id'] ?? '') == $cat_row['category_id']) ? 'selected' : '';
                                                echo "<option value=\"{$cat_row['category_id']}\" $selected>" . htmlspecialchars($cat_row['category_name']) . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <!-- Dormitory Filter -->
                                <div class="col-md-3">
                                    <select name="dorm_id" id="dorm_id" class="form-select">
                                        <option value="">All Dormitories</option>
                                        <?php
                                        $dorms = getAllDormitory();
                                        if ($dorms) {
                                            while ($dorm_row = mysqli_fetch_assoc($dorms)) {
                                                $selected = (($filters['dorm_id'] ?? '') == $dorm_row['dormitory_id']) ? 'selected' : '';
                                                echo "<option value=\"{$dorm_row['dormitory_id']}\" $selected>" . htmlspecialchars($dorm_row['dormitory_name']) . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <!-- Buttons -->
                                <div class="col-md-3">
                                    <button class="btn btn-primary" type="submit"><i class="bi bi-filter"></i> Filter</button>
                                    <?php if ($show_clear_button) : ?>
                                        <a href="complaint.php" class="btn btn-secondary ms-2"><i class="bi bi-x-circle"></i> Clear</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- === COMPLAINT LIST === -->
                <?php
                if ($getall && mysqli_num_rows($getall) > 0) {
                    while ($row = mysqli_fetch_assoc($getall)) {
                        $complaint_id = $row["complaint_id"];
                        $complaint_status = $row["complaint_status"]; // This is a string
                        $current_urgency = $row["urgency_level"];
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
                                                <br><?php echo htmlspecialchars(ucfirst($row["category_name"] ?? 'N/A')); ?>
                                            </div>
                                            <div class="col-md-3"> <strong>Last Updated:</strong>
                                                <br><?php echo date("Y-m-d H:i", strtotime($row["date_updated"])); ?>
                                            </div>
                                            <div class="col-md-3"> <strong>Assigned Staff:</strong>
                                                <br><?php echo htmlspecialchars($row["assigned_staff_id"] ?? 'Not Assigned'); ?>
                                            </div>
                                            <div class="col-md-3"> <strong>Current Status:</strong>
                                                <br><span class="status-<?php echo strtolower(str_replace(' ', '', $complaint_status)); ?>"><?php echo htmlspecialchars($complaint_status); ?></span>
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

                                <!-- Status Tracking Bar (Uses ENUM strings) -->
                                <div class="track mb-5">
                                    <?php
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

                                <hr>
                                <!-- Action Row (Uses ENUM strings) -->
                                <div class="row align-items-end">
                                    <div class="col-md-3">
                                        <label for="complaint_status_<?php echo $complaint_id; ?>" class="form-label">Update Status:</label>
                                        <?php if ($complaint_status == "Closed" || $complaint_status == "Withdrawn") : ?>
                                            <!-- Show a message instead of the dropdown -->
                                            <p class="form-control-static text-muted pt-2">
                                                <em>Cannot be updated. Status is <?php echo htmlspecialchars($complaint_status); ?>.</em>
                                            </p>
                                        <?php else : ?>
                                            <!-- Show the dropdown -->
                                                <select onchange='confirmStatusChange(this, "<?php echo $complaint_id; ?>", "<?php echo $complaint_status; ?>")' 
                                                    id="complaint_status_<?php echo $complaint_id; ?>" 
                                                    class='form-select' 
                                                    name="complaint_status" 
                                                    <?php echo ($complaint_status == "Closed" || $complaint_status == "Withdrawn") ? 'disabled' : ''; ?>>
                                                
                                                <option value="Open" <?php if ($complaint_status == "Open") echo "selected"; ?>>Open</option>
                                                <option value="In Progress" <?php if ($complaint_status == "In Progress") echo "selected"; ?>>In Progress</option>
                                                <option value="Resolved" <?php if ($complaint_status == "Resolved") echo "selected"; ?>>Resolved</option>
                                                <?php if ($logged_in_user_role == 'admin') : ?>
                                                    <option value="Closed" <?php if ($complaint_status == "Closed") echo "selected"; ?>>Closed</option>
                                                <?php endif; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="urgency_level_<?php echo $complaint_id; ?>" class="form-label">Urgency:</label>
                                        <select onchange='updateData(this, "<?php echo $complaint_id; ?>","urgency_level", "complaint", "complaint_id")' 
                                                id="urgency_level_<?php echo $complaint_id; ?>" 
                                                class='form-select' 
                                                name="urgency_level"
                                                <?php echo ($complaint_status == "Closed" || $complaint_status == "Withdrawn") ? 'disabled' : ''; ?>>
                                            <option value="low" <?php if ($current_urgency == "low") echo "selected"; ?>>Low</option>
                                            <option value="medium" <?php if ($current_urgency == "medium") echo "selected"; ?>>Medium</option>
                                            <option value="high" <?php if ($current_urgency == "high") echo "selected"; ?>>High</option>
                                            <option value="critical" <?php if ($current_urgency == "critical") echo "selected"; ?>>Critical</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                        <label class="form-label d-block d-md-none">&nbsp;</label>
                                        <?php if ($logged_in_user_role == 'admin') : ?>
                                            <button type="button" onclick="deleteData(<?php echo $complaint_id; ?>,'complaint', 'complaint_id')" class="btn btn-danger"> <i class="fa-solid fa-trash"></i> Delete</button>
                                        <?php endif; ?>
                                        <a href="get_complaint_report.php?complaint_id=<?php echo $complaint_id; ?>" target="_blank" class="btn btn-info py-2 px-4 text-white ms-2"><i class="fa-solid fa-print"></i> Print</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                <?php
                    } // End while loop
                } else {
                    echo "<div class='alert alert-info mt-4'>No complaints found matching your filters.</div>";
                }
                ?>
            </div>

            <?php include "pages/footer.php"; ?>
        </div>
    </div>

    <!-- JS Includes -->
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
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
    .urgency-low { color: #28a745; font-weight: bold; }
    .urgency-medium { color: #ffc107; font-weight: bold; }
    .urgency-high { color: #fd7e14; font-weight: bold; }
    .urgency-critical { color: #dc3545; font-weight: bold; }

    /* Status String Colors */
    .status-open { color: #007bff; font-weight: bold; }
    .status-inprogress { color: #ffc107; font-weight: bold; }
    .status-resolved { color: #28a745; font-weight: bold; }
    .status-closed { color: #6c757d; font-weight: bold; }
    .status-withdrawn { color: #dc3545; font-weight: bold; }

    /* Tracking Bar */
    .track {
        position: relative;
        background-color: #ddd;
        height: 7px;
        display: flex;
        margin-bottom: 6rem !important;
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
    .track .step.active .text {
        font-weight: 600;
        color: #000;
    }

    .track .step.active::before {
        background: #0d6efd;
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
    .track .step.active.open ~ .step::before { background: #ddd; }
    .track .step.active.inprogress ~ .step::before { background: #ddd; }
    .track .step.active.resolved ~ .step::before { background: #ddd; }

    .text-md-end {
        text-align: right !important;
    }

    /* Add space for label alignment */
    .align-items-end .form-label {
        display: block;
        visibility: hidden;
    }
    
    .align-items-end .col-md-4:first-child .form-label,
    .align-items-end .col-md-3:first-child .form-label,
    .align-items-end .col-md-3:nth-child(2) .form-label {
        visibility: visible;
    }

    /* Align buttons in the new filter bar */
    .g-3.align-items-end .col-md-3 {
        display: flex;
        align-items: flex-end;
    }
</style>
</html>