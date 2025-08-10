<?php
// receipt.php
$host = "localhost";
$username = "root";
$password = "";
$database = "inventory_db";
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_GET['id'])) {
    die("Missing history ID.");
}
$id = intval($_GET['id']);
$sql = "SELECT * FROM history_table WHERE id_history = $id";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
if (!$data) {
    die("Order not found.");
}
// Create receipts folder if not exists
$folder = __DIR__ . "/receipts";
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}
// Prepare receipt content for saving
$receiptText = "ARRIVAL RECEIPT\n"
    . "=====================================\n"
    . "ID: {$data['id_history']}\n"
    . "Item: {$data['itemname_history']}\n"
    . "Company: {$data['companyname_history']}\n"
    . "Arrival Date: {$data['arrive_history']}\n"
    . "Status: {$data['status_history']}\n"
    . "Quantity: {$data['quantity_history']}\n"
    . "Generated: " . date("Y-m-d H:i:s") . "\n"
    . "=====================================\n";
$filePath = $folder . "/receipt_{$data['id_history']}.txt";
file_put_contents($filePath, $receiptText);
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Arrival Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --light: #ecf0f1;
            --success: #27ae60;
            --border: #bdc3c7;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .receipt-container {
            width: 100%;
            max-width: 450px;
        }
        
        .receipt {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }
        
        .receipt-header {
            background: var(--primary);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        
        .receipt-header::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 30px;
            background: var(--primary);
            border-radius: 50%;
            box-shadow: 0 0 0 10px white;
        }
        
        .receipt-header h1 {
            margin: 0;
            font-weight: 600;
            font-size: 1.8rem;
            letter-spacing: 1px;
        }
        
        .receipt-header p {
            margin: 5px 0 0;
            opacity: 0.8;
            font-size: 0.9rem;
        }
        
        .receipt-body {
            padding: 30px 20px 20px;
        }
        
        .receipt-id {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .receipt-id span {
            background: var(--light);
            color: var(--primary);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .receipt-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed var(--border);
        }
        
        .receipt-item:last-child {
            border-bottom: none;
        }
        
        .receipt-item .label {
            color: #7f8c8d;
            font-weight: 500;
        }
        
        .receipt-item .value {
            font-weight: 600;
            text-align: right;
            max-width: 60%;
        }
        
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status.delivered {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }
        
        .status.pending {
            background: rgba(241, 196, 15, 0.1);
            color: #f1c40f;
        }
        
        .status.processing {
            background: rgba(52, 152, 219, 0.1);
            color: var(--secondary);
        }
        
        .receipt-footer {
            text-align: center;
            padding: 20px;
            background: var(--light);
            font-size: 0.85rem;
            color: #7f8c8d;
        }
        
        .receipt-footer i {
            color: var(--secondary);
            margin: 0 5px;
        }
        
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .print-btn:hover {
            background: #2980b9;
            transform: scale(1.05);
        }
        
        @media print {
            body {
                background: none;
                padding: 0;
            }
            
            .receipt-container {
                max-width: 100%;
                box-shadow: none;
            }
            
            .print-btn {
                display: none;
            }
            
            .receipt {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt">
            <div class="receipt-header">
                <h1>ARRIVAL RECEIPT</h1>
                <p>Inventory Management System</p>
            </div>
            <div class="receipt-body">
                <div class="receipt-id">
                    <span>ID: <?= $data['id_history'] ?></span>
                </div>
                
                <div class="receipt-item">
                    <div class="label">Item Name</div>
                    <div class="value"><?= htmlspecialchars($data['itemname_history']) ?></div>
                </div>
                
                <div class="receipt-item">
                    <div class="label">Company</div>
                    <div class="value"><?= htmlspecialchars($data['companyname_history']) ?></div>
                </div>
                
                <div class="receipt-item">
                    <div class="label">Arrival Date</div>
                    <div class="value"><?= htmlspecialchars($data['arrive_history']) ?></div>
                </div>
                
                <div class="receipt-item">
                    <div class="label">Status</div>
                    <div class="value">
                        <span class="status <?= strtolower(htmlspecialchars($data['status_history'])) ?>">
                            <?= htmlspecialchars($data['status_history']) ?>
                        </span>
                    </div>
                </div>
                
                <div class="receipt-item">
                    <div class="label">Quantity</div>
                    <div class="value"><?= htmlspecialchars($data['quantity_history']) ?></div>
                </div>
            </div>
            <div class="receipt-footer">
                <p>Thank you for your business! <i class="fas fa-check-circle"></i> <i class="fas fa-truck"></i></p>
                <p>Generated on <?= date("F j, Y, g:i a") ?></p>
            </div>
        </div>
    </div>
    
    <button class="print-btn" onclick="window.print()" title="Print Receipt">
        <i class="fas fa-print fa-lg"></i>
    </button>
    
    <script>
        // Auto print when loaded
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
