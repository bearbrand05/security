<?php
include 'db.php';

$sql = "SELECT * FROM order_table ORDER BY date_of_order DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pending Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
    }

    h2 {
      font-weight: bold;
      margin-bottom: 20px;
    }

    .table th {
      text-transform: uppercase;
      font-size: 14px;
    }

    .table td {
      font-size: 14px;
      vertical-align: middle;
    }

    .proceed-link {
      color: #007bff;
      text-decoration: underline;
      cursor: pointer;
    }

    .proceed-link:hover {
      color: #0056b3;
    }
  </style>
</head>
<body class="p-4">
  <h2>Pending Orders</h2>

  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="table-light">
        <tr>
          <th>Order ID</th>
          <th>Customer</th>
          <th>Destination</th>
          <th>Item name</th>
          <th>Quantity</th>
          <th>Date of order</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td>#<?= $row['id_order'] ?></td>
              <td><?= htmlspecialchars($row['customer_name']) ?></td>
              <td><strong><?= htmlspecialchars($row['destination_order']) ?></strong></td>
              <td><?= htmlspecialchars($row['itemname_order']) ?></td>
              <td><?= $row['quantity_order'] ?></td>
              <td><?= date("d/m/y", strtotime($row['date_of_order'])) ?></td>
              <td>
                <a href="supplier_list.php?order_id=<?= $row['id_order'] ?>" class="proceed-link">
                  Proceed to order
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center">No pending orders.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>