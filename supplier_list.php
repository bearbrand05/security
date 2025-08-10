<?php
include 'db.php';

// Fetch suppliers
$sql = "SELECT * FROM supplier_table ORDER BY id_supplier ASC";
$result = $conn->query($sql);

// Fetch pending orders
$orderQuery = "SELECT * FROM order_table ORDER BY date_of_order DESC";
$orderResult = $conn->query($orderQuery);
$orders = [];
while ($orderRow = $orderResult->fetch_assoc()) {
  $orders[] = $orderRow;
}

// Define category items
$categoryItems = [
  'Electronics' => ['Desktop', 'Laptop', 'Monitor', 'Phone'],
  'Peripherals' => ['Keyboard', 'Mouse', 'Headset'],
  'Furniture' => ['Chair', 'Desk', 'Cabinet'],
  'Stationery' => ['Pen', 'Notebook', 'Stapler'],
  'Cleaning Supplies' => ['Mop', 'Detergent', 'Brush']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Supplier List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body { font-family: 'Segoe UI', sans-serif; }
    h2 { font-weight: bold; margin-bottom: 20px; }
    .btn-back {
      background-color: #888; color: white; border: none;
      padding: 6px 20px; border-radius: 5px; text-decoration: none;
    }
    .btn-back:hover { background-color: #555; }
    .view-link {
      color: #007bff; text-decoration: underline; cursor: pointer;
    }
    .view-link:hover { color: #0056b3; }
    .form-section {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body class="p-4">
  <h2>Suppliers</h2>

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
      <?php foreach ($result as $supplier): ?>
        <?php
        // Find matching pending order using ANY item in supply
        $matchingOrder = null;
        $suppliedItems = array_map('trim', explode(',', $supplier['supply']));
        foreach ($orders as $order) {
          if (in_array($order['itemname_order'], $suppliedItems)) {
            $matchingOrder = $order;
            break;
          }
        }
        ?>
        <tr>
          <td>#<?= $supplier['id_supplier'] ?></td>
          <td><?= htmlspecialchars($supplier['name_supplier']) ?></td>
          <td><?= is_null($supplier['ratings']) ? 'N/A' : number_format($supplier['ratings'], 1) . ' / 5.0' ?></td>
          <td>
            <?php if ($matchingOrder): ?>
              <button class="view-link" data-bs-toggle="modal" data-bs-target="#supplierModal<?= $supplier['id_supplier'] ?>">view details</button>
            <?php else: ?>
              <span class="text-muted">No order</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="text-end">
    <a href="supply.php" class="btn-back">Back</a>
  </div>

  <!-- Modals for each supplier -->
  <?php foreach ($result as $supplier): ?>
    <?php
    $matchingOrder = null;
    $suppliedItems = array_map('trim', explode(',', $supplier['supply']));
    foreach ($orders as $order) {
      if (in_array($order['itemname_order'], $suppliedItems)) {
        $matchingOrder = $order;
        break;
      }
    }

    if (!$matchingOrder) continue;

    $category = $supplier['category'];
    $items = $categoryItems[$category] ?? [];

    $itemname_order = $matchingOrder['itemname_order'];
    $destination_order = $matchingOrder['destination_order'];
    $date_of_order = $matchingOrder['date_of_order'];
    $quantity_order = $matchingOrder['quantity_order'];
    $order_id = $matchingOrder['id_order'];
    ?>
    <div class="modal fade" id="supplierModal<?= $supplier['id_supplier'] ?>" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content p-3">
          <h4>Supplier Details</h4>
          <div class="form-section">
            <p><strong>Name:</strong> <?= htmlspecialchars($supplier['contact_name']) ?></p>
            <p><strong>Supply:</strong> <?= htmlspecialchars($supplier['supply']) ?></p>
            <p><strong>Company:</strong> <?= htmlspecialchars($supplier['name_supplier']) ?></p>
            <p><strong>Review:</strong> <?= htmlspecialchars($supplier['review']) ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($category) ?></p>
          </div>

          <form action="submit_order.php" method="POST">
            <input type="hidden" name="item" value="<?= htmlspecialchars($itemname_order) ?>">
            <input type="hidden" name="destination" value="<?= htmlspecialchars($destination_order) ?>">
            <input type="hidden" name="date_of_order" value="<?= $date_of_order ?>">
            <input type="hidden" name="quantity" value="<?= $quantity_order ?>">
            <input type="hidden" name="supplier_name" value="<?= htmlspecialchars($supplier['name_supplier']) ?>">
            <input type="hidden" name="id_order" value="<?= $order_id ?>">

            <div class="form-section">
              <p><strong>Quantity:</strong> <?= $quantity_order ?></p>
              <p><strong>Destination:</strong> <?= htmlspecialchars($destination_order) ?></p>
              
              <div class="mb-3">
                <label class="form-label"><strong>Warehouse:</strong></label>
                <select name="warehouse" class="form-select" required>
                  <option value="">Select Warehouse</option>
                  <option value="Warehouse 1">Warehouse 1</option>
                  <option value="Warehouse 2">Warehouse 2</option>
                  <option value="Warehouse 3">Warehouse 3</option>
                  <option value="Warehouse 4">Warehouse 4</option>
                  <option value="Warehouse 5">Warehouse 5</option>
                </select>
              </div>
            </div>

            <div class="form-section">
              <label class="form-label"><strong>ITEM:</strong></label>
              <select name="selected_item" class="form-select" required>
                <option value="" disabled selected>Choose Item</option>
                <?php foreach ($items as $i): ?>
                  <option value="<?= $i ?>"><?= $i ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="text-end mt-3">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Submit Order</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</body>
</html>