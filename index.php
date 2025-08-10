<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sidebar Iframe Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden;
      background-color: #E3E1D9;
    }

    .d-flex {
      height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background-color: #B4B4B8;
      border-right: 1px solid #aaa;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      padding: 1rem;
    }

    .admin-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: white;
      margin-bottom: 1rem;
    }

    .admin-bar .bar-decor {
      flex-grow: 1;
      height: 4px;
      background-color: #888;
      margin-left: 10px;
      border-radius: 2px;
    }

    /* Sidebar links */
    .sidebar a {
      display: block;
      padding: 10px 12px;
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      font-size: 1.05rem;
      transition: background-color 0.2s, color 0.2s, transform 0.2s;
      border-radius: 5px;
    }

    .sidebar a:hover {
      background-color: #A0A0A4;
      color: #fff;
      transform: scale(1.03);
    }

    .nav-link.active {
      margin-left: 10px;
      background-color: #C7C8CC;
      font-weight: bold;
      color: #000 !important;
    }

    .nav-link i {
      margin-right: 8px;
    }

    .content {
      flex-grow: 1;
    }

    iframe {
      width: 100%;
      height: 100vh;
      border: none;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="admin-bar">
        <span><i class="bi bi-person"></i> <strong>ADMIN</strong></span>
        
      </div>
      <ul class="nav flex-column" id="sidebarMenu">
        <li class="nav-item mb-2">
          <a href="dashboard.php" data-page="dashboard.php" target="contentFrame" class="nav-link">
            <i class="bi bi-grid"></i> Dashboard
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="supply.php" data-page="supply.php" target="contentFrame" class="nav-link">
            <i class="bi bi-box-seam"></i> Supply & Order
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="inventory.php" data-page="inventory.php" target="contentFrame" class="nav-link">
            <i class="bi bi-archive"></i> Inventory
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="logistic.php" data-page="logistics.php" target="contentFrame" class="nav-link">
            <i class="bi bi-cart3"></i> Logistics
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="history.php" data-page="history.php" target="contentFrame" class="nav-link">
            <i class="bi bi-person-lines-fill"></i> History
          </a>
        </li>
      </ul>
    </div>

    <!-- Main Content Area -->
    <div class="content">
      <iframe id="contentFrame" name="contentFrame" src=""></iframe>
    </div>
  </div>

  <!-- Script to handle active link and iframe load -->
  <script>
    const links = document.querySelectorAll('#sidebarMenu .nav-link');
    const iframe = document.getElementById('contentFrame');

    function setActiveLink(page) {
      links.forEach(link => {
        const linkPage = link.getAttribute('data-page');
        if (linkPage === page) {
          link.classList.add('active');
        } else {
          link.classList.remove('active');
        }
      });
    }

    const savedPage = localStorage.getItem('activePage') || 'dashboard.php';
    iframe.src = savedPage;
    setActiveLink(savedPage);

    links.forEach(link => {
      link.addEventListener('click', function () {
        const page = this.getAttribute('data-page');
        localStorage.setItem('activePage', page);
        setActiveLink(page);
      });
    });
  </script>
</body>
</html>