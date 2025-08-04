<?php
include 'db.php';

$sql = "SELECT * FROM supplier_table ORDER BY id_supplier ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Supplier List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
    }

    h2 {
      font-weight: bold;
      margin-bottom: 20px;
    }

    .container-box {
      border: 2px black;
      padding: 20px;
      border-radius: 10px;
    }

    .table th {
      text-transform: uppercase;
      font-size: 14px;
    }

    .table td {
      font-size: 14px;
      vertical-align: middle;
    }

    .btn-back {
      background-color: #888;
      color: white;
      border: none;
      padding: 6px 20px;
      border-radius: 5px;
      text-decoration: none;
    }

    .btn-back:hover {
      background-color: #555;
      color: white;
    }

    .view-link {
      color: #007bff;
      text-decoration: underline;
      cursor: pointer;
    }

    .view-link:hover {
      color: #0056b3;
    }
  </style>
</head>
<body class="p-4">
  <h2>Suppliers</h2>

  <div class="container-box">
    <table class="table table-hover">
      <thead class="table-light">
        <tr>
          <th>ID #</th>
          <th>Supplier</th>
          <th>Ratings</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td>#<?= $row['id_supplier'] ?></td>
              <td><?= htmlspecialchars($row['name_supplier']) ?></td>
              <td><?= is_null($row['ratings']) ? 'N/A' : number_format($row['ratings'], 1) . ' / 5.0' ?></td>
              <td><a href="supplier_details.php?id=<?= $row['id_supplier'] ?>" class="view-link">view details</a></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="4" class="text-center">No suppliers found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div class="text-end">
      <a href="supply.php" class="btn-back">back</a>
    </div>
  </div>
</body>
</html>
