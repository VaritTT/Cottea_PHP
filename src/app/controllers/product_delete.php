<?php
include 'condb.php';

if (!isset($_GET['id'])) {
    exit('No ID specified!');
}

$product_id = $_GET['id'];
$query = "DELETE FROM product WHERE product_id = '$product_id'";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('ลบข้อมูลสินค้าสำเร็จ'); window.history.go(-1);</script>";
} else {
    exit('Error deleting product');
}
?>
