<?php

class Payment_process {
    use Model;
    protected $table = 'payment_method';
    public function getMethodNameByID($id) {
        $id_arr['payment_method_id'] = $id;
        $method_name = $this->where($id_arr);
        return $method_name;
    }
}
