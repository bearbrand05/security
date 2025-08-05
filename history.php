<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "silus"; // Your database

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query for history_table
$sql = "SELECT * FROM history_table";
$result = $conn->query($sql);

// Output page with inline CSS and table
echo '
<!DOCTYPE html>
<html>
<head>
    <title>History Table</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            color: #333;
            margin: 40px;
        }
        h2 {
            color: #111;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th {
            background-color: #000;
            color: #fff;
            padding: 12px;
            text-transform: uppercase;
            font-size: 14px;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <h2>Item Arrival History</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Company Name</th>
            <th>Arrival Date</th>
            <th>Status</th>
            <th>Quantity</th>
        </tr>
';

// Display table rows
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id_history']}</td>
            <td>{$row['itemname_history']}</td>
            <td>{$row['companyname_history']}</td>
            <td>{$row['arrive_history']}</td>
            <td>{$row['status_history']}</td>
            <td>{$row['quantity_history']}</td>
        </tr>";
    }
} else {
    echo '<tr><td colspan="6">No history records found.</td></tr>';
}

echo '
    </table>
</body>
</html>
';

$conn->close();
?>
