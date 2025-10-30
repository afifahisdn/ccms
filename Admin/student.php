<!DOCTYPE html>
<html lang="en">

<?php
/*
* admin/student.php
*
* Displays a list of all students.
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

                        <!-- Student link is now active -->
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
                <h3>Students</h3>
                <div class="mt-3 mb-3 border-bottom pb-2"></div>
            </div>
            <div class="row">
                <div class="col-lg-9">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Student List</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-3 text-lg-end">
                    <a href="add_student.php" class="btn btn-primary"> Add New Student</a>
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
                                            <th>Name</th>
                                            <th>Student ID</th> <!-- Added -->
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Room No.</th> <!-- Added -->
                                            <th>Address</th>
                                            <th>Gender</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Fetch all students
                                        $getall = getAllStudents();
                                        if ($getall) {
                                            while ($row = mysqli_fetch_assoc($getall)) {
                                                $student_id = $row["student_id"];
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["student_id_number"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["email"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["phone"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["room_number"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["address"]); ?></td>
                                                    <td><?php echo ($row["gender"] == "1") ? "Male" : "Female"; ?></td>
                                                    <td>
                                                        <!-- Calls deleteData from delete.js -->
                                                        <button type="button" onclick="deleteData(<?php echo $student_id; ?>, 'student', 'student_id')" class="btn btn-danger btn-sm">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                        <!-- You can add an edit button here if needed -->
                                                        <!-- <a href="edit_student.php?student_id=<?php echo $student_id; ?>" class="btn btn-info btn-sm"><i class="fa-solid fa-edit"></i></a> -->
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

    <!-- JS Includes -->
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    
    <!-- Simple Datatables (included via assets.php) -->
    <!-- <script src="assets/vendors/simple-datatables/simple-datatables.js"></script> -->
    <script>
        // Initialize Simple Datatable
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('datatablesSimple')) {
                new simpleDatatables.DataTable("#datatablesSimple");
            }
        });
    </script>
    
    <script src="assets/js/main.js"></script>
</body>

</html>