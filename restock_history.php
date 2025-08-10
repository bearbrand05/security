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
  <style>
    body {
      background-color: #C7C8CC;
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
    .btn-secondary {
      background-color: #C7C8CC;
      border: none;
      color: #333;
    }
    .btn-secondary:hover {
      background-color: #B4B4B8;
      color: white;
    }
  </style>
</head>
<body class="container py-4">

  <!-- View Button + Title -->
  <div class="d-flex justify-content-between align-items-center mb-2">
     <a href="inventory.php" class="btn btn-outline-secondary btn-sm" style="font-weight: bold;">View Order/Restock History</a>
  <h5 class="m-0 text-center flex-grow-1" style="fornt-weight: bold; color: #1C352D;">  Order/Restock History</h5>
    <div style="width: 120px;"></div>
  </div>

  <table class="table table-bordered text-center">
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
            <td style="background-color: #F9F6EE;">#<?= $row['id_inventory'] ?></td>
            <td><?= htmlspecialchars($row['warehouse']) ?></td>
            <td style="background-color: #FAF3E0;"><?= htmlspecialchars($row['itemname_invent']) ?></td>
            <td style="background-color: #F5F5DC;"><?= $row['restock_invent'] ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="4" class="text-muted">No restock records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> 303baf17d177401e3c3fcbc4d3a65964f486a7c3
