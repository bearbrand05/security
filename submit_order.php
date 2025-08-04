<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $item = $_POST['item'];
  $quantity = intval($_POST['quantity']);
  $destination = $_POST['destination'];
  $date_of_order = $_POST['date_of_order'];

  // Get current pending quantity
  $check = $conn->prepare("SELECT id_order, quantity_order FROM order_table WHERE itemname_order = ? AND destination_order = ? AND date_of_order = ? LIMIT 1");
  $check->bind_param("sss", $item, $destination, $date_of_order);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    $order_id = $order['id_order'];
    $current_quantity = $order['quantity_order'];

    if ($quantity >= $current_quantity) {
      // Quantity fulfilled or exceeded — delete the order
      $delete = $conn->prepare("DELETE FROM order_table WHERE id_order = ?");
      $delete->bind_param("i", $order_id);
      $delete->execute();
      $delete->close();
    } else {
      // Partial fulfillment — update the quantity
      $new_quantity = $current_quantity - $quantity;
      $update = $conn->prepare("UPDATE order_table SET quantity_order = ? WHERE id_order = ?");
      $update->bind_param("ii", $new_quantity, $order_id);
      $update->execute();
      $update->close();
    }

    // Record in order_receipt
    $arrive_date = date('Y-m-d', strtotime($date_of_order . ' +3 days'));
    $insert = $conn->prepare("INSERT INTO order_receipt (item_receipt, quantity_reciept, arrive_receipt, destination_receipt) VALUES (?, ?, ?, ?)");
    $insert->bind_param("siss", $item, $quantity, $arrive_date, $destination);
    $insert->execute();
    $receipt_id = $insert->insert_id;
    $insert->close();

    // ✅ Redirect to receipt
    header("Location: receipt.php?id=$receipt_id");
    exit();

  } else {
    echo "Order not found.";
  }

  $check->close();
} else {
  echo "Invalid request.";
}
