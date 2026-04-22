<?php  include('includes/include.php');   
$_POST['ids'] = intval($_POST['ids']); 
$_POST['pid'] = intval($_POST['pid']); 
?>   

<div class="modal-dialog modal-dialog-centered modal-lg"> 
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Change Close Date for <?=getSingleresult("select school_name from orders where id=".mysqli_real_escape_string($GLOBALS['dbcon'],$_POST['pid']))?></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
   </div>
    <?php $query = db_query("select * from users where id=" . $_SESSION['user_id']);
    $row_data = db_fetch_array($query); ?>
    <div class="modal-body">
      <?php 
        $current_cdDate=getSingleresult("select expected_close_date from orders where id=".mysqli_real_escape_string($GLOBALS['dbcon'],$_POST['pid']));
        $createdDt=getSingleresult("select created_date from orders where id=".mysqli_real_escape_string($GLOBALS['dbcon'],$_POST['pid']));
        $created_date_for_js = date("Y-m-d", strtotime($createdDt));
        $sqlStage = "select * from lead_stage where status ='1'";
              $stageList = db_query($sqlStage);
       ?>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Close Date</label>
              <div class="calendar-field-with-icon">
                <i class="fa fa-fw fa-calendar-week"></i>
                <input type="text" value="<?=$current_cdDate?>" class="form-control datepicker"  name="close_date" id="close_date" />
              </div> 
              <input type="hidden" name="pid" value="<?=$_POST['pid']?>" />                           
            </div> 
          </div>
         
        </div>

    <input type="hidden" name="pid" value="<?= $_POST['pid'] ?>" />

    <div class="mt-3 text-center">
      <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="get_cd_data('<?=intval($_POST['pid'])?>','<?=intval($_POST['ids'])?>')">Save</button>

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
var createdDate = "<?= $created_date_for_js ?>";
$(function() {
  $('.datepicker').datepicker({
      format: 'yyyy-mm-dd',
      forceParse: false,
      autoclose: !0,
      startDate: createdDate
  });

});

function get_cd_data(pid,ids)
{
 var cd_date= $('#close_date').val();

 //alert(substage);
 change_cdDate(cd_date,pid,ids);

}


</script>