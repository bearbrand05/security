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
    .receipt-box {
      max-width: 600px;
      margin: 40px auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      background: #f9f9f9;
    }
    .btn-done {
      background-color: #4dabf7;
      color: white;
      padding: 8px 24px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
    }
    .btn-done:hover {
      background-color: #1c7ed6;
    }
  </style>
</head>
<body>
  <div class="receipt-box">
    <h3>Receipt</h3>
    <p><strong>Reference Number:</strong> #<?= $data['id_receipt'] ?></p>
    <p><strong>Item:</strong> <?= htmlspecialchars($data['item_receipt']) ?></p>
    <p><strong>Quantity:</strong> <?= $data['quantity_reciept'] ?></p>
    <p><strong>Arrive Time:</strong> <?= date("F d, Y", strtotime($data['arrive_receipt'])) ?></p>
    <p><strong>Destination:</strong> <?= htmlspecialchars($data['destination_receipt']) ?></p>

    <div class="text-end mt-4">
      <a href="supplier_list.php" class="btn-done">Done</a>
    </div>
  </div>
</body>
</html>