<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sidebar Iframe Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-light p-3">
      <div class="mb-4">
        <i class="bi bi-person"></i> <span class="fw-bold">CLOTHING</span>
      </div>
      <ul class="nav flex-column" id="sidebarMenu">
        <li class="nav-item mb-2">
          <a href="dashboard.php" data-page="dashboard.php" target="contentFrame" class="nav-link text-dark">
            <i class="bi bi-grid"></i> Dashboard
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="supply.php" data-page="supply.php" target="contentFrame" class="nav-link text-dark">
            <i class="bi bi-box-seam"></i> Supply & Order
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="inventory.php" data-page="inventory.php" target="contentFrame" class="nav-link text-dark">
            <i class="bi bi-archive"></i> Inventory
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="logistic.php" data-page="logistics.php" target="contentFrame" class="nav-link text-dark">
            <i class="bi bi-cart3"></i> Logistics
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="history.php" data-page="history.php" target="contentFrame" class="nav-link text-dark">
            <i class="bi bi-person-lines-fill"></i> History
          </a>
        </li>
      </ul>
    </div>

    <!-- Content Area -->
    <div class="content p-4 w-100">
      <iframe id="contentFrame" name="contentFrame" src="" frameborder="0" style="width: 100%; height: 90vh;"></iframe>
    </div>
  </div>

  <!-- js ito ung pang highlight tas pang move --> 
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

    // On page load: load stored page or default to dashboard // dko to magets 
    const savedPage = localStorage.getItem('activePage') || 'dashboard.php';
    iframe.src = savedPage;
    setActiveLink(savedPage);

    // dito ung pag na click na
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