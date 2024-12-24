<?php 

$db_server = "localhost";
$db_user = "root";
$db_password = "12345chadli";
$db_name = "blogpress";

$conn = mysqli_connect($db_server, $db_user, $db_password, $db_name);

if($conn){
    // echo "connected succsefully";
}
else{
    // echo "probleme de connection"; 
}


?>