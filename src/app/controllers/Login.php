<?php

class Login {
    use Controller;

    public function index() {
        date_default_timezone_set('Asia/Bangkok');
        if (isset($_SESSION['USER'])) {
            if ($_SESSION['USER']->user_type == 'admin') {
                redirect('admin/dashboard');
            }
        }
        $user = new User();
        $count_limit = 5; // จำนวนที่ห้าม login เกิน 
        $ban_time_minutes = 1; // เวลา(นาที)ในการ lock account
        
        if (isset($_SESSION['USER'])) {
            redirect('home');
        }
        if (empty($_SESSION['provider'])) {
            $google_login_link = $user->googleLogin();
            $github_login_link = $user->githubLogin();
        } else {
            if ($_SESSION['provider'] == 'google') {
                $google_login_link = $user->googleLogin();
            } else if ($_SESSION['provider'] == 'github') {
                $github_login_link = $user->githubLogin();
            }
        }
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['google_login'])) {
                if (!empty($google_login_link)) {
                    $_SESSION['provider'] = 'google';
                    die(header('Location: ' . $google_login_link));
                }
            }
            if (isset($_POST['github_login'])) {
                if (!empty($github_login_link)) {
                    $_SESSION['provider'] = 'github';
                    die(header('Location: ' . $github_login_link));
                }
            }
            if (isset($_POST['login'])) {
                if (empty($_POST['userInput'])) {
                    $user->errors['username'] = 'Please enter your username or email';
                } else {
                    $result_user_obj = $user->getUserByUsernameOrEmail(htmlspecialchars($_POST['userInput']));
                    if ($result_user_obj) {
                        $customer_id = $result_user_obj->customer_id;
                        if ($result_user_obj->lock_account == 1) { // เช็คว่าถูก lock account ไหม
                            echo "<script>alert('บัญชีของคุณถูกระงับ\\nบัญชีของคุณจะถูกระงับเป็นเวลา $ban_time_minutes นาที เนื่องจากกรอกรหัสผิดพลาดเกินกำหนด\\nโดยบัญชีนี้จะกลับมาใช้งานได้อีกครั้ง    ในเวลา" . $result_user_obj->ban_datetime . "');</script>";
                        } else if (password_verify(htmlspecialchars($_POST['password']) . $result_user_obj->password_salting, $result_user_obj->password)) {
                            // reset การ lock account
                            $count_login['count_login'] = 0;
                            $user->updateUser($customer_id, $count_login);

                            // ตรวจสอบว่ามี username หรือ email
                            if (!empty($result_user_obj->username)) {
                                $userInput = $result_user_obj->username;
                            } else {
                                $userInput = $result_user_obj->email;
                            }

                            // ตรวจสอบว่า user_type เป็นอะไร  
                            if ($result_user_obj->user_type === 'admin') {
                                $user_type = "admin";
                            } else {
                                $user_type = "user";
                            }

                            // generateJWTToken
                            $token = $this->generateJWTToken($customer_id, $userInput, $user_type);
                            // params(ชื่อ, value, time, url, domain, https_only, ส่งเฉพาะ domain เท่านั้น)
                            setcookie("token", $token, time() + 3600, "/", "", true, true);


                            // เก็บ SESSION ของ user เอาไว้                            
                            $_SESSION['USER'] = $result_user_obj;
                            unset($_SESSION['GUEST']);
                            if ($_SESSION['USER']->user_type == 'admin') {
                                redirect('admin');
                            } else {
                                redirect('home');
                            }
                        } else { // login ผิดพลาด เก็บจำนวนที่ผิดไว้ ถ้าเกินที่กำหนด โดน lock account
                            $count_login['count_login'] = $result_user_obj->count_login + 1;
                            if ($user->updateUser($customer_id, $count_login)) {
                                $result_user_check = $user->getUserByCustID($customer_id);
                                if ($result_user_check->count_login >= $count_limit) {
                                    $data_arr['lock_account'] = 1;
                                    $data_arr['ban_datetime'] = date('Y-m-d H:i:s', strtotime("+ $ban_time_minutes minutes"));;
                                    if ($user->updateUser($customer_id, $data_arr)) {
                                        echo "<script>alert('บัญชีของคุณถูกระงับ\\nบัญชีของคุณจะถูกระงับเป็นเวลา $ban_time_minutes นาที เนื่องจากผู้ใช้กรอกรหัสผิดเกินตามที่เว็บกำหนด\\nและบัญชีนี้จะกลับมาใช้งานได้ในเวลา" . $result_user_check->ban_datetime . "');</script>";
                                    } else {
                                        $user->errors['server'] = "Update user count login failed";
                                    }
                                }
                            } else {
                                $user->errors['server'] = "Update user count login failed";
                            }
                            $user->errors['username'] = "Invalid username/email or password.";
                        }
                    } else {
                        $user->errors['username'] = "Invalid username/email or password.";
                    }
                }
            }
            $data['errors'] = $user->errors;
        }
        if ($user->errors) {
            $this->view('user/login', false, $data);
        } else {
            $this->view('user/login', false);
        }
    }
}
