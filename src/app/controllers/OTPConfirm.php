<?php

class OTPConfirm {
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
        if (isset($_POST['otp'])) {
            $submittedOTP = $_POST['otp'];
            $sessionOTP = $_SESSION['OTP'];
            // ตรวจสอบ otp ที่ใส่เข้ามาตรงกับที่สุ่มขึ้นมาหรือไม่
            if ($submittedOTP == $sessionOTP) {
                echo "<script>alert('OTP is correct. Verification successful.');</script>";
                echo "<script>window.location.href='" . ROOT . "/createAccount';</script>";
            } else {
                echo "<script>alert('OTP is incorrect. Please try again.');</script>";
            }
        }
        if (isset($_GET['resend'])) {
            $receiverEmail = $_SESSION['email'];
            require('smtp/PHPMailerAutoload.php');
            $otp = rand(100000, 999999);
            $subject = "Send OTP Verification";
            $emailbody = "Here is your 6 Digit OTP Code: ";
            $_SESSION['OTP'] = $otp; // บันทึก OTP ไว้ใน session เพื่อตรวจสอบในภายหลัง

            $result = $user->smtp_mailer($receiverEmail, $subject, $emailbody . $otp);
            if ($result) {
                echo "<script>alert('We have sent a 6 Digit OTP code to your email: " . $to . "');</script>";
                redirect('otpconfirm');
            } else {
                echo "<script>alert('Something went wrong.');</script>";
            }
        }
        $this->view('user/otp_confirm', false);
    }
}
