<?php

function personalReport_columnData($table)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $array = [];
  $sql_select = db_query("SELECT * FROM $table WHERE 1");
  while ($data = db_fetch_array($sql_select)) {
    $array[] = $data;
  }
  return $array;
}

function personalReport_tableLabels($table, $field)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $array = [];
  $sql_select = db_query("SELECT field_label FROM $table WHERE order_field_name IN ('" . $field . "')");
  while ($data = db_fetch_array($sql_select)) {
    $array[] = $data;
  }
  return $array;
}

function personalReport_dateType($table)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $array = [];
  $date_query = db_query("SELECT o.created_date,o.prospecting_date,o.partner_close_date as closed_date FROM $table as o WHERE 1");
  while ($data = mysqli_fetch_field($date_query)) {
    $array[] = $data;
  }
  return $array;
}

function personalReport_industrySelect($table)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $array = [];
  $query = db_query("SELECT * FROM $table WHERE 1");
  return $query;
}

function personalReport_stateSelect($table)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $array = [];
  $query = db_query("SELECT * FROM $table WHERE 1");
  return $query;
}

function personalReport_citySelect($table)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $array = [];
  $query = db_query("SELECT Distinct(city) FROM $table WHERE 1 and city IS NOT NULL order by city");
  return $query;
}

function personalReportPartner_industry($table, $field1)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query = db_query("SELECT Distinct(i.name) as industry,i.id FROM $table as o
  LEFT JOIN industry as i on o.industry = i.id WHERE 1 and o.team_id =" . $field1 . " order by i.name");

  return $query;
}

function personalReportPartner_state($table, $field1)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query = db_query("SELECT Distinct(i.name) as state,i.id FROM $table as o
  LEFT JOIN states as i on o.state = i.id WHERE 1 and o.team_id =" . $field1 . " order by i.name");

  return $query;
}

function personalReportPartner_city($table, $field1)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $array = [];
  $query = db_query("SELECT Distinct(city) FROM $table WHERE 1 and city IS NOT NULL and team_id =" . $field1);
  return $query;
}

function personalReport_ajaxData($table, $field)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $date_query = db_query("SELECT " . $field . " FROM $table WHERE 1=1 ORDER By id Desc");
  //print_r($date_query);die;
  return $date_query;
}

function personalReport_ajaxDateSearch($table, $col_label, $d_type, $d_from, $d_to)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $date_query = db_query("SELECT $col_label FROM $table WHERE ($d_type BETWEEN '$d_from' AND '$d_to') ORDER By id Desc");
  return $date_query;
}

function personalReport_TableData($table, $field1, $field2, $field3,$u_cond)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $select_query = db_query("SELECT $field1,o.id,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by 
  FROM $table as o
  LEFT JOIN states as s ON o.state=s.id
  LEFT JOIN sub_industry as si ON o.sub_industry = si.id
  LEFT JOIN industry as i ON o.industry = i.id
  LEFT JOIN users as u ON o.created_by = u.id 
  WHERE 1=1 $u_cond GROUP BY o.id ORDER By o.id Desc LIMIT " . $field2 . " ," . $field3 . "");
  //"SELECT $field1,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by FROM $table as o
  // LEFT JOIN states as s ON o.state = s.id
  // LEFT JOIN sub_industry as si ON o.industry = si.industry_id
  // LEFT JOIN industry as i ON o.industry = i.id
  // LEFT JOIN users as u ON o.created_by = u.id
  // WHERE 1=1 ORDER By o.id Desc LIMIT " . $field2 . " ," . $field3 . "";
  return $select_query;
}

function personalReport_TableDataWithDate($table, $field1, $field2, $field3, $field4,$u_cond)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $select_query = db_query("SELECT o.id,p.product_id,p.product_type_id,$field1,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by
  FROM $table as o
  LEFT JOIN states as s ON o.state = s.id
  LEFT JOIN sub_industry as si ON o.sub_industry = si.id
  LEFT JOIN industry as i ON o.industry = i.id
  LEFT JOIN users as u ON o.created_by = u.id
  LEFT JOIN tbl_lead_product as p ON o.id=p.lead_id
  WHERE 1 $u_cond $field4 GROUP by o.id
  ORDER By o.id Desc LIMIT " . $field2 . " ," . $field3 . "");

  return $select_query;
}


function personalRep_PartnerData($table, $field1, $field2, $field3, $field4)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $select_query = db_query("SELECT $field1,o.id,o.association_name,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by 
  FROM $table as o
  LEFT JOIN states as s ON o.state=s.id
  LEFT JOIN sub_industry as si ON o.sub_industry = si.id
  LEFT JOIN industry as i ON o.industry = i.id
  LEFT JOIN users as u ON o.created_by = u.id 
  WHERE 1=1 and o.team_id =" . $field4 . " GROUP BY o.id ORDER By o.id Desc LIMIT " . $field2 . " ," . $field3 . "");

  return $select_query;
}

function personalRep_PartnerTableWithDate($table, $field1, $field2, $field3, $field4, $field5)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $select_query = db_query("SELECT o.id,o.association_name,p.product_id,p.product_type_id,$field1,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by
  FROM $table as o
  LEFT JOIN states as s ON o.state = s.id
  LEFT JOIN sub_industry as si ON o.sub_industry = si.id
  LEFT JOIN industry as i ON o.industry = i.id
  LEFT JOIN users as u ON o.created_by = u.id
  LEFT JOIN tbl_lead_product as p ON o.id=p.lead_id
  WHERE 1 $field4 and o.team_id =$field5 GROUP by o.id 
  ORDER By o.id Desc LIMIT " . $field2 . " ," . $field3 . "");
  // "SELECT $check_label,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by  FROM orders as o
  // LEFT JOIN states as s ON o.state = s.id
  // LEFT JOIN sub_industry as si ON o.sub_industry = si.id
  // LEFT JOIN industry as i ON o.industry = i.id
  // LEFT JOIN users as u ON o.created_by = u.id
  // WHERE 1 $data 
  // ORDER By o.id Desc LIMIT " . $requestData['start'] . " ," . $requestData['length'] . ""
  return $select_query;
}


function massLead_TableData($table, $field1, $field2)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $select_query = db_query("SELECT o.r_user,o.created_date,o.r_name,o.agreement_type as license_type,o.caller,o.id,o.lead_status,o.quantity,o.school_name,o.eu_email,o.eu_mobile,o.close_time,o.stage,u.name as user_name FROM $table as o
  LEFT JOIN users as u ON o.created_by = u.id
  WHERE 1=1 GROUP BY o.id ORDER By o.id Asc LIMIT " . $field1 . " ," . $field2 . "");
  return $select_query;
}


function massLead_DataWithConditions($table, $field1, $field2, $field3)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $select_query = db_query("SELECT o.r_user,o.created_date,o.r_name,o.agreement_type as license_type,o.caller,o.id,o.lead_status,o.quantity,o.school_name,o.eu_email,o.eu_mobile,o.close_time,o.stage,u.name as user_name,p.product_id,p.product_type_id FROM $table as o
  LEFT JOIN users as u ON o.created_by = u.id
  left join tbl_lead_product as p on o.id=p.lead_id
  WHERE 1 $field1 
  GROUP BY o.id ORDER By o.id Desc LIMIT " . $field2 . " ," . $field3 . "");
  return $select_query;
}

function massLead_NewCaller($table)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $select_query = db_query("SELECT * FROM $table WHERE 1 ORDER By id Desc");
  return $select_query;
}

function massLead_modifyLog($table, $field1)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $select_query = db_query("SELECT o.*,cl.name as caller FROM orders as o
  LEFT JOIN callers as cl ON o.caller = cl.id
  WHERE o.id = $field1 ");
  return $select_query;
}

function saveNotification($table, $id, $title, $company_name, $submitted_by, $sender_type, $partner_name, $sender_id, $receiver_id, $initiate_reason, $visit_done, $usage_confirmed)
{
  $sqlQuery = "INSERT INTO " . $table . "(type_id, title, company_name, submitted_by, sender_type,partner_name,created_at,sender_id,receiver_id,initiate_reason,visit_done,usage_confirmed) VALUES('$id', '$title', '$company_name', '$submitted_by', '$sender_type','$partner_name',now(),$sender_id,'$receiver_id','$initiate_reason','$visit_done','$usage_confirmed')";
  $result = db_query($sqlQuery);
  if (!$result) {
    return ('Error in query: ' . db_error());
  } else {
    return $result;
  }
}

function selectNotification($table)
{
  $query = db_query("Select * from $table where 1");
  return $query;
}

function updateNotification($type_id)
{
  $update = db_query("update lead_notification set is_read = 1 where type_id ='.$type_id.'");
  return $update;
}


