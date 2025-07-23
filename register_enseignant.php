<?php
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
        isset($_POST['LastName'])
        && isset($_POST['FirstName']) 
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


            $name = trim($FirstName);
            if (strlen($FirstName) < 2) {
                
                
            }



            $name = trim($LastName);
            if (strlen($LastName) < 2) {
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
 