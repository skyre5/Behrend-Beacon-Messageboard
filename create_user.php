<?php

include 'database.php';
$conn = getConnection();
$user = $_POST["UserName"];
$user = strip_tags($user);
$pass = $_POST["Password"];
$pass2 = $_POST["RetypedPassword"];

if ($pass == $pass2) {
    if ($user != "")
    {
        $err = createUser($conn, $user, $pass);
    } else
    {
        $err = 3;
    }
    
    
    if ($err == 0) {
        print(0);
    } else if ($err == 1062) {
        print(2);
    } else if($err == 3) {
        print(3);
    } else {
        print(-1);
    }
} else {
    print(1);
}

$conn->close();

?>