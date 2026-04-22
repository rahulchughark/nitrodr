<?php
include('includes/include.php');
include_once('helpers/DataController.php');

$helperData = new DataController();

// DataTables parameters
$draw   = $_POST['draw'] ?? 1;
$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$search = $_POST['search']['value'] ?? '';

// Apply user-level filtering
if ($_SESSION['user_type'] == "ADMIN" || $_SESSION['user_type'] == "FM") {
    $whrCond = "";
} else {
    $whrCond = " AND o.created_by = " . intval($_SESSION['user_id']);
}

$type  = $_POST['renewal_type'] ?? '';
$fy    = $_POST['financial_year'] ?? '';


if (!empty($fy)) {
    list($fy_start, $fy_end) = explode('-', $fy);
} else {

    $year  = date("Y");
    $month = date("m");

    if ($month >= 4) {  
        $fy_start = $year;
        $fy_end   = $year + 1;
    } else {           
        $fy_start = $year - 1;
        $fy_end   = $year;
    }
}

$fy_start_date = "{$fy_start}-04-01 00:00:01";
$fy_end_date   = "{$fy_end}-03-31 23:59:59";

// $joinsCondition = " LEFT JOIN lead_modify_log lml ON lml.lead_id = o.id ";
$joinsCondition = "LEFT JOIN (
                    SELECT lead_id, created_date, type,modify_name
                    FROM lead_modify_log
                    WHERE type='stage'
                    AND modify_name='PO/CIF Issued'
                    AND created_date BETWEEN '{$fy_start_date}' AND '{$fy_end_date}'
                    GROUP BY lead_id
                ) AS lml ON lml.lead_id = o.id";


if ($type == "fresh") {
    $whrCond .= "
        AND (
                (
                    o.agreement_type = 'fresh'
                    AND lml.type = 'stage'
                    AND lml.modify_name = 'PO/CIF Issued'
                    AND lml.created_date BETWEEN '{$fy_start_date}' AND '{$fy_end_date}'
                )
                
            )";
} elseif ($type === "renewal") {

    $whrCond .= "
        AND (
                (
                    o.agreement_type = 'renewal'
                    AND renewal_leads.financial_year_start = '{$fy_start}'
                    AND renewal_leads.financial_year_end   = '{$fy_end}'
                )
               
            )
    ";

} elseif (!empty($fy)) {
    $whrCond .= "
        AND (
                (
                    lml.type = 'stage'
                    AND lml.modify_name = 'PO/CIF Issued'
                    AND lml.created_date BETWEEN '{$fy_start_date}' AND '{$fy_end_date}'
                )
                OR
                (
                    renewal_leads.financial_year_start = '{$fy_start}'
                    AND renewal_leads.financial_year_end   = '{$fy_end}'
                )
            )
    ";

} else {

    $whrCond .= "
        AND (
                (
                    lml.type = 'stage'
                    AND lml.modify_name = 'PO/CIF Issued'
                    AND lml.created_date BETWEEN '{$fy_start_date}' AND '{$fy_end_date}'
                )
                OR
                (
                    renewal_leads.financial_year_start = '{$fy_start}'
                    AND renewal_leads.financial_year_end   = '{$fy_end}'
                )
            )
    ";
}


