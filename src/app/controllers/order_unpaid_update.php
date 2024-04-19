<?php
include 'condb.php';

$sql = "UPDATE order_header SET order_status_id = 5 WHERE order_status_id = 1 AND order_datetime < NOW() - INTERVAL 1 DAY";
$result = mysqli_query($conn, $sql);

if (mysqli_affected_rows($conn) > 0) {
    echo "<script>alert('Orders updated successfully.'); window.location.href='" . ROOT . "/admin/order_unpaid';</script>";
} else {
    echo "<script>alert('No orders found to update.'); window.location.href='" . ROOT . "/admin/order_unpaid';</script>";
}

mysqli_close($conn);
