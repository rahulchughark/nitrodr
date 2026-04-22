<?php

function diffTime($startdate,$enddate){
  $starttimestamp = strtotime($startdate);
  $endtimestamp = strtotime($enddate);
  $difference = abs($endtimestamp - $starttimestamp)/3600;
  return $difference;
}

function dataCounterReceivedAdmin($date_from,$date_to,$sales)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.agreement_type='Fresh' and o.dvr_flag!=1 and date(o.created_date)>='" . $date_from . "' and date(o.created_date)<='" . $date_to . "'" .$sales);

  return $query;
}

function dataCounterPendingAdmin($date_from,$date_to,$sales)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.agreement_type='Fresh' and o.dvr_flag!=1 and o.status='Pending' and date(o.created_date)>='" . $date_from . "' and date(o.created_date)<='" . $date_to . "'" .$sales);

  return $query;
}

function dataCounterQualifiedAdmin($date_from,$date_to,$sales)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.agreement_type='Fresh' and o.dvr_flag!=1 and o.status='Approved' and date(o.created_date)>='" . $date_from . "' and date(o.created_date)<='" . $date_to . "'" .$sales);

  return $query;
}
function dataCounterUnQualifiedAdmin($date_from,$date_to,$sales)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.agreement_type='Fresh' and o.dvr_flag!=1 and o.status='Cancelled' and date(o.created_date)>='" . $date_from . "' and date(o.created_date)<='" . $date_to . "'" .$sales);

  return $query;
}
function dataCounterOthersAdmin($date_from,$date_to,$sales)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.agreement_type='Fresh' and o.dvr_flag!=1 and o.status in('Already locked','Insufficient Information','Incorrect Information','Out Of Territory','Duplicate Record Found') and date(o.created_date)>='" . $date_from . "' and date(o.created_date)<='" . $date_to . "'" .$sales);

  return $query;
}
function dataCounterInsufficientInformationAdmin($date_from,$date_to,$sales)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.agreement_type='Fresh' and o.dvr_flag!=1 and o.status='Insufficient Information' and date(o.created_date)>='" . $date_from . "' and date(o.created_date)<='" . $date_to . "'" .$sales);

  return $query;
}

function dataCounterIncorrectInformationAdmin($date_from,$date_to,$sales)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.agreement_type='Fresh' and o.dvr_flag!=1 and o.status='Incorrect Information' and date(o.created_date)>='" . $date_from . "' and date(o.created_date)<='" . $date_to . "'" .$sales);

  return $query;
}

function dataCounterOutOfTerritoryAdmin($date_from,$date_to,$sales)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.agreement_type='Fresh' and o.dvr_flag!=1 and o.status='Out Of Territory' and date(o.created_date)>='" . $date_from . "' and date(o.created_date)<='" . $date_to . "'" .$sales);

  return $query;
}

function dataCounterDuplicateRecordFoundAdmin($date_from,$date_to,$sales)
{
  if ($dbcon2 == '') {
    if (!isset($GLOBALS['dbcon'])) {
      connect_db();
    }
    $dbcon2  = $GLOBALS['dbcon'];
  }
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.agreement_type='Fresh' and o.dvr_flag!=1 and o.status='Duplicate Record Found' and date(o.created_date)>='" . $date_from . "' and date(o.created_date)<='" . $date_to . "'" .$sales);

  return $query;
}

function todayProgressLCAdmin($date_from,$sales){
    if ($dbcon2 == '') {
        if (!isset($GLOBALS['dbcon'])) {
          connect_db();
        }
        $dbcon2  = $GLOBALS['dbcon'];
      }
      $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o where  o.agreement_type='Fresh' and o.dvr_flag!=1 and o.lead_type='LC' and o.status !='Pending' and date(o.approval_time)='".$date_from ."'".$sales);
    
      return $query;
}
function todayProgressBDAdmin($date_from,$sales){
    if ($dbcon2 == '') {
        if (!isset($GLOBALS['dbcon'])) {
          connect_db();
        }
        $dbcon2  = $GLOBALS['dbcon'];
      }
      $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o where  o.agreement_type='Fresh' and o.dvr_flag!=1 and o.lead_type='BD' and o.status !='Pending' and date(o.approval_time)='".$date_from ."'".$sales);
    
      return $query;
}

