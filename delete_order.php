<?php
include 'db.php';

if (isset($_GET['id'])) {
  $id = intval($_GET['id']); // sanitize input

  $sql = "DELETE FROM order_table WHERE id_order = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    echo "✅ Order #$id deleted successfully.";
  } else {
    echo "❌ Error: " . $stmt->error;
  }

  $stmt->close();
} else {
  echo "❌ No order ID provided.";
}

$conn->close();
?>