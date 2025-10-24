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
                <h3>Customer</h3>
                <div class="mt-3 mb-3 border-bottom pb-2"></div>
            </div>
            <div class="row">
                <div class="col-lg-9">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customer List</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-2">
                    <a href="add_customer.php" class="btn btn-primary"> Add New</a>
                </div>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-12">
                        <div class="row">
                            <table id="datatablesSimple" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Gender</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $getall = getAllcustomers();
                                    while ($row = mysqli_fetch_assoc($getall)) {
                                        $customer_id = $row["customer_id"]; ?>
                                    <tr>

                                        <td><?php echo $row["name"]; ?></td>
                                        <td><?php echo $row["email"]; ?></td>
                                        <td><?php echo $row["phone"]; ?></td>
                                        <td><?php echo $row["address"]; ?></td>
                                        <td><?php if ($row["gender"] == "1") {
                                                echo "Male";
                                            } else {
                                                echo "Female";
                                            } ?>
                                        </td>
                                        <td> 
                                            <button type="button" onclick="deleteData(<?php echo $row["customer_id"]; ?>,
                                            'customer', 'customer_id')" class="btn btn-darkblue"> 
                                            <i class="fa-solid fa-trash"></i></button>

                                        </td>
                                    </tr>

                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#datatablesSimple').DataTable();
                                });
                            </script>

                        </div>
                    </div>

                </section>
            </div>
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