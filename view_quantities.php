<?php
// submit_order.php
include 'db.php';

// 1. Get and sanitize form data
$warehouse = isset($_POST['warehouse']) ? trim($_POST['warehouse']) : '';
$itemname = isset($_POST['selected_item']) ? trim($_POST['selected_item']) : '';
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
$date_of_order = isset($_POST['date_of_order']) ? $_POST['date_of_order'] : date('Y-m-d H:i:s');
$destination = isset($_POST['destination']) ? trim($_POST['destination']) : '';

// 2. Validate required fields
if (empty($warehouse) || empty($itemname) || $quantity <= 0) {
    header("Location: inventory.php?error=" . urlencode("Missing required fields"));
    exit();
}

// 3. Determine the category based on item name
$category = 'General'; // Default category
$item_lower = strtolower($itemname);

if (in_array($item_lower, ['desktop', 'laptop', 'monitor', 'phone'])) {
    $category = 'Electronics';
} elseif (in_array($item_lower, ['keyboard', 'mouse', 'headset'])) {
    $category = 'Peripherals';
} elseif (in_array($item_lower, ['chair', 'desk', 'cabinet'])) {
    $category = 'Furniture';
} elseif (in_array($item_lower, ['pen', 'notebook', 'stapler'])) {
    $category = 'Stationery';
} elseif (in_array($item_lower, ['mop', 'detergent', 'brush'])) {
    $category = 'Cleaning Supplies';
}

// 4. Prepare and execute the order submission
try {
    // Insert into order_table
    $order_stmt = $conn->prepare("INSERT INTO order_table 
                                (itemname_order, destination_order, date_of_order, quantity_order, name_supplier) 
                                VALUES (?, ?, ?, ?, ?)");
    
    $supplier = "Supplier"; // Default or get from form if available
    $order_stmt->bind_param('sssis', $itemname, $destination, $date_of_order, $quantity, $supplier);
    
    if (!$order_stmt->execute()) {
        throw new Exception("Order submission failed: " . $order_stmt->error);
    }
    
    $order_id = $conn->insert_id;
    
    // Update inventory_table
    $inventory_stmt = $conn->prepare("INSERT INTO inventory_table 
                                    (warehouse, itemname_invent, stock_invent, restock_invent, categories) 
                                    VALUES (?, ?, ?, ?, ?)
                                    ON DUPLICATE KEY UPDATE 
                                    stock_invent = stock_invent + VALUES(stock_invent),
                                    restock_invent = restock_invent + VALUES(restock_invent)");
    
    $inventory_stmt->bind_param('ssiis', $warehouse, $itemname, $quantity, $quantity, $category);
    
    if (!$inventory_stmt->execute()) {
        throw new Exception("Inventory update failed: " . $inventory_stmt->error);
    }
    
    // Success - redirect with success message
    header("Location: inventory.php?success=1&order_id=$order_id");
    exit();
    
} catch (Exception $e) {
    // Error - redirect with error message
    header("Location: inventory.php?error=" . urlencode($e->getMessage()));
    exit();
} finally {
    // Close statements if they exist
    if (isset($order_stmt)) $order_stmt->close();
    if (isset($inventory_stmt)) $inventory_stmt->close();
    $conn->close();
}
?>