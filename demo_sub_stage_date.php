<?php  include('includes/include.php');   
$id = intval($_POST['id']); 
$subStageID = intval($_POST['subStageID']); 
$currentSubStage = getSingleresult("select name from sub_stage where id=".$subStageID);


if (isset($_POST['logID'])) {

    $logDate = $_POST['logDate']." ".date('H:i:s');
    $previousStage = $_REQUEST['previousSubStage'];
    $currentSubStageValue = $_REQUEST['currentSubStage'];
    $logID = $_REQUEST['logID'];

    $isExistsData = 
    getSingleresult("select modify_name from lead_modify_log where lead_id=".$logID." and previous_name='".$previousStage."' and modify_name='".$currentSubStageValue."'");

    if($isExistsData){
        $res = db_query("update lead_modify_log set previous_name = '".$previousStage."',timestamp='".$logDate."' where lead_id=".$logID." and
        previous_name='".$previousStage."' and  modify_name='".$currentSubStageValue."'");
    }else{
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`,`timestamp`)
        values('" . $logID . "','Sub Stage','" . $previousStage . "','" .$currentSubStageValue. "','".$logDate."','" . $_SESSION['user_id'] . "','".$logDate."')");
    }

    

    if ($res) {
        echo 'success';
        exit;
    } else {

        echo 'Error :' . mysql_info();
        exit;
    }
}

?>   

<div class="modal-dialog modal-dialog-centered modal-lg"> 
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Update Date For <?php echo $currentSubStage ?> </h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
   </div>
    
    <div class="modal-body">
      
        <div class="row">


        <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Previous Sub Stage<span class="text-danger">*</span> </label>
              <?php
             
             $data = db_query("select * from lead_modify_log where lead_id=".$id." and modify_name ='".$currentSubStage."' order by created_date desc LIMIT 1");
             $selectedValue = db_fetch_array($data);

                                  
  
              ?>
              <select name="lead_status" class="form-control" required="" id="sub_stage" placeholder="" data-validation-required-message="This field is required">
                  <option value="">---Select---</option>
                    <?php
                    $sstage_sql = db_query("select name,id from sub_stage where stage_name='Demo'");
                    while ($subStageHeader = db_fetch_array($sstage_sql)) {                        
                        ?>
                      
                    <option  <?= ((isset($selectedValue['previous_name']) && $selectedValue['previous_name'] ==$subStageHeader['name']) ? 'selected' : '') ?> ><?php echo $subStageHeader['name']; ?></option>
                        <?php
                    
                }

                    ?>                                               
             </select>
            </div> 
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Sub Stage Change Date<span class="text-danger">*</span></label>
              <div class="calendar-field-with-icon">
                <i class="fa fa-fw fa-calendar-week"></i>
                <input type="text" 
                value="<?= isset($selectedValue['timestamp']) ? date('Y-m-d',strtotime($selectedValue['timestamp'])) : ''; ?>" class="form-control datepicker"  name="log_date" id="log_date" required/>
              </div> 
              <input type="hidden" name="pid" value="<?=$id?>" />                           
            </div> 
          </div>


         
        </div>

    <input type="hidden" name="pid" value="<?= $id ?>" />

    <div class="mt-3 text-center">
      <button type="button" class="btn btn-primary" data-dismiss="modal" 
      onclick="update_sub_stage_timestamp('<?=$id?>','<?=$currentSubStage?>')">Save</button>

      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>


    </div>
  </div>


</div>
</div>



<script>
//  $(function() {
//     $('#close_date').daterangepicker({
        
//       "singleDatePicker": true,
//     "showDropdowns": false,
//     "opens":"right",
//     autoUpdateInput: false,
//      locale: {
//       format: 'YYYY-MM-DD'
//     }
// }); 
// $('#close_date').on('apply.daterangepicker', function(ev, picker) {
//       $(this).val(picker.startDate.format('YYYY-MM-DD'));
//   });
 
        
//     });

$(function() {
  $('.datepicker').datepicker({
      format: 'yyyy-mm-dd',
      forceParse: false,
      autoclose: !0

  });

});


function update_sub_stage_timestamp(logID,currentSubStage){

    

    var logID = logID;
    var currentSubStage = currentSubStage;
    var previousSubStage = $('#sub_stage').find(":selected").text();
    var logDate = $('#log_date').val();
    if(previousSubStage == '' || previousSubStage == null || logDate == '' || logDate == null){
      swal('Fill Required Fields.');
    }else{
      update_sub_stage_timestamps(logID,currentSubStage,previousSubStage,logDate);
    }

}

</script>