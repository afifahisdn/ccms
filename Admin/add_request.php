<!DOCTYPE html>
<html lang="en">

<?php include "pages/header.php"; ?>
<?php include "admin.php"; ?>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between mt-0 px-2">
                    <a href="index.php">
                            <img src="assets/images/logo.png" alt="Velocity Express">
                            <div class="mt-1 mb-2 border-bottom pb-2"></div>
                            <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == "admin"): ?>
                                <h6>‎ ‎ ‎<b>ADMINISTRATOR PAGE</b></h6>
                            <?php else: ?>
                                <h6>‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎<b>EMPLOYEE PAGE</b></h6>
                            <?php endif; ?>
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

                        <li class="sidebar-item active">
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
                                <span>Log out</span>
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
                <h3>Courier Request</h3>
                <div class="mt-3 mb-3 border-bottom pb-2"></div>
            </div>
            <div class="row">
                <div class="col-lg-10">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Courier List</li>
                            <li class="breadcrumb-item active" aria-current="page">Request Register</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-12">
                        <div class="row">

                        <form action="#" class="p-5 bg-white" method="post">
                            <h4>Sender Details</h4>
                            <div class="row mt-5">
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-12 mb-3 mb-md-0">
                                            <label class="text-black" for="customer_id">Customer</label>
                                            <select id="customer_id" class="form-control norad tx12" name="customer_id" type="text">
                                                <option value="">Please Select</option>
                                                <?php
                                                $getall = getAllcustomers();
                                                while ($row = mysqli_fetch_assoc($getall)) { ?>
                                                    <option value="<?php echo $row["customer_id"]; ?>">
                                                        <?php echo $row["name"]; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-12 mb-3 mb-md-0">
                                            <label class="text-black" for="sender_name">Sender Name</label>
                                            <input type="text" name="sender_name" id="sender_name" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="row form-group">
                                        <div class="col-md-12 mb-3 mb-md-0">
                                            <label class="text-black" for="sender_phone">Phone Number</label>
                                            <input type="text" name="sender_phone" id="sender_phone" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="row form-group">
                                        <div class="col-md-12 mb-3 mb-md-0">
                                            <label class="text-black" for="sender_address">Sender Address</label>
                                            <textarea name="sender_address" id="sender_address" cols="30" rows="3" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <h4>Parcel Details</h4>
                                <div class="col-md-6 mt-3">
                                    <div class="row form-group">
                                        <div class="col-md-12 mb-3 mb-md-0">
                                            <label class="text-black" for="weight">Weight</label>
                                            <input type="number" name="weight" id="weight" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-12 mb-3 mb-md-0">
                                            <label class="text-black" for="send_location">Pick Up Location</label>
                                            <select id="send_location" class="form-control norad tx12" name="send_location" type="text">
                                                <option value="">Please Select</option>
                                                <?php
                                                $getall = getAllArea();
                                                while ($row = mysqli_fetch_assoc($getall)) { ?>
                                                    <option value="<?php echo $row["area_id"]; ?>">
                                                        <?php echo $row["area_name"]; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-12 mb-3 mb-md-0">
                                            <label class="text-black" for="end_location">Sending Location</label>
                                            <select id="end_location" onchange="calculationAdmin(this)" class="form-control norad tx12" name="end_location" type="text">
                                                <option value="">Please Select</option>
                                                <?php
                                                $getall = getAllArea();
                                                while ($row = mysqli_fetch_assoc($getall)) { ?>
                                                    <option value="<?php echo $row["area_id"]; ?>">
                                                        <?php echo $row["area_name"]; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 mt-5">
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    Price :
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" disabled name="total" id="total" class="form-control">
                                                    <input type="hidden" name="total_fee" id="total_fee" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="mt-5">Receiver Details</h4>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-12 mb-3 mb-md-0">
                                            <label class="text-black" for="rec_name">Receiver Name</label>
                                            <input type="text" name="rec_name" id="rec_name" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-12 mb-3 mb-md-0">
                                            <label class="text-black" for="rec_phone">Phone Number</label>
                                            <input type="text" name="rec_phone" id="rec_phone" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label class="text-black" for="rec_address">Receiver Address</label>
                                    <textarea name="rec_address" id="rec_address" cols="30" rows="7" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <input type="button" onclick="addRequestAdmin(this.form)" value="Add Request" class="btn btn-primary py-2 px-4 text-white">
                                </div>
                            </div>
                        </form>

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

    <script src="assets/js/main.js"></script>
</body>

</html>