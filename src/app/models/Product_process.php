<?php

class Product_process {
    use Model;
    protected $table = 'product';

    public function showProductByID($id) {
        $id_arr['product_id'] = $id;
        $result = $this->where($id_arr);
        return $result[0];
    }

    public function getProductByID($product_id) {
        $product_id_arr['product_id'] = $product_id;
        $result = $this->where($product_id_arr);
        return $result[0];
    }

    public function insertProduct($data_arr) {
        $result = $this->insert($data_arr);
        if ($result) {
            return true;
        }
        return false;
    }

    public function updateProduct($product_id, $data_arr) {
        $result = $this->update($product_id, $data_arr, 'product_id');
        if ($result) {
            return true;
        }
        return false;
    }

    public function deleteProduct($product_id) {
        $result = $this->delete($product_id, 'product_id');
        if ($result) {
            return true;
        }
        return false;
    }

    public function updateStock($product_id, $qty) {
        $result_product = $this->getProductByID($product_id);
        $qty_arr['stock_qty'] = $result_product->stock_qty + $qty;
        $result = $this->update($product_id, $qty_arr, 'product_id');
        if ($result) {
            return true;
        }
        return false;
    }
}
