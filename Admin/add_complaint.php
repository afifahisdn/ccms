<?php
/*
* admin/add_complaint.php
*
* Form for Admins to manually create a complaint.
* Only accessible by 'admin' role.
*/

include "pages/header.php"; // Includes assets, connection, get
include "admin.php";      // Includes session check, gets $logged_in_user_role
include "checkAdmin.php"; // Ensures only 'admin' role can access
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
                            <h6>‎ ‎ ‎<b>ADMINISTRATOR PAGE</b></h6>
                            <div class="border-bottom"></div>
                        </a>
                    </div>
                </div>
                <div class="sidebar-menu ">
                    <ul class="menu">
                        <li class="sidebar-item">
                            <a href="index.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item ">
                            <a href="student.php" class='sidebar-link'>
                                <i class="bi bi-people-fill"></i>
                                <span>Students</span>
                            </a>
                        </li>
                        <!-- Set Complaint link as active -->
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
                        <li class="sidebar-item">
                            <a href="department.php" class='sidebar-link'>
                                <i class="bi bi-building"></i>
                                <span>Departments</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="staff.php" class='sidebar-link'>
                                <i class="bi bi-person-badge-fill"></i>
                                <span>Staff</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="dormitory.php" class='sidebar-link'>
                                <i class="bi bi-house-door-fill"></i>
                                <span>Dormitories</span>
                            </a>
                        </li>
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
                <h3>Add New Complaint (Manual Entry)</h3>
                <div class="mt-3 mb-3 border-bottom pb-2"></div>
            </div>
            <div class="row">
                <div class="col-lg-10">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="complaint.php">Complaint List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add New Complaint</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Added enctype for file uploads -->
                                <form action="#" class="p-3 bg-white" method="post" id="complaintFormAdmin" enctype="multipart/form-data">
                                    <h4>Complaint Details</h4>

                                    <div class="row mt-4">
                                        <!-- Select Student -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="student_id">Student <span class="text-danger">*</span></label>
                                            <select id="student_id" class="form-select" name="student_id" required>
                                                <option value="">Please Select Student</option>
                                                <?php
                                                // Fetch Students
                                                $getall_students = getAllStudents();
                                                if ($getall_students) {
                                                    while ($student_row = mysqli_fetch_assoc($getall_students)) {
                                                        echo '<option value="' . $student_row["student_id"] . '">'
                                                            . htmlspecialchars($student_row["name"]) . ' (' . htmlspecialchars($student_row["student_id_number"]) . ')'
                                                            . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <!-- Select Dormitory -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="dormitory_id">Dormitory <span class="text-danger">*</span></label>
                                            <select id="dormitory_id" class="form-select" name="dormitory_id" required>
                                                <option value="">Please Select Dormitory</option>
                                                <?php
                                                // Fetch Dormitories
                                                $getall_dorms = getAllDormitory();
                                                if ($getall_dorms) {
                                                    while ($dorm_row = mysqli_fetch_assoc($getall_dorms)) {
                                                        echo '<option value="' . $dorm_row["dormitory_id"] . '">'
                                                            . htmlspecialchars($dorm_row["dormitory_name"])
                                                            . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Room Number -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="room_number">Room Number <span class="text-danger">*</span></label>
                                            <input type="text" name="room_number" id="room_number" class="form-control" placeholder="e.g., A-101" required>
                                        </div>
                                        <!-- Complaint Title -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="complaint_title">Complaint Title <span class="text-danger">*</span></label>
                                            <input type="text" name="complaint_title" id="complaint_title" class="form-control" placeholder="Brief title of the issue" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- === UPDATED CATEGORY DROPDOWN === -->
                                        <div class="col-md-6 mb-3">
                                            <label class="text-black" for="category_id">Category <span class="text-danger">*</span></label>
                                            <!-- The name is now "category_id" -->
                                            <select id="category_id" class='form-control' name="category_id" required>
                                                <option value="">-- Select Category --</option>
                                                <?php
                                                // Use new function to get categories from DB
                                                $getallCategories = getAllCategories();
                                                if ($getallCategories) {
                                                    while ($cat_row = mysqli_fetch_assoc($getallCategories)) {
                                                        echo '<option value="' . $cat_row["category_id"] . '">'
                                                            . htmlspecialchars(ucfirst($cat_row["category_name"]))
                                                            . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <!-- Urgency Level Dropdown -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="urgency_level">Urgency Level <span class="text-danger">*</span></label>
                                            <select id="urgency_level" class="form-select" name="urgency_level" required>
                                                <option value="low">Low</option>
                                                <option value="medium" selected>Medium</option>
                                                <option value="high">High</option>
                                                <option value="critical">Critical</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Description Textarea -->
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label class="form-label" for="complaint_description">Description</label>
                                            <textarea name="complaint_description" id="complaint_description" cols="30" rows="5" class="form-control" placeholder="Provide details about the complaint..."></textarea>
                                        </div>
                                    </div>

                                    <!-- Photo Upload -->
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <label class="form-label" for="photo">Upload Photo (Optional)</label>
                                            <input type="file" name="photo" id="photo" class="form-control">
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <!-- Calls addComplaintAdmin from main.js -->
                                            <button type="button" onclick="addComplaintAdmin(this.form)" class="btn btn-primary py-2 px-4 text-white">Submit Complaint</button>
                                            <a href="complaint.php" class="btn btn-secondary py-2 px-4 ms-2">Cancel</a>
                                        </div>
                                    </div>
                                </form>
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
    <script src="assets/js/main.js"></script>
</body>

</html>