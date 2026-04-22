<?php 
include('includes/include.php');

if(!empty($_POST["lead_type_id"])){    
        echo '
        <label class="control-label">Type of validation<span class="text-danger">*</span></label>
        <select name="validation_type" class="form-control" id="profiling_type" required data-validation-required-message="This field is required">
        <option value="" >Type of validation</option>'; ?>
            <option value="profiling_validation">Validation through call (Profiling)</option>
            <option value="emailer_validation">Validation through emailer</option>
            <?php  
		echo '</select>';    
} 

$activity_log = db_query("select call_subject from activity_log where pid=".$_POST["lid"]."  order by id desc limit 1");
while($activity_arr = db_fetch_array($activity_log)){
  $log_arr[] = $activity_arr['call_subject'];
}
//print_r($log_arr);
?>

<script>
    $(document).ready(function() {
            $('#profiling_type').on('change', function() {               
             var val_type = $('#profiling_type').val(); 
             var call_subject = <?php echo json_encode($log_arr); ?>;         

            if(val_type == 'profiling_validation' && call_subject!='Profiling Call'){               
                $('#call_subject_profiling').show();
                $('#remarks').show();
                $("#call_subject_emailer").remove();
                $("#remarks_emailer").remove();
                $("#attachment_user").remove();
          
           }else if(val_type == 'emailer_validation'){
                $("#attachment").remove();
                $("#attachment_user").show();

                if(call_subject!='Profiling Call'){               
                $('#call_subject_emailer').show();
                $('#remarks_emailer').show();
                $("#call_subject_profiling").remove();
                $("#remarks").remove();
            }            
         }
        });
        });
</script>