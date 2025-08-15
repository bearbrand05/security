<?php
// --- MySQL Connection ---
$host = "localhost";
$username = "root";
$password = "";
$database = "inventory_db";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// --- Fetch total Delivered, Returned, Cancelled quantities ---
$totalDelivered = 0;
$totalReturned = 0;
$totalCancelled = 0;
$sql = "
  SELECT status_history, SUM(quantity_history) AS total
  FROM history_table
  GROUP BY status_history
";
$result = $conn->query($sql);
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $status = strtolower($row['status_history']);
    $qty = (int)$row['total'];
    if ($status === 'delivered') {
      $totalDelivered = $qty;
    } elseif ($status === 'returned') {
      $totalReturned = $qty;
    } elseif ($status === 'cancelled') {
      $totalCancelled = $qty;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order History Menu</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary-bg: #F2EFE5;
      --card-bg: #E3E1D9;
      --header-text: #B4B4B8;
      --button-bg: #C7C8CC;
      --button-hover: #B4B4B8;
      --text-dark: #2C3E50;
      --text-medium: #34495E;
      --text-light: #7F8C8D;
      --accent: #3498DB;
      --accent-light: #5DADE2;
      --accent-dark: #2980B9;
      --success: #2ECC71;
      --success-light: #58D68D;
      --warning: #F39C12;
      --warning-light: #F5B041;
      --danger: #E74C3C;
      --danger-light: #EC7063;
      --purple: #9B59B6;
      --purple-light: #BB8FCE;
      --teal: #1ABC9C;
      --teal-light: #48C9B0;
      --header-bg: #5D6D7E;
      --header-accent: #34495E;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #F2EFE5 0%, #E8E4DA 100%);
      color: var(--text-medium);
      min-height: 100vh;
      overflow-x: hidden;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    
    .header {
      background: linear-gradient(135deg, var(--header-bg) 0%, var(--header-accent) 100%);
      color: white;
      padding: 30px 0;
      margin-bottom: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      position: relative;
      overflow: hidden;
    }
    
    .header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="none"/><path d="M0,0 L100,100 M100,0 L0,100" stroke="rgba(255,255,255,0.1)" stroke-width="2"/></svg>');
      background-size: 20px 20px;
      opacity: 0.3;
    }
    
    .header-content {
      position: relative;
      z-index: 1;
      text-align: center;
      padding: 0 20px;
    }
    
    .header h1 {
      font-size: 42px;
      font-weight: 700;
      margin-bottom: 10px;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      letter-spacing: 1px;
    }
    
    .header p {
      font-size: 18px;
      font-weight: 400;
      opacity: 0.9;
    }
    
    .header-icon {
      display: inline-block;
      background: rgba(255, 255, 255, 0.2);
      width: 60px;
      height: 60px;
      border-radius: 50%;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
    }
    
    .stats-container {
      display: flex;
      gap: 30px;
      justify-content: center;
      margin-bottom: 60px;
      flex-wrap: wrap;
    }
    
    .stat-card {
      background: white;
      border-radius: 20px;
      padding: 35px 25px;
      width: 280px;
      text-align: center;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
      border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 8px;
    }
    
    .stat-card.delivered::before {
      background: linear-gradient(to right, var(--success), var(--success-light));
    }
    
    .stat-card.returned::before {
      background: linear-gradient(to right, var(--warning), var(--warning-light));
    }
    
    .stat-card.cancelled::before {
      background: linear-gradient(to right, var(--danger), var(--danger-light));
    }
    
    .stat-card:hover {
      transform: translateY(-15px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .stat-icon {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      font-size: 36px;
      color: white;
    }
    
    .stat-card.delivered .stat-icon {
      background: linear-gradient(135deg, var(--success), var(--success-light));
      box-shadow: 0 8px 20px rgba(46, 204, 113, 0.3);
    }
    
    .stat-card.returned .stat-icon {
      background: linear-gradient(135deg, var(--warning), var(--warning-light));
      box-shadow: 0 8px 20px rgba(243, 156, 18, 0.3);
    }
    
    .stat-card.cancelled .stat-icon {
      background: linear-gradient(135deg, var(--danger), var(--danger-light));
      box-shadow: 0 8px 20px rgba(231, 76, 60, 0.3);
    }
    
    .stat-card h2 {
      font-size: 48px;
      color: var(--text-dark);
      margin-bottom: 10px;
      font-weight: 700;
    }
    
    .stat-card p {
      color: var(--text-light);
      font-size: 16px;
      margin: 0;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 1.5px;
    }
    
    .action-links {
      display: flex;
      gap: 30px;
      justify-content: center;
      margin-bottom: 60px;
      flex-wrap: wrap;
    }
    
    .action-links a {
      text-decoration: none;
      font-size: 18px;
      font-weight: 600;
      color: white;
      background: linear-gradient(135deg, var(--accent) 0%, var(--purple) 100%);
      padding: 18px 36px;
      border-radius: 50px;
      box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      position: relative;
      overflow: hidden;
    }
    
    .action-links a::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s;
    }
    
    .action-links a:hover::before {
      left: 100%;
    }
    
    .action-links a i {
      margin-right: 12px;
      font-size: 20px;
    }
    
    .action-links a:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(52, 152, 219, 0.4);
    }
    
    .gallery-section {
      margin-top: 70px;
      background: white;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }
    
    .section-title {
      text-align: center;
      font-size: 28px;
      color: var(--text-dark);
      margin-bottom: 40px;
      font-weight: 600;
      position: relative;
      display: inline-block;
      width: 100%;
    }
    
    .section-title::after {
      content: '';
      position: absolute;
      bottom: -12px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: linear-gradient(to right, var(--accent), var(--purple));
      border-radius: 4px;
    }
    
    .image-gallery {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 25px;
    }
    
    .image-card {
      position: relative;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      transition: all 0.4s ease;
    }
    
    .image-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    
    .image-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      display: block;
      transition: transform 0.5s ease;
    }
    
    .image-card:hover img {
      transform: scale(1.1);
    }
    
    .image-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
      padding: 20px 15px 15px;
      color: white;
      font-weight: 500;
      transform: translateY(100%);
      transition: transform 0.3s ease;
    }
    
    .image-card:hover .image-overlay {
      transform: translateY(0);
    }
    
    .footer {
      text-align: center;
      margin-top: 60px;
      padding: 30px;
      color: var(--text-light);
      font-size: 16px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .floating-shapes {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -1;
      overflow: hidden;
    }
    
    .shape {
      position: absolute;
      opacity: 0.1;
      animation: float 15s infinite ease-in-out;
    }
    
    .shape-1 {
      width: 100px;
      height: 100px;
      background: var(--accent);
      border-radius: 50%;
      top: 20%;
      left: 10%;
      animation-delay: 0s;
    }
    
    .shape-2 {
      width: 80px;
      height: 80px;
      background: var(--purple);
      border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
      top: 60%;
      right: 15%;
      animation-delay: 2s;
    }
    
    .shape-3 {
      width: 120px;
      height: 120px;
      background: var(--teal);
      border-radius: 50%;
      bottom: 10%;
      left: 20%;
      animation-delay: 4s;
    }
    
    .shape-4 {
      width: 90px;
      height: 90px;
      background: var(--warning);
      border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%;
      top: 30%;
      right: 30%;
      animation-delay: 6s;
    }
    
    @keyframes float {
      0%, 100% {
        transform: translateY(0) rotate(0deg);
      }
      50% {
        transform: translateY(-30px) rotate(10deg);
      }
    }
    
    @media (max-width: 768px) {
      .header h1 {
        font-size: 32px;
      }
      
      .stat-card {
        width: 100%;
        max-width: 350px;
      }
      
      .action-links {
        flex-direction: column;
        align-items: center;
      }
      
      .action-links a {
        width: 100%;
        max-width: 350px;
        justify-content: center;
      }
      
      .image-gallery {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      }
    }
  </style>
</head>
<body>
  <div class="floating-shapes">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    <div class="shape shape-4"></div>
  </div>
  
  <div class="container">
    <div class="header">
      <div class="header-content">
        <div class="header-icon">
          <i class="fas fa-clipboard-list"></i>
        </div>
        <h1>ORDER HISTORY HUB</h1>
        <p>Track and explore past orders and categories</p>
      </div>
    </div>
    
    <div class="stats-container">
      <div class="stat-card delivered">
        <div class="stat-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <h2><?= $totalDelivered ?></h2>
        <p>Delivered Orders</p>
      </div>
      <div class="stat-card returned">
        <div class="stat-icon">
          <i class="fas fa-undo-alt"></i>
        </div>
        <h2><?= $totalReturned ?></h2>
        <p>Returned Orders</p>
      </div>
      <div class="stat-card cancelled">
        <div class="stat-icon">
          <i class="fas fa-times-circle"></i>
        </div>
        <h2><?= $totalCancelled ?></h2>
        <p>Cancelled Orders</p>
      </div>
    </div>
    
    <div class="action-links">
      <a href="arrival_history.php" target="_self">
        <i class="fas fa-box"></i> View Arrival History
      </a>
      <a href="category_tracking.php" target="_self">
        <i class="fas fa-folder-open"></i> View by Category
      </a>
    </div>
    
    <div class="gallery-section">
      <h2 class="section-title">Recent Orders</h2>
      <div class="image-gallery">
        <div class="image-card">
          <img src="https://via.placeholder.com/250x200/E3E1D9/4A4A4A?text=Order+1" alt="Order snapshot">
          <div class="image-overlay">Order #12345</div>
        </div>
        <div class="image-card">
          <img src="https://via.placeholder.com/250x200/E3E1D9/4A4A4A?text=Order+2" alt="Order snapshot">
          <div class="image-overlay">Order #12346</div>
        </div>
        <div class="image-card">
          <img src="https://via.placeholder.com/250x200/E3E1D9/4A4A4A?text=Order+3" alt="Order snapshot">
          <div class="image-overlay">Order #12347</div>
        </div>
        <div class="image-card">
          <img src="https://via.placeholder.com/250x200/E3E1D9/4A4A4A?text=Order+4" alt="Order snapshot">
          <div class="image-overlay">Order #12348</div>
        </div>
      </div>
    </div>
    
    <div class="footer">
      <p>© <?= date('Y') ?> Order History Hub. All rights reserved.</p>
    </div>
  </div>
</body>
</html>

<?php $conn->close(); ?>
