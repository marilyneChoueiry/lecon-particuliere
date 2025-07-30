<?php
// Start session first
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize session variables with fallbacks (only if not already set)
if (!isset($isLoggedIn)) {
    $isLoggedIn = isset($_SESSION['user_id']);
}
if (!isset($userRole)) {
    $userRole = $isLoggedIn && isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
}
if (!isset($firstName)) {
    $firstName = $isLoggedIn && isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
}

try {
    require_once '../config/database.php';
    // If database connection succeeds, these variables will be updated by database.php
    // But we keep our fallback values in case database fails
} catch (Exception $e) {
    // Continue with header even if database fails
    // Session variables are already set above
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leçon Particulière - Private Tutoring Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Modern Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-graduation-cap me-2"></i>Leçon Particulière
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>

                    <?php if ($isLoggedIn): ?>
                        <?php if ($userRole == 'student'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="my-reservations.php">
                                    <i class="fas fa-calendar-check me-1"></i>My Reservations
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="courses.php">
                                    <i class="fas fa-book me-1"></i>Courses
                                </a>
                            </li>
                        <?php elseif ($userRole == 'teacher'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="courses.php">
                                    <i class="fas fa-chalkboard-teacher me-1"></i>My Courses
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="manage-reservations.php">
                                    <i class="fas fa-users me-1"></i>Manage Students
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($firstName); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="profile.php">
                                        <i class="fas fa-user-edit me-2"></i>Profile
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">