function selectTitleOnClick($field1, $field2)
{
  $lead_notify = db_query("select * from lead_notification 
    where sender_type='Partner' and type_id =" . $field1 . " and sender_id =" . $field2 . " and is_read=0");

  if (mysqli_num_rows($lead_notify) > 0) {
    return true;
  }
}
function modification_log($table, $id, $field1, $field2)
{

  $res =   db_query("insert into $table(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $id . "','Request Status','" . $field1 . "','LC',now(),'" . $field2 . "')");

  return $res;
}

function ReLog_modification($table, $id, $field1, $field2)
{

  $res =   db_query("insert into $table(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $id . "','Re-log Status','Expired','" . $field1 . "',now(),'" . $field2 . "')");

  return $res;
}

function delete_modification_log($table, $id, $field1, $field2)
{

  $res =   db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $id . "','Request Delete Status','" . $field1 . "','LC',now(),'" . $field2 . "')");

  return $res;
}

function module_listing($sel = 0)
{
  $html = '';

  $i = 0;
  foreach (db_query("select * from tbl_module where parentId='0' order by setOrder") as $res1) {
    $html .= '<option value="' . $res1['id'] . '" ' . ($res1['id'] == $sel ? 'selected' : '') . '>' . $res1['name'] . '</option>';
    //$html[$i][$res1['id']]=$res1['name'];
    foreach (db_query("select * from tbl_module where parentId='" . $res1['id'] . "' order by setOrder") as $res2) {
      $j = 0;
      $html .= '<option value="' . $res2['id'] . '" ' . ($res2['id'] == $sel ? 'selected' : '') . '>&nbsp;&nbsp;&nbsp;' . $res2['name'] . '</option>';
      //$html[$i][$j][$res2['id']]='&nbsp;&nbsp;&nbsp;'.$res2['name'];
      $j++;
    }
    $i++;
  }
  //print_r($html);
  return $html;
}
//module listing tree
function get_tree()
{

  $cat_tree_arr = [];

  $i = 0;
  foreach (db_query("select * from tbl_module where parentId='0' order by setOrder ASC") as $res1) {
    $spacing = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    $cat_tree_arr[] = array('id' => $res1['id'], 'url' => $res1['url'], 'icon' => $res1['icon'], 'setOrder' => $res1['setOrder'], 'parentId' => $res1['parentId'], 'name' => $res1['name'] . $spacing . $res1['user_type'], 'status' => $res1['status'], 'user_type' => $res1['user_type'],'menu'=>"parent");

    foreach (db_query("select * from tbl_module where parentId='" . $res1['id'] . "' order by setOrder ASC") as $value) {
      $j = 0;

      // $spacing = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      $spacing = '';
      $cat_tree_arr[] = array('id' => $value['id'], 'url' => $value['url'], 'icon' => $value['icon'], 'setOrder' => $value['setOrder'], 'parentId' => $value['parentId'], 'name' => $spacing . $value['name'], 'status' => $value['status'], 'user_type' => $value['user_type'],'menu'=>"child");

      $j++;
      $spacing = ' ';
    }
    $i++;
  }

  return $cat_tree_arr;
}

function get_tree_mainModule()
{

  $cat_tree_arr = [];

  $i = 0;
  foreach (db_query("select * from tbl_module where parentId='0' order by setOrder ASC") as $res1) {
    $cat_tree_arr[] = array('id' => $res1['id'], 'url' => $res1['url'], 'icon' => $res1['icon'], 'setOrder' => $res1['setOrder'], 'parentId' => $res1['parentId'], 'name' => $res1['name'], 'status' => $res1['status'], 'user_type' => $res1['user_type']);

    foreach (db_query("select * from tbl_module where parentId='" . $res1['id'] . "' order by setOrder ASC") as $value) {
      $j = 0;
      // $spacing = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

      $cat_tree_arr[] = array('id' => $value['id'], 'url' => $value['url'], 'icon' => $value['icon'], 'setOrder' => $value['setOrder'], 'parentId' => $value['parentId'], 'name' => $spacing . $value['name'], 'status' => $value['status'], 'user_type' => $value['user_type']);

      $j++;
      $spacing = ' ';
    }
    $i++;
  }

  return $cat_tree_arr;
}

//for adding module details(form data)
function add_module($field1, $field2, $field3, $field4, $field5, $field6, $field7, $field8)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("INSERT INTO `tbl_module`(`name`, `url`, `status`, `parentId`, `setOrder`, `icon`,`user_type`,`is_function`) VALUES ('" . $field1 . "' ,'" . $field2 . "' ,'" . $field3 . "' ,'" . $field4 . "','" . $field5 . "','" . $field6 . "','" . $field7 . "','" . $field8 . "')");

  return $res;
}

//for editing module details(form data)
function update_module($field1, $field2, $field3, $field4, $field5, $field6, $field7, $field8, $field9)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("UPDATE `tbl_module` set `name`='" . $field1 . "',`url`='" . $field2 . "',`status`='" . $field3 . "',`parentId`='" . $field4 . "',`setOrder`='" . $field5 . "',`icon`='" . $field6 . "',`user_type`= '" . $field8 . "',`is_function`= '" . $field9 . "' where id=" . $field7);

  return $res;
}

//getting roles in dropdown
function getRoleData()
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("select * from user_type_role where status=1");

  return $res;
}

//get module data
function getModuleData()
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("select * from tbl_module where status=1");

  return $res;
}


//for role module
function get_access_details()
{
  $result = [];

  $sql = db_query("select r.*, (select GROUP_CONCAT(m.name) from tbl_role_access a inner join tbl_module m on a.moduleId=m.id where a.roleId=r.id order by m.setOrder) as b from user_type_role r where r.status='1' ");

  foreach ($sql as $value) {

    $result[] = array('id' => $value['id'], 'role' => $value['role_type'], 'module_name' => $value['b']);
  }

  //print_r($result);
  return $result;
}

//checkbox data roles
function get_role_access($id)
{

  $res = db_query("select moduleId as n from tbl_role_access where roleId='" . $id . "'");

  $arr = array();

  foreach ($res as $res) {
    $arr[] = $res['n'];
  }
  return implode(',', $arr);
}

//delete checkbox data role module
function delete_roles($id)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $delete_query = db_query("delete from tbl_role_access where roleId=" . $id);
  return $delete_query;
}

function update_roles_permission($id, $field1, $field2, $field3, $field4, $field5, $field6, $field7, $field8)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $update_query = db_query("update tbl_role_permission set edit_log='" . $field1 . "',edit_lead='" . $field2 . "',edit_stage='" . $field3 . "',edit_date='" . $field4 . "',edit_ownership='" . $field5 . "',edit_status='" . $field6 . "',edit_review_log='" . $field7 . "',edit_product='" . $field8 . "' where role_id=" . $id);
  return $update_query;
}

function access_role_permission()
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $query = db_query("select * from tbl_role_permission where role_id=" . $_SESSION['role_id']);

  return $query;
}

function insert_roles_permission($field1, $field2, $field3, $field4, $field5, $field6, $field7, $field8, $field9)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query = db_query("insert into tbl_role_permission(role_id,edit_log,edit_lead,edit_stage,edit_date,edit_ownership,edit_status,edit_review_log,edit_product) values('" . $field1 . "','" . $field2 . "','" . $field3 . "','" . $field4 . "','" . $field5 . "','" . $field6 . "','" . $field7 . "','" . $field8 . "','" . $field9 . "')");
  return $query;
}

function get_role_permission($id)
{

  $res = db_query("select * from tbl_role_permission where role_id='" . $id . "'");

  return $res;
}

//insert checkbox data role module
function insert_roles($field1, $field2)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query =  db_query("insert into tbl_role_access(moduleId,roleId) values('" . $field1 . "','" . $field2 . "')");

  return $query;
}

//checkbox data users
function get_user_access($id)
{

  $res = db_query("select moduleId as n from tbl_user_access where userId='" . $id . "'");

  $arr = array();

  foreach ($res as $res) {
    $arr[] = $res['n'];
  }
  return implode(',', $arr);
}

//for user module
function get_user_access_details()
{
  $result = [];


  $sql = db_query("select u.*,r.role_type, (select GROUP_CONCAT(m.name) from tbl_user_access a inner join tbl_module m on a.moduleId=m.id where a.userId=u.id order by m.setOrder) as b from users u left join user_type_role r on u.user_type=r.role_code where u.status='Active' order by u.id asc");

  foreach ($sql as $value) {

    $result[] = array('id' => $value['id'], 'name' => $value['name'], 'role' => $value['role'], 'module_name' => $value['b'], 'profile_path' => $value['profile_path'], 'role_type' => $value['role_type']);
  }

  //print_r($result);
  return $result;
}

//delete checkbox data user module
function delete_user_roles($id)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $delete_query = db_query("delete from tbl_user_access where userId=" . $id);
  return $delete_query;
}
//insert checkbox data user module
function insert_user_roles($field1, $field2)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query =  db_query("insert into tbl_user_access(moduleId,userId) values('" . $field1 . "','" . $field2 . "')");

  return $query;
}


//for sidebar
function user_access($userId, $roleId)
{
  $roleAccess =  get_role_access($roleId);
  $userAccess  =  get_user_access($userId);
  //print_r($userAccess);
  //print_r($roleAccess);

  if ($roleAccess != '' &&  $userAccess != '') {
    $roleAccessData = explode(",", $roleAccess);
    $userAccessData  = explode(",", $userAccess);
    $combAccessData = array_intersect($roleAccessData, $userAccessData);
    //$combAccessData = array_unique(array_merge($roleAccessData, $userAccessData));
    //print_r($combAccessData);
    return implode(",", $combAccessData);
  } else if ($roleAccess != '') {
    return $roleAccess;
  } else if ($userAccess != '') {
    return   $userAccess;
  }
}

function get_modules_by_level($pid = 0)
{
  return db_query("select * from tbl_module where parentId='" . $pid . "' AND status=1 order by setOrder");
}

function add_campaign($field1, $field2, $field3, $field4, $field5, $field6, $field7)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("INSERT INTO `campaign`(`name`, `description`, `status`, `start_date`, `end_date`, `created_by`,`created_at`) VALUES ('" . $field1 . "' ,'" . $field2 . "' ,'" . $field3 . "' ,'" . $field4 . "','" . $field5 . "','" . $field6 . "','" . $field7 . "')");

  return $res;
}

function get_tag_data()
{

  $res = db_query("select c.*,u.name as user,tp.product_name from tag as c left join users as u on c.created_by=u.id left join tbl_product as tp on c.product_id=tp.id");

  return $res;
}

function update_tag($field1, $field2, $field3, $field4, $field5, $field6, $field7, $field8)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("UPDATE `tag` set `name`='" . $field1 . "',`description`='" . $field2 . "',`status`='" . $field3 . "',`start_date`='" . $field4 . "',`end_date`='" . $field5 . "',`created_by`='" . $field6 . "',`product_id`='" . $field8 . "' where id=" . $field7);

  return $res;
}

function campaign_data($field)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $date = date('Y-m-d');
  $res = db_query("select * from campaign where status=1 and start_date<='" . $date . "'and end_date>='" . $date . " ' and product_id=" . $field . " order by id desc");

  return $res;
}

function personalReportPartner_campaign($table)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query = db_query("SELECT * FROM $table order by id desc");

  return $query;
}

//Product module
function addProductType($field1, $field2, $field3, $field4, $field5, $field6)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("INSERT INTO `tbl_product_pivot`(`product_id`, `product_type`,`license_type`,`product_code`, `status`, `created_at`) VALUES ('" . $field1 . "' ,'" . $field2 . "' ,'" . $field3 . "' ,'" . $field4 . "','" . $field5 . "',$field6)");

  return $res;
}

