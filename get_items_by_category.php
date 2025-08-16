<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "inventory_db";
$mysqli = new mysqli($host, $user, $pass, $dbname);

if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

$category = $_GET['category'] ?? '';
$category = $mysqli->real_escape_string($category);

$result = $mysqli->query("SELECT itemname_invent, stock_invent, restock_invent 
                         FROM inventory_table 
                         WHERE categories = '$category'");

if (!$result) {
  die("Query failed: " . $mysqli->error);
}

if ($result->num_rows > 0) {
  echo '<ul class="list-unstyled">';
  while ($row = $result->fetch_assoc()) {
    echo '<li class="items-list-item">';
    echo '<strong>' . htmlspecialchars($row['itemname_invent']) . '</strong><br>';
    echo 'Stock: ' . $row['stock_invent'] . ' | ';
    echo 'Restock: ' . $row['restock_invent'];
    echo '</li>';
  }
  echo '</ul>';
} else {
  echo '<div class="alert alert-info">No items found in this category.</div>';
}

?>

