<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "meow";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM logistics_table";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logistics Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #343a40;
            --secondary: #6c757d;
            --light: #f8f9fa;
            --dark: #212529;
            --accent: #adb5bd;
            --success: #6c9c5c;
            --warning: #e6a23c;
            --info: #909399;
            --danger: #f56c6c;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: var(--dark);
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        header h1 {
            font-size: 28px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        header h1 i {
            margin-right: 15px;
            color: var(--light);
        }
        
        .content {
            padding: 30px;
        }
        
        .table-container {
            overflow-x: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        
        th {
            background-color: var(--light);
            color: var(--primary);
            font-weight: 600;
            text-align: left;
            padding: 18px 20px;
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 2px solid var(--accent);
        }
        
        td {
            padding: 16px 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            min-width: 100px;
        }
        
        .status-delivered {
            background-color: rgba(108, 156, 92, 0.15);
            color: var(--success);
        }
        
        .status-in-transit {
            background-color: rgba(144, 147, 153, 0.15);
            color: var(--info);
        }
        
        .status-pending {
            background-color: rgba(230, 162, 60, 0.15);
            color: var(--warning);
        }
        
        .status-shipped {
            background-color: rgba(245, 108, 108, 0.15);
            color: var(--danger);
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--secondary);
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            color: var(--accent);
        }
        
        footer {
            background: var(--light);
            padding: 20px;
            text-align: center;
            color: var(--secondary);
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .content {
                padding: 20px;
            }
            
            th, td {
                padding: 12px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-shipping-fast"></i> Logistics</h1>
        </header>
        
        <div class="content">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item</th>
                            <th>Destination</th>
                            <th>Delivery Date</th>
                            <th>Pickup Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['item'] ?></td>
                                    <td><?= $row['destination'] ?></td>
                                    <td><?= date('M d, Y', strtotime($row['delivery_date'])) ?></td>
                                    <td><?= date('M d, Y', strtotime($row['pickup_date'])) ?></td>
                                    <td>
                                        <?php 
                                        $status = $row['status'];
                                        $statusClass = 'status-' . strtolower(str_replace(' ', '-', $status));
                                        echo "<span class='status {$statusClass}'>{$status}</span>";
                                        ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <i class="fas fa-box-open"></i>
                                    <div>No logistics data found.</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <footer>
            Logistics Management System &copy; <?= date('Y') ?>
        </footer>
    </div>
</body>
</html>
<?php
$conn->close();
?>






-- Drop existing table if it exists
DROP TABLE IF EXISTS logistics_table;

-- Create table with auto-increment
CREATE TABLE logistics_table (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item VARCHAR(255) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    pickup_date DATE,
    delivery_date DATE,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data with explicit IDs (1-4)
INSERT INTO logistics_table (id, item, destination, delivery_date, pickup_date, status) VALUES
(1, 'T-Shirt - Black', 'Cebu City', '2025-08-01', '2025-07-30', 'Delivered'),
(2, 'Hoodie - Blue', 'Davao City', '2025-08-03', '2025-08-01', 'In Transit'),
(3, 'Jogger Pants', 'Baguio City', '2025-08-05', '2025-08-02', 'Pending'),
(4, 'Cap - Red', 'Makati City', '2025-08-04', '2025-08-02', 'Shipped');

-- Reset auto-increment to start after 4
ALTER TABLE logistics_table AUTO_INCREMENT = 5;
