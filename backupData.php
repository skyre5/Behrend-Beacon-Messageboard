<?php

$salt = "SuperSaltySalt";

class Thread {

	public $id;
	public $title;
	public $content;
	public $user;
	public $dateCreated;

	public function __construct($id, $title, $content, $user, $dateCreated) {
		$this->id = $id;
		$this->title = $title;
		$this->content = $content;
		$this->user = $user;
		$this->dateCreated = $dateCreated;
	}

}

class Comment {

	public $commentId;
	public $content;
	public $user;
	public $dateCreated;

	public function __construct($commentId, $content, $user, $dateCreated) {
		$this->commentId = $commentId;
		$this->content = $content;
		$this->user = $user;
		$this->dateCreated = $dateCreated;
	}

}

class Chat {

	public $chatId;
	public $userId;
	public $message;
	public $date;

	public function __construct($chatId, $userId, $message, $date) {
		$this->chatId = $chatId;
		$this->userId = $userId;
		$this->message = $message;
		$this->date = $date;
	}

}

function getConnection() {
	return new mysqli("beacon.cphux8mhfagi.us-east-1.rds.amazonaws.com:4321", "master", "SkyIsTheLimit!", "beacon");
}

function getUserById($conn, $userId) {
	$res = $conn->query("SELECT Name FROM Users WHERE UserId = $userId");
	if ($res) {
		$row = $res->fetch_assoc();
		$name = $row["Name"];
		if ($name) {
			return $name;
		}
	}
	return null;
}

function getThreadById($conn, $threadId) {
	$res = $conn->query("SELECT Title, Content, UserId, DATE_FORMAT(DateCreated, '%M %e, %Y') AS DateCreated FROM Threads WHERE ThreadId = $threadId");
	$row = $res->fetch_assoc();
	
	$title = $row["Title"];
	$content = $row["Content"];
	$userId = $row["UserId"];
	$user = getUserById($conn, $userId);
	$date = $row["DateCreated"];
	
	return new Thread($threadId, $title, $content, $user, $date);
}

function getNewThreads($conn, $sinceId) {
	$res = $conn->query("SELECT ThreadId, Title, Content, UserId, DATE_FORMAT(DateCreated, '%M %e, %Y') AS DateCreated FROM Threads where ThreadId > $sinceId");

	$threads = array();

	while ($row = $res->fetch_assoc()) {

		$threadId = $row["ThreadId"];
		$title = $row["Title"];
		$content = $row["Content"];
		$userId = $row["UserId"];
		$user = getUserById($conn, $userId);
		if (!$user) {
		    $user = "unknown";
		}
		$date = $row["DateCreated"];

		$threads[$threadId] = new Thread($threadId, $title, $content, $user, $date);

	}

	return $threads;
}

function getThreads($conn) {
	$resV2 = $conn->query("SELECT COUNT(ThreadId) as Num FROM Threads");
	$rowV2 = $resV2->fetch_assoc();
	$num_rows = $rowV2["Num"];
	$res = $conn->query("SELECT ThreadId, Title, Content, UserId, DATE_FORMAT(DateCreated, '%M %e, %Y') AS DateCreated FROM Threads LIMIT 10 OFFSET ($num_rows - 10)");

	$threads = array();

	while ($row = $res->fetch_assoc()) {

		$threadId = $row["ThreadId"];
		//Gets the comment count if we end up using it
		//$commentCount = getCommentCount($conn,$threadId);
		
		$title = $row["Title"];
		$content = $row["Content"];
		$userId = $row["UserId"];
		$user = getUserById($conn, $userId);
		if (!$user) {
		    $user = "unknown";
		}
		$date = $row["DateCreated"];

		$threads[$threadId] = new Thread($threadId, $title, $content, $user, $date);

	}

	return $threads;
}
//function deleteThread($conn, $threadId){
	//stmt = $conn->prepare("Delete from Comments where ThreadId = $threadId");
	//stmt->execute();
	//stmt2 = $conn->prepare(Delete from Threads where ThreadId = $threadId);
	//stmt2->execute();
//}
//function deleteComment($conn, $commentId){
	//$stmt = $conn->prepare("Delete from Comments where CommentId = $commentId");
	//$stmt->execute();
//}

//function getCommentCount($conn,$threadId){
	//$stmt = $conn->query("SELECT count(*) as Number from Comments where $threadId = ThreadId");
	//$row = $res->fetch_assoc();
	//return $row["Number"];
//}


