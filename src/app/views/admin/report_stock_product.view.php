<?php
require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'default_font_size' => 12,
    'default_font' => 'sarabun'
]);
ob_start(); // Start output buffering

include 'controller/condb.php'; // Include the database connection file

$totalCost = 0;

$sql = "SELECT product_id, product_name, stock_qty, original_price, unit_price, (stock_qty * original_price) AS total_cost
        FROM product
        ORDER BY product_id ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานสินค้าคงเหลือ</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead.head1 th, tbody th {
            border-bottom: 0.5px dashed black;
            padding-top: 16px;
            padding-bottom: 8px;
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
                    <td style="font-weight: 600;">รายงานสินค้าคงเหลือทั้งหมด</td>
                    <td style="font-weight: 600;">บันทึกวันที่ : <?= date("d-M-Y H:i:s") ?></td>
                </tr>
            </thead>
    </table>
    <table>
        <thead class="head1">
            <tr>
                <th style="text-align: left;">รหัสสินค้า</th>
                <th style="text-align: left; width: 40px">ชื่อสินค้า</th>
                <th style="text-align: right;">ราคาต้นขาย (ต่อชิ้น)</th>
                <th style="text-align: right;">ราคาต้นทุน (ต่อชิ้น)</th>
                <th style="text-align: right;">จำนวนคงเหลือ</th>
                <th style="text-align: right;">ราคารวมต้นทุน</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_array($result)) {
                $totalCost += $row['total_cost'];
            ?>
            <tr>
                <td style="text-align: left; padding-top: 12px; padding-bottom: 12px;"><?= $row['product_id'] ?></td>
                <td style="text-align: left; padding-top: 12px; padding-bottom: 12px;"><?= $row['product_name'] ?></td>
                <td style="text-align: right; padding-top: 12px; padding-bottom: 12px;"><?= number_format($row['unit_price'], 2) ?></td>
                <td style="text-align: right; padding-top: 12px; padding-bottom: 12px;"><?= number_format($row['original_price'], 2) ?></td>
                <td style="text-align: right; padding-top: 12px; padding-bottom: 12px;"><?= $row['stock_qty'] ?></td>
                <td style="text-align: right; padding-top: 12px; padding-bottom: 12px;"><?= number_format($row['total_cost'], 2) ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align: right; padding-top: 12px;">รวมต้นทุนทั้งหมด</th>
                <th style="text-align: right; padding-top: 12px;"><?= number_format($totalCost, 2) ?></th>
            </tr>
        </tfoot>
    </table>

    <?php
    $html = ob_get_contents();
    ob_end_clean();
    $mpdf->WriteHTML($html);
    $mpdf->Output('Stock_Product_Report.pdf', 'I');
    ?>
</body>
</html>
