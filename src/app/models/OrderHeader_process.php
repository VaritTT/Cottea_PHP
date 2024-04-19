<?php

class OrderHeader_process {
    use Model;
    protected $table = 'order_header';

    public function setShippingAddress($data) {
        // $result = $this->where($data);
        // show($result);
    }

    public function setOrderHeader($order_data) {
        if ($this->insert($order_data)) {
            return true;
        }
        return false;
    }

    public function setStatusByID($order_id, $order_status_id) {
        $order_status_id_arr['order_status_id'] = $order_status_id;
        if ($this->update($order_id, $order_status_id_arr, 'order_id')) {
            return true;
        }
        return false;
    }

    public function insertTrackNumber($order_id) {
        // generate tracking number
        $prefix = "TN"; // คำนำหน้า Tracking Number
        $digits = 6; // 6 หลัก

        // เลขสมมติจาก logistic
        $random_number = '';
        for ($i = 0; $i < $digits; $i++) {
            $random_number .= rand(0, 9);
        }
        $tracking_number = $prefix . $random_number;

        $tracking_number_arr['tracking_number'] = $tracking_number;
        if ($this->update($order_id, $tracking_number_arr, 'order_id')) {
            return true;
        }
        return false;
    }
    public function getOrderHeaderByStatusID($status_id, $order_by = 'ASC') {
        $status_id_arr['order_status_id'] = $status_id;
        $result_order = $this->where($status_id_arr, [], $order_by);
        return $result_order;
    }

    public function getIDByStatusID($status_id) {
        $status_id_arr['order_status_id'] = $status_id;
        $result_order = $this->where($status_id_arr);
        return $result_order[0]->order_id;
    }

    public function getIDByCustID($customer_id) {
        $customer_id_arr['customer_id'] = $customer_id;
        $result_order = $this->where($customer_id_arr, [], 'DESC');
        return $result_order[0]->order_id;
    }

    public function getOrderHeaderCustStatus($customer_id, $status_id, $order_by = 'ASC') {
        $id_arr['customer_id'] = $customer_id;
        $id_arr['order_status_id'] = $status_id;
        $result_order = $this->where($id_arr, [], $order_by);
        return $result_order;
    }


    public function deleteOrderHeader($order_id) {
        $result = $this->delete($order_id, 'order_id');
        if ($result) {
            return true;
        }
        return false;
    }

    public function getOrderByGUEST() {
        $result_order = $this->findAll('1 DESC');
        return $result_order[0];
    }
}
