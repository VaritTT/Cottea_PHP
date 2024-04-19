<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?= ROOT ?>/css/style_category.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Order</title>
</head>


<body>
    <div class="container container-order">
        <!-- <form class="search-container" method="GET">
            <input id="search" class="search-box" name="search" type="text" placeholder="Type here">
            <input id="submit" class="search-btn" type="submit" value="Search">
        </form> -->
        <div class="view-toggle">
            <button id="list-view" type="submit" name="list-view" class="list-btn"><i class='bx bx-list-ul bx-md'></i></button>
            <button id="grid-view" type="submit" name="grid-view" class="grid-btn"><i class='bx bxs-grid-alt bx-md'></i></button>
        </div>
        <ul id="product-main" class="product-main container-grid">
            <?php foreach ($data['category_items'] as $product) { ?>
                <li class="product-content grid-view">
                    <a href="<?= ROOT ?>/product?product_id=<?php echo $product->product_id; ?>">
                        <div class="product-image">
                            <img src="<?= ROOT ?>/img/product_image/<?php echo $product->product_image ?>">
                        </div>
                        <!-- <div class="id"><?php echo $product->product_id ?></div> -->
                        <div class="product-detail">
                            <div class="product-name"><?php echo $product->product_name ?></div>
                            <div class="product-description"><?php echo $product->product_description ?></div>
                            <div class="product-price">฿<?php echo $product->unit_price ?></div>
                            <div id="qty-id" class="quantity">in stock <?php echo $product->stock_qty ?></div>
                        </div>
                    </a>
                    <form class="order-form" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product->product_id; ?>">
                        <div class="order-selector">
                            <button class="rounded-circle decrease-btn" name="decrease"><i class='bx bx-minus'></i></button>
                            <input type="text" class="pick-qty" name="pick_qty" value="0" placeholder="0" min="0">
                            <button class="rounded-circle increase-btn" name="increase"><i class='bx bx-plus'></i></button>
                        </div>

                        <div class="order-btn">
                            <input class="btn btn-success" type="submit" name="add" value="Add to Cart">
                            <input class="btn btn-warning" type="submit" name="purchase" value="Purchase">
                        </div>
                    </form>
                </li>
            <?php } ?>
        </ul>
    </div>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        document.querySelector('.list-btn').addEventListener('click', function(e) {
            e.preventDefault();
            setView('list');
        });

        document.querySelector('.grid-btn').addEventListener('click', function(e) {
            e.preventDefault();
            setView('grid');
        });

        function setView(view) {
            const mainProduct = document.querySelector('.product-main');
            console.log(mainProduct);
            const products = document.querySelectorAll('.product-content');
            products.forEach(product => {
                if (view === 'list') {
                    mainProduct.classList.remove('container-grid');
                    mainProduct.classList.add('container-list');
                    product.classList.remove('grid-view');
                    product.classList.add('list-view');
                } else if (view === 'grid') {
                    mainProduct.classList.remove('container-list');
                    mainProduct.classList.add('container-grid');
                    product.classList.remove('list-view');
                    product.classList.add('grid-view');
                }
            });
        }

        const containerOrders = document.querySelectorAll('.product-content');
        containerOrders.forEach(function(containerOrder) {
            const decreaseBtn = containerOrder.querySelector('.decrease-btn');
            const increaseBtn = containerOrder.querySelector('.increase-btn');
            const pickQtyInput = containerOrder.querySelector('.pick-qty');
            const qtyElement = containerOrder.querySelector('.quantity');
            const numberQty = parseInt((qtyElement.textContent).match(/\d+/));

            // disable enter ทิ้ง
            pickQtyInput.addEventListener('keydown', function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                }
            });

            pickQtyInput.addEventListener('input', function() {
                if (!pickQtyInput.value || isNaN(pickQtyInput.value)) {
                    pickQtyInput.value = 0;
                }
            });

            decreaseBtn.addEventListener('click', function(event) {
                console.log(event.key === "click");
                event.preventDefault(); // ไม่ให้ยุ่งกับ form
                let currentValue = parseInt(pickQtyInput.value);
                if (currentValue > 0) {
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