function selectProduct($table)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("select * from $table where status=1");

  return $res;
}

function selectProductPartner($field)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("SELECT b.id,b.product_name,b.status,a.id as pid FROM partners a INNER JOIN tbl_product b ON FIND_IN_SET(b.id, a.product_id) > 0 where b.status=1 and a.id=" . $field);

  //$row = db_fetch_array($res);

  //$res1 = db_query("SELECT * from tbl_product_pivot where product_id=".$row['id']);

  //$res_query = db_fetch_array()

  return $res;
}

function selectProductType($table, $field)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("select * from $table where status=1 and product_id=" . $field);

  return $res;
}

function productTypeMultiselect($field)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query('select * from tbl_product_pivot where status=1 and product_id in ("' . implode('", "', $field) . '")');

  return $res;
}

function addProducts($table, $field1, $field2, $field3, $field4)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("INSERT INTO `$table`(`product_name`, `description`, `status`, `created_at`) VALUES ('" . $field1 . "' ,'" . $field2 . "' ,'" . $field3 . "' ,$field4 )");

  return $res;
}
function manageProducts($table)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query = db_query("SELECT * FROM $table order by id desc");

  return $query;
}

function manageProductTypes($table)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query = db_query("SELECT pt.*,p.product_name  FROM $table as pt left join tbl_product as p on pt.product_id=p.id order by pt.id desc");

  return $query;
}

function updateProductType($table, $field1, $field2, $field3, $field4, $field5, $field6, $field7)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query = db_query("UPDATE `tbl_product_pivot` set `product_id`='" . $field1 . "', `product_type`='" . $field2 . "',`product_code`='" . $field3 . "',`license_type`='" . $field4 . "', `status`='" . $field5 . "', `updated_at`=$field6 where id=" . $field7);


  return $query;
}

function selectLeadPartner($table, $field1)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query = db_query("SELECT pt.*,p.product_name  FROM $table as pt left join tbl_product as p on pt.product_id=p.id where pt.id =" . $field1);

  return $query;
}

function insertLeadDataParallel($table, $field1, $field2, $field3, $field4, $field5, $field6, $field7, $field8, $field9, $field10, $field11, $field12, $field13, $field14, $field15, $field16, $field17, $field18, $field19, $field20, $field21, $field22, $field23, $field24, $field25, $field26, $field27, $field28, $field29, $field30)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("INSERT INTO `$table`(`r_name`, `r_email`, `r_user`,`source`, `lead_type`, `company_name`, `parent_company`, `landline`,region, `industry`,sub_industry, `address`, `pincode`, `state`, `city`, `country`, `eu_name`, `eu_email`, `eu_landline`, `department`, `eu_mobile`, `eu_designation`, `eu_role`, `visit_remarks`, `license_type`, `quantity`, `created_by`, `team_id`, `status`,user_attachement,partner_close_date,campaign_type) VALUES ('" . $field1 . "','" . $field2 . "','" . $field3 . "','" . $field4 . "','" . $field5 . "','" . htmlspecialchars($field6, ENT_QUOTES) . "','" . htmlspecialchars($field7, ENT_QUOTES) . "','" . $field8 . "','" . $field9 . "','" . $field10 . "','" . $field11 . "','" . htmlspecialchars($field12, ENT_QUOTES) . "','" . $field13 . "','" . $field14 . "','" . $field15 . "','" . $field16 . "','" . $field17 . "','" . $field18 . "','" . $field19 . "','" . $field20 . "','" . $field21 . "','" . $field22 . "','" . $field23 . "','" . htmlspecialchars($field24, ENT_QUOTES) . "','Commercial','" . $field25 . "','" . $field26 . "','" . $field27 . "','Pending','" . $field28 . "','" . $field29 . "','" . $field30 . "')");

  return $res;
}

function insertLeadContact($table, $field1, $field2, $field3, $field4, $field5)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $res =  db_query("INSERT INTO $table(`lead_id`, `eu_name`,`eu_email`,`eu_mobile`, `eu_designation`) VALUES ('" . $field1 . "' ,'" . $field2 . "' ,'" . $field3 . "' ,'" . $field4 . "','" . $field5 . "')");

  return $res;
}

function insertGradeStudents($table, $field1, $field2,$field3)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $res =  db_query("INSERT INTO $table(`lead_id`, `grade`,`students`) VALUES ('" . $field1 . "','" . $field2 . "','" . $field3 . "')");

  return $res;
}

function insertLeadInfo($table, $field1, $field2, $field3, $field4, $field5)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $res =  db_query("INSERT INTO $table(`lead_id`, `product_id`,`product_type_id`,`existing_IT`, `app_usage`,`created_at`) VALUES ('" . $field1 . "' ,'" . $field2 . "' ,'" . $field3 . "' ,'" . $field4 . "','" . $field5 . "','" . now() . "')");

  return $res;
}

function insertLeadData($table, $field4, $field5, $field6, $field7, $field8, $field9, $field10, $field11, $field12, $field13, $field14, $field15, $field16, $field17, $field18, $field19, $field20, $field21, $field22, $field23, $field24, $field25, $field26, $field27, $field28, $field29, $field30, $field31, $field32, $field33, $field34, $field35, $field36, $field37, $field38, $field39, $field40,$field41)
{

  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("INSERT INTO `$table`(`r_name`, `r_email`, `r_user`,`source`, `lead_type`, `company_name`, `parent_company`, `landline`,region, `industry`,sub_industry, `address`, `pincode`, `state`, `city`, `country`, `eu_name`, `eu_email`, `eu_landline`, `department`, `eu_mobile`, `eu_designation`, `eu_role`, `visit_remarks`,`account_visited`,`confirmation_from`, `license_type`, `quantity`, `created_by`, `team_id`, `status`,user_attachement,os,version,runrate_key,partner_close_date,campaign_type,association_name,validation_type,data_ref) VALUES ('" . $field37 . "','" . $field38 . "','" . $field39 . "','" . $field4 . "','" . $field5 . "','" . htmlspecialchars($field6, ENT_QUOTES) . "','" . htmlspecialchars($field7, ENT_QUOTES) . "','" . $field8 . "','" . $field9 . "','" . $field10 . "','" . $field11 . "','" . htmlspecialchars($field12, ENT_QUOTES) . "','" . $field13 . "','" . $field14 . "','" . $field15 . "','" . $field16 . "','" . $field17 . "','" . $field18 . "','" . $field19 . "','" . $field20 . "','" . $field21 . "','" . $field22 . "','" . $field23 . "','" . htmlspecialchars($field24, ENT_QUOTES) . "','" . $field31 . "','" . $field32 . "','" . $field33 . "','" . $field25 . "','" . $field26 . "','" . $field27 . "','Pending','" . $field28 . "','" . $field34 . "','" . $field35 . "','" . $field36 . "','" . $field29 . "','" . $field30 . "','" . $field40 . "','" . $field41 . "',1)");

  return $res;
}

function leadContactData($table, $field1)
{

  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query = db_query("SELECT * FROM $table  where lead_id =" . $field1);

  return $query;
}

function leadViewData($table, $field1, $field2)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query = db_query("select o.*,tp.*,p.product_name,tpp.product_type,tpp.id as type_id from $table as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where 1 " . $field1 . " and o.id=" . $field2);

  return $query;
}

function copyLeadNew($table, $field)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query = db_query("select o.*,c.name as campaign,tp.*,p.product_name,tpp.product_type from $table as o left join campaign as c on o.campaign_type = c.id left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where o.id=" . addslashes($field));

  return $query;
}

function copyRawNew($table, $field)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $query = db_query("select r.*,tp.product_name,tpp.* from $table as r left join tbl_product as tp on r.product_id=tp.id left join tbl_product_pivot as tpp on r.product_type_id=tpp.id where r.id=" . addslashes($field));

  return $query;
}

function updateLeadUndervalidation($table, $field1, $field2, $field3, $field4, $field5, $field6, $field7, $field8, $field9, $field10, $field11, $field12, $field13, $field14, $field15, $field16, $field17, $field18, $field19, $field20, $field21, $field22, $field23, $field24, $field25, $field26, $field27, $field28, $field29, $field30)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("update  $table set `source`='" . $field1 . "', `company_name`='" . $field2 . "', `parent_company`='" . $field3 . "', `landline`='" . $field4 . "',`region`='" . $field5 . "', `industry`='" . $field6 . "',`sub_industry`='" . $field7 . "', `address`='" . htmlspecialchars($field8, ENT_QUOTES) . "', `pincode`='" . $field9 . "', `state`='" . $field10 . "', `city`='" . $field10 . "', `country`='" . $field11 . "', `eu_name`='" . $field12 . "', `eu_email`='" . $field13 . "', `eu_landline`='" . $field14 . "', `department`='" . $field15 . "', `eu_mobile`='" . $field16 . "', `eu_designation`='" . $field17 . "', `eu_role`='" . $field18 . "', `account_visited`='" . $field19 . "', `visit_remarks`='" . htmlspecialchars($field20, ENT_QUOTES) . "', `confirmation_from`='" . $field21 . "', `license_type`='" . $field22 . "', `quantity`='" . $field23 . "',user_attachement='" . $field24 . "',os='" . $field25 . "',version='" . $field26 . "',runrate_key='" . $field27 . "',partner_close_date='" . $field28 . "',status='Pending',created_date=now(),campaign_type='" . $field29 . "' where id=" . $field30);
}

