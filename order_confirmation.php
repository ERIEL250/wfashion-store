<?php
require_once 'includes/config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit;
}

include 'includes/header.php';
?>

<div class="container text-center py-5 mb-5">
    <div class="mb-4">
        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
    </div>
    <h1 class="fw-bold mb-3">Order Confirmed!</h1>
    <p class="lead text-muted mb-4">Thank you for your purchase. Your order #<?php echo $order_id; ?> has been placed successfully.</p>
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 500px;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Order Details</h5>
            <div class="d-flex justify-content-between mb-2">
                <span>Order ID:</span>
                <span class="fw-bold">#<?php echo $order_id; ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Total Amount:</span>
                <span class="fw-bold text-primary">$<?php echo number_format($order['total_price'], 2); ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Status:</span>
                <span class="badge bg-success">Completed</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Date:</span>
                <span><?php echo date('M d, Y', strtotime($order['created_at'])); ?></span>
            </div>
        </div>
    </div>
    <div class="mt-5">
        <a href="shop.php" class="btn btn-outline-primary rounded-pill px-4 me-3">Continue Shopping</a>
        <a href="order_history.php" class="btn btn-primary rounded-pill px-4">View My Orders</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
