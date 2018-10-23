<?php
    $user = (isset($_POST['data.userId']) ? $_POST['data.userId'] : '');
	$message=(isset($_POST['data.message']) ? $_POST['data.message'] : '');
	
	if (empty($user)){
	    $user = 'Anon';
	}
    echo "<h5 id='chat-username' style='display: inline'>" . $user . ":  </h5><p id='disp-message' style='display: inline'>" . $message . "</p></br>";
    
?>