function updateLead($table, $field1, $field2, $field3, $field4, $field5, $field6, $field7, $field8, $field9, $field10, $field11, $field12, $field13, $field14, $field15, $field16, $field17, $field18, $field19, $field20, $field21, $field22, $field23, $field24, $field25, $field26, $field27, $field28, $field29, $field30)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("update  $table set `source`='" . $field1 . "', `company_name`='" . $field2 . "', `parent_company`='" . $field3 . "', `landline`='" . $field4 . "',`region`='" . $field5 . "', `industry`='" . $field6 . "',`sub_industry`='" . $field7 . "', `address`='" . htmlspecialchars($field8, ENT_QUOTES) . "', `pincode`='" . $field9 . "', `state`='" . $field10 . "', `city`='" . $field10 . "', `country`='" . $field11 . "', `eu_name`='" . $field12 . "', `eu_email`='" . $field13 . "', `eu_landline`='" . $field14 . "', `department`='" . $field15 . "', `eu_mobile`='" . $field16 . "', `eu_designation`='" . $field17 . "', `eu_role`='" . $field18 . "', `account_visited`='" . $field19 . "', `visit_remarks`='" . htmlspecialchars($field20, ENT_QUOTES) . "', `confirmation_from`='" . $field21 . "', `license_type`='" . $field22 . "', `quantity`='" . $field23 . "',user_attachement='" . $field24 . "',os='" . $field25 . "',version='" . $field26 . "',runrate_key='" . $field27 . "',partner_close_date='" . $field28 . "',campaign_type='" . $field29 . "' where id=" . $field30);
}

//edit order functions
function selectEditDetails($field1, $field2, $field3 = NULL)
{

  if ($field3 != '') {
    $condition = " and o.team_id=" . $field2;
  } else {
    $condition = " ";
  }
  $query = db_query("select o.* from orders as o where o.id=" . $field1 . $condition);

  return $query;
}

function fetchMstOrder($field1, $field2, $field3 = NULL)
{

  if ($field3 != '') {
    $condition = " and o.team_id=" . $field2;
  } else {
    $condition = " ";
  }
  $query = db_query("select o.* from orders as o where o.id=" . $field1 . $condition);

  return $query;
}

/* 
functions for KRA BO
*/
function LCcalling_profiling_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log on activity_log.pid=o.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.lead_type='LC' and o.team_id='" . $team_id . "' and o.validation_type='profiling_validation' and activity_log.call_subject='Profiling Call' and date(activity_log.created_date)>='" . $date_from . "' and o.created_by in (" . $created_by . ") and date(activity_log.created_date)<='" . $date_to . "' and o.status='Approved'");

  return $query;
}

// function newDR_iss_KRA_BO($team_id, $date_from, $date_to, $created_by)
// {
//   $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.team_id='" . $team_id . "' and o.allign_to in (" . $created_by . ") and date(o.approval_time)>='" . $date_from . "' and date(o.approval_time)<='" . $date_to . "' and o.status='Approved' and iss=1 and is_iss_lead=0");
//   return $query;
// }

function logCall_lead_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where tp.product_type_id in (1,2) and orders.team_id='" . $team_id . "' and orders.license_type='Commercial' and orders.dvr_flag=0 and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.added_by in (" . $created_by . ")  and activity_log.call_subject like '%visit%' and activity_log.is_intern=0");

  return $query;
}

function logCall_lapsed_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where tp.product_type_id in (1,2) and l.team_id='" . $team_id . "' and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.added_by in (" . $created_by . ") and activity_log.call_subject like '%visit%' and activity_log.is_intern=0");
  return $query;
}

function logCall_raw_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.added_by in (" . $created_by . ") and activity_log.call_subject like '%visit%' and activity_log.activity_type='Raw' and activity_log.is_intern=0");
  return $query;
}

function dvr_BO_KRA($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.dvr_flag=1 and o.is_dr=1 and o.license_type='Commercial' and date(o.convert_date) IS NULL and o.created_by in (" . $created_by . ") and date(o.created_date)>='" . $date_from . "' and date(o.created_date)<='" . $date_to . "' and o.team_id='" . $team_id . "'");
  return $query;
}
function convertedDRV_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.is_dr=1 and o.license_type='Commercial' and o.created_by in (" . $created_by . ") and date(o.convert_date)>='" . $date_from . "' and date(o.convert_date)<='" . $date_to . "' and o.team_id='" . $team_id . "' and o.dvr_by!=0");
  return $query;
}

function LCcalling_emailer_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log on activity_log.pid=o.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.lead_type='LC' and o.team_id='" . $team_id . "' and o.validation_type='emailer_validation' and activity_log.call_subject='Profiling Call' and date(activity_log.created_date)>='" . $date_from . "' and o.created_by in (" . $created_by . ") and date(activity_log.created_date)<='" . $date_to . "' and o.status='Approved'");
  //getSingleresult("select count(distinct(id)) from raw_leads where (product_type_id=1 or product_type_id=2) and team_id='" . $team_id . "' and created_by in (" . $created_by . ") and date(created_date)>='" . $date_from . "' and date(created_date)<='" . $date_to . "'");
  return $query;
}
function logISS_lead_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.added_by in (" . $created_by . ") and activity_log.call_subject not like '%visit%' and activity_log.is_intern=0");

  return $query;
}
function logISS_lapsed_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.added_by in (" . $created_by . ") and activity_log.call_subject not like '%visit%' and activity_log.is_intern=0");

  return $query;
}

function totalLog_lead_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where tp.product_type_id in (1,2) and orders.team_id='" . $team_id . "' and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.added_by in (" . $created_by . ") and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call') and orders.id!='' and activity_log.activity_type='Lead' and activity_log.is_intern=0 and activity_log.call_subject!=''");

  return $query;
}
function totalLog_lapsed_BO($team_id, $date_from, $date_to, $created_by)
{
   $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where tp.product_type_id in (1,2) and l.team_id='" . $team_id . "' and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.added_by in (" . $created_by . ") and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call') and l.id!='' and activity_log.activity_type='Lead' and activity_log.is_intern=0 and activity_log.call_subject!=''");

  return $query;
}
function totalLog_raw_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where o.product_type_id in (1,2) and o.team_id='" . $team_id . "' and  activity_log.added_by in (" . $created_by . ") and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call') and activity_log.activity_type='Raw' and o.id!='' and activity_log.is_intern=0 and activity_log.call_subject!=''");

  //$query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM activity_log left join raw_leads as o on activity_log.pid=o.id where o.product_type_id in (1,2) and o.team_id='" . $team_id . "' and  activity_log.added_by in (" . $created_by . ") and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject in ('Fresh Call','Follow-up Call') and activity_log.activity_type='Raw' and o.id!='' and activity_log.is_intern=0");
  return $query;
}


function sales_target_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("select IFNULL(sum(o.quantity),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.team_id='" . $team_id . "' and o.created_by in (" . $created_by . ") and date(o.partner_close_date)>='" . $date_from . "' and date(o.partner_close_date)<='" . $date_to . "' and o.stage='OEM Billing'");
  return $query;
}
function iss_sales_target_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("select IFNULL(sum(o.quantity),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.team_id='" . $team_id . "' and o.allign_to in (" . $created_by . ") and date(o.partner_close_date)>='" . $date_from . "' and date(o.partner_close_date)<='" . $date_to . "' and o.stage='OEM Billing'");
  return $query;
}
/* 
functions for KRA SALES
*/
function sales_target($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("select IFNULL(sum(o.quantity),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.team_id='" . $team_id . "' and o.created_by='" . $created_by . "' and date(o.partner_close_date)>='" . $date_from . "' and date(o.partner_close_date)<='" . $date_to . "' and o.stage='OEM Billing'");
  return $query;
}

function iss_sales_target($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("select IFNULL(sum(o.quantity),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.team_id='" . $team_id . "' and o.allign_to='" . $created_by . "' and date(o.partner_close_date)>='" . $date_from . "' and date(o.partner_close_date)<='" . $date_to . "' and o.stage='OEM Billing'");
  return $query;
}

function logCall_lead_SALES($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and orders.license_type='Commercial' and orders.dvr_flag=0 and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.is_intern=0 and activity_log.activity_type='Lead' and orders.id!=''");

  return $query;
}

function logCall_lapsed_SALES($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and activity_log.added_by='" . $created_by . "' and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.is_intern=0 and activity_log.activity_type='Lead' and l.id!=''");
  return $query;
}

function logCall_raw_SALES($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and activity_log.added_by='" . $created_by . "' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.activity_type='Raw' and activity_log.is_intern=0 and o.id!=''");
  return $query;
}

function dvr_SALES_KRA($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.dvr_flag=1 and o.is_dr=1 and date(o.convert_date) IS NULL and o.license_type='Commercial' and date(o.created_date)>='" . $date_from . "' and date(o.created_date)<='" . $date_to . "' and o.team_id='" . $team_id . "' and  o.created_by='" . $created_by . "'");
  return $query;
}
function convertedDRV_SALES($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.is_dr=1 and o.license_type='Commercial' and date(o.convert_date)>='" . $date_from . "' and date(o.convert_date)<='" . $date_to . "' and o.team_id='" . $team_id . "' and  o.created_by='" . $created_by . "' and o.dvr_by!=0");
  return $query;
}

function logDVR_raw_SALES($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.activity_type='Raw' and activity_log.is_intern=0 and o.id!=''");
  return $query;
}
function logDVR_lead_SALES($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where  tp.product_type_id in (1,2) and orders.team_id='" . $team_id . "' and activity_log.added_by='" . $created_by . "' and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.is_intern=0 and activity_log.activity_type='Lead' and orders.id!=''");

  return $query;
}

function logDVR_lapsed_SALES($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and activity_log.added_by='" . $created_by . "' and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.is_intern=0 and activity_log.activity_type='Lead' and l.id!=''");
  return $query;
}

function LCcalling_profiling_SALES($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log on activity_log.pid=o.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.status='Approved' and o.lead_type='LC' and o.team_id='" . $team_id . "' and o.validation_type='profiling_validation' and activity_log.call_subject='Profiling Call' and date(activity_log.created_date)>='" . $date_from . "' and o.created_by='" . $created_by . "' and date(activity_log.created_date)<='" . $date_to . "'");

  // getSingleresult("select count(distinct(id)) from raw_leads where (product_type_id=1 or product_type_id=2) and team_id='" . $team_id . "' and created_by='" . $created_by . "' and date(created_date)>='" . $date_from . "' and date(created_date)<='" . $date_to . "'");
  return $query;
}

