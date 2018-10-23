<?php
include_once 'database.php';
$conn = getConnection();
?>

<!DOCTYPE html>
<html>

<?php

$threadId = $_GET["thread"];

$stmt = $conn->prepare("Update Threads set Views = Views + 1 where ThreadId = $threadId");
$stmt->execute();
$res = $conn->query("Select Views from Threads where ThreadId = $threadId");
$row = $res->fetch_assoc();
$pageViews = $row["Views"];  


$comments = getComments($conn, $threadId);
$thread = getThreadById($conn, $threadId);
$threadContent = $thread->content;
$title = $thread->title;
$threadUser = $thread->user;
$threadDateCreated = $thread->dateCreated;

?>

<head>
    
    <?php include "css.php" ?>
    
</head>

<body>
    <?php
    $headerTitle = $title;
    $tag = "h2";
    $subheading = -1;
	include 'header.php';
	?>

    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
	<?php print("<tr><td><div class='post-preview'><p class='post-subtitle thread'>$threadContent</p></a><p class='post-meta'>Posted by $threadUser on $threadDateCreated</p></div></td></tr>"); ?>
    </div>
    
    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
        <hr style='border-top-color: rgb(4, 80, 204); border-top-width: 3px; width:100%'>
    </div>

    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
    	<table id="comments" style='width:100%'>
    
    		<?php
    
            $since = 0;
    
    		foreach ($comments as $comment) {
    		    $commentId = $comment->commentId;
    		    
    		    if ($commentId > $since) {
    			    $since = $commentId;
    			}
    		    
    			$content = $comment->content;
    			$user = $comment->user;
    			$dateCreated = $comment->dateCreated;
    			$delete = "";
    			
    			
    			printComment($commentId, $content, $user, $dateCreated);
    		}
    
    		?>
    
    	</table>
    </div>
    
    <div class='container' style='text-align:center'>
        <button class="btn btn-primary" id="newCommentButton" type="button"></button>
    </div>
    
    <hr>
    
    <?php
    $div = 
    "<div class='container' style='text-align:center'>";
    print($div);
    $commentModalButton = 
    "        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#commentsModal'>Leave a Comment!</button>";
    if (getCurrentUserId($conn) != -1) {
        print($commentModalButton);
    }
    print("</div>");
    ?>
    
    <!-- Comments Modal -->
    <div class="modal fade" id="commentsModal" tabindex="-4" role="dialog" aria-labelledby="commentsModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="commentsModalLabel">Post a comment!</h4>
                </div>
                <div class="modal-body">
                    <!-- Comments -->
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8"> <!-- col-lg-offset-2  col-md-10 col-md-offset-1 -->
                                <!--
                                <p>Create a thread!</p>
                                -->
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-10">
                                            <!-- Comment Form -->
                                            <form action="create_comment.php" id = "createCommentForm">
                                                <div class="row control-group">
                                                    <div class="form-group col-xs-12 floating-label-form-group controls">
                                                        <label>Comment</label>
                                                        <textarea rows="4" cols="50" name="content" form="createCommentForm" placeholder = "Your Comment..." required data-validation-required-message="Please enter a comment."></textarea>
                                                        <p class="help-block text-danger"></p>
                                                    </div>
                                                </div>
                                        		<?php print("<input type='hidden' name='thread' value=$threadId />"); ?>
                                                <br>
                                                <div id="success"></div>
                                            </form>
                                         </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a href='javascript:postComment()' type="button" class="btn btn-primary">Create!</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Thread Modal -->
    <div class="modal fade" id="deleteThreadModal" tabindex="-7" role="dialog" aria-labelledby="deleteThreadModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:initial">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="deleteThreadModalLabel">Would you like to delete the thread?</h4>
                </div>
                
                <div class="modal-body">
                    
                </div>
                
                <div class="modal-footer" style="border-top:initial">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel!</button>
                    <button id='deleteThread' type="button" class="btn btn-primary">Confirm Delete Thread</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Comment Modal -->
    <div class="modal fade" id="deleteCommentModal" tabindex="-8" role="dialog" aria-labelledby="deleteCommentModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:initial">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="deleteCommentModalLabel">Would you like to delete this comment?</h4>
                </div>
                
                <div class="modal-body" id="commentToDelete">
                    
                </div>
                
                <div class="modal-footer" style="border-top:initial">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel!</button>
                    <button id='deleteComment' type="button" class="btn btn-primary">Confirm Delete Comment</a>
                </div>
            </div>
        </div>
    </div>
    
    <hr>
    
    <!-- Make into modal for an 'Are you sure?' dialog -->
    <?php
    $deleteModalButton = 
    "<div class='container' style='text-align:center'>
        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#deleteThreadModal' style='text-align:center'>Delete Thread!</button>
    </div>";
    if (getCurrentUserPriv($conn) > 1) {
        print($deleteModalButton);
    }
    ?>

    <?php include 'footer.html' ?>

