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
?>

<script>
    $(document).ready(function() {
            $('#profiling_type').on('change', function() {               
             var val_type = $('#profiling_type').val();                       
            if(val_type == 'profiling_validation'){
                //alert('abc');
                $("#call_subject option").remove();
                $('#call_subject').append('<option value="Profiling Call">Profiling Call</option>');
                $("#attachment").show();
                $("#attachment_user").hide();                
          
         }else if(val_type == 'emailer_validation'){
             $("#attachment").hide();
             $("#attachment_user").show();
            // $("#attachment_user").show()

             $("#call_subject option").remove();
             $('#call_subject').append('<option value="Profiling Call">Profiling Call</option>');

         }
        });
        });


</script>