function todayProgressIncomingAdmin($date_from,$sales){
    if ($dbcon2 == '') {
        if (!isset($GLOBALS['dbcon'])) {
          connect_db();
        }
        $dbcon2  = $GLOBALS['dbcon'];
      }
      $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.agreement_type='Fresh' and o.dvr_flag!=1 and o.lead_type='Incoming' and (o.status='Approved' or o.status='Cancelled' or o.status='Undervalidation' or o.status='On-Hold') and date(o.approval_time)='".$date_from ."'".$sales);
    
      return $query;
}

function todayProgressPendingAdmin($date_from,$sales){
    if ($dbcon2 == '') {
        if (!isset($GLOBALS['dbcon'])) {
          connect_db();
        }
        $dbcon2  = $GLOBALS['dbcon'];
      }
      $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o where  o.agreement_type='Fresh' and o.dvr_flag!=1 and o.status ='Pending' and date(o.created_date)='".$date_from ."'".$sales);
    
      return $query;
}

function activeTeamVAR($sm_user){
    if ($dbcon2 == '') {
        if (!isset($GLOBALS['dbcon'])) {
          connect_db();
        }
        $dbcon2  = $GLOBALS['dbcon'];
      }
      $query = getSingleresult("select count(id) from partners where status='Active'".$sm_user);
    
      return $query;
}

function activeTeamISS($active_users){
    if ($dbcon2 == '') {
        if (!isset($GLOBALS['dbcon'])) {
          connect_db();
        }
        $dbcon2  = $GLOBALS['dbcon'];
      }
      $query = getSingleresult("select count(id) from users where status='Active' and role='TC' ".$active_users);
    
      return $query;
}
function activeTeamSales($active_users){
    if ($dbcon2 == '') {
        if (!isset($GLOBALS['dbcon'])) {
          connect_db();
        }
        $dbcon2  = $GLOBALS['dbcon'];
      }
      $query = getSingleresult("select count(id) from users where status='Active' and role='SAL' ".$active_users);
    
      return $query;
}
function activeTeamAE($active_users){
    if ($dbcon2 == '') {
        if (!isset($GLOBALS['dbcon'])) {
          connect_db();
        }
        $dbcon2  = $GLOBALS['dbcon'];
      }
      $query = getSingleresult("select count(id) from users where status='Active' and role='AE' ".$active_users);
    
      return $query;
}

function inactiveTeamDVR($field,$sales){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from orders o where o.agreement_type='Fresh' and o.dvr_flag=1 and o.created_date>='". $field."' )".$sales);
    
      return $query;
}
function inactiveTeamlogLead($field,$sales){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from orders o left join activity_log a on o.id=a.pid where o.agreement_type='Fresh' and a.pid=o.id and a.created_date>= '". $field."' )".$sales);  
    
      return $query;
}
function inactiveTeamlogLapsed($field,$sales){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from lapsed_orders o left join activity_log a on o.id=a.pid where o.agreement_type='Fresh' and a.pid=o.id and a.created_date>= '". $field."' )".$sales);  
    
      return $query;
}
function inactiveTeamlogRaw($field,$sales){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from raw_leads o left join activity_log a on o.id=a.pid where a.pid=o.id and a.created_date>= '". $field."' )".$sales);  
    
      return $query;
}
function inactiveTeamStage($field,$sales){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from orders o left join lead_modify_log a on o.id=a.lead_id where o.agreement_type='Fresh' and a.lead_id=o.id and a.type='Stage' and a.timestamp>= '". $field."' )".$sales);  
    
      return $query;
}

function inactiveTeamDVRDate7($sm_user){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from orders o where o.agreement_type='Fresh' and o.dvr_flag=1 and o.created_date BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() )". $sm_user);
    
      return $query;
}
function inactiveTeamlogLeadDate7($sm_user){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from orders o left join activity_log a on o.id=a.pid where o.agreement_type='Fresh' and a.pid=o.id and a.created_date BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() )". $sm_user);  
    
      return $query;
}
function inactiveTeamlogLapsedDate7($sm_user){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from lapsed_orders o left join activity_log a on o.id=a.pid where o.agreement_type='Fresh' and a.pid=o.id and a.created_date BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() )". $sm_user);  
    
      return $query;
}
function inactiveTeamlogRawDate7($sm_user){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from raw_leads o left join activity_log a on o.id=a.pid where a.pid=o.id and a.created_date BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() )". $sm_user);  
    
      return $query;
}
function inactiveTeamStageDate7($sm_user){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from orders o left join lead_modify_log a on o.id=a.lead_id where o.agreement_type='Fresh' and a.lead_id=o.id and a.type='Stage' and a.timestamp BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() )". $sm_user);  
    
      return $query;
}

