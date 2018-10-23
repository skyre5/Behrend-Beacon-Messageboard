<?php

include_once "database.php";

$conn = getConnection();

$since = $_POST["since"];

$chats = getNewChat($conn, $since);

$arr = array();

foreach ($chats as $chat) {
	$chatId = $chat->chatId;
	$userId = $chat->userId;
	$message = $chat->message;
	$date = $chat->date;
	$userName = getUserById($conn, $userId);
	if (!$userName) {
	    $userName = "Anon";
	}

    $arr[] = array(
        "chatId" => $chatId,
        "userName" => $userName,
        "message" => $message,
        "date" => $date
    );
}
$conn->close();


print(json_encode($arr));

?>