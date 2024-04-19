<?php
include 'condb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $customer_name = $_POST['customer_name'];
    $addr = $_POST['addr'];
    $tel = $_POST['tel'];
    $birthDate = $_POST['birthDate'];
    // ทำการอัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE customer SET customer_name='$customer_name', addr='$addr', tel='$tel', birthDate='$birthDate' WHERE customer_id='$customer_id'";
    mysqli_query($conn, $sql);

    echo "<script>alert('การแก้ไขข้อมูลสำเร็จ'); window.location.href='" . ROOT . "/admin/user';</script>";
} else {
    // ถ้าคำขอไม่ใช่ POST, กลับไปยังหน้าแก้ไข
    header("Location: " . ROOT . "/admin/user_edit?id=" . $_POST['customer_id']);
    exit();
}
