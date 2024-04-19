<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- css -->
    <link rel="stylesheet" href="<?= ROOT ?>/css/style_login.css">
    <!-- icon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <title>Register</title>
</head>

<body>
    <div class="background-blur"></div>
    <div class="container">
        <div class="form-wrapper sign-in">
            <form id="loginForm" method="POST">
                <h2>Login</h2>
                <div class="field email-login">
                    <div class="input-group">
                        <input type="text" class="email" name="userInput" required>
                        <label>Email or Username</label>
                    </div>
                </div>
                <div class="field password-login">
                    <div class="input-group">
                        <input type="password" class="password-login" name="password" required>
                        <i class="bx bx-hide show-hide"></i>
                        <label>Password</label>
                    </div>
                </div>
                <?php if (!empty($data['errors'])) { ?>
                    <span class="error message-error" id="error-message">
                        <i class="bx bx-error-circle error-icon"></i>
                        <p class="error-text">Incorrect Username or Password.</p>
                    </span>
                <?php } ?>
                <button type="submit" class="btn btn-submit" name="login" id="submitButton">Login</button>
            </form>

            <form method="POST">
                <div class="divider"><span>Or</span></div>
                <!-- Google -->
                <button class="gsi-material-button btn-google" name="google_login">
                    <div class="gsi-material-button-content-wrapper">
                        <div class="gsi-material-button-icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: block;">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                                <path fill="none" d="M0 0h48v48H0z"></path>
                            </svg>
                        </div>
                        <span class="gsi-material-button-contents">Continue with Google</span>
                    </div>
                </button>
                <br>
                <!-- Github -->
                <button class="gsi-material-button btn-github" name="github_login">
                    <div class="gsi-material-button-content-wrapper">
                        <div class="gsi-material-button-icon">
                            <i class="fa-brands fa-github"></i>
                        </div>
                        <span class="gsi-material-button-contents">Continue with Github</span>
                    </div>
                </button>
            </form>
            <div class="sign-link">
                <p class="opt1">Don't have an account?<a href="<?= ROOT ?>/register" class="opt">Sign Up</a></p>
                <p class="opt2">Continue As<a href="<?= ROOT ?>/home" class="opt">Guest</a></p>
            </div>
        </div>
        <div class="wrapper"></div>
    </div>
    <script src="<?= ROOT ?>/js/login.js"></script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>