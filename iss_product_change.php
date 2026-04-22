<?php include('includes/include.php');
include_once('helpers/DataController.php');

$modify_log = new DataController();
$_POST['id']         = intval($_POST['id']);
$_POST['type']     = intval($_POST['type']);

/**
 * Admin product update
 */
if ($_POST['id']) {
    
        $update_query = db_query("update tbl_lead_product set product_type_id=".$_POST['type']." where lead_id=" . $_POST['id'] );

        $modify_name = getSingleresult("select product_type from tbl_product_pivot where id=".$_POST['type']);

        $log = ['lead_id'=> $_POST['id'],'type'=> 'Product Type','previous_name'=> $_POST['previous_type'],'modify_name'=> $modify_name,'created_date'=> date("Y-m-d H:i:s"),'created_by'=> $_SESSION['user_id'],'raw_id'=>0 ];
        $res = $modify_log->insert($log, 'lead_modify_log');
}
/**
 * Admin Raw lead product update
 */
if ($_POST['raw_lead_id']) {
    
    $update_query =  db_query("update raw_leads set product_id=".$_POST['product_id']." , product_type_id=".$_POST['type']." where id=" . $_POST['raw_lead_id'] );

    $modify_name = getSingleresult("select product_type from tbl_product_pivot where id=".$_POST['type']);
    $log = ['raw_id'=> $_POST['raw_lead_id'],'type'=> 'Product Type','previous_name'=>$_POST['previous_type'],'modify_name'=> $modify_name,'created_date'=> date("Y-m-d H:i:s"),'created_by'=> $_SESSION['user_id'],'lead_id'=> 0 ];

    $res = $modify_log->insert($log, 'lead_modify_log');
}
/**
 *  View lead partner product type update
 */
if ($_POST['lead_id']) {
    if ($_POST['type'] == 2) {
        $log = ['lead_id'=> $_POST['lead_id'],'type'=> 'Product Type','previous_name'=> 'CDGS Annual','modify_name'=> 'CDGS Perpetual','created_date'=> date("Y-m-d H:i:s"),'created_by'=> $_SESSION['user_id'],'raw_id'=>0
        ];
        $res = $modify_log->insert($log, 'lead_modify_log');

        $update_query = db_query("update tbl_lead_product set product_type_id=1 where lead_id=" . $_POST['lead_id'] );
    } elseif($_POST['type']==1) {
        $log = ['lead_id'=> $_POST['lead_id'],'type'=> 'Product Type','previous_name'=> 'CDGS Perpetual','modify_name'=> 'CDGS Annual','created_date'=> date("Y-m-d H:i:s"),'created_by'=> $_SESSION['user_id'],'raw_id'=>0
        ];
        $res = $modify_log->insert($log, 'lead_modify_log');
        $update_query = db_query("update tbl_lead_product set product_type_id=2 where lead_id=" . $_POST['lead_id'] );
    }
}

if ($_POST['rl_id']) {
    if ($_POST['type'] == 6) {
        $update_query = db_query("update tbl_lead_product set product_type_id=7 where lead_id=" . $_POST['rl_id'] );
    } elseif($_POST['type']==7) {
        $update_query = db_query("update tbl_lead_product set product_type_id=6 where lead_id=" . $_POST['rl_id'] );
    }
}


if ($_POST['ed_id']) {
    if ($_POST['type'] != '') {
        $update_query = db_query("update tbl_lead_product set product_type_id='".$_POST['type']."' where lead_id=" . $_POST['ed_id'] );
    }
}

if($_POST['raw_id']){
    if ($_POST['type'] == 2) {

        $update_query = db_query("update raw_leads set product_type_id=1 where id=" . $_POST['raw_id'] );

    } elseif($_POST['type']==1) {

        $update_query = db_query("update raw_leads set product_type_id=2 where id=" . $_POST['raw_id'] );

    }
}

if($_POST['lapsed_lead_id']){
    if ($_POST['type'] == 2) {

        $update_query = db_query("update tbl_lead_product set product_type_id=1 where lead_id=" . $_POST['lapsed_lead_id'] );

    } elseif($_POST['type']==1) {

        $update_query = db_query("update tbl_lead_product set product_type_id=2 where lead_id=" . $_POST['lapsed_lead_id'] );

    }
}

if($_POST['iss_lead_id']){
    if ($_POST['type'] == 2) {

        $update_query = db_query("update tbl_lead_product set product_type_id=1 where lead_id=" . $_POST['iss_lead_id'] );

    } elseif($_POST['type']==1) {

        $update_query = db_query("update tbl_lead_product set product_type_id=2 where lead_id=" . $_POST['iss_lead_id'] );

    }
}
