<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/css/style_purchase.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>My Purchase</title>
</head>

<body>
    <div class="container">
        <div class="nav-scroller py-1 mb-2" style="width: 100%;">
            <nav class="nav d-flex justify-content-between nav-status">
                <a class="p-2" href="<?= ROOT ?>/myPurchase?status=1">Unpaid</a>
                <a class="p-2" href="<?= ROOT ?>/myPurchase?status=2">Preparing</a>
                <a class="p-2" href="<?= ROOT ?>/myPurchase?status=3">Shipping</a>
                <a class="p-2" href="<?= ROOT ?>/myPurchase?status=4">Delivered</a>
                <a class="p-2" href="<?= ROOT ?>/myPurchase?status=5">Cancelled</a>
            </nav>
            <!-- ตรวจสอบข้อผิดพลาด -->
            <?php if (!empty($data['errors'])) : ?>
                <div class="error-message"><?= $data['errors']['order'] ?></div>
            <?php else : ?>
                <!-- วนลูปผ่านแต่ละออเดอร์ -->
                <?php foreach ($data['show_product'] as $order) : ?>
                    <div class="order-header d-flex justify-content-between mt-4 mb-4">
                        <h4 class="mb-0">Order ID: <?= $order['order_id']; ?></h4>
                        <div class=" d-flex">
                            <h4 class="mb-0 me-4 text-align-center">Total Price: ฿<?= number_format($order['total_price'], 2); ?></h4>
                            <?php if ($order['order_status_id'] == 1) { ?>
                                <a href="<?= ROOT ?>/update_order_status?order_id=<?= $order['order_id']; ?>&new_status=2" class="btn btn-warning">จ่ายเงิน</a>
                            <?php }; ?>
                        </div>
                    </div>
                    <ul class="order-main">
                        <!-- วนลูปผ่านรายการสินค้าในออเดอร์ -->
                        <?php foreach ($order['products'] as $product) : ?>
                            <li class="product-content">
                                <div class="container">
                                    <div class="product-image">
                                        <img src="<?= ROOT ?>/img/product_image/<?= $product->product_image ?>">
                                    </div>
                                    <div class="product-detail">
                                        <div class="name"><?= $product->product_name ?></div>
                                        <div class="description"><?= $product->product_description ?></div>
                                        <div class="price">฿<?= $product->unit_price ?></div>
                                    </div>
                                </div>
                                <div class="order-btn">
                                    <a href="<?= ROOT ?>/product?product_id=<?= $product->product_id ?>" class="btn-lg active btn-buy btn-action" role="button" aria-pressed="true">Buy Again</a>
                                    <?php if ($order['order_status_id'] == 3) { ?>
                                        <a href="<?= ROOT ?>/update_order_status?order_id=<?= $order['order_id']; ?>&new_status=4" class="btn-lg active btn btn-warning btn-action ms-4">ได้รับแล้ว</a>
                                    <?php }; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // Highlight the active order status link
        var status = <?php echo json_encode($_GET['status']); ?>;
        var links = document.querySelectorAll('.nav-status a');
        links.forEach(function(link) {
            var linkStatus = link.getAttribute('href').split('=')[1];
            if (linkStatus == status) {
                link.style.borderBottom = '3px solid #588157';
                link.style.color = '#588157';
                link.style.fontWeight = '500';
            }
        });
    </script>
</body>

</html>