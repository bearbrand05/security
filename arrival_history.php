<?php
// --- MySQL Connection ---
$host = "localhost";
$username = "root";
$password = ""; // ← Replace with your actual password
$database = "silus"; // ← Replace with your DB name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch arrival history
$sql = "SELECT * FROM history_table ORDER BY arrive_history DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Arrival History</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f9e1e0;
      margin: 0;
      padding: 40px;
      color: #4a7ba6;
    }

    h1 {
      text-align: center;
      color: #bc85a3;
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    th, td {
      padding: 16px;
      text-align: center;
    }

    th {
      background-color: #feadb9;
      color: white;
      font-weight: 600;
    }

    tr:nth-child(even) {
      background-color: #fce9eb;
    }

    tr:hover {
      background-color: #f6d7dc;
    }

    td {
      color: #4a7ba6;
    }

    .status {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: 500;
      display: inline-block;
    }

    .status.Delivered {
      background-color: #bc85a3;
      color: white;
    }

    .status.Pending {
      background-color: #9799ba;
      color: white;
    }

    .status.Cancelled {
      background-color: #feadb9;
      color: white;
    }

    .back-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #feadb9;
    color: #fff;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    padding: 8px 16px;
    border-radius: 20px;
    text-decoration: none;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: background-color 0.2s ease;
    z-index: 999;
  }

  .back-button:hover {
    background-color: #bc85a3;
  }
  </style>
</head>
<body>

<a href="history.php" class="back-button" title="Go Back">&#8592; Back</a>
<h1>Arrival History</h1>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Item Name</th>
      <th>Company</th>
      <th>Arrival Date</th>
      <th>Status</th>
      <th>Quantity</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id_history'] ?></td>
          <td><?= htmlspecialchars($row['itemname_history']) ?></td>
          <td><?= htmlspecialchars($row['companyname_history']) ?></td>
          <td><?= $row['arrive_history'] ?></td>
          <td><span class="status <?= $row['status_history'] ?>"><?= $row['status_history'] ?></span></td>
          <td><?= $row['quantity_history'] ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="6">No history data found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>