function inactiveTeamDVRDate15($sm_user){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from orders o where o.agreement_type='Fresh' and o.dvr_flag=1 and o.created_date BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW() )". $sm_user);
    
      return $query;
}
function inactiveTeamlogLeadDate15($sm_user){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from orders o left join activity_log a on o.id=a.pid where o.agreement_type='Fresh' and a.pid=o.id and a.created_date BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW() )". $sm_user);  
    
      return $query;
}
function inactiveTeamlogLapsedDate15($sm_user){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from lapsed_orders o left join activity_log a on o.id=a.pid where o.agreement_type='Fresh' and a.pid=o.id and a.created_date BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW() )". $sm_user);  
    
      return $query;
}
function inactiveTeamlogRawDate15($sm_user){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from raw_leads o left join activity_log a on o.id=a.pid where a.pid=o.id and a.created_date BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW() )". $sm_user);  
    
      return $query;
}
function inactiveTeamStageDate15($sm_user){
  $query = db_query("select DISTINCT(name) from partners where status='Active' AND id NOT IN (select DISTINCT(o.team_id) from orders o left join lead_modify_log a on o.id=a.lead_id where o.agreement_type='Fresh' and a.lead_id=o.id and a.type='Stage' and a.timestamp BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW() )". $sm_user);  
    
      return $query;
}

function DRActionLC($date_from,$date_to){

  $lc_query=db_query("select id,created_date,approval_time,convert_date FROM orders where status='Approved' and lead_type='LC' and date(created_date) BETWEEN '".$date_from."' and '".$date_to."' group by id");

  $total_time=0;
  $count_lc=db_num_array($lc_query);
  if($count_lc){
  while($lc_data=db_fetch_array($lc_query))
  {
      if($lc_data['convert_date'])
      {
          $time_diff=diffTime($lc_data['convert_date'],$lc_data['approval_time']);
      }
      else
      {
          $time_diff+=diffTime($lc_data['created_date'],$lc_data['approval_time']);  
      }
      if($time_diff<200)
      {
          $total_time+=$time_diff;
      }
  }
  $lc=$total_time/$count_lc;
  }

  return $lc;

}

function DRActionBD($date_from,$date_to){
  
  $bd_query=db_query("select id,created_date,approval_time,convert_date FROM orders where status='Approved' and lead_type='BD' and date(created_date) BETWEEN '".$date_from."' and '".$date_to."' group by id");
  $total_time=0;
  $count_bd=db_num_array($bd_query);
  if($count_bd){
  while($bd_data=db_fetch_array($bd_query))
  {
   

      if($bd_data['convert_date'])
      {
          $time_diff=diffTime($bd_data['convert_date'],$bd_data['approval_time']);
      }
      else
      {
          $time_diff+=diffTime($bd_data['created_date'],$bd_data['approval_time']);  
      }
      if($time_diff<200)
      {
          $total_time+=$time_diff;
      }
  }
  $bd=$total_time/$count_bd;
  }

  return $bd;
}

function DRActionIncoming($date_from,$date_to){

  $in_query=db_query("select id,created_date,approval_time,convert_date,IFNULL(AVG(TIMESTAMPDIFF(HOUR,created_date,approval_time)),'N/A') as avg_time FROM orders where status='Approved' and lead_type='Incoming' and date(created_date) BETWEEN '".$date_from."' and '".$date_to."' group by id");
  $total_time=0;
  $count_in=db_num_array($in_query);
   if($count_in){
  while($in_data=db_fetch_array($in_query))
  {
        
     
      $time_diff+=diffTime($in_data['created_date'],$in_data['approval_time']);  
      if($in_data['avg_time']<200 && $in_data['avg_time']>0)
      {
          $total_time+=$in_data['avg_time'];
      }
      
  }
  $incoming=$total_time/$count_in;
  }

  return $incoming;
}
/**
 * Corel ISS Dashboard
 * ISS Caller
 */
