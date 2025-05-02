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
      </style>
      
</head>
<body>
    <form action="formValidation.php" method="POST">
        <h2>sign in for student</h2>
        <div class="student">
            <label >Name</label>
            <input type="text"  class="name">
          </div>
          <br><br>
          <div class="student">
            <label >Last name</label>
            <input type="text"  class="lastName">
          </div>
          <br><br>
        <div class="student">
            <label >Email</label>
            <input type="email"  class="email">
          </div>
        <br><br>
          <div class="student">
            <label >Password</label>
            <input type="password" class="password" >
            <?php if(isset($error["password"])) echo "<div id='error' class='error-message'>" . $error["password"] . "</div>"; ?>
          </div>
          <br><br>
          <div class="student">
            <label >Region</label>
            <select  class="region">
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
</html>
