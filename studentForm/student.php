<!DOCTYPE html> 
<?php include_once 'formValidation.php';?>

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
            <input type="text"   name="name"required>
            <?php if(isset($error["name"])) echo "<div id='error' class='error-message'>" . $error["name"] . "</div>"; ?>
          </div>
          <br><br>
          <div class="student">
            <label >Last name</label>
            
            <input type="text"   name="lastName" required>
          </div>
          <br><br>
        <div class="student">
            <label >Email</label>
            
            <input type="email"   name="email">
          </div>
        <br><br>
          <div class="student">
            <label >Password</label>
           
            <input type="password"   name="password" required>
            <?php if(isset($error["password"])) echo "<div id='error' class='error-message'>" . $error["password"] . "</div>"; ?>
          </div>
          <br><br>
          <div class="student">
            <label >Region</label>
           
            <select   name ="region" required>
              <option selected value="">--Choose the region--</option>
              <option>Beirut</option>
              <option>Mount Lebanon</option>
              <option>South</option>
              <option>Nabatieh</option>
              <option>Beqaa</option>
              <option>jbeil</option>
              <option>North</option>
              <option>Baalbek</option>
              <option>Akkar</option>
              
            </select>
            <br><br>
            <div>
                <label>choose cycle</label>
                <input  name="cycle"  type="checkbox" value="Elementary" >Elementary
                <br>
                <input  name="cycle"  type="checkbox" value="Middle School" >Middle School
               <br>
                <input  name="cycle"  type="checkbox" value="High School" >High School
                
                
             </div>
              <input type="hidden" name="role" value="student">
            <br>
            <div class="student">
            <button type="submit" class="submit" name="submit"  >Sign in</button>
          </div>
    </form>
</body>



