<script src="vendor/jquery/jquery.min.js"></script>
<script>
    function logout() {
        $.post("logout.php", function(data) {
            location.reload();
        });
    }
</script>

<!-- Navigation -->
<nav class="navbar navbar-default navbar-custom navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                Menu <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="index.php">Behrend Beacon</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="index.php">Home</a>
                </li>
                <li>
                    <a href="about.php">About</a>
                </li>
                <li>
                    <?php
                    include_once "database.php";
                    $headerConn = getConnection();
                    $headerUser = getCurrentUserName($headerConn);
                    $headerPriv = getCurrentUserPriv($headerConn);
                    $headerConn->close();
                    if ($headerUser && $headerPriv > 1) {
                        print("<a href='stats.php'>Statistics</a>");
                    } else {
                        //print("");
                    }
                    
                    ?>
                </li>
                <li>
                    <?php
                    if ($headerUser) {
                        // Modal Trigger for Logout
                        print("<a type='button' data-toggle='modal' data-target='#logoutModal'>$headerUser</a>");
                    } else {
                        // Modal Trigger for Login
                        print("<a type='button' data-toggle='modal' data-target='#loginModal'>Login</a>");
                    }
                    ?>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>



<!-- Page Header -->
<!-- Set your background image for this header on the line below. -->
<header class="intro-header" style="background-image: url('img/PennState-REDC-1.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="site-heading" style='text-shadow: 0 0 3px #000000, 0px 0px 5px #0000FF'>
                    <?php
                    print("<$tag>$headerTitle</$tag>");
                    if($subheading != -1)
                    {
                        print("<hr class='small'>");
                        print("<span class='subheading'>$subheading</span>");
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="loginModalLabel">Login</h4>
            </div>
            <div class="modal-body">
                <!-- Logins -->
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                            <!--
                            <p>Login</p>
                            -->
                            <!-- Login Form -->
                            <form id="loginForm">
                                <div class="row control-group">
                                    <div class="form-group col-xs-8 floating-label-form-group controls">
                                        <label>Username</label>
                                        <input type="text" class="form-control" placeholder="Username" name="userName" required data-validation-required-message="Please enter a username.">
                                        <p class="help-block text-danger"></p>
                                    </div>
                                    <div class="form-group col-xs-8 floating-label-form-group controls">
                                        <label>Password</label>
                                        <input type="password" class="form-control" placeholder="Password" name="passWord" required data-validation-required-message="Please enter a password.">
                                        <p class="help-block text-danger"></p>
                                    </div>
                                </div>
                                <br>
                                <div id="success"></div>
                                <div class="row">
                                    <div class="form-group col-xs-8">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!--
                <button type="button" class="btn btn-primary">Save changes</button>
                -->
            </div>
        </div>
    </div>
</div>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-2" role="dialog" aria-labelledby="logoutModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="logoutModalLabel">Logout?</h4>
            </div>
            <div class="modal-body">
                <!-- Logout -->
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                            <p>Would you like to log out?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a href='javascript:logout()' type="button" class="btn btn-primary">Log Out</a>
            </div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="post.js"></script>
<script>
    ajaxPost("loginForm", "process_login.php", function(success) {
        if (success) {
            window.location = "https://behrend-beacon-rabisu.c9users.io";
        } else {
            alert("Invalid username or password.");
        }
    });
    
    $('#loginModal').on('shown.bs.modal', function () {
        $('#myInput').focus()
    })
    
    $('#logoutModal').on('shown.bs.modal', function () {
        $('#myInput').focus()
    })
</script>