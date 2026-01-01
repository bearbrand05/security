<?php
require 'php/security.php';
$csrf = generate_csrf_token();
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="header">
    <a href="index.php" class="logo">
      <?php echo file_get_contents(__DIR__ . '/assets/logo.svg'); ?>
      <span class="site-title">SafeAccess</span>
    </a>
  </header>

  <div class="wrapper">
    <div class="container">
      <h2>Welcome back</h2>
      <div class="lead">Sign in to your account</div>

      <form method="post" action="php/login.php">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">

        <div class="form-row">
          <label for="username">Username</label>
          <input id="username" type="text" name="username" placeholder="Username" required>
        </div>

        <div class="form-row">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="Password" required>
        </div>

        <div class="actions">
          <button type="submit">Sign in</button>
          <a class="btn secondary" href="register.php">Create an account</a>
        </div>
      </form>

      <div class="footer">Having problems? Contact support.</div>
    </div>
  </div>
  <script src="js/form.js"></script>
  <script src="js/captcha.js"></script>
</body>
</html>