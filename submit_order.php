<?php
// submit_order.php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $item = $_POST['item'];
  $quantity = intval($_POST['quantity']);
  $destination = $_POST['destination'];
  $date_of_order = $_POST['date_of_order'];

  // Calculate arrival date (date + 3 days)
  $arrive_date = date('Y-m-d', strtotime($date_of_order . ' +3 days'));

  // Insert into order_receipt
  $stmt1 = $conn->prepare("INSERT INTO order_receipt (item_receipt, quantity_reciept, arrive_receipt, destination_receipt) VALUES (?, ?, ?, ?)");
  $stmt1->bind_param("siss", $item, $quantity, $arrive_date, $destination);
  $stmt1->execute();
  $receipt_id = $stmt1->insert_id;

  // Delete from pending order
  $delete = $conn->prepare("DELETE FROM order_table WHERE itemname_order = ? AND destination_order = ? AND date_of_order = ? LIMIT 1");
  $delete->bind_param("sss", $item, $destination, $date_of_order);
  $delete->execute();

  $stmt1->close();
  $stmt2->close();
  $delete->close();

  header("Location: receipt.php?id=$receipt_id");
  exit();
} else {
  echo "Invalid request.";
}
