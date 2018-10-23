<?php
include_once 'database.php';
$conn = getConnection();
$commentID = $_POST["comment"];
$user = getCurrentUserId($conn);
if($user != -1){
    if(getCurrentUserPriv($conn) > 1)
    {
        $stmt = $conn->prepare("DELETE FROM Comments WHERE CommentId = ?");
        $stmt->bind_param("i", $commentID);
        $stmt->execute();
        print(true);
    } else {
        print(false);
    }
} else {
    print(false);
}
$conn->close();
?>