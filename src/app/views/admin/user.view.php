<?php
include 'controller/condb.php';

// ดึงข้อมูลมา
$user_type = isset($_GET['user_type']) ? $_GET['user_type'] : 'all';
$searchFilter = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) && in_array($_GET['sort'], ['customer_id', 'customer_name', 'sex', 'user_type']) ? $_GET['sort'] : 'customer_id';

// ดึงจากฐานข้อมูล
$sql = "SELECT * FROM customer WHERE 1 ";

// เผื่อ
// if ($user_type != 'all') {
//     $sql .= "AND user_type = '$user_type' ";
// }

// เพิ่มเงื่อนไขค้นหาถ้ามีการระบุ
if (!empty($searchFilter)) {
  $sql .= "AND (customer_id LIKE '%$searchFilter%' OR customer_name LIKE '%$searchFilter%' OR sex LIKE '%$searchFilter%') ";
}
// query
$result = mysqli_query($conn, $sql);
$cus_num = mysqli_num_rows($result);
?>

<!doctype html>
<html lang="en">

<head>
  <title>User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
</head>

<body>
  <div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4>จัดการผู้ใช้ทั้งหมด</h4>
      <form class="d-flex" method="get">
        <input class="form-control me-2" type="text" placeholder="ค้นหาลูกค้า" name="search" value="<?= $searchFilter; ?>">
        <button class="btn btn-info" type="submit">Search</button>
      </form>
    </div>

    <div class="row">
      <div class="col-12">
        <table id="customersTable" class="table">
          <thead>
            <tr>
              <th class="text-center">รหัสผู้ใช้</th>
              <th class="text-center">ประเภทผู้ใช้</th>
              <th class="text-center">ชื่อผู้ใช้</th>
              <th class="text-center">เพศ</th>
              <th class="text-center">ที่อยู่</th>
              <th class="text-center">เบอร์ติดต่อ</th>
              <th class="text-center">วันเกิด</th>
              <th class="text-center">ดำเนินการ</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_array($result)) { ?>
              <tr>
                <td class="text-center"><?= $row['customer_id'] ?></td>
                <td class="text-center"><?= $row['user_type'] ?></td>
                <td class="text-center"><?= $row['customer_name'] ?></td>
                <td class="text-center"><?= $row['sex'] ?></td>
                <td class="text-center"><?= $row['addr'] ?></td>
                <td class="text-center"><?= $row['tel'] ?></td>
                <td class="text-center"><?= date('d-m-Y', strtotime($row['birthDate'])) ?></td>
                <td class="text-center">
                  <a href="<?= ROOT ?>/admin/user_edit?id=<?= $row['customer_id'] ?>" class="btn btn-primary">แก้ไขข้อมูล</a>
                  <a href="<?= ROOT ?>/user_changetype?id=<?= $row['customer_id'] ?>&type=<?= $row['user_type'] ?>" class="btn btn-warning">เปลี่ยนตำแหน่ง</a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

  <script>
    $(document).ready(function() {
      $('#customersTable').DataTable({
        "order": [
          [0, "asc"]
        ],
        "searching": false,
        "paging": false,
        // "order": [],
        "columnDefs": [{
          "targets": 'no-sort',
          "orderable": false,
        }]
      });
    });
  </script>
</body>

</html>