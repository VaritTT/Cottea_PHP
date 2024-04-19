<?php
require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'default_font_size' => 13,
    'default_font' => 'sarabun'
]);

ob_start();
include 'controller/condb.php';

$startDate = $_POST['startDate'] . ' 00:00:00';
$endDate = $_POST['endDate'] . ' 23:59:59';

$orders = [];
$totalSales = 0;

// Fetch orders
$sqlOrders = "SELECT order_id, customer_id, order_datetime FROM order_header WHERE order_datetime BETWEEN '$startDate' AND '$endDate' ORDER BY order_id ASC";
$resultOrders = mysqli_query($conn, $sqlOrders);

while ($order = mysqli_fetch_array($resultOrders)) {
    $orders[$order['order_id']] = [
        'customer_id' => $order['customer_id'],
        'order_datetime' => $order['order_datetime'],
        'details' => [],
        'order_total' => 0 // Initialize order total
    ];
}

// Fetch all products for calculation
$products = mysqli_query($conn, "SELECT product_id, product_name, unit_price FROM product");
$productDetails = [];
while ($product = mysqli_fetch_array($products)) {
    $productDetails[$product['product_id']] = $product;
}

// Fetch order details
$sqlDetails = "SELECT order_id, product_id, qty FROM order_detail WHERE order_id IN (SELECT order_id FROM order_header WHERE order_datetime BETWEEN '$startDate' AND '$endDate')";
$resultDetails = mysqli_query($conn, $sqlDetails);

while ($detail = mysqli_fetch_array($resultDetails)) {
    if (isset($orders[$detail['order_id']])) {
        $productId = $detail['product_id'];
        $productInfo = $productDetails[$productId];
        $qty = $detail['qty'];
        $pricePerUnit = $productInfo['unit_price'];
        $totalPrice = $qty * $pricePerUnit;
        $orders[$detail['order_id']]['order_total'] += $totalPrice; // Add to order total
        $totalSales += $totalPrice;
        $orders[$detail['order_id']]['details'][] = [
            'product_id' => $productInfo['product_id'],
            'product_name' => $productInfo['product_name'],
            'qty' => $qty,
            'unit_price' => $pricePerUnit,
            'total_price' => $totalPrice
        ];
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
                <td colspan="1" style="font-weight: 600;">รายงานสรุปการขาย(แยกตามออเดอร์)</td>
                <td colspan="2" style="font-weight: 600;">เริ่มต้นวันที่ : <?= date('d/m/Y', strtotime($startDate)); ?></td>
                <td colspan="2" style="font-weight: 600;">จนถึงวันที่ : <?= date('d/m/Y', strtotime($endDate)); ?></td>
            </tr>
        </thead>
    </table>
    
    <table>
        <thead class="head1">
            <tr>
                <th style="text-align: left; padding-bottom: 12px; width: 100px;">รหัสสินค้า</th>
                <th style="text-align: left; padding-bottom: 12px; width: 300px;">ชื่อสินค้า</th>
                <th style="text-align: right; padding-bottom: 12px;">จำนวน</th>
                <th style="text-align: right; padding-bottom: 12px;">ราคาต่อหน่วย</th>
                <th style="text-align: right; padding-bottom: 12px;">ราคาขายทั้งหมด</th>
            </tr>
        </thead>
    </table>

    <?php foreach ($orders as $orderId => $order): ?>
    <table>
        <tbody>
            <tr>
                <td style="padding-top: 12px;"><strong>Order ID:</strong> <?= $orderId; ?></td>
                <td style="padding-top: 12px;"><strong>Customer ID:</strong> <?= $order['customer_id']; ?>, </td>
                <td style="padding-top: 12px;"><strong>Date:</strong> <?= date('d/m/Y H:i', strtotime($order['order_datetime'])); ?></td>
                <td style="text-align: right;"><strong>Order Total:</strong></td>
                <td style="text-align: right;"><strong><?= number_format($order['order_total'], 2); ?></strong></td>
            </tr>
            <?php foreach ($order['details'] as $detail): ?>
            <tr>
                <td style="text-align: left; padding-bottom: 8px;"><?= $detail['product_id']; ?></td>
                <td style="text-align: left; padding-bottom: 8px;"><?= $detail['product_name']; ?></td>
                <td style="text-align: right; padding-bottom: 8px;"><?= $detail['qty']; ?></td>
                <td style="text-align: center; padding-bottom: 8px;"><?= number_format($detail['unit_price'], 2); ?></td>
                <td style="text-align: right; padding-bottom: 8px;"><?= number_format($detail['total_price'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endforeach; ?>
    <table>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align: right; padding-top: 16px;">Total Sales:</th>
                <th style="text-align: right; right; padding-top: 16px;"><?= number_format($totalSales, 2); ?></th>
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
