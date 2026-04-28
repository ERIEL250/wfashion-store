<?php
require_once 'includes/config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container mb-5">
    <h2 class="fw-bold mb-4">My Orders</h2>
    
    <?php if ($orders): ?>
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">Order ID</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th class="px-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="px-4 fw-bold">#<?php echo $order['id']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                                <td>
                                    <?php if ($order['status'] === 'completed'): ?>
                                        <span class="badge bg-success">Completed</span>
                                    <?php elseif ($order['status'] === 'pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><?php echo ucfirst($order['status']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 text-end">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#orderModal<?php echo $order['id']; ?>">
                                        Details
                                    </button>
                                </td>
                            </tr>

                            <!-- Order Details Modal -->
                            <div class="modal fade" id="orderModal<?php echo $order['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-0 bg-light">
                                            <h5 class="modal-title fw-bold">Order Details #<?php echo $order['id']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <?php
                                            $item_stmt = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                                            $item_stmt->execute([$order['id']]);
                                            $items = $item_stmt->fetchAll();
                                            ?>
                                            <div class="table-responsive">
                                                <table class="table table-borderless">
                                                    <thead>
                                                        <tr class="text-muted small text-uppercase">
                                                            <th>Product</th>
                                                            <th>Price</th>
                                                            <th>Qty</th>
                                                            <th class="text-end">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($items as $item): ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="<?php echo BASE_URL . UPLOAD_DIR . ($item['image'] ?: 'no-image.jpg'); ?>" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                                        <span><?php echo $item['name']; ?></span>
                                                                    </div>
                                                                </td>
                                                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                                                <td><?php echo $item['quantity']; ?></td>
                                                                <td class="text-end">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="border-top">
                                                            <td colspan="3" class="text-end fw-bold pt-3">Total:</td>
                                                            <td class="text-end fw-bold text-primary pt-3">$<?php echo number_format($order['total_price'], 2); ?></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-history fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No orders found</h4>
            <a href="shop.php" class="btn btn-primary rounded-pill px-4 mt-3">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
