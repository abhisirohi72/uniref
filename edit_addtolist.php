<?php include('inc/header.php'); 
//$get_branch = mysql_query("SELECT * FROM idle_notification WHERE is_active = '0'");
$get_branch = mysql_query("SELECT * FROM update_notification WHERE user_id='".$_SESSION['user_id']."' and status = '0'");


?>




<style>
.sel{
    display: inline-block;
    height: 36px!important;
    padding: 4px 6px;
    margin-bottom: 10px;
    font-size: 14px;
    line-height: 20px;
    color: #555;
    vertical-align: middle;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px!important;
  width: 73%;
    margin-left: 14%;
}
.modal{
  border-radius:8px;
  font-family: 'Barlow', sans-serif;
  text-align:center;
  top:9%;
}
.modal-title{ 
  color: rgb(51, 51, 51);
    font-size: 20px;
    margin: 22px;
    font-weight: 400;
    -webkit-font-smoothing: antialiased;
    letter-spacing: -1.2px;
  text-align:center;
} 
.p{
  text-align: center;
    font-size: 18px;
    line-height: 36px;
    color: #676767;
  padding: 8px 0px 8px 0px;
}
.bt{
  margin:9px 30px 25px 38px;
}
.label1{
  margin-left:14%;font-weight:600; font-size:16px; color:#000;
}

.modal-body{
  max-height:600px;
} 
.tex1{
    resize: none;
  border-radius: 4px!important;
  width:70%;
  margin-left:14%;
}
.popup-h4{
  color:#cc1f1f;
  font-size:18px;
  margin:0px;
}
.popup-h4-w{
  color:#bb2d2d;font-size:24px;
}
hr{
  margin: 7px -13px;
}
</style>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Added List</a> </div>
    
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        
		 <?php if(isset($_SESSION['success_msg'])) {  ?>
			<div class="alert alert-success success_display">
			<button class="close" data-dismiss="alert">x</button>
			<strong class="error_submission">Success!</strong><span> Succesfully deleted.</span>
		  </div>
		  <?php } 
		  unset($_SESSION['success_msg']);
		  ?>
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Added List</h5>
			      <!--<a href="add-branch.php" style="float:right; margin:3px; color:#fff;" class="btn btn-info">Add Branch</a>-->
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Vehicle No </th>
                  <th>Idle Hours</th>
                  <th>Location</th>
                  <th> Submit Type</th>
				  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
			  	      <?php while($added_list = mysql_fetch_assoc($get_branch)) { ?>
                <tr class="gradeX">
                  <td style="text-align: center;"><?php echo $added_list['vehicle_no']; ?></td>
                  <td style="text-align: center;"><?php echo $added_list['idle_hr']; ?></td>
                  <td style="text-align: center;"><?php echo $added_list['location'];?> </td>

                  <td style="text-align: center;"><?php echo $added_list['submit_type'];?> </td>

				          <td style="text-align: center;"> 
               




              <a class="btn btn-info" href="#" onclick="popup('<?php echo $added_list['vehicle_no']; ?>','<?php echo $added_list['idle_hr']; ?>','<?php echo $added_list['location']; ?>');">POP UP</a> 

  
              <script type="text/javascript">
                
                function test(vehicle_no,idle_hr,loc){

                  alert(vehicle_no+idle_hr+loc);
                }

              </script>
             <!-- <a class="btn btn-info" href="delete.php?id=<?php //echo base64_encode($fetch_branch['id']);?>&action=branch">POP UP</a>-->

            <!--<a class="btn btn-info" href="edit-branch.php?id=<?php //echo base64_encode($fetch_branch['id']);?>">Edit</a>--></td>
                </tr>
                <?php } ?>         
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12"> <?php echo date('Y');?> &copy; Gtrac. All Rights Reserved. </div>
</div>
<!--end-Footer-part-->














<script src="js/jquery.min.js"></script> 
<script src="js/jquery.ui.custom.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/jquery.uniform.js"></script> 
<script src="js/select2.min.js"></script> 
<script src="js/jquery.dataTables.min.js"></script> 
<!--<script src="js/matrix.js"></script> -->
<!--<script src="js/matrix.tables.js"></script>-->
<!--<script src=" http://localhost/newtracking/js/ui/jquery-1.10.2.js" type="text/javascript"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<script type="text/javascript">


  var $tt=jQuery.noConflict();

  
