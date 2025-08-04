<?php
// supplier_details.php
include 'db.php';

if (!isset($_GET['id'])) {
  die("Supplier ID is required.");
}

$id = intval($_GET['id']);
$supplierSql = "SELECT * FROM supplier_table WHERE id_supplier = $id";
$supplierResult = $conn->query($supplierSql);
$supplier = $supplierResult->fetch_assoc();

// Insert a dummy order if no matching order is found
$orderSql = "SELECT * FROM order_table WHERE itemname_order LIKE '%" . $supplier['supply'] . "%' ORDER BY date_of_order DESC LIMIT 1";
$orderResult = $conn->query($orderSql);

if ($orderResult->num_rows === 0) {
  // Insert a dummy order
  $dummyItem = $supplier['supply'];
  $destination = "BANGKOD";
  $quantity = rand(100, 1000);
  $date = date('Y-m-d');
  $customer = "Inventory";

  $stmt = $conn->prepare("INSERT INTO order_table (customer_name, destination_order, itemname_order, quantity_order, date_of_order) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssds", $customer, $destination, $dummyItem, $quantity, $date);
  $stmt->execute();
  $stmt->close();

  // Fetch the newly inserted order
  $orderSql = "SELECT * FROM order_table WHERE itemname_order LIKE '%" . $supplier['supply'] . "%' ORDER BY date_of_order DESC LIMIT 1";
  $orderResult = $conn->query($orderSql);
}

$order = $orderResult->fetch_assoc();
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
      <p><strong>Arrive Time:</strong> Within 1-3 Days</p>

      <a href="supplier_list.php" class="btn-back">BACK</a>
      <button type="submit" class="btn-submit">ADD ORDER</button>
    </form>
  <?php else: ?>
    <p>No pending order found for this supplier.</p>
    <a href="supplier_list.php" class="btn-back">BACK</a>
  <?php endif; ?>
</body>
</html>
