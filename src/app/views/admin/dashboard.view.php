<?php
include 'controller/condb.php';

// ฟังก์ชันดึงข้อมูลสถิติ
function getStatistic($conn, $query) {
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_array($result);
  return $row[0];
}
// ดึงข้อมูลสถิติ จำนวนผู้ใช้, จำนวนสินค้า, จำนวนออเดอร์, จำนวนยอดขาย ทั้งหมด ผ่านฟังก์ชัน
$totalUsers = getStatistic($conn, "SELECT COUNT(*) FROM customer");
$totalProducts = getStatistic($conn, "SELECT COUNT(*) FROM product");
$totalOrders = getStatistic($conn, "SELECT COUNT(*) FROM order_header");
$totalSales = getStatistic($conn, "SELECT SUM(total_price) FROM order_header");

// ดึงตารางออเดอร์สินค้า(ที่ต้องจัดส่ง)
$sql1 = "SELECT order_id, customer_id, total_price, order_datetime, order_status_id FROM order_header WHERE order_status_id = '2' ORDER BY order_datetime DESC LIMIT 5";
$ordersResult = mysqli_query($conn, $sql1);

// ดึงตารางสินค้า (ที่ขายดี)
$sql2 = "SELECT p.product_id, p.product_name, p.stock_qty, SUM(od.qty) AS total_sold FROM order_detail od 
JOIN product p ON od.product_id = p.product_id GROUP BY od.product_id ORDER BY total_sold DESC LIMIT 5";
$sellingResult = mysqli_query($conn, $sql2);


// ฟังก์ชันดึงยอดขาย 5 วันล่าสุด
function getSalesLast5Days($conn) {
  // array ไว้เก็บยอดขาย
  $salesData = [];
  // เซ็ตยอดขายทั้ง 5 วันเป็น 0 เริ่มต้น
  for ($i = 0; $i < 5; $i++) {
    $date = date('Y-m-d', strtotime("-$i day"));
    $salesData[$date] = 0;
  }
  // ดึงข้อมูลยอดขาย
  $query = "SELECT DATE(order_datetime) as sale_date, SUM(total_price) as daily_sales 
            FROM order_header 
            WHERE order_datetime >= CURDATE() - INTERVAL 4 DAY 
            GROUP BY DATE(order_datetime) 
            ORDER BY sale_date DESC";
  $result = mysqli_query($conn, $query);
  // ปรับปรุงยอดขายตามข้อมูลที่เจอ
  while ($row = mysqli_fetch_assoc($result)) {
    $salesData[$row['sale_date']] = $row['daily_sales'];
  }
  return $salesData;
}
$salesLast5Days = getSalesLast5Days($conn);

// คำนวณยอดต้นทุน, ยอดขาย, และกำไรหรือขาดทุน
$totalCost = getStatistic($conn, "SELECT SUM(original_price * stock_qty) FROM product");
$totalProfit = $totalSales - $totalCost;
$salesGrowthRate = $totalCost > 0 ? ($totalSales - $totalCost) / $totalCost * 100 : 0;

$statistics = [
  'Total Cost' => number_format($totalCost, 2) . ' THB',
  'Total Sales' => number_format($totalSales, 2) . ' THB',
  'Profit / Loss' => number_format($totalProfit, 2) . ' THB',
  'Sales Growth Rate' => number_format($salesGrowthRate, 2) . '%',
];
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="dashboard.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-4">
    <!-- ส่วนแสดงสถิติ -->
    <div class="row">
      <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
          <div class="card-header">Total Users</div>
          <div class="card-body">
            <h5 class="card-title"><?= $totalUsers ?></h5>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
          <div class="card-header">Total Products</div>
          <div class="card-body">
            <h5 class="card-title"><?= $totalProducts ?></h5>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
          <div class="card-header">Total Orders</div>
          <div class="card-body">
            <h5 class="card-title"><?= $totalOrders ?></h5>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-danger mb-3">
          <div class="card-header">Total Sales</div>
          <div class="card-body">
            <h5 class="card-title"><?= number_format($totalSales, 2) ?> THB</h5>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">

      <!-- ตารางแสดงออเดอร์ล่าสุดที่ต้องจัดส่ง -->
      <div class="col-6">
        <h4>Latest Orders to Ship</h4>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th class="text-center">รหัสออเดอร์</th>
                <th class="text-center">รหัสผู้ใช้</th>
                <th class="text-center">ราคาทั้งหมด</th>
                <th class="text-center">วันที่สั่งซื้อ</th>
                <th class="text-center">สถานะ</th>
                <th class="text-center">ดำเนินการ</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($order = mysqli_fetch_array($ordersResult)) { ?>
                <tr>
                  <td class="text-center"><?= $order['order_id'] ?></td>
                  <td class="text-center"><?= $order['customer_id'] ?></td>
                  <td class="text-center"><?= number_format($order['total_price'], 2) ?></td>
                  <td class="text-center"><?= date('d-m-Y H:i', strtotime($order['order_datetime'])) ?></td>
                  <td class="text-center"><?= $order['order_status_id'] == '1' ? 'Pending' : ''; ?></td>
                  <td class="text-center"><a href="<?php echo ROOT ?>/order_status_update?order_id=<?php echo $order['order_id'] ?>&new_status=3" class="btn btn-warning btn-sm">อัพเดตสถานะ</a></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ส่วนแสดงยอดขาย 5 วันล่าสุด -->
      <div class="col-6">
        <h4>Best Selling Products</h4>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th class="text-center">รหัสสินค้า</th>
                <th class="text-center">ชื่อสินค้า</th>
                <th class="text-center">จำนวนที่ขายไป</th>
                <th class="text-center">จำนวนที่เหลือ</th>
                <th class="text-center">ดำเนินการ</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($product = mysqli_fetch_array($sellingResult)) { ?>
                <tr>
                  <td class="text-center"><?= $product['product_id'] ?></td>
                  <td class="text-center"><?= $product['product_name'] ?></td>
                  <td class="text-center"><?= $product['total_sold'] ?></td>
                  <td class="text-center"><?= $product['stock_qty'] ?></td>
                  <td class="text-center"><a href="<?= ROOT ?>/admin/product" class="btn btn-success btn-sm">จัดการสินค้า</a></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-6 mt-4">
        <h5 class="mb-4">ยอดขาย 5 วันล่าสุด</h5>
        <?php foreach ($salesLast5Days as $date => $dailySales) : ?>
          <h6><?= date('d-m-Y', strtotime($date)) ?></h6>
          <div class="progress mb-2">
            <div class="progress-bar" role="progressbar" style="width: <?= $dailySales > 0 ? ($dailySales / $totalSales * 100) : 0 ?>%;" aria-valuenow="<?= $dailySales ?>" aria-valuemin="0" aria-valuemax="100">
              <?= number_format($dailySales, 2) ?> THB
            </div>
          </div>
        <?php endforeach; ?>
      </div>


      <div class="col-6 mt-4">
        <h5>Financial Overview</h5>
        <div class="row">
          <?php
          reset($statistics); // รีเซ็ต array pointer
          while ($value = current($statistics)) {
            $title = key($statistics);
          ?>
            <div class="col-md-6 mt-4">
              <div class="card mb-3">
                <div class="card-header"><?= $title ?></div>
                <div class="card-body">
                  <h5 class="card-title"><?= $value ?></h5>
                </div>
              </div>
            </div>
          <?php
            next($statistics); // เลื่อนไปยังอีเลเมนต์ถัดไป
          }
          ?>
        </div>
      </div>

    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>