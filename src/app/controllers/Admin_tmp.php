<?php
// class Admin {
//     use Controller;
//     public function index() {
//         if (isset($_SESSION['USER'])) {
//             if ($_SESSION['USER']->user_type !== 'admin') {
//                 redirect('home');
//             }
//         } else {
//             redirect('home');
//         }
//         $product = new Product_process();
//         $order_header = new OrderHeader_process();
//         $result_order_unpaid = $order_header->getOrderHeaderByStatusID('2', 'DESC');
//         $data['order_shipping'] = $result_order_unpaid;
//         $this->view('admin/dashboard', true, $data);
//     }

//     public function user() {
//         if (isset($_SESSION['USER'])) {
//             if ($_SESSION['USER']->user_type !== 'admin') {
//                 redirect('home');
//             }
//         } else {
//             redirect('home');
//         }
//         $user = new User();
//         if (isset($_GET['id'])) {
//             $target_customer_id = $_GET['id'];
//             $result_user = $user->getUserByCustID($target_customer_id);
//             if (!empty($result_user)) {
//                 $user_current_type = $result_user->user_type;
//                 $user_new_type = $user_current_type == 'user' ? 'admin' : 'user';
//                 $user_data = array(
//                     'user_type' => $user_new_type
//                 );
//                 $result = $user->updateUser($target_customer_id, $user_data);
//                 if ($result) {
//                     echo "<script>alert('การดำเนินการเสร็จสิ้น');</script>";
//                 } else {
//                     $data['errors'] = 'Something went wrong with the change.';
//                 }
//             } else {
//                 $data['errors'] = 'User not found.';
//             }
//         } else {
//             $data['errors'] = 'GET id not found';
//         }
//         $this->view('admin/user', true, $data);
//     }

//     public function product() {
//         if (isset($_SESSION['USER'])) {
//             if ($_SESSION['USER']->user_type !== 'admin') {
//                 redirect('home');
//             }
//         } else {
//             redirect('home');
//         }
//         $category = new Category_process();
//         $product = new Product_process();

//         // ดึง category มาแสดง
//         $data['category'] = $category->getAllCategory();

//         $where_arr = array();
//         if (isset($_GET['search'])) {
//             if (!empty($_GET['search'])) {
//                 $search = $_GET['search'];
//                 $where_arr = array(
//                     'CONCAT(product_id, product_name, product_description) LIKE ?' => "%{$search}%"
//                 );
//             }
//         }
//         if (isset($_GET['category'])) {
//             if (!empty($_GET['category'])) {
//                 $category_id = $_GET['category'];
//                 if (empty($where_arr)) {
//                     $where_arr = array(
//                         "category_id = ?" => $category_id
//                     );
//                 } else {
//                     $where_arr['category_id = ?'] = $category_id;
//                 }
//             }
//         }
//         if (!empty($where_arr)) {
//             $select = "*";
//             $result_product = $product->customSelect($select, $where_arr);
//         } else {
//             $result_product = $product->findAll();
//         }
//         if ($result_product) {
//             $data['search'] = $result_product;
//         } else {
//             $data['errors'] = "Don't have any product";
//         }
//         // แสดงตอนเพิ่ม สต็อกสินค้า
//         $data['product'] = $product->findAll();


//         // update product
//         if (isset($_POST['stockProduct'])) {
//             $product_id = $_POST['stockProduct'];
//             if (isset($_POST['stockAmount'])) {
//                 $nums = (int)$_POST['stockAmount'];
//                 $result = $product->updateStock($product_id, $nums);
//                 if ($result) {
//                     echo "<script>alert('Update product successful.');</script>";
//                     echo "<script>window.location='" . ROOT . "/admin/product';</script>";
//                 } else {
//                     echo "<script>alert('Update product failed.');</script>";
//                 }
//             }
//         }

