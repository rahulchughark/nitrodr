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
  $query = db_query("SELECT * FROM $table WHERE 1");
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

function personalReport_TableData($table, $field1, $field2, $field3)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $select_query = db_query("SELECT $field1,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by FROM $table as o
  LEFT JOIN states as s ON o.state = s.id
  LEFT JOIN sub_industry as si ON o.industry = si.industry_id
  LEFT JOIN industry as i ON o.industry = i.id
  LEFT JOIN users as u ON o.created_by = u.id
  WHERE 1=1 ORDER By o.id Desc LIMIT " . $field2 . " ," . $field3 . "");
  //print_r($select_query);
  return $select_query;
}

function personalReport_TableDataWithDate($table, $field1, $field2, $field3, $field4)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }

  $select_query = db_query("SELECT $field1,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by  FROM $table as o
  LEFT JOIN states as s ON o.state = s.id
  LEFT JOIN sub_industry as si ON o.industry = si.industry_id
  LEFT JOIN industry as i ON o.industry = i.id
  LEFT JOIN users as u ON o.created_by = u.id
  WHERE 1 $field4 
  ORDER By o.id Desc LIMIT " . $field2 . " ," . $field3 . "");
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
  $select_query = db_query("SELECT o.*,cl.name as caller FROM $table as o
  LEFT JOIN callers as cl ON o.caller = cl.id
  WHERE 1=1 ORDER By o.id Desc LIMIT " . $field1 . " ," . $field2 . "");
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
  $select_query = db_query("SELECT o.r_name,o.lead_type,o.license_type,o.created_date,cl.name as caller FROM $table as o
  LEFT JOIN callers as cl ON o.caller = cl.id
  WHERE 1 $field1
  ORDER By o.id Desc LIMIT " . $field2 . " ," . $field3 . "");
  return $select_query;
}

function massLead_NewCaller($table){
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $select_query = db_query("SELECT * FROM $table WHERE 1 ORDER By id Desc");
  return $select_query;
}