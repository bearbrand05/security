<?php
// add_pending_order.php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $customer = $_POST['customer'];
  $destination = $_POST['destination'];
  $item = $_POST['item'];
  $quantity = intval($_POST['quantity']);
  $date = $_POST['date'];

  $stmt = $conn->prepare("INSERT INTO order_table (customer_name, destination_order, itemname_order, quantity_order, date_of_order) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssds", $customer, $destination, $item, $quantity, $date);
  $stmt->execute();
  $stmt->close();

  // Redirect to pending orders page
  header("Location: supply.php"); // or 'pending_orders.php' depending on your setup
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Pending Order</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .container { max-width: 600px; margin-top: 50px; }
    .form-label { font-weight: bold; }
    .btn-submit {
      background-color: #4dabf7;
      color: white;
      border: none;
      padding: 8px 24px;
      border-radius: 5px;
    }
    .btn-submit:hover {
      background-color: #1a8cff;
    }
  </style>
</head>
<body>
  <div class="container">
    <h3>Add Pending Order</h3>
    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label">Customer Name</label>
        <input type="text" name="customer" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Destination</label>
        <input type="text" name="destination" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Item Name</label>
        <input type="text" name="item" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Quantity</label>
        <input type="number" name="quantity" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Date of Order</label>
        <input type="date" name="date" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-submit">Done</button>
    </form>
  </div>
</body>
</html>