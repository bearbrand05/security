<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "meow";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("ALTER TABLE logistics_table ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL DEFAULT NULL");
if (!isset($_GET['delete_id']) && !isset($_GET['restore_id']) && 
    (isset($_GET['deleted']) || isset($_GET['restored']))) {
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("UPDATE logistics_table SET deleted_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?deleted=1");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
}
if (isset($_GET['restore_id'])) {
    $id = $_GET['restore_id'];
    $stmt = $conn->prepare("UPDATE logistics_table SET deleted_at = NULL WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?restored=1");
        exit();
    } else {
        echo "Error restoring record: " . $conn->error;
    }
    $stmt->close();
}
$sql = "SELECT * FROM logistics_table WHERE deleted_at IS NULL";
$result = $conn->query($sql);
$deleted_sql = "SELECT * FROM logistics_table WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC LIMIT 5";
$deleted_result = $conn->query($deleted_sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logistics Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1a365d;
            --secondary: #2c5282;
            --accent: #d4af37;
            --light: #f8fafc;
            --background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            --dark: #1a202c;
            --success: #38a169;
            --warning: #d69e2e;
            --info: #3182ce;
            --danger: #e53e3e;
            --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            --hover-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
            --gold-gradient: linear-gradient(135deg, #d4af37 0%, #f9e79f 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: var(--background);
            color: var(--dark);
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        .container {
            max-width: 1400px;
            margin: 30px auto;
            background: var(--light);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            position: relative;
        }
        
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--gold-gradient);
        }
        
        header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 35px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }
        
        header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
        }
        
        header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            display: flex;
            align-items: center;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        header h1 i {
            margin-right: 20px;
            color: var(--accent);
            font-size: 32px;
        }
        
        .content {
            padding: 40px;
        }
        
        .actions-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 14px;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-100%);
            transition: transform 0.4s ease;
            z-index: -1;
        }
        
        .btn:hover::before {
            transform: translateX(0);
        }
        
        .btn i {
            font-size: 16px;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #c53030);
            color: white;
            box-shadow: 0 4px 10px rgba(229, 62, 62, 0.3);
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, #c53030, #9b2c2c);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(229, 62, 62, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success), #2f855a);
            color: white;
            box-shadow: 0 4px 10px rgba(56, 161, 105, 0.3);
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #2f855a, #276749);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(56, 161, 105, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #e2e8f0, #cbd5e0);
            color: var(--primary);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #cbd5e0, #a0aec0);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .btn-sm {
            padding: 8px 14px;
            font-size: 12px;
        }
        
        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 40px;
            background: white;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        
        th {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            color: var(--primary);
            font-weight: 600;
            text-align: left;
            padding: 20px 25px;
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 2px solid var(--accent);
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        td {
            padding: 18px 25px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 15px;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover {
            background-color: #f8fafc;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 500;
            text-align: center;
            min-width: 110px;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }
        
        .status::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }
        
        .status-in-transit {
            background-color: rgba(49, 130, 206, 0.1);
            color: var(--info);
            border: 1px solid rgba(49, 130, 206, 0.2);
        }
        
        .status-in-transit::before {
            background-color: var(--info);
        }
        
        .status-pending {
            background-color: rgba(214, 158, 46, 0.1);
            color: var(--warning);
            border: 1px solid rgba(214, 158, 46, 0.2);
        }
        
        .status-pending::before {
            background-color: var(--warning);
        }
        
        .status-shipped {
            background-color: rgba(229, 62, 62, 0.1);
            color: var(--danger);
            border: 1px solid rgba(229, 62, 62, 0.2);
        }
        
        .status-shipped::before {
            background-color: var(--danger);
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }
        
        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            color: var(--accent);
            opacity: 0.7;
        }
        
        .empty-state div {
            font-size: 18px;
            font-weight: 500;
        }
        
        footer {
            background-color: #1a365d;
            padding: 25px;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        
        .filter-container {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .filter-container select,
        .filter-container input {
            padding: 12px 18px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background-color: white;
            font-size: 14px;
            font-family: 'Montserrat', sans-serif;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .filter-container select:focus,
        .filter-container input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }
        
        .success-message {
            background-color: rgba(56, 161, 105, 0.1);
            color: var(--success);
            padding: 18px 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 5px solid var(--success);
            display: flex;
            align-items: center;
            gap: 15px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(56, 161, 105, 0.1);
        }
        
        .success-message i {
            font-size: 24px;
        }
        
        .deleted-items {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-top: 40px;
            box-shadow: var(--card-shadow);
            border: 1px solid #e2e8f0;
        }
        
        .deleted-items h3 {
            color: var(--danger);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 700;
        }
        
        .deleted-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 25px;
            background-color: #f8fafc;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 4px solid var(--danger);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }
        
        .deleted-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        }
        
        .deleted-item-info {
            flex: 1;
        }
        
        .deleted-item-meta {
            font-size: 13px;
            color: #718096;
            margin-top: 8px;
        }
        
        /* Luxury animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .container, header, .content, .table-container, .deleted-items {
            animation: fadeIn 0.8s ease-out;
        }
        
        /* Gold accents */
        .gold-accent {
            color: var(--accent);
            font-weight: 600;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
                padding: 25px 20px;
            }
            
            header h1 {
                font-size: 28px;
            }
            
            .actions-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .content {
                padding: 25px 20px;
            }
            
            th, td {
                padding: 15px 20px;
                font-size: 14px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .deleted-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .deleted-items h3 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-shipping-fast"></i> <span class="gold-accent">Logistics</span> Dashboard</h1>
        </header>
        
        <div class="content">
            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <span>Shipment moved to deleted items! You can restore it from the bottom of the page.</span>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['restored']) && $_GET['restored'] == 1): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <span>Shipment restored successfully!</span>
                </div>
            <?php endif; ?>
            
            <div class="actions-bar">
                <div class="filter-container">
                    <select id="statusFilter" onchange="filterTable()">
                        <option value="">All Statuses</option>
                        <option value="Shipped">Shipped</option>
                        <option value="In Transit">In Transit</option>
                        <option value="Pending">Pending</option>
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
                                    <td><?= !empty($row['delivery_date']) ? date('M d, Y', strtotime($row['delivery_date'])) : 'N/A' ?></td>
                                    <td><?= !empty($row['pickup_date']) ? date('M d, Y', strtotime($row['pickup_date'])) : 'N/A' ?></td>
                                    <td>
                                        <?php 
                                        $status = $row['status'];
                                        $statusClass = 'status-' . strtolower(str_replace(' ', '-', $status));
                                        echo "<span class='status {$statusClass}'>{$status}</span>";
                                        ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this shipment?')">
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
            
            <?php if ($deleted_result && $deleted_result->num_rows > 0): ?>
                <div class="deleted-items">
                    <h3><i class="fas fa-trash-alt"></i> Recently Deleted Items</h3>
                    
                    <?php while($deleted_row = $deleted_result->fetch_assoc()): ?>
                        <div class="deleted-item">
                            <div class="deleted-item-info">
                                <strong><?= $deleted_row['item'] ?></strong>
                                <div class="deleted-item-meta">
                                    ID: <?= $deleted_row['id'] ?> | 
                                    Destination: <?= $deleted_row['destination'] ?> | 
                                    Deleted: <?= date('M d, Y H:i', strtotime($deleted_row['deleted_at'])) ?>
                                </div>
                            </div>
                            <a href="?restore_id=<?= $deleted_row['id'] ?>" class="btn btn-sm btn-success">
                                <i class="fas fa-undo"></i> Restore
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
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




CREATE TABLE logistics_table (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item VARCHAR(255) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    pickup_date DATE,
    delivery_date DATE,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL
);

INSERT INTO logistics_table (id, item, destination, delivery_date, pickup_date, status, deleted_at) VALUES
(1, 'T-Shirt - Black', 'Cebu City', '2025-08-01', '2025-07-30', 'In Transit', NULL),
(2, 'Hoodie - Blue', 'Davao City', '2025-08-03', '2025-08-01', 'In Transit', NULL),
(3, 'Cargo Pants', 'Baguio City', '2025-08-05', '2025-08-02', 'Pending', NULL),
(4, 'Cap - Red', 'Makati City', '2025-08-04', '2025-08-02', 'Shipped', NULL);
