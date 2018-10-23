<?php
include_once 'database.php';
$conn = getConnection();
$thread = $_POST["thread"];
$content = nl2br($_POST["content"]);
$content = strip_tags($content, '<img> <a>');
$user = getCurrentUserId($conn);
if($user != -1){
    $stmt = $conn->prepare("INSERT INTO Comments(ThreadId, UserId, Content, DateCreated) VALUES (?, ?, ?, CURRENT_TIMESTAMP - INTERVAL 4 HOUR)");
    $stmt->bind_param("iis", $thread, $user, $content);
    $stmt->execute();
    print(true);
} else {
    print(false);
}
$conn->close();
?>