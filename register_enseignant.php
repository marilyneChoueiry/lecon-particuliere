<?php
session_start();
require_once __DIR__ . '/../function.php';
if($_SERVER["REQUEST_METHOD"] != 'POST'){
    die

    function validatePassword($password) {
        return preg_match('/[A-Z]/', $password) && 
               preg_match('/[a-z]/', $password) && 
               preg_match('/[0-9]/', $password) && 
               preg_match('/[\W_]/', $password) && 
    }
    function validateEmail($email)
    {
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        return preg_match($pattern, $email);
    }

    if(
        isset($_POST['lastName'])
        && isset($_POST['firstName']) 
        && isset($_POST['email']) 
        && isset($_POST['password']) 
        && isset($_POST['description']) 
        && isset($_POST['region']) 
        && isset($_POST['subject'])
        && isset($_POST['cycle'])
        && isset($_FILES['cv'])
        )
    }
            extract($_POST);


            $name = trim($firstName);
            if (strlen($firstName) < 2) {
                
                
            }



            $name = trim($lastName);
            if (strlen($lastName) < 2) {
               die
            }
        
            if (!validateEmail($email)) {      
                die
            }
        
            if (!validatePassword($password)) {               
                die
            }
            if (count(region) == 0) {             
                die
            }
            $description = trim(description);
            if (strlen($description) < 10) {            
                die
            }
            if (count(subject) == 0) {             
                die
            }
        if (count(cycle) == 0) {             
            die
        }
    
    if($_FILES["cv"]["error"] !== 0) die
     if($_FILES["cv"]["type"] != 'application/pdf') die
     if($_FILES["cv"]["size"] > (5 * 1024 * 1024)) die

     


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
}last_name

        $query = "INSERT INTO users (fist_name, last_name, email, password, region, cycle, description,cv,role)
        VALUES (:name, :lastname, :email, :password, :region, :cycle, :role)";
        $stmt = $conn->prepare($query);
        
        $stmt->bindParam(':fist_name', $fist_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password); 
        $stmt->bindParam(':region', $region);
        $stmt->bindParam(':cycle', $cycle);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':cv', $cv);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        echo " User inserted successfully!";
    } catch (PDOException $e) {
        echo " PDO Error: " . $e->getMessage();
    }
    


$firstName=$_POST['fist_name'];
$lastName=$_POST['last_name'];
$region=$_POST['region'];
$cycle=$_POST['cycle'];
$subject=$_POST['subject'];

$teacher=[
'first_name'=>$firstName,
'last_name'=>$lastName,
'email'=>$email,
'region'=>$region,
'cycle'=>$cycle,
'subject'=>$subject,
];
if(!isset($_SESSION['panier'])){
$_SESSION['panier']=[];

$_SESSION['panier'][]=$teacher;
echo "teacher added to the panier";

}

       
       
