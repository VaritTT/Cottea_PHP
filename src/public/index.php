<?php
session_start();

require "../app/core/init.php";

if (DEBUG) { // display_errors กำหนดให้แสดงหรือไม่แสดง errors
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}

$app = new App();
$app->loadController();