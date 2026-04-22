<?php 
include('includes/include.php');
  $tlType = $_POST['tl_type'];
  $actionType = $_POST['edit'];
  $lead_id = $_POST['lead_id'];
  // echo $lead_id; die();
  if($actionType == true){
    $lead_id = $_POST['lead_id'];
    $data = db_query("select * from users where id='".$lead_id."'");
    $user_data = db_fetch_array($data);
    $callers_data = explode(',',$user_data['caller']);

     if($tlType == 'TEAM LEADER')
      {
         $and =" and users.user_type in('CLR','TEAM LEADER') ";
      }
      else if($tlType == 'RENEWAL TL')
      {
        $and =" and users.user_type in('RCLR','RENEWAL TL') ";
      }
  }else if($actionType == false){
     if($tlType == 'TEAM LEADER')
      {
         $and =" and users.user_type='CLR' ";
      }
      else if($tlType == 'RENEWAL TL')
      {
        $and =" and users.user_type='RCLR' ";
      }
      $callers_data = [0];
  }

  // print_r($lead_id); die;

    $res = db_query("select callers.* from callers left join users on callers.user_id=users.id where users.status='Active' ".$and." order by callers.name ASC"); 
    ?>

      <select name="caller[]" id="caller" class="multiselect_caller form-control" data-live-search="true" multiple data-validation-required-message="This field is required">
        
        <?php while ($row = db_fetch_array($res)) { ?>
        <option <?=(in_array($row['id'],$callers_data) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] . ' (' . $row['caller_id'] . ')' ?></option>
        <?php } ?>
      </select> 
         

 <script type="text/javascript">
     $('.multiselect_caller').multiselect({
        buttonWidth: '100%',
        includeSelectAllOption: true,
        nonSelectedText: 'Select an Option'
    });
 </script>  

