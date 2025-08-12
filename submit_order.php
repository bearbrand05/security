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

        // Calculate arrival date (3 days from order date)
        $arrive_date = date('Y-m-d', strtotime($date_of_order . ' +3 days'));

        // Start transaction
        $conn->begin_transaction();

        try {
            // 1. Track the quantity in warehouse_quantities table
            $track_sql = "INSERT INTO warehouse_quantities (warehouse, item_name, total_quantity) 
                         VALUES (?, ?, ?) 
                         ON DUPLICATE KEY UPDATE 
                         total_quantity = total_quantity + VALUES(total_quantity)";
            $track_stmt = $conn->prepare($track_sql);
            if (!$track_stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $track_stmt->bind_param("ssi", $warehouse, $selected_item, $quantity);
            if (!$track_stmt->execute()) {
                throw new Exception("Execute failed: " . $track_stmt->error);
            }
            $track_stmt->close();

            // 2. Process the order in order_receipt table (without supplier_name)
            $order_sql = "INSERT INTO order_receipt (item_receipt, quantity_reciept, arrive_receipt, destination_receipt, warehouse) 
                         VALUES (?, ?, ?, ?, ?)";
            $order_stmt = $conn->prepare($order_sql);
            if (!$order_stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $order_stmt->bind_param("sisss", $selected_item, $quantity, $arrive_date, $destination, $warehouse);
            if (!$order_stmt->execute()) {
                throw new Exception("Execute failed: " . $order_stmt->error);
            }
            $receipt_id = $order_stmt->insert_id;
            $order_stmt->close();

            // 3. Remove from pending orders if exists
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

            // Commit transaction
            $conn->commit();

            // Redirect to receipt page
            header("Location: receipt.php?id=" . $receipt_id);
            exit();

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            throw $e;
        }

    } catch (Exception $e) {
        // Display error message
        die("Error: " . $e->getMessage());
    }
} else {
    die("Invalid request method.");
}
?>
