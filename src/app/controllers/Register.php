<?php

class Register {
    use Controller;

    public function index() {
        if (isset($_SESSION['USER'])) {
            if ($_SESSION['USER']->user_type != 'admin') {
                redirect('home');
            } else {
                redirect('admin/dashboard');
            }
        }
        $user = new User();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            // ไม่มีข้อมูล email ส่งมา
            if (!isset($_POST['email']) || empty($_POST['email'])) {
                echo "<script>alert('ไม่มีข้อมูล email'); window.location='register.html';</script>";
                exit();
            } else { // เอา email ที่เข้ามา ไปตรวจสอบ
                $email = htmlspecialchars($_POST['email']);
                if ($user->validateEmail($email)) { // ตรวจสอบ email ถูกต้องหรือไม่ และซ้ำหรือไม่
                    $receiverEmail = htmlspecialchars($_POST['email']);
                    $_SESSION['email'] = $receiverEmail;

                    // generate OTP
                    require('smtp/PHPMailerAutoload.php');
                    $otp = rand(100000, 999999);
                    $subject = "Send OTP Verification";
                    $emailbody = "Here is your 6 Digit OTP Code: ";
                    $_SESSION['OTP'] = $otp; // บันทึก OTP ไว้ใน session เพื่อตรวจสอบในภายหลัง

                    // ส่ง OTP ให้กับ user
                    $result = $user->smtp_mailer($receiverEmail, $subject, $emailbody . $otp);

                    if ($result) { // ส่งสำเร็จ
                        echo "<script>alert('We have sent a 6 Digit OTP code to your email: " . $to . "');</script>";
                        echo "<script>window.location.href='" . ROOT . "/otpconfirm';</script>";
                        // redirect('otpconfirm');
                    } else { // ส่งไม่สำเร็จ
                        echo "<script>alert('Something went wrong.');</script>";
                    }
                }
            }
        }
        if ($user->errors) { // ถ้ามี errors ให้เก็บไว้ใน $data['errors'] เพื่อนำไปแสดงผลในหน้า view
            $data['errors'] = $user->errors;
        }
        if ($user->errors) {
            $this->view('user/register', false, $data);
        } else {
            $this->view('user/register', false);
        }
    }
}
