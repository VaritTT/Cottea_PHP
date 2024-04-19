<ul class="order-main">
    <ul class="product-main">
        <li class="product-content">
            <div class="container">
                <div class="product-image">
                    <img src="<?= ROOT ?>/img/product_image/<?php echo $product->product_image ?>">
                </div>
                <!-- <div class="id"><?php echo $product->product_id ?></div> -->
                <div class="product-detail">
                    <div class="name"><?php echo $product->product_name ?></div>
                    <div class="description"><?php echo $product->product_description ?></div>
                    <div class="price">฿<?php echo $product->unit_price ?></div>
                    <!-- <div id="qty-id" class="quantity">in stock <?php echo $product->stock_qty ?></div> -->
                </div>
            </div>
            <input type="hidden" name="product_id" value="<?php echo $product->product_id; ?>">
            <div class="order-btn">
                <a href="<?= ROOT ?>/product?product_id=<?= $product->product_id ?>" class="btn-lg active btn-buy btn-action" role="button" aria-pressed="true">Buy Again</a>
            </div>
        </li>
    </ul>
    <?php if ($data['order_status_id'] != 4 && $data['order_status_id'] != 5) { ?>
        <div class="order-btn2">
            <button class="btn-lg active btn-cancel btn-action" role="button" aria-pressed="true" name="cancel_order" value="<?= $index ?>">Cancel</button>
        </div>
    <?php } ?>
</ul>