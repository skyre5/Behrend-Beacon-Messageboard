<?php
    $user = $_COOKIE["user"];
	$message=(isset($_POST['message']) ? $_POST['message'] : '');
	
	if (empty($user)){
	    $user = 'Anon';
	}
    echo "<h5 id='chat-username' style='display: inline'>" . $user . ":  </h5><p id='disp-message' style='display: inline'>" . $message . "</p></br>";
    
?>