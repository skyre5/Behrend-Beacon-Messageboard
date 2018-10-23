<?php

$salt = "SuperSaltySalt";

$DATE_FORMAT = "%M %e, %Y - %h:%i %p";

class User {

	public $name;
	public $userID;
	public $threadCount;
	public $commentCount;
	public $COUNTER;
	public $Privileges;
	public function __construct($Privileges,$name,$userID,$threadCount,$commentCount,$COUNTER) {
		$this->name = $name;
		$this->userID = $userID;
		$this->threadCount = $threadCount;
		$this->commentCount= $commentCount;
		$this->COUNTER=$COUNTER;
		$this->Privileges=$Privileges;
	}

}

class Thread {

	public $id;
	public $title;
	public $content;
	public $user;
	public $dateCreated;
	public $commentCount;

	public function __construct($id, $title, $content, $user, $dateCreated, $commentCount) {
		$this->id = $id;
		$this->title = $title;
		$this->content = $content;
		$this->user = $user;
		$this->dateCreated = $dateCreated;
		$this->commentCount = $commentCount;
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
	return new mysqli("52.14.78.60:4321", "master", "SkyIsTheLimit!", "beacon");
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
//Moustafa
//Look at getThreads in here for how to do it
function getUserList($conn){
	$stmt = $conn->prepare("Select Name,Users.Privileges, Users.UserId, T.threadCount, T2.commentCount from Users
left join(SELECT UserId, count(*) as threadCount from Threads group by UserId) as T
on T.UserId = Users.UserId
left join(SELECT UserId, count(*) as commentCount from Comments group by UserId) as T2
on T2.UserId = Users.UserId");

	$stmt->execute();
	$res = $stmt->get_result();
	
	$users = array();
	$COUNTER = 1;
	while ($row = $res->fetch_assoc()) {

		$name = $row["Name"];
		$userID = $row["UserId"];
		$threadCount = $row["threadCount"];
		$commentCount = $row["commentCount"];
		$Privileges = $row["Privileges"];
		
		if (!$threadCount) {
			$threadCount = 0;
		}
		if (!$commentCount) {
			$commentCount = 0;
		}
		
		
		
		$users[$COUNTER] = new User($Privileges,$name,$userID,$threadCount,$commentCount,$COUNTER);
		$COUNTER++;
	}

	return $users;

}
function getThreadById($conn, $threadId) {
	global $DATE_FORMAT;
	$res = $conn->query("SELECT Title, Content, UserId, DATE_FORMAT(DateCreated, '$DATE_FORMAT') AS DateCreated FROM Threads WHERE ThreadId = $threadId");
	$row = $res->fetch_assoc();
	
	$commentCount = getCommentCount($conn, $threadId);
	$title = $row["Title"];
	$content = $row["Content"];
	$userId = $row["UserId"];
	$user = getUserById($conn, $userId);
	$date = $row["DateCreated"];
	
	return new Thread($threadId, $title, $content, $user, $date, $commentCount);
}

function getNewThreads($conn, $afterId, $limit) {
	return getThreads($conn, ">", $afterId, $limit);
}

function getOldThreads($conn, $beforeId, $limit) {
	return getThreads($conn, "<", $beforeId, $limit);
}

function getThreads($conn, $comare, $id, $limit) {
	
	global $DATE_FORMAT;
	
	$limitSql = "";
	if ($limit) {
		$limitSql = "LIMIT ?";
	}
	
	$stmt = $conn->prepare("SELECT ThreadId, Title, Content, UserId, DateCreated as Date, DATE_FORMAT(DateCreated, '$DATE_FORMAT') AS DateCreated FROM Threads where ThreadId $comare ? ORDER BY Date DESC $limitSql");
	if ($limit) {
		$stmt->bind_param("ii", $id, $limit);
	} else {
		$stmt->bind_param("i", $id);
	}
	$stmt->execute();
	$res = $stmt->get_result();
	
	$threads = array();

	while ($row = $res->fetch_assoc()) {

		$threadId = $row["ThreadId"];
		$commentCount = getCommentCount($conn, $threadId);
		$title = $row["Title"];
		$content = $row["Content"];
		$userId = $row["UserId"];
		$user = getUserById($conn, $userId);
		if (!$user) {
		    $user = "unknown";
		}
		$date = $row["DateCreated"];

		$threads[$threadId] = new Thread($threadId, $title, $content, $user, $date, $commentCount);

	}

	return $threads;
}

function getInitialThreads($conn) {
	return getNewThreads($conn, -1, 10);
}

function deleteThread($conn, $threadId){
	$stmt = $conn->prepare("Delete from Threads where ThreadId = $threadId");
	$stmt->execute();
}


function getCommentCount($conn, $threadId) {
	$stmt = $conn->prepare("SELECT count(*) as Number from Comments where ThreadId = ?");
	$stmt->bind_param("i", $threadId);
	$stmt->execute();
	$res = $stmt->get_result();
	$row = $res->fetch_assoc();
	return $row["Number"];
}

function getNewComments($conn, $threadId, $sinceId) {
	global $DATE_FORMAT;
	$stmt = $conn->prepare("SELECT CommentId, Content, UserId, DATE_FORMAT(DateCreated, '$DATE_FORMAT') AS DateCreated FROM Comments WHERE ThreadId = ? AND CommentId > ?");
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
	return getNewComments($conn, $threadId, -1);
}

function createUser($conn, $user, $pass) {
	$hash = hashPass($pass);
	$stmt = $conn->prepare("INSERT INTO Users (Name, Privileges, Pass) VALUES (?, 0, ?)");
	$stmt->bind_param("ss", $user, $hash);
	$stmt->execute();
	return $stmt->errno;
}

function hashPass($pass) {
	global $salt;
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

function getCurrentUserName($conn) {
	return getUserById($conn, getCurrentUserId($conn));
}

function getCurrentUserPriv($conn) {
	$stmt = $conn->prepare("SELECT Privileges FROM Users WHERE UserId = ?");
	$stmt->bind_param("i", getCurrentUserId($conn));
	$stmt->execute();
	$res = $stmt->get_result();
	
	$row = $res->fetch_assoc();
	
	return $row["Privileges"];
}

function getNewChat($conn, $sinceId) {
	$stmt = $conn->prepare("SELECT ChatId, UserId, Message, Date FROM Messages WHERE ChatId > ? AND Date > (CURRENT_TIMESTAMP - INTERVAL 4 HOUR - INTERVAL 1 MINUTE)");
	$stmt->bind_param("i", $sinceId);
	$stmt->execute();
	$res = $stmt->get_result();

	$chats = array();

	while ($row = $res->fetch_assoc()) {
		$chatId = $row["ChatId"];
		$userId = $row["UserId"];
		$message = $row["Message"];
		$date = $row["Date"];

		$chats[$chatId] = new Chat($chatId, $userId, $message, $date);
	}

	return $chats;
}

function addChat($conn, $userId, $message) {
	$stmt = $conn->prepare("INSERT INTO Messages (UserId, Message, Date) VALUES (?, ?, CURRENT_TIMESTAMP - INTERVAL 4 HOUR)");
	$stmt->bind_param("is", $userId, $message);
	$stmt->execute();
}

?>