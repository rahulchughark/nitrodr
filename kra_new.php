<?php 
include('includes/header.php');


$helper = new DataController;

// admin_page();
admin_can_access();
?>

<style>
    body[data-layout=horizontal] .page-content {
        min-height: auto !important;
        max-height: calc(100vh - 146px) !important;
        overflow: auto;
    }
    .table {
        margin-bottom: 5px;
    }
    thead .custom-checkbox:before {
        background-color: rgb(239, 214, 198);
    }

    .scroll_div .table th {
        z-index: 9;
    }

    .table-striped tbody tr:nth-of-type(odd) .custom-checkbox:before {
        background-color: #f8f9fa;
    }

    .table-striped tbody tr:nth-of-type(even) .custom-checkbox:before {
        background-color: rgba(174, 174, 188, 0.1);
    }

    .table td.disabled {
        opacity: 0.6;
        pointer-events: none;
    }


</style>

<!-- ============================================================== -->
<!-- Page wrapper  -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                           <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home >KRA</small>
                                            <h4 class="font-size-14 m-0 mt-1">KRA</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">



<!-- Call Log KRA -->

                                <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="40px">
                                                <div class="col-sm-7">
                                                    <div class="custom-checkbox">
                                                        <input type="checkbox" name="is_group" id="is_group" value="yes" class="form-control" placeholder=""  <?= isset($is_group) && $is_group == 'yes' ? 'checked' : '' ?>>
                                                        <label for="is_group"></label>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>Call Log KRA</th>
                                            <th width="30%">Target</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        <?php 
                        $sql=db_query("select * from call_subject where 1 and kra_status=1");

                        while($data=db_fetch_array($sql)){
                            $isTargetExists = $helper->checkKRATargetExists($_GET['id'],1,$data['id'],$data['subject']);
                            $className = !$isTargetExists ? "disabled" : '';
                            $isCheckedInput = $isTargetExists ? true : false;
                            $targetValue = $helper->checkKRATargetExists($_GET['id'],1,$data['id'],$data['subject'],'target');

                        ?>
                                    
                                        <tr>
                                            <td>
                                                <div class="col-sm-7">
                                                    <div class="custom-checkbox">
                                                        <input type="checkbox"
                                                        class="form-control"
                                                        id="target-checkbox-<?= $data['id'] ?>"
                                                        name="target-checkbox-<?= $data['id'] ?>"
                                                        <?= isset($isCheckedInput) && $isCheckedInput ? 'checked' : '' ?>
                                                        onclick="return updateUserWiseKRA(event,'call-log-table',<?= $data['id'] ?>, '<?= addslashes($data['subject']) ?>',1,<?= $_GET['id'] ?>)"
                                                        <?= isset($is_group) && $is_group == 'yes' ? 'checked' : '' ?>>
                                                        <label for="target-checkbox-<?= $data['id'] ?>"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="call-log-table<?= $data['id'] ?> <?= $className?>"><?= $data['subject'] ?></td>
                                            <td class="call-log-table<?= $data['id'] ?> <?= $className?>">
                                            <input type="text" class="form-control"
                                             placeholder="00" value="<?= $targetValue ?>"  onkeyup="return updateKRATargetValue(event, <?= $data['id'] ?>, '<?= addslashes($data['subject']) ?>',1,<?= $_GET['id'] ?>)" style="max-width:150px"></td>
                                        </tr>
                                    <?php } ?>



                                    </tbody>
                                </table>


<!-- Lead Status KRA -->


                         <table id="example23" class="table display nowrap table-striped mt-3" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="40px">
                                                <div class="col-sm-7">
                                                    <div class="custom-checkbox">
                                                        <input type="checkbox" name="is_group" id="is_group" value="yes" class="form-control" placeholder=""  <?= isset($is_group) && $is_group == 'yes' ? 'checked' : '' ?>>
                                                        <label for="is_group"></label>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>Lead Status KRA</th>
                                            <th width="30%">Target</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        <?php 
                        $sql=db_query("select * from lead_status_master where 1 and kra_status=1");

                        while($leadStatus=db_fetch_array($sql)){
                            $isTargetExists = $helper->checkKRATargetExists($_GET['id'],2,$leadStatus['id'],$leadStatus['name']);
                            $className = !$isTargetExists ? "disabled" : '';
                            $isCheckedInput = $isTargetExists ? true : false;
                            $targetValue = $helper->checkKRATargetExists($_GET['id'],2,$leadStatus['id'],$leadStatus['name'],'target');

                        ?>
                                    
                                        <tr>
                                            <td>
                                                <div class="col-sm-7">
                                                    <div class="custom-checkbox">
                                                        <input type="checkbox"
                                                        class="form-control"
                                                        id="lead-status-checkbox-<?= $leadStatus['id'] ?>"
                                                        name="lead-status-checkbox-<?= $leadStatus['id'] ?>"
                                                        <?= isset($isCheckedInput) && $isCheckedInput ? 'checked' : '' ?>
                                                        onclick="return updateUserWiseKRA(event,'lead-status',<?= $leadStatus['id'] ?>, '<?= addslashes($leadStatus['name']) ?>',2,<?= $_GET['id'] ?>)"
                                                        <?= isset($is_group) && $is_group == 'yes' ? 'checked' : '' ?>>
                                                        <label for="lead-status-checkbox-<?= $leadStatus['id'] ?>"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lead-status<?= $leadStatus['id'] ?> <?= $className?>"><?= $leadStatus['name'] ?></td>
                                            <td class="lead-status<?= $leadStatus['id'] ?> <?= $className?>">
                                            <input type="text" class="form-control"
                                             placeholder="00" value="<?= $targetValue ?>"  onkeyup="return updateKRATargetValue(event, <?= $leadStatus['id'] ?>, '<?= addslashes($leadStatus['name']) ?>',2,<?= $_GET['id'] ?>)" style="max-width:150px"></td>
                                        </tr>
                                    <?php } ?>



                                    </tbody>
                                </table>

