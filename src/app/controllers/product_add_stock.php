<?php
include 'condb.php';

if (isset($_POST['stockProduct'], $_POST['productionDate'], $_POST['expiryDate'], $_POST['stockAmount'], $_POST['costAmount'])) {
    $product_id = $_POST['stockProduct'];
    $production_date = $_POST['productionDate'];
    $expiry_date = $_POST['expiryDate'];
    $quantity = $_POST['stockAmount'];
    $costAmount = $_POST['costAmount'];

    $sql = "INSERT INTO product_lot (product_id, quantity, production_date, expiry_date, amount) VALUES ('$product_id', '$quantity', '$production_date', '$expiry_date', '$costAmount')";
    $sql2 = "UPDATE product SET stock_qty = stock_qty + $quantity WHERE product_id = '$product_id'";

    if (mysqli_query($conn, $sql) && mysqli_query($conn, $sql2)) {
        echo "<script>alert('เพิ่มสต็อกสินค้าสำเร็จ'); window.location.href='" . ROOT . "/admin/product_lot';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน'); window.history.back();</script>";
}
