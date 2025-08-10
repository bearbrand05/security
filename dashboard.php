<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="container-fluid">
        <div class="row">
            
            <!-- Main Content -->
            <main class="col-md-10 p-4">
                <h2 class="mb-4 fw-bold">Dashboard</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="info-card shadow-sm">
                            <h6>Shipped/Stocks</h6>
                            <h3 class="fw">350/10000</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card shadow-sm">
                            <h6>Pending order</h6>
                            <h3 class="fw">6,431</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card shadow-sm">
                            <h6>Stocks 10000</h6>
                            <p class="mb-1">Item 1 | 4352</p>
                            <p class="mb-1">Item 2 | 1233</p>
                            <p class="mb-0">Item 3 | 4235</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card shadow-sm">
                            <h6>Demand item</h6>
                            <h4 class="fw">item 1</h4>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="info-card shadow-sm">
                            <h6>Items Delivered On Time</h6>
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <canvas id="donutChart"></canvas>
                                </div>
                                <div class="col-6">
                                    <ul class="list-unstyled mb-0">
                                        <li><span class="dot bg-dark me-2"></span>On Time - 77.4%</li>
                                        <li><span class="dot bg-primary me-2"></span>Late - 22.6%</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap Icons (optional) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('donutChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['On Time', 'Late'],
                datasets: [{
                    data: [77.4, 22.6],
                    backgroundColor: ['#000', '#80bfff'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
</body>

<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> 303baf17d177401e3c3fcbc4d3a65964f486a7c3
