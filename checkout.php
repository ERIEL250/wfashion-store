<?php
require_once 'includes/config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items to calculate total
$stmt = $pdo->prepare("SELECT c.quantity, p.price, p.id as product_id FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

if (!$cart_items) {
    header('Location: shop.php');
    exit;
}

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start transaction
    $pdo->beginTransaction();
    try {
        // 1. Create order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'completed')");
        $stmt->execute([$user_id, $total_price]);
        $order_id = $pdo->lastInsertId();

        // 2. Add order items
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cart_items as $item) {
            $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
        }

        // 3. Clear cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);

        $pdo->commit();
        header("Location: order_confirmation.php?id=$order_id");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Checkout failed: " . $e->getMessage();
    }
}

include 'includes/header.php';
?>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="fw-bold mb-4">Checkout</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Shipping Details</h5>
                            <form id="checkoutForm" action="checkout.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control rounded-pill" value="<?php echo $_SESSION['user_name']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" rows="3" placeholder="Street address, apartment, etc." required></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control rounded-pill" required>
                                    </div>
                            
                                </div>
                                
                                <h5 class="fw-bold my-4">Payment Method</h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment" id="cod" checked>
                                    <label class="form-check-label" for="cod">Cash on Delivery</label>
                                </div>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="radio" name="payment" id="card" disabled>
                                    <label class="form-check-label text-muted" for="card">Credit Card (Coming Soon)</label>
                                </div>
                                
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill">Place Order</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Order Summary</h5>
                            <?php foreach ($cart_items as $item): 
                                // Fetch product name again for summary
                                $ps = $pdo->prepare("SELECT name FROM products WHERE id = ?");
                                $ps->execute([$item['product_id']]);
                                $pname = $ps->fetchColumn();
                            ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small"><?php echo $pname; ?> x <?php echo $item['quantity']; ?></span>
                                    <span class="small">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                </div>
                            <?php endforeach; ?>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>$<?php echo number_format($total_price, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="h5 fw-bold">Total</span>
                                <span class="h5 fw-bold text-primary">$<?php echo number_format($total_price, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
