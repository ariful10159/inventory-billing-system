<?php
session_start();
require_once 'config.php';

// Login check
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Get business info
$business_query = "SELECT * FROM business_info WHERE id = 1";
$business_result = mysqli_query($conn, $business_query);
$business_info = mysqli_fetch_assoc($business_result);

// Dummy stats (replace with real queries as needed)
$total_products = 120;
$todays_sales = 15;
$monthly_sales = 320;
$total_income = 150000;
$out_of_stock = 3;
$low_stock = 7;

// Recent activities
$recent_activities = [
    "New product <b>Gazi Water Tank</b> added.",
    "Bill #1023 created for customer <b>Rahim</b>.",
    "Stock updated for <b>Holcim Cement</b>.",
    "New product <b>RFL Pipe</b> added.",
    "Stock updated for <b>Gazi Motor</b>."
];

// Notifications
$notifications = [
    "New Discount Offers available!",
    "Upcoming Holidays: Eid-ul-Adha (15 June)",
    "Stock alert: Some products are running low."
];

// Dummy sales data for chart (replace with real data)
$sales_chart_labels = json_encode(["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"]);
$sales_chart_data = json_encode([12000, 15000, 18000, 14000, 20000, 22000, 17000]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Business Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($user['owner_name'], 0, 1)); ?>
                </div>
                <div>
                    <div style="font-weight: 600;"><?php echo htmlspecialchars($user['owner_name']); ?></div>
                    <div style="color: #6c757d; font-size: 14px;">
                        <?php echo isset($user['role']) ? ucfirst($user['role']) : 'Owner'; ?>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="welcome-card">
            <div class="business-logo">
                <i class="fas fa-store"></i>
            </div>
            <div class="shop-photo-wrap">
                <img src="uploads/shop.jpg" alt="Shop Photo" class="shop-photo">
            </div>
            <h2>Welcome to <?php echo htmlspecialchars($business_info['shop_name'] ?? 'Your Business'); ?></h2>
            <p style="color: #6c757d;">Manage your business operations efficiently with our comprehensive system.</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-title">Total Users</div>
                <div class="stat-value">
                    <?php
                    $users_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
                    $users_data = mysqli_fetch_assoc($users_count);
                    echo $users_data['count'] ?? 0;
                    ?>
                </div>
                <div class="stat-desc">Registered users</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-title">Business Status</div>
                <div class="stat-value">Active</div>
                <div class="stat-desc">System operational</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stat-title">Today's Date</div>
                <div class="stat-value" style="font-size: 18px;"><?php echo date('M d'); ?></div>
                <div class="stat-desc"><?php echo date('Y'); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-title">Current Time</div>
                <div class="stat-value" style="font-size: 20px;" id="current-time"></div>
                <div class="stat-desc">Local time</div>
            </div>
        </div>

        <!-- Actions Grid -->
        <div class="actions-grid">
            <div class="action-card">
                <h3><i class="fas fa-store"></i> Business Management</h3>
                <div class="action-buttons">
                    <a href="business.php" class="action-btn btn-primary">
                        <i class="fas fa-edit"></i>
                        Edit Business Info
                    </a>
                    <a href="#" class="action-btn btn-success">
                        <i class="fas fa-chart-bar"></i>
                        View Reports
                    </a>
                    <a href="#" class="action-btn btn-info">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                </div>
            </div>

            <div class="action-card">
                <h3><i class="fas fa-users"></i> User Management</h3>
                <div class="action-buttons">
                    <?php if ($user['role'] === 'admin'): ?>
                    <a href="#" class="action-btn btn-primary">
                        <i class="fas fa-user-plus"></i>
                        Add New User
                    </a>
                    <a href="#" class="action-btn btn-warning">
                        <i class="fas fa-users-cog"></i>
                        Manage Users
                    </a>
                    <?php endif; ?>
                    <a href="#" class="action-btn btn-info">
                        <i class="fas fa-user-edit"></i>
                        Edit Profile
                    </a>
                </div>
            </div>

            <div class="action-card">
                <h3><i class="fas fa-box"></i> Inventory & Sales</h3>
                <div class="action-buttons">
                    <a href="add-product.php" class="action-btn btn-success">
                        <i class="fas fa-plus-circle"></i>
                        Add Products
                    </a>
                    <a href="#" class="action-btn btn-warning">
                        <i class="fas fa-shopping-cart"></i>
                        Manage Sales
                    </a>
                    <a href="#" class="action-btn btn-info">
                        <i class="fas fa-warehouse"></i>
                        Stock Management
                    </a>
                </div>
            </div>

            <div class="action-card">
                <h3><i class="fas fa-chart-pie"></i> Reports & Analytics</h3>
                <div class="action-buttons">
                    <a href="#" class="action-btn btn-primary">
                        <i class="fas fa-file-alt"></i>
                        Sales Reports
                    </a>
                    <a href="#" class="action-btn btn-success">
                        <i class="fas fa-money-bill"></i>
                        Financial Reports
                    </a>
                    <a href="#" class="action-btn btn-warning">
                        <i class="fas fa-download"></i>
                        Export Data
                    </a>
                </div>
            </div>
        </div>

        <!-- Activity Feed & Notifications -->
        <div class="dashboard-content">
            <div class="activity-feed">
                <h3><i class="fas fa-history"></i> Recent Activity</h3>
                <ul>
                    <?php foreach ($recent_activities as $activity): ?>
                        <li><?php echo $activity; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="notifications">
                <h3><i class="fas fa-bell"></i> Notifications</h3>
                <ul>
                    <?php foreach ($notifications as $note): ?>
                        <li><?php echo $note; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="analytics-graph">
                <h3><i class="fas fa-chart-bar"></i> Sales Trend</h3>
                <canvas id="salesChart" width="350" height="200"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            document.getElementById('current-time').textContent = timeString;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Chart.js for sales trend
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo $sales_chart_labels; ?>,
                datasets: [{
                    label: 'Sales (৳)',
                    data: <?php echo $sales_chart_data; ?>,
                    backgroundColor: 'rgba(102, 126, 234, 0.7)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Add some interactivity
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>