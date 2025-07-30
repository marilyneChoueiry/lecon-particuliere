<?php
require_once '../config/database.php';
requireStudent();

$error = '';
$success = '';

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

// Get existing opinions for this course
$stmt = $pdo->prepare("
    SELECT o.*, u.first_name, u.last_name, u.region
    FROM opinions o 
    JOIN users u ON o.id_user = u.id_user 
    WHERE o.id_course = ? 
    ORDER BY o.date_opinion DESC 
    LIMIT 5
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dateHour = $_POST['date_hour'];

    if (empty($dateHour)) {
        $error = 'Please select a date and time.';
    } else {
        try {
            $pdo->beginTransaction();

            // Create reservation
            $stmt = $pdo->prepare("INSERT INTO reservations (id_course, date_hour, status) VALUES (?, ?, 'on hold')");
            $stmt->execute([$courseId, $dateHour]);
            $reservationId = $pdo->lastInsertId();

            // Link student to reservation
            $stmt = $pdo->prepare("INSERT INTO student_reservations (id_user, id_reservation) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $reservationId]);

            $pdo->commit();
            $success = 'Reservation submitted successfully! The teacher will review and confirm your booking.';
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Failed to create reservation. Please try again.';
        }
    }
}

include '../includes/header.php';
?>

<div class="container main-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Reserve Course
                    </h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <div class="text-center">
                            <a href="my-reservations.php" class="btn btn-primary">View My Reservations</a>
                        </div>
                    <?php else: ?>
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <h5><?php echo htmlspecialchars($course['title']); ?></h5>
                                <p class="text-muted"><?php echo htmlspecialchars($course['description']); ?></p>
                                <p><strong>Teacher:</strong> <?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?></p>
                                <p><strong>Price:</strong> $<?php echo number_format($course['price'], 2); ?></p>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($course['category']); ?></span>
                            </div>
                        </div>

                        <!-- Course Reviews Section -->
                        <?php if (!empty($opinions)): ?>
                            <div class="mb-4">
                                <h6><i class="fas fa-star me-2"></i>Course Reviews</h6>
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
                                    <span class="ms-2 text-muted"><?php echo $avgRating; ?>/5 (<?php echo $totalReviews; ?> reviews)</span>
                                </div>

                                <div class="row">
                                    <?php foreach ($opinions as $opinion): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card border">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <strong class="small"><?php echo htmlspecialchars($opinion['first_name'] . ' ' . $opinion['last_name']); ?></strong>
                                                        <small class="text-muted"><?php echo date('M j', strtotime($opinion['date_opinion'])); ?></small>
                                                    </div>
                                                    <div class="stars mb-2">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <?php if ($i <= $opinion['stars']): ?>
                                                                <i class="fas fa-star text-warning"></i>
                                                            <?php else: ?>
                                                                <i class="far fa-star text-warning"></i>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <?php if ($opinion['comment']): ?>
                                                        <p class="small text-muted mb-0"><?php echo htmlspecialchars($opinion['comment']); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="text-center">
                                    <a href="course-opinions.php?id=<?php echo $courseId; ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View All Reviews
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="date_hour" class="form-label">Select Date and Time *</label>
                                <input type="datetime-local" class="form-control" id="date_hour" name="date_hour" required>
                                <div class="form-text">Please select a date and time that works for you. The teacher will review and confirm your booking.</div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="courses.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Courses
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-calendar-check me-2"></i>Submit Reservation
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>