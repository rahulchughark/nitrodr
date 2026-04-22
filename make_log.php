<?php 
include('includes/include.php');

$leadids = db_query("SELECT o.id,o.created_by FROM orders o LEFT JOIN lead_modify_log lml ON o.id = lml.lead_id AND lml.type = 'Opportunity' WHERE o.is_opportunity = 1 AND lml.id IS NULL");

while ($lead = db_fetch_array($leadids)) {
    $proCreatedDate = getSingleResult("SELECT created_at From tbl_lead_product_opportunity where lead_id=".$lead['id']." order by id limit 1");
    $log = db_query("INSERT INTO lead_modify_log(`lead_id`, `type`, `previous_name`, `modify_name`, `created_date`, `created_by`)
                  VALUES('" . $lead['id'] . "', 'Opportunity', 'Lead', 'Opportunity','".$proCreatedDate."' , '".$lead['created_by']."')");
    // print_r($proCreatedDate);die;
}