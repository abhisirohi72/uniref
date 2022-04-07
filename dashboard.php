<?php include('inc/header.php');
      include_once('conn/configu.php');
 
$yesterday_date = date('Y-m-d', strtotime('-1 day', strtotime($current_date)));
 
 
$total_exc_tod = mysqli_query($connu,"SELECT * FROM idle_notification WHERE sys_user_id='".$_SESSION['user_id']."' and is_active = '0' and update_time>='".$current_date." 00:00:00' and update_time<='".$current_date." 23:59:00'");
$total__exc_today=mysqli_num_rows($total_exc_tod);
 
$accepted_exc_tod = mysqli_query($connu,"SELECT * FROM update_notification WHERE user_id='".$_SESSION['user_id']."' and status = '1' and date>='".$current_date." 00:00:00' and date<='".$current_date." 23:59:00'");
$total_accepted_exc_today=mysqli_num_rows($accepted_exc_tod);
 
$added_to_list_tod = mysqli_query($connu,"SELECT * FROM update_notification WHERE user_id='".$_SESSION['user_id']."' and status = '0' and date>='".$current_date." 00:00:00' and date<='".$current_date." 23:59:00'");
$total_addtolist_exc_today=mysqli_num_rows($added_to_list_tod);
 
 
 
 
 
$total_exc_yes = mysqli_query($connu,"SELECT * FROM idle_notification WHERE sys_user_id='".$_SESSION['user_id']."' and is_active = '0' and update_time>='".$yesterday_date." 00:00:00' and update_time<='".$yesterday_date." 23:59:00'");
$total__exc_yesterday=mysqli_num_rows($total_exc_yes);
 
$accepted_exc_yes = mysqli_query($connu,"SELECT * FROM update_notification WHERE user_id='".$_SESSION['user_id']."' and status = '1' and date>='".$yesterday_date." 00:00:00' and date<='".$yesterday_date." 23:59:00'");
$total_accepted_exc_yesterday=mysqli_num_rows($accepted_exc_yes);
 
$added_to_list_yes = mysqli_query($connu,"SELECT * FROM update_notification WHERE user_id='".$_SESSION['user_id']."' and status = '0' and date>='".$yesterday_date." 00:00:00' and date<='".$yesterday_date." 23:59:00'");
$total_addtolist_exc_yesterday=mysqli_num_rows($added_to_list_yes);
 
 
 
 
 
 
 
 
 
 
 
 
?>
 
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="indexafterlogin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->
 
<!--Action boxes-->
  <div class="container-fluid">
     <div class="row-fluid"> 
        <div class="span4 box-b">
           <p class="text-c">Today's Total Exceptions<span class="icon-i"><i class="fa fa-bar-chart-o" style="font-size:46px;"></i></span></p>
           <h1><?=$total__exc_today ?></h1>
          <!--  <p class="text-c">Machine Movement and Tasks</p>
 -->        </div>
        <div class="span4 box-sb">
           <p class="text-c">Today's Total Resolved Exceptions<span class="icon-i"><i class="fa fa-bar-chart-o" style="font-size:46px;"></i></span></p>
           <h1><?=$total_accepted_exc_today ?></h1>
           <!-- <p class="text-c">Machine Movement and Tasks</p> -->
        </div>
        <div class="span4 box-r">
           <p class="text-c">Today's Total Unsolved Exceptions<span class="icon-i"><i class="fa fa-bar-chart-o" style="font-size:46px;"></i></span></p>
           <h1><?=$total_addtolist_exc_today ?></h1>
          <!--  <p class="text-c">Machine Movement and Tasks</p> -->
        </div>
          
      </div>
 
 
 
     
     
      <div class="row-fluid"> 
            <div class="span6 graph-border ">
                <div id="piechart"></div>
 
               <div class="span12 text-center">
                  <h5>Block wise Today's Exceptions</h5>
               </div>
            </div>
<!--End-Chart-box-->
 
            <div class="span6 graph-border text-center">
               <div id="exceptionsperperson" style=" height: 400px;"></div>
                <div class="span12 text-center">
                    <h5>Exceptions Per Person</h5>
                </div>
            </div>
    </div>
    <!---->
     <div class="row-fluid"> 
      <div class="span12 graph-border text-center">
        
         <div id="columnchart_material" style=" height: 500px;"></div>
          <div class="span12 text-center">
              <h5>Monthly Gate-in and Gate-out</h5>
           </div>
      </div>
     
    </div>
    <!---->
    <div class="row-fluid"> 
      <div class="span6 graph-border ">
            <div id="partywise" style=" height: 500px;"></div>
           <div class="span12 text-center">
              <h5>Party Wise/ Client Wise</h5>
           </div>
          </div>
      <div class="span6 graph-border text-center"> 
            <div id="routewise" style=" height: 500px;"></div>
          <div class="span12 text-center">
              <h5>Route wise </h5>
           </div>
      </div>
    </div>
 
