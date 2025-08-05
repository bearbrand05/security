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
$lowestStock = 0;
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
    .info-box {
      background-color: #e7f1ff;
      border-radius: 8px;
      padding: 1rem;
      text-align: center;
    }
    
    td div {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
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
    table th, table td {
        vertical-align: middle;
        }
    </style>
    </head>
    <body class="container py-4">

    <div class="row text-center mb-4">
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
    </div>
    <div class="col-md-4">
        <div class="info-box">
        <h6>Total items</h6>
        <h4>Warehouse 1 - 5<br><small class="text-muted"><?= number_format($totalStock) ?></small></h4>
        </div>
    </div>
    </div>

    <table class="table table-bordered table-hover text-center">
    <thead class="table-light">
        <tr>
        <th>ID #</th>
        <th>Warehouse</th>
        <th>Item name</th>
        <th>Stock</th>
        <th>Restock</th>



        </tr>
    </thead>
   <tbody>
  <?php if (empty($items)): ?>
    <tr>
      <td colspan="5" class="text-center text-muted">
        No items in inventory.
      </td>
  
    </tr>
  <?php else: ?>
    <?php foreach ($items as $item): ?>
      <tr id="row-<?= $item['id_inventory'] ?>">
        <td>#<?= $item['id_inventory'] ?></td>
        <td><?= htmlspecialchars($item['warehouse']) ?></td>
        <td><?= htmlspecialchars($item['itemname_invent']) ?></td>
        <td><?= $item['stock_invent'] ?></td>
        <td><?= $item['restock_invent'] ?></td>
     
    <?php endforeach; ?>
  <?php endif; ?>
</tbody>



</tbody>

</table>

<script>
  function deleteItem(id) {
    if (confirm("Delete this item?")) {
      fetch("delete_invent.php?id_inventory=" + id, { method: "GET" })
        .then(res => res.text())
        .then(response => {
          if (response === "success") {
            document.getElementById("row-" + id).remove();
          } else {
            alert("Failed to delete.");
          }
        });
    }
  }

  function addItem(id) {
    alert("You clicked Add for ID #" + id);
    // You can trigger a modal or redirect to an add-item form here
  }
  
</script>

</body>

</html>
