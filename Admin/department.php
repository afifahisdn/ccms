<?php
/*
* admin/department.php
*
* Manages staff departments (e.g., Maintenance, IT, Cleaning).
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
                <div class="sidebar-menu">
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
                        <!-- Departments link active -->
                        <li class="sidebar-item active">
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
                <h3>Departments</h3>
                <div class="mt-3 mb-3 border-bottom pb-2"></div>
            </div>
            <div class="row">
                <div class="col-lg-10">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Department List</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-2 text-lg-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#DepartmentModal"> Add New</button>
                </div>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Department Name</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $getall = getAllDepartment();
                                        if ($getall) {
                                            while ($row = mysqli_fetch_assoc($getall)) {
                                                $department_id = $row["department_id"];
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row["department_name"]); ?></td>
                                                    <td><?php echo htmlspecialchars(ucfirst($row["department_type"])); ?></td>
                                                    <td>
                                                        <button type="button" onclick="deleteData(<?php echo $department_id; ?>, 'department', 'department_id')" class="btn btn-danger btn-sm">
                                                            <i class="fa-solid fa-trash"></i> Delete
                                                        </button>
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
                </section>
            </div>

            <?php include "pages/footer.php"; ?>
        </div>
    </div>


    <!-- Add Department Modal -->
    <div class="modal fade" id="DepartmentModal" tabindex="-1" aria-labelledby="DepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="DepartmentModalLabel">Add New Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addDepartmentForm" method="post" onsubmit="return false;">
                    <div class="modal-body bg-white">
                        <div class="mb-3">
                            <label for="department_name" class="form-label">Department Name</label>
                            <input type="text" class="form-control" name="department_name" id="department_name" placeholder="e.g., Maintenance Department" required>
                        </div>
                        <div class="mb-3">
                            <label for="department_type" class="form-label">Department Type</label>
                            <select class="form-select" name="department_type" id="department_type" required>
                                <option value="">Select Type</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="it">IT</option>
                                <option value="cleaning">Cleaning</option>
                                <option value="security">Security</option>
                                <option value="administration">Administration</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- Calls addDepartment from add.js -->
                        <button type="button" onclick="addDepartment(document.getElementById('addDepartmentForm'))" name="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
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