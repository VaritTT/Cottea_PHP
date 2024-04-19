<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS Folder -->
    <link rel="stylesheet" href="<?= ROOT ?>/css/style_register.css">

    <!-- icon boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Register</title>
</head>

<body>
    <div class="background-blur"></div>
    <div class="container">

        <div class="form-wrapper sign-in">
            <form method="POST">
                <h2>Sign Up</h2>
                <div class="field email-field">
                    <div class="input-group">
                        <input type="text" class="email" name="email" required>
                        <label for="">Email</label>
                    </div>
                    <?php if (!empty($data['errors'])) { ?>
                        <span class="error email-error email-already" style="display: flex; margin-bottom: 1.25rem;">
                            <i class="bx bx-error-circle error-icon"></i>
                            <p class="error-text"><?php echo $data['errors']['email'] ?></p>
                        </span>
                    <?php } ?>
                    <span class="error email-error">
                        <i class="bx bx-error-circle error-icon"></i>
                        <p class="error-text">Please enter a valid email</p>
                    </span>
                </div>

                <div class="remember">
                    <label for="">
                        <input type="checkbox" id="termsCheckbox"> Agree to the Service and<a href="" class="signUp-link"> Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn" id="submitButton" disabled>Confirm Email</button>

                <div class="sign-link">
                    <p class="opt1">Already have an account?<a href="<?= ROOT ?>/login" class="opt">Login</a></p>
                    <p class="opt2">Continue As<a href="<?= ROOT ?>/home" class="opt">Guest</a></p>
                </div>
            </form>
        </div>

        <div class="wrapper"></div>

    </div>

    <script src="<?= ROOT ?>/js/register.js"></script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>