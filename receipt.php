<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "inventory_db";

// Establish connection
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate receipt ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid receipt ID");
}
$id = intval($_GET['id']);

// Fetch receipt data
$sql = "SELECT * FROM order_receipt WHERE id_receipt = $id";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Receipt not found");
}

$receipt = $result->fetch_assoc();

// Create receipts directory if needed
$receiptsDir = __DIR__ . '/receipts';
if (!is_dir($receiptsDir)) {
    mkdir($receiptsDir, 0755, true);
}

// Save text version of receipt
$receiptContent = "ORDER RECEIPT\n";
$receiptContent .= "==============================\n";
$receiptContent .= "Receipt ID: " . $receipt['id_receipt'] . "\n";
$receiptContent .= "Item: " . $receipt['item_receipt'] . "\n";
$receiptContent .= "Quantity: " . $receipt['quantity_reciept'] . "\n";
$receiptContent .= "Arrival Date: " . $receipt['arrive_receipt'] . "\n";
$receiptContent .= "Destination: " . $receipt['destination_receipt'] . "\n";
$receiptContent .= "Generated: " . date('Y-m-d H:i:s') . "\n";
$receiptContent .= "==============================";

file_put_contents($receiptsDir . '/receipt_' . $receipt['id_receipt'] . '.txt', $receiptContent);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt #<?= $receipt['id_receipt'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .receipt-card {
            max-width: 500px;
            margin: 2rem auto;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .receipt-header {
            background-color: #2c3e50;
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        .receipt-body {
            background-color: white;
            padding: 2rem;
        }
        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px dashed #dee2e6;
        }
        .receipt-footer {
            background-color: #f8f9fa;
            padding: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #2c3e50;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            cursor: pointer;
        }
        @media print {
            body {
                background: none;
            }
            .print-btn {
                display: none;
            }
            .receipt-card {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="receipt-card">
            <div class="receipt-header">
                <h2><i class="fas fa-receipt me-2"></i> ORDER RECEIPT</h2>
                <p class="mb-0">Inventory Management System</p>
            </div>
            
            <div class="receipt-body">
                <div class="receipt-item">
                    <span class="fw-bold">Receipt ID:</span>
                    <span>#<?= htmlspecialchars($receipt['id_receipt']) ?></span>
                </div>
                
                <div class="receipt-item">
                    <span class="fw-bold">Item Name:</span>
                    <span><?= htmlspecialchars($receipt['item_receipt']) ?></span>
                </div>
                
                <div class="receipt-item">
                    <span class="fw-bold">Quantity:</span>
                    <span><?= htmlspecialchars($receipt['quantity_reciept']) ?></span>
                </div>
                
                <div class="receipt-item">
                    <span class="fw-bold">Arrival Date:</span>
                    <span><?= htmlspecialchars($receipt['arrive_receipt']) ?></span>
                </div>
                
                <div class="receipt-item">
                    <span class="fw-bold">Destination:</span>
                    <span><?= htmlspecialchars($receipt['destination_receipt']) ?></span>
                </div>
            </div>
            
            <div class="receipt-footer">
                <p class="mb-2">Thank you for your business!</p>
                <p class="mb-0">Generated on <?= date('F j, Y \a\t g:i A') ?></p>
            </div>
        </div>
    </div>

    <button class="print-btn" onclick="window.print()" title="Print Receipt">
        <i class="fas fa-print fa-lg"></i>
    </button>

    <script>
        // Auto-print after page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        });
    </script>
</body>
</html>
