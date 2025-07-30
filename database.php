<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$dbname = 'lecon_particuliere';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Define session variables for header compatibility (only if not already set)
if (!isset($isLoggedIn)) {
    $isLoggedIn = isset($_SESSION['user_id']);
}
if (!isset($userRole)) {
    $userRole = $isLoggedIn && isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
}
if (!isset($firstName)) {
    $firstName = $isLoggedIn && isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
}

// Helper functions
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isStudent()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'student';
}

function isTeacher()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'teacher';
}

function requireLogin()
{
    if (!isLoggedIn()) {
        // Detect if we're in pages directory or root
        $isRoot = !file_exists('../login.php');
        $loginPath = $isRoot ? 'pages/login.php' : 'login.php';
        header('Location: ' . $loginPath);
        exit();
    }
}

function requireStudent()
{
    requireLogin();
    if (!isStudent()) {
        // Detect if we're in pages directory or root
        $isRoot = !file_exists('../index.php');
        $homePath = $isRoot ? 'index.php' : '../index.php';
        header('Location: ' . $homePath);
        exit();
    }
}

function requireTeacher()
{
    requireLogin();
    if (!isTeacher()) {
        // Detect if we're in pages directory or root
        $isRoot = !file_exists('../index.php');
        $homePath = $isRoot ? 'index.php' : '../index.php';
        header('Location: ' . $homePath);
        exit();
    }
}
