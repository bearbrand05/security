<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate and sanitize input data
        $selected_item = trim($_POST['selected_item'] ?? '');
        $quantity = filter_var($_POST['quantity'] ?? 0, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1]
        ]);
        $warehouse = trim($_POST['warehouse'] ?? 'Warehouse 1');
        $destination = trim($_POST['destination'] ?? '');
        $date_of_order = $_POST['date_of_order'] ?? date('Y-m-d');
        $order_id = filter_var($_POST['id_order'] ?? 0, FILTER_VALIDATE_INT);
        $supplier_name = trim($_POST['name_supplier'] ?? '');

        // Validate required fields
        if (empty($selected_item)) {
            throw new Exception("Please select an item.");
        }
        
        if ($quantity === false) {
            throw new Exception("Invalid quantity. Please enter a positive number.");
        }
        
        if (empty($destination)) {
            throw new Exception("Destination is required.");
        }
        
        if (!strtotime($date_of_order)) {
            throw new Exception("Invalid order date.");
        }

        // Determine category based on item name
        $category_map = [
            'Electronics' => ['Desktop', 'Laptop', 'Monitor', 'Phone'],
            'Peripherals' => ['Keyboard', 'Mouse', 'Headset'],
            'Furniture' => ['Chair', 'Desk', 'Cabinet'],
            'Stationery' => ['Pen', 'Notebook', 'Stapler'],
            'Cleaning Supplies' => ['Mop', 'Detergent', 'Brush']
        ];

        $category = 'General';
        foreach ($category_map as $cat => $items) {
            if (in_array($selected_item, $items)) {
                $category = $cat;
                break;
            }
        }

        // Calculate dates
        $arrive_date = date('Y-m-d', strtotime($date_of_order . ' +3 days'));
        $pickup_date = date('Y-m-d', strtotime($date_of_order . ' +1 day')); // Assuming pickup 1 day after order
        $delivery_date = $arrive_date;
        $status = 'Processing'; // Initial status

        // Start transaction
        $conn->begin_transaction();

        try {
            // 1. Update inventory table
            $inventory_sql = "INSERT INTO inventory_table 
                            (warehouse, itemname_invent, stock_invent, restock_invent, categories) 
                            VALUES (?, ?, ?, ?, ?) 
                            ON DUPLICATE KEY UPDATE 
                            stock_invent = stock_invent + VALUES(stock_invent),
                            restock_invent = restock_invent + VALUES(restock_invent)";
            
            $inventory_stmt = $conn->prepare($inventory_sql);
            if (!$inventory_stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $inventory_stmt->bind_param("ssiis", $warehouse, $selected_item, $quantity, $quantity, $category);
            if (!$inventory_stmt->execute()) {
                throw new Exception("Execute failed: " . $inventory_stmt->error);
            }
            $inventory_stmt->close();

            // 2. Process the order in order_receipt table
            $order_sql = "INSERT INTO order_receipt 
                         (item_receipt, quantity_reciept, arrive_receipt, destination_receipt, warehouse, supplier_name) 
                         VALUES (?, ?, ?, ?, ?, ?)";
            $order_stmt = $conn->prepare($order_sql);
            if (!$order_stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $order_stmt->bind_param("sissss", $selected_item, $quantity, $arrive_date, $destination, $warehouse, $supplier_name);
            if (!$order_stmt->execute()) {
                throw new Exception("Execute failed: " . $order_stmt->error);
            }
            $receipt_id = $order_stmt->insert_id;
            $order_stmt->close();

            // 3. Add to logistics tracking
            $logistics_sql = "INSERT INTO logistics_table 
                            (item, destination, pickup_date, delivery_date, status) 
                            VALUES (?, ?, ?, ?, ?)";
            $logistics_stmt = $conn->prepare($logistics_sql);
            if (!$logistics_stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $logistics_stmt->bind_param("sssss", $selected_item, $destination, $pickup_date, $delivery_date, $status);
            if (!$logistics_stmt->execute()) {
                throw new Exception("Execute failed: " . $logistics_stmt->error);
            }
            $logistics_id = $logistics_stmt->insert_id;
            $logistics_stmt->close();

            // 4. Remove from pending orders if exists
            if ($order_id > 0) {
                $del_sql = "DELETE FROM order_table WHERE id_order = ?";
                $del_stmt = $conn->prepare($del_sql);
                if (!$del_stmt) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                $del_stmt->bind_param("i", $order_id);
                if (!$del_stmt->execute()) {
                    throw new Exception("Execute failed: " . $del_stmt->error);
                }
                $del_stmt->close();
            }

            $conn->commit();
            header("Location: receipt.php?id=" . $receipt_id . "&logistics_id=" . $logistics_id);
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }

    } catch (Exception $e) {
        header("Location: inventory.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: inventory.php?error=Invalid+request+method");
    exit();
}