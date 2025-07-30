<?php
require_once '../config/database.php';
requireStudent();

$error = '';
$success = '';

$courseId = isset($_GET['course']) ? intval($_GET['course']) : 0;

// Check if student has a confirmed reservation for this course
$stmt = $pdo->prepare("
    SELECT c.title, c.description, u.first_name, u.last_name
    FROM reservations r 
    JOIN student_reservations sr ON r.id_reservation = sr.id_reservation 
    JOIN courses c ON r.id_course = c.id_course 
    LEFT JOIN teacher_courses tc ON c.id_course = tc.id_course 
    LEFT JOIN users u ON tc.id_user = u.id_user 
    WHERE sr.id_user = ? AND r.id_course = ? AND r.status = 'confirm'
");
$stmt->execute([$_SESSION['user_id'], $courseId]);
$course = $stmt->fetch();

if (!$course) {
    header('Location: my-reservations.php');
    exit();
}

// Check if opinion already exists
$stmt = $pdo->prepare("SELECT * FROM opinions WHERE id_user = ? AND id_course = ?");
$stmt->execute([$_SESSION['user_id'], $courseId]);
if ($stmt->fetch()) {
    header('Location: edit-opinion.php?course=' . $courseId);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stars = intval($_POST['stars']);
    $comment = trim($_POST['comment']);

    if ($stars < 1 || $stars > 5) {
        $error = 'Please select a rating between 1 and 5 stars.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO opinions (id_user, id_course, stars, comment) VALUES (?, ?, ?, ?)");

        if ($stmt->execute([$_SESSION['user_id'], $courseId, $stars, $comment])) {
            $success = 'Review submitted successfully!';
        } else {
            $error = 'Failed to submit review. Please try again.';
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
                        <i class="fas fa-star me-2"></i>Add Review
                    </h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <div class="text-center">
                            <a href="my-reservations.php" class="btn btn-primary">Back to My Reservations</a>
                        </div>
                    <?php else: ?>
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <h5><?php echo htmlspecialchars($course['title']); ?></h5>
                                <p class="text-muted"><?php echo htmlspecialchars($course['description']); ?></p>
                                <p><strong>Teacher:</strong> <?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?></p>
                            </div>
                        </div>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Rating *</label>
                                <div class="rating-input">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <input type="radio" name="stars" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" required>
                                        <label for="star<?php echo $i; ?>" class="star-label">
                                            <i class="far fa-star"></i>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                                <div class="form-text">Click on the stars to rate your experience</div>
                            </div>

                            <div class="mb-3">
                                <label for="comment" class="form-label">Comment (Optional)</label>
                                <textarea class="form-control" id="comment" name="comment" rows="4"
                                    placeholder="Share your experience with this course..."></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="my-reservations.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Review
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .rating-input input {
        display: none;
    }

    .star-label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }

    .rating-input input:checked~.star-label,
    .rating-input input:hover~.star-label {
        color: #f39c12;
    }

    .rating-input:hover .star-label {
        color: #f39c12;
    }
</style>

<?php include '../includes/footer.php'; ?>