<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= ROOT ?>/css/style_cart.css">
    <title>Cart</title>
</head>

<body>
    <div class="container container-cart">
        <div class="form-check mb-4">
            <input class="form-check-input checkbox-all me-3" id="select_all" type="checkbox" value="" id="flexCheckDefault">
            <label class="form-check-label label-checkbox-all" style="font-weight: 400; font-size: 16px;" for="flexCheckDefault">Select All</label>
        </div>

        <form method="POST">
            <?php foreach ($data['cart_items'] as $item) { ?>
                <div id="cart-box" class="cart-box">
                    <div class="form-check">
                        <input class="form-check-input product-checkbox me-4" type="checkbox" id="flexCheckDefault" name="pick_product_id_array_checked[]" value="<?php echo $item->product_id ?>" autocomplete="off">
                    </div>
                    <input type="hidden" name="pick_product_id_array_uncheck[]" value="<?php echo $item->product_id ?>" id="checkbox_<?php echo $item->product_id ?>">
                    <div class="cart-image">
                        <img src="<?= ROOT ?>/img/product_image/<?php echo $item->product_image ?>">
                    </div>
                    <div class="cart-detail">
                        <div class="product-name"><?php echo $item->product_name ?></div>
                        <div class="product-description"><?php echo $item->product_description ?></div>
                        <div class="product-price">฿<?php echo $item->unit_price ?></div>
                        <div class="product-pick-qty">
                            <button id="decrease-btn" class="rounded-circle decrease-btn" name="decrease"><i class='bx bx-minus'></i></button>
                            <input id="productPickQty" class="pick-qty" type="text" name="pick_qty_array[]" value="<?php echo $item->pick_qty ?>">
                            <button id="increase-btn" class="rounded-circle increase-btn" name="increase"><i class='bx bx-plus'></i></button>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <footer class="cart-footer">
                <div class="product-checked">
                    <div class="count-checked mt-3" style="color: white; font-weight: 400;">
                        <p class="me-1">Total Item: </p>
                        <p id="count-checked">0</p>
                    </div>
                    <div class="price-checked" style="color: white; font-weight: 400;">
                        <p>Total Price: ฿</p>
                        <p id="price-checked" min="0">0.00</p>
                    </div>

                </div>
                <div class="footer-btn">
                    <input id="delete-btn" type="submit" class="btn delete-btn" style="background-color:#ee6969; color: white;" name="delete" value="Delete">
                    <input id="checkout-btn" type="submit" class="btn btn-success checkout-btn" name="checkout" style="background-color:#588157; color: white;" value=" Check Out">
                </div>
            </footer>
        </form>
    </div>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        const checkboxAll = document.getElementById("select_all");
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const deleteBtn = document.getElementById("delete-btn");
        let totalPrice = 0;
        let count = 0;

        checkboxAll.addEventListener('change', () => {
            checkboxes.forEach(checkbox => {
                console.log(checkbox.checked == true);
                if (checkbox.checked == false && checkboxAll.checked == true) {
                    checkbox.checked = checkboxAll.checked;
                    calculateTotalPrice(checkbox);
                } else if (checkbox.checked == true && checkboxAll.checked == false) {
                    checkbox.checked = checkboxAll.checked;
                    calculateTotalPrice(checkbox);
                }
            });
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                let allChecked = true;
                checkboxes.forEach(checkbox => {
                    if (!checkbox.checked) {
                        allChecked = false;
                    }
                });
                checkboxAll.checked = allChecked;
                calculateTotalPrice(checkbox);
            });
        });

        deleteBtn.addEventListener('click', (event) => {
            event.preventDefault();
            if (confirm("Do you want to remove the checked items?")) {
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        removeCheckedCart(checkbox);
                    }
                });
            }
        });

        const cartBoxes = document.querySelectorAll('.cart-box');
        cartBoxes.forEach(cartBox => {
            const decreaseBtn = cartBox.querySelector('#decrease-btn');
            const increaseBtn = cartBox.querySelector('#increase-btn');
            const pickQtyInput = cartBox.querySelector('.pick-qty');

            decreaseBtn.addEventListener('click', function(event) {
                event.preventDefault();
                decreaseQuantity(cartBox);
                updateCart(pickQtyInput);
            });

            increaseBtn.addEventListener('click', function(event) {
                event.preventDefault();
                increaseQuantity(cartBox);
                updateCart(pickQtyInput);
            });

            pickQtyInput.addEventListener('change', function(event) {
                updateCart(pickQtyInput);
            });
        });

        function calculateTotalPrice(checkbox) {
            // checkboxes.forEach(checkbox => {
            const productPriceText = checkbox.closest('.cart-box').querySelector('.product-price').innerText;
            const productPrice = parseFloat(productPriceText.replace(/[^\d.]/g, ''));
            const productQty = parseInt(checkbox.closest('.cart-box').querySelector('.pick-qty').value);
            if (checkbox.checked) {
                totalPrice += productPrice * productQty;
                count++;
            } else {
                totalPrice -= productPrice * productQty;
                count--;
            }
            // });
            document.getElementById('count-checked').innerText = `${count}`;
            document.getElementById('price-checked').innerText = `${totalPrice.toFixed(2)}`;
        }



        function decreaseQuantity(cartBox) {
            const productPriceText = cartBox.querySelector('.product-price').innerText;
            const productPrice = parseFloat(productPriceText.replace(/[^\d.]/g, ''));
            const pickQtyInput = cartBox.querySelector('.pick-qty');
            const checkbox = cartBox.querySelector('.product-checkbox');
            let currentValue = parseInt(pickQtyInput.value);

            if (currentValue > 1) {
                pickQtyInput.value = currentValue - 1;
                if (checkbox.checked) {
                    updateTotalPrice(-productPrice);
                }
            } else {
                if (confirm("Do you want to remove this item?")) {
                    removeCart(pickQtyInput);
                }
            }
        }

        function increaseQuantity(cartBox) {
            const productPriceText = cartBox.querySelector('.product-price').innerText;
            const productPrice = parseFloat(productPriceText.replace(/[^\d.]/g, ''));
            const pickQtyInput = cartBox.querySelector('.pick-qty');
            const checkbox = cartBox.querySelector('.product-checkbox');
            let currentValue = parseInt(pickQtyInput.value);

            pickQtyInput.value = currentValue + 1;
            if (checkbox.checked) {
                updateTotalPrice(productPrice);
            }
        }

        function updateTotalPrice(priceDifference) {
            totalPrice += priceDifference;
            document.getElementById('price-checked').innerText = `${totalPrice.toFixed(2)}`;
        }

        function updateCart(pickQtyInput) {
            const cartBox = pickQtyInput.closest('.cart-box');
            const product_id = cartBox.querySelector('.product-checkbox').value;

            fetch(window.location.pathname, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${product_id}&pick_qty=${pickQtyInput.value}&action=update`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }

        function removeCart(pickQtyInput) {
            const cartBox = pickQtyInput.closest('.cart-box');
            const productPriceText = cartBox.querySelector('.product-price').innerText;
            const productPrice = parseFloat(productPriceText.replace(/[^\d.]/g, ''));
            const product_id = cartBox.querySelector('.product-checkbox').value;
            const customer_id = cartBox.querySelector('.product-checkbox').value;

            fetch(window.location.pathname, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${product_id}&customer_id=${<?php echo $_SESSION["USER"]->customer_id; ?>}&action=delete`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    console.log(data);
                    cartBox.remove();
                    updateTotalPrice(-parseFloat(productPrice) * parseInt(pickQtyInput.value));
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }

        function removeCheckedCart(checkbox) {
            const cartBox = checkbox.closest('.cart-box');
            const checkboxChecked = cartBox.querySelector(".product-checkbox");
            const productPriceText = cartBox.querySelector('.product-price').innerText;
            const productPrice = parseFloat(productPriceText.replace(/[^\d.]/g, ''));
            const pickQtyInput = cartBox.querySelector(".pick-qty");
            const product_id = cartBox.querySelector('.product-checkbox').value;
            const customer_id = cartBox.querySelector('.product-checkbox').value;
            fetch(window.location.pathname, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${product_id}&customer_id=${<?php echo $_SESSION["USER"]->customer_id; ?>}&action=delete`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    console.log(data);
                    cartBox.remove();
                    updateTotalPrice(-parseFloat(productPrice) * parseInt(pickQtyInput.value));
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }
    </script>
</body>

</html>