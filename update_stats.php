<?php


include_once "database.php";

$conn = getConnection();
$stmt = $conn->query("SELECT count(*) as ThreadCount from Threads");
$row = $stmt->fetch_assoc();
$threadCount = $row["ThreadCount"];

$stmt = $conn->query("SELECT count(*) as UserCount from Users");
$row = $stmt->fetch_assoc();
$userCount = $row["UserCount"];

$stmt = $conn->query("SELECT count(*) as CommentCount from Comments");
$row = $stmt->fetch_assoc();
$commentCount = $row["CommentCount"];

$stmt = $conn->query("SELECT sum(Views) as TotalViews from Threads");
$row = $stmt->fetch_assoc();
$totalViews = $row["TotalViews"];

$stmt = $conn->query("Select Name from Users inner join(SELECT UserId, count(*) 
as Num from Threads group by UserId
order by Num desc limit 1) as T
on Users.UserId = T.UserId");
$row = $stmt->fetch_assoc();
$bestPoster = $row["Name"];

$stmt = $conn->query("Select Name from Users inner join(SELECT UserId, count(*) 
as Num from Comments group by UserId
order by Num desc limit 1) as T
on Users.UserId = T.UserId");
$row = $stmt->fetch_assoc();
$bestCommentor = $row["Name"];

$arr = array();
$arr[] = array(
    "threadCount" => $threadCount,
    "userCount" => $userCount,
    "commentCount" => $commentCount,
    "totalViews" => $totalViews,
    "bestPoster" => $bestPoster,
    "bestCommentor" => $bestCommentor
    );
    
$conn->close();
//$newInfo->threadCount = $threadCount;
//$newInfo->userCount = $userCount;
//$newInfo->commentCount = $commentCount;
//$newInfo->totalViews = $totalViews;
//$newInfo->bestPoster = $bestPoster;
//$newInfo->bestCommentor = $bestCommentor;
print(json_encode($arr));
?>