<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12"> 2018 &copy; G-Trac </div>
</div>
 
<!--end-Footer-part-->
 
<script src="js/excanvas.min.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/jquery.ui.custom.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.flot.min.js"></script>
<script src="js/jquery.flot.resize.min.js"></script>
<script src="js/jquery.peity.min.js"></script>
<script src="js/fullcalendar.min.js"></script>
<script src="js/matrix.js"></script>
<script src="js/matrix.dashboard.js"></script>
<script src="js/jquery.gritter.min.js"></script>
<script src="js/matrix.interface.js"></script>
<script src="js/matrix.chat.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/matrix.form_validation.js"></script>
<script src="js/jquery.wizard.js"></script>
<script src="js/jquery.uniform.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/matrix.popover.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/matrix.tables.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
 
 
<script type="text/javascript">
 
 
 
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart1);
 
 
  function drawChart1() {
  var data = google.visualization.arrayToDataTable([
  ['Task', 'Hours per Day'],
  ['Idle in transit', <?=4?>],
  ['Loading', <?=2?>],
  ['Unloading', <?=2?>]
 
]);
 
 
  var options = { 'title':'Exceptions',
                  'width':550,
                  'height':400,
                //'colors': ['#e0440e', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6','#f6c7b2'],
                  is3D: true
  };
 
  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
 
 
 
 
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(exceptionsperperson);
 
      function exceptionsperperson() {
        var data = google.visualization.arrayToDataTable([
          ['', 'Loading', 'Unloading', 'Breakdown','Delays'],
          ['Yogender', 8, 4, 2,3],
          ['Suryakant', 11, 4, 2,3],
          ['Jaskaran Chaddha', 9, 4, 2,3],
          ['Dharmender', 1, 4, 2,3],
         
         
        ]);
 
        var options = {
          chart: {
            title: '',
            subtitle: '',
          },
 
          // 'colors': ['#e0440e', '#e6693e', '#ec8f6e']
        };
 
        var chart = new google.charts.Bar(document.getElementById('exceptionsperperson'));
 
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
 
 
 
 
 
 
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);
 
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Exceptions Assigned', 'Yogender', 'Praveen Sethi', 'Suryakant','Jaskaran Chaddha','Dharmender'],
          ['Loading', 8, 4, 2,3,3.5],
          ['Unloading', 11, 4, 2,3,3.5],
          ['Breakdown', 9, 4, 2,3,3.5],
          ['Delays', 1, 4, 2,3,3.5],
         
         
        ]);
 
        var options = {
          chart: {
            title: 'XYZ',
            subtitle: 'Sales, xyz',
          },
 
          // 'colors': ['#e0440e', '#e6693e', '#ec8f6e']
        };
 
        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
 
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
 
 
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(partywise);
 
      function partywise() {
        var data = google.visualization.arrayToDataTable([
          ['', 'Party'],
          ['Dell', 8],
          ['Hp', 11],
          ['Lenovo', 9],
          ['Sony', 1],
         
         
        ]);
 
        var options = {
          chart: {
            title: '--',
            subtitle: '---',
          },
 
          // 'colors': ['#e0440e', '#e6693e', '#ec8f6e']
        };
 
        var chart = new google.charts.Bar(document.getElementById('partywise'));
 
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
 
 
 
 
 
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(routewise);
 
      function routewise() {
        var data = google.visualization.arrayToDataTable([
          ['', 'Party'],
          ['Dell', 8],
          ['Hp', 11],
          ['Lenovo', 9],
          ['Sony', 1],
         
         
        ]);
 
        var options = {
          chart: {
            title: '--',
            subtitle: '---',
          },
 
          // 'colors': ['#e0440e', '#e6693e', '#ec8f6e']
        };
 
        var chart = new google.charts.Bar(document.getElementById('routewise'));
 
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
 
 
 
 
  // This function is called from the pop-up menus to transfer to
  // a different page. Ignore if the value returned is a null string:
  function goPage (newURL) {
 
      // if url is empty, skip the menu dividers and reset the menu selection to default
      if (newURL != "") {
     
          // if url is "-", it is this page -- reset the menu:
          if (newURL == "-" ) {
              resetMenu();           
          }
          // else, send page to designated URL           
          else { 
            document.location.href = newURL;
          }
      }
  }
 
// resets the menu selection upon entry to this page:
function resetMenu() {
   document.gomenu.selector.selectedIndex = 2;
}
</script>
<script src="js/chartjs/chart.min.js"></script>
  <script src="js/chartjs/chart-int.js"></script>
  </body>
</body>
</html>