<style>
    .module-view-table td {
        height: 50px;
        padding-left: 20px;
        padding-right: 20px;
    }
</style>


<?php
include('includes/include.php');
include_once('helpers/DataController.php');

$helperData = new DataController();


$orderID = $_POST['order_id'];
$masterProduct = $_POST['master_product_id'];
$is_group = $_POST['is_group'];
$group_id = $_POST['group_id'];


if ($is_group === "yes") {
    // Get all lead IDs for the group
    $leadsID = $helperData->getGroupOrderIdsStr($group_id); // e.g., "2382,2443"
} else {
    // Single lead ID (assuming $orderID comes from somewhere)
    $leadsID = intval($orderID);
}

if ($is_group === "yes") {
    $leadCondition = "lpo.lead_id IN ($leadsID)";
} else {
    $leadCondition = "lpo.lead_id = $orderID";
}

    $result = db_query("
        SELECT 
            tpo.product_name, 
            tpo.id AS product_id,
            COUNT(lpo.lead_id) AS product_count
        FROM tbl_lead_product_opportunity AS lpo
        LEFT JOIN tbl_product_opportunity AS tpo
            ON tpo.id = lpo.product
        WHERE $leadCondition
        AND lpo.main_product_id = $masterProduct
        AND lpo.status = 1 
        AND lpo.deleted_by IS NULL
        GROUP BY tpo.id, tpo.product_name");


// $result = db_query("
//     SELECT tpo.product_name, tpo.id AS product_id
//     FROM tbl_lead_product_opportunity AS lpo
//     LEFT JOIN tbl_product_opportunity AS tpo
//       ON tpo.id = lpo.product
//       WHERE lpo.lead_id = $orderID 
//       AND lpo.main_product_id = $masterProduct
//       AND lpo.status = 1 
//       AND lpo.deleted_by IS NULL
// ");





$masterProductQuery = db_query("
    SELECT name
    FROM tbl_main_product_opportunity
    WHERE id = $masterProduct
");

$row2 = db_fetch_array($masterProductQuery);
$productName = $row2 ? $row2['name'] : null;


?>

<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
			<h5 class="modal-title align-self-center mt-0" id="exampleModalLabel"><?= $productName ?></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
		</div>
		<div class="modal-body mb-4">
            <table class="table module-view-table">
                <?php
                while ($row = db_fetch_array($result)) {
                    ?>
                    <tr>
                        <td><?= $row['product_count']  ?></td>
                        <td><?= $row['product_name'] ?></td>
                    </tr>
                    <?php
                    }
                    ?>
            </table>
        </div>
    </div>
</div>
