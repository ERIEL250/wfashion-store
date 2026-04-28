<?php
require_once 'includes/config.php';
include 'includes/header.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: shop.php');
    exit;
}

// Fetch related products
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? LIMIT 4");
$stmt->execute([$product['category_id'], $product_id]);
$related_products = $stmt->fetchAll();
?>

<div class="container mb-5">
    <div class="row g-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm overflow-hidden rounded-3">
                <img src="<?php echo BASE_URL . UPLOAD_DIR . ($product['image'] ?: 'no-image.jpg'); ?>" class="img-fluid" alt="<?php echo $product['name']; ?>">
            </div>
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
                    <li class="breadcrumb-item active"><?php echo $product['category_name']; ?></li>
                </ol>
            </nav>
            <h1 class="display-5 fw-bold mb-3"><?php echo $product['name']; ?></h1>
            <p class="h3 text-primary fw-bold mb-4">$<?php echo number_format($product['price'], 2); ?></p>
            <p class="text-muted mb-5 lead"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            
            <form action="cart.php" method="POST" class="row g-3 align-items-center">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <div class="col-auto">
                    <label class="fw-bold me-2">Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" class="form-control text-center" style="width: 80px;">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
                        <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                    </button>
                </div>
            </form>
            
            <hr class="my-5">
            <div class="d-flex align-items-center">
                <span class="me-3 fw-bold">Share:</span>
                <a href="#" class="text-dark me-3 fs-5"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-dark me-3 fs-5"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-dark me-3 fs-5"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
<?php if ($related_products): ?>
<section class="container mb-5">
    <h3 class="fw-bold mb-4">Related Products</h3>
    <div class="row g-4">
        <?php foreach ($related_products as $rel): ?>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative overflow-hidden">
                        <img src="<?php echo BASE_URL . UPLOAD_DIR . ($rel['image'] ?: 'no-image.jpg'); ?>" class="card-img-top" alt="<?php echo $rel['name']; ?>" style="height: 250px; object-fit: cover;">
                        <div class="product-overlay">
                            <a href="product.php?id=<?php echo $rel['id']; ?>" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm">View Details</a>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title mb-2"><?php echo $rel['name']; ?></h5>
                        <p class="text-primary fw-bold mb-0">$<?php echo number_format($rel['price'], 2); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

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