<!-- STAGE KRA -->

                                 <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="40px">
                                                <div class="col-sm-7">
                                                    <div class="custom-checkbox">
                                                        <input type="checkbox" name="is_group" id="is_group" value="yes" class="form-control" placeholder=""  <?= isset($is_group) && $is_group == 'yes' ? 'checked' : '' ?>>
                                                        <label for="is_group"></label>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>Stage KRA</th>
                                            <th width="30%">Target</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        <?php 
                        $sql=db_query("select * from stages where 1 and kra_status=1");

                        while($stageData=db_fetch_array($sql)){
                           $isTargetExists = $helper->checkKRATargetExists($_GET['id'],3,$stageData['id'],$stageData['stage_name']);
                           $className = !$isTargetExists ? "disabled" : '';
                           $isCheckedInput = $isTargetExists ? true : false;
                           $targetValue = $helper->checkKRATargetExists($_GET['id'],3,$stageData['id'],$stageData['stage_name'],'target');

                        ?>
                                    
                                        <tr>
                                            <td>
                                                <div class="col-sm-7">
                                                    <div class="custom-checkbox">
                                                        <input type="checkbox"
                                                        class="form-control"
                                                        id="stage-target-checkbox-<?= $stageData['id'] ?>"
                                                        name="stage-target-checkbox-<?= $stageData['id'] ?>"
                                                        <?= isset($isCheckedInput) && $isCheckedInput ? 'checked' : '' ?>
                                                        onclick="return updateUserWiseKRA(event,'stage-table',<?= $stageData['id'] ?>, '<?= addslashes($stageData['stage_name']) ?>',3,<?= $_GET['id'] ?>)"
                                                        <?= isset($is_group) && $is_group == 'yes' ? 'checked' : '' ?>>
                                                        <label for="stage-target-checkbox-<?= $stageData['id'] ?>"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="stage-table<?= $stageData['id'] ?> <?= $className?>"><?= $stageData['stage_name'] ?></td>
                                            <td class="stage-table<?= $stageData['id'] ?> <?= $className?>">
                                            <input type="text" class="form-control"
                                             placeholder="00" value="<?= $targetValue ?>"  onkeyup="return updateKRATargetValue(event, <?= $stageData['id'] ?>, '<?= addslashes($stageData['stage_name']) ?>',3,<?= $_GET['id'] ?>)" style="max-width:150px"></td>
                                        </tr>
                                    <?php } ?>



                                    </tbody>
                                </table>


