<?php
include 'condb.php';

if (!isset($_POST['product_id'])) {
    echo "ไม่พบ ID สินค้า";
    exit;
}

// รับค่าจากฟอร์ม
$product_id = $_POST['product_id'];
$productName = $_POST['productName'];
$productCategory = $_POST['productCategory'];
$productDescription = $_POST['productDescription'];
$productPriceOriginal = $_POST['productPriceOriginal'];
$productPriceSale = $_POST['productPriceSale'];
$productStock = $_POST['productStock'];

// ตรวจสอบและอัพโหลดรูปภาพถ้ามี
if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
    $target_file_name = $_FILES['productImage']['name'];
    $target_tmp_name = $_FILES['productImage']['tmp_name'];
    $target_file = "../public/img/product_image/" . basename($_FILES["productImage"]["tmp_name"]);
    if (move_uploaded_file($target_tmp_name, $target_file)) {
        // รูปภาพถูกอัพโหลด
        $productImage = $target_file_name;
    } else {
        echo "เกิดข้อผิดพลาดในการอัพโหลดรูปภาพ.";
        exit;
    }
} else {
    $productImage = ''; // ถ้าไม่มีการอัพโหลดรูปใหม่, ใช้ค่าว่างหรือดึงค่าเก่ามาจากฐานข้อมูล
}

// อัปเดตข้อมูลสินค้า
$query = "UPDATE product SET product_name = '$productName', category_id = '$productCategory', product_description = '$productDescription', original_price = '$productPriceOriginal', unit_price = '$productPriceSale',original_price = '$productPriceOriginal', stock_qty = '$productStock', product_image = '$productImage' WHERE product_id = '$product_id'";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('อัปเดตข้อมูลสำเร็จ'); window.history.go(-2);</script>";
} else {
    echo "<script>alert('เกิดข้อผิดพลาด'); window.history.go(-1);</script>";
}
