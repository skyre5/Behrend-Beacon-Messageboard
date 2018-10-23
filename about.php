<!DOCTYPE html>
<html lang="en">

<head>
    
    <?php
    $title = "About Us";
    include "css.php";
    ?>

</head>

<body>
    <?php
    $headerTitle = "About Us";
    $tag = "h1";
    $subheading = "This is the Behrend Beacon.";
	include_once 'header.php';
	?>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <p>
                    The Behrend Beacon, which was founded in 1948, is Penn State Behrend's official student newspaper.
                    The weekly newspaper is written, designed, edited, and produced entirely by the students of Penn State Behrend.
                    With each new issue, published every Tuesday, the Beacon strives to accurately and fully cover the activities of the students, faculty, and administration at Penn State Behrend, and to give our readers the information they need to make informed decisions without bias.
                </p>
            </div>
        </div>
    </div>

    <hr>

    <?php include 'footer.html' ?>

</body>

</html>
