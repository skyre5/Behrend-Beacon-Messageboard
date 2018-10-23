<?php
include_once 'database.php';
$conn = getConnection();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include "css.php" ?>

</head>

<body>
    
    <?php
    
    $headerTitle = "The Behrend Beacon";
    $tag = "h1";
    $subheading = -1;
	include_once 'header.php';
	
    $modalButton = 
    "<div class='container' style='text-align:center'>
        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#threadModal'>Create a thread</button>
    </div>";
    if (getCurrentUserPriv($conn) > 0) {
        print($modalButton);
    }
    ?>
            
    <!-- New Thread Button -->
    <div id='newThreadDiv' class='container' style='text-align:center'>
        <hr>
        <button class="btn btn-primary" id="newThreadButton"  type="button"></button>
    </div>
    
    <!-- Thread Modal -->
    <div class="modal fade" id="threadModal" tabindex="-3" role="dialog" aria-labelledby="threadModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="threadModalLabel">Create a thread!</h4>
                </div>
                <div class="modal-body">
                    <!-- Threads -->
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8"> <!-- col-lg-offset-2  col-md-10 col-md-offset-1 -->
                                <!--
                                <p>Create a thread!</p>
                                -->
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-10">
                                            <!--
                                            <p>Post a thread!</p>
                                            -->
                                            <!-- Thread Form -->
                                            <form action="create_thread.php" id = "createThreadForm">
                                                <div class="row control-group">
                                                    <div class="form-group col-xs-12 floating-label-form-group controls">
                                                        <label>Title</label>
                                                        <input type="text" class="form-control" placeholder="Thread Title" name="title" required data-validation-required-message="Please enter a thread title.">
                                                        <p class="help-block text-danger"></p>
                                                    </div>
                                                </div>
                                                <div class="row control-group">
                                                    <div class="form-group col-xs-12 floating-label-form-group controls">
                                                        <label>Content</label>
                                                        <textarea rows="4" cols="50" name="content" form="createThreadForm" placeholder = "Thread Contents..." required data-validation-required-message="Please enter thread contents."></textarea>
                                                        <!--
                                                        <input type="text" class="form-control" placeholder="Thread Contents" name="content" required data-validation-required-message="Please enter thread contents.">
                                                        -->
                                                        <p class="help-block text-danger"></p>
                                                    </div>
                                                </div>
                                                <br>
                                                <div id="success"  tabindex="-5" data-trigger="focus" title="Thread Creation Failed" data-content="You must fill out Thread title AND Thread content."></div>
                                                <div class="row">
                                                    <div class="form-group col-xs-12">
                                                        <!--
                                                        <input type="submit" value="Send" class="btn btn-default">
                                                        -->
                                                    </div>
                                                </div>
                                            </form>
                                            <!--
                                            <textarea rows="4" cols="50" name="content" form="createThreadForm" placeholder = "Thread Contents..." required data-validation-required-message="Please enter thread contents."></textarea>
                                            -->
                                         </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a href='javascript:postThread()' type="button" class="btn btn-primary">Create!</a>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <table id="threads">

            		<?php
            
            		$threads = getInitialThreads($conn);
            		
            		$maxId = -1;
            		$minId = -1;
            
            		foreach ($threads as $thread) {
            			$threadId = $thread->id;
            			
            			if ($maxId == -1 || $threadId > $maxId) {
            			    $maxId = $threadId;
            			}
            			
            			if ($minId == -1 || $threadId < $minId) {
            			    $minId = $threadId;
            			}
            			
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
            			
            			printThread($threadId, $title, $cutContent, $user, $dateCreated, $commentCount);
            			
            		}
            
            		?>

	            </table>
            </div>
        </div>
    </div>
    
    
                
    <div class='container' style='text-align:center'>
        <button class="btn btn-primary" id="oldThreadButton"  type="button">Older Posts</button>
    </div>
    
    <hr>

    <?php include 'footer.html' ?>

</body>

