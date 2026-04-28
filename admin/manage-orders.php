<?php
include 'includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
    $message = "Order #$order_id status updated to $status!";
}

$orders = $pdo->query("SELECT o.*, u.name as user_name, u.email as user_email FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetchAll();
?>

<h2 class="fw-bold mb-4">Manage Orders</h2>

<?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="px-4">Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="px-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td class="px-4 fw-bold">#<?php echo $o['id']; ?></td>
                        <td>
                            <div class="fw-bold"><?php echo $o['user_name']; ?></div>
                            <small class="text-muted"><?php echo $o['user_email']; ?></small>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($o['created_at'])); ?></td>
                        <td class="fw-bold">$<?php echo number_format($o['total_price'], 2); ?></td>
                        <td>
                            <form action="manage-orders.php" method="POST" class="d-inline">
                                <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                                <select name="status" class="form-select form-select-sm rounded-pill d-inline-block w-auto" onchange="this.form.submit()">
                                    <option value="pending" <?php echo $o['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="completed" <?php echo $o['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo $o['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td class="px-4 text-end">
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#orderModal<?php echo $o['id']; ?>">
                                View Items
                            </button>
                        </td>
                    </tr>

                    <!-- Order Items Modal -->
                    <div class="modal fade" id="orderModal<?php echo $o['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-0 bg-light">
                                    <h5 class="modal-title fw-bold">Order Items #<?php echo $o['id']; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <?php
                                    $item_stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                                    $item_stmt->execute([$o['id']]);
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
                                                        <td><?php echo $item['name']; ?></td>
                                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                                        <td><?php echo $item['quantity']; ?></td>
                                                        <td class="text-end">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr class="border-top">
                                                    <td colspan="3" class="text-end fw-bold pt-3">Total:</td>
                                                    <td class="text-end fw-bold text-primary pt-3">$<?php echo number_format($o['total_price'], 2); ?></td>
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

<?php include 'includes/footer.php'; ?>