function getNewComments($conn, $threadId, $sinceId) {
	$stmt = $conn->prepare("SELECT CommentId, Content, UserId, DATE_FORMAT(DateCreated, '%M %e, %Y') AS DateCreated FROM Comments WHERE ThreadId = ? AND CommentId > ?");
	$stmt->bind_param("ii", $threadId, $sinceId);
	$stmt->execute();
	$res = $stmt->get_result();

	$comments = array();

	while ($row = $res->fetch_assoc()) {
		$commentId = $row["CommentId"];
		$content = $row["Content"];
		$userId = $row["UserId"];
		$user = getUserById($conn, $userId);
		if (!$user) {
		    $user = "unknown";
		}
		$date = $row["DateCreated"];

		$comments[$commentId] = new Comment($commentId, $content, $user, $date);
	}

	return $comments;
}

function getComments($conn, $threadId) {
	$stmt = $conn->prepare("SELECT CommentId, Content, UserId, DATE_FORMAT(DateCreated, '%M %e, %Y') AS DateCreated FROM Comments WHERE ThreadId = ?");
	$stmt->bind_param("i", $threadId);
	$stmt->execute();
	$res = $stmt->get_result();

	$comments = array();

	while ($row = $res->fetch_assoc()) {
		$commentId = $row["CommentId"];
		$content = $row["Content"];
		$userId = $row["UserId"];
		$user = getUserById($conn, $userId);
		if (!$user) {
		    $user = "unknown";
		}
		$date = $row["DateCreated"];

		$comments[$commentId] = new Comment($commentId, $content, $user, $date);
	}

	return $comments;
}

function createUser($conn, $user, $pass) {
	$hash = hashPass($pass);
	$stmt = $conn->prepare("INSERT INTO Users (Name, Privileges, Pass) VALUES (?, 0, ?)");
	$stmt->bind_param("ss", $user, $hash);
	$stmt->execute();
}

function hashPass($pass) {
	return hash("sha512", "$salt$pass");
}

function getUserPassHash($conn, $user) {
	$stmt = $conn->prepare("SELECT Pass FROM Users WHERE Name = ?");
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$res = $stmt->get_result();
	$row = $res->fetch_assoc();
	return $row["Pass"];
}

function login($conn, $user, $pass) {
	if (hashPass($pass) == getUserPassHash($conn, $user)) {
		$session = md5(microtime().$_SERVER['REMOTE_ADDR']);
		saveSessionKey($conn, $user, $session);
		return true;
	}
	return false;
}

function logout() {
	setcookie("user", "", time() - 3600, "/", "behrend-beacon-rabisu.c9users.io");
	setcookie("session", "", time() - 3600, "/", "behrend-beacon-rabisu.c9users.io");
}

function saveSessionKey($conn, $user, $session) {
	$stmt = $conn->prepare("UPDATE Users SET SessionId = ? WHERE Name = ?");
	$stmt->bind_param("ss", $session, $user);
	$stmt->execute();
	
	$time = time()+60*60*24*30;
	setcookie("user", $user, $time, "/", "behrend-beacon-rabisu.c9users.io");
	setcookie("session", $session, $time, "/", "behrend-beacon-rabisu.c9users.io");
}

function getSessionKey() {
	return $_COOKIE["session"];
}

function getCurrentUserId($conn) {
	//Returns -1 if there is no user logged in
	
	$user = $_COOKIE["user"];
	$session = $_COOKIE["session"];
	
	if ($user && $session) {
		$stmt = $conn->prepare("SELECT UserId FROM Users WHERE Name = ? AND SessionId = ?");
		$stmt->bind_param("ss", $user, $session);
		$stmt->execute();
		$res = $stmt->get_result();
		$row = $res->fetch_assoc();
		return $row["UserId"];
	}
	return -1;
}

function getCurrentUserName($conn){
	return getUserById($conn, getCurrentUserId($conn));
}

function getNewChat($conn, $sinceId) {
	$stmt = $conn->prepare("SELECT ChatId, UserId, Message, Date, FROM Messages WHERE ChatId > ?");
	$stmt->bind_param("i", $sinceId);
	$stmt->execute();
	$res = $stmt->get_result();

	$chats = array();

	while ($row = $res->fetch_assoc()) {
		$chatId = $row["ChatId"];
		$userId = $row["UserId"];
		$message = $row["Message"];
		$date = $row["Date"];

		$chats[$chatId] = new Comment($chatId, $userId, $message, $date);
	}

	return $chats;
}

function addChat($userId, $message) {
	$stmt = $conn->prepare("INSERT INTO Messages (UserId, Message, Date) VALUES (?, ?, CURRENT_TIMESTAMP)");
	$stmt->bind_param("is", $userId, $message);
	$stmt->execute();
}

?>