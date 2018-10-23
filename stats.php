<?php
include_once 'database.php';
$conn = getConnection();
?>
<!DOCTYPE html>
<html lang='en'>
  
    <head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="/path/to/jquery.easy-pie-chart.js"></script>

<link rel="stylesheet"type="text/css" href="/path/to/jquery.easy-pie-chart.css">
    <!-- Bootstrap core CSS -->
    <link href='/dist/css/bootstrap.min.css' rel='stylesheet'>
    <!--
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!-- <link href='http://getbootstrap.com/assets/css/ie10-viewport-bug-workaround.css' rel='stylesheet'>

    <!-- Custom styles for this template -->
    <link href='/css/dashboard.css' rel='stylesheet'>

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src='../../assets/js/ie8-responsive-file-warning.js'></script><![endif]-->
    <script src='/js/ie-emulation-modes-warning.js'></script>
    
    <?php
        $title = 'Admin Stats';
        include 'css.php';
    ?>

    </head>
    <body>
    <script src="/js/Charts.js"></script>
    
      <?php
        $headerTitle = 'Dashboard';
        $tag = 'h1';
        $subheading = 'You have questions. We have answers.';
    	  include_once 'header.php';
    	  
    	  
        if (getCurrentUserPriv($conn) > 1)
        {
          print("
            <div class='container-fluid'>
                
              <div class='row placeholders'>
                <div class='col-xs-6 col-md-2 placeholder'>
                  <img src='img/kanye/TotalThreads.jpg' width='150' height='200' class='img-responsive' alt='Generic placeholder thumbnail'>
                  <h4>Total Threads</h4>
                  <span class='text-muted'>
                      <h3 id='totalThreads'></h1>
                </span>
                </div>
                <div class='col-xs-6 col-md-2 placeholder'>
                  <img src='img/kanye/totalUsers.png' width='150' height='200' class='img-responsive' alt='Generic placeholder thumbnail'>
                  <h4>Total Users</h4>
                  <span class='text-muted'>
                      <h3 id='totalUsers'></h1>
                </span>
                </div>
                <div class='col-xs-6 col-md-2 placeholder'>
                  <img src='img/kanye/totalComments.png' width='150' height='200' class='img-responsive' alt='Generic placeholder thumbnail'>
                  <h4>Total Comments</h4>
                  <span class='text-muted'>
                      <h3 id='totalComments'></h1>
                </span>
                </div>
                <div class='col-xs-6 col-md-2 placeholder'>
                  <img src='img/kanye/pageviews.png' width='150' height='200' class='img-responsive' alt='Generic placeholder thumbnail'>
                  <h4>Total Page Views</h4>
                  <span class='text-muted'>
                      <h3 id='totalViews'></h1>
                </span>
                </div>
                <div class='col-xs-6 col-md-2 placeholder'>
                  <img src='img/kanye/kanye-west3.jpg' width='150' height='200' class='img-responsive' alt='Generic placeholder thumbnail'>
                  <h4>Most Active Poster</h4>
                  <span class='text-muted'>
                      <h3 id='bestPoster'></h1>
                </span>
                </div>
                <div class='col-xs-6 col-md-2 placeholder'>
                  <img src='img/kanye/MostActiveCommenter.png' width='150' height='200' class='img-responsive' alt='Generic placeholder thumbnail'>
                  <h4>Most Active Commenter</h4>
                  <span class='text-muted'>
                      <h3 id='bestCommentor'></h1>
                </span>
                </div>
              </div>
              <div id='container' style='min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto'></div>
              <div class='row'>
                  <div class='col-sm-1 col-md-1 sidebar'></div>
                  <center><div class='col-sm-10 col-md-10 main'>
                    <div class='table-responsive'>
                      <h2 class='sub-header'>Active User Information</h2>
                        <table class='table table-striped sortable'>
                          <thead>
                            <tr>
                              <th>Id</th>
                              <th>Name</th>
                              <th>Account Type</th>
                              <th>Threads Created</th>
                              <th>Threads Commented</th>
                            </tr>
                          </thead>
                          <tbody>
        ");
            
            		$users = getUserList($conn);
            
            		foreach ($users as $User) {
            			$name = $User->name;
            			$userID = $User->userID;
            			$threadCount = $User->threadCount;
            			$commentCount = $User->commentCount;
            			$COUNTER = $User->COUNTER;
            			$Privileges= $User->Privileges;
            			switch ($Privileges) {
            			  case 0:
            			    $Privileges = 'Average Joe';
            			    break;
            			  case 1:
            			    $Privileges = 'Editor';
            			    break;
            			  case 2:
            			    $Privileges = 'Admin';
            			    break;
            			}
            			print("<tr><td>$userID</td><td>$name</td><td>$Privileges</td><td>$threadCount</td><td>$commentCount</td></tr>");
            			
            		}
            


        print("
                        </tbody>
                      </table>
                </div> 
                </div></center>
                <div class='col-sm-1 col-md-1 sidebar'></div>
            </div>
           
          
          </div>
          
  
          <hr>
        ");
        } else {
          print("<h1 style='text-align: center'>403 - Forbidden</h1>");
        }
        
        include 'footer.html'
      ?>
   
<script>
var chart = Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Beacon Stats'
    },
    tooltip: {
        //pointFormat: '{series.name}: <b>{point.y:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y}',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Statistics',
        colorByPoint: true,
        data: [{
            name: 'Total Threads',
            y: 1,
            color: Highcharts.getOptions().colors[0] 
        }, {
            name: 'Total Users',
            y: 1,
            sliced: true,
            selected: true
        }, {
            name: 'Total Comments',
            y: 1
        }, {
            name: 'Total Page Views',
            y: 1
        }]
    }]
});
update2();
setInterval(update2,10000);
    function update2(){
        $.post('update_stats.php', function(data){
            console.log(data);
            var newStats = JSON.parse(data);
            var totalCount = parseInt(newStats[0]['userCount']);
            var totalThreads = parseInt(newStats[0]['threadCount']);
            var totalComments = parseInt(newStats[0]['commentCount']);
            var totalViews = parseInt(newStats[0]['totalViews']);
            chart.update ({
                series: [{
                    name: 'Statistics',
                    colorByPoint: true,
                    data: [{
                        name: 'Total Threads',
                        y: totalThreads
                    }, {
                        name: 'Total Users',
                        y: totalCount,
                        sliced: true,
                        selected: true
                    }, {
                        name: 'Total Comments',
                        y: totalComments
                    }, {
                        name: 'Total Page Views',
                        y: totalViews
                    }]
                }]
            });
        });
    }
    
</script>
    </body>
    
    <script>
    update();
    setInterval(update,10000);
    function update(){
        $.post('update_stats.php', function(data){
            var newStats = JSON.parse(data);
            var totalCount = newStats[0]['userCount'];
            $('#totalUsers').html(totalCount);
            var totalThreads = newStats[0]['threadCount'];
            $('#totalThreads').html(totalThreads);
            var totalComments = newStats[0]['commentCount'];
            $('#totalComments').html(totalComments);
            var totalViews = newStats[0]['totalViews'];
            $('#totalViews').html(totalViews);
            var bestPoster = newStats[0]['bestPoster'];
            $('#bestPoster').html(bestPoster);
            var bestCommentor = newStats[0]['bestCommentor'];
            $('#bestCommentor').html(bestCommentor);
        });
    }
    
</script>

</html>

<?php
$conn->close();
?>