$baseQuery = "
            FROM orders o
            LEFT JOIN tbl_mst_invoice mv
                ON mv.order_id = o.id

            LEFT JOIN tbl_mst_group grp
                ON grp.id = o.group_name

            LEFT JOIN tbl_renewal_lead_task_process_record renewal_leads
                ON renewal_leads.lead_id = o.id

            LEFT JOIN tbl_invoice_emi emi
                ON emi.order_id = o.id
                AND emi.is_deleted = 0

            LEFT JOIN (
                    SELECT 
                        emi.order_id,
                        (
                            SUM(
                                CASE
                                    WHEN emi.status = 1
                                        AND emi.is_deleted = 0
                                        AND (emi.received_amount IS NULL OR emi.received_amount < emi.amount)
                                    THEN emi.amount - IFNULL(emi.received_amount, 0)
                                    ELSE 0
                                END
                            )
                            - IFNULL(mv.tds, 0)
                        ) AS total_outstanding
                    FROM tbl_invoice_emi emi
                    LEFT JOIN tbl_mst_invoice mv
                        ON mv.order_id = emi.order_id
                    GROUP BY emi.order_id
                ) emi_total
                    ON emi_total.order_id = o.id


            $joinsCondition

            WHERE o.school_name IS NOT NULL
            AND o.school_name != ''
            AND EXISTS (
                SELECT 1
                FROM opportunity_attachments oa
                WHERE oa.lead_id = o.id
                AND oa.attachment_type IN ('po_attachments','pi_attachments','invoice_attachments')
            )
            $whrCond
            ";


    // Total records
    $totalRecords = mysqli_fetch_assoc(
        db_query("SELECT COUNT(DISTINCT o.id) as total $baseQuery")
    )['total'];

    // Search filter
    $searchCond = "";
    if (!empty($search)) {
        // $search = mysqli_real_escape_string($conn, $search);
        $searchCond = " AND (o.school_name LIKE '%$search%' OR o.agreement_type LIKE '%$search%' OR grp.name LIKE '%$search%')";
    }

    $totalFiltered = mysqli_fetch_assoc(
        db_query("SELECT COUNT(DISTINCT o.id) as total $baseQuery $searchCond")
    )['total'];

   
    $query = db_query("
    SELECT o.id,
        CASE 
            WHEN o.is_group = 'yes' THEN UCASE(grp.name) 
            ELSE o.school_name 
        END AS school_name, 
        o.agreement_type, 
        mv.billing_detail,
        mv.remark AS order_remark, 
        mv.sub_category, 
        mv.tds,
        o.is_group,
        o.group_name,
        COALESCE(emi_total.total_outstanding, 0) AS total_outstanding
        $baseQuery    
        $searchCond
        GROUP BY 
            CASE 
                WHEN o.is_group = 'yes' THEN o.group_name 
                ELSE o.id 
            END
        ORDER BY
        IFNULL(emi_total.total_outstanding, 0) = 0 ASC,
        IFNULL(emi_total.total_outstanding, 0) DESC,
        emi.date ASC
        LIMIT $start, $length
");

// $data = [];
// while ($row = db_fetch_array($query)) {
//     $data[] = $row;
// }

// echo "<pre>";
// print_r($data);
// exit;

$data = [];
while ($row = db_fetch_array($query)) {

        // This is for Product's
        $products = $helperData->getProductsByLeadId($row['id'],$row['is_group'], $row['group_name']);       

        $customAmountData = $helperData->fetchLeadCustomAmount($row['id']);
        $customAmount = $customAmountData['amount'] ?? 0;
        $model3ProductValue = "";

        if (!empty($products)) {
            $modulesParts = [];
            $quantityParts = [];
            $categoryProduct = [];
         

            foreach ($products as $product) {
                $modulesParts[] = sprintf(
                    '<p>%s <span class="mdi mdi-note-text text-main cursor-pointer" onclick="module_view(%d,%d,\'%s\',%d)"></span></p>',
                    htmlspecialchars($product['product_name']),
                    $row['id'],
                    $product['main_product_id'],
                    $row['is_group'],
                    $row['group_name']
                );
                $quantityParts[] = '<span>' . intval($product['quantity']) . '</span>';
                $categoryProduct[] = $product['product_name'];              
                $model3ProductValue = $product['product_name'] == "Model 3" ? "Model 3" : $model3ProductValue;
            }

            $modulesHtml  = '<div class="inner-items">' . implode('', $modulesParts) . '</div>';
            $quantityHTML = '<div class="inner-items">' . implode('', $quantityParts) .'</div>';
            $categoryProduct = implode(', ', $categoryProduct);
        } else {
            $modulesHtml  = '<div class="inner-items">NA</div>';
            $quantityHTML = '<div class="inner-items"><span>0</span></div>';
            $categoryProduct = "NA";
     
        }

       $isModel3Product = $model3ProductValue == "Model 3" ? 1 : 0;

       $isInvoiceAttachmentReadyPI = $helperData->isInvoiceReadyWithAttachments($row["id"],'pi_attachments');
       $isInvoiceAttachmentReadyInvoice = $helperData->isInvoiceReadyWithAttachments($row["id"],'invoice_attachments');
       
        
       $subCategoryEnum = [
                1 => 'Direct',
                2 => 'Reseller Combo'
            ];

            $subCategory = isset($subCategoryEnum[$row['sub_category']])
                ? $subCategoryEnum[$row['sub_category']]
                : 'NA';
	    

        # This is for GST/ Non GST Amount
        $productAmount = $helperData->getCollectionPrice($row['id'],$row['is_group'], $row['group_name']);

        if (!empty($productAmount['total_inc_gst']) && !empty($productAmount['total_exc_gst'])) {
            // [1 = Inc GST, 2 = Exc GST]
            $id = (int)$row['id'];
            $isGroup = $row['is_group'] ? (boolean)$row['is_group'] : 0;
            $groupName = $row['group_name'] ? $row['group_name'] : null;

            $gstIncBtn = ' <button class="btn btn-primary px-2 py-1" 
              onclick="rate_per_student(1, '.$id.', '.$isGroup.', \''.$groupName.'\','.$isModel3Product.')">
            <span class="mdi mdi-eye"></span></button>';

            $gstExcBtn = ' <button class="btn btn-primary px-2 py-1"
             onclick="rate_per_student(2, '.$id.', '.$isGroup.', \''.$groupName.'\','.$isModel3Product.')">
             <span class="mdi mdi-eye"></span></button>';

            $amountGST = $customAmount > 0 ? $customAmount : $productAmount['total_inc_gst'];
            $amountNoGST = $customAmount > 0 ? $customAmount : $productAmount['total_exc_gst'];

            $labelPriceGST   = '<span class="d-inline-block" style="min-width: 80px">₹'. $amountGST.'</span>' . $gstIncBtn;
            $labelPriceExGST = '<span class="d-inline-block" style="min-width: 80px">₹'.$amountNoGST.'</span>' . $gstExcBtn;
            } else {
            $labelPriceGST = $labelPriceExGST = 0;
                }
             
       $invoiceEMIs = $helperData->getInvoiceEmiByOrderId($row['id']);
       $isShowTDS = false;        

        if (!empty($invoiceEMIs)) {
            $isShowTDS = true;
            $emiProductInput      = '<div class="inner-items">';
            $emiReceivedAmount    = '<div class="inner-items">';
            $emiReceivedAmountDate = '<div class="inner-items">';
            $emiReceivedAmountDateInput = '<div class="inner-items">';

            foreach ($invoiceEMIs as $product) {
                $emiProductInput      .= '<div class="form-fields">
                <input type="text" class="form-control" onclick="this.select()" value='.$product['received_amount'].' id="receiving-amount-'.$product['id'].'">
                <button class="btn btn-primary"
                 onclick="return updateReceivingData(event,1,'.$product['id'].')">save</button></div>';
                $emiReceivedAmount    .= '<p>₹' . $product['amount'] . '</p>';
                $emiReceivedAmountDate .= '<p>' . $product['date'] . '</p>';
                $emiReceivedAmountDateInput .= '<div class="form-fields">
                <input type="date" onkeydown="return false;" value='.$product['received_date'].' id="receiving-date-'.$product['id'].'" 
                onchange="return updateReceivingData(event,2,'.$product['id'].')" class="form-control"></div>';
            }

            $emiProductInput      .= '</div>';
            $emiReceivedAmount    .= '</div>';
            $emiReceivedAmountDate .= '</div>';
            $emiReceivedAmountDateInput .= '</div>';

        } else {
            $isShowTDS = false;
            $naHtml = '<div class="inner-items">NA</div>';
            $emiProductInput      = $naHtml;  
            $emiReceivedAmount    = $naHtml;    
            $emiReceivedAmountDate = $naHtml; 
            $emiReceivedAmountDateInput = $naHtml; 
        }
       
	
    $data[] = [
        // 1: S.No.
        'sno' => $row['id'],

        // 2: PO View
        'po_btn' => '<button class="btn btn-primary px-2 py-1" onclick="po_view('.$row['id'].', '.$isGroup.', '.$groupName.')"><span class="mdi mdi-eye"></span></button>',

        // 3: PI View
        'pi_btns' => '<div class="text-nowrap">
                           <button class="btn btn-primary px-2 py-1 ' . ($isInvoiceAttachmentReadyPI ? '' : 'd-none') . '" 
                                    onclick="pi_edit(' . $row['id'] . ',2, '.$isGroup.', '.$groupName.')">
                                <span class="mdi mdi-eye"></span>
                            </button>
                        <button class="btn btn-primary px-2 py-1" onclick="pi_attach('.$row['id'].', '.$isGroup.', '.$groupName.')"><span class="mdi mdi-paperclip"></span></button>
                        <button class="btn btn-primary px-2 py-1 ' . ($isInvoiceAttachmentReadyPI ? '' : 'd-none') . '" onclick="pi_edit('.$row['id'].',1, '.$isGroup.', '.$groupName.')"><span class="mdi mdi-pencil"></span></button>
                      </div>',

        // 4: Invoice View
        'invoice_view' => '<div class="text-nowrap">
                            <button class="btn btn-primary px-2 py-1 ' . ($isInvoiceAttachmentReadyInvoice ? '' : 'd-none') . '" onclick="invoice_attach_edit('.$row['id'].',2, '.$isGroup.', '.$groupName.')"><span class="mdi mdi-eye"></span></button>
                            <button class="btn btn-primary px-2 py-1" onclick="invoice_attach('.$row['id'].', '.$isGroup.', '.$groupName.')"><span class="mdi mdi-paperclip"></span></button>
                            <button class="btn btn-primary px-2 py-1 ' . ($isInvoiceAttachmentReadyInvoice ? '' : 'd-none') . '" onclick="invoice_attach_edit('.$row['id'].',1, '.$isGroup.', '.$groupName.')"><span class="mdi mdi-pencil"></span></button>
                          </div>',

        // 5: School Name
        'school_name' => htmlspecialchars($row['school_name']),

        // 6: Billing Name
        'billing_name' => $row['billing_detail'] ? $row['billing_detail'] : 'NA',

        // 7: New Renewal
        'agreement_type' => htmlspecialchars($row['agreement_type']),

        // 8: Module
        'modules' => $modulesHtml,

        // 9: Student Count / Platform
        'student_count' => $quantityHTML,

        // 10: No of License Count
        'license_count' => '-',
        
        // 11: Rate per Student Inc. GST
        'rate_gst' => $labelPriceGST,
        // 12: Rate per Student Basic Value
        'rate_basic' => $labelPriceExGST,

        

        // 13: Total Value
        // 'total_value' => '194,610',

        // 14: Category
        'category' => $categoryProduct,

        // 15: Sub-Category
        'sub_category' => $subCategory,

        // 16: Receivable Amount
        'receivable_amount' => $emiReceivedAmount,

        // 17: Receivable Date
        'receivable_date' => $emiReceivedAmountDate,

        // 18: Received Amount
        'received_amount' => $emiProductInput,

        // 19: Received Date
        'received_date' => $emiReceivedAmountDateInput,

        // 20: Value of TDS
        'tds_value' => $isShowTDS ? '<div class="inner-items">
                        <div class="form-fields">
                        <input type="number" onkeyup="checkValue(this)" value="'.$row["tds"].'" id="amount-tds-'.$row["id"].'" class="form-control">
                        <button onclick="updateTDSDetail('.$row["id"].')" class="btn btn-primary">save</button>
                        </div>
                        </div>' : 'NA',

        // 21: Overdue Payment
        'overdue_payment' => $helperData->getCurrentMonthInvoiceEmi($row['id']),

        // 22: Total Overdue Outstanding
        'total_overdue' => $helperData->getTotalOutstandingByOrder($row['id']),

        // 23: Status of Invoicing
       'status' => $isInvoiceAttachmentReadyInvoice 
                                    ? '<span class="badge badge-success">Invoice Done</span>' 
                                    : '<span class="badge badge-warning">Invoice Pending</span>',

        // 24: Remarks
        'remarks' => '<div class="inner-items">
                        <div class="form-fields flex-column align-items-start">
                            <textarea id="remark-textarea-'.$row["id"].'" class="form-control">
                             '.trim($row['order_remark']).'
                            </textarea>
                            <button id="btn-remark-'.$row["id"].'" onclick="invoiceRemarkAdd('.$row["id"].')" class="btn btn-primary">save</button>
                        </div>
                      </div>'
    ];
}

echo json_encode([
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
]);