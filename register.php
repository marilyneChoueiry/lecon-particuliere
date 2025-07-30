<?php
require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $region = trim($_POST['region']);
    $cycle = trim($_POST['cycle']);
    $bio = trim($_POST['bio']);
    $role = $_POST['role'];

    // Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($region) || empty($role)) {
        $error = 'All required fields must be filled.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id_user FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = 'Email already exists.';
        } else {
            // Hash password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password_hash, region, cycle, bio, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            if ($stmt->execute([$firstName, $lastName, $email, $passwordHash, $region, $cycle, $bio, $role])) {
                $success = 'Registration successful! You can now login.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="container main-content">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center mb-0">Register</h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <div class="text-center">
                            <a href="login.php" class="btn btn-primary">Go to Login</a>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name *</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last Name *</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password *</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm Password *</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="region" class="form-label">Region *</label>
                                <input type="text" class="form-control" id="region" name="region" required>
                            </div>

                            <div class="mb-3">
                                <label for="cycle" class="form-label">Cycle (Optional)</label>
                                <select class="form-control" id="cycle" name="cycle">
                                    <option value="">Select Cycle</option>
                                    <option value="Primary">Primary</option>
                                    <option value="Secondary">Secondary</option>
                                    <option value="High School">High School</option>
                                   
                                    
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role *</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="student">Student</option>
                                    <option value="teacher">Teacher</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio (Optional)</label>
                                <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Tell us about yourself..."></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="login.php">Login here</a></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>