function LCcalling_emailer_admin($team_id, $created_by, $date_from, $date_to)
{
  
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log on activity_log.pid=o.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.lead_type='LC' and o.team_id='" . $team_id . "' and o.validation_type='emailer_validation' and activity_log.call_subject='Profiling Call' and date(activity_log.created_date)>='" . $date_from . "' and o.created_by='" . $created_by . "' and date(activity_log.created_date)<='" . $date_to . "' and o.status='Approved'");

  return $query;

}
/* 
functions for KRA TC
*/

function LCcalling_emailer($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log on activity_log.pid=o.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.lead_type='LC' and o.team_id='" . $team_id . "' and o.validation_type='emailer_validation' and activity_log.call_subject='Profiling Call' and date(activity_log.created_date)>='" . $date_from . "' and o.created_by='" . $created_by . "' and date(activity_log.created_date)<='" . $date_to . "' and o.status='Approved'");

  // getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.team_id='" . $team_id . "' and o.created_by='" . $created_by . "' and date(o.approval_time)>='" . $date_from . "' and date(o.approval_time)<='" . $date_to . "' and o.status='Approved'");
  return $query;
}

function LCcalling_profiling_TCadmin($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log on activity_log.pid=o.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.lead_type='LC' and o.team_id='" . $team_id . "' and o.validation_type='profiling_validation' and activity_log.call_subject='Profiling Call' and date(activity_log.created_date)>='" . $date_from . "' and o.created_by='" . $created_by . "' and date(activity_log.created_date)<='" . $date_to . "' and o.status='Approved'");

  return $query;
}

// function iss_approved($team_id, $created_by, $date_from, $date_to)
// {
//   $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.team_id='" . $team_id . "' and o.allign_to='" . $created_by . "' and date(o.approval_time)>='" . $date_from . "' and date(o.approval_time)<='" . $date_to . "' and o.status='Approved' and iss=1 and is_iss_lead=0");
//   return $query;
// }

function logCall_lead_TC($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call') and activity_log.is_intern=0");

  return $query;
}

function logCall_lapsed_TC($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call') and activity_log.is_intern=0");
  return $query;
}

function logCall_raw_TC($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call') and activity_log.activity_type='Raw' and activity_log.is_intern=0");
  return $query;
}

/**
 * function for New account call per day 15 by each team (Sales + ISS)
 */

function freshCall_lead($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and dvr_flag=0 and activity_log.is_intern=0");

  return $query;
}

function freshCall_DVR($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and is_dr=1 and dvr_flag=1 and activity_log.is_intern=0");

  return $query;
}

function freshCall_lapsed($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and activity_log.is_intern=0");
  return $query;
}

function freshCall_raw($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject = 'Fresh Call' and activity_log.activity_type='Raw' and activity_log.is_intern=0");
  return $query;
}

/**
 * function for New account call per day 15 by each team (Sales + ISS) BO
 */

function freshCall_lead_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by in (" . $created_by . ") and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and dvr_flag=0 and orders.id!='' and activity_log.activity_type='Lead' and activity_log.is_intern=0");

  return $query;
}

function freshCall_DVR_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by in (" . $created_by . ") and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and is_dr=1 and dvr_flag=1 and orders.id!='' and activity_log.activity_type='Lead' and activity_log.is_intern=0");

  return $query;
}

function freshCall_lapsed_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and  activity_log.added_by in (" . $created_by . ") and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and l.id!='' and activity_log.activity_type='Lead' and activity_log.is_intern=0");
  return $query;
}

function freshCall_raw_BO($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and  activity_log.added_by in (" . $created_by . ") and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject = 'Fresh Call' and activity_log.activity_type='Raw' and o.id!='' and activity_log.is_intern=0");
  return $query;
}

function logDVR_raw_BO($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM activity_log left join raw_leads as o on activity_log.pid=o.id where o.product_type_id in (1,2) and o.team_id='" . $team_id . "' and activity_log.added_by in (" . $created_by . ") and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.activity_type='Raw' and o.id!='' and activity_log.is_intern=0");
  return $query;
}

function logDVR_lead_BO($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where tp.product_type_id in (1,2) and orders.team_id='" . $team_id . "' and activity_log.added_by in (" . $created_by . ") and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.activity_type='Lead' and orders.id!='' and activity_log.is_intern=0");

  return $query;
}

function logDVR_lapsed_BO($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where tp.product_type_id in (1,2) and l.team_id='" . $team_id . "' and activity_log.added_by in (" . $created_by . ") and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.activity_type='Lead' and l.id!='' and activity_log.is_intern=0");

  //$query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where tp.product_type_id in (1,2) and l.team_id='" . $team_id . "' and activity_log.added_by in (" . $created_by . ") and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.is_intern=0");
  return $query;
}

/* 
functions for KRA BO Dashboard
*/
function LCcalling_profiling_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log on activity_log.pid=o.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.lead_type='LC' and o.team_id='" . $team_id . "' and o.validation_type='profiling_validation' and activity_log.call_subject='Profiling Call' and MONTH(activity_log.created_date)>='" . $date_from . "' and o.created_by in (" . $created_by . ") and YEAR(activity_log.created_date)<='" . $date_to . "' and o.status='Approved'");

  // getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.license_type='Commercial' and o.status='Approved' and o.team_id='" . $team_id . "' and MONTH(o.approval_time)=" . $date_from . " and o.created_by in (" . $created_by . ") and YEAR(o.approval_time)=" . $date_to . " ");

  return $query;
}

// function newDR_iss_KRA_BO_dash($team_id, $date_from, $date_to, $created_by)
// {
//   $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.team_id='" . $team_id . "' and o.allign_to in (" . $created_by . ") and MONTH(o.approval_time)=" . $date_from . " and YEAR(o.approval_time)=" . $date_to . " and o.status='Approved' and iss=1 and is_iss_lead=0");
//   return $query;
// }

function logCall_lead_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where tp.product_type_id in (1,2) and orders.team_id='" . $team_id . "' and orders.license_type='Commercial' and orders.dvr_flag=0 and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.added_by in (" . $created_by . ")  and activity_log.call_subject like '%visit%' and activity_log.is_intern=0");
  return $query;
}

function logCall_lapsed_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and l.license_type='Commercial' and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.added_by in (" . $created_by . ") and activity_log.call_subject like '%visit%' and activity_log.is_intern=0");
  return $query;
}

function logCall_raw_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.added_by in (" . $created_by . ") and activity_log.call_subject like '%visit%' and activity_log.activity_type='Raw' and activity_log.is_intern=0");
  return $query;
}

function dvr_BO_KRA_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.dvr_flag=1 and o.is_dr=1 and o.license_type='Commercial' and date(o.convert_date) IS NULL and o.created_by in (" . $created_by . ") and MONTH(o.created_date)=" . $date_from . " and YEAR(o.created_date)=" . $date_to . " and o.team_id='" . $team_id . "'");
  return $query;
}

function convertedDRV_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.is_dr=1 and o.license_type='Commercial' and o.created_by in (" . $created_by . ") and MONTH(o.convert_date)=" . $date_from . " and YEAR(o.convert_date)=" . $date_to . " and o.team_id='" . $team_id . "' and o.dvr_by!=0");
  return $query;
}

function LCcalling_emailer_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log on activity_log.pid=o.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.lead_type='LC' and o.team_id='" . $team_id . "' and o.validation_type='emailer_validation' and activity_log.call_subject='Profiling Call' and MONTH(activity_log.created_date)>='" . $date_from . "' and o.created_by in (" . $created_by . ") and YEAR(activity_log.created_date)<='" . $date_to . "'  and o.status='Approved'");
  return $query;
}

function logISS_lead_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and orders.license_type='Commercial' and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.added_by in (" . $created_by . ") and activity_log.call_subject not like '%visit%' and activity_log.is_intern=0");

  return $query;
}

function logISS_lapsed_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and l.license_type='Commercial' and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.added_by in (" . $created_by . ") and activity_log.call_subject not like '%visit%' and activity_log.is_intern=0");

  return $query;
}

function totalLog_lead_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and orders.license_type='Commercial' and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.added_by in (" . $created_by . ") and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call') and orders.id!='' and activity_log.activity_type='Lead' and activity_log.is_intern=0 and activity_log.call_subject!=''");

  return $query;
}
function totalLog_lapsed_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and l.license_type='Commercial' and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.added_by in (" . $created_by . ") and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call') and l.id!='' and activity_log.activity_type='Lead' and activity_log.is_intern=0 and activity_log.call_subject!=''");

  return $query;
}
function totalLog_raw_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and  activity_log.added_by in (" . $created_by . ") and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call') and activity_log.activity_type='Raw' and o.id!='' and activity_log.is_intern=0 and activity_log.call_subject!=''");
  return $query;
}
// function logISS_raw_BO_dash($team_id, $date_from, $date_to, $created_by)
// {
//   $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.added_by in (" . $created_by . ") and activity_log.call_subject not like '%visit%' and activity_log.activity_type='Raw'");

//   return $query;
// }
function sales_target_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("select IFNULL(sum(o.quantity),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.team_id='" . $team_id . "' and o.created_by in (" . $created_by . ") and MONTH(o.partner_close_date)='" . $date_from . "' and YEAR(o.partner_close_date)='" . $date_to . "' and o.stage='OEM Billing'");
  return $query;
}
function iss_sales_target_BOdash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("select IFNULL(sum(o.quantity),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.team_id='" . $team_id . "' and o.allign_to in (" . $created_by . ") and MONTH(o.partner_close_date)=" . $date_from . " and YEAR(o.partner_close_date)=" . $date_to . " and o.stage='OEM Billing'");
  return $query;
}
function freshCall_lead_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by in (" . $created_by . ") and orders.license_type='Commercial' and MONTH(activity_log.created_date)='" . $date_from . "' and YEAR(activity_log.created_date)='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and dvr_flag=0 and orders.id!='' and activity_log.activity_type='Lead' and activity_log.is_intern=0");

  return $query;
}

function freshCall_DVR_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by in (" . $created_by . ") and orders.license_type='Commercial' and MONTH(activity_log.created_date)='" . $date_from . "' and YEAR(activity_log.created_date)='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and is_dr=1 and dvr_flag=1 and orders.id!='' and activity_log.activity_type='Lead' and activity_log.is_intern=0");

  return $query;
}

