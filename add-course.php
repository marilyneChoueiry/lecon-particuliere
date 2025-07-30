<?php
require_once '../config/database.php';
requireTeacher();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);

    if (empty($title) || empty($description) || empty($category) || $price <= 0) {
        $error = 'All fields are required and price must be greater than 0.';
    } else {
        try {
            $pdo->beginTransaction();

            // Insert course
            $stmt = $pdo->prepare("INSERT INTO courses (title, description, category, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, $category, $price]);
            $courseId = $pdo->lastInsertId();

            // Link teacher to course
            $stmt = $pdo->prepare("INSERT INTO teacher_courses (id_user, id_course) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $courseId]);

            $pdo->commit();
            $success = 'Course created successfully!';
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Failed to create course. Please try again.';
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
                        <i class="fas fa-plus me-2"></i>Add New Course
                    </h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <div class="text-center">
                            <a href="courses.php" class="btn btn-primary">Back to My Courses</a>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="title" class="form-label">Course Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Category *</label>
                                        <select class="form-control" id="category" name="category" required>
                                            <option value="">Select Category</option>
                                            <option value="Mathematics">Mathematics</option>
                                            <option value="Science">Science</option>
                                            <option value="Bio">Bio</option>
                                            <option value="Physics">Physics</option>
                                            <option value="Chemistry">Chemistry</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price ($) *</label>
                                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="courses.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create Course
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