<?php
/*
* admin/feedback.php
*
* Displays a list of all feedback submissions.
* Accessible by both 'admin' and 'staff' roles.
*/

include "pages/header.php"; // Includes assets, connection, get
include "admin.php";      // Includes session check, gets $logged_in_user_role, $logged_in_staff_id

// $logged_in_user_role is available from admin.php
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
                        <li class="sidebar-item">
                            <a href="complaint.php" class='sidebar-link'>
                                <i class="bi bi-exclamation-octagon-fill"></i>
                                <span>Complaints</span>
                            </a>
                        </li>
                        <!-- Feedback link active -->
                        <li class="sidebar-item active">
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
                <h3>Feedback</h3>
                <div class="mt-3 mb-3 border-bottom pb-2"></div>
            </div>
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Feedback List</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="datatablesSimple" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Subject</th>
                                                <th>Message</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Use new function name
                                            $getall = getAllFeedback();
                                            if ($getall) {
                                                while ($row = mysqli_fetch_assoc($getall)) {
                                            ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row["name"]); ?></td>
                                                        <td><?php echo htmlspecialchars($row["email"]); ?></td>
                                                        <td><?php echo htmlspecialchars($row["subject"]); ?></td>
                                                        <td><?php echo htmlspecialchars($row["message"]); ?></td>
                                                        <td><?php echo date("Y-m-d H:i", strtotime($row["date_updated"])); ?></td>
                                                        <td>
                                                            <!-- Delete button only for admin -->
                                                            <?php if ($logged_in_user_role == "admin") : ?>
                                                                <!-- Updated deleteData parameters -->
                                                                <button type="button" onclick="deleteData(<?php echo $row['feedback_id']; ?>, 'feedback', 'feedback_id')" class="btn btn-danger btn-sm">
                                                                    <i class="fa-solid fa-trash"></i> Delete
                                                                </button>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                            <?php
                                                } // end while
                                            } // end if
                                            ?>
                                        </tbody>
                                    </table>
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

    <!-- Simple Datatables (included via assets.php) -->
    <!-- <script src="assets/vendors/simple-datatables/simple-datatables.js"></script> -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('datatablesSimple')) {
                new simpleDatatables.DataTable("#datatablesSimple");
            }
        });
    </script>

    <script src="assets/js/main.js"></script>
</body>
</html>