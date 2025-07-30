<?php
require_once '../config/database.php';
requireStudent();

// Get student's reservations
$stmt = $pdo->prepare("
    SELECT r.*, c.title, c.description, c.price, c.category, u.first_name, u.last_name,
           o.stars, o.comment, o.date_opinion
    FROM reservations r 
    JOIN student_reservations sr ON r.id_reservation = sr.id_reservation 
    JOIN courses c ON r.id_course = c.id_course 
    LEFT JOIN teacher_courses tc ON c.id_course = tc.id_course 
    LEFT JOIN users u ON tc.id_user = u.id_user 
    LEFT JOIN opinions o ON (c.id_course = o.id_course AND o.id_user = ?)
    WHERE sr.id_user = ? 
    ORDER BY r.date_hour DESC
");
$stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
$reservations = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container main-content">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-calendar-check me-2"></i>My Reservations
            </h2>

            <?php if (empty($reservations)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>You haven't made any reservations yet.
                    <br><a href="courses.php" class="btn btn-primary mt-2">Browse Courses</a>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($reservation['title']); ?></h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><?php echo htmlspecialchars($reservation['description']); ?></p>

                                    <div class="mb-3">
                                        <strong>Teacher:</strong> <?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?><br>
                                        <strong>Date:</strong> <?php echo date('M j, Y g:i A', strtotime($reservation['date_hour'])); ?><br>
                                        <strong>Price:</strong> $<?php echo number_format($reservation['price'], 2); ?><br>
                                        <strong>Status:</strong>
                                        <?php
                                        $statusClass = '';
                                        switch ($reservation['status']) {
                                            case 'confirm':
                                                $statusClass = 'badge-confirm';
                                                break;
                                            case 'canceled':
                                                $statusClass = 'badge-canceled';
                                                break;
                                            default:
                                                $statusClass = 'badge-hold';
                                        }
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($reservation['status']); ?></span>
                                    </div>

                                    <?php if (isset($reservation['category']) && $reservation['category']): ?>
                                        <span class="badge bg-secondary mb-2"><?php echo htmlspecialchars($reservation['category']); ?></span>
                                    <?php endif; ?>

                                    <?php if ($reservation['stars']): ?>
                                        <div class="mb-3">
                                            <strong>Your Rating:</strong>
                                            <div class="stars">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <?php if ($i <= $reservation['stars']): ?>
                                                        <i class="fas fa-star"></i>
                                                    <?php else: ?>
                                                        <i class="far fa-star"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </div>
                                            <?php if ($reservation['comment']): ?>
                                                <p class="text-muted mt-2"><?php echo htmlspecialchars($reservation['comment']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer">
                                    <?php if ($reservation['status'] == 'confirm' && !$reservation['stars']): ?>
                                        <div class="d-grid">
                                            <a href="add-opinion.php?course=<?php echo $reservation['id_course']; ?>" class="btn btn-warning">
                                                <i class="fas fa-star me-2"></i>Add Review
                                            </a>
                                        </div>
                                    <?php elseif ($reservation['status'] == 'confirm' && $reservation['stars']): ?>
                                        <div class="d-grid">
                                            <a href="edit-opinion.php?course=<?php echo $reservation['id_course']; ?>" class="btn btn-outline-warning">
                                                <i class="fas fa-edit me-2"></i>Edit Review
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center text-muted">
                                            <small>Review available after confirmation</small>
                                        </div>
                                    <?php endif; ?>
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