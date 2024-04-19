<?php

require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'default_font_size' => 12,
    'default_font' => 'sarabun'
]);

ob_start();
include 'controller/condb.php';

$totalCost = $totalSales = $totalProfit = $totalCostAll = $totalSalesAll = 0;
$sql = "SELECT product_id, product_name, original_price, unit_price FROM product ORDER BY product_id ASC";
$products = mysqli_query($conn, $sql);

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

        thead.head1 th,
        tbody th {
            border-bottom: 0.5px dashed black;
            padding-top: 16px;
            padding-bottom: 8px;
        }

        tfoot th {
            border-top: 0.5px dashed black;
        }

        th,
        td {
            border: none;
        }
    </style>
</head>

<body>
    <table class="report-header">
        <thead>
            <tr>
                <th colspan="3" style="text-align: left; padding-bottom: 12px;">
                    <h3>Cottea Cafe</h3>
                </th>
            </tr>
            <tr>
                <td colspan="1" style="font-weight: 600;">รายงานกำไร/ขาดทุน แยกตามสินค้า</td>
                <td colspan="2" style="font-weight: 600;">บันทึก ณ วันที่ : <?= date("d-M-Y H:i:s") ?></td>
            </tr>
        </thead>
    </table>
    <table>
        <thead class="head1">
            <tr>
                <th style="text-align: left;">รหัสสินค้า</th>
                <th style="text-align: left;">ชื่อสินค้า</th>
                <th style="text-align: right;">จำนวนที่ขายได้</th>
                <th style="text-align: right;">ต้นทุนสินค้า</th>
                <th style="text-align: right;">ราคาที่ขายได้ทั้งหมด</th>
                <th style="text-align: right;">กำไร/ขาดทุน</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = mysqli_fetch_array($products)) {
                $productId = $product['product_id'];
                $salesResult = mysqli_query($conn, "SELECT SUM(qty) AS quantity_sold FROM order_detail WHERE product_id = '$productId'");
                $sales = mysqli_fetch_array($salesResult);
                $quantitySold = $sales['quantity_sold'] ?? 0;
                $totalCost = $product['original_price'] * $quantitySold;
                $totalSales = $product['unit_price'] * $quantitySold;
                $profit = $totalSales - $totalCost;

                // ปรับปรุงยอดรวม
                $totalCostAll += $totalCost;
                $totalSalesAll += $totalSales;
                $totalProfit += $profit;
            ?>
                <tr>
                    <!-- แสดงผลข้อมูลสินค้า -->
                    <td style="text-align: left; padding-top: 6px; padding-bottom: 6px;"><?= $product['product_id'] ?></td>
                    <td style="text-align: left; padding-top: 6px; padding-bottom: 6px;"><?= $product['product_name'] ?></td>
                    <td style="text-align: right; padding-top: 6px; padding-bottom: 6px;"><?= $quantitySold ?></td>
                    <td style="text-align: right; padding-top: 6px; padding-bottom: 6px;"><?= number_format($totalCost, 2) ?></td>
                    <td style="text-align: right; padding-top: 6px; padding-bottom: 6px;"><?= number_format($totalSales, 2) ?></td>
                    <td style="text-align: right; padding-top: 6px; padding-bottom: 6px;"><?= number_format($profit, 2) ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <!-- แสดงผลรวมของตาราง -->
                <th colspan="3" style="text-align: right; padding-top: 12px;">รวมทั้งหมด</th>
                <th style="text-align: right; padding-top: 12px;"><?= number_format($totalCostAll, 2) ?></th>
                <th style="text-align: right; padding-top: 12px;"><?= number_format($totalSalesAll, 2) ?></th>
                <th style="text-align: right; padding-top: 12px;"><?= number_format($totalProfit, 2) ?></th>
            </tr>
        </tfoot>
    </table>
</body>

</html>

<?php
$html = ob_get_contents(); // เก็บเนื้อหาที่เรียบเรียงเป็น HTML
ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering

$mpdf->WriteHTML($html); // เขียนเนื้อหา HTML ลงใน PDF
$mpdf->Output('Profit_Report.pdf', 'I'); // สร้างไฟล์ PDF โดยแสดงผลใน browser
?>