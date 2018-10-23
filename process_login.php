<?php

include_once 'database.php';
$conn = getConnection();
$user = $_POST["userName"];
$pass = $_POST["passWord"];
print(login($conn, $user, $pass));
?>