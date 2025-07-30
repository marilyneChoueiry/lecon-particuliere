<?php
require_once '../config/database.php';
requireTeacher();

$success = '';

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reservation_id']) && isset($_POST['status'])) {
    $reservationId = intval($_POST['reservation_id']);
    $status = $_POST['status'];

    // Verify teacher owns this course
    $stmt = $pdo->prepare("
        SELECT r.id_reservation FROM reservations r 
        JOIN teacher_courses tc ON r.id_course = tc.id_course 
        WHERE r.id_reservation = ? AND tc.id_user = ?
    ");
    $stmt->execute([$reservationId, $_SESSION['user_id']]);

    if ($stmt->fetch()) {
        $stmt = $pdo->prepare("UPDATE reservations SET status = ? WHERE id_reservation = ?");
        if ($stmt->execute([$status, $reservationId])) {
            $success = 'Reservation status updated successfully!';
        }
    }
}

// Get teacher's course reservations
$courseFilter = isset($_GET['course']) ? intval($_GET['course']) : 0;

$sql = "
    SELECT r.*, c.title, c.description, c.price, c.category,
           u.first_name, u.last_name, u.email, u.region, u.cycle
    FROM reservations r 
    JOIN courses c ON r.id_course = c.id_course 
    JOIN teacher_courses tc ON c.id_course = tc.id_course 
    LEFT JOIN student_reservations sr ON r.id_reservation = sr.id_reservation 
    LEFT JOIN users u ON sr.id_user = u.id_user 
    WHERE tc.id_user = ?
";

$params = [$_SESSION['user_id']];

if ($courseFilter) {
    $sql .= " AND c.id_course = ?";
    $params[] = $courseFilter;
}

$sql .= " ORDER BY r.date_hour DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reservations = $stmt->fetchAll();

// Get teacher's courses for filter
$stmt = $pdo->prepare("
    SELECT c.id_course, c.title 
    FROM courses c 
    JOIN teacher_courses tc ON c.id_course = tc.id_course 
    WHERE tc.id_user = ? 
    ORDER BY c.title
");
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container main-content">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-users me-2"></i>Manage Reservations
            </h2>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <!-- Course Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-6">
                            <label for="course" class="form-label">Filter by Course</label>
                            <select class="form-control" id="course" name="course">
                                <option value="">All Courses</option>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?php echo $course['id_course']; ?>"
                                        <?php echo ($courseFilter == $course['id_course']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($course['title']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="manage-reservations.php" class="btn btn-secondary">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (empty($reservations)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>No reservations found for your courses.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($reservation['email']); ?></small><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($reservation['region']); ?></small>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($reservation['title']); ?></strong><br>
                                        <small class="text-muted">$<?php echo number_format($reservation['price'], 2); ?></small>
                                    </td>
                                    <td>
                                        <?php echo date('M j, Y g:i A', strtotime($reservation['date_hour'])); ?>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <?php if ($reservation['status'] == 'on hold'): ?>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['id_reservation']; ?>">
                                                <input type="hidden" name="status" value="confirm">
                                                <button type="submit" class="btn btn-success btn-sm"
                                                    onclick="return confirm('Confirm this reservation?')">
                                                    <i class="fas fa-check"></i> Confirm
                                                </button>
                                            </form>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['id_reservation']; ?>">
                                                <input type="hidden" name="status" value="canceled">
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Cancel this reservation?')">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted">No actions available</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>