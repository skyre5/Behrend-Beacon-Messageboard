<!DOCTYPE html>
<html lang="en">

<head>
    
    <?php
    $title = "Login";
    include "css.php";
    ?>

</head>
<body>
    <?php
    $headerTitle = "Login";
    $tag = "h1";
    $subheading = -1;
	include_once 'header.php';
	?>
    
    
    <!-- Logins -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <p>Login</p>
                <!-- Login Form -->
                <form id="loginForm" action="process_login.php">
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Username</label>
                            <input type="text" class="form-control" placeholder="Username" name="userName" required data-validation-required-message="Please enter a username.">
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Password</label>
                            <input type="password" class="form-control" placeholder="Password" name="passWord" required data-validation-required-message="Please enter a password.">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <br>
                    <div id="success"></div>
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <input type="submit" id="submit" value="Send" class="btn btn-default">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
        
    <!-- Logins -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <p>Don't have an account? <a href="registration.php">Create one!</a></p>
            </div>
        </div>
    </div>

    <?php include 'footer.html' ?>

</body>

</html>
