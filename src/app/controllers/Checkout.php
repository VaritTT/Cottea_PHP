<?php

class Checkout {
    use Controller;

    public function index() {

        if (isset($_SESSION['USER'])) {
            if ($_SESSION['USER']->user_type == 'admin') {
                redirect('admin/dashboard');
            }
        }
        $order_header = new OrderHeader_process();
        $order_detail = new OrderDetail_process();
        $address = new Address_process();
        $product = new Product_process();
        $cart = new Cart_process();
        $product = new Product_process();
        $payment = new Payment_process();

        if (isset($_SESSION['USER'])) {
            // แสดง detail ของ order
            $customer_id = $_SESSION['USER']->customer_id;

            $data['cart_selected'] = $_SESSION['cart_selected'];
            $grand_total = 0;
            foreach ($data['cart_selected'] as $item) {
                $grand_total += $item->total_price;
            }
            $data['total_qty'] = count($data['cart_selected']);
            $data['grand_total'] = $grand_total;
            $result_shipping = $address->getAddressByCustIDAndType($customer_id, 'shipping');
            if (!empty($result_shipping)) { // ถ้ามี shipping address
                $data['shipping_address'] = $result_shipping;
            } else { // ถ้าไม่มี shipping address
                if (!empty($_SESSION['USER']->addr) && !empty($_SESSION['USER']->postal_code) && !empty($_SESSION['USER']->tel)) {
                    // ดึง address ส่วนตัวมาใส่
                    $name = $_SESSION['USER']->customer_name;
                    $address_details =  $_SESSION['USER']->addr;
                    $postal_code =  $_SESSION['USER']->postal_code;
                    $tel =  $_SESSION['USER']->tel;
                    $address->addShippingAddress($customer_id, $name, $address_details, $postal_code, $tel);
                    $result_shipping = $address->getAddressByCustIDAndType($customer_id, 'shipping');
                    $data['shipping_address'] = $result_shipping;
                } else { // ไม่มี address ส่วนตัว
                    $address->errors['address'] = "Please add your shipping address.";
                }
            }
        } else {
            $product_id = $_SESSION['GUEST']->checkout->product_id;
            $result_product = $product->getProductByID($product_id);
            $_SESSION['GUEST']->product = $result_product;
            $_SESSION['GUEST']->product->total_qty = $_SESSION['GUEST']->checkout->qty;
            $_SESSION['GUEST']->product->grand_total = $_SESSION['GUEST']->product->total_qty * $_SESSION['GUEST']->product->unit_price;
            $_SESSION['GUEST']->product->pick_qty = $_SESSION['GUEST']->checkout->qty;
            if (empty($_SESSION['GUEST']->shipping_address)) {
                $_SESSION['GUEST']->shipping_address = "";
            }
        }

        if (isset($_POST['change_address'])) {
            if (isset($_SESSION['USER'])) {
                $customer_id = $_SESSION['USER']->customer_id;
                $shipping_name = $_POST['shipping_name'];
                $shipping_address = $_POST['shipping_address'];
                $shipping_postal_code = $_POST['shipping_postal_code'];
                $shipping_tel = $_POST['shipping_tel'];
                $address->setShippingAddress($customer_id, $shipping_name, $shipping_address, $shipping_postal_code, $shipping_tel);
                $result_shipping = $address->getAddressByCustIDAndType($customer_id, 'shipping');
                $data['shipping_address'] = $result_shipping;
            } else {
                if (!empty($_SESSION['GUEST']->shipping_address)) {
                    $shipping_address_guest = new stdClass();

                    $shipping_address_guest->name = $_POST['shipping_name'];
                    $shipping_address_guest->address_details = $_POST['shipping_address'];
                    $shipping_address_guest->postal_code = $_POST['shipping_postal_code'];
                    $shipping_address_guest->tel = $_POST['shipping_tel'];

                    // เก็บลง SESSION GUEST
                    $_SESSION['GUEST']->shipping_address = $shipping_address_guest;
                }
            }
        }
        if (isset($_POST['add_address'])) {
            if (isset($_SESSION['USER'])) {
                $customer_id = $_SESSION['USER']->customer_id;
                $shipping_name = $_POST['shipping_name'];
                $shipping_address = $_POST['shipping_address'];
                $shipping_postal_code = $_POST['shipping_postal_code'];
                $shipping_tel = $_POST['shipping_tel'];
                $address->addShippingAddress($customer_id, $shipping_name, $shipping_address, $shipping_postal_code, $shipping_tel);
                $result_shipping = $address->getAddressByCustIDAndType($customer_id, 'shipping');
                $data['shipping_address'] = $result_shipping;
            } else {
                $shipping_address_guest = new stdClass();

                $shipping_address_guest->name = $_POST['shipping_name'];
                $shipping_address_guest->address_details = $_POST['shipping_address'];
                $shipping_address_guest->postal_code = $_POST['shipping_postal_code'];
                $shipping_address_guest->tel = $_POST['shipping_tel'];
                // เก็บลง SESSION GUEST
                $_SESSION['GUEST']->shipping_address = $shipping_address_guest;

                // นำไปแสดงผล                
                $data['shipping_address'] = $_SESSION['GUEST']->shipping_address;
            }
        }

        if (isset($_POST['place_order'])) {
            if (isset($_SESSION['USER'])) {
                if (isset($_POST['payment_method'])) {
                    $payment_method_id = $_POST['payment_method'];
                    if (!empty($data['shipping_address'])) {
                        $order_status_id = $payment_method_id == 1 ? 1 : 2; // QR กับ ปลายทาง

                        // insert order_header
                        date_default_timezone_set('Asia/Bangkok');
                        $order_header_data = array(
                            'customer_id' => $customer_id,
                            'order_status_id' => $order_status_id,
                            'order_datetime' => date('Y-m-d H:i:s'),
                            'total_price' => $data['grand_total'],
                            'shipping_address_id' => $data['shipping_address']->address_id,
                            'payment_method_id' => $payment_method_id
                        );

                        // insert order header สำเร็จ
                        if ($order_header->setOrderHeader($order_header_data)) {
                            // insert order_detail
                            $order_id = $order_header->getIDByCustID($customer_id);
                            foreach ($data['cart_selected'] as $item) {
                                $order_detail_data = array(
                                    'order_id' => $order_id,
                                    'product_id' => $item->product_id,
                                    'qty' => $item->pick_qty
                                );
                                // insert order detail สำเร็จ
                                if ($order_detail->setOrderDetail($order_detail_data)) {
                                    $cusotmer_id = $_SESSION['USER']->$customer_id;
                                    $product_id = $item->product_id;
                                    $result_product = $product->showProductByID($product_id);
                                    // หักลบจำนวน stock ของ product ทิ้ง
                                    $qty_current = $result_product->stock_qty - $item->pick_qty;
                                    $product_data = array(
                                        'stock_qty' => $qty_current
                                    );
                                    $product->updateProduct($product_id, $product_data);

                                    // ลบ cart ที่เลือกไว้ทิ้ง 
                                    $cart_id = $cart->getIDByIDCustomerProduct($customer_id, $product_id);
                                    $cart->deleteCart($cart_id);
                                    echo "<script>alert('Order successful');</script>";
                                }
                            }
                        } else {
                            $order_header->errors['order'] = "Something wrong with order";
                        }
                        unset($_SESSION['cart_selected']);
                        if ($order_status_id == 1) {
                            redirect('myPurchase?status=' . $order_status_id);
                        } else {
                            redirect('myPurchase?status=' . $order_status_id);
                        }
                    } else {
                        $address->errors['address'] = "Please add your shipping address.";
                    }
                } else {
                    $address->errors['payment_method'] = "Please choose a payment method.";
                }
            } else {
                $payment_method_id = $_POST['payment_method'];
                $order_status_id = 2;
                if (!empty($_SESSION['GUEST']->shipping_address)) {
                    // insert order_header
                    date_default_timezone_set('Asia/Bangkok');
                    $order_header_data = array(
                        'order_status_id' => $order_status_id,
                        'order_datetime' => date('Y-m-d H:i:s'),
                        'total_price' => $_SESSION['GUEST']->product->grand_total,
                        'shipping_address_id' => $_SESSION['GUEST']->shipping_address->address_id,
                        'payment_method_id' => $payment_method_id
                    );

                    // insert order header สำเร็จ
                    if ($order_header->setOrderHeader($order_header_data)) {
                        $result_order_guest = $order_header->getOrderByGUEST();

                        // insert order_detail
                        $order_id = $result_order_guest->order_id;
                        $product_id = $_SESSION['GUEST']->product->product_id;
                        $pick_qty = $_SESSION['GUEST']->product->pick_qty;
                        $order_detail_data = array(
                            'order_id' => $order_id,
                            'product_id' => $product_id,
                            'qty' => $pick_qty
                        );
                        // insert order detail สำเร็จ
                        if ($order_detail->setOrderDetail($order_detail_data)) {
                            $result_product = $product->showProductByID($product_id);
                            $qty_current = $result_product->stock_qty - $pick_qty;
                            $product_data = array(
                                'stock_qty' => $qty_current
                            );
                            $product->updateProduct($product_id, $product_data);
                            unset($_SESSION['GUEST']->product);
                            echo "<script>alert('Place order success.');</script>";
                            echo "<script>window.location.href='" . ROOT . "/home';</script>";
                        }
                    }
                } else {
                    $address->errors['address'] = "Please add your shipping address.";
                }
            }
        }
        $data['errors'] = $address->errors;
        $this->view('user/checkout', true, $data);
    }
}
