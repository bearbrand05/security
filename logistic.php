<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "inventory_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add deleted_at column if it doesn't exist
$conn->query("ALTER TABLE logistics_table ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL DEFAULT NULL");

// Handle redirects after delete/restore actions
if (!isset($_GET['delete_id']) && !isset($_GET['restore_id']) && 
    (isset($_GET['deleted']) || isset($_GET['restored']))) {
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle delete action
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

// Handle restore action
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

// Get active shipments
$sql = "SELECT * FROM logistics_table WHERE deleted_at IS NULL";
$result = $conn->query($sql);

// Get recently deleted items
$deleted_sql = "SELECT * FROM logistics_table WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC LIMIT 5";
$deleted_result = $conn->query($deleted_sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logistics Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* [All your CSS styles remain exactly the same] */
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
                                    <td><?= htmlspecialchars($row['item']) ?></td>
                                    <td><?= htmlspecialchars($row['destination']) ?></td>
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
                                <strong><?= htmlspecialchars($deleted_row['item']) ?></strong>
                                <div class="deleted-item-meta">
                                    ID: <?= $deleted_row['id'] ?> | 
                                    Destination: <?= htmlspecialchars($deleted_row['destination']) ?> | 
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