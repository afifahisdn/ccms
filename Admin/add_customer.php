<!DOCTYPE html>
<html lang="en">

<?php include "pages/header.php"; ?>
<?php include "admin.php"; ?>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <a href="index.php">
                        <img src="assets/images/logo.png" alt="Velocity Express">
                        <h6>‎ ‎________________________</h6>
                        <h6>‎ ‎ ‎<b>ADMINISTRATOR PAGE</b></h6>
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

                        <li class="sidebar-item active">
                            <a href="customer.php" class='sidebar-link'>
                                <i class="bi bi-people-fill"></i>
                                <span>Customer</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="price.php" class='sidebar-link'>
                                <i class="bi bi-table"></i>
                                <span>Price Table</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="courier.php" class='sidebar-link'>
                                <i class="bi bi-truck"></i>
                                <span>Courier</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="message.php" class='sidebar-link'>
                                <i class="bi bi-chat"></i>
                                <span>Message</span>
                            </a>
                        </li>
                        <?php if (
                            isset($_SESSION["admin"]) &&
                            $_SESSION["admin"] == "admin"
                        ): ?>
                            <li class="sidebar-item">
                                <a href="branch.php" class='sidebar-link'>
                                    <i class="bi bi-columns"></i>
                                    <span>Branches</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="sidebar-item">
                            <a href="employee.php" class='sidebar-link'>
                                <i class="bi bi-person-fill"></i>
                                <span>Employee </span>
                            </a>
                        </li>
                        <?php if (
                            isset($_SESSION["admin"]) &&
                            $_SESSION["admin"] == "admin"
                        ): ?>
                            <li class="sidebar-item">
                                <a href="area.php" class='sidebar-link'>
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>Area</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="settings.php" class='sidebar-link'>
                                    <i class="bi bi-gear-fill"></i>
                                    <span>Settings</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="sidebar-item">
                            <a href="logout.php" class='sidebar-link'>
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Log Out</span>
                            </a>
                        </li>
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
                <h3>Customer Register</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customer List</li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Register</li>
                    </ol>
                </nav>
            </div>

            <div class="page-content">
                <section class="row justify-content-center">
                    <div class="col-md-7">
                        <div class="card card-register">
                            <div class="card-body">
                                <form action="" method="post" id="basicform" data-parsley-validate="">
                                    <div class="form-group">
                                        <label for="inputName">Name:</label>
                                        <input id="inputName" type="text" name="name" data-parsley-trigger="change" required="" placeholder="Enter Full Name" autocomplete="off" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail">Email Address:</label>
                                        <input id="inputEmail" type="email" name="email" data-parsley-trigger="change" required="" placeholder="Enter email" autocomplete="off" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="inputPhone">Phone Number:</label>
                                        <input id="inputPhone" type="text" name="phone" data-parsley-trigger="change" required="" placeholder="Enter Phone Number" autocomplete="off" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="inputAddress">Address:</label>
                                        <textarea id="inputAddress" name="address" data-parsley-trigger="change" required="" placeholder="Enter Address" autocomplete="off" class='form-control' rows=5></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputGender">Gender:</label>
                                        <select class="form-select" name="gender" id="gender" aria-label="Default select example">
                                            <option value=1 selected>Male</option>
                                            <option value=0>Female</option>
                                        </select>
                                    </div>

                                    <div class='form-group'>
                                        <label for='inputPassword'>Password:</label>
                                        <input id='inputPassword' type='password' name='password' placeholder='Password' required='' class='form-control'>
                                    </div>

                                    <div class='form-group'>
                                        <label for='inputRepeatPassword'>Confirm Password:</label>
                                        <input id='inputRepeatPassword' data-parsley-equalto='#inputPassword' type='password' required='' name='conf_password' placeholder='Confirm Password' class='form-control'>
                                    </div>

                                    <button type='button' onclick='addCustomerAdmin(this.form)' name='submit' class='btn btn-primary btn-block'>Save changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php include "pages/footer.php"; ?>
        </div>
    </div>

    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/vendors/apexcharts/apexcharts.js"></script>
    <script src="assets/js/pages/dashboard.js"></script>

    <script src="assets/js/main.js"></script>
</body>

</html>