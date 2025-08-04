<?php
include 'db.php';

if (!isset($_GET['id'])) {
  echo "Supplier ID missing.";
  exit;
}

$supplier_id = intval($_GET['id']);

// Get supplier info
$sql_supplier = "SELECT * FROM supplier_table WHERE id_supplier = $supplier_id";
$result_supplier = $conn->query($sql_supplier);

if ($result_supplier->num_rows == 0) {
  echo "Supplier not found.";
  exit;
}

$supplier = $result_supplier->fetch_assoc();

// Get supplier items
$sql_items = "SELECT * FROM supplier_items WHERE supplier_id = $supplier_id";
$result_items = $conn->query($sql_items);

// Get distinct destinations from orders
$sql_destinations = "SELECT DISTINCT destination_order FROM order_table";
$result_destinations = $conn->query($sql_destinations);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Supplier Details</title>
  <style>
    /* Your styling here or link your CSS */
  </style>
</head>
<body>
  <h2>Details</h2>
  <p><strong>Supplier name:</strong> <?= htmlspecialchars($supplier['name_supplier']) ?></p>
  <p><strong>Supply:</strong> <!-- You can add a supply description column to supplier_table or use static text --></p>
  <p><strong>Review:</strong> This person is overall good ...</p>

  <h2>Order</h2>
  <form method="post" action="submit_order.php">
    <?php 
    $index = 1;
    while ($item = $result_items->fetch_assoc()) : ?>
      <div style="display:inline-block; margin: 10px;">
        <!-- Replace blank box with image if you have -->
        <div style="width:100px; height:100px; border: 1px solid #ccc; background: #eee;">
          <img src="<?= htmlspecialchars($item['item_image']) ?>" alt="<?= htmlspecialchars($item['item_name']) ?>" style="max-width: 100%; max-height: 100%;">
        </div>
        <p>Item <?= $index ?>: <?= htmlspecialchars($item['item_name']) ?></p>
        <label><input type="checkbox" name="items[<?= $item['id_item'] ?>][selected]" value="1"> Select</label><br>
        Quantity: <input type="number" name="items[<?= $item['id_item'] ?>][quantity]" min="0" value="0" />
      </div>
    <?php 
      $index++;
    endwhile; ?>
    <br><br>

    <p><strong>Arrive Time:</strong> Within 1 - 3 Days</p>

    <p><strong>Destination:</strong>
      <select name="destination" required>
        <option value="">Select destination</option>
        <?php while ($dest = $result_destinations->fetch_assoc()) : ?>
          <option value="<?= htmlspecialchars($dest['destination_order']) ?>"><?= htmlspecialchars($dest['destination_order']) ?></option>
        <?php endwhile; ?>
      </select>
    </p>

    <input type="hidden" name="supplier_id" value="<?= $supplier_id ?>">
    <button type="button" onclick="history.back()">Back</button>
    <button type="submit">Add Order</button>
  </form>
</body>
</html>
