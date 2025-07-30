<?php
require_once '../config/database.php';
requireStudent();

$courseId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get course details
$stmt = $pdo->prepare("
    SELECT c.*, u.first_name, u.last_name 
    FROM courses c 
    LEFT JOIN teacher_courses tc ON c.id_course = tc.id_course 
    LEFT JOIN users u ON tc.id_user = u.id_user 
    WHERE c.id_course = ?
");
$stmt->execute([$courseId]);
$course = $stmt->fetch();

if (!$course) {
    header('Location: courses.php');
    exit();
}

// Get all opinions for this course
$stmt = $pdo->prepare("
    SELECT o.*, u.first_name, u.last_name, u.region, u.cycle
    FROM opinions o 
    JOIN users u ON o.id_user = u.id_user 
    WHERE o.id_course = ? 
    ORDER BY o.date_opinion DESC
");
$stmt->execute([$courseId]);
$opinions = $stmt->fetchAll();

// Calculate average rating
$avgRating = 0;
$totalReviews = count($opinions);
if ($totalReviews > 0) {
    $totalStars = array_sum(array_column($opinions, 'stars'));
    $avgRating = round($totalStars / $totalReviews, 1);
}

include '../includes/header.php';
?>

<div class="container main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-star me-2"></i>Course Reviews
                </h2>
                <div>
                    <a href="courses.php" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to Courses
                    </a>
                    <a href="reserve-course.php?id=<?php echo $courseId; ?>" class="btn btn-primary">
                        <i class="fas fa-calendar-plus me-2"></i>Reserve This Course
                    </a>
                </div>
            </div>

            <!-- Course Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4><?php echo htmlspecialchars($course['title']); ?></h4>
                            <p class="text-muted"><?php echo htmlspecialchars($course['description']); ?></p>
                            <p><strong>Teacher:</strong> <?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?></p>
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($course['category']); ?></p>
                            <p><strong>Price:</strong> $<?php echo number_format($course['price'], 2); ?></p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="mb-2">
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $avgRating): ?>
                                            <i class="fas fa-star"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <div class="h5 mb-0"><?php echo $avgRating; ?>/5</div>
                                <small class="text-muted"><?php echo $totalReviews; ?> reviews</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (empty($opinions)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>No reviews yet for this course.
                    <br><small>Be the first to review this course after completing it!</small>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($opinions as $opinion): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong><?php echo htmlspecialchars($opinion['first_name'] . ' ' . $opinion['last_name']); ?></strong>
                                        <small class="text-muted"><?php echo date('M j, Y', strtotime($opinion['date_opinion'])); ?></small>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="stars">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $opinion['stars']): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    </div>

                                    <?php if ($opinion['comment']): ?>
                                        <p class="card-text"><?php echo htmlspecialchars($opinion['comment']); ?></p>
                                    <?php else: ?>
                                        <p class="text-muted"><em>No comment provided</em></p>
                                    <?php endif; ?>

                                    <div class="mt-auto">
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($opinion['region']); ?>
                                            <?php if ($opinion['cycle']): ?>
                                                <br><i class="fas fa-graduation-cap me-1"></i><?php echo htmlspecialchars($opinion['cycle']); ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>