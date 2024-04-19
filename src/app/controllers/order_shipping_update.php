<?php
include 'condb.php';

$sql = "UPDATE order_header SET order_status_id = 4 WHERE order_status_id = 3 AND order_datetime < NOW() - INTERVAL 7 DAY";
$result = mysqli_query($conn, $sql);

if (mysqli_affected_rows($conn) > 0) {



} else {
    echo "<script>alert('No orders found to update.'); window.location.href='" . ROOT . "/admin/order_shipping';</script>";
}

mysqli_close($conn);