function popup(vehicle_no,idle_hr,location){




                //  alert("yes I  am working");
                // alert(vehicle_no+"-"+idle_hr+"-"+location);

                $tt('.Mydialog').fadeIn('fast');
                 $tt('#title1').html(vehicle_no);
                 $tt('#title2').html(location);
                 $tt('#title3').html(idle_hr+" Hours");








                 document.getElementById('btnaccept').onclick = function() {






      
                      var comment=$tt("#comm").val();
                      var other_comm=$tt("#other_comm").val();
                      var next_action_by=$tt('#next_action_by').val();



                       if (comment == "" || comment == null)
                    {
                         
                          alert("Please Select comment for Action");
                          $tt("#comm").focus();
                          return false;
                    }
                    else if (comment == "Other" && (other_comm == "" || other_comm == null))
                    {
                          alert("Please fill Other Reason comment for Action");
                          $tt("#other_comm").focus();
                          return false;
                    }
                    else
                    {
                        var final_comment;                   
                        if(comment == 'Other'){ final_comment = comment + '-' +other_comm;}
                        else{ final_comment = comment;}
                    }

                   // alert(comment+other_comm+next_action_by);

                        $tt.ajax({
                            //url: Path +"update_notification.php?action=updateTempSnowm",
                            url: "http://203.115.101.54/controlroom/admin/update_addtolist.php?action=accept",

                            data: { "veh_no": vehicle_no,
                                    "idle_hr":idle_hr,
                                    "location":location,
                                    "final_comment": final_comment,
                                    "submit_type":"Accept",
                                    "status":"1", 
                                    "next_action_by":next_action_by
                                   

                             },


                            type: "POST",
                            success: function(data)
                            {
                               alert(data);
                               
                                  $tt('.Mydialog').fadeOut('fast');
                               
                            }
                        });


    };




                        

 

}












function ReasonSelection(Val)
{
 if(Val=="Other")
    {
        document.getElementById('other_comm').style.display = "block";
        document.getElementById('other_comm').value = "";
    }
    else
    {
        document.getElementById('other_comm').style.display = "none";
    }
   
}





$(document).ready(function(){
    $("#hide").click(function(){
        $("#full-width").hide();
    });
   
});


</script>




