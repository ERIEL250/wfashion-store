<?php
include 'includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = (int)$_POST['user_id'];
    // Don't allow deleting self
    if ($user_id != $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $message = "User deleted successfully!";
    } else {
        $message = "You cannot delete your own account!";
    }
}

$users = $pdo->query("SELECT * FROM users ORDER BY role ASC, id DESC")->fetchAll();
?>

<h2 class="fw-bold mb-4">Manage Users</h2>

<?php if ($message): ?>
    <div class="alert alert-info alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="px-4">ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined Date</th>
                    <th class="px-4 text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td class="px-4 text-muted">#<?php echo $u['id']; ?></td>
                        <td class="fw-bold"><?php echo $u['name']; ?></td>
                        <td><?php echo $u['email']; ?></td>
                        <td>
                            <span class="badge bg-<?php echo $u['role'] == 'admin' ? 'danger' : 'secondary'; ?>">
                                <?php echo ucfirst($u['role']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                        <td class="px-4 text-end">
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <form action="manage-users.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                    <button type="submit" name="delete_user" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted small">Current User</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
