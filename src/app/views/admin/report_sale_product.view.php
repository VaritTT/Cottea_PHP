<?php
require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'default_font_size' => 12,
    'default_font' => 'sarabun'
]);

ob_start();
include 'controller/condb.php';

$startDate = isset($_POST['startDate']) ? $_POST['startDate'] . ' 00:00:00' : '';
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] . ' 23:59:59' : '';

$totalSales = 0;

// ดึงรายการ order_id ที่อยู่ในช่วงเวลาที่กำหนด
$sqlOrderIds = "SELECT order_id FROM order_header WHERE order_datetime BETWEEN '{$startDate}' AND '{$endDate}'";
$resultOrderIds = $conn->query($sqlOrderIds);

$orderIds = [];
while ($orderId = mysqli_fetch_array($resultOrderIds)) {
    $orderIds[] = $orderId['order_id'];
}

// ตรวจสอบว่ามี order_id หรือไม่
if (empty($orderIds)) {
    echo "<p>No sales data found for the selected period.</p>";
    return;
}

// ดึงข้อมูลสินค้า
$sqlProducts = "SELECT product_id, product_name, unit_price FROM product";
$resultProducts = $conn->query($sqlProducts);

$products = [];
while ($product = mysqli_fetch_array($resultProducts)) {
    $products[$product['product_id']] = $product;
}

$salesData = [];

// ดึงข้อมูลการขายสำหรับ order_id ที่ได้
foreach ($orderIds as $orderId) {
    $sqlSales = "SELECT product_id, qty FROM order_detail WHERE order_id = $orderId";
    $resultSales = $conn->query($sqlSales);

    while ($sale = mysqli_fetch_array($resultSales)) {
        $productId = $sale['product_id'];
        $qty = $sale['qty'];
        $pricePerUnit = $products[$productId]['unit_price'];
        $totalPrice = $qty * $pricePerUnit;

        if (!isset($salesData[$productId])) {
            $salesData[$productId] = ['qty' => 0, 'total_sales' => 0];
        }
        
        $salesData[$productId]['qty'] += $qty;
        $salesData[$productId]['total_sales'] += $totalPrice;
        $totalSales += $totalPrice;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานกำไร-ขาดทุน</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead.head1 th, tbody th {
            border-bottom: 0.5px dashed black;
        }
        tfoot th {
            border-top: 0.5px dashed black;
        }
        th, td {
            border: none;
        }
    </style>
</head>
<body>
    <table class="report-header">
        <thead>
            <tr>
                <th colspan="3" style="text-align: left; padding-bottom: 12px;"><h3>Cottea Cafe</h3></th>
            </tr>
            <tr>
                <td colspan="1" style="font-weight: 600;">รายงานสรุปการขาย(แยกตามสินค้า)</td>
                <td colspan="2" style="font-weight: 600;">เริ่มต้นวันที่ : <?= date('d/m/Y', strtotime($startDate)); ?></td>
                <td colspan="2" style="font-weight: 600;">จนถึงวันที่ : <?= date('d/m/Y', strtotime($endDate)); ?></td>
            </tr>
        </thead>
    </table>
    <table>
        <thead class="head1">
            <tr>
                <th style="text-align: left; padding-bottom: 12px;">รหัสสินค้า</th>
                <th style="text-align: left; padding-bottom: 12px;">ชื่อสินค้า</th>
                <th style="text-align: right; padding-bottom: 12px;">จำนวน</th>
                <th style="text-align: right; padding-bottom: 12px;">ราคาต่อหน่วย</th>
                <th style="text-align: right; padding-bottom: 12px;">ราคาขายทั้งหมด</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($salesData as $productId => $data): ?>
            <tr>
                <td style="text-align: left;"><?= $productId; ?></td>
                <td style="text-align: left;"><?= $products[$productId]['product_name']; ?></td>
                <td style="text-align: right;"><?= $data['qty']; ?></td>
                <td style="text-align: right;"><?= number_format($products[$productId]['unit_price'], 2); ?></td>
                <td style="text-align: right;"><?= number_format($data['total_sales'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align: right;">Total Sales:</th>
                <th style="text-align: right;"><?= number_format($totalSales, 2); ?></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>

<?php
$html = ob_get_contents();
ob_end_clean();

$mpdf->WriteHTML($html);
$mpdf->Output('Sale_Report.pdf', 'I');
?>
