<?php
include 'controller/condb.php';

$searchFilter = "";
$dateFilter = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $searchFilter = " WHERE CONCAT(order_id, customer_id) LIKE '%$search%'";
}

if (isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] != '' && $_GET['end_date'] != '') {
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];
    $dateFilter = " AND order_datetime BETWEEN '$startDate' AND '$endDate'";
}

$sql = "SELECT * FROM order_header" . $searchFilter . $dateFilter . " ORDER BY order_id DESC";
$result = mysqli_query($conn, $sql);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report All</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
</head>
<body>
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Order(ทั้งหมด)</h4>
            <div class="ms-auto d-flex">
                <form class="d-flex me-2" role="search" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4"><input class="form-control" type="search" placeholder="ค้นหารหัสออเดอร์ หรือรหัสผู้ใช้งาน" name="search" aria-label="Search" value="<?= $search ?? '' ?>"></div>
                        <div class="col-md-3"><input class="form-control" type="date" name="start_date" aria-label="Start date" value="<?= $startDate ?? '' ?>"></div>
                        <div class="col-md-3"><input class="form-control" type="date" name="end_date" aria-label="End date" value="<?= $endDate ?? '' ?>"></div>
                        <div class="col-md-2"><button class="btn btn-info w-100" type="submit">Search</button></div>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($result && mysqli_num_rows($result) > 0){ ?>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center col-1">Order ID</th>
                        <th class="text-center col-1">Customer ID</th>
                        <th class="text-center col-3">Address</th>
                        <th class="text-center col-2">Order Date</th>
                        <th class="text-center col-1">Total Price</th>
                        <th class="text-center col-1">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($result)) { ?>
                        <tr>
                            <td class="text-center"><?= $row['order_id'] ?></td>
                            <td class="text-center"><?= $row['customer_id'] ?></td>
                            <?php 
                            $sql_address = "SELECT * FROM address WHERE address_id = " . $row['shipping_address_id'];
                            $result_address = mysqli_query($conn, $sql_address);
                            $address = mysqli_fetch_assoc($result_address);
                            ?>
                            <td class="text-center"><?= $address['address_details'] ?></td>
                            <td class="text-center"><?= $row['order_datetime'] ?></td>
                            <td class="text-center"><?= $row['total_price'] ?></td>
                            <td class="text-center">
                                <?php
                                switch ($row['order_status_id']) {
                                    case 1: echo "<b style='color:black'>Unpaid</b>"; break;
                                    case 2: echo "<b style='color:green'>Paid</b>"; break;
                                    case 3: echo "<b style='color:green'>Shipping</b>"; break;
                                    case 4: echo "<b style='color:green'>Delivered</b>"; break;
                                    case 5: echo "<b style='color:red'>Cancelled</b>"; break;
                                    default: echo "Unknown"; break;
                                }
                                ?>
                            </td>
                        </tr>
                    <?php }; ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No orders found.</p>
        <?php }; ?>
        <?php mysqli_close($conn); ?>
    </div>

    <script>
        // JQuery
        $(document).ready( function () {
            $('table').DataTable({
                "searching": false,
                "paging": false,
                "lengthChange": false
            });
        } );
    </script>
</body>
</html>
