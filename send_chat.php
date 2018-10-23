<?php
include_once 'database.php';

$conn = getConnection();

$message = $_POST["message"];
$message = strip_tags($message, "");
$message = nl2br($message);

$user = getCurrentUserId($conn);

if($user != -1){
    addChat($conn, $user, $message);
    print(true);
} else {
    addChat($conn, $user, $message);
    print(false);
}

$conn->close();
?>