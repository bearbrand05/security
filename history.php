<?php
// --- MySQL Connection ---
$host = "localhost";
$username = "root";
$password = "";
<<<<<<< HEAD
$database = "jalosi";
=======
$database = "inventory_db";
>>>>>>> 303baf17d177401e3c3fcbc4d3a65964f486a7c3

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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      padding: 40px;
      font-family: 'Inter', sans-serif;
      background-color: #F2EFE5;
      color: #4A4A4A;
    }

    .header {
      text-align: center;
      margin-bottom: 40px;
    }

    .header h1 {
      font-size: 34px;
      color: #B4B4B8;
      margin-bottom: 12px;
      font-weight: bold;
    }

    .header p {
      font-size: 18px;
      color: #7A7A7A;
    }

    .cards {
      display: flex;
      gap: 20px;
      justify-content: center;
      margin-bottom: 40px;
      flex-wrap: wrap;
    }

    .card {
      background-color: #E3E1D9;
      border-radius: 14px;
      padding: 24px;
      width: 250px;
      text-align: center;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: scale(1.03);
    }

    .card h2 {
      font-size: 36px;
      color: #4A4A4A;
      margin-bottom: 8px;
    }

    .card p {
      color: #6A6A6A;
      font-size: 16px;
      margin: 0;
    }

    .history-links {
      display: flex;
      flex-direction: column;
      gap: 20px;
      align-items: center;
    }

    .history-links a {
      text-decoration: none;
      font-size: 18px;
      background-color: #C7C8CC;
      color: #fff;
      padding: 14px 24px;
      border-radius: 10px;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.07);
      transition: background-color 0.3s ease;
    }

    .history-links a i {
      margin-right: 8px;
    }

    .history-links a:hover {
      background-color: #B4B4B8;
    }

    .image-gallery {
      display: flex;
      gap: 15px;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 50px;
    }

    .image-gallery img {
      width: 160px;
      height: 100px;
      object-fit: cover;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>ORDER HISTORY HUB</h1>
    <p>Track and explore past orders and categories</p>
  </div>

  <div class="cards">
    <div class="card">
      <h2><?= $totalDelivered ?></h2>
      <p>Delivered Orders</p>
    </div>
    <div class="card">
      <h2><?= $totalReturned ?></h2>
      <p>Returned Orders</p>
    </div>
    <div class="card">
      <h2><?= $totalCancelled ?></h2>
      <p>Cancelled Orders</p>
    </div>
  </div>

  <div class="history-links">
    <a href="arrival_history.php" target="_self">
      <i class="fas fa-box"></i> View Arrival History
    </a>
    <a href="category_tracking.php" target="_self">
      <i class="fas fa-folder-open"></i> View by Category
    </a>
  </div>

  <div class="image-gallery">
    <img src="https://via.placeholder.com/160x100/E3E1D9/4A4A4A?text=Order+1" alt="Order snapshot">
    <img src="https://via.placeholder.com/160x100/E3E1D9/4A4A4A?text=Order+2" alt="Order snapshot">
    <img src="https://via.placeholder.com/160x100/E3E1D9/4A4A4A?text=Order+3" alt="Order snapshot">
  </div>

</body>
</html>

<<<<<<< HEAD
<?php $conn->close(); ?>

=======
<?php $conn->close(); ?>
>>>>>>> 303baf17d177401e3c3fcbc4d3a65964f486a7c3
