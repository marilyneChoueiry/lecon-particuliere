<?php
require_once '../config/database.php';
requireStudent();

$error = '';
$success = '';

$courseId = isset($_GET['course']) ? intval($_GET['course']) : 0;

// Get existing opinion
$stmt = $pdo->prepare("
    SELECT o.*, c.title, c.description, u.first_name, u.last_name
    FROM opinions o 
    JOIN courses c ON o.id_course = c.id_course 
    LEFT JOIN teacher_courses tc ON c.id_course = tc.id_course 
    LEFT JOIN users u ON tc.id_user = u.id_user 
    WHERE o.id_user = ? AND o.id_course = ?
");
$stmt->execute([$_SESSION['user_id'], $courseId]);
$opinion = $stmt->fetch();

if (!$opinion) {
    header('Location: my-reservations.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stars = intval($_POST['stars']);
    $comment = trim($_POST['comment']);

    if ($stars < 1 || $stars > 5) {
        $error = 'Please select a rating between 1 and 5 stars.';
    } else {
        $stmt = $pdo->prepare("UPDATE opinions SET stars = ?, comment = ? WHERE id_user = ? AND id_course = ?");

        if ($stmt->execute([$stars, $comment, $_SESSION['user_id'], $courseId])) {
            $success = 'Review updated successfully!';
            $opinion['stars'] = $stars;
            $opinion['comment'] = $comment;
        } else {
            $error = 'Failed to update review. Please try again.';
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
                        <i class="fas fa-edit me-2"></i>Edit Review
                    </h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                    <?php endif; ?>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h5><?php echo htmlspecialchars($opinion['title']); ?></h5>
                            <p class="text-muted"><?php echo htmlspecialchars($opinion['description']); ?></p>
                            <p><strong>Teacher:</strong> <?php echo htmlspecialchars($opinion['first_name'] . ' ' . $opinion['last_name']); ?></p>
                        </div>
                    </div>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Rating *</label>
                            <div class="rating-input">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <input type="radio" name="stars" value="<?php echo $i; ?>" id="star<?php echo $i; ?>"
                                        <?php echo ($opinion['stars'] == $i) ? 'checked' : ''; ?> required>
                                    <label for="star<?php echo $i; ?>" class="star-label">
                                        <i class="<?php echo ($opinion['stars'] >= $i) ? 'fas' : 'far'; ?> fa-star"></i>
                                    </label>
                                <?php endfor; ?>
                            </div>
                            <div class="form-text">Click on the stars to rate your experience</div>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment (Optional)</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4"
                                placeholder="Share your experience with this course..."><?php echo htmlspecialchars($opinion['comment']); ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="my-reservations.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Review
                            </button>
                        </div>
                    </form>
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