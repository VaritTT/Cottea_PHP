<?php
include 'condb.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // ขั้นแรกคือดึงประเภทผู้ใช้ปัจจุบันจากฐานข้อมูล
    $query = "SELECT user_type FROM customer WHERE customer_id = '$id'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $currentType = $row['user_type'];
        // กำหนดค่า newType ให้ตรงกันข้ามกับค่าปัจจุบัน
        $newType = $currentType == 'user' ? 'admin' : 'user';

        $sql = "UPDATE customer SET user_type = '$newType' WHERE customer_id = '$id'";
        if (mysqli_query($conn, $sql)) {
            echo "<script>
            alert('การดำเนินการเสร็จสิ้น');
            window.location.href='". ROOT . "/admin/user';
            </script>";
        } else {
            echo "เกิดข้อผิดพลาดในการเปลี่ยนแปลง: " . mysqli_error($conn);
        }
    } else {
        echo "ไม่พบผู้ใช้";
    }
} else {
    // ไม่ได้รับ ID
    echo "ข้อมูลไม่ครบถ้วน";
}
