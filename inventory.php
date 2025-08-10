<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "inventory_db";
$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}
$query = "SELECT * FROM inventory_table";
$result = $mysqli->query($query);
$items = [];
$totalStock = 0;
$highestRestock = 0;
$lowestStock = PHP_INT_MAX;
$warehouseHighDemand = '';
$warehouseLowStock = '';
while ($row = $result->fetch_assoc()) {
  $items[] = $row;
  $totalStock += $row['stock_invent'];
  if ($row['restock_invent'] > $highestRestock) {
    $highestRestock = $row['restock_invent'];
    $warehouseHighDemand = $row['warehouse'];
  }
  if ($row['stock_invent'] < $lowestStock) {
    $lowestStock = $row['stock_invent'];
    $warehouseLowStock = $row['warehouse'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Warehouse Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-bg: #C7C8CC;
      --secondary-bg: #E3E1D9;
      --accent-light: #F2EFE5;
      --accent-dark: #1C352D;
      --highlight-green: #28a745;
      --highlight-red: #dc3545;
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
    
    .dashboard-header {
      background-color: white;
      border-radius: 10px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .info-box {
      background-color: var(--secondary-bg);
      border-radius: 10px;
      padding: 1.5rem;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 100%;
    }
    
    .info-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    
    .info-box h6 {
      margin: 0;
      font-weight: 600;
      font-size: 0.9rem;
      color: #495057;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .info-box h4 {
      margin: 0.5rem 0 0;
      font-weight: 700;
    }
    
    .info-box i {
      font-size: 2rem;
      margin-bottom: 0.5rem;
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
    
    .badge-stock {
      font-size: 0.85rem;
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
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
            <a class="nav-link active" href="inventory.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="restock_history.php">Restock History</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-4">
    <!-- Dashboard Header -->
    <div class="dashboard-header text-center">
      <h1 class="page-title">WAREHOUSE DASHBOARD</h1>
      <p class="text-muted">Real-time inventory overview across all locations</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-md-4 mb-3">
        <div class="info-box">
          <i class="bi bi-graph-up-arrow text-success"></i>
          <h6>HIGHEST DEMAND</h6>
          <h4><?= $warehouseHighDemand ?><br><span class="badge bg-success badge-stock"><?= number_format($highestRestock) ?></span></h4>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="info-box">
          <i class="bi bi-exclamation-triangle text-danger"></i>
          <h6>LOWEST STOCK</h6>
          <h4><?= $warehouseLowStock ?><br><span class="badge bg-danger badge-stock"><?= number_format($lowestStock) ?></span></h4>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="info-box">
          <i class="bi bi-boxes text-primary"></i>
          <h6>TOTAL ITEMS</h6>
          <h4>Warehouse 1 - 5<br><span class="badge bg-primary badge-stock"><?= number_format($totalStock) ?></span></h4>
        </div>
      </div>
    </div>
    
    <!-- Table Section -->
    <div class="table-container">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="restock_history.php" class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-clock-history me-1"></i> View Restock History
        </a>
        <h5 class="m-0 text-center flex-grow-1">APOLISTA STOCK INVENTORY</h5>
        <div style="width: 120px;"></div> <!-- placeholder to balance space -->
      </div>
      
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>ID #</th>
              <th>Warehouse</th>
              <th>Categories</th>
              <th>Total Stock</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($items)): ?>
              <tr><td colspan="4" class="text-muted text-center py-3">No items in inventory.</td></tr>
            <?php else: ?>
              <?php foreach ($items as $item): ?>
                <tr>
                  <td style="background-color: #F9F6EE; font-weight: 600;">#<?= $item['id_inventory'] ?></td>
                  <td><?= htmlspecialchars($item['warehouse']) ?></td>
                  <td style="background-color: #FAF3E0;"><?= htmlspecialchars($item['itemname_invent']) ?></td>
                  <td style="background-color: #F5F5DC; font-weight: 600;">
                    <span class="badge bg-secondary badge-stock"><?= $item['stock_invent'] ?></span>
                  </td>
                </tr>
              <?php endforeach; ?>
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