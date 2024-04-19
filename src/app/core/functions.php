<?php
function show($stuff) {
    echo "<pre>";
    print_r($stuff);
    echo "</pre>";
}

function esc($str) {
    // เปลี่ยน predefined characters เป็น HTML entities
    // เช่น  & -> &amp; " -> &quot;
    return htmlspecialchars($str);
}

function redirect($path) {
    die(header("Location: " . ROOT . "/" . $path));
}
