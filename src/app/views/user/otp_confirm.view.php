<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= ROOT ?>/css/style_otpconfirm.css">
    <link rel="stylesheet" href="<?= ROOT ?>/css/style_register.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <title>Register</title>
</head>

<body>
    <div class="container">
        <div class="form-wrapper confirm-mail">
            <form method="POST">
                <h2>Check Your Email</h2>
                <div class="sign-link">
                    <p>We send a verification link to</p>
                    <?php
                    echo "<b>" . $_SESSION['email'] . "</b>"; // แสดงอีเมล
                    ?>
                </div>

                <div class="input-field">
                    <input type="number" name="otp1" maxlength="1" />
                    <input type="number" name="otp2" maxlength="1" disabled />
                    <input type="number" name="otp3" maxlength="1" disabled />
                    <input type="number" name="otp4" maxlength="1" disabled />
                    <input type="number" name="otp5" maxlength="1" disabled />
                    <input type="number" name="otp6" maxlength="1" disabled />
                </div>


                <input type="hidden" name="otp">
                <button type="submit" class="btn2">Continue</button>


                <div class="sign-link">
                    <p>OTP not received ? <a href="otpconfirm?resend=" class="opt">Resend</a></p>
                </div>

            </form>
        </div>
        <div class="wrapper">
        </div>
    </div>

    <script src="<?= ROOT ?>/js/otpconfirm.js"></script>

</body>

</html>