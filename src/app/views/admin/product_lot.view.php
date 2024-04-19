<?php
include 'controller/condb.php';

$searchQuery = '';
$searchValue = isset($_GET['search']) ? $_GET['search'] : '';
$filterValue = isset($_GET['filter']) ? $_GET['filter'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

if (!empty($searchValue)) {
    $searchQuery .= " AND (pl.lot_id LIKE '%$searchValue%' OR pl.product_id LIKE '%$searchValue%' OR p.product_name LIKE '%$searchValue%')";
}

if (!empty($filterValue) && $filterValue != 'all') {
    $searchQuery .= " AND pl.product_id = '$filterValue'";
}

if ($statusFilter == 'not_expired') {
    $searchQuery .= " AND pl.expiry_date >= CURDATE()";
} elseif ($statusFilter == 'expired') {
    $searchQuery .= " AND pl.expiry_date < CURDATE()";
}

$sql = "SELECT pl.lot_id, pl.product_id, p.product_name, pl.quantity, pl.production_date, pl.expiry_date, pl.amount 
        FROM product_lot pl 
        INNER JOIN product p ON pl.product_id = p.product_id 
        WHERE 1=1 $searchQuery";
$result = mysqli_query($conn, $sql);

$productQuery = "SELECT product_id, product_name FROM product ORDER BY product_name";
$productResult1 = mysqli_query($conn, $productQuery);
$productResult2 = mysqli_query($conn, $productQuery);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lot_Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>จัดการสต็อกสินค้า</h4>
            <div class="ms-auto d-flex">
                <form class="d-flex me-2" role="search">
                    <input class="form-control me-2" type="text" placeholder="ค้นหาล็อตหรือสินค้า" name="search" aria-label="Search" value="<?= $searchValue; ?>">
                    <select class="form-select me-2" name="filter" aria-label="Filter">
                        <option value="all">สินค้าทั้งหมด</option>
                        <?php while ($product = mysqli_fetch_assoc($productResult1)): ?>
                            <option value="<?= $product['product_id'] ?>" <?= ($filterValue == $product['product_id']) ? 'selected' : '' ?>><?= $product['product_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    <select class="form-select me-2" name="status" aria-label="Status">
                        <option value="">สถานะทั้งหมด</option>
                        <option value="not_expired" <?= $statusFilter == 'not_expired' ? 'selected' : '' ?>>ยังไม่หมดอายุ</option>
                        <option value="expired" <?= $statusFilter == 'expired' ? 'selected' : '' ?>>หมดอายุแล้ว</option>
                    </select>
                    <button class="btn btn-info" type="submit">ค้นหา</button>
                </form>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockModal">เพิ่มสต็อกสินค้า</button>
            </div>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table id="productsLotTable" class="productsLotTable">
                <thead>
                    <tr>
                        <th>Lot Num</th>
                        <th>รหัสสินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th>จำนวนสินค้า</th>
                        <th>วันผลิตสินค้า</th>
                        <th>วันหมดอายุสินค้า Date</th>
                        <th>ราคารวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['lot_id']) ?></td>
                            <td><?= htmlspecialchars($row['product_id']) ?></td>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= htmlspecialchars($row['production_date']) ?></td>
                            <td><?= htmlspecialchars($row['expiry_date']) ?></td>
                            <td><?= htmlspecialchars($row['amount']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>

    </div>

    <!-- add stock modal -->
    <div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStockModalLabel">เพิ่มสต็อกสินค้า</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?=ROOT?>/product_add_stock" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="stockProduct" class="form-label">เลือกสินค้า</label>
                                <select class="form-select" id="stockProduct" name="stockProduct" required>
                                    <option value="">เลือกสินค้า...</option>
                                    <?php while ($product = mysqli_fetch_assoc($productResult2)): ?>
                                        <option option value="<?= $product['product_id'] ?>"><?= htmlspecialchars($product['product_name']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="stockAmount" class="form-label">จำนวนที่เพิ่ม</label>
                                <input type="number" class="form-control" id="stockAmount" name="stockAmount" required>
                            </div>
                            <div class="mb-3">
                                <label for="productionDate" class="form-label">วันผลิต</label>
                                <input type="date" class="form-control" id="productionDate" name="productionDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="expiryDate" class="form-label">วันหมดอายุ</label>
                                <input type="date" class="form-control" id="expiryDate" name="expiryDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="costAmount" class="form-label">จำนวนต้นทุนที่เสียไป</label>
                                <input type="number" class="form-control" id="costAmount" name="costAmount" step="0.01" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            <button type="submit" class="btn btn-primary">เพิ่มสต็อก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</body>
    <script>
        $(document).ready(function() {
            $('#productsLotTable').DataTable({
                "searching": false, // Disable built-in search box since we're using our own
                "paging": false,
                "order": [],
                "columnDefs": [{ 
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
        });
    </script>
</html>
