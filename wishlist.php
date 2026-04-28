<?php
require_once 'includes/config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle Actions
if (isset($_GET['action'])) {
    $product_id = (int)$_GET['product_id'];
    
    if ($_GET['action'] === 'add') {
        $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $product_id]);
            $message = "Product added to wishlist!";
        } else {
            $message = "Product already in wishlist!";
        }
    }
    
    if ($_GET['action'] === 'remove') {
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $message = "Product removed from wishlist!";
    }
}

$stmt = $pdo->prepare("SELECT w.id as wishlist_id, p.*, c.name as category_name FROM wishlist w JOIN products p ON w.product_id = p.id LEFT JOIN categories c ON p.category_id = c.id WHERE w.user_id = ?");
$stmt->execute([$user_id]);
$wishlist_items = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container mb-5">
    <h2 class="fw-bold mb-4">My Wishlist</h2>
    
    <?php if ($message): ?>
        <div class="alert alert-info alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($wishlist_items): ?>
        <div class="row g-4">
            <?php foreach ($wishlist_items as $item): ?>
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm product-card">
                        <div class="position-relative overflow-hidden">
                            <img src="<?php echo BASE_URL . UPLOAD_DIR . ($item['image'] ?: 'no-image.jpg'); ?>" class="card-img-top" alt="<?php echo $item['name']; ?>" style="height: 250px; object-fit: cover;">
                            <div class="product-overlay">
                                <a href="product.php?id=<?php echo $item['id']; ?>" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm me-2">View</a>
                                <a href="wishlist.php?action=remove&product_id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">Remove</a>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <p class="text-muted small mb-1"><?php echo $item['category_name']; ?></p>
                            <h5 class="card-title mb-2"><?php echo $item['name']; ?></h5>
                            <p class="text-primary fw-bold mb-3">$<?php echo number_format($item['price'], 2); ?></p>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="far fa-heart fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Your wishlist is empty</h4>
            <a href="shop.php" class="btn btn-primary rounded-pill px-4 mt-3">Browse Products</a>
        </div>
    <?php endif; ?>
</div>

<style>
    .product-card { transition: transform 0.3s ease; }
    .product-card:hover { transform: translateY(-5px); }
    .product-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: opacity 0.3s ease;
    }
    .product-card:hover .product-overlay { opacity: 1; }
</style>

<?php include 'includes/footer.php'; ?>
