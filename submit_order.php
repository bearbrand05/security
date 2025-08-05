<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $item = $_POST['item'] ?? '';
  $destination = $_POST['destination'] ?? '';
  $date_of_order = $_POST['date_of_order'] ?? '';
  $quantity = $_POST['quantity'] ?? '';
  $selected_item = $_POST['selected_item'] ?? '';
  $supplier_name = $_POST['supplier_name'] ?? '';
  $order_id = intval($_POST['id_order'] ?? 0);

  if (empty($selected_item)) {
    die("Error: Please select an item.");
  }

  $arrive_date = date('Y-m-d', strtotime($date_of_order . ' +3 days'));

  // 1. Insert into order_receipt
  $stmt = $conn->prepare("INSERT INTO order_receipt (item_receipt, quantity_reciept, arrive_receipt, destination_receipt) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("siss", $selected_item, $quantity, $arrive_date, $destination);
  $stmt->execute();
  $receipt_id = $stmt->insert_id;
  $stmt->close();

  // 2. Delete from order_table (pending orders)
  if ($order_id > 0) {
    $del = $conn->prepare("DELETE FROM order_table WHERE id_order = ?");
    $del->bind_param("i", $order_id);
    $del->execute();
    $del->close();
  }

  // 3. Redirect to receipt page
  header("Location: receipt.php?id=" . $receipt_id);
  exit();
}
?>
