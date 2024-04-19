<?php

class Cart_process {
    use Model;
    protected $table = 'cart';

    public function getIDByIDCustomerProduct($customer_id, $product_id) {
        $id_data = array(
            'customer_id' => $customer_id,
            'product_id' => $product_id
        );
        $result_cart = $this->where($id_data);
        return $result_cart[0]->cart_id;
    }

    public function updateCart($cart_id, $arr_qty) {
        $result = $this->update($cart_id, $arr_qty, 'cart_id');
    }

    public function deleteCart($cart_id) {
        $result = $this->delete($cart_id, 'cart_id');
    }

    public function addCart($data) {
        $new_data['customer_id'] = $data['customer_id'];
        $new_data['product_id'] = $data['product_id'];
        $check_product = $this->countWhere($new_data); // ตรวจสอบสินค้าชิ้นนั้นมีใน cart

        if ($check_product > 0) {
            $where_result = $this->where($new_data); // row ที่เท่าไหร่
            $id = $where_result[0]->cart_id; // cart_id
            $arr['qty'] = $where_result[0]->qty + $data['qty']; // จำนวนที่ add ใหม่ + จำนวนเดิม
            $result = $this->update($id, $arr, 'cart_id');
            if ($result) {
                return true;
            } else {
                return false;
            }
        } else {
            $result = $this->insert($data);
            if ($result) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function showCart($customer_id_array) { // ส่ง customer_id เข้ามา check
        $where_cart = $this->where($customer_id_array);
        $product = new Product_process();
        $product_cart = array();
        foreach ($where_cart as $item) {
            $product_id = $item->product_id;
            $product_data = $product->showProductByID($product_id);
            $product_data->pick_qty = $item->qty;
            $product_cart[] = $product_data;
        }
        return $product_cart;
    }

    public function checkProduct($data) {
        if (!empty($data['selected_product_id'])) {
            $product = new Product_process();
            $customer_id = $data['customer_id'];
            $product_selected = array();

            foreach ($data['selected_product_id'] as $product_id) {
                $cart_id = $this->getIDByIDCustomerProduct($customer_id, $product_id);
                $product_data = $this->getProductCartByID($cart_id, true);
                $product_selected[] = $product_data;
            }
            return $product_selected;
        } else {
        }
        // $_SESSION['selected_cart'] = $_POST;
    }

    public function getProductCartByID($id, $isPick) {
        // select cart
        $arr_id['cart_id'] = $id;
        $cart_arr = $this->where($arr_id);

        // select product from cart
        $product = new Product_process();
        $arr_product_id['product_id'] = $cart_arr[0]->product_id;
        $product_arr = $product->where($arr_product_id);
        if ($isPick) {
            $product_arr[0]->pick_qty = $cart_arr[0]->qty;
            $product_arr[0]->total_price = $product_arr[0]->unit_price * $product_arr[0]->pick_qty;
        }
        return $product_arr[0];
    }
}
