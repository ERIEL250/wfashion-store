<?php
require_once 'includes/config.php';
include 'includes/header.php';

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$query = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
$params = [];

if ($category_id > 0) {
    $query .= " AND p.category_id = ?";
    $params[] = $category_id;
}

if (!empty($search)) {
    $query .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$query .= " ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<div class="container mb-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Search</h5>
                    <form action="shop.php" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Categories</h5>
                    <div class="list-group list-group-flush">
                        <a href="shop.php" class="list-group-item list-group-item-action border-0 px-0 <?php echo $category_id == 0 ? 'text-primary fw-bold' : ''; ?>">All Categories</a>
                        <?php foreach ($categories as $cat): ?>
                            <a href="shop.php?category=<?php echo $cat['id']; ?>" class="list-group-item list-group-item-action border-0 px-0 <?php echo $category_id == $cat['id'] ? 'text-primary fw-bold' : ''; ?>">
                                <?php echo $cat['name']; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Our Shop</h2>
                <p class="text-muted mb-0"><?php echo count($products); ?> Products found</p>
            </div>

            <div class="row g-4">
                <?php if ($products): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm product-card">
                                <div class="position-relative overflow-hidden">
                                    <img src="<?php echo BASE_URL . UPLOAD_DIR . ($product['image'] ?: 'no-image.jpg'); ?>" class="card-img-top" alt="<?php echo $product['name']; ?>" style="height: 250px; object-fit: cover;">
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
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No products found matching your criteria.</p>
                        <a href="shop.php" class="btn btn-primary rounded-pill px-4">Clear Filters</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
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
