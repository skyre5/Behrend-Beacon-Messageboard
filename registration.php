<!DOCTYPE html>
<html lang="en">

<head>
    
    <?php
    $title = "Registration";
    include "css.php";
    ?>

</head>
<body>
    <?php
    $headerTitle = "Registration";
    $tag = "h1";
    $subheading = "Sign up with us!";
	include_once 'header.php';
	?>
    
    
        <!-- Logins -->
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <p>Register For Your Own Account</p>
                    <!-- Login Form -->
                    <form id="registerForm">
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Username</label>
                                <input type="text" class="form-control" placeholder="Username" name="UserName" required data-validation-required-message="Please enter a username.">
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Password</label>
                                <input type="password" class="form-control" placeholder="Password" name="Password" required data-validation-required-message="Please enter a password.">
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Retype Password</label>
                                <input type="password" class="form-control" placeholder="Password" name="RetypedPassword" required data-validation-required-message="Please enter the same password.">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <br>
                        <div id="success"></div>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <input type="submit" value="Submit" class="btn btn-default">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<!--
<h1>Log In:</h1><br>
<form action="process_login.php">
    UserName:
    <input type="text" name="userName"><br>
    Password:
    <input type="password" name="passWord"><br>
    <input type="submit" value = "submit">
</form>
-->

    <?php include 'footer.html' ?>

</body>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="post.js"></script>
<script>
    ajaxPost("registerForm", "create_user.php", function(success) {
        switch (success) {
            case "0":
                window.location = "https://behrend-beacon-rabisu.c9users.io";
                break;
            case "1":
                alert("Typed passwords did not match.");
                break;
            case "2":
                alert("Username is already taken!");
                break;
            case "3":
                alert("Username can not be empty!");
                break;
            default:
                alert("An unknown error has occurred.");
                break;
        }
    });
</script>

</html>