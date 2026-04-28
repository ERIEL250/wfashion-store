<?php
include 'includes/header.php';

// Fetch stats
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(total_price) FROM orders WHERE status = 'completed'")->fetchColumn() ?: 0;

// Fetch recent orders
$recent_orders = $pdo->query("SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Dashboard Summary</h2>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card card-stats shadow-sm bg-white p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                    <i class="fas fa-box text-primary fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Products</h6>
                    <h3 class="fw-bold mb-0"><?php echo $total_products; ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats shadow-sm bg-white p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                    <i class="fas fa-shopping-cart text-success fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Orders</h6>
                    <h3 class="fw-bold mb-0"><?php echo $total_orders; ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats shadow-sm bg-white p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                    <i class="fas fa-users text-info fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Customers</h6>
                    <h3 class="fw-bold mb-0"><?php echo $total_users; ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats shadow-sm bg-white p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                    <i class="fas fa-dollar-sign text-warning fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Revenue</h6>
                    <h3 class="fw-bold mb-0">$<?php echo number_format($total_revenue, 2); ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Recent Orders</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4">Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="px-4 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td class="px-4">#<?php echo $order['id']; ?></td>
                                    <td><?php echo $order['user_name']; ?></td>
                                    <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $order['status'] == 'completed' ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 text-end">
                                        <a href="manage-orders.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="manage-products.php" class="btn btn-primary rounded-pill"><i class="fas fa-plus me-2"></i> Add New Product</a>
                    <a href="manage-categories.php" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-tags me-2"></i> Manage Categories</a>
                    <a href="manage-users.php" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-users me-2"></i> View All Users</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
