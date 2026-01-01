<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <header class="header">
    <a href="../index.php" class="logo">
      <?php echo file_get_contents(__DIR__ . '/../assets/logo.svg'); ?>
      <span class="site-title">SafeAccess</span>
    </a>
    <div style="margin-left:auto"><a class="btn" href="logout.php">Logout</a></div>
  </header>
  <div class="wrapper">
    <div class="container">
      <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></h2>
      <p class="small">You are logged in.</p>
    </div>
  </div>
</body>
</html>
