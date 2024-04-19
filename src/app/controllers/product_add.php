<?php
include 'condb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['productName'];
    $productCategory = $_POST['productCategory'];
    $productDescription = $_POST['productDescription'];
    $productPriceOriginal = $_POST['productPriceOriginal'];
    $productPriceSale = $_POST['productPriceSale'];
    $productStock = $_POST['productStock'];

    // การจัดการไฟล์รูปภาพ
    $productImageName = time() . '-' . $_FILES['productImage']['name'];
    $productImageTmpName = $_FILES['productImage']['tmp_name'];
    $productImagePath = "../image/" . basename($productImageName);

    // ย้ายไฟล์รูปภาพไปยังโฟลเดอร์ที่กำหนด
    if (move_uploaded_file($productImageTmpName, $productImagePath)) {
        $sql = "INSERT INTO product (product_name, category_id, product_description, original_price, unit_price, stock_qty, product_image) VALUES ('$productName', '$productCategory', '$productDescription', '$productPriceOriginal', '$productPriceSale', '$productStock', '$productImageName')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('เพิ่มสินค้าสำเร็จ'); window.location.href='" . ROOT . "/admin/product';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('มีปัญหาในการอัพโหลดรูปภาพ'); window.history.go(-1);</script>";
    }
    mysqli_close($conn);
}
?>
