<!DOCTYPE html>
<html lang="en">

<?php
/*
* admin/index.php
*
* Main dashboard for the Admin/Staff panel.
* Displays overview counts.
*/

// Includes assets, connection, and all get/add/update/delete functions
include "pages/header.php";
// Includes session check, sets $logged_in_user_role, $logged_in_staff_id, etc.
include "admin.php";

// --- POOR MAN'S CRON JOB ---
// Run the auto-close logic every time an admin/staff visits the dashboard.
autoCloseComplaints();
// --- END CRON JOB ---
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
                            <?php if (isset($logged_in_user_role) && $logged_in_user_role == "admin") : ?>
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
                        <!-- Set Dashboard as active -->
                        <li class="sidebar-item active ">
                            <a href="index.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <!-- Admin-Only Links -->
                        <?php if (isset($logged_in_user_role) && $logged_in_user_role == 'admin') : ?>
                            <li class="sidebar-item ">
                                <a href="student.php" class='sidebar-link'>
                                    <i class="bi bi-people-fill"></i>
                                    <span>Students</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- All Staff Links -->
                        <li class="sidebar-item">
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

                        <!-- Admin-Only Links -->
                        <?php if (isset($logged_in_user_role) && $logged_in_user_role == 'admin') : ?>
                            <li class="sidebar-item">
                                <a href="department.php" class='sidebar-link'>
                                    <i class="bi bi-building"></i>
                                    <span>Departments</span>
                                </a>
                            </li>
                            <!-- NEW CATEGORIES LINK -->
                            <li class="sidebar-item">
                                <a href="categories.php" class='sidebar-link'>
                                    <i class="bi bi-tags-fill"></i>
                                    <span>Categories</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Profile/Staff Link -->
                        <?php if (isset($logged_in_user_role) && $logged_in_user_role == "admin") : ?>
                            <li class="sidebar-item">
                                <a href="staff.php" class='sidebar-link'>
                                    <i class="bi bi-person-badge-fill"></i>
                                    <span>Staff</span>
                                </a>
                            </li>
                        <?php else : ?>
                            <!-- Link for staff to view their own profile -->
                            <li class="sidebar-item">
                                <a href="staff_edit.php?staff_id=<?php echo htmlspecialchars($logged_in_staff_id); ?>" class='sidebar-link'>
                                    <i class="bi bi-person-fill"></i>
                                    <span>Profile</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Admin-Only Links -->
                        <?php if (isset($logged_in_user_role) && $logged_in_user_role == 'admin') : ?>
                            <li class="sidebar-item">
                                <a href="dormitory.php" class='sidebar-link'>
                                    <i class="bi bi-house-door-fill"></i>
                                    <span>Dormitories</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- All Staff Links -->
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
                <h2 class="mt-3 text-uppercase font-weight-bold"><b>‎ Current Overview</b></h2>
                <div class="mt-3 mb-5 border-bottom pb-2"></div>
            </header>
            <div class="page-content">
                <!-- Removed Total Sales Row -->
                <section class="row">
                    <div class="col-12 col-lg-12">
                        <div class="row">
                            <!-- Departments Card -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon bg-primary">
                                                    <i class="iconly-boldBookmark text-white"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Departments</h6>
                                                <h6 class="font-extrabold mb-0"><?php dataCount('department'); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Students Card -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon bg-success">
                                                    <i class="iconly-boldProfile text-white"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Students</h6>
                                                <h6 class="font-extrabold mb-0"><?php dataCount('student'); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Staff Card -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon bg-info">
                                                    <i class="iconly-boldAdd-User text-white"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Staff</h6>
                                                <h6 class="font-extrabold mb-0"><?php dataCount('staff'); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Total Complaints Card -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon bg-warning">
                                                    <i class="iconly-boldDocument text-white"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Total Complaints</h6>
                                                <h6 class="font-extrabold mb-0"><?php dataCount('complaint'); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Complaint Status Row -->
                    <div class="col-12 col-lg-12">
                        <div class="row">
                            <!-- Open Complaints Card -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon bg-danger">
                                                    <i class="iconly-boldTime-Circle text-white"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Open</h6>
                                                <!-- UPDATED: Check for 'Open' string -->
                                                <h6 class="font-extrabold mb-0"><?php dataCountWhere('complaint', " complaint_status = 'Open' "); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- In Progress Complaints Card -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon bg-warning">
                                                    <i class="iconly-boldWork text-white"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">In Progress</h6>
                                                <!-- UPDATED: Check for 'In Progress' string -->
                                                <h6 class="font-extrabold mb-0"><?php dataCountWhere('complaint', " complaint_status = 'In Progress' "); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Resolved Complaints Card -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon bg-info">
                                                    <i class="iconly-boldShield-Done text-white"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Resolved</h6>
                                                <!-- UPDATED: Check for 'Resolved' string -->
                                                <h6 class="font-extrabold mb-0"><?php dataCountWhere('complaint', " complaint_status = 'Resolved' "); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Closed Complaints Card -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon bg-secondary">
                                                    <i class="iconly-boldClose-Square text-white"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Closed</h6>
                                                <!-- UPDATED: Check for 'Closed' string -->
                                                <h6 class="font-extrabold mb-0"><?php dataCountWhere('complaint', " complaint_status = 'Closed'"); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php include "pages/footer.php"; ?>
        </div>
    </div>
    
    <!-- JS Includes -->
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendors/apexcharts/apexcharts.js"></script>
    <!-- <script src="assets/js/pages/dashboard.js"></script> --> <!-- Removed default dashboard JS -->
    <script src="assets/js/main.js"></script>
</body>

</html>