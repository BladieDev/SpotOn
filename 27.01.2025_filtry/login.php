<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="style_logowanie.css">
</head>
<body>
  <div class="login-container">
    <h1>Login</h1>
    <form method="post" action="login.php">
      <?php include('errors.php'); ?>
      <div class="input-group">
        <label>Username</label>
        <input type="text" name="username">
      </div>
      <div class="input-group password-input">
        <label>Password</label>
        <input type="password" name="password" id="password">
        <span class="eye" onclick="togglePasswordVisibility()">👀</span>
      </div>
      <div class="input-group">
        <button type="submit" class="btn" name="login_user">Login</button>
      </div>
      <p>
        Not yet a member? <a href="register.php">Sign up</a>
      </p>
      <p>Nie pamietasz hasła? <a href="reset_hasla/brak_hasla.html">Reset password</a></p>
    </form>
  </div>
  <script src="script_logowanie.js"></script>
</body>
</html>