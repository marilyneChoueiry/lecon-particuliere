<?php 
session_start();
if(isset($_SESSION['panier']))&&!empty($_SESSION['panier']) {
echo"<h2> Teacher:</h2>;

foreach($_SESSION['panier']) as
$index=>$teacher) {
echo "<strong>
Teacher".
($index +1)."</strong><br>";
echo "First Name:". 
htmlspecialchars($teacher['first_name'])
."<br>";
echo "Last Name:". 
htmlspecialchars($teacher['last_name'])
."<br>";
echo "Email:". 
htmlspecialchars($teacher['email'])
."<br>";
echo "cycle:". 
htmlspecialchars($teacher['cycle'])
."<br>";
echo "Subject:". 
htmlspecialchars($teacher['subject'])
."<br>";

echo"<hr>;
}else{
echo "no teacher in the panier";
}
?>









}





}