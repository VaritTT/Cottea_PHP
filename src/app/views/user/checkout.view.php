<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= ROOT ?>/css/style_checkout.css">
    <title>Checkout</title>
</head>

<body>
    <div class="container container-checkout">
        <div class="address-box">
            <h3>Shipping Address</h3>
            <div class="address-content d-flex align-items-center">

                <!-- Button trigger modal -->
                <?php if (empty($_SESSION['USER'])) {
                    $data['shipping_address'] = $_SESSION['GUEST']->shipping_address;
                } ?>
                <?php if (!empty($data['shipping_address'])) { ?>
                    <div><?php echo $data['shipping_address']->name . " (" . $data['shipping_address']->tel . ") | " . $data['shipping_address']->address_details . ", " . $data['shipping_address']->postal_code ?></div>
                    <button type="button" class="btn btn-warning change-addr" data-bs-toggle="modal" data-bs-target="#exampleModal">Change</button>
                <?php } else { ?>
                    <button type="button" class="btn btn-danger add-addr" data-bs-toggle="modal" data-bs-target="#addNewAddress">Add New Address</button>
                    <?php if (!empty($data['errors'])) { ?>
                        <div class="message-error ms-2" style="font-weight: bold;"><?php echo $data['errors']['address'] ?></div>
                    <?php  } ?>
                <?php } ?>

                <!-- My Shipping Address -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel">My Shipping Address</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="shipping_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="shipping_name" value="<?php echo $data['shipping_address']->name; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="shipping_address" class="form-label">Shipping Address</label>
                                        <input type="text" class="form-control" name="shipping_address" value="<?php echo $data['shipping_address']->address_details; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="shipping_postal_code" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control" name="shipping_postal_code" value="<?php echo $data['shipping_address']->postal_code; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="shipping_tel" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="shipping_tel" value="<?php echo $data['shipping_address']->tel; ?>">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="change_address">Confirm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- New Address -->
                <div class="modal fade" id="addNewAddress" tabindex="-1" aria-labelledby="addNewAddressLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addNewAddressToggleLabel">New Address</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="shipping_address" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="shipping_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="shipping_address" class="form-label">Shipping Address</label>
                                        <input type="text" class="form-control" name="shipping_address">
                                    </div>
                                    <div class="mb-3">
                                        <label for="shipping_postal_code" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control" name="shipping_postal_code">
                                    </div>
                                    <div class="mb-3">
                                        <label for="shipping_tel" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="shipping_tel">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="add_address">Confirm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="product-picked">
            <div class="product-picked-header">
                <div class="header-detail">
                    <h4>Product Detail</h4>
                </div>
                <div class="header-price-per-unit">Unit Price</div>
                <div class="header-picked-qty">Amount</div>
                <div class="header-price-total">Item Subtotal</div>
            </div>
            <?php
            if (!empty($_SESSION['USER'])) {
                foreach ($data['cart_selected'] as $item) { ?>
                    <form method="POST">
                        <div class="product-picked-content">
                            <div class="product-detail">
                                <div class="product-image">
                                    <img src="<?= ROOT ?>/img/product_image/<?php echo $item->product_image ?>">
                                </div>
                                <!-- <div class="product-id"><?php echo $item->product_id ?></div> -->
                                <div class="product-name"><?php echo $item->product_name ?></div>
                                <div class="product-description"><?php echo $item->product_description ?></div>
                            </div>
                            <div class="product-price-per-unit">฿<?php echo $item->unit_price ?></div>
                            <div class="product-picked-qty"><?php echo $item->pick_qty ?></div>
                            <div class="product-price-total">฿<?php echo $item->total_price ?></div>
                        </div>
                    <?php } ?>
                    <div class="payment">
                        <h5>Payment Method</h5>
                        <input type="radio" class="btn-check" name="payment_method" id="option1" value="1" autocomplete="off" checked>
                        <label class="btn btn-outline-primary" for="option1">QR PromptPay</label>

                        <input type="radio" class="btn-check" name="payment_method" id="option2" value="2" autocomplete="off">
                        <label class="btn btn-outline-primary" for="option2">Cash On Delivery</label>
                    </div>
                    <footer class="checkout-footer">
                        <div class="count-checked">Total Item: <?php echo $total_qty ?></div>
                        <div class="price-checked">Grand Total: ฿<?php echo number_format($grand_total, 2) ?></div>
                        <div class="place-order-btn-container">
                            <input class="btn btn-success place-order-btn" type="submit" name="place_order" value="Place Order">
                        </div>
                    </footer>
                    </form>
                <?php } else {
                $item = $_SESSION['GUEST']->product;
                ?>
                    <form method="POST">
                        <div class="product-picked-content">
                            <div class="product-detail">
                                <div class="product-image">
                                    <img src="<?= ROOT ?>/img/product_image/<?php echo $item->product_image ?>">
                                </div>
                                <!-- <div class="product-id"><?php echo $item->product_id ?></div> -->
                                <div class="product-name"><?php echo $item->product_name ?></div>
                                <div class="product-description"><?php echo $item->product_description ?></div>
                            </div>
                            <div class="product-price-per-unit">฿<?php echo $item->unit_price ?></div>
                            <div class="product-picked-qty"><?php echo $item->pick_qty ?></div>
                            <div class="product-price-total">฿<?php echo $item->grand_total ?></div>
                        </div>
                        <div class="payment">
                            <h5>Payment Method</h5>
                            <input type="radio" class="btn-check" name="payment_method" id="option1" value="1" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="option1">QR PromptPay</label>

                            <input type="radio" class="btn-check" name="payment_method" id="option2" value="2" autocomplete="off" disabled>
                            <label class="btn btn-outline-primary" for="option2">Cash On Delivery</label>
                        </div>
                        <footer class="checkout-footer">
                            <div class="count-checked">Total Item: <?php echo $item->total_qty ?></div>
                            <div class="price-checked">Grand Total: ฿<?php echo number_format($item->grand_total, 2) ?></div>
                            <div class="place-order-btn-container">
                                <input class="btn btn-success place-order-btn" type="submit" name="place_order" value="Place Order">
                            </div>
                        </footer>
                    <?php } ?>
                    </form>
        </div>
    </div>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>