<?php
include 'db.php';

// Insert multiple dummy orders
$sql = "INSERT INTO order_table (customer_name, destination_order, itemname_order, quantity_order, date_of_order) VALUES
  ('Inventory', 'BANGKOD', 'Cotton', 2312, '2025-07-31'),
  ('Inventory', 'BANGKOD', 'Fishball', 367, '2025-07-29'),
  ('Inventory', 'BANGKOD', 'Kickiam', 1324, '2025-07-27'),
  ('Inventory', 'BANGKOD', 'Kerkare', 1255, '2025-07-24'),
  ('Inventory', 'BANGKOD', 'Tornboy', 10200, '2025-07-23')";

// to input data you need type to http://localhost/dashboard/code/insert_dummy_orders.php
if ($conn->query($sql) === TRUE) {
  echo "✅ Dummy data inserted successfully into order_table."; 
} else {
  echo "❌ Error inserting data: " . $conn->error;
}

$conn->close();
?>
