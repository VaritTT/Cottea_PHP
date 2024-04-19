<?php
include 'condb.php';

// ตรวจสอบว่ามีการส่งค่า order_id และ new_status
if (isset($_GET['order_id']) && isset($_GET['new_status'])) {
    $orderId = mysqli_real_escape_string($conn, $_GET['order_id']);
    $newStatus = mysqli_real_escape_string($conn, $_GET['new_status']);

    // อัปเดต order_status_id ในตาราง order_header
    $sql = "UPDATE order_header SET order_status_id = $newStatus WHERE order_id = $orderId";

    if (mysqli_query($conn, $sql)) {
        // ถ้าอัปเดตสำเร็จ, กลับไปยังหน้า order_paid.php
        echo "<script>alert('อัปเดตข้อมูลสำเร็จ'); window.location=' " . ROOT . "/admin/order_paid';</script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
