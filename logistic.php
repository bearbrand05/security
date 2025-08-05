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
    <title>Logistics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

    <h2>Logistics Table</h2>

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
                        <td><?= $row['id_logistic'] ?></td>
                        <td><?= $row['item'] ?></td>
                        <td><?= $row['destination'] ?></td>
                        <td><?= $row['delivery_date'] ?></td>
                        <td><?= $row['pickup_date'] ?></td>
                        <td><?= $row['status'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No logistics data found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

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
