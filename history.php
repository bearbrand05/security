<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order History Menu</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 40px;
      font-family: 'Inter', sans-serif;
      background-color: #f9e1e0;
      color: #4a7ba6;
    }

    .header {
      text-align: center;
      margin-bottom: 40px;
    }

    .header h1 {
      font-size: 32px;
      color: #bc85a3;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .header p {
      font-size: 18px;
      color: #9799ba;
    }

    .cards {
      display: flex;
      gap: 20px;
      justify-content: center;
      margin-bottom: 40px;
      flex-wrap: wrap;
    }

    .card {
      background-color: #feadb9;
      border-radius: 12px;
      padding: 20px;
      width: 250px;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: scale(1.03);
    }

    .card h2 {
      font-size: 36px;
      color: white;
      margin-bottom: 5px;
    }

    .card p {
      color: #4a7ba6;
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
      background-color: #feadb9;
      color: white;
      padding: 14px 24px;
      border-radius: 8px;
      transition: background-color 0.3s;
    }

    .history-links a:hover {
      background-color: #bc85a3;
    }

    .image-gallery {
      display: flex;
      gap: 15px;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 40px;
    }

    .image-gallery img {
      width: 160px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
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
      <h2>1,240</h2>
      <p>Delivered Orders</p>
    </div>
    <div class="card">
      <h2>125</h2>
      <p>Returned Orders</p>
    </div>
    <div class="card">
      <h2>30</h2>
      <p>Cancelled Orders</p>
    </div>
  </div>

  <div class="history-links">
    <a href="arrival_history.php" target="_self">View Arrival History</a>
    <a href="category_tracking.php" target="_self">View by Category</a>
  </div>

  <div class="image-gallery">
    <!-- Sample images; replace with your own -->
    <img src="https://via.placeholder.com/160x100/feadb9/4a7ba6?text=Order+1" alt="Order snapshot">
    <img src="https://via.placeholder.com/160x100/feadb9/4a7ba6?text=Order+2" alt="Order snapshot">
    <img src="https://via.placeholder.com/160x100/feadb9/4a7ba6?text=Order+3" alt="Order snapshot">
  </div>

</body>
</html>
