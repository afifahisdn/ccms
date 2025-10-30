<!DOCTYPE html>
<html lang="en">

<?php
/*
* admin/settings.php
*
* Allows staff/admin to change their password.
* Allows 'admin' role to change public website settings.
*/

include "pages/header.php"; // Includes assets, connection, get
include "admin.php";      // Includes session check, gets $logged_in_user_role, $logged_in_staff_id, $logged_in_user_email

// $logged_in_user_role is available from admin.php
// $logged_in_user_email is available from admin.php
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
                        <!-- Settings link active -->
                        <li class="sidebar-item active">
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
                <h3>Settings</h3>
                <div class="mt-3 mb-3 border-bottom pb-2"></div>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-9">
                        <div class="card">
                            <div class="card-body">
                                <!-- ACCOUNT SETTINGS (For All Staff) -->
                                <section class="row">
                                    <h6>ACCOUNT SETTINGS</h6>
                                    <hr>
                                    <p>Change your account password.</p>
                                    <p><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ChangePasswordModal">Change Password</button></p>
                                </section>

                                <!-- WEBSITE SETTINGS (Admin Only) -->
                                <?php if ($logged_in_user_role == "admin") : ?>
                                    <?php
                                    // Fetch settings once
                                    $setting_result = getAllSettings();
                                    $res = $setting_result ? mysqli_fetch_assoc($setting_result) : null;

                                    if ($res) {
                                        // Prepare image paths safely
                                        $header_img_path = "../" . $res["header_image"];
                                        $sub_img_path = "../" . $res["sub_image"];
                                        $about_img_path = "../" . $res["about_image"];
                                    ?>

                                        <!-- HEADER INFORMATION Section -->
                                        <section class="row">
                                            <h6 class="mt-5">HEADER INFORMATION (Public Pages)</h6>
                                            <hr>
                                            <div class="col-md-12 mt-3">
                                                <label for="header_title" class="form-label">Header Title</label>
                                                <input type="text" onchange='settingsUpdate(this, "header_title")' value="<?php echo htmlspecialchars($res["header_title"]); ?>" class="form-control" name="header_title" id="header_title" placeholder="Header Title" required>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <label for="header_desc" class="form-label">Header Description</label>
                                                <textarea onchange='settingsUpdate(this, "header_desc")' class="form-control" id="header_desc" required rows="3"><?php echo htmlspecialchars($res["header_desc"]); ?></textarea>
                                            </div>

                                            <!-- Header Image Upload -->
                                            <div class="col-md-6 mt-3">
                                                <form method="POST" enctype="multipart/form-data" id="headerImageForm">
                                                    <div class="mb-3">
                                                        <input type="hidden" name="field" value="header_image">
                                                        <label for="headerFile" class="form-label">Header Image</label>
                                                        <input class="form-control" onchange="uploadSettingImage(document.getElementById('headerImageForm'));" name="file" type="file" id="headerFile">
                                                    </div>
                                                </form>
                                                <?php if (!empty($res["header_image"]) && file_exists($header_img_path)) : ?>
                                                    <img class="mt-2 img-thumbnail" width="200" src='<?php echo $header_img_path; ?>?t=<?php echo time(); ?>' alt="Header Image Preview">
                                                <?php else : ?>
                                                    <p class="mt-2 text-muted">No header image uploaded.</p>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Sub Header Image Upload -->
                                            <div class="col-md-6 mt-3">
                                                <form method="POST" enctype="multipart/form-data" id="subImageForm">
                                                    <div class="mb-3">
                                                        <input type="hidden" name="field" value="sub_image">
                                                        <label for="subFile" class="form-label">Sub Header Image (Banner)</label>
                                                        <input class="form-control" onchange="uploadSettingImage(document.getElementById('subImageForm'));" name="file" type="file" id="subFile">
                                                    </div>
                                                </form>
                                                <?php if (!empty($res["sub_image"]) && file_exists($sub_img_path)) : ?>
                                                    <img class="mt-2 img-thumbnail" width="200" src='<?php echo $sub_img_path; ?>?t=<?php echo time(); ?>' alt="Sub Header Image Preview">
                                                <?php else : ?>
                                                    <p class="mt-2 text-muted">No sub-header image uploaded.</p>
                                                <?php endif; ?>
                                            </div>
                                        </section>

                                        <!-- ABOUT SETTINGS Section -->
                                        <section class="row">
                                            <h6 class="mt-5">ABOUT SETTINGS (Public Pages)</h6>
                                            <hr>
                                            <div class="col-md-12 mt-3">
                                                <label for="about_title" class="form-label">About Title</label>
                                                <input type="text" onchange='settingsUpdate(this, "about_title")' value="<?php echo htmlspecialchars($res["about_title"]); ?>" class="form-control" id="about_title" placeholder="About Title" required>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <label for="about_desc" class="form-label">About Description</label>
                                                <textarea onchange='settingsUpdate(this, "about_desc")' class="form-control" id="about_desc" required rows="5"><?php echo htmlspecialchars($res["about_desc"]); ?></textarea>
                                            </div>

                                            <!-- About Image Upload -->
                                            <div class="col-md-6 mt-3">
                                                <form method="POST" enctype="multipart/form-data" id="aboutImageForm">
                                                    <div class="mb-3">
                                                        <input type="hidden" name="field" value="about_image">
                                                        <label for="aboutFile" class="form-label">About Image</label>
                                                        <input class="form-control" onchange="uploadSettingImage(document.getElementById('aboutImageForm'));" name="file" type="file" id="aboutFile">
                                                    </div>
                                                </form>
                                                <?php if (!empty($res["about_image"]) && file_exists($about_img_path)) : ?>
                                                    <img class="mt-2 img-thumbnail" width="200" src='<?php echo $about_img_path; ?>?t=<?php echo time(); ?>' alt="About Image Preview">
                                                <?php else : ?>
                                                    <p class="mt-2 text-muted">No about image uploaded.</p>
                                                <?php endif; ?>
                                            </div>
                                        </section>

                                        <!-- CONTACT SETTINGS Section -->
                                        <section class="row">
                                            <h6 class="mt-5">CONTACT SETTINGS (Public Pages)</h6>
                                            <hr>
                                            <div class="col-md-12 mt-3">
                                                <label for="company_phone" class="form-label">College Phone Number</label>
                                                <input type="text" onchange='settingsUpdate(this, "company_phone")' value="<?php echo htmlspecialchars($res["company_phone"]); ?>" class="form-control" id="company_phone" placeholder="College Phone Number" required>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="company_email" class="form-label">College Email Address</label>
                                                <input type="email" onchange='settingsUpdate(this, "company_email")' value="<?php echo htmlspecialchars($res["company_email"]); ?>" class="form-control" id="company_email" placeholder="College Email Address" required>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="company_address" class="form-label">College Address</label>
                                                <input type="text" onchange='settingsUpdate(this, "company_address")' value="<?php echo htmlspecialchars($res["company_address"]); ?>" class="form-control" id="company_address" placeholder="College Address" required>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="link_facebook" class="form-label">Facebook Link</label>
                                                <input type="url" onchange='settingsUpdate(this, "link_facebook")' value="<?php echo htmlspecialchars($res["link_facebook"]); ?>" class="form-control" id="link_facebook" placeholder="Facebook Link">
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="link_twitter" class="form-label">Twitter Link</label>
                                                <!-- Note: Original file had a typo "link_twiiter", assuming DB is "link_twitter" -->
                                                <input type="url" onchange='settingsUpdate(this, "link_twitter")' value="<?php echo htmlspecialchars($res["link_twitter"]); ?>" class="form-control" id="link_twitter" placeholder="Twitter Link">
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="link_instagram" class="form-label">Instagram Link</label>
                                                <input type="url" onchange='settingsUpdate(this, "link_instagram")' value="<?php echo htmlspecialchars($res["link_instagram"]); ?>" class="form-control" id="link_instagram" placeholder="Instagram Link">
                                            </div>

                                    <?php
                                    } else {
                                        echo "<div class='alert alert-danger'>Could not load settings data from the database.</div>";
                                    }
                                    ?>
                                    </section>
                                <?php endif; // End Admin-Only Settings 
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php include "pages/footer.php"; ?>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="ChangePasswordModal" tabindex="-1" aria-labelledby="ChangePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="ChangePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="changePasswordForm" method="post" onsubmit="return false;">
                    <div class="modal-body bg-white">
                        <div class="col-md-12 mt-1">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Current Password" required>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="New Password" required>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="confirm_new_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_new_password" id="confirm_new_password" placeholder="Confirm New Password" required>
                        </div>
                        <!-- Pass the logged-in user's email -->
                        <input type="hidden" class="form-control" name="email" value="<?php echo htmlspecialchars($logged_in_user_email); ?>" id="email">
                    </div>
                    <div class="modal-footer ">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- Calls changePasswordAdmin from main.js -->
                        <button type="button" onclick="changePasswordAdmin(document.getElementById('changePasswordForm'))" name="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- JS Includes -->
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>