<?php

class Cart {
    use Controller;
    public function index() {

        if (isset($_SESSION['USER'])) {
            if ($_SESSION['USER']->user_type == 'admin') {
                redirect('admin/dashboard');
            }
        }
        
        if (!isset($_SESSION['USER'])) {
            echo "<script>alert('Please login before accessing the cart.');</script>";
            echo "<script>window.location.href='" . ROOT . "/home';</script>";
        }
        $cart = new Cart_process();
        $cart_arr['customer_id'] = $_SESSION['USER']->customer_id;
        $result = $cart->showCart($cart_arr);
        $data['cart_items'] = $result;
        if (isset($_POST['checkout'])) {
            if (!empty($_POST['pick_product_id_array_checked'])) {
                $post['selected_product_id'] = $_POST['pick_product_id_array_checked'];
                $post['unselected_product_id'] = $_POST['pick_product_id_array_uncheck'];
                $post['pick_qty'] = $_POST['pick_qty_array'];
                $post['customer_id'] = $_SESSION['USER']->customer_id;

                $result = $cart->checkProduct($post);
                $_SESSION['cart_selected'] = $result;
                redirect('checkout');
            } else {
                redirect('cart');
            }
        }

        if (isset($_POST["action"])) {
            $cusotmer_id = $_SESSION['USER']->customer_id;
            $product_id =  $_POST['product_id'];
            $cart_id = $cart->getIDByIDCustomerProduct($cusotmer_id, $product_id);
            if ($_POST["action"] == "update") {
                $arr_qty['qty'] = $_POST['pick_qty'];
                $result = $cart->updateCart($cart_id, $arr_qty);
            } else if ($_POST["action"] == 'delete') {
                $result = $cart->deleteCart($cart_id, 'cart_id');
            }
        }
        $this->view('user/cart', true, $data);
    }
}
