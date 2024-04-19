<?php
require_once("../public/vendor/autoload.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


trait Controller {
    private $jwt_key;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        $this->jwt_key = $_ENV['JWTKEY'];
    }
    public function view($name, $nav = false, $data = []) {
        // if (!isset($_COOKIE['token']) && isset($_SESSION['USER'])) {
        //     redirect('logout');
        // }
        if (!empty($data)) {
            $tmp = extract($data); // เอาค่าแต่ละ index ของ $data ออกมา
        }
        $filename = "../app/views/" . $name . ".view.php"; // Ex. home.php
        if (file_exists($filename)) {
            if ($nav == true) {
                if (isset($_SESSION['USER']) && $_SESSION['USER']->user_type == 'admin') {
                    require_once "../app/views/admin/menu_admin.view.php";
                } else {
                    require_once "../app/views/user/menu_user.view.php";
                }
            }
            require $filename;
        } else {
            $filename = "../app/views/404.view.html";
            require $filename;
        }
    }

    public function jwtKey() {
        return $this->jwt_key;
    }

    public function authorizeAdmin($token) {
        // verify ว่า signature ตรงไหม และเช็คสิทธิ์ในการเข้าถึง
        try {
            $jwt_decode = JWT::decode($token, new Key($this->jwt_key, 'HS256'));
            // authorize สิทธิ์ admin กับ user
            if ($jwt_decode->user_type === 'admin') {
                return true;
            } else {
                return false;
            }
        } catch (\Firebase\JWT\ExpiredException $e) {
            // token หมดอายุ
            echo "<script>alert('Your token has expired. Please log in again.');</script>";
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            // token ไม่ถูกต้อง
            echo "<script>alert('Invalid token. Please provide a valid token.');</script>";
        } catch (Exception $e) {
            echo "<script>alert('An unexpected error occurred. Please try again later.');</script>";
        }
    }

    protected function checkAdminAccess() {
        // เช็คสิทธิ์จาก token JWT ที่เก็บไว้ใน cookie
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
            $isAdmin = $this->authorizeAdmin($token);
            if (!$isAdmin) {
                redirect('_404');
            }
        } else {
            redirect('_404');
        }
    }

    public function generateJWTToken($customer_id, $userInput, $user_type) {
        // generate JWT
        date_default_timezone_set('Asia/Bangkok');
        $key = $this->jwtKey();
        $iat = time();
        $exp = $iat + 60 * 15; // expired 15 นาที
        $exp_refresh = time() + 60 * 15;
        $payload = array(
            'iat' => $iat,
            'exp' => $exp,
            'data' => array(
                'customer_id' => $customer_id,
                'username' => $userInput
            ),
            'user_type' => $user_type
        );

        $token = JWT::encode($payload, $key, 'HS256');
        return $token;
        // if(isset($_COOKIE['token'])) {
        //     // generate refresh token
        //     $payload = array(
        //         'iat' => $iat,
        //         'exp' => $exp_refresh,
        //         'data' => array(
        //             'customer_id' => $customer_id,
        //             'username' => $userInput
        //         ),
        //         'user_type' => $user_type
        //     );

        // }
    }
}
