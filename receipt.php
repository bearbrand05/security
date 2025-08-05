<?php
// receipt.php
include 'db.php';

if (!isset($_GET['id'])) {
  die("Missing receipt ID.");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM order_receipt WHERE id_receipt = $id";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Receipt</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f4f4;
      font-family: 'Segoe UI', sans-serif;
    }
    .receipt-box {
      max-width: 500px;
      margin: 50px auto;
      padding: 30px;
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .receipt-box h3 {
      font-weight: bold;
      margin-bottom: 20px;
      color: #333;
    }
    .receipt-box p {
      font-size: 16px;
      margin: 5px 0;
      color: #444;
    }
    .btn-done {
      background-color: #4dabf7;
      color: white;
      padding: 8px 20px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
    }
    .btn-done:hover {
      background-color: #339af0;
    }
  </style>
</head>
<body>
  <div class="receipt-box">
    <h3>Receipt</h3>
    <?php if ($data): ?>
      <p><strong>Ref #:</strong> #<?= $data['id_receipt'] ?></p>
      <p><strong>Item:</strong> <?= htmlspecialchars($data['item_receipt']) ?></p>
      <p><strong>Quantity:</strong> <?= $data['quantity_reciept'] ?></p>
      <p><strong>Arrive:</strong> <?= htmlspecialchars($data['arrive_receipt']) ?></p>
      <p><strong>Destination:</strong> <?= htmlspecialchars($data['destination_receipt']) ?></p>
      <div class="text-end mt-4">
        <a href="supplier_list.php" class="btn-done">Done</a>
      </div>
    <?php else: ?>
      <p class="text-danger">Order not found.</p>
      <div class="text-end mt-4">
        <a href="supplier_list.php" class="btn-done">Back</a>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
