<?php include_once 'function.php';
function isValidPassword($password) {
    $passLength = strlen($password) >= 8;
    $uppercase    = preg_match('@[A-Z]@', $password);
    $lowercase    = preg_match('@[a-z]@', $password);
    $number       = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    return $passLength && $uppercase && $lowercase && $number && $specialChars;
}



if($_SERVER["REQUEST_METHOD"] == 'POST'){
    if(
        !isset($_POST["name"]) 
        || empty(trim($_POST["name"]))
        || !isset($_POST['lastName'])
        || empty(trim($_POST["lastName"]))
        || !isset($_POST["email"]) 
        || empty(trim($_POST["email"]))
        || !isset($_POST["password"]) 
        || empty(trim($_POST["password"]))
        || !isset($_POST["region"]) 
        || !isset($_post['cycle'])
        || empty(trim($_post['cycle']))
    ){
    
        http_response_code(400);
        header("location:student.php?error=emptyFields");
    } else {
        $error = [];


        //name and last name Validation
        $name = htmlspecialchars(trim($_POST["name"]));
        if(!preg_match("/^[a-zA-Z]{3}[a-zA-Z ]{0,21}$/", $name)){
            $error["name"] = "Invalid Name";
        }
        $lastname = htmlspecialchars(trim($_POST["lastname"]));
        if(!preg_match("/^[a-zA-Z]{3}[a-zA-Z ]{0,21}$/", $name)){
            $error["name"] = "Invalid Name";
        }

        // Email Validation
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error["email"] = "Invalid Email";
        }

        // Password Validation
        $password = trim($_POST["password"]);
        if(!isValidPassword($password)){
            $error["password"] = "Invalid Password";
        }
         // region Validation
         $nationality = intval($_POST["region"]);
         if($region < 1 || $region > 9){
             $error["region"] = "Invalid region";            
         }
        $cycle=$_POST['cycle'];

         $conn -> getPdoConnention ();
         $query ="INSERT INTO users (name, lastname,email,pasword,region,cycle) VALUES (:name,:lastname, :email , :pasword,:region,:cycle)";
         $stmt =$pdo-> prepare ($query);
         stmt-> blindParam(':name',$name);
         stmt-> blindParam(':lastname',$lastname);
         stmt-> blindParam('::email',$email);
         stmt-> blindParam(':pasword',$password);
         stmt-> blindParam(':region',$region);
         stmt-> blindParam('cycle',$cycle);
        }      

        
      }
    


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
          font-family: Arial, sans-serif;
          background-color: #f0f4f8;
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
          margin: 0;
        }
    
        form {
          background-color: #ffffff;
          padding: 30px 40px;
          border-radius: 8px;
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
          width: 350px;
        }
    
        h2 {
          text-align: center;
          margin-bottom: 20px;
          color: #333;
        }
    
        .student {
          margin-bottom: 15px;
        }
    
        label {
          display: block;
          margin-bottom: 5px;
          color: #333;
          font-weight: bold;
        }
    
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
          width: 100%;
          padding: 10px;
          border: 1px solid #ccc;
          border-radius: 5px;
          font-size: 14px;
        }
    
        button {
          width: 100%;
          padding: 12px;
          background-color: #007BFF;
          color: white;
          border: none;
          border-radius: 5px;
          font-size: 16px;
          cursor: pointer;
        }
    
        button:hover {
          background-color: #0056b3;
        }
        .error-message{
            margin-top: 20px; 
            padding: 10px; 
            border: 1px solid red; 
            background-color: #ffe6e6; 
            color: red; 
            font-weight: bold;
        }
      </style>
      
</head>
<body>
    <form action="formValidation.php" method="POST">
        
        
        <h2>sign up for student</h2>
        <div class="student">
            <label >Name</label>
            <input type="text"  class="name" name="name">
            <?php if(isset($error["name"])) echo "<div id='error' class='error-message'>" . $error["name"] . "</div>"; ?>
          </div>
          <br><br>
          <div class="student">
            <label >Last name</label>
            
            <input type="text"  class="lastName" name="lastName">
          </div>
          <br><br>
        <div class="student">
            <label >Email</label>
            
            <input type="email"  class="email" name="email">
          </div>
        <br><br>
          <div class="student">
            <label >Password</label>
           
            <input type="password" class="password"  name="password">
            <?php if(isset($error["password"])) echo "<div id='error' class='error-message'>" . $error["password"] . "</div>"; ?>
          </div>
          <br><br>
          <div class="student">
            <label >Region</label>
           
            <select  class="region" name ="region">
              <option selected>--Choose the region--</option>
              <option>Beirut</option>
              <option>Mount Lebanon</option>
              <option>South</option>
              <option>Nabatieh</option>
              <option>Beqaa</option>
              <option>jbeilt</option>
              <option>North</option>
              <option>Baalbek</option>
              <option>Akkar</option>
              
            </select>
            <br><br>
            <div>
                <label>choose cycle</label>
                <input class="cycle" type="checkbox" value="Elementary" >Elementary
                <br>
                <input class="cycle" type="checkbox" value="Middle School" >Middle School
               <br>
                <input class="cycle" type="checkbox" value="High School" >High School
                
                
             </div>
            <br>
            <div class="student">
            <button type="submit" class="submit">Sign in</button>
          </div>
    </form>
</body>



