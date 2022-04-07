<?php 
//Author - Sanjana

require("conn/config.php"); ?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link rel="stylesheet" href="css/main.css">
</head>
<body>

<div id="boxes">
  <div style="top: 199.5px; left: 551.5px; display: none;" id="dialog" class="window"> 
  <p>Popup Title</p>
  <p>Popup message</p>
    <div id="lorem">
      
	  Hours - 
	  <select name="hours" id="hours">
	  <option value="0"> ------- Select Hours -------- </option>
	  <option value="1">1</option>
	  <option value="2">2</option>
	  <option value="3">3</option>
	  </select> <span id="error_hour" class="error"></span><br>
	  Choose reason -
	  <select name="reason" id="reason">
	  <option value="0"> ------- Select Reason -------- </option>
	  <option value="1">Reason A</option>
	  <option value="2">Reason B</option>
	  <option value="3">Other</option>
	  </select> <span id="error_reason" class="error"></span><br>
	  <div class="other_reason_msg" style="display:none">
	  Specify reason - <textarea id="other_reason"></textarea><span id="other_reason_error" class="error"></span><br>
	  </div>
		<input type="submit" value="SUBMIT" id="submit_pop">
    </div>
  </div>
  <div style="width: 1478px; font-size: 32pt; color:white; height: 602px; display: none; opacity: 0.8;" id="mask"></div>
</div>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<script src="js/main.js"></script>
<script>
$( document ).ready(function(){
	
	$("#reason").change(function(){
		var reason = $("select#reason option:selected").val();
		if( reason == 3 ) {
			$(".other_reason_msg").css("display","block");
		}
		else {
			$(".other_reason_msg").css("display","none");
		}
	});
	
	$("#submit_pop").click(function(){
		var hours = $("select#hours option:selected").val();
		var reason = $("select#reason option:selected").val();
		var reason_msg = $("#other_reason").val();
		
		if( hours == '0' ){
			$("#error_hour").html("Please select hours");
			return false;
		}
		else {
			$("#error_hour").html("");
		}
		
		if( reason == '0' ){
			$("#error_reason").html("Please select hours");
			return false;
		}
		else {
			$("#error_reason").html("");
		}
		
		if( reason == '3' && reason_msg == '' ){
			$("#other_reason_error").html("Please specify reason");
			return false;
		}
		else {
			$("#other_reason_error").html("");
		}
		
		
		
		$.ajax({
			type: 'POST',  // http method
			url: 'ajax/submit_popup.php',
			data: { hours: hours, reason:reason, reason_msg:reason_msg},  // data to submit
			success: function (response) {
				alert(response);//$('p').append('status: ' + status + ', data: ' + data);
			},
			error: function (jqXhr, textStatus, errorMessage) {
					$('p').append('Error: ' + errorMessage);
				}
		});
			
			
	});

});
</script>
</body>
</html>