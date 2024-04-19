<?php

class Address_process {
    use Model;
    protected $table = 'address';

    public function addShippingAddress($customer_id, $name, $address_details, $postal_code, $tel) {
        $address['customer_id'] = $customer_id;
        $address['name'] = $name;
        $address['tel'] = $tel;
        $address['address_type'] = 'shipping';
        $address['address_details'] = $address_details;
        $address['postal_code'] = $postal_code;
        $this->insert($address);
    }

    public function setShippingAddress($customer_id, $name, $address_details, $postal_code, $tel) {
        $address['customer_id'] = $customer_id;
        $address['name'] = $name;
        $address['address_type'] = 'shipping';
        $address['tel'] = $tel;
        $address['address_details'] = $address_details;
        $address['postal_code'] = $postal_code;
        $address_arr = $this->getAddressByCustIDAndType($customer_id, 'shipping');
        $address_id = $address_arr->address_id;
        $this->update($address_id, $address, 'address_id');
    }

    public function getAddressByCustIDAndType($customer_id, $address_type) {
        $address_arr['customer_id'] = $customer_id;
        $address_arr['address_type'] = $address_type;
        $result_address = $this->where($address_arr, [], 'ASC');
        return $result_address[0];
    }

    public function getAddressByID($address_id) {
        $address_arr['address_id'] = $address_id;
        $result_address = $this->where($address_arr);
        return $result_address[0];
    }
}
