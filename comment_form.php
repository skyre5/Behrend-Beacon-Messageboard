<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            <p>Leave a Comment!</p>
            <!-- Comment Form -->
            <form action="create_comment.php">
                <div class="row control-group">
                    <div class="form-group col-xs-12 floating-label-form-group controls">
                        <label>Comment</label>
                        <input type="text" class="form-control" placeholder="Comment" name="content" required data-validation-required-message="Please enter a comment.">
                        <p class="help-block text-danger"></p>
                    </div>
                </div>
        		<?php print("<input type='hidden' name='thread' value=$threadId />"); ?>
                <br>
                <div id="success"></div>
                <div class="row">
                    <div class="form-group col-xs-12">
                        <input type="submit" value="Send" class="btn btn-default">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>