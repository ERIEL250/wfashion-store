<?php
require_once 'includes/config.php';

// Action handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }

    $action = $_POST['action'] ?? '';
    $user_id = $_SESSION['user_id'];

    if ($action === 'add') {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)($_POST['quantity'] ?? 1);

        // Check if item already in cart
        $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existing = $stmt->fetch();

        if ($existing) {
            $new_quantity = $existing['quantity'] + $quantity;
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt->execute([$new_quantity, $existing['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $product_id, $quantity]);
        }
        header('Location: cart.php');
        exit;
    }

    if ($action === 'update') {
        $cart_id = (int)$_POST['cart_id'];
        $quantity = (int)$_POST['quantity'];
        
        if ($quantity > 0) {
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$quantity, $cart_id, $user_id]);
        } else {
            $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
            $stmt->execute([$cart_id, $user_id]);
        }
        header('Location: cart.php');
        exit;
    }

    if ($action === 'remove') {
        $cart_id = (int)$_POST['cart_id'];
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->execute([$cart_id, $user_id]);
        header('Location: cart.php');
        exit;
    }
}

include 'includes/header.php';

if (!isLoggedIn()) {
    echo '<div class="container text-center py-5">
            <h2 class="mb-4">Please login to view your cart</h2>
            <a href="login.php" class="btn btn-primary rounded-pill px-4">Login Now</a>
          </div>';
    include 'includes/footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT c.id as cart_id, c.quantity, p.* FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll();

$total_price = 0;
?>

<div class="container mb-5">
    <h2 class="fw-bold mb-4">Shopping Cart</h2>
    
    <?php if ($cart_items): ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm overflow-hidden mb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4">Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th class="px-4 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): 
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total_price += $subtotal;
                                ?>
                                    <tr>
                                        <td class="px-4">
                                            <div class="d-flex align-items-center py-2">
                                                <img src="<?php echo BASE_URL . UPLOAD_DIR . ($item['image'] ?: 'no-image.jpg'); ?>" alt="" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                <div class="ms-3">
                                                    <h6 class="mb-0 fw-bold"><?php echo $item['name']; ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td>
                                            <form action="cart.php" method="POST" class="d-flex align-items-center" style="width: 120px;">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control form-control-sm text-center me-2" onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                                        <td class="px-4 text-end">
                                            <form action="cart.php" method="POST">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <span class="fw-bold">$<?php echo number_format($total_price, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping</span>
                            <span class="text-success">Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 fw-bold">Total</span>
                            <span class="h5 fw-bold text-primary">$<?php echo number_format($total_price, 2); ?></span>
                        </div>
                        <div class="d-grid">
                            <a href="checkout.php" class="btn btn-primary btn-lg rounded-pill">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Your cart is empty</h4>
            <a href="shop.php" class="btn btn-primary rounded-pill px-4 mt-3">Go Shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
