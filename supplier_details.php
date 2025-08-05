<?php
// supplier_details.php (updated to always show form)
include 'db.php';

if (!isset($_GET['id'])) {
  die("Supplier ID is required.");
}

$id = intval($_GET['id']);

// Fetch supplier
override:
$supplierSql = "SELECT * FROM supplier_table WHERE id_supplier = ?";
$supplierStmt = $conn->prepare($supplierSql);
$supplierStmt->bind_param("i", $id);
$supplierStmt->execute();
$supplierResult = $supplierStmt->get_result();
$supplier = $supplierResult->fetch_assoc();
$supplierStmt->close();

$supply = $supplier['supply'];

// Fetch matching order (if exists)
$orderSql = "SELECT * FROM order_table WHERE itemname_order = ? ORDER BY date_of_order DESC LIMIT 1";
$stmt = $conn->prepare($orderSql);
$stmt->bind_param("s", $supply);
$stmt->execute();
$orderResult = $stmt->get_result();
$order = $orderResult->fetch_assoc();
$stmt->close();

// Default values if no exact order found
$item = $order['itemname_order'] ?? $supply;
$quantity = $order['quantity_order'] ?? 100;
$destination = $order['destination_order'] ?? "Default Warehouse";
$date_of_order = $order['date_of_order'] ?? date('Y-m-d');

// Set supplier categories
$categories = [
  'Supply Name 1' => ['Desktop', 'Phone', 'Keyboard', 'Mouse'],
  'Supply Name 2' => ['Printer', 'Monitor', 'Scanner'],
  'Supply Name 3' => ['Webcam', 'Tripod'],
  'Supply Name 4' => ['Network Switch', 'Router'],
  'Supply Name 5' => ['Projector', 'Speaker']
];
$cat = $supplier['supply'];
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

  <h4 class="mt-4">Order</h4>
  <form action="submit_order.php" method="POST">
    <input type="hidden" name="item" value="<?= htmlspecialchars($item) ?>">
    <input type="hidden" name="destination" value="<?= htmlspecialchars($destination) ?>">
    <input type="hidden" name="date_of_order" value="<?= $date_of_order ?>">

    <p><strong>CATEGORY:</strong> <?= htmlspecialchars($cat) ?></p>
    <p>
      <label for="category_item">Select Item:</label>
      <select name="category_item" id="category_item" class="form-control w-50" required>
        <option value="">-- Choose item --</option>
        <?php foreach ($categories[$cat] ?? [] as $opt): ?>
          <option value="<?= $opt ?>"><?= $opt ?></option>
        <?php endforeach; ?>
      </select>
    </p>
    <p><strong>Fixed Quantity:</strong> <?= $quantity ?></p>
    <input type="hidden" name="quantity" value="<?= $quantity ?>">
    <p><strong>Destination:</strong> <?= htmlspecialchars($destination) ?></p>
    <p><strong>Arrive Time:</strong> Within 1–3 Days</p>

    <a href="supplier_list.php" class="btn-back">BACK</a>
    <button type="submit" class="btn-submit" name="submit">ADD ORDER</button>
  </form>
</body>
</html>
