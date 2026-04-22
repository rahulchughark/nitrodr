<?php
include("includes/include.php");
include_once('helpers/DataController.php');
admin_protect();

$dataObj = new DataController;
$product_id = $_POST['product_id'] ?? 0;

$result = db_query("
    SELECT stage, sub_stage
    FROM tbl_lead_product_opportunity
    WHERE id = {$product_id}
");

$row = db_fetch_array($result);

$stage     = $row['stage'];
$sub_stage = $row['sub_stage'];

if($stage){
$stageName = getSingleresult("
    SELECT stage_name
    FROM stages
    WHERE id = {$stage}
");
$subStages = $dataObj->getSubStagesByStageName($stageName);
}else{
  $subStages = [];  
}

?>
    

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title align-self-center mt-0">
                Change Stage</b>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <div class="modal-body py-4">
                <form id="stageForm" >
                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                    <div class="row">
                       <div class="col">

                            <div class="form-group">
                                <label class="control-label">Stage</label>
                               <select name="stage" class="form-control" required onchange="loadSubStages(this)">
                                        <option value="">Select Stage</option>

                                        <?php
                                        $stages = $dataObj->getActiveStageNames(); // ['id'=>..., 'name'=>...]
                                        if (!empty($stages)) {
                                            foreach ($stages as $stageRow) {
                                                $selected = ($stageRow['id'] == $stage) ? 'selected' : '';
                                                 echo '<option 
                                                            value="' . (int)$stageRow['id'] . '" 
                                                            data-name="' . htmlspecialchars($stageRow['name']) . '" 
                                                            ' . $selected . '>'
                                                        . htmlspecialchars($stageRow['name']) .
                                                    '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                            </div>
                            
                        </div>
                       <div class="col <?= empty($subStages) ? 'd-none' : '' ?>" id="sub_stage_container">
                            <div class="form-group">
                                <label class="control-label">Sub Stage</label>
                                <select name="sub_stage" id="sub_stage" class="form-control" required>
                                    <option value="">Select Sub Stage</option>
                                    <?php
                                    if (!empty($subStages)) {
                                        foreach ($subStages as $row) {

                                            $selected = ($row['id'] == $sub_stage) ? 'selected' : '';

                                            echo '<option value="' . (int)$row['id'] . '" ' . $selected . '>'
                                                . htmlspecialchars($row['name']) .
                                            '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                   <button type="button" 
                        id="submitStageBtn" 
                        class="btn btn-primary"
                        onclick="submitStageForm(this)">
                    <span class="btn-text">Submit</span>
                    <span class="btn-loader d-none">
                        <i class="fa fa-spinner fa-spin"></i> Processing...
                    </span>
                </button>
                </form>
            </div>
        </div>
    </div>