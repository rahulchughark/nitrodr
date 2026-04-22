
<?php
include_once 'helpers/DataController.php';
admin_protect();
$dataObj = new DataController();

$pid = $_POST['pid'] ?? 0;


echo $dataObj->getAllCategoriesTreeCheckbox($pid); 


?>
