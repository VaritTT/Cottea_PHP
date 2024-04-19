<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="<?= ROOT ?>/css/style_register.css">
  <link rel="stylesheet" href="<?= ROOT ?>/css/style_validation.css">

  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <!-- Fontawesome Link for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

  <title>Register</title>
</head>

<body>
  <div class="container">
    <div class="form-wrapper sign-in">
      <form method="POST">
        <h2>Create Account</h2>
        <div class="field confirm-username">
          <div class="input-group">
            <input type="text" class="username" name="username" id="username" onInput="checkUsername()" required>
            <label for="">Username</label>
          </div>
          <span class="error username-error">
            <i class="bx bx-error-circle error-icon"></i>
            <p class="error-text">Please enter a valid username</p>
          </span>
        </div>
        <?php if (!empty($data['errors'])) { ?>
          <span class="username-exists">
            <i class="bx bx-error-circle error-icon"></i>
            <p class="error-text">Username already exists.</p>
          </span>
        <?php } ?>

        <div class="field create-password">
          <div class="input-group">
            <input type="password" class="password" name="password" required />
            <i class="bx bx-hide show-hide"></i>
            <label for="">Password</label>
          </div>
          <span class="error password-error">
            <i class="bx bx-error-circle error-icon"></i>
            <p class="error-text">
              Please choose a more secure password.
            </p>
          </span>

          <div class="content">
            <p>Password must contains</p>
            <ul class="requirement-list">
              <li>
                <i class="fa-solid fa-circle"></i>
                <span class="er">At least 8 characters length</span>
              </li>
              <li>
                <i class="fa-solid fa-circle"></i>
                <span class="er">At least 1 number (0...9)</span>
              </li>
              <li>
                <i class="fa-solid fa-circle"></i>
                <span class="er">At least 1 lowercase letter (a...z)</span>
              </li>
              <li>
                <i class="fa-solid fa-circle"></i>
                <span class="er">At least 1 special symbol (!...$)</span>
              </li>
              <li>
                <i class="fa-solid fa-circle"></i>
                <span class="er">At least 1 uppercase letter (A...Z)</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="field confirm-password">
          <div class="input-group">
            <input type="password" class="cPassword" name="confirm_password" required />
            <i class="bx bx-hide show-hide"></i>
            <label for="">Confirm Password</label>
          </div>
          <span class="error cPassword-error">
            <i class="bx bx-error-circle error-icon"></i>
            <p class="error-text">Password don't match</p>
          </span>
        </div>
        <button type="submit" class="btn">Confirm</button>
      </form>
    </div>
    <div class="wrapper">
    </div>
  </div>

  <script src="<?= ROOT ?>/js/setprofile.js"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }

    const usernameInput = document.querySelector('.username');

    const usernameExists = document.querySelector('.username-exists');

    // ถ้ากรอกให้ error display none 
    usernameInput.addEventListener('keydown', () => { // email
      usernameExists.style.display = 'none';
    });



    function checkUsername() {
      jQuery.ajax({
        url: "../app/controllers/checkava.php",
        data: {
          username: $("#username").val()
        },
        type: "POST",
        dataType: 'json', // set response กลับมา
        success: function(data) {
          console.log(data); // เพิ่มบรรทัดนี้เพื่อตรวจสอบข้อมูลที่ส่งกลับมาจากเซิร์ฟเวอร์
          if (data.status === 'error') {
            $(".username-error").html(`<p style='color:red'>${data.message}</p>`).show();
          } else {
            $(".username-error").html(`<p style='color:green'>${data.message}</p>`).show();
          }
        },
        error: function(xhr, status, error) {
          // แสดงข้อความผิดพลาดหาก AJAX request ล้มเหลว
          console.log(xhr.responseText);
          $(".username-error").html("<p style='color:red'>An error occurred.</p>").show();
        }
      });
    }
  </script>
</body>

</html>