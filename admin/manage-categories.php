<?php
include 'includes/header.php';

$message = '';

// Handle Category Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $name = trim($_POST['name']);
        if (!empty($name)) {
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);
            $message = "Category added successfully!";
        }
    }
    
    if (isset($_POST['delete_category'])) {
        $id = (int)$_POST['category_id'];
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Category deleted successfully!";
    }

    if (isset($_POST['update_category'])) {
        $id = (int)$_POST['category_id'];
        $name = trim($_POST['name']);
        if (!empty($name)) {
            $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
            $message = "Category updated successfully!";
        }
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Manage Categories</h2>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fas fa-plus me-2"></i> Add Category
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
                    <th class="px-4" style="width: 100px;">ID</th>
                    <th>Category Name</th>
                    <th class="px-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td class="px-4 text-muted">#<?php echo $cat['id']; ?></td>
                        <td><span class="fw-bold"><?php echo $cat['name']; ?></span></td>
                        <td class="px-4 text-end">
                            <button class="btn btn-sm btn-outline-info rounded-pill px-3 me-2" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $cat['id']; ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="manage-categories.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure? This may affect products in this category.')">
                                <input type="hidden" name="category_id" value="<?php echo $cat['id']; ?>">
                                <button type="submit" name="delete_category" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?php echo $cat['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content border-0 shadow">
                                <form action="manage-categories.php" method="POST">
                                    <div class="modal-header border-0 bg-light">
                                        <h5 class="modal-title fw-bold">Edit Category</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <input type="hidden" name="category_id" value="<?php echo $cat['id']; ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Category Name</label>
                                            <input type="text" name="name" class="form-control rounded-pill" value="<?php echo $cat['name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" name="update_category" class="btn btn-primary rounded-pill px-4">Update Category</button>
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

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="manage-categories.php" method="POST">
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control rounded-pill" placeholder="e.g. Dresses" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_category" class="btn btn-primary rounded-pill px-4">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
