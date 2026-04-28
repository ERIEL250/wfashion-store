<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Women Fashion Store</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        :root {
            --primary-color: #ff4d6d;
            --secondary-color: #2b2d42;
            --accent-color: #8d99ae;
            --light-bg: #f8f9fa;
        }
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--secondary-color);
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: #ff3355;
            border-color: #ff3355;
        }
        .nav-link {
            font-weight: 500;
            color: var(--secondary-color) !important;
        }
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        .cart-badge {
            background-color: var(--primary-color);
            font-size: 0.7rem;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php">FASHION<span class="text-dark">STORE</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>shop.php">Shop</a></li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>wishlist.php">
                        <i class="far fa-heart fs-5"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative" href="<?php echo BASE_URL; ?>cart.php">
                        <i class="fas fa-shopping-bag fs-5"></i>
                        <?php
                        $cart_count = 0;
                        if (isLoggedIn()) {
                            $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
                            $stmt->execute([$_SESSION['user_id']]);
                            $cart_count = $stmt->fetchColumn() ?: 0;
                        }
                        if ($cart_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill cart-badge">
                                <?php echo $cart_count; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fs-5"></i> <?php echo explode(' ', $_SESSION['user_name'])[0]; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (isAdmin()): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/dashboard.php">Admin Panel</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>order_history.php">My Orders</a></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary btn-sm ms-lg-2 rounded-pill px-4" href="<?php echo BASE_URL; ?>register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="py-4">
