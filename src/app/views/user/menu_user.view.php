<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- css -->
    <link rel="stylesheet" href="<?= ROOT ?>/css/style_nav.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <nav class="navbar sticky-bottom navbar-expand-lg mb-4" style="background-color: #588157;">
        <div class="container-fluid">
            <a class="navbar-brand ms-4" style="color: #fff;" href="<?= ROOT ?>/home">Cottea</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle ms-4" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #fff;">All Categories</a>
                        <ul class="dropdown-menu ms-4">
                            <!-- <li><a class="dropdown-item" href="<?= ROOT ?>/category">Popular Products</a></li> -->

                            <?php
                            // $sql2 = "SELECT p.product_id, p.product_name, p.stock_qty, SUM(od.qty) AS total_sold FROM order_detail od 
                            // JOIN product p ON od.product_id = p.product_id GROUP BY od.product_id ORDER BY total_sold DESC LIMIT 5";
                            // $sellingResult = mysqli_query($conn, $sql2);

                            $category = new Category_process();
                            $row = $category->findAll();
                            foreach ($row as $category_arr) { ?>
                                <li><a class="dropdown-item" href="<?= ROOT ?>/category?category_id=<?php echo $category_arr->category_id; ?>"><?php echo $category_arr->category_name; ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>

            </div>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav ms-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                    <?php $cart = new Cart_process();
                    if (isset($_SESSION['USER'])) {
                        $customer_id_arr['customer_id'] = $_SESSION['USER']->customer_id;
                        $result_product = $cart->showCart($customer_id_arr);
                        $count_cart = count($result_product);
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link me-2" style="color: #fff; position: relative;" href="<?= ROOT ?>/cart">Cart
                            <?php if (!empty($count_cart)) { ?>
                                <div class="count-cart">
                                    <?php echo $count_cart ?>
                                </div>
                            <?php } ?>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['USER'])) { ?>
                        <li class="nav-item">
                            <a class="nav-link" style="color: #fff;" href="<?= ROOT ?>/logout">Logout</a>
                        </li>
                        <?php if (!empty($_SESSION['USER']->customer_name)) {
                            $profile_button = $_SESSION['USER']->customer_name;
                        } else if (!empty($_SESSION['USER']->username)) {
                            $profile_button = $_SESSION['USER']->username;
                        } else {
                            $profile_button = "Cust No. " . $_SESSION['USER']->customer_id;
                        } ?>
                        <li class="nav-item dropdown mx-2">
                            <a class="nav-link btn btn-dark" style="background-color: #344E41;color: #fff;" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo $profile_button ?></a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= ROOT ?>/myPurchase">My Purchase</a></li>
                                <!-- <li><a class="dropdown-item" href="#">จัดการพนักงาน</a></li> -->
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" style="color: #fff;" href="<?= ROOT ?>/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="color: #fff;" href="<?= ROOT ?>/register">Sign Up</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="btn btn-dark" style="background-color: #344E41;color: #fff;" href="<?= ROOT ?>/myPurchase">GUEST</a>
                        </li>
                    <?php } ?>
                </ul>

            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>