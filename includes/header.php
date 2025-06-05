<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Endorse Phurutsi - For A Smarter, Stronger Council'; ?></title>
    <meta name="description" content="<?php echo $pageDescription ?? 'Vote for Mashitishi B. Phurutsi - Tech-driven educator and digital innovator transforming higher education'; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo $pageTitle ?? 'Endorse Phurutsi'; ?>">
    <meta property="og:description" content="<?php echo $pageDescription ?? 'For A Smarter, Stronger Council'; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
</head>
<body>
    <!-- Loading Screen -->
    <div id="loading-screen" class="loading-screen">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <h2 class="loading-text">Loading Content...</h2>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="index.php">
                    <span class="logo-text">Phurutsi</span>
                    <span class="logo-subtitle">For Council</span>
                </a>
            </div>
            
            <div class="nav-menu" id="nav-menu">
                <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="about.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user"></i> About
                </a>
                <a href="endorse.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'endorse.php' ? 'active' : ''; ?>">
                    <i class="fas fa-heart"></i> Endorse Me
                </a>
                <a href="contact.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">
                    <i class="fas fa-envelope"></i> Contact
                </a>
            </div>
            
            <div class="nav-toggle" id="nav-toggle">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">