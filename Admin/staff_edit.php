<?php
/*
* admin/staff_edit.php
*
* Form for Admins to edit staff, or for Staff to edit their own profile.
*/

include "pages/header.php"; // Includes assets, connection, get
include "admin.php";      // Includes session check, gets $logged_in_user_role, $logged_in_staff_id

// --- Security Check ---
// Get the ID of the staff member being edited from the URL
$staff_id_to_edit = isset($_REQUEST["staff_id"]) ? (int)$_REQUEST["staff_id"] : 0;
// $logged_in_staff_id and $logged_in_user_role are from admin.php

// Redirect if:
// 1. No staff_id is provided in the URL OR
// 2. The user is NOT an admin AND they are trying to edit someone else's profile
if ($staff_id_to_edit === 0 || ($logged_in_user_role !== 'admin' && $staff_id_to_edit !== $logged_in_staff_id)) {
    // Redirect non-admins to their own profile edit page
    if ($logged_in_user_role !== 'admin' && $logged_in_staff_id > 0) {
        header("Location: staff_edit.php?staff_id=" . $logged_in_staff_id);
    } else {
        // Redirect admins with no ID or if session is invalid
        header("Location: index.php");
    }
    exit; // Stop script execution
}
// --- End Security Check ---

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

                        <!-- Admin-Only Links -->
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

                        <!-- Admin-Only Links -->
                        <?php if ($logged_in_user_role == 'admin') : ?>
                            <li class="sidebar-item">
                                <a href="department.php" class='sidebar-link'>
                                    <i class="bi bi-building"></i>
                                    <span>Departments</span>
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

                        <!-- Admin-Only Links -->
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
                <!-- Dynamic Title -->
                <h3><?php echo ($logged_in_user_role == 'admin' && $staff_id_to_edit != $logged_in_staff_id) ? 'Edit Staff Member' : 'Edit My Profile'; ?></h3>
            </div>
            <div class="row">
                <div class="col-lg-10">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <!-- Dynamic Breadcrumb -->
                            <?php if ($logged_in_user_role == "admin") : ?>
                                <li class="breadcrumb-item"><a href="staff.php">Staff List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Staff</li>
                            <?php else : ?>
                                <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="page-content">
                <section class="row justify-content-center">
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                // Fetch the staff member's data using the validated ID
                                $getall = getStaffById($staff_id_to_edit);
                                if ($getall && $row = mysqli_fetch_assoc($getall)) :
                                    $staff_id = $row["staff_id"]; // Use the ID from the fetched row
                                ?>
                                    <form onsubmit="return false;">
                                        <div class="form-group mt-2">
                                            <label for="inputName">Name</label>
                                            <input id="inputName_<?php echo $staff_id; ?>" type="text" name="name" onchange="updateData(this, '<?php echo $staff_id; ?>', 'name', 'staff', 'staff_id');" value="<?php echo htmlspecialchars($row['name']); ?>" required placeholder="Enter Full Name" autocomplete="off" class="form-control">
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="inputEmail">Email address</label>
                                            <input id="inputEmail_<?php echo $staff_id; ?>" type="email" name="email" onchange="updateData(this, '<?php echo $staff_id; ?>', 'email', 'staff', 'staff_id');" value="<?php echo htmlspecialchars($row['email']); ?>" required placeholder="Enter email" autocomplete="off" class="form-control">
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="inputPhone">Phone Number</label>
                                            <input id="inputPhone_<?php echo $staff_id; ?>" type="text" name="phone" required placeholder="Enter Phone Number" autocomplete="off" onchange="updateData(this, '<?php echo $staff_id; ?>', 'phone', 'staff', 'staff_id');" value="<?php echo htmlspecialchars($row['phone']); ?>" class="form-control">
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="inputNRIC">NRIC</label>
                                            <input id="inputNRIC_<?php echo $staff_id; ?>" type="text" name="nric" onchange="updateData(this, '<?php echo $staff_id; ?>', 'nric', 'staff', 'staff_id');" value="<?php echo htmlspecialchars($row['nric']); ?>" required placeholder="Enter NRIC Number" autocomplete="off" class="form-control">
                                        </div>

                                        <!-- Department Dropdown -->
                                        <div class="form-group mt-2">
                                            <label for="department_id_<?php echo $staff_id; ?>">Department</label>
                                            <select onchange='updateData(this, "<?php echo $staff_id; ?>","department_id", "staff", "staff_id")' id="department_id_<?php echo $staff_id; ?>" class="form-control" name="department_id" <?php echo ($logged_in_user_role != 'admin') ? 'disabled' : ''; // Disable for non-admins 
                                                                                                                                                                                                                                                                        ?>>
                                                <option value="">Select Department</option>
                                                <?php
                                                // Fetch all departments
                                                $getallDepts = getAllDepartment();
                                                if ($getallDepts) {
                                                    while ($row2 = mysqli_fetch_assoc($getallDepts)) {
                                                        $selected = ($row['department_id'] == $row2['department_id']) ? 'selected' : '';
                                                        echo '<option value="' . $row2['department_id'] . '" ' . $selected . '>'
                                                            . htmlspecialchars($row2['department_name'])
                                                            . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <!-- Role Dropdown (Admin Only) -->
                                        <?php if ($logged_in_user_role == 'admin') : ?>
                                            <div class="form-group mt-2">
                                                <label for="staff_role_<?php echo $staff_id; ?>">Role</label>
                                                <select onchange='updateData(this, "<?php echo $staff_id; ?>","staff_role", "staff", "staff_id")' id="staff_role_<?php echo $staff_id; ?>" class="form-control" name="staff_role">
                                                    <option value="staff" <?php if ($row['staff_role'] == 'staff') echo 'selected'; ?>>Staff</option>
                                                    <option value="admin" <?php if ($row['staff_role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                                </select>
                                            </div>
                                        <?php endif; ?>

                                        <div class="form-group mt-2">
                                            <label for="inputAddress">Address</label>
                                            <textarea id="inputAddress_<?php echo $staff_id; ?>" name="address" onchange="updateData(this, '<?php echo $staff_id; ?>', 'address', 'staff', 'staff_id');" required placeholder="Enter Address" autocomplete="off" class="form-control" rows="3"><?php echo htmlspecialchars($row['address']); ?></textarea>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="inputGender">Gender</label>
                                            <select onchange='updateData(this, "<?php echo $staff_id; ?>","gender", "staff", "staff_id")' id="gender_<?php echo $staff_id; ?>" class="form-control" name="gender">
                                                <option value="1" <?php if ($row['gender'] == "1") echo 'selected'; ?>>Male</option>
                                                <option value="2" <?php if ($row['gender'] == "2") echo 'selected'; ?>>Female</option>
                                            </select>
                                        </div>
                                        <div class="mt-3">
                                            <a href="<?php echo ($logged_in_user_role == 'admin') ? 'staff.php' : 'index.php'; ?>" class="btn btn-secondary">Back</a>
                                        </div>
                                    </form>
                                <?php else : ?>
                                    <p class="text-danger">Staff member not found.</p>
                                    <a href="staff.php" class="btn btn-secondary">Back to List</a>
                                <?php endif; ?>
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