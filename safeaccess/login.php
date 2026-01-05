<?php
require 'php/security.php';
$csrf = generate_csrf_token();
$flash = null;
if (session_status() === PHP_SESSION_NONE) start_secure_session();
if (isset($_SESSION['flash'])) { $flash = $_SESSION['flash']; unset($_SESSION['flash']); }

// Ensure attempts counter and create a CAPTCHA when attempts >= 3 so the UI can render it
if (!isset($_SESSION['attempts'])) { $_SESSION['attempts'] = 0; }
$showCaptcha = false;
if ($_SESSION['attempts'] >= 3) {
    if (!isset($_SESSION['captcha'])) {
        $_SESSION['captcha'] = substr(str_shuffle("ABCDEFG123456"), 0, 5);
    }
    $showCaptcha = true;
}
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

      <?php if ($flash): ?>
      <div class="alert <?php echo htmlspecialchars($flash['type']); ?>">
        <span class="icon"><?php echo ($flash['type']==='success') ? '✔️' : '⚠️'; ?></span>
        <span class="message"><?php echo htmlspecialchars($flash['message']); ?></span>
      </div>
      <?php endif; ?>

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

        <?php if ($showCaptcha): ?>
        <div class="form-row" id="captchaBox">
          <label>CAPTCHA: <span id="captchaText"><?php echo htmlspecialchars($_SESSION['captcha']); ?></span></label>
          <div style="display:flex;gap:8px;align-items:center;margin-top:8px">
            <input id="captchaInput" type="text" name="captcha" placeholder="Enter CAPTCHA" required style="flex:1">
            <button type="button" id="captchaRefresh" class="btn secondary">Refresh</button>
          </div>
        </div>
        <?php endif; ?>

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
  <script>
    (function(){
      const a = document.querySelector('.alert');
      if (a) {
        setTimeout(()=>{
          a.style.transition = 'opacity .4s, max-height .4s';
          a.style.opacity = 0;
          a.style.maxHeight = '0';
          setTimeout(()=>a.remove(),500);
        },6000);
      }

      // CAPTCHA refresh handler
      const refresh = document.getElementById('captchaRefresh');
      if (refresh) {
        refresh.addEventListener('click', function(){
          fetch('php/captcha.php', { method: 'POST' })
            .then(r => r.json())
            .then(j => {
              const ct = document.getElementById('captchaText');
              const ci = document.getElementById('captchaInput');
              if (ct) ct.innerText = j.captcha;
              if (ci) ci.focus();
            })
            .catch(()=>{ alert('Failed to refresh CAPTCHA'); });
        });
      }
    })();
  </script>
</body>
</html>