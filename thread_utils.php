<?php

include_once "database.php";

function printThreads($threads) {
    
    $arr = array();
    
    foreach ($threads as $thread) {
    	$threadId = $thread->id;
    	$title = $thread->title;
    	$content = $thread->content;
    	$user = $thread->user;
    	$dateCreated = $thread->dateCreated;
    	$commentCount = $thread->commentCount;
    	$size = min(20,strlen($content));
    	if (preg_match("([<].+)", substr($content, 0, $size)))
    	{
    	    // Cut style tags 
    	    $content = preg_replace('(style=[\'|"].*?[\'|"])', "", $content);
    	    // Limit image size.
    	    $content = preg_replace("([<][i][m][g])", "<img style='max-height:400px; max-width:400px;'", $content);
    		// Search string for the end of the content
    		$endContent = substr($content, $size, strlen($content));
    		// The position of the end of the tag to be cut
    		// Will currently break if the user uses mismatching tags.
    		$lastTag = strpos($endContent, ">");
    		if ($lastTag > 0) {
    		    // If there's a tag, add it's position to the size
    		    $size = $size + $lastTag + 1;
    		}
    	}
    	$cutContent = substr($content,0,$size);
    	if(strlen($content) > 20 && $lastTag == 0){
    	    $cutContent = $cutContent.'...';
    	}
    
        $arr[] = array(
            "threadId" => $threadId,
            "title" => $title,
            "user" => $user,
            "dateCreated" => $dateCreated,
            "cutContent" => $cutContent,
            "commentCount" => $commentCount
        );
    }
    
    print(json_encode($arr));
    
}

?>