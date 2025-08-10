<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "inventory_db";
$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}
$query = "SELECT * FROM inventory_table ORDER BY id_inventory";
$result = $mysqli->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Restock History</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-bg: #C7C8CC;
      --secondary-bg: #E3E1D9;
      --accent-light: #F2EFE5;
      --accent-dark: #1C352D;
      --highlight-blue: #007bff;
    }
    
    body {
      background-color: var(--primary-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .navbar {
      background-color: var(--accent-dark) !important;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      color: white !important;
    }
    
    .page-header {
      background-color: white;
      border-radius: 10px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      text-align: center;
    }
    
    .table-container {
      background-color: white;
      border-radius: 10px;
      padding: 1.5rem;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .table thead {
      background-color: var(--primary-bg);
    }
    
    .table thead th {
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border: none;
    }
    
    .table tbody tr:nth-child(even) {
      background-color: var(--accent-light);
    }
    
    .table tbody tr:hover {
      background-color: var(--secondary-bg);
    }
    
    .table td {
      vertical-align: middle;
      border: none;
    }
    
    .btn-outline-secondary {
      border-color: #B4B4B8;
      color: #333;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }
    
    .btn-outline-secondary:hover {
      background-color: var(--accent-dark);
      color: white;
      border-color: var(--accent-dark);
    }
    
    .page-title {
      font-weight: 700;
      color: var(--accent-dark);
      margin: 0;
      position: relative;
      display: inline-block;
    }
    
    .page-title::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 50px;
      height: 3px;
      background-color: var(--highlight-blue);
    }
    
    .badge-restock {
      font-size: 0.85rem;
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
      background-color: #6c757d;
    }
    
    .footer {
      background-color: var(--accent-dark);
      color: white;
      padding: 1.5rem 0;
      margin-top: 2rem;
      text-align: center;
    }
  </style>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="bi bi-box-seam me-2"></i>APOLISTA WAREHOUSE
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="inventory.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="restock_history.php">Restock History</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
      <h1 class="page-title">RESTOCK HISTORY</h1>
      <p class="text-muted">Historical record of all inventory restocking activities</p>
    </div>
    
    <!-- Table Section -->
    <div class="table-container">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="inventory.php" class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
        <h5 class="m-0 text-center flex-grow-1">ORDER/RESTOCK HISTORY</h5>
        <div style="width: 120px;"></div> <!-- placeholder to balance space -->
      </div>
      
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Warehouse</th>
              <th>Categories</th>
              <th>Total Restock</th>
            </tr> 
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td style="background-color: #F9F6EE; font-weight: 600;">#<?= $row['id_inventory'] ?></td>
                  <td><?= htmlspecialchars($row['warehouse']) ?></td>
                  <td style="background-color: #FAF3E0;"><?= htmlspecialchars($row['itemname_invent']) ?></td>
                  <td style="background-color: #F5F5DC; font-weight: 600;">
                    <span class="badge badge-restock"><?= $row['restock_invent'] ?></span>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="4" class="text-muted text-center py-3">No restock records found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <p>&copy; <?= date('Y') ?> Apolista Warehouse Management System. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>