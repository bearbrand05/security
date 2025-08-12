<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .dashboard-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .info-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            height: 100%;
            transition: transform 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-5px);
        }
        
        .info-card h6 {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .info-card h3, .info-card h4 {
            color: #212529;
            font-weight: 600;
        }
        
        .info-card p {
            color: #495057;
            margin-bottom: 5px;
        }
        
        .dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        
        .chart-container {
            position: relative;
            height: 200px;
            width: 200px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Main Content -->
            <main class="col-md-10 mx-auto">
                <div class="dashboard-header">
                    <h2 class="fw-bold"><i class="bi bi-speedometer2 me-2"></i>Inventory Dashboard</h2>
                    <p class="text-muted">Overview of your inventory and shipments</p>
                </div>
                
                <div class="row g-4">
                    <!-- Shipped/Stocks Card -->
                    <div class="col-md-4">
                        <div class="info-card shadow-sm">
                            <h6><i class="bi bi-truck me-2"></i>Shipped Items</h6>
                            <h3 class="fw-bold">350<small class="text-muted fs-6">/10,000</small></h3>
                            <p class="text-success mt-2"><i class="bi bi-arrow-up me-1"></i>12% from last month</p>
                        </div>
                    </div>
                    
                    <!-- Pending Orders Card -->
                    <div class="col-md-4">
                        <div class="info-card shadow-sm">
                            <h6><i class="bi bi-clock me-2"></i>Pending Orders</h6>
                            <h3 class="fw-bold">6,431</h3>
                            <p class="text-danger mt-2"><i class="bi bi-arrow-down me-1"></i>5% from last week</p>
                        </div>
                    </div>
                    
                    <!-- Stock Levels Card -->
                    <div class="col-md-4">
                        <div class="info-card shadow-sm">
                            <h6><i class="bi bi-box-seam me-2"></i>Stock Levels</h6>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-1"><span class="dot bg-primary me-2"></span>Item 1: 4,352</p>
                                    <p class="mb-1"><span class="dot bg-success me-2"></span>Item 2: 1,233</p>
                                    <p class="mb-0"><span class="dot bg-warning me-2"></span>Item 3: 4,235</p>
                                </div>
                                <h3 class="fw-bold">10,000</h3>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Top Demand Card -->
                    <div class="col-md-4">
                        <div class="info-card shadow-sm">
                            <h6><i class="bi bi-star me-2"></i>Top Demand Item</h6>
                            <div class="d-flex align-items-center mt-3">
                                <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                    <i class="bi bi-box-seam text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-0">Item 1</h4>
                                    <p class="text-muted mb-0">4352 units this month</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delivery Performance Card -->
                    <div class="col-md-8">
                        <div class="info-card shadow-sm">
                            <h6><i class="bi bi-check-circle me-2"></i>Delivery Performance</h6>
                            <div class="row align-items-center mt-3">
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <canvas id="donutChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><span class="dot bg-success me-2"></span>On Time - 77.4%</li>
                                        <li><span class="dot bg-danger me-2"></span>Late - 22.6%</li>
                                    </ul>
                                    <div class="mt-4">
                                        <p class="text-muted mb-1">Last month: 72.1% on time</p>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 77.4%" aria-valuenow="77.4" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Donut Chart
        const ctx = document.getElementById('donutChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['On Time', 'Late'],
                datasets: [{
                    data: [77.4, 22.6],
                    backgroundColor: ['#28a745', '#dc3545'],
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
</html>