function scoreQualifiedCaller($date_from,$caller)
{
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.agreement_type='Fresh' and o.dvr_flag!=1 and o.status='Approved' and o.is_iss_lead = 0 and date(o.approval_time)='" . $date_from . "' and o.caller=".$caller);

  return $query;
}
function scoreLogsCaller($date_from,$caller){
  $query = getSingleresult("select count(DISTINCT(a.id)) from activity_log as a left join tbl_lead_product as tp on a.pid=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and date(a.created_date)='" . $date_from . "' and a.added_by=" . $caller);
  return $query;
}
function VARLeadsCaller($date_from,$caller){
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.agreement_type='Fresh' and o.dvr_flag!=1 and o.is_iss_lead = 1 and date(o.created_date)='" . $date_from . "' and o.created_by=".$caller);
  return $query;
}

function dataCounterCaller($date_from,$date_to,$caller)
{
  $query =  db_query("select o.r_name,count(o.id),IFNULL(sum(distinct(o.id)),0),o.team_id from orders as o  left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.dvr_flag=0 and o.is_iss_lead = 0 and o.status='Approved' and o.agreement_type='Fresh' and date(o.approval_time)>='" . $date_from . "' and date(o.approval_time)<='" . $date_to . "' and o.caller=".$caller." group by o.r_name");
  return $query;
}

function LCBDCounterCaller($date_from,$date_to,$caller)
{
  $query =  db_query("select * from (select count(lm.lead_id),o.r_name from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2) and o.agreement_type='Fresh' and date(lm.created_date)>='" . $date_from . "' and date(lm.created_date)<='" . $date_to . "' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' group by o.r_name) t1 INNER JOIN (select count(lm.lead_id),o.r_name from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2) and o.agreement_type='Fresh' and date(lm.created_date)>='" . $date_from . "' and date(lm.created_date)<='" . $date_to . "' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name='".$caller."' group by o.r_name) t2 on t1.r_name=t2.r_name ");

  return $query;
}

function LCBDCounterMngr($date_from,$date_to,$iss_names)
{
  $query =  db_query("select * from (select count(lm.lead_id),o.r_name from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2) and o.agreement_type='Fresh' and date(lm.created_date)>='" . $date_from . "' and date(lm.created_date)<='" . $date_to . "' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' group by o.r_name) t1 INNER JOIN (select count(lm.lead_id),o.r_name from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2) and o.agreement_type='Fresh' and date(lm.created_date)>='" . $date_from . "' and date(lm.created_date)<='" . $date_to . "' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name in ('".$iss_names."') group by o.r_name) t2 on t1.r_name=t2.r_name");

  return $query;
}

function LCBDCounterSalesMngr($date_from,$date_to,$iss_names)
{
  $query =  db_query("select * from (select count(lm.lead_id),o.r_name from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2) and o.agreement_type='Fresh' and date(lm.created_date)>='" . $date_from . "' and date(lm.created_date)<='" . $date_to . "' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' and o.team_id in (".$_SESSION['access'].") group by o.r_name) t1 INNER JOIN (select count(lm.lead_id),o.r_name from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2) and o.agreement_type='Fresh' and date(lm.created_date)>='" . $date_from . "' and date(lm.created_date)<='" . $date_to . "' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name in ('".$iss_names."') and o.team_id in (".$_SESSION['access'].") group by o.r_name) t2 on t1.r_name=t2.r_name");

  return $query;
}

/**
 * ISS MANAGER
 */

function dataCounterISSMngr($date_from,$date_to,$iss_ids)
{
  $query =  db_query("select o.r_name,count(o.id),IFNULL(sum(distinct(o.id)),0),o.team_id from orders as o  left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.dvr_flag=0 and o.is_iss_lead = 0 and o.status='Approved' and o.agreement_type='Fresh' and date(o.approval_time)>='" . $date_from . "' and date(o.approval_time)<='" . $date_to . "' and o.caller in (".$iss_ids.") group by o.r_name");
  return $query;
}
function scoreQualifiedMngr($date_from,$iss_ids)
{
  $query =getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.agreement_type='Fresh' and o.dvr_flag!=1 and o.status='Approved' and o.is_iss_lead = 0 and date(o.approval_time)='" . $date_from . "' and o.caller in (".$iss_ids.")");
  return $query;
}
function scoreLogsMngr($date_from,$iss_ids)
{
  $query = getSingleresult("select count(DISTINCT(a.id)) from activity_log as a left join tbl_lead_product as tp on a.pid=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and date(a.created_date)='" . $date_from . "' and a.added_by in (" . $iss_ids.")");
  return $query;
}

function VARLeadsMngr($date_from,$iss_ids)
{
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.agreement_type='Fresh' and o.dvr_flag!=1 and o.is_iss_lead = 1 and date(o.created_date)='" . $date_from . "' and o.created_by in (".$iss_ids.")");
  return $query;
}

