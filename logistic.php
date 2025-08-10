<?php
$host = "localhost";
$user = "root";
$pass = "";

$dbname = "inventory_db";

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
    <style>
        :root {
            --primary: #B4B4B8;
            --secondary: #C7C8CC;
            --light: #E3E1D9;
            --background: #F2EFE5;
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
            background-color: var(--background);
            color: var(--dark);
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: var(--light);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--dark);
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
            color: var(--dark);
        }
        
        .content {
            padding: 30px;
        }
        
        .actions-bar {
            display: flex;
            justify-content: space-between;
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
        
        .btn-success {
            background-color: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #5a8a4a;
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            color: var(--dark);
        }
        
        .btn-secondary:hover {
            background-color: var(--primary);
        }
        
        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
        }
        
        .table-container {
            overflow-x: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            background: white;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        
        th {
            background-color: var(--secondary);
            color: var(--dark);
            font-weight: 600;
            text-align: left;
            padding: 18px 20px;
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 2px solid var(--primary);
        }
        
        td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--secondary);
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover {
            background-color: var(--light);
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
            color: var(--primary);
        }
        
        footer {
            background-color: var(--secondary);
            padding: 20px;
            text-align: center;
            color: var(--dark);
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
            border: 1px solid var(--primary);
            border-radius: 4px;
            background-color: white;
        }
        
        .success-message {
            background-color: rgba(108, 156, 92, 0.15);
            color: var(--success);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid var(--success);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .success-message i {
            font-size: 20px;
        }
        
        .deleted-items {
            background-color: white;
            border: 1px solid var(--secondary);
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .deleted-items h3 {
            color: var(--danger);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .deleted-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            background-color: var(--light);
            border-radius: 6px;
            margin-bottom: 10px;
            border-left: 3px solid var(--danger);
        }
        
        .deleted-item-info {
            flex: 1;
        }
        
        .deleted-item-meta {
            font-size: 12px;
            color: var(--secondary);
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .actions-bar {
                flex-direction: column;
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
            
            .deleted-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
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








