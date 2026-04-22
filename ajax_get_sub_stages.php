<?php
include_once('helpers/DataController.php');

$stage = isset($_POST['stage_name']) ? trim($_POST['stage_name']) : '';
$dataObj = new DataController;

if ($stage === '') {
    echo json_encode([]);
    exit;
}

$dataObj = new DataController();
$subStages = $dataObj->getSubStagesByStageName($stage);

echo json_encode($subStages);
exit;