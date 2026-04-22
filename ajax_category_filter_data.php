<?php 

// ajax_category_filter_data.php
 
include_once 'helpers/DataController.php';
$modify_log = new DataController();
$keyword = $_POST['keyword'];

echo $modify_log->getAllCategoriesTreeUl($keyword);