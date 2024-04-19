<?php

class MyPurchase {
    use Controller;

    public function index() {
        if (isset($_SESSION['USER'])) {
            if ($_SESSION['USER']->user_type == 'admin') {
                redirect('admin/dashboard');
            }
        }
        $product = new Product_process();
        $order_header = new OrderHeader_process();
        $order_detail = new OrderDetail_process();
        $payment = new Payment_process();
        // ตั้ง status เป็น 1 ในการแสดงใน my_purchase

        if (isset($_GET['status'])) {
            $order_status_id = $_GET['status'];
        } else {
            $order_status_id = 1;
        }
        $data['order_status_id'] = $order_status_id;

        if (isset($_SESSION['USER'])) {
            $data['show_product'] = array();
            $customer_id = $_SESSION['USER']->customer_id;
            $result_order = $order_header->getOrderHeaderCustStatus($customer_id, $order_status_id, 'DESC');
            $order_id_arr = array();
            foreach ($result_order as $order) {
                $order_id_arr[] = $order->order_id;
            }
        } else {
            $order_id_arr[] = $_SESSION['GUEST']->order_id;
        }
        // ที่ไปแก้มา
        if (!empty($order_id_arr)) {
            $result_order_product = array();
            foreach ($order_id_arr as $order_id) {

                $order_info_list = $order_header->getOrderHeaderCustStatus($customer_id, $order_status_id, 'DESC', $order_id);
                
                foreach ($order_info_list as $order_info) {
                    $total_price = $order_info->total_price; 

                    $product_arr = array();
                    $product_id_arr = $order_detail->getAllProductIDByOrderID($order_info->order_id);
                    foreach ($product_id_arr as $product_id) {
                        $product_arr[] = $product->getProductByID($product_id);
                    }

                    $result_order_product[] = [
                        'order_id' => $order_info->order_id,
                        'order_status_id' => $order_info->order_status_id,
                        'total_price' => $total_price,
                        'products' => $product_arr
                    ];
                }
                break;
            }
            $data['show_product'] = $result_order_product;
        } else {
            $order_header->errors['order'] = "No Orders yet.";
        }
        // cancel สินค้า
        if (isset($_POST['cancel_order'])) {
            $customer_id = $_SESSION['USER']->customer_id;
            $index = $_POST['cancel_order'];
            $order_id_cancel = -1;
            foreach ($order_id_arr as $i => $order_id) {
                if ($index == $i) {
                    $order_id_cancel = $order_id;
                }
            }
            // update stock
            $result_product = $order_detail->getProductByOrderID($order_id_cancel);
            foreach ($result_product as $item) {
                $result_product = $product->showProductByID($item->product_id);
                $current_qty = $result_product->stock_qty + $item->qty;
                $product_data = array(
                    'stock_qty' => $current_qty
                );
                $product->updateProduct($item->product_id, $product_data);
            }
            // delete order header
            if ($order_header->setStatusByID($order_id_cancel, "5")) {
                echo "<script>alert('Cancel order success.');</script>";
                echo "<script>window.location.href='" . ROOT . "/myPurchase?status=5';</script>";
            } else {
                echo "<script>alert('Delete order failed.');</script>";
                echo "<script>window.location.href='" . ROOT . "/myPurchase?status=" . $order_status_id . "';</script>";
            }
        }
        $data['errors'] = $order_header->errors;
        $this->view('user/my_purchase', true, $data);
    }
}
