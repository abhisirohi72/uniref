<?php include('inc/header.php'); 
$get_contacts = mysql_query("SELECT * FROM contact_info WHERE user_id = '".$_SESSION['user_id']."'");

?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">My Contacts</a> </div>
    
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>My Contacts</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Branch</th>
                  <th>Category</th>
                  <th>Contacts</th>
				  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
			  	<?php while($fetch_contacts = mysql_fetch_assoc($get_contacts)) { ?>
                <tr class="gradeX">
                  <td><?php echo $fetch_contacts['branch']; ?></td>
                  <td><?php echo rtrim($fetch_contacts['category'],","); ?></td>
                  <td class="center">
				  <?php
				  $contacts = mysql_query("SELECT * FROM contacts WHERE contact_id = '".$fetch_contacts['id']."'");
				  $contact_name = '';
				  while($fetch_contact = mysql_fetch_assoc($contacts)) { 
					$contact_name .= $fetch_contact['name'].',';
				  } 
				  echo rtrim($contact_name,",");
				  ?>
				  </td>
				  <td><a class="btn btn-success" href="view-contact.php?id=<?php echo base64_encode($fetch_contacts['id']);?>">View</a> <a class="btn btn-info" href="edit-contact-info.php?id=<?php echo base64_encode($fetch_contacts['id']);?>">Edit</a></td>
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
<script src="js/matrix.js"></script> 
<script src="js/matrix.tables.js"></script>
</body>
</html>
