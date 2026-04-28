<?php
include 'includes/header.php';

$message = '';

// Handle Product Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $name = trim($_POST['name']);
        $price = (float)$_POST['price'];
        $description = trim($_POST['description']);
        $category_id = (int)$_POST['category_id'];
        
        $image_name = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_name = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], '../' . UPLOAD_DIR . $image_name);
        }

        $stmt = $pdo->prepare("INSERT INTO products (name, price, description, image, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $price, $description, $image_name, $category_id]);
        $message = "Product added successfully!";
    }
    
    if (isset($_POST['update_product'])) {
        $id = (int)$_POST['product_id'];
        $name = trim($_POST['name']);
        $price = (float)$_POST['price'];
        $description = trim($_POST['description']);
        $category_id = (int)$_POST['category_id'];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_name = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], '../' . UPLOAD_DIR . $image_name);
            $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, description=?, image=?, category_id=? WHERE id=?");
            $stmt->execute([$name, $price, $description, $image_name, $category_id, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, description=?, category_id=? WHERE id=?");
            $stmt->execute([$name, $price, $description, $category_id, $id]);
        }
        $message = "Product updated successfully!";
    }

    if (isset($_POST['delete_product'])) {
        $id = (int)$_POST['product_id'];
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Product deleted successfully!";
    }
}

$products = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Manage Products</h2>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fas fa-plus me-2"></i> Add Product
    </button>
</div>

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
                    <th class="px-4">Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th class="px-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td class="px-4">
                            <div class="d-flex align-items-center">
                                <img src="../<?php echo UPLOAD_DIR . ($p['image'] ?: 'no-image.jpg'); ?>" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0 fw-bold"><?php echo $p['name']; ?></h6>
                                    <small class="text-muted">ID: #<?php echo $p['id']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?php echo $p['category_name']; ?></span></td>
                        <td class="fw-bold text-primary">$<?php echo number_format($p['price'], 2); ?></td>
                        <td class="px-4 text-end">
                            <button class="btn btn-sm btn-outline-info rounded-pill px-3 me-2" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $p['id']; ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="manage-products.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                <button type="submit" name="delete_product" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?php echo $p['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content border-0 shadow">
                                <form action="manage-products.php" method="POST" enctype="multipart/form-data">
                                    <div class="modal-header border-0 bg-light">
                                        <h5 class="modal-title fw-bold">Edit Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Product Name</label>
                                                <input type="text" name="name" class="form-control rounded-pill" value="<?php echo $p['name']; ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Price ($)</label>
                                                <input type="number" name="price" step="0.01" class="form-control rounded-pill" value="<?php echo $p['price']; ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Category</label>
                                                <select name="category_id" class="form-select rounded-pill" required>
                                                    <?php foreach ($categories as $cat): ?>
                                                        <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $p['category_id'] ? 'selected' : ''; ?>>
                                                            <?php echo $cat['name']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Product Image</label>
                                                <input type="file" name="image" class="form-control rounded-pill">
                                                <small class="text-muted">Leave empty to keep current image</small>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="4" required><?php echo $p['description']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" name="update_product" class="btn btn-primary rounded-pill px-4">Update Product</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="manage-products.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control rounded-pill" placeholder="e.g. Floral Summer Dress" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price ($)</label>
                            <input type="number" name="price" step="0.01" class="form-control rounded-pill" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select rounded-pill" required>
                                <option value="" disabled selected>Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Image</label>
                            <input type="file" name="image" class="form-control rounded-pill" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Enter product details..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_product" class="btn btn-primary rounded-pill px-4">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
