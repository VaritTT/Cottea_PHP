<?php
include 'controller/condb.php';

$searchQueryPart = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $searchQueryPart .= " WHERE CONCAT(product_id, product_name, product_description) LIKE '%$search%'";
}

if (isset($_GET['category']) && $_GET['category'] != '') {
    $categoryId = $_GET['category'];
    $searchQueryPart .= (empty($searchQueryPart) ? " WHERE " : " AND ") . "category_id = '$categoryId'";
}

$sql = "SELECT * FROM product" . $searchQueryPart;
$result = mysqli_query($conn, $sql);

$productQuery = "SELECT product_id, product_name FROM product ORDER BY product_name";
$productResult = mysqli_query($conn, $productQuery);

$categoryQuery = "SELECT * FROM categories ORDER BY category_name";
$categoryResult1 = mysqli_query($conn, $categoryQuery);
$categoryResult2 = mysqli_query($conn, $categoryQuery);
$categoryResult3 = mysqli_query($conn, $categoryQuery);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report All</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    <style>
        .product-image {
            border: none;
            text-align: center;
        }

        .product-image img {
            border-radius: 8px;
            border: 1px solid #ddd;
            width: 8rem;
            height: 8rem;
            object-fit: cover;
        }
    </style>

</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>จัดการสินค้าทั้งหมด</h4>

            <div class="ms-auto d-flex">
                <form class="d-flex me-2" method="GET" action="">
                    <input class="form-control me-2" type="text" placeholder="ค้นหาสินค้า" name="search" value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <select class="form-select me-2" name="category">
                        <option value="">ทุกประเภท</option>
                        <?php while ($category = mysqli_fetch_array($categoryResult2)) { ?>
                            <option value="<?= $category['category_id'] ?>" <?= isset($_GET['category']) && $_GET['category'] == $category['category_id'] ? 'selected' : '' ?>><?= $category['category_name'] ?></option>
                        <?php }; ?>
                    </select>
                    <button class="btn btn-info" type="submit">Search</button>
                </form>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockModal">เพิ่มสต็อกสินค้า</button>
                <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#addProductModal">เพิ่มสินค้า</button>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-4">
                <table id="productsTable" class="table">
                    <thead>
                        <tr>
                            <th class="text-center col-1">รหัสสินค้า</th>
                            <th class="text-center col-2">รูปสินค้า</th>
                            <th class="text-center col-1">ชื่อสินค้า</th>
                            <th class="text-center col-3">รายละเอียดสินค้า</th>
                            <th class="text-center col-1">ต้นทุน</th>
                            <th class="text-center col-1">ราคาขาย</th>
                            <th class="text-center col-1">จำนวนที่มี</th>
                            <th class="text-center no-sort col-2">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_array($result)) { ?>
                            <tr>
                                <td class="text-center"><?= $row['product_id'] ?></td>
                                <!-- <td class="text-center"><img src="image/<?php echo $row['product_image']; ?>" style="width: 50px; height: 50px;"></td> -->
                                <td class="text-center">
                                    <div class="product-image">
                                        <img src="<?= ROOT ?>/img/product_image/<?php echo $row['product_image'] ?>">
                                    </div>
                                </td>
                                <td class="text-center"><?= $row['product_name'] ?></td>
                                <td class="text-center"><?= $row['product_description'] ?></td>
                                <td class="text-center"><?= $row['original_price'] ?> บาท</td>
                                <td class="text-center"><?= $row['unit_price'] ?> บาท</td>
                                <td class="text-center"><?= $row['stock_qty'] ?></td>
                                <td class="text-center">
                                    <a href="<?= ROOT ?>/admin/product_edit?id=<?= $row['product_id'] ?>" class="btn btn-warning">แก้ไข</a>
                                    <a href="javascript:void(0);" onclick="confirmDelete(<?= $row['product_id'] ?>)" class="btn btn-danger">ลบ</a>
                                </td>
                            </tr>
                        <?php }; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">เพิ่มสินค้าใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= ROOT ?>/product_add" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <!-- ฟิลด์ข้อมูลสินค้า -->
                        <div class="mb-3">
                            <label for="productName" class="form-label">ชื่อสินค้า</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>

                        <div class="mb-3">
                            <label for="productCategory" class="form-label">ประเภทสินค้า</label>
                            <select class="form-select" id="productCategory" name="productCategory" required>
                                <option value="">เลือกประเภท...</option>
                                <?php while ($category = mysqli_fetch_assoc($categoryResult1)) { ?>
                                    <option value="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></option>
                                <?php }; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="productDescription" class="form-label">รายละเอียดสินค้า</label>
                            <textarea class="form-control" id="productDescription" name="productDescription" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="productPriceOriginal" class="form-label">ราคาต้นทุน</label>
                            <input type="number" class="form-control" id="productPriceOriginal" name="productPriceOriginal" required>
                        </div>

                        <div class="mb-3">
                            <label for="productPriceSale" class="form-label">ราคาขาย</label>
                            <input type="number" class="form-control" id="productPriceSale" name="productPriceSale" required>
                        </div>

                        <div class="mb-3">
                            <label for="productStock" class="form-label">จำนวนสินค้าตอนนี้</label>
                            <input type="number" class="form-control" id="productStock" name="productStock" required>
                        </div>

                        <div class="mb-3">
                            <label for="productImage" class="form-label">รูปภาพสินค้า</label>
                            <input type="file" class="form-control" id="productImage" name="productImage" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary">เพิ่มสินค้า</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- add stock modal -->
    <div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStockModalLabel">เพิ่มสต็อกสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= ROOT ?>/product_add_stock" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="stockProduct" class="form-label">เลือกสินค้า</label>
                            <select class="form-select" id="stockProduct" name="stockProduct" required>
                                <option value="">เลือกสินค้า...</option>
                                <?php while ($product = mysqli_fetch_assoc($productResult)) { ?>
                                    <option option value="<?= $product['product_id'] ?>"><?= $product['product_name'] ?></option>
                                <?php }; ?>
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


    <script>
        $(document).ready(function() {
            $('#productsTable').DataTable({
                "searching": false, // Disable built-in search box since we're using our own
                "paging": false,
                "order": [],
                "columnDefs": [{
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
        });

        function confirmDelete(productId) {
            if (confirm('คุณแน่ใจว่าต้องการลบสินค้านี้ใช่ไหม?')) {
                window.location.href = '<?= ROOT ?>/product_delete?id=' + productId;
            }
        }
    </script>
</body>

</html>