function freshCall_lapsed_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and  activity_log.added_by in (" . $created_by . ") and l.license_type='Commercial' and MONTH(activity_log.created_date)='" . $date_from . "' and YEAR(activity_log.created_date)='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and l.id!='' and activity_log.activity_type='Lead' and activity_log.is_intern=0");
  return $query;
}

function freshCall_raw_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and  activity_log.added_by in (" . $created_by . ") and MONTH(activity_log.created_date)='" . $date_from . "' and YEAR(activity_log.created_date)='" . $date_to . "'  and activity_log.call_subject = 'Fresh Call' and activity_log.activity_type='Raw' and o.id!='' and activity_log.is_intern=0");
  return $query;
}

function logDVR_raw_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and  activity_log.added_by in (" . $created_by . ") and MONTH(activity_log.created_date)='" . $date_from . "' and YEAR(activity_log.created_date)='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.activity_type='Raw' and o.id!='' and activity_log.is_intern=0");
  return $query;
}
function logDVR_lead_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and activity_log.added_by in (" . $created_by . ") and orders.license_type='Commercial' and MONTH(activity_log.created_date)='" . $date_from . "' and YEAR(activity_log.created_date)='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.activity_type='Lead' and orders.id!='' and activity_log.is_intern=0");

  return $query;
}

function logDVR_lapsed_BO_dash($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and activity_log.added_by in (" . $created_by . ") and l.license_type='Commercial' and MONTH(activity_log.created_date)='" . $date_from . "' and YEAR(activity_log.created_date)='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.activity_type='Lead' and l.id!='' and activity_log.is_intern=0");
  return $query;
}

/* 
functions for KRA SALES Dashboard
*/

function sales_target_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("select IFNULL(sum(o.quantity),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.team_id='" . $team_id . "' and o.created_by='" . $created_by . "' and MONTH(o.partner_close_date)=" . $date_from . " and YEAR(o.partner_close_date)=" . $date_to . " and o.stage='OEM Billing'");
  return $query;
}

function iss_sales_target_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("select IFNULL(sum(o.quantity),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.team_id='" . $team_id . "' and o.allign_to='" . $created_by . "' and MONTH(o.partner_close_date)=" . $date_from . " and YEAR(o.partner_close_date)=" . $date_to . " and o.stage='OEM Billing'");
  return $query;
}

function logCall_lead_SALES_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where tp.product_type_id in (1,2) and orders.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and orders.license_type='Commercial' and orders.dvr_flag=0 and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . "  and activity_log.call_subject like '%visit%' and activity_log.is_intern=0");

  return $query;
}

function logCall_lapsed_SALES_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where tp.product_type_id in (1,2) and l.team_id='" . $team_id . "' and activity_log.added_by='" . $created_by . "' and l.license_type='Commercial' and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.call_subject like '%visit%' and activity_log.is_intern=0");
  return $query;
}

function logCall_raw_SALES_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and activity_log.added_by='" . $created_by . "' and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.call_subject like '%visit%' and activity_log.is_intern=0 and activity_log.activity_type='Raw'");
  return $query;
}

function dvr_SALES_KRA_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.dvr_flag=1 and o.is_dr=1 and date(o.convert_date) IS NULL and o.license_type='Commercial' and MONTH(o.created_date)=" . $date_from . " and YEAR(o.created_date)=" . $date_to . " and o.team_id='" . $team_id . "' and  o.created_by='" . $created_by . "'");
  return $query;
}
function convertedDRV_SALES_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.is_dr=1 and o.license_type='Commercial' and MONTH(o.convert_date)=" . $date_from . " and YEAR(o.convert_date)=" . $date_to . " and o.team_id='" . $team_id . "' and  o.created_by='" . $created_by . "' and o.dvr_by!=0");
  return $query;
}

function logDVR_raw_SALES_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.call_subject like '%visit%' and activity_log.is_intern=0 ");
  return $query;
}
function logDVR_lead_SALES_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and activity_log.added_by='" . $created_by . "' and orders.license_type='Commercial' and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.call_subject like '%visit%' and activity_log.is_intern=0");

  return $query;
}

function logDVR_lapsed_SALES_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and activity_log.added_by='" . $created_by . "' and l.license_type='Commercial' and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.call_subject like '%visit%' and activity_log.is_intern=0 ");
  return $query;
}

function LCcalling_profiling_SALES_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log on activity_log.pid=o.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.lead_type='LC' and o.team_id='" . $team_id . "' and o.validation_type='profiling_validation' and activity_log.call_subject='Profiling Call' and MONTH(activity_log.created_date)>='" . $date_from . "' and o.created_by='" . $created_by . "' and YEAR(activity_log.created_date)<='" . $date_to . "' and o.status='Approved'");

  // $query = getSingleresult("select count(distinct(id)) from raw_leads where (product_type_id=1 or product_type_id=2) and team_id='" . $team_id . "' and created_by='" . $created_by . "' and MONTH(created_date)=" . $date_from . " and YEAR(created_date)=" . $date_to . " ");
   return $query;
}

function freshCall_lead_SALES_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and orders.license_type='Commercial' and MONTH(activity_log.created_date)='" . $date_from . "' and YEAR(activity_log.created_date)='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and dvr_flag=0");

  return $query;
}

function freshCall_DVR_SALES_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and orders.license_type='Commercial' and MONTH(activity_log.created_date)='" . $date_from . "' and YEAR(activity_log.created_date)='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and is_dr=1 and dvr_flag=1");

  return $query;
}

function freshCall_lapsed_SALES_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and l.license_type='Commercial' and MONTH(activity_log.created_date)='" . $date_from . "' and YEAR(activity_log.created_date)='" . $date_to . "' and activity_log.call_subject = 'Fresh Call'");
  return $query;
}

function freshCall_raw_SALES_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and MONTH(activity_log.created_date)='" . $date_from . "' and YEAR(activity_log.created_date)='" . $date_to . "'  and activity_log.call_subject = 'Fresh Call' and activity_log.activity_type='Raw'");
  return $query;
}

/* 
functions for KRA TC Dashboard
*/

function LCcalling_emailer_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log on activity_log.pid=o.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.lead_type='LC' and o.team_id='" . $team_id . "' and o.validation_type='emailer_validation' and activity_log.call_subject='Profiling Call' and MONTH(activity_log.created_date)>='" . $date_from . "' and o.created_by='" . $created_by . "' and YEAR(activity_log.created_date)<='" . $date_to . "' and o.status='Approved'");

  // $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.team_id='" . $team_id . "' and o.created_by='" . $created_by . "' and MONTH(o.approval_time)=" . $date_from . " and YEAR(o.approval_time)=" . $date_to . " and o.status='Approved'");
   return $query;
}


// function iss_approved_dash($team_id, $created_by, $date_from, $date_to)
// {
//   $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.team_id='" . $team_id . "' and o.allign_to='" . $created_by . "' and MONTH(o.approval_time)=" . $date_from . " and YEAR(o.approval_time)=" . $date_to . " and o.status='Approved' and iss=1 and is_iss_lead=0");
//   return $query;
// }

function logCall_lead_TC_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and orders.license_type='Commercial' and MONTH(activity_log.created_date)='" . $date_from . "' and YEAR(activity_log.created_date)='" . $date_to . "' and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call')");

  return $query;
}

function logCall_lapsed_TC_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and l.license_type='Commercial' and MONTH(activity_log.created_date)='" . $date_from . "' and YEAR(activity_log.created_date)='" . $date_to . "' and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call')");

  return $query;
}

function logCall_raw_TC_dash($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and  activity_log.added_by='" . $created_by . "' and MONTH(activity_log.created_date)=" . $date_from . " and YEAR(activity_log.created_date)=" . $date_to . " and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call')");

  return $query;
}

//View DVR page
function DVRselect_query($field)
{

  $query = db_query("select o.*,tp.*,p.product_name,tpp.product_type,tpp.id as type_id from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where 1 and o.id=" . $field);
  return $query;
}

//manage kra query

function manageKra($field)
{
  $query = db_query("select id,name,user_type,role from users where team_id='" . $field . "' and role not in ('BO','AE') and status='Active' order by role");
  return $query;
}

function userTarget_Kra($field1, $field2, $field3, $month, $year)
{
  $query = getSingleresult("select kra from user_kra where kra_name='" . $field1 . "' and user_id='" . $field2 . "' and team_id='" . $field3 . "' and month='" . date('n', strtotime($month)) . "' and year='" . date('Y', strtotime($year)) . "'");
  return $query;
}

function deficitTarget_Kra($field1, $field2, $field3, $month, $year)
{
  $query = getSingleresult("select kra from user_kra where kra_name='" . $field1 . "' and user_id='" . $field2 . "' and   team_id='" . $field3 . "' and month='" . date('n', strtotime($month)) . "' and year='" . date('Y', strtotime($year)) . "'");
  return $query;
}
function userTarget_KraDash($field1, $field2, $field3, $month, $year)
{
  $query = getSingleresult("select kra from user_kra where kra_name='" . $field1 . "' and user_id='" . $field2 . "' and team_id='" . $field3 . "' and month='" . $month . "' and year='" . $year . "'");
  return $query;
}

function deleteKRA($field, $field1, $field2, $field3)
{
  $delete_query = db_query("delete from user_kra where kra_name=" . $field . " and team_id =" . $field3 . " and month=" . $field1 . " and year=" . $field2);

  return $delete_query;
}
/**KRA Mail queries */

function selectKRA($field1, $field2, $field3)
{
  $query = db_query("select * from user_kra where kra_name='" . $field1 . "' and user_id='" . $field2 . "' and team_id=" . $field3);

  return $query;
}
function kraMailData($field1, $field2)
{
  $query = db_query("select * from user_kra where kra_name='" . $field1 . "' and team_id=" . $field2);

  return $query;
}

function kraPopUp($field1, $field2, $date)
{
  $query = db_query("select * from user_kra where kra_name='" . $field1 . "' and team_id=" . $field2 . " and month='" . date('n', strtotime($date)) . "' and year = '" . date('Y', strtotime($date)) . "'");

  return $query;
}