<!-- Sub Stage KRA - Follow Up -->

          <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="40px">
                                                        <div class="col-sm-7">
                                                            <div class="custom-checkbox">
                                                                <input type="checkbox" name="is_group" id="is_group" value="yes" class="form-control" placeholder=""  <?= isset($is_group) && $is_group == 'yes' ? 'checked' : '' ?>>
                                                                <label for="is_group"></label>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th>Follow Up Sub Stage KRA</th>
                                                    <th width="30%">Target</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                <?php 
                                $sql = db_query("SELECT * FROM sub_stage WHERE kra_status = 1 AND stage_name = 'Follow-up'");
                                while ($followUpSubStage = db_fetch_array($sql)) {
                                    $stageId = $followUpSubStage['id'];
                                    $stageName = addslashes($followUpSubStage['name']);
                                    $isTargetExists = $helper->checkKRATargetExists($_GET['id'], 4, $stageId, $followUpSubStage['name']);
                                    $targetValue = $helper->checkKRATargetExists($_GET['id'], 4, $stageId, $followUpSubStage['name'], 'target');
                                    $className = !$isTargetExists ? 'disabled' : '';
                                    $isChecked = $isTargetExists || (($is_group ?? '') === 'yes');
                                ?>
                                <tr>
                                    <td>
                                        <div class="custom-checkbox">
                                            <input type="checkbox"
                                                class="form-control"
                                                id="follow-up-sub-stage-<?= $stageId ?>"
                                                name="follow-up-sub-stage-<?= $stageId ?>"
                                                <?= $isChecked ? 'checked' : '' ?>
                                                onclick="return updateUserWiseKRA(event, 'follow-up-sub-stage-table', <?= $stageId ?>, '<?= $stageName ?>', 4, <?= $_GET['id'] ?>)">
                                            <label for="follow-up-sub-stage-<?= $stageId ?>"></label>
                                        </div>
                                    </td>
                                    <td class="follow-up-sub-stage-table<?= $stageId ?> <?= $className ?>">
                                        <?= $followUpSubStage['name'] ?>
                                    </td>
                                    <td class="follow-up-sub-stage-table<?= $stageId ?> <?= $className ?>">
                                        <input type="text" class="form-control" placeholder="00"
                                            name="target[<?= $stageId ?>]"
                                            value="<?= $targetValue ?>"
                                            onkeyup="return updateKRATargetValue(event, <?= $stageId ?>, '<?= $stageName ?>', 4, <?= $_GET['id'] ?>)"
                                            style="max-width:150px">
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>



<!-- Sub Stage KRA - Quote -->

          <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                            <thead>

                                                <tr>
                                                    <th width="40px">
                                                        <div class="col-sm-7">
                                                            <div class="custom-checkbox">
                                                                <input type="checkbox" name="is_group" id="is_group" value="yes" class="form-control" placeholder=""  <?= isset($is_group) && $is_group == 'yes' ? 'checked' : '' ?>>
                                                                <label for="is_group"></label>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th>Quote Sub Stage KRA</th>
                                                    <th width="30%">Target</th>
                                                </tr>
                                                
                                            </thead>
                                            <tbody>
                                <?php 
                                $sql=db_query("select * from sub_stage where 1 and kra_status=1 and stage_name = 'quote'");

                                while($followUpSubStage=db_fetch_array($sql)){
                                   $isTargetExists = $helper->checkKRATargetExists($_GET['id'],5,$followUpSubStage['id'],$followUpSubStage['name']);
                                   $className = !$isTargetExists ? "disabled" : '';
                                   $isCheckedInput = $isTargetExists ? true : false;
                                   $targetValue = $helper->checkKRATargetExists($_GET['id'],5,$followUpSubStage['id'],$followUpSubStage['name'],'target');

                                ?>
                                            
                                                <tr>
                                                    <td>
                                                        <div class="col-sm-7">
                                                            <div class="custom-checkbox">
                                                                <input type="checkbox"
                                                                class="form-control"
                                                                id="quote-sub-stage-checkbox-<?= $followUpSubStage['id'] ?>"
                                                                name="quote-sub-stage-checkbox-<?= $followUpSubStage['id'] ?>"
                                                                <?= isset($isCheckedInput) && $isCheckedInput ? 'checked' : '' ?>
                                                                onclick="return updateUserWiseKRA(event,'quote-sub-stage',<?= $followUpSubStage['id'] ?>, '<?= addslashes($followUpSubStage['name']) ?>',5,<?= $_GET['id'] ?>)"
                                                                <?= isset($is_group) && $is_group == 'yes' ? 'checked' : '' ?>>
                                                                <label for="quote-sub-stage-checkbox-<?= $followUpSubStage['id'] ?>"></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="quote-sub-stage<?= $followUpSubStage['id'] ?> <?= $className?>"><?= $followUpSubStage['name'] ?></td>
                                                    <td class="quote-sub-stage<?= $followUpSubStage['id'] ?> <?= $className?>">
                                                    <input type="text" class="form-control"
                                                     placeholder="00" value="<?= $targetValue ?>"  onkeyup="return updateKRATargetValue(event, <?= $followUpSubStage['id'] ?>, '<?= addslashes($followUpSubStage['name']) ?>',5,<?= $_GET['id'] ?>)" style="max-width:150px"></td>
                                                </tr>
                                            <?php } ?>



                                            </tbody>
                                        </table>

          

                            </div> 
                        </div> 

                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div>
        </div> <!-- container-fluid -->
    </div>
</div>
<!-- End Page-content -->
 
<?php include('includes/footer.php') ?>

<script>
    $('#example23').DataTable({
        dom: 'Bfrtip',
        language: {
            paginate: {
                previous: '<i class="fas fa-arrow-left"></i>',
                next: '<i class="fas fa-arrow-right"></i>'
            }
        },
        buttons: [
                    'copy', 'csv', 'excel',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    },  'print', 'pageLength',
                    
                ],
		lengthMenu: [
        [ 15, 25, 50, 100,500,1000 ],
        [ '15', '25', '50','100','500', '1000' ]
    ],
        "displayLength": 15,
    });
</script>

<script>
    $(document).ready(function() {
        var wfheight = $(window).height();
        $('.dataTables_wrapper').height(wfheight - 310);				
        $("#example23").tableHeadFixer(); 

    });
</script>