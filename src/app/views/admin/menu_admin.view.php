<?php
include 'controller/condb.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark" style="background-color: #4D6A44;">
        <div class="container mt-4">
            <a class="navbar-brand" href="<?= ROOT ?>/admin/dashboard">Admin Shopping</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= ROOT ?>/admin/dashboard">Dashboard</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">User</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= ROOT ?>/admin/user">จัดการผู้ใช้ทั้งหมด</a></li>
                            <!-- <li><a class="dropdown-item" href="#">จัดการพนักงาน</a></li> -->
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Product</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= ROOT ?>/admin/product">จัดการสินค้า</a></li>
                            <li><a class="dropdown-item" href="<?= ROOT ?>/admin/product_lot">จัดการสต๊อกสินค้า</a></li>
                            <!-- <li><a class="dropdown-item" href="#"></a></li> -->
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Order</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= ROOT ?>/admin/order_all">ออเดอร์สั่งซื้อ-ทั้งหมด</a></li>
                            <li><a class="dropdown-item" href="<?= ROOT ?>/admin/order_unpaid">ออเดอร์สั่งซื้อ-ยังไม่ได้ชำระ</a></li>
                            <li><a class="dropdown-item" href="<?= ROOT ?>/admin/order_paid">ออเดอร์สั่งซื้อ-ชำระแล้ว</a></li>
                            <li><a class="dropdown-item" href="<?= ROOT ?>/admin/order_shipping">ออเดอร์สั่งซื้อ-กำลังจัดส่ง</a></li>
                            <li><a class="dropdown-item" href="<?= ROOT ?>/admin/order_delivered">ออเดอร์สั่งซื้อ-จัดส่งสำเร็จ</a></li>
                            <li><a class="dropdown-item" href="<?= ROOT ?>/admin/order_cancel">ออเดอร์สั่งซื้อ-ที่ถูกยกเลิก</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Report</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= ROOT ?>/admin/report_stock_product">รายงานสินค้าคงเหลือ</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#salesReport1Modal">รายงานยอดขายสินค้า (แยกตามออเดอร์)</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#salesReport2Modal">รายงานยอดขายสินค้า (แยกตามสินค้า)</a></li>
                            <li><a class="dropdown-item" href="<?= ROOT ?>/admin/report_profit_product">รายงานกำไร-ขาดทุน (เรียงตามสินค้า)</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav ms-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li> -->
                    <?php if (isset($_SESSION['USER']) && isset($_SESSION['USER']->user_type)) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="">Cust No: <?php echo $_SESSION['USER']->customer_id ?> (Admin) </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ms-2" href="<?= ROOT ?>/logout">Logout</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= ROOT ?>/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= ROOT ?>/register">Register</a>
                        </li>
                    <?php } ?>
                </ul>

            </div>
        </div>
    </nav>

    <?php include 'modal_report.view.php'; ?>
</body>

</html>