<div class = "modal Mydialog " id="full-width" style="text-align: left;display:none; ">
 <div class="modal-dialog">
    <div class = "modal-content">
      <!--<div class = "modal-header">
        <button type = "button" class = "close " id="next" data-dismiss = "modal" aria-hidden = "true"> &times; </button>
      </div>-->
      <div class = "modal-body">
      <div class="row-fluid">
      <div class="span4">
        <h4 class="popup-h4" id = "myModalLabel" style="font-weight:400; color:#676767;">Notification </h4>
      </div>
      <div class="span8 text-right">
        <h4 class="popup-h4"><span id="title2">@Loading </span> More than : <span id="title3">5 Hours</span></h4>
      </div>
    </div>
        <hr> 
       <h4 class="popup-h4" style="font-size: 22px;" id="title1">NL01AA1110 </h4>   
        <p class="p"> Please Contact<b style="color:#333;">:  &nbsp;Mr. KULDEEP Kumar </b>  Mobile No<b style="color:#333;">:&nbsp;  9958243833</b> </p>


        <!-- <input type='hidden' name='veh_id' id='veh_no' value=''/>
        <input type='hidden' name='sys_service_id' id='sys_service_id' value=''/>
        <input type='hidden' name='sys_group_id' id='sys_group_id' value=''/>
        <input type='hidden' name='location' id='location' value=''/>
        <input type='hidden' name='comment' id='comment' value=''/>
        <input type='hidden' name='submit_type' id='submit_type' value=''/>
        <input type='hidden' name='insert_time' id='insert_time' value=''/>
        <input type='hidden' name='update_time' id='update_time' value=''/>
        <input type='hidden' name='distance_from' id='distance_from' value=''/>
        <input type='hidden' name='comment_by' id='comment_by' value=''/>
        <input type='hidden' name='idle_hr' id='idle_hour' value=''/> -->


                                      

        <label class="label1">Reason of Exception</label>

          
       
       <!--<select name="comm" id="comm" class="form-control" onchange="ReasonSelection(this.value)">-->
           <select name="comm" id="comm" class="form-control sel" onchange="ReasonSelection(this.value)" >
            <option value="">Select Status</option>
            
            <option value="Plant Closed">Plant Closed</option>
            <option value="In Queue">In Queue</option>
            <option value="Load not Ready">Load not Ready</option>
            <option value="Document not Ready">Document not Ready</option>
            <option value="Advance">Advance</option>
            <option value="Holiday">Holiday</option>
            <option value="Break Down">Break Down</option>
            <option value="Staff Not Available">Staff Not Available</option>
            <option value="Space Not Available">Space Not Available</option>
            <option value="Vehicle in Queue">Vehicle in Queue</option>
            <option value="Rejection">Rejection</option>

           <option value="Permit Problem ">Permit Problem</option>
           <option value="supervisor on Leave">supervisor on Leave</option>
           <option value="Driver at Home">Driver at Home</option>
           <option value="No Entry">No Entry</option>
           <option value="Without Driver">Without Driver</option> 
           <option value="RTO Problem">RTO Problem</option> 
           <option value="Advance Problem">Advance Problem</option> 
           <option value="Fastag">Fastag</option>            
           <option value="Diesel">Diesel</option> 
           <option value="Rest">Rest</option> 
           <option value="Accident">Accident</option>
           <option value="Driver Satisfaction ">Driver Satisfaction</option>
           <option value="Price Short">Price Short</option>
           <option value="Festival">Festival</option>
           <option value="Medical Issue">Medical Issue</option>
           <option value="Driver on Leave">Driver on Leave</option>  
           <option value="Hisab">Hisab</option>
           <option value="Permit not Available">Permit not Available</option>  
           <option value="Major Repair">Major Repair</option> 
           <option value="Spare Part Not Available">Spare Part Not Available</option>
           <option value="Mechanic Not Available">Mechanic Not Available</option> 
           <option value="Staff Not Available">Staff Not Available</option> 
           <option value="Health Issue">Health Issue</option> 
           <option value="Traffic Problem">Traffic Problem</option>
           <option value="Danger Route">Danger Route</option>  
           <option value="Express Load">Express Load</option>
           <option value="Critical Load">Critical Load</option>
           <option value="Avoiding Toll">Avoiding Toll</option> 
           <option value="Short Route">Short Route</option>
           <option value="On Demand">On Demand</option> 
           <option value="Avoiding RTO">Avoiding RTO</option>
           <option value="Going to Home">Going to Home</option>
           <option value="Sampling Test">Sampling Test</option>
           <option value="Delibrate">Delibrate</option> 
           <option value="No Entry Avoid">No Entry Avoid</option>
           <option value="Address not Confirmed">Address not Confirmed</option>
           <option value="Delay Cover Up">Delay Cover Up</option>
           <option value="Other">Other</option>
          </select>
        &nbsp;
       <textarea name="other_comm" id="other_comm" rows="5" cols="2" style="display:none;" class="form-control tex1"/></textarea>
         <!--<textarea  rows="5" cols="2"  class="form-control"/></textarea>-->


             <label class="label1">Next Action After</label>


            <!-- <select name="comm" id="next_action_by" class="form-control" onchange="action(this.value)">-->
             <select name="comm" id="next_action_by" class="form-control sel" >

            <option value="">Select Your Hours</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
        </select>

        <div class="text-center bt">

        <button type="button" class="btn btn-primary"  id="btnaccept" style="background: rgba(5, 180, 44, 0.8); border: 1px solid rgba(5, 180, 44, 0.8); border-radius:4px;font-size:14px;font-size: 18px;     width:85%;     margin-bottom: 8px;
    padding: 6px 32px;">Accept</button>
    <button type="button" class="btn btn-default" data-dismiss="modal" style="background: red; border: 1px solid red; border-radius:4px;font-size:14px;font-size: 18px;     width:85%;     margin-bottom: 8px;
    padding: 6px 32px;color: #fff;" id="hide">Close</button>
        
          <!--<button type="button" class="btn btn-primary"  id="btnreject" style="background: red; border: 1px red;">Reject</button>-->
  <br>
           
   
 </div>
       <!--<img src="1.png" width="50px" class="text-right"> -->
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>


</body>
</html>
