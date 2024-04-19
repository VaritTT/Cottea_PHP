<?php
include 'controller/condb.php';

// ตรวจสอบว่ามี ID ของสินค้าใน query string หรือไม่
if (!isset($_GET['id'])) {
    echo "ไม่พบ ID สินค้า"; exit;
}
$product_id = $_GET['id'];
$query = "SELECT * FROM product WHERE product_id = '$product_id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    echo "ไม่พบสินค้าด้วย ID นี้"; exit;
}

$product = mysqli_fetch_array($result);

// ดึงข้อมูลประเภทสินค้า
$categoryQuery = "SELECT * FROM categories ORDER BY category_name";
$categoryResult = mysqli_query($conn, $categoryQuery);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.4.0/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">แก้ไขสินค้า</h4>
                </div>
                <div class="card-body">
                    <form action="<?=ROOT?>/product_update" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">

                        <div class="mb-3">
                            <label for="productName" class="form-label">ชื่อสินค้า</label>
                            <input type="text" class="form-control" id="productName" name="productName" required value="<?= $product['product_name'] ?>">
                        </div>

                        <div class="mb-3">
                            <select class="form-select" id="productCategory" name="productCategory" required>
                                <option value="">เลือกประเภท...</option>
                                <?php while ($category = mysqli_fetch_array($categoryResult)){ ?>
                                <option value="<?= $category[0] ?>" <?php if ($product['category_id'] == $category['category_id']) echo "selected"; ?>><?= $category['category_name'] ?></option>
                                <?php }; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="productDescription" class="form-label">รายละเอียดสินค้า</label>
                            <textarea class="form-control" id="productDescription" name="productDescription" rows="1" required><?= $product['product_description'] ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="productPriceOriginal" class="form-label">ราคาต้นทุน</label>
                            <input type="number" class="form-control" id="productPriceOriginal" name="productPriceOriginal" required value="<?= $product['original_price'] ?>">
                        </div>

                        <div class="mb-3">
                            <label for="productPriceSale" class="form-label">ราคาขาย</label>
                            <input type="number" class="form-control" id="productPriceSale" name="productPriceSale" required value="<?= $product['unit_price'] ?>">
                        </div>

                        <div class="mb-3">
                            <label for="productStock" class="form-label">จำนวนสินค้าตอนนี้</label>
                            <input type="number" class="form-control" id="productStock" name="productStock" required value="<?= $product['stock_qty'] ?>">
                        </div>

                        <div class="mb-3">
                            <label for="productImage" class="form-label">รูปภาพสินค้า (ถ้าต้องการเปลี่ยน)</label>
                            <input type="file" class="form-control" id="productImage" name="productImage">
                            <small class="text-muted">ปล่อยว่างไว้หากไม่ต้องการเปลี่ยนรูปภาพ</small>
                        </div>

                        <a href="javascript:history.back()" class="btn btn-secondary mt-2">ย้อนกลับ</a>
                        <button type="submit" class="btn btn-primary mt-2">บันทึกการเปลี่ยนแปลง</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
