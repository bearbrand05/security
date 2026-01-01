<?php
require 'php/security.php';
$csrf = generate_csrf_token();
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
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
      <h2>Create your account</h2>
      <div class="lead">Join and keep your account secure.</div>

      <form method="post" action="php/register.php">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">

        <div class="form-row">
          <label for="username">Username</label>
          <input id="username" type="text" name="username" placeholder="Username" required>
        </div>

        <div class="form-row">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" placeholder="you@example.com" required>
        </div>

        <div class="form-row">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="Create a password" required>
          <div class="strength" aria-hidden="true"><i style="width:0%"></i></div>
          <div class="strength-text small">Enter a strong password</div>
        </div>

        <div class="actions">
          <button type="submit">Create account</button>
          <a class="btn secondary" href="login.php">Sign in</a>
        </div>
      </form>

      <div class="footer">By registering you agree to our terms.</div>
    </div>
  </div>
  <script src="js/form.js"></script>
</body>
</html>