<?php

include_once "database.php";
include_once "thread_utils.php";

$conn = getConnection();

$before = $_POST["before"];

$threads = getOldThreads($conn, $before, 10);

$conn->close();

printThreads($threads)

?>