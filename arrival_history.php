<?php
// --- MySQL Connection ---
$host = "localhost";
$username = "root";
$password = "";
$database = "inventory_db";

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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary-bg: #F2EFE5;
      --card-bg: #E3E1D9;
      --header-text: #B4B4B8;
      --button-bg: #C7C8CC;
      --button-hover: #B4B4B8;
      --text-dark: #2C3E50;
      --text-medium: #34495E;
      --text-light: #7F8C8D;
      --accent: #3498DB;
      --accent-light: #5DADE2;
      --accent-dark: #2980B9;
      --success: #2ECC71;
      --success-light: #58D68D;
      --warning: #F39C12;
      --warning-light: #F5B041;
      --danger: #E74C3C;
      --danger-light: #EC7063;
      --purple: #9B59B6;
      --purple-light: #BB8FCE;
      --teal: #1ABC9C;
      --teal-light: #48C9B0;
      --header-bg: #5D6D7E;
      --header-accent: #34495E;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #F2EFE5 0%, #E8E4DA 100%);
      color: var(--text-medium);
      min-height: 100vh;
      overflow-x: hidden;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    
    .header {
      background: linear-gradient(135deg, var(--header-bg) 0%, var(--header-accent) 100%);
      color: white;
      padding: 30px 0;
      margin-bottom: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      position: relative;
      overflow: hidden;
    }
    
    .header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="none"/><path d="M0,0 L100,100 M100,0 L0,100" stroke="rgba(255,255,255,0.1)" stroke-width="2"/></svg>');
      background-size: 20px 20px;
      opacity: 0.3;
    }
    
    .header-content {
      position: relative;
      z-index: 1;
      text-align: center;
      padding: 0 20px;
    }
    
    .header h1 {
      font-size: 42px;
      font-weight: 700;
      margin-bottom: 10px;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      letter-spacing: 1px;
    }
    
    .header p {
      font-size: 18px;
      font-weight: 400;
      opacity: 0.9;
    }
    
    .header-icon {
      display: inline-block;
      background: rgba(255, 255, 255, 0.2);
      width: 60px;
      height: 60px;
      border-radius: 50%;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
    }
    
    .table-container {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      margin-bottom: 60px;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
    }
    
    th, td {
      padding: 18px 15px;
      text-align: left;
    }
    
    th {
      background: linear-gradient(135deg, var(--header-bg) 0%, var(--header-accent) 100%);
      color: white;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-size: 14px;
    }
    
    tr:nth-child(even) {
      background-color: rgba(242, 239, 229, 0.3);
    }
    
    tr:hover {
      background-color: rgba(227, 225, 217, 0.5);
    }
    
    td {
      color: var(--text-medium);
      font-weight: 500;
    }
    
    .status {
      padding: 8px 16px;
      border-radius: 30px;
      font-size: 14px;
      font-weight: 600;
      display: inline-block;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .status.Delivered {
      background: linear-gradient(135deg, var(--success), var(--success-light));
      color: white;
      box-shadow: 0 4px 8px rgba(46, 204, 113, 0.2);
    }
    
    .status.Pending {
      background: linear-gradient(135deg, var(--warning), var(--warning-light));
      color: white;
      box-shadow: 0 4px 8px rgba(243, 156, 18, 0.2);
    }
    
    .status.Cancelled {
      background: linear-gradient(135deg, var(--danger), var(--danger-light));
      color: white;
      box-shadow: 0 4px 8px rgba(231, 76, 60, 0.2);
    }
    
    .export-button {
      padding: 8px 16px;
      background: linear-gradient(135deg, var(--accent), var(--accent-light));
      color: white;
      text-decoration: none;
      border-radius: 30px;
      font-size: 14px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(52, 152, 219, 0.2);
    }
    
    .export-button:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(52, 152, 219, 0.3);
    }
    
    .export-button i {
      margin-right: 6px;
    }
    
    .back-button {
      position: fixed;
      bottom: 25px;
      right: 25px;
      background: linear-gradient(135deg, var(--accent) 0%, var(--purple) 100%);
      color: white;
      font-size: 16px;
      font-weight: 500;
      padding: 12px 20px;
      border-radius: 50px;
      text-decoration: none;
      box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      z-index: 1000;
    }
    
    .back-button i {
      margin-right: 8px;
    }
    
    .back-button:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 25px rgba(52, 152, 219, 0.4);
    }
    
    .no-data {
      text-align: center;
      padding: 40px;
      color: var(--text-light);
      font-size: 18px;
    }
    
    .floating-shapes {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -1;
      overflow: hidden;
    }
    
    .shape {
      position: absolute;
      opacity: 0.1;
      animation: float 15s infinite ease-in-out;
    }
    
    .shape-1 {
      width: 100px;
      height: 100px;
      background: var(--accent);
      border-radius: 50%;
      top: 20%;
      left: 10%;
      animation-delay: 0s;
    }
    
    .shape-2 {
      width: 80px;
      height: 80px;
      background: var(--purple);
      border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
      top: 60%;
      right: 15%;
      animation-delay: 2s;
    }
    
    .shape-3 {
      width: 120px;
      height: 120px;
      background: var(--teal);
      border-radius: 50%;
      bottom: 10%;
      left: 20%;
      animation-delay: 4s;
    }
    
    .shape-4 {
      width: 90px;
      height: 90px;
      background: var(--warning);
      border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%;
      top: 30%;
      right: 30%;
      animation-delay: 6s;
    }
    
    @keyframes float {
      0%, 100% {
        transform: translateY(0) rotate(0deg);
      }
      50% {
        transform: translateY(-30px) rotate(10deg);
      }
    }
    
    @media (max-width: 768px) {
      .header h1 {
        font-size: 32px;
      }
      
      .table-container {
        overflow-x: auto;
      }
      
      table {
        min-width: 700px;
      }
      
      th, td {
        padding: 12px 10px;
      }
    }
  </style>
</head>
<body>
  <div class="floating-shapes">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    <div class="shape shape-4"></div>
  </div>
  
  <div class="container">
    <div class="header">
      <div class="header-content">
        <div class="header-icon">
          <i class="fas fa-history"></i>
        </div>
        <h1>Arrival History</h1>
        <p>Track and view all item arrivals</p>
      </div>
    </div>
    
    <div class="table-container">
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
                  <a class="export-button" href="receipt.php?id=<?= $row['id_history'] ?>" target="_blank">
                    <i class="fas fa-file-export"></i> Export
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="no-data">
                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block; color: var(--header-text);"></i>
                No history data found.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <a href="category_tracking.php" class="back-button">
    <i class="fas fa-arrow-left"></i> Back
  </a>
</body>
</html>

<?php $conn->close(); ?>
