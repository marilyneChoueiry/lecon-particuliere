<?php 
function getPdoConnention (){
    $servername="127.0.0.1";
    $username ="root";
    $password="";
    $dbname="lecon-particuliere";

    try{
        $con=new PDO("mysql:host = $servername;dbmane=$dbname;$username;$password");
        $conn -> setAttribute(PDO::Attr_ERRMODE,PDO::HERRMODE_EXCEPTION);

    }catch(PDOException $e){
        echo "Connection failed".$e -> getMessage();
        return null;
    }
}
?>