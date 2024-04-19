<?php
include 'condb.php';

header('Content-Type: application/json'); // ตั้งค่า header ให้เป็น JSON

$response = array(); // สร้าง array รอเก็บ response

// ถ้า array ไม่มีค่าว่าง
if (!empty($_POST["username"])) {
  $username = mysqli_real_escape_string($conn, $_POST["username"]);
  $query = "SELECT * FROM customer WHERE username='$username'";
  $result = mysqli_query($conn, $query);
  $count = mysqli_num_rows($result);

  if ($count > 0) {
    // User exists
    $response = array(
      'status' => 'error',
      'message' => 'Username already exists.'
    );
  } else {
    // User available for registration
    $response = array(
      'status' => 'success',
      'message' => 'Username available.'
    );
  }
}

echo json_encode($response); // แปลง array เป็น JSON และ echo
