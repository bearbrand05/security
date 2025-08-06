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
    h3 {
      color: #333;
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

  <h3 class="mb-4">Restock History</h3>
  <a href="inventory.php" class="btn btn-secondary btn-sm mb-3">&larr; Back</a>

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
            <td>#<?= $row['id_inventory'] ?></td>
            <td><?= htmlspecialchars($row['warehouse']) ?></td>
            <td><?= htmlspecialchars($row['itemname_invent']) ?></td>
            <td><?= $row['restock_invent'] ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="4" class="text-muted">No restock records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>
