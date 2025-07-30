<?php
require_once '../config/database.php';
include '../includes/header.php';

// Get courses based on user role
if (isLoggedIn() && isTeacher()) {
    // Teachers see their own courses
    $stmt = $pdo->prepare("
        SELECT c.*, COUNT(sr.id_user) as student_count 
        FROM courses c 
        LEFT JOIN teacher_courses tc ON c.id_course = tc.id_course 
        LEFT JOIN reservations r ON c.id_course = r.id_course 
        LEFT JOIN student_reservations sr ON r.id_reservation = sr.id_reservation 
        WHERE tc.id_user = ? 
        GROUP BY c.id_course, c.title, c.description, c.category, c.price
        ORDER BY c.title
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $courses = $stmt->fetchAll();
} else {
    // Students and guests see all available courses with opinions
    $stmt = $pdo->prepare("
        SELECT c.*, 
               MAX(u.first_name) as first_name, 
               MAX(u.last_name) as last_name,
               COUNT(DISTINCT r.id_reservation) as total_reservations,
               AVG(o.stars) as avg_rating,
               COUNT(o.id_opinion) as review_count,
               COUNT(DISTINCT o.id_user) as unique_reviewers
        FROM courses c 
        LEFT JOIN teacher_courses tc ON c.id_course = tc.id_course 
        LEFT JOIN users u ON tc.id_user = u.id_user 
        LEFT JOIN reservations r ON c.id_course = r.id_course 
        LEFT JOIN opinions o ON c.id_course = o.id_course 
        GROUP BY c.id_course, c.title, c.description, c.category, c.price
        ORDER BY c.title
    ");
    $stmt->execute();
    $courses = $stmt->fetchAll();
}
?>

<div class="container main-content">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <?php if (isLoggedIn() && isTeacher()): ?>
                    <i class="fas fa-chalkboard-teacher me-2"></i>My Courses
                <?php else: ?>
                    <i class="fas fa-book me-2"></i>Available Courses
                <?php endif; ?>
            </h2>

            <?php if (isLoggedIn() && isTeacher()): ?>
                <div class="mb-4">
                    <a href="add-course.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Course
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (empty($courses)): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <?php if (isLoggedIn() && isTeacher()): ?>
                        <i class="fas fa-info-circle me-2"></i>You haven't created any courses yet.
                    <?php else: ?>
                        <i class="fas fa-info-circle me-2"></i>No courses available at the moment.
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($courses as $course): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card course-card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($course['title']); ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>

                            <?php if (isset($course['first_name'])): ?>
                                <p class="text-muted">
                                    <i class="fas fa-user me-1"></i>
                                    <?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?>
                                </p>
                            <?php endif; ?>

                            <?php if (isset($course['category']) && $course['category']): ?>
                                <span class="badge bg-secondary mb-2"><?php echo htmlspecialchars($course['category']); ?></span>
                            <?php endif; ?>

                            <?php if (isset($course['avg_rating']) && $course['avg_rating']): ?>
                                <div class="mb-2">
                                    <div class="stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $course['avg_rating']): ?>
                                                <i class="fas fa-star"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <span class="ms-2 text-muted">(<?php echo $course['review_count']; ?> reviews)</span>
                                    </div>
                                </div>
                            <?php elseif (isset($course['review_count']) && $course['review_count'] == 0): ?>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="far fa-star"></i> No reviews yet
                                    </small>
                                </div>
                            <?php endif; ?>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-primary mb-0">$<?php echo number_format($course['price'], 2); ?></span>
                                <?php if (isLoggedIn() && isTeacher()): ?>
                                    <span class="badge bg-info"><?php echo $course['student_count']; ?> students</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer">
                            <?php if (isLoggedIn() && isStudent()): ?>
                                <div class="d-grid gap-2">
                                    <a href="reserve-course.php?id=<?php echo $course['id_course']; ?>" class="btn btn-primary">
                                        <i class="fas fa-calendar-plus me-2"></i>Reserve Course
                                    </a>
                                    <?php if (isset($course['review_count']) && $course['review_count'] > 0): ?>
                                        <a href="view-course-opinions.php?id=<?php echo $course['id_course']; ?>" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-star me-1"></i>View Reviews (<?php echo $course['review_count']; ?>)
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php elseif (isLoggedIn() && isTeacher()): ?>
                                <div class="btn-group w-100" role="group">
                                    <a href="edit-course.php?id=<?php echo $course['id_course']; ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="manage-reservations.php?course=<?php echo $course['id_course']; ?>" class="btn btn-outline-success">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <a href="course-opinions.php?id=<?php echo $course['id_course']; ?>" class="btn btn-outline-warning">
                                        <i class="fas fa-star"></i>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="d-grid gap-2">
                                    <a href="login.php" class="btn btn-primary">Login to Reserve</a>
                                    <?php if (isset($course['review_count']) && $course['review_count'] > 0): ?>
                                        <a href="view-course-opinions.php?id=<?php echo $course['id_course']; ?>" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-star me-1"></i>View Reviews (<?php echo $course['review_count']; ?>)
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>