function poaNeedMoreValidationMngr($iss_ids,$date_from,$date_to)
{
  $query = getSingleresult("select DISTINCT(o.id),a.action_plan from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log a on o.id=a.pid left join callers c on c.id=o.caller where tp.product_type_id in (1,2) and o.agreement_type='Fresh' and a.is_intern=0 and o.caller in (".$iss_ids.") and date(a.created_date)>='" . $date_from . "' and date(a.created_date)<='" . $date_to . "' group by a.pid having (count(a.pid)>0) order by a.created_date desc");
  return $query;
}

function poaDropMngr($iss_ids,$date_from,$date_to)
{
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log a on o.id=a.pid left join callers c on c.id=o.caller where tp.product_type_id in (1,2) and o.agreement_type='Fresh' and a.is_intern=0 and a.action_plan='Drop' and o.caller in (".$iss_ids.") and date(a.created_date)>='" . $date_from . "' and date(a.created_date)<='" . $date_to . "' group by a.pid ORDER BY a.id DESC limit 1");
  return $query;
}

function poaTurnsNegativeMngr($iss_ids,$date_from,$date_to)
{
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log a on o.id=a.pid left join callers c on c.id=o.caller where tp.product_type_id in (1,2) and o.agreement_type='Fresh' and a.is_intern=0 and a.action_plan='Turns Negative' and o.caller in (".$iss_ids.") and date(a.created_date)>='" . $date_from . "' and date(a.created_date)<='" . $date_to . "' group by a.pid ORDER BY a.id DESC limit 1");
  return $query;
}

function poaNeedMoreValidation($caller,$date_from,$date_to)
{
  $query = getSingleresult("select DISTINCT(o.id),a.action_plan from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log a on o.id=a.pid left join callers c on c.id=o.caller where tp.product_type_id in (1,2) and o.agreement_type='Fresh' and a.is_intern=0 and o.caller=".$caller." and date(a.created_date)>='" . $date_from . "' and date(a.created_date)<='" . $date_to . "' group by a.pid having (count(a.pid)>0) order by a.created_date desc");
  return $query;
}

function poaDrop($caller,$date_from,$date_to)
{
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log a on o.id=a.pid left join callers c on c.id=o.caller where tp.product_type_id in (1,2) and o.agreement_type='Fresh' and a.is_intern=0 and a.action_plan='Drop' and o.caller=".$caller." and date(a.created_date)>='" . $date_from . "' and date(a.created_date)<='" . $date_to . "' group by a.pid having (count(a.pid)>1) ");
  return $query;
}

function poaTurnsNegative($caller,$date_from,$date_to)
{
  $query = getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log a on o.id=a.pid left join callers c on c.id=o.caller where tp.product_type_id in (1,2) and o.agreement_type='Fresh' and a.is_intern=0 and a.action_plan='Turns Negative' and o.caller=".$caller." and date(a.created_date)>='" . $date_from . "' and date(a.created_date)<='" . $date_to . "' group by a.pid having (count(a.pid)>1) ");
  return $query;
}

// ISS SBE  Manager
 function dataCounterSBEMngr($date_from,$date_to,$iss_ids)
{
  $query =  db_query("select o.r_name,count(o.id),IFNULL(sum(distinct(o.id)),0),o.team_id from orders as o  left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2,3) and o.quantity >= 3 and o.dvr_flag=0 and o.is_iss_lead = 0 and o.status='Approved' and o.agreement_type='Fresh' and date(o.approval_time)>='" . $date_from . "' and date(o.approval_time)<='" . $date_to . "' and o.caller in (".$iss_ids.") group by o.r_name");
  return $query;
}

function LCBDCounterSBEMngr($date_from,$date_to,$iss_names)
{
  $query =  db_query("select * from (select count(lm.lead_id),o.r_name from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2,3) and o.agreement_type='Fresh' and o.quantity >= 3 and date(lm.created_date)>='" . $date_from . "' and date(lm.created_date)<='" . $date_to . "' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' group by o.r_name) t1 INNER JOIN (select count(lm.lead_id),o.r_name from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2,3) and o.agreement_type='Fresh' and o.quantity >= 3 and date(lm.created_date)>='" . $date_from . "' and date(lm.created_date)<='" . $date_to . "' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name in ('".$iss_names."') group by o.r_name) t2 on t1.r_name=t2.r_name");

  return $query;
}
