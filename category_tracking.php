<?php
$host = "localhost";
$username = "root";
$password = "";

$database = "inventory_db";


$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Define categories and subcategories
$categories = [
  "Electronics" => ["Appliances", "Gadgets", "Accessories"],
  "Fashion" => ["Clothes", "Shoes", "Bags"],
  "Home & Living" => ["Furniture", "Decor"],
  "Beauty & Health" => ["Skincare", "Supplements"]
];

// Fetch total quantity of Delivered items only
$totals = [];
$sql = "
  SELECT itemname_history, SUM(quantity_history) AS total 
  FROM history_table 
  WHERE status_history = 'Delivered'
  GROUP BY itemname_history
";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
  $totals[$row['itemname_history']] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Category Tracking</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
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
      font-size: 32px;
    }

    .category-card {
      background-color: #E3E1D9;
      border-radius: 14px;
      padding: 24px;
      margin-bottom: 25px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s ease, background-color 0.2s ease;
      cursor: pointer;
    }

    .category-card:hover {
      transform: translateY(-4px);
      background-color: #DAD8CF;
    }

    .category-title {
      font-size: 22px;
      font-weight: 600;
      color: #5A5A5A;
      margin-bottom: 10px;
    }

    .subcategory-list {
      padding-left: 20px;
      margin: 0;
      display: none;
    }

    .subcategory-item {
      font-size: 16px;
      margin-bottom: 8px;
      color: #4A4A4A;
    }

    .subcategory-item::before {
      content: "↳ ";
      color: #B4B4B8;
    }

    .back-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #C7C8CC;
      color: #fff;
      font-size: 14px;
      padding: 10px 18px;
      border-radius: 25px;
      text-decoration: none;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
      transition: background-color 0.2s ease;
      z-index: 1000;
    }

    .back-button:hover {
      background-color: #B4B4B8;
    }

    /* Special Highlight for Electronics */
    .category-card.electronics {
      border-left: 6px solid #B4B4B8;
    }
  </style>
</head>
<body>

<h1>Category Tracking</h1>

<?php foreach ($categories as $category => $subcategories): ?>
  <div class="category-card <?= strtolower($category) === 'electronics' ? 'electronics' : '' ?>" onclick="toggleSub(this)">
    <div class="category-title"><?= htmlspecialchars($category) ?></div>
    <ul class="subcategory-list">
      <?php foreach ($subcategories as $sub): 
        $qty = $totals[$sub] ?? 0;
      ?>
        <li class="subcategory-item"><?= htmlspecialchars($sub) ?> <strong>(Total: <?= $qty ?>)</strong></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endforeach; ?>

<a href="history.php" class="back-button">&#8592; Back</a>

<script>
  function toggleSub(card) {
    const list = card.querySelector('.subcategory-list');
    const allLists = document.querySelectorAll('.subcategory-list');
    allLists.forEach(l => {
      if (l !== list) l.style.display = 'none';
    });
    list.style.display = list.style.display === 'block' ? 'none' : 'block';
  }
</script>

</body>
</html>

<<<<<<< HEAD
<?php $conn->close(); ?>

=======
<?php $conn->close(); ?>
>>>>>>> 303baf17d177401e3c3fcbc4d3a65964f486a7c3