//         // add product
//         if (isset($_POST['add_product'])) {
//             // การจัดการไฟล์รูปภาพ
//             $productImageName = $_FILES['productImage']['name']; // เพิ่ม timestamp เพื่อให้ชื่อไฟล์ไม่ซ้ำกัน
//             $productImageTmpName = $_FILES['productImage']['tmp_name'];
//             $productImagePath = "../public/img/product_image/" . basename($productImageName); // ใช้ basename() เพื่อความปลอดภัย
//             // ย้ายไฟล์รูปภาพไปยังโฟลเดอร์ที่กำหนด
//             if (move_uploaded_file($productImageTmpName, $productImagePath)) {
//                 $product_data = array(
//                     'product_name' => $_POST['productName'],
//                     'category_id' => $_POST['productCategory'],
//                     'product_description' => $_POST['productDescription'],
//                     'original_price' => $_POST['productPriceOriginal'],
//                     'unit_price' => $_POST['productPriceSale'],
//                     'stock_qty' => $_POST['productStock'],
//                     'product_image' => $productImageName
//                 );
//                 $result = $product->insertProduct($product_data);
//                 if ($result) {
//                     echo "<script>alert('Add product successful');</script>";
//                     echo "<script>window.location='" . ROOT . "/admin/product';</script>";
//                 } else {
//                     echo "<script>alert('Add product failed');</script>";
//                 }
//             } else {
//                 echo "<script>alert('There was a problem uploading the image');</script>";
//             }
//         }

//         // กดปุ่ม edit แล้วเปิดหน้า edit product
//         if (isset($_GET['edit_id'])) {
//             redirect('admin/product_edit?edit_id=' . $_GET['edit_id']);
//         } else { // ตรวจสอบว่ามี ID ของสินค้าหรือไม่
//             echo "<script>alert('Don't have a product.');</script>";
//         }

//         // ลบ product
//         if (isset($_GET['delete_id'])) {
//             $product_id =  $_GET['delete_id'];
//             $result = $product->deleteProduct($product_id);
//             if ($result) {
//                 echo "<script>alert('Delete product successful');</script>";
//                 echo "<script>window.location='" . ROOT . "/admin/product';</script>";
//             } else {
//                 echo "<script>alert('Delete product failed');</script>";
//             }
//         }
//         $this->view('admin/product', true, $data);
//     }

//     public function order_all() {
//         $order_header = new OrderHeader_process();
//         if (isset($_SESSION['USER'])) {
//             if ($_SESSION['USER']->user_type !== 'admin') {
//                 redirect('home');
//             }
//         } else {
//             redirect('home');
//         }

//         if (isset($_GET['order_id_shipping'])) {
//             $order_id = $_GET['order_id_shipping'];
//             if ($order_header->setStatusByID($order_id, '3')) {
//                 if ($order_header->insertTrackNumber($order_id)) {
//                 } else {
//                     $data['errors'] = "Insert TrackiFng Number failed.";
//                 }
//             } else {
//                 $data['errors'] = "Update Status failed.";
//             }
//         }
//         $where_arr = array();
//         $order_header = new OrderHeader_process();
//         if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
//             $order_status_id = $_GET['order_status'];
//             $where_arr = array(
//                 'order_status_id = ?' => $order_status_id
//             );
//         }

//         if (isset($_GET['search'])) {
//             $search = $_GET['search'];
//             $where_arr = array(
//                 'CONCAT(order_id, customer_id) LIKE ?' => "%{$search}%"
//             );
//         } else {
//             $result_order_header = $order_header->findAll();
//             $data['order_header'] = $result_order_header;
//         }
//         if (isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] != '' && $_GET['end_date'] != '') {
//             $start_date = $_GET['start_date'];
//             $end_date = $_GET['end_date'];
//             $where_arr = array(
//                 'order_datetime BETWEEN ? AND ?' => "$start_date",
//                 '' => "$end_date"
//             );
//         }
//         if (!empty($where_arr)) {
//             $select = "*";
//             $order_by = "order_id DESC";
//             $result_order_header = $order_header->customSelect($select, $where_arr, $order_by);
//             $data['order_header'] = $result_order_header;
//         }


