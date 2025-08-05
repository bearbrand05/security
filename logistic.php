<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "meow";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query data from the existing table
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
        
        .actions-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 14px;
        }
        
        .btn i {
            font-size: 16px;
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #e05555;
        }
        
        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
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
        
        .action-buttons {
            display: flex;
            gap: 8px;
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
            background-color: var(--light);
            padding: 20px;
            text-align: center;
            color: var(--secondary);
            font-size: 14px;
        }
        
        .filter-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .filter-container select,
        .filter-container input {
            padding: 8px 12px;
            border: 1px solid var(--accent);
            border-radius: 4px;
            background-color: white;
        }
        
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .actions-bar {
                justify-content: center;
            }
            
            .content {
                padding: 20px;
            }
            
            th, td {
                padding: 12px 15px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-shipping-fast"></i> Logistics Dashboard</h1>
        </header>
        
        <div class="content">
            <div class="actions-bar">
                <div class="filter-container">
                    <select id="statusFilter" onchange="filterTable()">
                        <option value="">All Statuses</option>
                        <option value="Delivered">Delivered</option>
                        <option value="In Transit">In Transit</option>
                        <option value="Pending">Pending</option>
                        <option value="Shipped">Shipped</option>
                    </select>
                    <input type="text" id="searchInput" placeholder="Search..." onkeyup="filterTable()">
                </div>
            </div>
            
            <div class="table-container">
                <table id="logisticsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item</th>
                            <th>Destination</th>
                            <th>Delivery Date</th>
                            <th>Pickup Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
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
                                    <td>
                                        <div class="action-buttons">
                                            <a href="delete_shipment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this shipment?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <i class="fas fa-box-open"></i>
                                    <div>No logistics data found.</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

    <script>
        function filterTable() {
            const statusFilter = document.getElementById('statusFilter').value;
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('logisticsTable');
            const tr = table.getElementsByTagName('tr');
            
            for (let i = 1; i < tr.length; i++) {
                const statusCell = tr[i].getElementsByTagName('td')[5];
                const statusText = statusCell.textContent || statusCell.innerText;
                
                let showRow = true;
                
                if (statusFilter && statusText.indexOf(statusFilter) === -1) {
                    showRow = false;
                }
                
                if (searchInput) {
                    const rowText = tr[i].textContent.toLowerCase();
                    if (rowText.indexOf(searchInput) === -1) {
                        showRow = false;
                    }
                }
                
                tr[i].style.display = showRow ? '' : 'none';
            }
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>





DROP TABLE IF EXISTS logistics_table;

-- auto-increment
CREATE TABLE logistics_table (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item VARCHAR(255) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    pickup_date DATE,
    delivery_date DATE,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO logistics_table (id, item, destination, delivery_date, pickup_date, status) VALUES
(1, 'T-Shirt - Black', 'Cebu City', '2025-08-01', '2025-07-30', 'Delivered'),
(2, 'Hoodie - Blue', 'Davao City', '2025-08-03', '2025-08-01', 'In Transit'),
(3, 'Jogger Pants', 'Baguio City', '2025-08-05', '2025-08-02', 'Pending'),
(4, 'Cap - Red', 'Makati City', '2025-08-04', '2025-08-02', 'Shipped');

-- Reset auto-increment to start after 4
ALTER TABLE logistics_table AUTO_INCREMENT = 5;