</body>

<script>
    $('#commentsModal').on('shown.bs.modal', function () {
        $('#myInput').focus()
    })
    
    $("#deleteThread").click(function() {
        threadID = <?php print($threadId); ?>;
        //console.log("threadID: " + threadID);
        $.post("delete_thread.php", {"thread": threadID}, function(success) {
            //console.log("success: " + success)
            
            if (success) {
                window.location = 'https://behrend-beacon-rabisu.c9users.io/';
            } else {
                alert("Deletion Failed");
            }
            
        });
    });
    
    var commentID = -1;
    
    $(".deleteComment").click(function (event) {
        //alert("commentID: " + jQuery(this).attr("id"));
        commentID = jQuery(this).attr("id");
        comment = jQuery(this).parent();
        comment.addClass("modalComment");
        
        $(".modalComment > .close").remove();
        
        $("#commentToDelete").html(comment.html());
        
        /*
        $.post("delete_comment.php", {"comment": commentID}, function(success) {
            //console.log("success: " + success)
            
            if (success) {
                window.location = <?php print("'https://behrend-beacon-rabisu.c9users.io/comments.php?thread=$threadId'") ?>;
            } else {
                alert("Deletion Failed");
            }
        });
        */
    });
    
    $("#deleteComment").click(function (event) {
        //alert("commentID: " + jQuery(this).attr("id"));
        //commentID = jQuery(this).attr("id");
        
        
        $.post("delete_comment.php", {"comment": commentID}, function(success) {
            //console.log("success: " + success)
            
            if (success) {
                window.location = <?php print("'https://behrend-beacon-rabisu.c9users.io/comments.php?thread=$threadId'") ?>;
            } else {
                alert("Deletion Failed");
            }
        });
        
    });
    
    var postComment = function() {
        var content = document.forms["createCommentForm"]["content"].value;

        if (content != "")
        {
            $("#createCommentForm").submit();
        }
        else
        {
            //console.log("fail");
            $('form').popover({
                trigger: "manual",
                placement: "bottom",
                tabindex: "-6",
                title: "Comment Creation Failed",
                content: "You must fill out Comment content."
            });
            $('form').popover('show');
        }
    }
    
    $('form').focusin(function() {
        $('form').popover('destroy');
        //console.log("Modal Destroy");
    });
    
    ajaxPost("createCommentForm", "create_comment.php", function(success) {
        if (success) {
            $('#commentsModal').modal('hide');
            window.location = <?php print("'https://behrend-beacon-rabisu.c9users.io/comments.php?thread=$threadId'") ?>;
        } else {
            alert("Comment Creation Failed.");
        }
    });
</script>

<script>
        
    var since = <?php print($since) ?>;
    var threadId = <?php print($threadId) ?>;
    
    var newCommentButton = $("#newCommentButton");
    
    newCommentButton.hide();
    
    newCommentButton.click(function() {
        $.post("new_comments.php", {"thread" : threadId, "since" : since}, function(data) {
            newCommentButton.hide();
            
            var newComments = JSON.parse(data);
            
            var table = $("#comments tbody");
            
            for (i in newComments) {
                var comment = newComments[i];
                
                var id = comment.commentId;
                
                if (id > since) {
                    since = id;
                }
                
                table.append("<?php printCommentJs("id", "comment.content", "comment.user", "comment.dateCreated"); ?>");

            }
            
            
        });
    });
        
    setInterval(function() {
        $.post("new_comments.php", {"thread" : threadId, "since" : since}, function(data) {
            var newComments = JSON.parse(data);
            var size = newComments.length;
            if (size > 0) {
                var suffix = "";
                if (size > 1) {
                    suffix = "s";
                }
                newCommentButton.show().html(size + " new comment" + suffix);
            }
        });
    }, 5000);
    
    
    
</script>

</html>



<?php

function printCommentJs($commentId, $content, $user, $dateCreated) {
    printComment('" + '.$commentId.' + "', '" + '.$content.' + "', '" + '.$user.' + "', '" + '.$dateCreated.' + "');
}

function printComment($commentId, $content, $user, $dateCreated) {
    global $conn;
    if(getCurrentUserPriv($conn) > 1) {
	    $delete = "<button type='button' data-toggle='modal' data-target='#deleteCommentModal' class='close deleteComment' id='$commentId'><span aria-hidden='true'>&times;</span></button>";
	}
    print("<tr><td><div class='col-md-10'>$delete<div class='post-preview'><p class='post-subtitle'>$content</p><p class='post-meta'>Posted by $user on $dateCreated</p><hr style='border-top-color: rgb(4, 80, 204); width=100%'></div></div></td></tr>");
}

?>

<?php
print("<center>Total Views: $pageViews</center>");
$conn->close();
?>