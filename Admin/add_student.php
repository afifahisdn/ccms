<!DOCTYPE html>
<html lang="en">

<?php
/*
* admin/add_student.php
*
* Form for Admins to manually create a new student account.
*/
include "pages/header.php"; // Includes assets, connection, get
include "admin.php";      // Includes session check, sets $logged_in_user_role
include "checkAdmin.php"; // Ensure only 'admin' role can access
?>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <a href="index.php">
                        <img src="assets/images/logo.png" alt="CCMS Logo">
                        <div class="mt-1 mb-2 border-bottom pb-2"></div>
                        <h6>‎ ‎ ‎<b>ADMINISTRATOR PAGE</b></h6>
                        <div class="border-bottom"></div>
                    </a>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-item">
                            <a href="index.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <!-- Students link is active -->
                        <li class="sidebar-item active">
                            <a href="student.php" class='sidebar-link'>
                                <i class="bi bi-people-fill"></i>
                                <span>Students</span>
                            </a>
                        </li>
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
                <h3>Add New Student</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="student.php">Student List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add New Student</li>
                    </ol>
                </nav>
            </div>

            <div class="page-content">
                <section class="row justify-content-center">
                    <div class="col-md-7">
                        <div class="card card-register">
                            <div class="card-body">
                                <!-- This form ID is used by add.js -> addStudentAdmin -->
                                <form method="post" id="basicform" data-parsley-validate="" onsubmit="return false;">
                                    <div class="form-group">
                                        <label for="inputName">Name:</label>
                                        <input id="inputName" type="text" name="name" data-parsley-trigger="change" required="" placeholder="Enter Full Name" autocomplete="off" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail">Email Address:</label>
                                        <input id="inputEmail" type="email" name="email" data-parsley-trigger="change" required="" placeholder="e.g., username@college.edu" autocomplete="off" class="form-control">
                                    </div>

                                    <!-- Added Student ID Number Field -->
                                    <div class="form-group">
                                        <label for="student_id_number">Student ID Number:</label>
                                        <input id="student_id_number" type="text" name="student_id_number" data-parsley-trigger="change" required="" placeholder="e.g., STU1234567" autocomplete="off" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="inputPhone">Phone Number:</label>
                                        <input id="inputPhone" type="text" name="phone" data-parsley-trigger="change" required="" placeholder="e.g., 012-3456789" autocomplete="off" class="form-control">
                                    </div>

                                    <!-- Added Room Number Field -->
                                    <div class="form-group">
                                        <label for="room_number">Room Number:</label>
                                        <input id="room_number" type="text" name="room_number" data-parsley-trigger="change" required="" placeholder="e.g., A-101" autocomplete="off" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="inputAddress">Address (Permanent):</label>
                                        <textarea id="inputAddress" name="address" data-parsley-trigger="change" required="" placeholder="Enter Address" autocomplete="off" class='form-control' rows=3></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputGender">Gender:</label>
                                        <select class="form-select" name="gender" id="gender" aria-label="Default select example">
                                            <option value="1" selected>Male</option>
                                            <option value="2">Female</option> <!-- Corrected value -->
                                        </select>
                                    </div>

                                    <div class='form-group'>
                                        <label for='inputPassword'>Password:</label>
                                        <input id='inputPassword' type='password' name='password' placeholder='Password (min. 6 characters)' required='' class='form-control'>
                                    </div>

                                    <div class='form-group'>
                                        <label for='inputRepeatPassword'>Confirm Password:</label>
                                        <input id='inputRepeatPassword' data-parsley-equalto='#inputPassword' type='password' required='' name='conf_password' placeholder='Confirm Password' class='form-control'>
                                    </div>

                                    <!-- Updated JS function call -->
                                    <button type='button' onclick='addStudentAdmin(this.form)' name='submit' class='btn btn-primary btn-block'>Save Student</button>
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
    <!-- Parsley validation (if you want to use data-parsley-validate) -->
    <!-- <script src="assets/vendors/parsleyjs/parsley.min.js"></script> -->
    <script src="assets/js/main.js"></script>
</body>

</html>