//         $this->view('admin/order_all', true, $data);
//     }

//     public function product_edit() {
//         if (isset($_SESSION['USER'])) {
//             if ($_SESSION['USER']->user_type !== 'admin') {
//                 redirect('home');
//             }
//         } else {
//             redirect('home');
//         }
//         $product = new Product_process();
//         $category = new Category_process();
//         if (isset($_GET['edit_id'])) {
//             $product_id = $_GET['edit_id'];

//             // ดึงสินค้า
//             $result_product = $product->getProductByID($product_id);
//             $data['product_edit'] = $result_product;

//             // ดึงข้อมูลประเภทสินค้า
//             $result_category = $category->getAllCategory();
//             $data['category'] = $result_category;
//         }

//         // กดปุ่ม Submit ตอน edit product
//         if (isset($_POST['submit_edit'])) {
//             if (isset($_POST['product_id'])) {
//                 $product_id = $_POST['product_id'];
//             } else {
//                 echo "<script>alert('Don't have a product.');</>";
//             }
//             $productImageName = $_FILES['productImage']['name']; // เพิ่ม timestamp เพื่อให้ชื่อไฟล์ไม่ซ้ำกัน
//             $productImageTmpName = $_FILES['productImage']['tmp_name'];
//             $productImagePath = "../public/img/product_image/" . basename($productImageName); // ใช้ basename() เพื่อความปลอดภัย
//             // ย้ายไฟล์รูปภาพไปยังโฟลเดอร์ที่กำหนด
//             if (move_uploaded_file($productImageTmpName, $productImagePath)) {
//                 $product_data = array(
//                     'product_name' => $_POST['productName'],
//                     'category_id' => $_POST['productCategory'],
//                     'product_description' => $_POST['productDescription'],
//                     'original_price' => $_POST['productPriceOriginal'],
//                     'unit_price' => $_POST['productPriceSale'],
//                     'stock_qty' => $_POST['productStock'],
//                     'product_image' => $productImageName
//                 );
//                 $result = $product->updateProduct($product_id, $product_data);
//                 if ($result) {
//                     echo "<script>alert('Update product successful');</script>";
//                     echo "<script>window.location='" . ROOT . "/admin/product';</script>";
//                 } else {
//                     echo "<script>alert('Update product failed');</script>";
//                 }
//             } else {
//                 echo "<script>alert('There was a problem uploading the image');</script>";
//             }
//         }
//         $this->view('admin/product_edit', false, $data);
//     }

//     public function report_sale_product() {
//         if (isset($_SESSION['USER'])) {
//             if ($_SESSION['USER']->user_type !== 'admin') {
//                 redirect('home');
//             }
//         } else {
//             redirect('home');
//         }
//         $this->view('admin/report_sale_product', false);
//     }

//     public function report_sale_customer() {
//         if (isset($_SESSION['USER'])) {
//             if ($_SESSION['USER']->user_type !== 'admin') {
//                 redirect('home');
//             }
//         } else {
//             redirect('home');
//         }
//         $this->view('admin/ ', false);
//     }
//     public function report_stock_product() {
//         if (isset($_SESSION['USER'])) {
//             if ($_SESSION['USER']->user_type !== 'admin') {
//                 redirect('home');
//             }
//         } else {
//             redirect('home');
//         }
//         $this->view('admin/report_stock_product', false);
//     }

//     public function report_profit_product() {
//         if (isset($_SESSION['USER'])) {
//             if ($_SESSION['USER']->user_type !== 'admin') {
//                 redirect('home');
//             }
//         } else {
//             redirect('home');
//         }
//         $this->view('admin/report_profit_product', false);
//     }
// }
