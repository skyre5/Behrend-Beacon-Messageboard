<?php

include_once "database.php";
include_once "thread_utils.php";

$conn = getConnection();

$since = $_POST["since"];

$threads = getNewThreads($conn, $since, null);

$conn->close();

printThreads($threads)

?>