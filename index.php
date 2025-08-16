<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: Arial, sans-serif;
    }

    .d-flex {
      height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 220px;
      background-color: #343a40;
      color: white;
    }

    .admin-bar {
      padding: 1rem;
      border-bottom: 1px solid #495057;
      text-align: center;
    }

    .admin-name {
      font-weight: bold;
      margin-top: 5px;
    }

    /* Sidebar links */
    .sidebar .nav {
      padding: 10px;
    }

    .sidebar .nav-link {
      color: #adb5bd;
      padding: 10px 15px;
      margin-bottom: 5px;
      border-radius: 4px;
      text-decoration: none;
      display: flex;
      align-items: center;
    }

    .sidebar .nav-link:hover {
      background-color: #495057;
      color: white;
    }

    .sidebar .nav-link.active {
      background-color: #007bff;
      color: white;
    }

    .sidebar .nav-link i {
      margin-right: 10px;
      font-size: 1.1rem;
    }

    /* Main content */
    .content {
      flex-grow: 1;
      background-color: #f8f9fa;
      padding: 20px;
    }

    iframe {
      width: 100%;
      height: 100%;
      border: none;
      background-color: white;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="admin-bar">
        <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
        <div class="admin-name">ADMIN</div>
      </div>
      <ul class="nav flex-column" id="sidebarMenu">
        <li class="nav-item">
          <a href="dashboard.php" data-page="dashboard.php" target="contentFrame" class="nav-link">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a href="supply.php" data-page="supply.php" target="contentFrame" class="nav-link">
            <i class="bi bi-box-seam"></i> Supply & Order
          </a>
        </li>
        <li class="nav-item">
          <a href="inventory.php" data-page="inventory.php" target="contentFrame" class="nav-link">
            <i class="bi bi-archive"></i> Inventory
          </a>
        </li>
        <li class="nav-item">
          <a href="logistic.php" data-page="logistic.php" target="contentFrame" class="nav-link">
            <i class="bi bi-truck"></i> Logistics
          </a>
        </li>
        <li class="nav-item">
          <a href="history.php" data-page="history.php" target="contentFrame" class="nav-link">
            <i class="bi bi-clock-history"></i> History
          </a>
        </li>
      </ul>
    </div>

    <!-- Main Content Area -->
    <div class="content">
      <iframe id="contentFrame" name="contentFrame" src="dashboard.php"></iframe>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const links = document.querySelectorAll('#sidebarMenu .nav-link');
      const iframe = document.getElementById('contentFrame');

      function setActiveLink(page) {
        links.forEach(link => {
          link.classList.toggle('active', link.getAttribute('data-page') === page);
        });
      }

      // Set initial active link based on iframe src
      setActiveLink(iframe.src.split('/').pop());

      links.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const page = this.getAttribute('data-page');
          iframe.src = page;
          setActiveLink(page);
        });
      });
    });
  </script>
</body>
</html>
