<?php
include 'controller/condb.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo "<script>alert('ไม่พบ ID ผู้ใช้.'); window.location.href='" . ROOT . "/admin/user';</script>";
    exit;
}

$id = (int)$id; // แปลงค่าเป็น integer เพื่อความปลอดภัย
$sql = "SELECT customer_name, addr, tel, birthDate FROM customer WHERE customer_id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    echo "<script>alert('ไม่พบข้อมูลผู้ใช้.'); window.location.href='" . ROOT . "/admin/user';</script>";
    exit;
}

$customer = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>


<!doctype html>
<html lang="en">

<head>
    <title>แก้ไขข้อมูลผู้ใช้</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">แก้ไขข้อมูลผู้ใช้</h2>
        <form action="<?= ROOT ?>/user_edit_change" method="post">
            <input type="hidden" name="customer_id" value="<?= $id ?>">
            <div class="form-group">
                <label for="customer_name">ชื่อผู้ใช้:</label>
                <input type="text" id="customer_name" name="customer_name" class="form-control" value="<?= $customer['customer_name'] ?>" required>
            </div>
            <div class="form-group">
                <label for="addr">ที่อยู่:</label>
                <input type="text" id="addr" name="addr" class="form-control" value="<?= $customer['addr'] ?>" required>
            </div>
            <div class="form-group">
                <label for="tel">เบอร์ติดต่อ:</label>
                <input type="text" id="tel" name="tel" class="form-control" value="<?= $customer['tel'] ?>" required>
            </div>
            <div class="form-group">
                <label for="birthDate">วันเกิด:</label>
                <input type="date" id="birthDate" name="birthDate" class="form-control" value="<?= $customer['birthDate'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
        </form>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>