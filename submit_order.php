<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Collect form data
  $item = $_POST['item'] ?? '';
  $destination = $_POST['destination'] ?? '';
  $date_of_order = $_POST['date_of_order'] ?? '';
  $quantity = $_POST['quantity'] ?? '';
  $selected_item = $_POST['selected_item'] ?? '';
  $order_id = intval($_POST['order_id'] ?? 0);

  if (empty($selected_item)) {
    die("Error: Please select an item.");
  }

  // Calculate arrive date = +3 days
  $arrive_date = date('Y-m-d', strtotime($date_of_order . ' +3 days'));

  // 1. Insert into order_receipt
  $insert = $conn->prepare("INSERT INTO order_receipt (item_receipt, quantity_reciept, arrive_receipt, destination_receipt) VALUES (?, ?, ?, ?)");
  $insert->bind_param("siss", $selected_item, $quantity, $arrive_date, $destination);
  $insert->execute();
  $receipt_id = $conn->insert_id;
  $insert->close();

  // 2. Delete from order_table based on the original pending order ID
  if ($order_id > 0) {
    $delete = $conn->prepare("DELETE FROM order_table WHERE id_order = ?");
    $delete->bind_param("i", $order_id);
    $delete->execute();
    $delete->close();
  }

  // 3. Redirect to receipt
  header("Location: receipt.php?id=" . $receipt_id);
  exit();
} else {
  echo "Invalid request.";
}
?>
