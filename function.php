<?php 
function getPdoConnention (){
   /* $servername='localhost';
    $username ='root';
    $password='';
    $dbname='lecon-particuliere';
*/
    try{
        $conn = new PDO("mysql:host=localhost;dbname=lecon-particuliere;charset=utf8", "root", "");
        $conn -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
         return $conn;
    }catch(PDOException $e){
        die("Connection failed: " . $e->getMessage());
    }
}
?>