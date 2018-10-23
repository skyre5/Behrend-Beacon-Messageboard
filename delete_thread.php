<?php
include_once 'database.php';
$conn = getConnection();
$threadID = $_POST["thread"];
$user = getCurrentUserId($conn);
if($user != -1){
    if(getCurrentUserPriv($conn) > 1)
    {
        //Doesn't even delete the comments associated with it. Sheesh
        //$stmt = $conn->prepare("DELETE FROM Comments WHERE ThreadId = $threadID");
        //$stmt->execute();
        $stmt = $conn->prepare("DELETE FROM Threads WHERE ThreadId = ?");
        $stmt->bind_param("i", $threadID);
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