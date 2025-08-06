<?php
// --- MySQL Connection ---
$host = "localhost";
$username = "root";
$password = "";
$database = "jalosi";

$conn = new mysqli($host, $username, $password, $database);
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
      background-color: #F2EFE5;
      margin: 0;
      padding: 40px;
      color: #4A4A4A;
    }

    h1 {
      text-align: center;
      color: #B4B4B8;
      margin-bottom: 30px;
      font-size: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
    }

    th, td {
      padding: 16px;
      text-align: center;
    }

    th {
      background-color: #C7C8CC;
      color: #2E2E2E;
      font-weight: 600;
    }

    tr:nth-child(even) {
      background-color: #F2EFE5;
    }

    tr:hover {
      background-color: #E3E1D9;
    }

    td {
      color: #4A4A4A;
    }

    .status {
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: 500;
      display: inline-block;
    }

    .status.Delivered {
      background-color: #B4B4B8;
      color: white;
    }

    .status.Pending {
      background-color: #C7C8CC;
      color: white;
    }

    .status.Cancelled {
      background-color: #E3E1D9;
      color: #333;
    }

    .export-button {
      padding: 6px 12px;
      background-color: #C7C8CC;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-size: 14px;
      transition: background-color 0.2s ease;
    }

    .export-button:hover {
      background-color: #B4B4B8;
    }

    .back-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #C7C8CC;
      color: white;
      font-family: 'Inter', sans-serif;
      font-size: 14px;
      padding: 8px 16px;
      border-radius: 20px;
      text-decoration: none;
      box-shadow: 0 2px 4px rgba(0,0,0,0.06);
      transition: background-color 0.2s ease;
      z-index: 999;
    }

    .back-button:hover {
      background-color: #B4B4B8;
    }
  </style>
</head>
<body>

<a href="category_tracking.php" class="back-button">&#8592; Back</a>
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
      <th>Action</th>
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
          <td>
            <a class="export-button" href="print.php?id=<?= $row['id_history'] ?>" target="_blank">📄 Export</a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7">No history data found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

</body>
</html>

<?php $conn->close(); ?>
