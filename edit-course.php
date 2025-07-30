<?php
require_once '../config/database.php';
requireTeacher();

$error = '';
$success = '';

$courseId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get course details
$stmt = $pdo->prepare("
    SELECT c.* FROM courses c 
    JOIN teacher_courses tc ON c.id_course = tc.id_course 
    WHERE c.id_course = ? AND tc.id_user = ?
");
$stmt->execute([$courseId, $_SESSION['user_id']]);
$course = $stmt->fetch();

if (!$course) {
    header('Location: courses.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);

    if (empty($title) || empty($description) || empty($category) || $price <= 0) {
        $error = 'All fields are required and price must be greater than 0.';
    } else {
        $stmt = $pdo->prepare("UPDATE courses SET title = ?, description = ?, category = ?, price = ? WHERE id_course = ?");

        if ($stmt->execute([$title, $description, $category, $price, $courseId])) {
            $success = 'Course updated successfully!';
            $course['title'] = $title;
            $course['description'] = $description;
            $course['category'] = $category;
            $course['price'] = $price;
        } else {
            $error = 'Failed to update course. Please try again.';
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
                        <i class="fas fa-edit me-2"></i>Edit Course
                    </h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="title" class="form-label">Course Title *</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="<?php echo htmlspecialchars($course['title']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($course['description']); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <?php
                                        $categories = ['Mathematics', 'Science', 'Bio', 'Physics', 'Chemistry'];
                                        foreach ($categories as $cat):
                                        ?>
                                            <option value="<?php echo $cat; ?>" <?php echo ($course['category'] == $cat) ? 'selected' : ''; ?>>
                                                <?php echo $cat; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price ($) *</label>
                                    <input type="number" class="form-control" id="price" name="price"
                                        step="0.01" min="0" value="<?php echo $course['price']; ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="courses.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>