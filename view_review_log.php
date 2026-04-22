<?php include('includes/include.php');
$_POST['pid'] = intval($_POST['pid']);
?>

<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">View Log for <?= getSingleresult("select company_name from orders where id =" . $_POST['pid']) ?></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">

      <?php
      $sqlStage = db_query("select * from review_log where lead_id ='" . $_POST['pid'] . "' order by created_date desc");

      $num_rows = mysqli_num_rows($sqlStage);
       if($num_rows > 0){

         while ($data = db_fetch_array($sqlStage)) {
           ?>

        <div class="card-body font-size-13">
          Stage changed from <b><?= $data['old_stage'] ?></b> to <b><?= $data['new_stage'] ?> (<?= $data['sub_stage'] ?>) </b> by <b><?= $data['added_by'] ?></b> on <b><?= date('d-m-Y h:i:s', strtotime($data['created_date'])) ?></b>
          <br>
          Lead Type changed from <b><?= (($data['old_lead_type']!='') ? $data['old_lead_type']:'N/A') ?></b> to <b><?= $data['new_lead_type'] ?> </b> by <b><?= $data['added_by'] ?></b> on <b><?= date('d-m-Y h:i:s', strtotime($data['created_date'])) ?></b>
          <br>
         
          Caller changed from <b> <?= (($data['old_caller']!='') ? getSingleresult("select name from callers where id=" . $data['old_caller']):'N/A') ?></b> to <b><?= (($data['new_caller']!='')?getSingleresult("select name from callers where id=" . $data['new_caller']):'N/A') ?> </b> by <b><?= $data['added_by'] ?></b> on <b><?= date('d-m-Y h:i:s', strtotime($data['created_date'])) ?></b>
          <br>
          <b>Comment:</b><?= $data['comment'] ?>

        </div>

      <?php } 

       }else{ ?>
          <p>No log available </p>
      <?php } ?>
          </div>
    <div class="modal-footer">

      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

    </div>

  </div>


</div>