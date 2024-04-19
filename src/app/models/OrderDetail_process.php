<?php

class OrderDetail_process {
    use Model;
    protected $table = 'order_detail';

    public function setOrderDetail($order_data) {
        if ($this->insert($order_data)) {
            return true;
        }
        return false;
    }

    public function getAllProductIDByOrderID($order_id) {
        $order_id_arr['order_id'] = $order_id;
        $result_detail = $this->where($order_id_arr);
        $product_id_arr = array();
        foreach ($result_detail as $detail) {
            $product_id_arr[] = $detail->product_id;
        }
        return $product_id_arr;
    }

    public function deleteOrderDetail($order_id) {
        $result =  $this->delete($order_id, 'order_id');
        if ($result) {
            return true;
        }
        return false;
    }

    public function getProductByOrderID($order_id) {
        $order_id_arr['order_id'] = $order_id;
        $result_order = $this->where($order_id_arr);
        return $result_order;
    }
}