// review leads
function partnerReviewLeads($field1, $field2)
{
  $query = "select orders.r_name,orders.r_user,orders.lead_type,orders.quantity,orders.company_name,orders.eu_email,orders.id,orders.eu_mobile,orders.team_id,orders.stage,lead_review.is_review,lead_review.added_date,orders.created_date from lead_review join orders on orders.id=lead_review.lead_id where 1=1  and orders.license_type='Commercial' and orders.team_id='" . $field1 . "' " . $field2 . " GROUP BY orders.id";

  return $query;
}

function partnerReviewLeadsSearch($field1)
{

  $query = "select orders.r_name,orders.r_user,orders.lead_type,orders.quantity,orders.company_name,orders.eu_email,orders.id,orders.eu_mobile,orders.team_id,orders.stage,lead_review.is_review,lead_review.added_date,orders.created_date from lead_review join orders on orders.id=lead_review.lead_id where 1=1  and orders.license_type='Commercial' and orders.team_id='" . $field1 . "'";

  return $query;
}
// renewal leads functions 

function singleLeadViewData($table, $field1)
{

  $query = db_query("select o.*,tp.*,p.product_name,tpp.product_type,tpp.id as type_id from " . $table . " as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where 1=1 and o.id=" . $field1 . $condition);

  return $query;
}

function renewalLeadViewData($table, $field1, $field3 = NULL)
{
  if ($field3 != '') {
    $condition = " and o.team_id=" . $field3;
  } else {
    $condition = " ";
  }
  $query = db_query("select o.*,tp.*,p.product_name,tpp.product_type,tpp.id as type_id from " . $table . " as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where 1=1 and o.id=" . $field1 . $condition);

  return $query;
}

function partnerRenewalLeads($field1, $field2)
{
  $query = "select o.*,tp.*,p.product_name,tpp.product_type,tpp.id as type_id FROM orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where 1=1  and o.license_type='Renewal' and o.team_id='" . $field1 . "' " . $field2;

  return $query;
}

function partnerRenewalLeadsSearch($field1)
{
  $query = "select o.*,tp.*,p.product_name,tpp.product_type,tpp.id as type_id FROM orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where 1=1  and o.license_type='Renewal' and o.team_id='" . $field1 . "' ";

  return $query;
}

function assignRenewalQuery($table, $condition)
{
  $query = "select * from " . $table . " where" . $condition;

  return $query;
}

// Education leads function 

function educationLeadQuery($condition)
{
  $query = "select o.*,tp.*,p.product_name,tpp.product_type,tpp.id as type_id FROM orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where 1=1  " . $condition;

  return $query;
}

// review Caller panel functions
function reviewLeadCallerId($user_id)
{
  $cid = getSingleresult("select id from callers where user_id='" . $user_id . "'");
  return $cid;
}

// function callerOrderWithoutRenewalLeads($caller_id,$condition)
// {

//   $query = "select * FROM orders where 1=1 and dvr_flag=0  and (caller='".$caller_id."' or status='For Validation' )". $condition;

//   return $query;
// }

function callerRenewalLeads($caller_id, $condition)
{

  $query = "select * FROM orders where 1 and dvr_flag=0  and  license_type='Renewal'  and caller='" . $caller_id . "'" . $condition;

  return $query;
}

/**
 * association filter query
 */

function searchAssociation($table, $field)
{

  $query =   db_query("SELECT Distinct(association_name) FROM $table WHERE association_name!=' ' and team_id ='" . $field . "' ORDER BY association_name ASC");

  return $query;
}


function searchAssociationAdmin($table)
{

  $query =   db_query("SELECT Distinct(association_name) FROM $table WHERE association_name!=' '  ORDER BY association_name ASC");

  return $query;
}

/**
 * Parallel point System
 */

function userDataParallel($table)
{
  $query = db_query("select up.user_id,u.name as user,p.name as partner,p.id from $table as up left join users as u on up.user_id=u.id left join partners as p on up.stage_id=p.id where p.status='Active' and FIND_IN_SET(4,p.product_id) group by up.user_id order by up.id desc");

  return $query;
}

function approvedDataParallel($field, $d_from, $d_to)
{
  $query = getSingleresult("SELECT IFNULL(sum(u.point),0) from user_points as u left join orders as o on u.lead_id=o.id WHERE u.stage_id=" . $field . " and date(o.approval_time) >='" . $d_from . "' and date(o.approval_time)<='" . $d_to . "' and u.stage_name='Approved' and o.license_type='Commercial'");

  return $query;
}

/**
 * ADmin KRA VAR Tracking
 */

 function LCCalling_KRAadmin($team_id, $date_from, $date_to, $created_by)
 {
    $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log on activity_log.pid=o.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.lead_type='LC' and o.team_id='" . $team_id . "' and o.validation_type='profiling_validation' and activity_log.call_subject='Profiling Call' and date(activity_log.created_date)>='" . $date_from . "' and o.created_by in ('" . $created_by . "') and date(activity_log.created_date)<='" . $date_to . "' and o.status='Approved'");

    return $query;
 }

// function newDR_KRAadmin($team_id, $date_from, $date_to, $created_by)
// {
//   $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.license_type='Commercial' and o.status='Approved' and o.team_id='" . $team_id . "' and date(o.approval_time)>='" . $date_from . "' and o.created_by in ('" . $created_by . "') and date(o.approval_time)<='" . $date_to . "'");

//   return $query;
// }

// function newDR_iss_KRAadmin($team_id, $date_from, $date_to, $created_by)
// {
//   $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.team_id='" . $team_id . "' and o.allign_to in ('" . $created_by . "') and date(o.approval_time)>='" . $date_from . "' and date(o.approval_time)<='" . $date_to . "' and o.status='Approved' and iss=1 and is_iss_lead=0");
//   return $query;
// }

function logCall_leadadmin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.added_by in ('" . $created_by . "')  and activity_log.call_subject in ('BD Visit','Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit')");

  return $query;
}

function logCall_lapsedadmin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.added_by in ('" . $created_by . "') and activity_log.call_subject in ('BD Visit','Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit')");
  return $query;
}

function logCall_rawadmin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.pid)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.added_by in ('" . $created_by . "') and activity_log.call_subject in ('BD Visit','Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit') and activity_log.activity_type='Raw'");
  return $query;
}

function dvr_KRAadmin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.dvr_flag=1 and o.is_dr=1 and o.license_type='Commercial' and date(o.convert_date) IS NULL and o.created_by in ('" . $created_by . "') and date(o.created_date)>='" . $date_from . "' and date(o.created_date)<='" . $date_to . "' and o.team_id='" . $team_id . "'");
  return $query;
}
function convertedDRVadmin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.is_dr=1 and o.license_type='Commercial' and o.created_by in ('" . $created_by . "') and date(o.convert_date)>='" . $date_from . "' and date(o.convert_date)<='" . $date_to . "' and o.team_id='" . $team_id . "'");
  return $query;
}

function LCCallingEmailer_KRAadmin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log on activity_log.pid=o.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.lead_type='LC' and o.team_id='" . $team_id . "' and o.validation_type='emailer_validation' and activity_log.call_subject='Profiling Call' and date(activity_log.created_date)>='" . $date_from . "' and o.created_by in ('" . $created_by . "') and date(activity_log.created_date)<='" . $date_to . "' and o.status='Approved'");
  return $query;
}

// function rawLeadsadmin($team_id, $date_from, $date_to, $created_by)
// {
//   $query = getSingleresult("select count(distinct(id)) from raw_leads where (product_type_id=1 or product_type_id=2) and team_id='" . $team_id . "' and created_by in ('" . $created_by . "') and date(created_date)>='" . $date_from . "' and date(created_date)<='" . $date_to . "'");
//   return $query;
// }

function logISS_rawadmin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.added_by in ('" . $created_by . "') and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call') and activity_log.activity_type='Raw' and o.id!='' and activity_log.is_intern=0");

  return $query;
}
function logISS_leadadmin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by in ('" . $created_by . "') and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call') and activity_log.activity_type='Lead' and orders.id!='' and activity_log.is_intern=0");

  return $query;
}

function logISS_lapsedadmin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and  activity_log.added_by in ('" . $created_by . "') and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject in ('Fresh Call','Follow-up Call','Profiling Call') and activity_log.activity_type='Lead' and l.id!='' and activity_log.is_intern=0");
  return $query;
}

function sales_targetadmin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("select IFNULL(sum(o.quantity),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.team_id='" . $team_id . "' and o.created_by in ('" . $created_by . "') and date(o.partner_close_date)>='" . $date_from . "' and date(o.partner_close_date)<='" . $date_to . "' and o.stage='OEM Billing'");
  return $query;
}

function iss_sales_targetAdmin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("select IFNULL(sum(o.quantity),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.team_id='" . $team_id . "' and o.allign_to in ('" . $created_by . "') and date(o.partner_close_date)>='" . $date_from . "' and date(o.partner_close_date)<='" . $date_to . "' and o.stage='OEM Billing'");
  return $query;
}

function visitRaw_SALES($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and  activity_log.added_by in ('" . $created_by . "') and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.activity_type='Raw' and o.id!='' and activity_log.is_intern=0");
  return $query;
}
function visitLead_SALES($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and activity_log.added_by in ('" . $created_by . "') and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.activity_type='Lead' and orders.id!='' and activity_log.is_intern=0");

  return $query;
}

function visitLapsed_SALES($team_id, $created_by, $date_from, $date_to)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and activity_log.added_by in ('" . $created_by . "') and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject like '%visit%' and activity_log.activity_type='Lead' and l.id!='' and activity_log.is_intern=0 ");
  return $query;
}

function freshCall_lead_admin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by in ('" . $created_by . "') and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and dvr_flag=0 and activity_log.activity_type='Lead' and orders.id!='' and activity_log.is_intern=0");

  return $query;
}

function freshCall_DVR_admin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $team_id . "' and  activity_log.added_by in ('" . $created_by . "') and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and is_dr=1 and dvr_flag=1 and activity_log.activity_type='Lead' and orders.id!='' and activity_log.is_intern=0");

  return $query;
}

function freshCall_lapsed_admin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join lapsed_orders as l on activity_log.pid=l.id left join tbl_lead_product as tp on l.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and l.team_id='" . $team_id . "' and  activity_log.added_by in ('" . $created_by . "') and l.license_type='Commercial' and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "' and activity_log.call_subject = 'Fresh Call' and activity_log.activity_type='Lead' and l.id!='' and activity_log.is_intern=0");
  return $query;
}

