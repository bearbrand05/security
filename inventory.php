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
  <style>
    body {
      background-color: #C7C8CC;
    }
    .info-box {
      background-color: #E3E1D9;
      border-radius: 8px;
      padding: 1rem;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .info-box h6 {
      margin: 0;
      font-weight: 400;
      font-size: 0.95rem;
    }

    .info-box h4 {
      margin: 0;
      font-weight: 700;
    }

    .table thead {
      background-color: #C7C8CC;
    }

    .table tbody tr:nth-child(even) {
      background-color: #F2EFE5;
    }

    .table tbody tr:hover {
      background-color: #E3E1D9;
    }

    .btn-outline-secondary {
      border-color: #B4B4B8;
      color: #333;
    }

    .btn-outline-secondary:hover {
      background
      -color: #C7C8CC;
    }
    
  </style>
</head>
<body class="container py-4">

  <div class="row text-center mb-2">
    <div class="col-md-4">
      <div class="info-box">
        <h6>Highest demand</h6>
        <h4><?= $warehouseHighDemand ?><br><small class="text-muted"><?= number_format($highestRestock) ?></small></h4>
      </div>
    </div>

    <div class="col-md-4">
      <div class="info-box">
        <h6>Low in stock</h6>
        <h4><?= $warehouseLowStock ?><br><small class="text-muted"><?= number_format($lowestStock) ?></small></h4>
      </div>
      <div class="text-center mt-2">
        <a href="restock_history.php" class="btn btn-outline-secondary btn-sm">View Restock History</a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="info-box">
        <h6>Total items</h6>
        <h4>Warehouse 1 - 5<br><small class="text-muted"><?= number_format($totalStock) ?></small></h4>
      </div>
    </div>
  </div>

  <table class="table table-bordered table-hover text-center">
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
        <tr><td colspan="4" class="text-muted">No items in inventory.</td></tr>
      <?php else: ?>
        <?php foreach ($items as $item): ?>
          <tr>
            <td>#<?= $item['id_inventory'] ?></td>
            <td><?= htmlspecialchars($item['warehouse']) ?></td>
            <td><?= htmlspecialchars($item['itemname_invent']) ?></td>
            <td><?= $item['stock_invent'] ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>
