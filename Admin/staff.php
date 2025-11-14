<!DOCTYPE html>
<html lang="en">

<?php
/*
* admin/staff.php
*
* Manages staff users.
* - Admins see a list of all staff and can add/edit/delete.
* - Staff see only their own profile information with a link to edit.
*/
include "pages/header.php"; // Includes assets, connection, get
include "admin.php";      // Includes session check, sets $logged_in_user_role, $logged_in_staff_id
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

                        <!-- Profile/Staff Link (Active) -->
                        <?php if ($logged_in_user_role == "admin") : ?>
                            <li class="sidebar-item active">
                                <a href="staff.php" class='sidebar-link'>
                                    <i class="bi bi-person-badge-fill"></i>
                                    <span>Staff</span>
                                </a>
                            </li>
                        <?php else : ?>
                            <li class="sidebar-item active">
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
                <?php if ($logged_in_user_role == "admin") : ?>
                    <h3>Staff Management</h3>
                <?php else : ?>
                    <h3>My Profile</h3>
                <?php endif; ?>
                <div class="mt-3 mb-3 border-bottom pb-2"></div>
            </div>
            <div class="row">
                <div class="col-lg-10">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <?php if ($logged_in_user_role == "admin") : ?>
                                <li class="breadcrumb-item active" aria-current="page">Staff List</li>
                            <?php else : ?>
                                <li class="breadcrumb-item active" aria-current="page">Profile</li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                </div>
                <?php if ($logged_in_user_role == "admin") : ?>
                    <div class="col-lg-2 text-lg-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#StaffModal">
                            Add New Staff
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-sm table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>NRIC</th>
                                            <th>Address</th>
                                            <th>Department</th>
                                            <th>Gender</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($logged_in_user_role != "admin") {
                                            // Staff sees only their own profile
                                            $getall = getStaffById($logged_in_staff_id);
                                        } else {
                                            // Admin sees all staff
                                            $getall = getAllStaff();
                                        }

                                        if ($getall) {
                                            while ($row = mysqli_fetch_assoc($getall)) {
                                                $staff_id = $row["staff_id"];
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["email"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["phone"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["nric"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["address"]); ?></td>
                                                    <td>
                                                        <?php
                                                        // Department name is already joined in getAllStaff/getStaffById
                                                        echo htmlspecialchars($row["department_name"] ?? 'N/A');
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($row["gender"] == "1") ? "Male" : "Female"; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars(ucfirst($row["staff_role"])); ?></td>
                                                    <td>
                                                        <a href="staff_edit.php?staff_id=<?php echo $staff_id; ?>" class="btn btn-info btn-sm"><i class="fa-solid fa-edit"></i></a>
                                                        <?php if ($logged_in_user_role == "admin") : ?>
                                                            <button type="button" onclick="deleteData(<?php echo $staff_id; ?>, 'staff', 'staff_id')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php include "pages/footer.php"; ?>
        </div>
    </div>

    <!-- Modal: Add New Staff (Admin Only) -->
    <?php if ($logged_in_user_role == "admin") : ?>
        <div class="modal fade" id="StaffModal" tabindex="-1" aria-labelledby="staffModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="staffModalLabel">Add New Staff</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="post" id="addStaffForm" data-parsley-validate="" onsubmit="return false;">
                        <div class="modal-body bg-white">
                            <div class="form-group mt-2">
                                <label for="inputName">Name</label>
                                <input id="inputName" type="text" name="name" data-parsley-trigger="change" required="" placeholder="Enter Full Name" autocomplete="off" class="form-control">
                            </div>

                            <div class="form-group mt-2">
                                <label for="inputEmail">Email address</label>
                                <input id="inputEmail" type="email" name="email" data-parsley-trigger="change" required="" placeholder="Enter Email" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label for="inputPhone">Phone Number</label>
                                <input id="inputPhone" type="text" name="phone" data-parsley-trigger="change" required="" placeholder="Enter Phone Number" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label for="inputNRIC">NRIC</label>
                                <input id="inputNRIC" type="text" name="nric" data-parsley-trigger="change" required="" placeholder="Enter NRIC Number" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label for="department_id">Department</label>
                                <select id="department_id" class='form-control' name="department_id" required>
                                    <option value="">Select Department</option>
                                    <?php
                                    $getall_depts = getAllDepartment();
                                    if ($getall_depts) {
                                        while ($row_dept_modal = mysqli_fetch_assoc($getall_depts)) {
                                            echo '<option value="' . $row_dept_modal["department_id"] . '">'
                                                . htmlspecialchars($row_dept_modal["department_name"])
                                                . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="staff_role">Role</label>
                                <select id="staff_role" class='form-control' name="staff_role" required>
                                    <option value="staff" selected>Staff</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="inputAddress">Address</label>
                                <input id="inputAddress" type="text" name="address" data-parsley-trigger="change" required="" placeholder="Enter Address" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label for="inputGender">Gender</label>
                                <select class="form-select" name="gender" id="gender" aria-label="Default select example">
                                    <option value="1" selected>Male</option>
                                    <option value="2">Female</option> <!-- Corrected Value -->
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="inputPassword">Password</label>
                                <input id="inputPassword" type="password" name="password" placeholder="Password" required="" class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label for="inputRepeatPassword">Repeat Password</label>
                                <input id="inputRepeatPassword" data-parsley-equalto="#inputPassword" type="password" required="" name="conf_password" placeholder="Confirm Password" class="form-control">
                            </div>

                        </div>
                        <div class="modal-footer ">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <!-- Calls addStaff from add.js -->
                            <button type="button" onclick="addStaff(document.getElementById('addStaffForm'))" name="submit" class="btn btn-primary">Save Staff</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- JS Includes -->
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <!-- Simple Datatables (included via assets.php) -->
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