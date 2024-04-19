<?php

// load class ให้เรียกเป็น ให้ callback include ไฟล์เข้ามา
spl_autoload_register(function ($classname) {
    require $filename = "../app/models/" . ucfirst($classname) . ".php";
});
require 'config.php';
require 'functions.php';
require 'Database.php';
require 'Model.php';
require 'Controller.php';
require 'App.php';
require 'GithubOAuthClient.php';
require 'GoogleOAuthClient.php';
