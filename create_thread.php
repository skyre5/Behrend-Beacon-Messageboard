<?php
include_once 'database.php';
$conn = getConnection();
$title = $_POST["title"];
$title = strip_tags($title);
$content = return_to_p($_POST["content"]);
$content = strip_tags($content, '<p> <a> <img> <i> <b> <br> <code> <em> <hr> <s> <strong> <sub> <sup> <h2> <h3> <h4>');
$user = getCurrentUserId($conn);
if($user != -1 && $title != "" && $content != ""){
    $stmt = $conn->prepare("INSERT INTO Threads(UserId, Title, Content, DateCreated) VALUES (?, ?, ?, CURRENT_TIMESTAMP - INTERVAL 4 HOUR)");
    $stmt->bind_param("sss",$user, $title, $content);
    $stmt->execute();
    print(true);
} else {
    print(false);
}

function return_to_p($content) {
    $content = preg_replace('(/\r\n|\r|\n/)', "</p><p class='thread'>", $content);
    $temp = "<p class='thread'>";
    $temp .= $content;
    $temp .= "</p>";
    return $temp;
}



$conn->close();
?>