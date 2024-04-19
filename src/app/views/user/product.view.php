<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/css/style_product.css">

    <!-- icon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Product</title>
</head>

<body>
    <?php $product = $data['product_item'][0]; ?>
    <div class="container container-product">
        <section class="product-content">
            <div class="product-image">
                <img src="<?= ROOT ?>/img/product_image/<?php echo $product->product_image ?>">
            </div>
            <!-- <div class="id"><?php echo $product->product_id ?></div> -->
            <div class="product-detail">
                <div class="product-name"><?php echo $product->product_name ?></div>
                <div class="product-price">฿<?php echo $product->unit_price ?></div>
                <div class="product-description"><?php echo $product->product_description ?></div>
                <div id="qty-id" class="product-qty">in stock <?php echo $product->stock_qty ?></div>
                <form class="order_process-form" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product->product_id ?>">
                    <?php if (!empty($data['success'])) { ?>
                        <div style="color: green; font-size: 16px; font-weight: bold; margin: 1rem 0;"><?= $data['success'] ?></div>
                    <?php } ?>
                    <div class="order-selector">
                        <button class="decrease-btn" name="decrease"><i class='bx bx-minus'></i></button>
                        <input type="text" class="pick-qty" name="pick_qty" value="1" min="1">
                        <button class="increase-btn" name="increase"><i class='bx bx-plus'></i></button>
                    </div>

                    <div class="order-btn">
                        <input type="submit" name="add" value="Add to Cart">
                        <input type="submit" name="purchase" value="Purchase">
                    </div>
                </form>
            </div>
        </section>
    </div>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        const containerProducts = document.querySelectorAll('.container-product');

        containerProducts.forEach(function(containerProduct) {
            const decreaseBtn = containerProduct.querySelector('.decrease-btn');
            const increaseBtn = containerProduct.querySelector('.increase-btn');
            const pickQtyInput = containerProduct.querySelector('.pick-qty');
            const qtyElement = containerProduct.querySelector('.product-qty');
            const numberQty = parseInt((qtyElement.textContent).match(/\d+/));

            // disable enter ทิ้ง
            pickQtyInput.addEventListener('keydown', function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                }
            });


            pickQtyInput.addEventListener('input', function() {
                if (!pickQtyInput.value || isNaN(pickQtyInput.value) || pickQtyInput.value < 1) {
                    pickQtyInput.value = 1;
                }
            });

            decreaseBtn.addEventListener('click', function(event) {
                console.log(event.key === "click");
                event.preventDefault(); // ไม่ให้ยุ่งกับ form
                let currentValue = parseInt(pickQtyInput.value);
                if (currentValue > 1) {
                    pickQtyInput.value = currentValue - 1;
                }
            });

            increaseBtn.addEventListener('click', function(event) {
                event.preventDefault();
                let currentValue = parseInt(pickQtyInput.value);
                if (currentValue < numberQty) {
                    pickQtyInput.value = currentValue + 1;
                }
            });
        });
    </script>
</body>

</html>