<script>
    $('#threadModal').on('shown.bs.modal', function () {
        $('#myInput').focus()
    })
    
    var postThread = function() {
        var title = document.forms["createThreadForm"]["title"].value;
        //$("#createThreadForm")["title"]
        var content = document.forms["createThreadForm"]["content"].value;
        //$("#createThreadForm")["content"]
        
        //console.log("'" /* + JSON.stringify($("#createThreadForm"))*/ + "' '" + title + "' '" + content + "'");
        
        if (title != "" && content != "")
        {
            $("#createThreadForm").submit();
        }
        else
        {
            //console.log("fail");
            $('form').popover({
                trigger: "manual",
                placement: "bottom",
                tabindex: "-5",
                title: "Thread Creation Failed",
                content: "You must fill out Thread title AND Thread content. If you have HTML content, be aware that it is being filtered out."
            });
            $('form').popover('show');
        }
    };
    
    $('form').focusin(function() {
        $('form').popover('destroy');
        //console.log("Modal Destroy");
    });
    
    ajaxPost("createThreadForm", "create_thread.php", function(success) {
        if (success) {
            $('#threadModal').modal('hide');
            window.location = "https://behrend-beacon-rabisu.c9users.io";
        } else {
            alert("Thread Creation Failed.");
        }
    });
</script>

<script>
        
    var maxThreadId = <?php print($maxId) ?>;
    var minThreadId = <?php print($minId) ?>;
    
    var newThreadDiv = $("#newThreadDiv");
    var newThreadButton = $("#newThreadButton");
    
    var oldThreadButton = $("#oldThreadButton");
    
    newThreadDiv.hide();
    
    oldThreadButton.hide();
    
    
    
    
    
    
    
    function checkOldThreads() {
        $.post("old_threads.php", {"before" : minThreadId}, function(data) {
            var oldThreads = JSON.parse(data);
            if (oldThreads.length > 0) {
                oldThreadButton.show();
            }
        });
    }
    
    
    
    
    
    newThreadButton.click(function() {
        $.post("new_threads.php", {"since" : maxThreadId}, function(data) {
            newThreadDiv.hide();
            
            var newThreads = JSON.parse(data);
            
            var table = $("#threads tbody");
            
            for (i in newThreads) {
                var thread = newThreads[newThreads.length - i - 1];
                
                var id = thread.threadId;
                
                if (id > maxThreadId) {
                    maxThreadId = id;
                }
                
                table.prepend("<?php printThreadJs("id", "thread.title", "thread.cutContent", "thread.user", "thread.dateCreated", "thread.commentCount"); ?>");
            }
            
            
        });
    });
    
    oldThreadButton.click(function() {
        $.post("old_threads.php", {"before" : minThreadId}, function(data) {
            
            
            console.log(data);
            
            oldThreadButton.hide();
            
            var oldThreads = JSON.parse(data);
            
            var table = $("#threads tbody");
            
            for (i in oldThreads) {
                var thread = oldThreads[i];
                
                var id = thread.threadId;
                
                if (id < minThreadId) {
                    minThreadId = id;
                }
                
                table.append("<?php printThreadJs("id", "thread.title", "thread.cutContent", "thread.user", "thread.dateCreated", "thread.commentCount"); ?>");
            }
            
            checkOldThreads();
            
        });
    });
        
    setInterval(function() {
        $.post("new_threads.php", {"since" : maxThreadId}, function(data) {
            var newThreads = JSON.parse(data);
            var size = newThreads.length;
            if (size > 0) {
                var suffix = "";
                if (size > 1) {
                    suffix = "s";
                }
                newThreadDiv.show();
                newThreadButton.html(size + " new thread" + suffix);
            }
        });
    }, 5000);
    
    
    checkOldThreads();
    
    
</script>

</html>


<?php

function printThreadJs($threadId, $title, $content, $user, $dateCreated, $commentCount) {
    printThread('" + '.$threadId.' + "', '" + '.$title.' + "', '" + '.$content.' + "', '" + '.$user.' + "', '" + '.$dateCreated.' + "', '" + '.$commentCount.' + "');
}

function printThread($threadId, $title, $content, $user, $dateCreated, $commentCount) {
    print("<tr><td><div class='post-preview'><a href='comments.php?thread=$threadId'><h2 class='post-title'>$title</h2><h3 class='post-subtitle'>$content</h3></a><p class='post-meta'>Posted by $user on $dateCreated <br> $commentCount comments</p></div></td></tr>");
}

?>

<?php
$conn->close();
?>