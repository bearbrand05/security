<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Category Tracking</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
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

    .category-card {
      background-color: #fff;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.07);
      transition: transform 0.2s ease;
    }

    .category-card:hover {
      transform: translateY(-4px);
    }

    .category-title {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 12px;
      color: #bc85a3;
    }

    .subcategory-list {
      padding-left: 20px;
    }

    .subcategory-item {
      font-size: 16px;
      margin-bottom: 6px;
      color: #4a7ba6;
    }

    .subcategory-item::before {
      content: "↳ ";
      color: #9799ba;
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

  <h1>Category Tracking</h1>

  <div class="category-card">
    <div class="category-title">Electronics</div>
    <ul class="subcategory-list">
      <li class="subcategory-item">Appliances</li>
      <li class="subcategory-item">Gadgets</li>
      <li class="subcategory-item">Accessories</li>
    </ul>
  </div>

  <div class="category-card">
    <div class="category-title">Fashion</div>
    <ul class="subcategory-list">
      <li class="subcategory-item">Clothes</li>
      <li class="subcategory-item">Shoes</li>
      <li class="subcategory-item">Bags</li>
    </ul>
  </div>

  <div class="category-card">
    <div class="category-title">Home & Living</div>
    <ul class="subcategory-list">
      <li class="subcategory-item">Furniture</li>
      <li class="subcategory-item">Decor</li>
    </ul>
  </div>

  <div class="category-card">
    <div class="category-title">Beauty & Health</div>
    <ul class="subcategory-list">
      <li class="subcategory-item">Skincare</li>
      <li class="subcategory-item">Supplements</li>
    </ul>
  </div>

  <a href="history.php" class="back-button" title="Go Back">&#8592; Back</a>


</body>
</html>
