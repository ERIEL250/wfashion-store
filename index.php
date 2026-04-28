<?php
require_once 'includes/config.php';
include 'includes/header.php';

// Fetch featured products (latest 4)
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT 4");
$featured_products = $stmt->fetchAll();
?>

<!-- Hero Section -->
<section class="hero-section bg-light py-5 mb-5 rounded-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-3 fw-bold mb-3" style="font-family: 'Playfair Display', serif;">Discover Your <span class="text-primary">Style</span></h1>
                <p class="lead mb-4">Explore the latest trends in women's fashion. Quality, elegance, and comfort all in one place.</p>
                <a href="shop.php" class="btn btn-primary btn-lg rounded-pill px-5 py-3">Shop Now</a>
            </div>
            <div class="col-md-6 text-center">
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=800&q=80" alt="Fashion" class="img-fluid rounded-3 shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Featured Products</h2>
        <a href="shop.php" class="text-primary text-decoration-none fw-bold">View All <i class="fas fa-arrow-right ms-1"></i></a>
    </div>
    
    <div class="row g-4">
        <?php if ($featured_products): ?>
            <?php foreach ($featured_products as $product): ?>
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm product-card">
                        <div class="position-relative overflow-hidden">
                            <img src="<?php echo BASE_URL . UPLOAD_DIR . ($product['image'] ?: 'no-image.jpg'); ?>" class="card-img-top" alt="<?php echo $product['name']; ?>" style="height: 300px; object-fit: cover;">
                            <div class="product-overlay">
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm">View Details</a>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <p class="text-muted small mb-1"><?php echo $product['category_name']; ?></p>
                            <h5 class="card-title mb-2"><?php echo $product['name']; ?></h5>
                            <p class="text-primary fw-bold mb-0">$<?php echo number_format($product['price'], 2); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No products found. Add some from the admin panel!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Categories Section -->
<section class="bg-white py-5 mb-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-5">Shop By Category</h2>
        <div class="row g-4">
            <?php
            $categories = $pdo->query("SELECT * FROM categories LIMIT 4")->fetchAll();
            foreach ($categories as $cat):
            ?>
                <div class="col-md-3">
                    <a href="shop.php?category=<?php echo $cat['id']; ?>" class="text-decoration-none">
                        <div class="category-card p-4 rounded-3 bg-light transition">
                            <h5 class="mb-0 text-dark"><?php echo $cat['name']; ?></h5>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
    .product-card {
        transition: transform 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
    }
    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .product-card:hover .product-overlay {
        opacity: 1;
    }
    .category-card:hover {
        background-color: var(--primary-color) !important;
    }
    .category-card:hover h5 {
        color: white !important;
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>

<?php include 'includes/footer.php'; ?>
