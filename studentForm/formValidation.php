<?php
require_once __DIR__ . '/../function.php';

function isValidPassword($password) {
    $passLength = strlen($password) >= 8;
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    return $passLength && $uppercase && $lowercase && $number && $specialChars;
}

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    
    if (
        !isset($_POST["name"]) || empty(trim($_POST["name"])) ||
        !isset($_POST["lastName"]) || empty(trim($_POST["lastName"])) ||
        !isset($_POST["email"]) || empty(trim($_POST["email"])) ||
        !isset($_POST["password"]) || empty(trim($_POST["password"])) ||
        !isset($_POST["region"]) ||
        !isset($_POST["cycle"]) || empty(trim($_POST["cycle"]))
    ) {
        http_response_code(400);
        header("Location: student.php?error=emptyFields");
        exit;
    }

    $errors = [];

   
    $name = htmlspecialchars(trim($_POST["name"]));
    if (!preg_match("/^[a-zA-Z]{3}[a-zA-Z ]{0,21}$/", $name)) {
        $errors["name"] = "Invalid name";
    }

    $lastname = htmlspecialchars(trim($_POST["lastName"]));
    if (!preg_match("/^[a-zA-Z]{3}[a-zA-Z ]{0,21}$/", $lastname)) {
        $errors["lastname"] = "Invalid last name";
    }

    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email";
    }

    $password = trim($_POST["password"]);
    if (!isValidPassword($password)) {
        $errors["password"] = "Invalid password";
    }

    $allowedRegions = [
    "Beirut",
    "Mount Lebanon",
    "South",
    "Nabatieh",
    "Beqaa",
    "Jbeil",
    "North",
    "Baalbek",
    "Akkar"
];

$region = $_POST["region"];

if (!in_array($region, $allowedRegions)) {
    $errors["region"] = "Invalid region selected.";
}

    $cycle = htmlspecialchars(trim($_POST["cycle"]));
    $role = 'student';
 
    if (!empty($errors)) {
        print_r($errors);
        exit;
    }

    
    try {
        
        $conn=getPdoConnention(); 
        if (!$conn) {
    die(" Database connection failed — \$conn is null");
}
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $checkStmt->execute([$email]);
        $emailExists = $checkStmt->fetchColumn();

    if ($emailExists) {
        echo "⚠️ This email is already registered.";
         exit;
}

        $query = "INSERT INTO users (name, lastname, email, password, region, cycle, role)
        VALUES (:name, :lastname, :email, :password, :region, :cycle, :role)";
        $stmt = $conn->prepare($query);
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password); 
        $stmt->bindParam(':region', $region);
        $stmt->bindParam(':cycle', $cycle);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        echo " User inserted successfully!";
    } catch (PDOException $e) {
        echo " PDO Error: " . $e->getMessage();
    }
    
}