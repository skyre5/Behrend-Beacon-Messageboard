<?php

include_once "database.php";

$conn = getConnection();

$thread = $_POST["thread"];
$since = $_POST["since"];

$comments = getNewComments($conn, $thread, $since);

$conn->close();

$arr = array();

foreach ($comments as $comment) {
	$commentId = $comment->commentId;
	$content = $comment->content;
	$user = $comment->user;
	$dateCreated = $comment->dateCreated;

    $arr[] = array(
        "commentId" => $commentId,
        "content" => $content,
        "user" => $user,
        "dateCreated" => $dateCreated
    );
}

print(json_encode($arr));

?>