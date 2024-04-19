<?php

class Category {
    use Controller;

    public function index() {
        
        if (isset($_SESSION['USER'])) {
            if ($_SESSION['USER']->user_type == 'admin') {
                redirect('admin/dashboard');
            }
        }
        $cart = new Cart_process();
        $product = new Product_process();
        $get = $_GET;
        unset($get['url']);
        $result = $product->where($get);
        $data['category_items'] = $result;

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $arr['customer_id'] = $_SESSION['USER']->customer_id;
            $arr['product_id'] = $_POST['product_id'];
            $arr['qty'] = $_POST['pick_qty'];
            if (isset($_POST['add'])) {
                if ($_POST['pick_qty'] == 0) {
                    $data['errors'] = 'Please choose quantity of product.';
                } else {
                    if (!empty($_SESSION['USER'])) {
                        $result = $cart->addCart($arr);
                        echo "<script>alert('Add item to your shopping cart success.');</script>";
                    } else {
                        echo "<script>alert('Please login before accessing the cart.');</script>";
                        echo "<script>window.location.href='" . ROOT . "/category?category_id=" . $_GET['category_id'] . "';</script>";
                    }
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
        $this->view('user/category', true, $data);
    }
}