function freshCall_raw_admin($team_id, $date_from, $date_to, $created_by)
{
  $query = getSingleresult("SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $team_id . "' and  activity_log.added_by in ('" . $created_by . "') and date(activity_log.created_date)>='" . $date_from . "' and date(activity_log.created_date)<='" . $date_to . "'  and activity_log.call_subject = 'Fresh Call' and activity_log.activity_type='Raw' and o.id!='' and activity_log.is_intern=0");
  return $query;
}

// billed Account functions for partners
function billedAccountsSearch($team_id, $condition, $condition2)
{
  $query = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,p.product_id,p.product_type_id FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.is_iss_lead = 0 and o.dvr_flag=0 and o.license_type='Commercial' " . $condition2 . " and o.stage='OEM Billing' and o.status='Approved' and o.team_id=" . $team_id . " " . $condition;

  return $query;
}

function billedAccountsLead($team_id, $condition)
{
  $query = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,p.product_id,p.product_type_id FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id WHERE o.is_iss_lead = 0 and o.dvr_flag=0  and o.license_type='Commercial' " . $condition . " and o.stage='OEM Billing' and o.status='Approved' and o.team_id=" . $team_id;

  return $query;
}

function billedAccountsPrepetual($team_id, $condition)
{
  $query = "select count(o.id) as prep_count_no ,COALESCE(SUM(o.quantity),0) as prep_license_count FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.is_iss_lead = 0 and o.dvr_flag=0 and o.license_type='Commercial' and p.product_id=1 and p.product_type_id=1 and o.stage='OEM Billing' and o.status='Approved' and o.team_id=" . $team_id . " " . $condition;

  return $query;
}

function billedAccountsAnnual($team_id, $condition)
{
  $query = "select count(o.id) as ann_count_no ,COALESCE(SUM(o.quantity),0) as ann_license_count FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.is_iss_lead = 0 and o.dvr_flag=0 and o.license_type='Commercial' and p.product_id=1 and p.product_type_id=2 and o.stage='OEM Billing' and o.status='Approved' and o.team_id=" . $team_id . " " . $condition;

  return $query;
}

// billed account functions for admin

function billedAccountsSearchAdmin($condition, $condition2)
{
  $query = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,p.product_id,p.product_type_id FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.is_iss_lead = 0 and o.dvr_flag=0 and o.license_type='Commercial' " . $condition2 . " and o.stage='OEM Billing' and o.status='Approved' " . $condition;

  return $query;
}

function billedAccountsLeadAdmin($condition)
{
  $query = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,p.product_id,p.product_type_id FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id WHERE o.is_iss_lead = 0 and o.dvr_flag=0  and o.license_type='Commercial' " . $condition . " and o.stage='OEM Billing' and o.status='Approved'";

  return $query;
}

function billedAccountsPrepetualAdmin($condition)
{
  $query = "select count(o.id) as prep_count_no ,COALESCE(SUM(o.quantity),0) as prep_license_count FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.is_iss_lead = 0 and o.dvr_flag=0 and o.license_type='Commercial' and p.product_id=1 and p.product_type_id=1 and o.stage='OEM Billing' and o.status='Approved' " . $condition;

  return $query;
}

function billedAccountsAnnualAdmin($condition)
{
  $query = "select count(o.id) as ann_count_no ,COALESCE(SUM(o.quantity),0) as ann_license_count FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.is_iss_lead = 0 and o.dvr_flag=0 and o.license_type='Commercial' and p.product_id=1 and p.product_type_id=2 and o.stage='OEM Billing' and o.status='Approved' " . $condition;

  return $query;
}

function insertRenewalLeadData($table, $code, $r_name, $r_email, $r_user, $license_key, $license_end_date, $company_name, $parent_company, $industry, $sub_industry, $region, $address, $pincode, $state, $city, $eu_name, $eu_email, $eu_mobile, $eu_designation, $quantity, $runrate_key, $created_by, $created_date, $team_id, $caller, $approval_time, $close_time, $partner_close_date)
{

  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("INSERT INTO `$table`(`code`, `r_name`, `r_email`, `r_user`, `source`, `lead_type`, `license_key`,`license_end_date`, `company_name`,`parent_company`,`industry`,`sub_industry`,`region`, `address`, `pincode`,`state`,`city`,`country`,`eu_name`, `eu_email`,`eu_mobile`,`eu_designation`,`license_type`,`quantity`, `runrate_key`, `created_by`, `created_date`, `team_id`, `status`,`caller`,`approval_time`, `close_time`, `partner_close_date`) VALUES ('" . $code . "','" . $r_name . "','" . $r_email . "','" . $r_user . "','Other','BD','" . $license_key . "','" . $license_end_date . "','" . $company_name . "','" . $parent_company . "','" . $industry . "','" . $sub_industry . "','" . $region . "','" . $address . "','" . $pincode . "','" . $state . "','" . $city . "','India','" . $eu_name . "','" . $eu_email . "','" . $eu_mobile . "','" . $eu_designation . "','Renewal','" . $quantity . "','" . $runrate_key . "','" . $created_by . "','" . $created_date . "','" . $team_id . "','Approved','" . $caller . "','" . $approval_time . "','" . $close_time . "','" . $partner_close_date . "')");

  return $res;
}

function activityLogs($field1, $field2, $field3, $field4,$field5)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $res = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by,action_plan,data_ref) values ('" . $field1 . "','" . htmlspecialchars($field2, ENT_QUOTES) . "','Lead','" . $field3 . "','" . $field4 . "','" . $field5 . "',1)");

  return $res;
}

/**Edit raw leads */
function editRawDetails($field1, $field2, $field3 = NULL)
{

  if ($field3 != '') {
    $condition = " and o.team_id=" . $field2;
  } else {
    $condition = " ";
  }
  $query = db_query("select o.*,p.product_name,p.id as productId,tpp.product_type from raw_leads as o left join tbl_product as p on o.product_id=p.id left join tbl_product_pivot as tpp on o.product_type_id=tpp.id where o.id=" . $field1 . $condition);

  return $query;
}

/**Corel interns */

function intern_stateSelect($table)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $array = [];
  $query = db_query("SELECT * FROM $table WHERE 1");
  return $query;
}

function intern_citySelect($table)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $array = [];
  $query = db_query("SELECT Distinct(city) FROM $table WHERE 1 and city IS NOT NULL and is_intern=1 order by city");
  return $query;
}

function dvrReportsDataQualified($team_id,$dat1,$dat2,$lead_id)
{
  $query = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $team_id . "' and date(o.approval_time) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "' and o.status='Approved' and o.dvr_flag=0 and o.iss is NULL  and o.id not in (" . $lead_id . ")");
  return $query;
}

function dvrReportsLCQualified($team_id,$dat1,$dat2,$lead_id)
{
  $query = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o left join lead_modify_log lm on o.id=lm.lead_id where o.team_id='" . $team_id . "' and date(o.approval_time)>='" . $dat1 . "' and date(o.approval_time)<='" . $dat2 . "' and o.lead_type='LC' and o.status='Approved' and o.dvr_flag=0 and o.iss is NULL and o.id not in (" . $lead_id . ") and lm.previous_name not in ('BD','Incoming','LC')");
  return $query;
}
function dvrReportsBDQualified($team_id,$dat1,$dat2,$lead_id)
{
  $query = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $team_id . "' and date(o.approval_time)>='" . $dat1 . "' and date(o.approval_time)<='" . $dat2 . "' and o.lead_type='BD' and o.status='Approved' and o.dvr_flag=0 and o.iss is NULL and o.id not in (" . $lead_id . ")");
  return $query;
}
function dvrReportsIncomingQualified($team_id,$dat1,$dat2,$lead_id)
{
  $query = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $team_id . "' and date(o.approval_time)>='" . $dat1 . "' and date(o.approval_time)<='" . $dat2 . "' and o.lead_type='Incoming' and o.status='Approved' and o.dvr_flag=0 and o.iss is NULL and o.id not in (" . $lead_id . ")");
  return $query;
}
function dvrReportsConvertedData($team_id,$dat1,$dat2,$lead_id,$dat)
{
  $query = db_query("select * from (select count(DISTINCT(o.id)),o.id from orders as o left join lead_modify_log lm on o.id=lm.lead_id where  (date(lm.created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') and o.team_id='" . $team_id . "' and o.license_type='Commercial' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' $dat GROUP BY o.id) t1 INNER JOIN (select count(DISTINCT(o.id)),o.id from orders as o left join lead_modify_log lm on o.id=lm.lead_id where  (date(lm.created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') and o.license_type='Commercial' and o.team_id='" . $team_id . "' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name in ('" . $lead_id . "') $dat GROUP BY o.id) t2 on t1.id=t2.id");
  return $query;
}
// function safe_implode(string $separator, mixed $array): string {
//   // Ensure the variable is an array before calling implode
//   if (!is_array($array)) {
//       return ''; // Return an empty string if it's not an array
//   }
//   return implode($separator, $array);
// }

// function safe_count($value) {
//   return is_array($value) ? count($value) : 0;
// }

// function safe_trim($value) {
//     return is_array($value) ? array_map('safe_trim', $value) : trim($value);
// }


function safe_implode($separator, $value)
{
    // Support Traversable (e.g., ArrayIterator)
    if ($value instanceof Traversable) {
        $value = iterator_to_array($value, false);
    }
 
    if (!is_array($value)) {
        return '';
    }
 
    return implode((string)$separator, $value);
}
 

function safe_count($value)
{
    if (is_array($value) || $value instanceof Countable) {
        return count($value);
    }
    return 0;
}
 

function safe_trim($value)
{
    if (is_array($value)) {
        // Use a closure to avoid relying on the function name as string
        return array_map(function ($v) { return safe_trim($v); }, $value);
    }
 
    if (is_string($value)) {
        return trim($value);
    }
 
    if ($value === null) {
        return '';
    }
 
    // For numbers/bools/objects, return as-is to avoid "Object could not be converted to string"
    return $value;
}
 