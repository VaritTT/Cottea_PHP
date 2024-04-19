<?php
include 'condb.php';


if (isset($_GET['order_id']) && isset($_GET['new_status'])) {
    $order_id = $_GET['order_id'];
    $new_status = $_GET['new_status'];

    // ตรวจสอบและอัพเดตสถานะออเดอร์ในฐานข้อมูล
    $stmt = $conn->prepare("UPDATE order_header SET order_status_id = ? WHERE order_id = ?");
    $stmt->bind_param("ii", $new_status, $order_id);
    $stmt->execute();

    echo "<script>alert('Update status Delivered');window.history.go(-1);</script>";
    // ส่งกลับไปยังหน้าแสดงผลออเดอร์
    // header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
