<?php include("includes/include.php");

?>
<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <?php /*?>  <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Goals for <?=getSingleresult("select name from partners where id=".$_POST['pid'])?></h4><?php */?>
        
         <h4 class="modal-title ">Import Raw Leads</h4>
         <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
      Please be careful while uploading file and ensure that all the below condtions are followed.
      <ul>
      <li>Keep column order same as attached csv format</li>
      <li>Fill real Partner id (No SFDC ID) in the column "team_id"</li>
      <li>Avoid Special Characters such as "Apostrophe(')"</li>
      </ul>
      <a href="uploads/raw_import_format.csv" download>Download CSV Format</a>
        <form action="#" method="post" class="form p-t-20" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="exampleInputuname">Select CSV File</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="ti-upload"></i></div>
                                            <input required type="file" onchange='triggerValidation(this)' name="file" class="form-control" id="exampleInputuname" placeholder="">
                                        </div>
                                    </div>
                                     
                       
                                    
        <div class="modal-footer">
	  <?php /*?><input type="submit" name="save" value="Save" class="btn btn-success waves-effect waves-light m-r-10" />
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button><?php */?>
        
          <input type="submit" name="save_csv" value="Save" class="btn btn-primary waves-effect waves-light m-r-10" />
        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
        
		
      </div>  </form>                           
      </div>
     
	   
    </div>
 
  </div>
  <script>
  var regex = new RegExp("(.*?)\.(csv)$");

function triggerValidation(el) {
  if (!(regex.test(el.value.toLowerCase()))) {
    el.value = '';
    alert('Please select correct file format');
  }
}
</script>