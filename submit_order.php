<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get all form data
    $selected_item = $_POST['selected_item'] ?? '';
    $quantity = intval($_POST['quantity'] ?? 0);
    $warehouse = $_POST['warehouse'] ?? 'Warehouse 1';
    $destination = $_POST['destination'] ?? '';
    $date_of_order = $_POST['date_of_order'] ?? date('Y-m-d');
    $order_id = intval($_POST['id_order'] ?? 0);

    // Validate required fields
    if (empty($selected_item) || $quantity <= 0) {
        die("Error: Please select an item and specify a valid quantity.");
    }

    // Calculate arrival date (3 days from order date)
    $arrive_date = date('Y-m-d', strtotime($date_of_order . ' +3 days'));

    // 1. Track the quantity in warehouse_quantities table
    $track_sql = "INSERT INTO warehouse_quantities (warehouse, item_name, total_quantity) 
                 VALUES (?, ?, ?) 
                 ON DUPLICATE KEY UPDATE 
                 total_quantity = total_quantity + ?";
    $track_stmt = $conn->prepare($track_sql);
    $track_stmt->bind_param("ssii", $warehouse, $selected_item, $quantity, $quantity);
    $track_stmt->execute();
    $track_stmt->close();

    // 2. Process the order in order_receipt table
    $order_sql = "INSERT INTO order_receipt (item_receipt, quantity_reciept, arrive_receipt, destination_receipt, warehouse) 
                 VALUES (?, ?, ?, ?, ?)";
    $order_stmt = $conn->prepare($order_sql);
    
    // Bind parameters separately to avoid reference issues
    $order_stmt->bind_param("sisss", $selected_item, $quantity, $arrive_date, $destination, $warehouse);
    $order_stmt->execute();
    $receipt_id = $order_stmt->insert_id;
    $order_stmt->close();

    // 3. Remove from pending orders if exists
    if ($order_id > 0) {
        $del_sql = "DELETE FROM order_table WHERE id_order = ?";
        $del_stmt = $conn->prepare($del_sql);
        $del_stmt->bind_param("i", $order_id);
        $del_stmt->execute();
        $del_stmt->close();
    }

    header("Location: receipt.php?id=" . $receipt_id);
    exit();
}
?>