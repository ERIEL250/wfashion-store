<?php
require_once '../includes/config.php';

if (!isAdmin()) {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Fashion Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <style>
        :root {
            --primary-color: #ff4d6d;
            --sidebar-width: 250px;
        }
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #2b2d42;
            color: white;
            padding-top: 20px;
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 25px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left: 4px solid var(--primary-color);
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 40px;
        }
        .card-stats {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s;
        }
        .card-stats:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="px-4 mb-4">
        <h4 class="fw-bold text-primary">ADMIN PANEL</h4>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-products.php' ? 'active' : ''; ?>" href="manage-products.php">
            <i class="fas fa-box"></i> Products
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-categories.php' ? 'active' : ''; ?>" href="manage-categories.php">
            <i class="fas fa-tags"></i> Categories
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-orders.php' ? 'active' : ''; ?>" href="manage-orders.php">
            <i class="fas fa-shopping-cart"></i> Orders
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-users.php' ? 'active' : ''; ?>" href="manage-users.php">
            <i class="fas fa-users"></i> Users
        </a>
        <hr class="mx-3 my-3 border-secondary">
        <a class="nav-link" href="../index.php">
            <i class="fas fa-eye"></i> View Store
        </a>
        <a class="nav-link text-danger" href="../logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </nav>
</div>

<div class="main-content">
