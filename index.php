<?php
require_once 'config/database.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $isLoggedIn && isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
$firstName = $isLoggedIn && isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leçon Particulière - Private Tutoring Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
  <!-- Modern Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <i class="fas fa-graduation-cap me-2"></i>Leçon Particulière
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="fas fa-home me-1"></i>Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/courses.php">
              <i class="fas fa-book me-1"></i>Courses
            </a>
          </li>
          <?php if ($isLoggedIn): ?>
            <li class="nav-item">
              <a class="nav-link" href="pages/profile.php">
                <i class="fas fa-user me-1"></i>Profile
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="pages/logout.php">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
              </a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="pages/login.php">
                <i class="fas fa-sign-in-alt me-1"></i>Login
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="pages/register.php">
                <i class="fas fa-user-plus me-1"></i>Register
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 fade-in-up">
          <h1 class="text-gradient text-shadow">
            Discover Your Perfect Tutor
          </h1>
          <p class="lead mb-4">
            Connect with experienced teachers and students for personalized learning experiences.
            Whether you're looking to learn or teach, we've got you covered.
          </p>
          <div class="d-flex gap-3 flex-wrap">
            <?php if (!$isLoggedIn): ?>
              <a href="pages/register.php" class="btn btn-light btn-lg pulse">
                <i class="fas fa-rocket me-2"></i>Get Started
              </a>
              <a href="pages/courses.php" class="btn btn-outline-light btn-lg">
                <i class="fas fa-book me-2"></i>Browse Courses
              </a>
            <?php else: ?>
              <a href="pages/courses.php" class="btn btn-light btn-lg pulse">
                <i class="fas fa-book me-2"></i>Browse Courses
              </a>
              <a href="pages/profile.php" class="btn btn-outline-light btn-lg">
                <i class="fas fa-user me-2"></i>My Profile
              </a>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-lg-6 fade-in-up" style="animation-delay: 0.2s;">
          <div class="text-center">
            <div class="stats-card glass">
              <div class="row">
                <div class="col-6">
                  <div class="stats-card">
                    <div class="icon">
                      <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="number">50+</div>
                    <div class="label">Teachers</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="stats-card">
                    <div class="icon">
                      <i class="fas fa-users"></i>
                    </div>
                    <div class="number">200+</div>
                    <div class="label">Students</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <?php if ($isLoggedIn): ?>
        <!-- Welcome Dashboard -->
        <div class="row mb-5">
          <div class="col-12">
            <div class="dashboard-card">
              <div class="row align-items-center">
                <div class="col-md-8">
                  <h2 class="text-gradient mb-3">
                    <i class="fas fa-sun me-2"></i>
                    Welcome back, <?php echo htmlspecialchars(isset($_SESSION['first_name']) ? $_SESSION['first_name'] : 'User'); ?>!
                  </h2>
                  <p class="lead mb-0">
                    Ready to continue your learning journey?
                    <?php if (isStudent()): ?>
                      Browse available courses or check your reservations.
                    <?php else: ?>
                      Manage your courses or review student requests.
                    <?php endif; ?>
                  </p>
                </div>
                <div class="col-md-4 text-end">
                  <div class="stats-card">
                    <div class="icon">
                      <i class="fas fa-<?php echo isStudent() ? 'graduation-cap' : 'chalkboard-teacher'; ?>"></i>
                    </div>
                    <div class="number"><?php echo isStudent() ? 'Student' : 'Teacher'; ?></div>
                    <div class="label">Account Type</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-5">
          <div class="col-12">
            <h3 class="mb-4">
              <i class="fas fa-bolt me-2"></i>Quick Actions
            </h3>
          </div>
          <?php if (isStudent()): ?>
            <div class="col-md-4 mb-4">
              <div class="card course-card hover-lift">
                <div class="card-body text-center">
                  <div class="icon mb-3">
                    <i class="fas fa-search"></i>
                  </div>
                  <h5>Find Courses</h5>
                  <p class="text-muted">Discover new courses from experienced teachers</p>
                  <a href="pages/courses.php" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Browse Courses
                  </a>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="card course-card hover-lift">
                <div class="card-body text-center">
                  <div class="icon mb-3">
                    <i class="fas fa-calendar-check"></i>
                  </div>
                  <h5>My Reservations</h5>
                  <p class="text-muted">Track your course reservations and status</p>
                  <a href="pages/my-reservations.php" class="btn btn-success">
                    <i class="fas fa-calendar me-2"></i>View Reservations
                  </a>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="card course-card hover-lift">
                <div class="card-body text-center">
                  <div class="icon mb-3">
                    <i class="fas fa-star"></i>
                  </div>
                  <h5>My Reviews</h5>
                  <p class="text-muted">Manage your course reviews and ratings</p>
                  <a href="pages/my-reservations.php" class="btn btn-warning">
                    <i class="fas fa-star me-2"></i>View Reviews
                  </a>
                </div>
              </div>
            </div>
          <?php else: ?>
            <div class="col-md-4 mb-4">
              <div class="card course-card hover-lift">
                <div class="card-body text-center">
                  <div class="icon mb-3">
                    <i class="fas fa-plus"></i>
                  </div>
                  <h5>Create Course</h5>
                  <p class="text-muted">Add a new course to your portfolio</p>
                  <a href="pages/add-course.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Course
                  </a>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="card course-card hover-lift">
                <div class="card-body text-center">
                  <div class="icon mb-3">
                    <i class="fas fa-users"></i>
                  </div>
                  <h5>Manage Students</h5>
                  <p class="text-muted">Review and approve student reservations</p>
                  <a href="pages/manage-reservations.php" class="btn btn-success">
                    <i class="fas fa-users me-2"></i>Manage Students
                  </a>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="card course-card hover-lift">
                <div class="card-body text-center">
                  <div class="icon mb-3">
                    <i class="fas fa-star"></i>
                  </div>
                  <h5>Course Reviews</h5>
                  <p class="text-muted">View student feedback for your courses</p>
                  <a href="pages/course-opinions.php" class="btn btn-warning">
                    <i class="fas fa-star me-2"></i>View Reviews
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <!-- Features for Guests -->
        <div class="row mb-5">
          <div class="col-12">
            <h2 class="text-center mb-5">
              <i class="fas fa-star me-2"></i>Why Choose Leçon Particulière?
            </h2>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card course-card hover-lift">
              <div class="card-body text-center">
                <div class="icon mb-3">
                  <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h5>Expert Teachers</h5>
                <p class="text-muted">Learn from experienced and qualified teachers in various subjects</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card course-card hover-lift">
              <div class="card-body text-center">
                <div class="icon mb-3">
                  <i class="fas fa-clock"></i>
                </div>
                <h5>Flexible Scheduling</h5>
                <p class="text-muted">Book lessons at times that work best for your schedule</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card course-card hover-lift">
              <div class="card-body text-center">
                <div class="icon mb-3">
                  <i class="fas fa-star"></i>
                </div>
                <h5>Student Reviews</h5>
                <p class="text-muted">Read authentic reviews from other students before booking</p>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <!-- Footer -->
  <footer class="text-center">
    <div class="container">
      <p class="mb-0">
        <i class="fas fa-heart text-danger"></i>
        © 2024 Leçon Particulière. All rights reserved.
      </p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/script.js"></script>
</body>

</html>