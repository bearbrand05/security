<?php
include 'db.php';

// Fetch pending orders with supplier information
$sql = "SELECT o.*, s.name_supplier 
        FROM order_table o
        LEFT JOIN supplier_table s ON FIND_IN_SET(o.itemname_order, REPLACE(s.supply, ' ', ''))
        ORDER BY o.date_of_order DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pending Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
    }
    
    .container {
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      padding: 25px;
      margin-top: 20px;
    }
    
    h2 {
      color: #343a40;
      font-weight: 600;
      margin-bottom: 25px;
      padding-bottom: 10px;
      border-bottom: 2px solid #dee2e6;
    }
    
    .table {
      margin-bottom: 0;
    }
    
    .table thead th {
      background-color: #f1f3f5;
      color: #495057;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 13px;
      letter-spacing: 0.5px;
    }
    
    .table tbody tr:hover {
      background-color: #f8f9fa;
    }
    
    .btn-action {
      background-color: #0d6efd;
      color: white;
      padding: 5px 12px;
      border-radius: 4px;
      font-size: 13px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      transition: all 0.2s;
    }
    
    .btn-action:hover {
      background-color: #0b5ed7;
      color: white;
      transform: translateY(-1px);
    }
    
    .btn-action i {
      margin-right: 5px;
      font-size: 14px;
    }
    
    .no-orders {
      color: #6c757d;
      font-style: italic;
      padding: 20px 0;
    }
    
    .badge-id {
      background-color: #e9ecef;
      color: #495057;
      font-weight: 600;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
    }
    
    .supplier-badge {
      background-color: #e2f0fd;
      color: #0d6efd;
      padding: 3px 8px;
      border-radius: 4px;
      font-size: 12px;
      display: inline-block;
      margin-top: 3px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2><i class="bi bi-list-check"></i> Pending Orders</h2>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Destination</th>
            <th>Item</th>
            <th>Qty</th>
            <th>Order Date</th>
            <th>Supplier</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><span class="badge-id">#<?= $row['id_order'] ?></span></td>
                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                <td><?= htmlspecialchars($row['destination_order']) ?></td>
                <td><?= htmlspecialchars($row['itemname_order']) ?></td>
                <td><?= $row['quantity_order'] ?></td>
                <td><?= date("M d, Y", strtotime($row['date_of_order'])) ?></td>
                <td>
                  <?php if (!empty($row['name_supplier'])): ?>
                    <span class="supplier-badge"><?= htmlspecialchars($row['name_supplier']) ?></span>
                  <?php else: ?>
                    <span class="text-muted">No supplier</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($row['name_supplier'])): ?>
                    <a href="supplier_list.php?order_id=<?= $row['id_order'] ?>&item=<?= urlencode($row['itemname_order']) ?>" 
                       class="btn-action">
                      <i class="bi bi-arrow-right-circle"></i> Process
                    </a>
                  <?php else: ?>
                    <span class="text-muted">No supplier</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="text-center no-orders">
                <i class="bi bi-inbox" style="font-size: 24px;"></i><br>
                No pending orders found
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>