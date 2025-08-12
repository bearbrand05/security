<?php
include 'db.php';
$result = $conn->query("SELECT * FROM warehouse_quantities ORDER BY warehouse, item_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Warehouse Quantities</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        .quantity-cell { font-weight: bold; }
    </style>
</head>
<body>
    <h2>Accumulated Order Quantities</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Warehouse</th>
                <th>Item</th>
                <th>Total Quantity</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['warehouse']) ?></td>
                <td><?= htmlspecialchars($row['item_name']) ?></td>
                <td class="quantity-cell"><?= $row['total_quantity'] ?></td>
                <td><?= $row['last_updated'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
