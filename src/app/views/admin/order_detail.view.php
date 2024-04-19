<?php
include 'controller/condb.php';

if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Query ข้อมูล order detail จาก database
    $orderDetailsQuery = "SELECT * FROM order_detail WHERE order_id = '$orderId'";
    $orderDetailsResult = mysqli_query($conn, $orderDetailsQuery);

    // สร้าง array เพื่อเก็บข้อมูลสินค้า
    $products = [];
    $productQuery = "SELECT product_id, product_name, unit_price FROM product";
    $productResult = mysqli_query($conn, $productQuery);
    while ($product = mysqli_fetch_array($productResult)) {
        $products[$product['product_id']] = $product;
    }
} else {
    echo "<script>window.history.go(-1);</script>";
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                <h2 class="mb-0">Order Details</h2>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a href="<?=ROOT?>/admin/order_all" class="btn btn-secondary mb-3">Back to Orders</a>
            </div>
        </div>

        <?php if ($orderDetailsResult && mysqli_num_rows($orderDetailsResult) > 0) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price Per Item</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($detail = mysqli_fetch_array($orderDetailsResult)){ ?>
                        <?php
                        $productId = $detail['product_id'];
                        $productName = isset($products[$productId]) ? $products[$productId]['product_name'] : "Product name not found";
                        $pricePerUnit = isset($products[$productId]) ? $products[$productId]['unit_price'] : 0;
                        ?>
                        <tr>
                            <td><?= $productId ?></td>
                            <td><?= $productName ?></td>
                            <td><?= $detail['qty'] ?></td>
                            <td><?= $pricePerUnit ?></td>
                            <td><?= $detail['qty'] * $pricePerUnit ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No details found for this order.</p>
        <?php } ?>
        <?php mysqli_close($conn); ?>
    </div>
</body>
</html>
