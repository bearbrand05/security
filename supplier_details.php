<?php
// supplier_details.php
include 'db.php';

if (!isset($_GET['id'])) {
  die("Supplier ID is required.");
}

$id = intval($_GET['id']);

// Fetch supplier
$supplierSql = "SELECT * FROM supplier_table WHERE id_supplier = ?";
$supplierStmt = $conn->prepare($supplierSql);
$supplierStmt->bind_param("i", $id);
$supplierStmt->execute();
$supplierResult = $supplierStmt->get_result();
$supplier = $supplierResult->fetch_assoc();
$supplierStmt->close();

$supply = $supplier['supply'];

// Fetch only existing pending orders that match this supplier's supply
$orderSql = "SELECT * FROM order_table WHERE itemname_order = ? ORDER BY date_of_order DESC LIMIT 1";
$stmt = $conn->prepare($orderSql);
$stmt->bind_param("s", $supply);
$stmt->execute();
$orderResult = $stmt->get_result();
$order = $orderResult->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Supplier Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .label { font-weight: bold; }
    .btn-back, .btn-submit {
      border: none; padding: 6px 20px; border-radius: 5px;
    }
    .btn-back { background: #888; color: white; }
    .btn-submit { background: #4dabf7; color: white; }
  </style>
</head>
<body class="p-4">
  <h3>Details</h3>
  <p><span class="label">Supplier name:</span> <?= htmlspecialchars($supplier['contact_name']) ?></p>
  <p><span class="label">Supply:</span> <?= htmlspecialchars($supplier['supply']) ?></p>
  <p><span class="label">Company Name:</span> <?= htmlspecialchars($supplier['name_supplier']) ?></p>
  <p><span class="label">Review:</span> <?= htmlspecialchars($supplier['review']) ?></p>

  <?php if ($order): ?>
    <h4 class="mt-4">Order</h4>
    <form action="submit_order.php" method="POST">
      <input type="hidden" name="item" value="<?= htmlspecialchars($order['itemname_order']) ?>">
      <input type="hidden" name="destination" value="<?= htmlspecialchars($order['destination_order']) ?>">
      <input type="hidden" name="date_of_order" value="<?= $order['date_of_order'] ?>">

      <p><strong>ITEM 1:</strong> <?= htmlspecialchars($order['itemname_order']) ?></p>
      <p>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required class="form-control w-25">
      </p>
      <p><strong>Destination:</strong> <?= htmlspecialchars($order['destination_order']) ?></p>
      <p><strong>Arrive Time:</strong> Within 1–3 Days</p>

      <a href="supplier_list.php" class="btn-back">BACK</a>
      <button type="submit" class="btn-submit" name="submit">ADD ORDER</button>
    </form>
  <?php else: ?>
    <p>No pending order found for this supplier.</p>
    <a href="supplier_list.php" class="btn-back">BACK</a>
  <?php endif; ?>
</body>
</html>
