<?php

class Product {
    use Controller;

    public function index() {
        if (isset($_SESSION['USER'])) {
            if ($_SESSION['USER']->user_type == 'admin') {
                redirect('admin/dashboard');
            }
        }
        $product = new Product_process();
        $get = $_GET;
        unset($get['url']);
        $result = $product->where($get);
        $data['product_item'] = $result;

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $cart = new Cart_process();
            if (!empty($_SESSION['USER'])) {
                $arr['customer_id'] = $_SESSION['USER']->customer_id;
            }
            $arr['product_id'] = $_POST['product_id'];
            $arr['qty'] = $_POST['pick_qty'];

            if (isset($_POST['add'])) {
                if (!empty($_SESSION['USER'])) {
                    $result = $cart->addCart($arr);
                    $data['success'] = "Add item to your shopping cart success.";
                } else {
                    echo "<script>alert('Please login before accessing the cart.');</script>";
                    echo "<script>window.location.href='" . ROOT . "/product?product_id=" . $_GET['product_id'] . "';</script>";
                }
            } else if (isset($_POST['purchase'])) {
                if (!empty($_SESSION['USER'])) {
                    $result = $cart->addCart($arr);
                    redirect('cart');
                } else {
                    $_SESSION['GUEST']->checkout->product_id = $_POST['product_id'];
                    $_SESSION['GUEST']->checkout->qty = $_POST['pick_qty'];
                    redirect('checkout');
                }
            }
        }
        $this->view('user/product', true, $data);
    }
}
