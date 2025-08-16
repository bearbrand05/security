<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "inventory_db";
>>>>>>> 303baf17d177401e3c3fcbc4d3a65964f486a7c3

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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      /* Base colors from request */
      --primary: #B4B4B8;
      --secondary: #C7C8CC;
      
      /* Additional colors */
      --accent: #6C63FF;
      --accent-light: #8A82FF;
      --accent-dark: #5A52D3;
      --complement: #FF6584;
      --complement-light: #FF85A0;
      --dark: #2D2D2D;
      --light: #F8F8F8;
      --white: #FFFFFF;
      --shadow: rgba(0, 0, 0, 0.1);
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #F8F8F8 0%, #EFEFEF 100%);
      color: var(--dark);
      line-height: 1.6;
      min-height: 100vh;
      padding: 30px 20px;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .header {
      text-align: center;
      margin-bottom: 50px;
      position: relative;
    }
    
    h1 {
      font-size: 3rem;
      font-weight: 700;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 15px;
      position: relative;
      display: inline-block;
    }
    
    h1::after {
      content: "";
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 5px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      border-radius: 3px;
    }
    
    .subtitle {
      color: var(--secondary);
      font-weight: 400;
      font-size: 1.2rem;
    }
    
    .categories {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 30px;
      margin-bottom: 60px;
    }
    
    .category-card {
      background: var(--white);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 15px 35px var(--shadow);
      transition: all 0.4s ease;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      border: 1px solid rgba(199, 200, 204, 0.2);
    }
    
    .category-card::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 8px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      transform: scaleX(0);
      transform-origin: left;
      transition: transform 0.4s ease;
    }
    
    .category-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(108, 99, 255, 0.15);
    }
    
    .category-card:hover::before {
      transform: scaleX(1);
    }
    
    .category-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }
    
    .category-title {
      font-size: 1.6rem;
      font-weight: 600;
      color: var(--dark);
      display: flex;
      align-items: center;
    }
    
    .category-icon {
      width: 60px;
      height: 60px;
      border-radius: 16px;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 18px;
      color: var(--white);
      font-size: 1.6rem;
      box-shadow: 0 8px 20px rgba(108, 99, 255, 0.2);
    }
    
    .toggle-icon {
      color: var(--secondary);
      font-size: 1.4rem;
      transition: transform 0.4s ease;
    }
    
    .category-card.active .toggle-icon {
      transform: rotate(180deg);
    }
    
    .subcategory-list {
      list-style: none;
      margin-top: 25px;
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.5s ease;
    }
    
    .category-card.active .subcategory-list {
      max-height: 600px;
    }
    
    .subcategory-item {
      padding: 18px 22px;
      margin-bottom: 15px;
      background: linear-gradient(135deg, var(--light), #FFFFFF);
      border-radius: 14px;
      font-size: 1.1rem;
      color: var(--dark);
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: all 0.3s ease;
      border-left: 5px solid transparent;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .subcategory-item:hover {
      background: linear-gradient(135deg, var(--accent-light), var(--accent));
      color: var(--white);
      transform: translateX(8px);
      border-left-color: var(--complement);
      box-shadow: 0 8px 25px rgba(108, 99, 255, 0.25);
    }
    
    .subcategory-name {
      font-weight: 500;
      display: flex;
      align-items: center;
    }
    
    .subcategory-name::before {
      content: "•";
      color: var(--accent);
      font-weight: bold;
      margin-right: 12px;
      font-size: 1.4rem;
    }
    
    .quantity-badge {
      background: linear-gradient(135deg, var(--complement), var(--complement-light));
      color: var(--white);
      font-weight: 600;
      min-width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      box-shadow: 0 5px 15px rgba(255, 101, 132, 0.3);
    }
    
    .back-button {
      position: fixed;
      bottom: 40px;
      right: 40px;
      background: linear-gradient(135deg, var(--accent), var(--accent-dark));
      color: var(--white);
      font-size: 1.1rem;
      font-weight: 500;
      padding: 16px 28px;
      border-radius: 50px;
      text-decoration: none;
      box-shadow: 0 10px 30px rgba(108, 99, 255, 0.3);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      z-index: 1000;
    }
    
    .back-button:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(108, 99, 255, 0.4);
      background: linear-gradient(135deg, var(--accent-light), var(--accent));
    }
    
    .back-button i {
      margin-right: 12px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      body {
        padding: 20px 15px;
      }
      
      h1 {
        font-size: 2.5rem;
      }
      
      .categories {
        grid-template-columns: 1fr;
        gap: 25px;
      }
      
      .category-card {
        padding: 25px;
      }
      
      .category-icon {
        width: 50px;
        height: 50px;
        font-size: 1.4rem;
      }
      
      .back-button {
        bottom: 20px;
        right: 20px;
        padding: 14px 24px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Category Tracking</h1>
      <p class="subtitle">Inventory Overview by Category</p>
    </div>
    
    <div class="categories">
      <?php foreach ($categories as $category => $subcategories): ?>
        <div class="category-card" onclick="toggleCard(this)">
          <div class="category-header">
            <div class="category-title">
              <div class="category-icon">
                <i class="fas fa-layer-group"></i>
              </div>
              <?= htmlspecialchars($category) ?>
            </div>
            <i class="toggle-icon fas fa-chevron-down"></i>
          </div>
          <ul class="subcategory-list">
            <?php foreach ($subcategories as $sub): 
              $qty = $totals[$sub] ?? 0;
            ?>
              <li class="subcategory-item">
                <span class="subcategory-name"><?= htmlspecialchars($sub) ?></span>
                <span class="quantity-badge"><?= $qty ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  
  <a href="history.php" class="back-button">
    <i class="fas fa-arrow-left"></i> Back to History
  </a>
  
  <script>
    function toggleCard(card) {
      // Close all other cards
      document.querySelectorAll('.category-card').forEach(c => {
        if (c !== card) c.classList.remove('active');
      });
      
      // Toggle current card
      card.classList.toggle('active');
    }
  </script>
</body>
</html>

<<<<<<< HEAD
<?php $conn->close(); ?>

=======
<?php $conn->close(); ?>
>>>>>>> 303baf17d177401e3c3fcbc4d3a65964f486a7c3
