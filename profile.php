<?php
require_once '../config/database.php';
requireLogin();

$error = '';
$success = '';

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $region = trim($_POST['region']);
    $cycle = trim($_POST['cycle']);
    $bio = trim($_POST['bio']);
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($region)) {
        $error = 'Required fields cannot be empty.';
    } else {
        // Check if email already exists (excluding current user)
        $stmt = $pdo->prepare("SELECT id_user FROM users WHERE email = ? AND id_user != ?");
        $stmt->execute([$email, $_SESSION['user_id']]);

        if ($stmt->rowCount() > 0) {
            $error = 'Email already exists.';
        } else {
            // Handle password change
            $passwordUpdate = '';
            $params = [$firstName, $lastName, $email, $region, $cycle, $bio];

            if (!empty($currentPassword)) {
                if (!password_verify($currentPassword, $user['password_hash'])) {
                    $error = 'Current password is incorrect.';
                } elseif (empty($newPassword)) {
                    $error = 'New password is required when changing password.';
                } elseif ($newPassword !== $confirmPassword) {
                    $error = 'New passwords do not match.';
                } elseif (strlen($newPassword) < 6) {
                    $error = 'New password must be at least 6 characters long.';
                } else {
                    $passwordUpdate = ', password_hash = ?';
                    $params[] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
            }

            if (empty($error)) {
                $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, region = ?, cycle = ?, bio = ?" . $passwordUpdate . " WHERE id_user = ?";
                $params[] = $_SESSION['user_id'];

                $stmt = $pdo->prepare($sql);
                if ($stmt->execute($params)) {
                    $success = 'Profile updated successfully!';

                    // Update session data
                    $_SESSION['first_name'] = $firstName;
                    $_SESSION['last_name'] = $lastName;
                    $_SESSION['email'] = $email;

                    // Refresh user data
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    $user = $stmt->fetch();
                } else {
                    $error = 'Failed to update profile. Please try again.';
                }
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="container main-content">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-user me-2"></i>Edit Profile
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="region" class="form-label">Region *</label>
                            <input type="text" class="form-control" id="region" name="region"
                                value="<?php echo htmlspecialchars($user['region']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="cycle" class="form-label">Cycle</label>
                            <select class="form-control" id="cycle" name="cycle">
                                <option value="">Select Cycle</option>
                                <?php
                                $cycles = ['Primary', 'Secondary', 'High School' ];
                                foreach ($cycles as $cycle):
                                ?>
                                    <option value="<?php echo $cycle; ?>" <?php echo ($user['cycle'] == $cycle) ? 'selected' : ''; ?>>
                                        <?php echo $cycle; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                        </div>

                        <hr>

                        <h5>Change Password (Optional)</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>

                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Role:</strong> <span class="badge bg-primary"><?php echo ucfirst($user['role']); ?></span></p>
                    <p><strong>Region:</strong> <?php echo htmlspecialchars($user['region']); ?></p>
                    <?php if ($user['cycle']): ?>
                        <p><strong>Cycle:</strong> <?php echo htmlspecialchars($user['cycle']); ?></p>
                    <?php endif; ?>
                    <p><strong>Member since:</strong> <?php echo date('M j, Y', strtotime($user['created_at'])); ?></p>

                    <?php if ($user['bio']): ?>
                        <hr>
                        <h6>Bio:</h6>
                        <p class="text-muted"><?php echo htmlspecialchars($user['bio']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>