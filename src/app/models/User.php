<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class User {
    use Controller, Model, GoogleOAuthClient, GithubOAuthClientTrait;
    protected $table = 'customer';

    // public function checkUsername($username) {
    //     $username_arr['username'] = $username;
    //     $result_user = $this->where($username);
    //     return $result_user;
    // }

    public function validateEmail($email) {
        $email_arr['email'] = $email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Please enter a valid email address.";
        }

        $row = $this->where($email_arr);
        if ($row) {
            $this->errors['email'] = "Email already exists.";
        }

        if (empty($this->errors)) {
            return true;
        }
        return false;
    }

    public function validateAccount($data) {
        $where_data['username'] = $data['username'];
        $minLength = 8;

        if (empty($data['username'])) {
            $this->errors['username'] = "Please enter your username.";
        }

        $row = $this->where($where_data);
        if ($row) {
            $this->errors['username'] = "Username already exists.";
        }

        if (strlen($data['password']) < $minLength) {
            $this->errors['password'] = "Your password must have at least 8 letters.";
        } else if (!preg_match('/[0-9]/', $data['password'])) {
            $this->errors['password'] = "Your password must contain at least 1 number letter.";
        } else if (!preg_match('/[a-z]/', $data['password'])) {
            $this->errors['password'] = "Your password must contain at least 1 lowercase letter.";
        } else if (!preg_match('/[A-Z]/', $data['password'])) {
            $this->errors['password'] = "Your password must contain at least 1 uppercase letter.";
        } else if (!preg_match('/[^A-Za-z0-9]/', $data['password'])) {
            $this->errors['password'] = "Your password must contain at least 1 special character.";
        } else if ($data['password'] !== $data['confirm_password']) {
            $this->errors['password'] = "Your password do not match.";
        }

        if (empty($this->errors)) {
            $length = random_int(97, 128);
            // random_byte คือการสุ่ม byte ที่แทนค่า 0-255 ออกมาเป็น unicode 
            // bin2hex คือการเปลี่ยนจากฐาน 2 เป็น 16
            // ตัวอย่าง สมมติว่าสุ่มได้ 4 byte = 32 bit = 8 ตัวอักษรในฐาน 16
            // แล้วเอาไปต่อกับ password จะเป็นการ hash + salt
            $salt_account = bin2hex(random_bytes($length));
            $password = $data['password'] . $salt_account;
            $algo = PASSWORD_ARGON2ID; // วิธีการ hash แบบ Argon2ID
            $options = [
                'cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
                'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
                'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
            ];
            $password_hash_salt = password_hash($password, $algo, $options);
            $data['password'] = $password_hash_salt;
            $insert_data['username'] = $data['username'];
            $insert_data['password'] = $data['password'];
            $insert_data['password_salting'] = $salt_account;
            $insert_data['email'] = $data['email'];
            print_r($insert_data);
            $this->insert($insert_data);
            return true;
        }
        return false;
    }

    // Register Send OTP method
    function smtp_mailer($to, $subject, $msg) {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 2;
        $mail->Username = "tcharoon45@gmail.com"; // Sender's Email
        $mail->Password = "amzmawhqzkgaebnk"; //Sender's Email App Password
        $mail->SetFrom("tcharoon45@gmail.com"); // Sender's Email
        $mail->Subject = $subject;
        $mail->Body = $msg;
        $mail->AddAddress($to);
        $mail->SMTPOptions = array('ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => false
        ));

        if (!$mail->Send()) {
            return false;
        } else {
            return true;
        }
    }

    public function checkUser($userData) {
        if (!empty($userData['email'])) {
            $result_user = $this->getUserByUsernameOrEmail($userData['email']);
        } else {
            $result_user = $this->getUserByUsernameOrEmail($userData['username']);
        }
        if ($result_user) { // ใช้ email และ google ในการ login
            // เข้าไป update ใส่ oauth provider กับ oauth uid
            if (empty($result_user->oauth_provider) || empty($result_user->oauth_uid) || $result_user->oauth_provider != $userData['oauth_provider']) {
                $oauth_arr['oauth_provider'] = $userData['oauth_provider'];
                $oauth_arr['oauth_uid'] = $userData['oauth_uid'];
                $this->update($result_user->customer_id, $oauth_arr, 'customer_id');
            }
        } else { // ใช้ google login เข้าไป insert ข้อมูล
            $this->insert($userData);
        }
    }

    public function googleLogin() {
        $google_client = $this->createGoogleClient();
        // เช็ค $_GET จาก OAuth ของ Google
        if (isset($_GET['code'])) {  // authenticate code from Google OAuth Flow
            $token = $google_client->fetchAccessTokenWithAuthCode($_GET['code']);
            $_SESSION['token'] = $token;
            header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
        }
        if (isset($_SESSION['token'])) {
            $google_client->setAccessToken($_SESSION['token']);
        }

        if ($google_client->getAccessToken()) {
            // get profile info
            $google_oauth = new Google\Service\Oauth2($google_client);
            $google_account_info = $google_oauth->userinfo->get();
            $userData['email'] = $google_account_info->email;
            $userData['customer_name'] = $google_account_info->name;
            $userData['oauth_provider'] = 'google';
            $userData['oauth_uid'] = $google_account_info->id;
            $this->checkUser($userData);
            $result_user = $this->getUserByUsernameOrEmail($userData['email']);

            $key = $this->jwtKey();
            $iat = time();
            $exp = $iat + 60 * 60; // expired 1 ชั่วโมง
            $payload = array(
                'iat' => $iat,
                'exp' => $exp,
                'data' => array(
                    'customer_id' => $result_user->customer_id,
                    'username' => $result_user->email
                ),
                'user_type' => $result_user->user_type
            );

            $token = JWT::encode($payload, $key, 'HS256');
            // params(ชื่อ, value, time, url, domain, https_only, ส่งเฉพาะ domain เท่านั้น)
            setcookie("token", $token, time() + 3600, "/", "", true, true);

            $_SESSION['USER'] = $result_user; // เก็บ SESSION ของ User ไว้
            if ($_SESSION['USER']->user_type === 'admin') {
                redirect('admin');
            }
        } else {
            // ดึง URL Google Login
            return $google_client->createAuthUrl();
        }
    }
    public function githubLogin() {
        $github_client = $this->createGithubClient();
        if (isset($_GET['code'])) { // (2)
            // Verify the state matches the stored state 
            if (!$_GET['state'] || $_SESSION['state'] != $_GET['state']) {
                header("Location: " . $_SERVER['PHP_SELF']);
            }
            // Exchange the auth code for a token 
            $access_token = $github_client->getAccessToken($_GET['state'], $_GET['code']);

            $_SESSION['access_token'] = $access_token;

            header('Location: ./');
        }
        if (isset($_SESSION['access_token'])) { // (3)
            $access_token = $_SESSION['access_token'];
            $github_account_info = $github_client->getAuthenticatedUser($access_token);
            if (!empty($github_account_info)) {
                $userData['username'] = $github_account_info->login;
                $userData['customer_name'] = !empty($github_account_info->name) ? $github_account_info->name : '';
                $userData['oauth_provider'] = 'github';
                $userData['oauth_uid'] = $github_account_info->id;
                $this->checkUser($userData);
                $result_user = $this->getUserByUsernameOrEmail($userData['username']);

                $key = $this->jwtKey();
                $iat = time();
                $exp = $iat + 60 * 60; // expired 1 ชั่วโมง
                $payload = array(
                    'iss' => 'http://localhost/fullstack/Cottea_PHP/src/public/',
                    'aud' => 'http://localhost/fullstack/Cottea_PHP/src/public/',
                    'iat' => $iat,
                    'exp' => $exp,
                    'data' => array(
                        'customer_id' => $result_user->customer_id,
                        'username' => $result_user->username
                    ),
                    'user_type' => $result_user->user_type
                );

                $token = JWT::encode($payload, $key, 'HS256');
                // params(ชื่อ, value, time, url, domain, https_only, ส่งเฉพาะ domain เท่านั้น)
                setcookie("token", $token, time() + 3600, "/", "", true, true);


                $_SESSION['USER'] = $result_user;
                if ($_SESSION['USER']->user_type === 'admin') {
                    redirect('admin');
                }
            }
        } else { // หน้าแรก login (1)
            // Generate a random hash and store in the session for security 
            $_SESSION['state'] = hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']);

            // Remove access token from the session 
            unset($_SESSION['access_token']);

            // Get the URL to authorize 
            $authUrl = $github_client->getAuthorizeURL($_SESSION['state']);

            return $authUrl;
        }
    }

    public function getUserByUsernameOrEmail($data) {
        if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
            $data_arr['email'] = $data;
        } else {
            $data_arr['username'] = $data;
        }
        $result_user = $this->where($data_arr);
        return $result_user[0];
    }

    public function getUserByCustID($customer_id) {
        $customer_id_arr['customer_id'] = $customer_id;
        $result_user = $this->where($customer_id_arr);
        return $result_user[0];
    }

    public function updateUser($customer_id, $data) {
        if ($this->update($customer_id, $data, 'customer_id')) {
            return true;
        }
        return false;
    }

    public function googleLogout() {
        $google_client = $this->createGoogleClient();
        $google_client->revokeToken();
    }
}
