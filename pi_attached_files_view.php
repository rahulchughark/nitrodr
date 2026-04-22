<?php
include('includes/include.php');
include_once('helpers/DataController.php');

header('Content-Type: application/json');

$order_id        = (int)$_POST['order_id'];  
$main_product_id = (int)$_POST['main_product'];  
$sub_product_id  = (int)$_POST['sub_product_id'];  

$sql = "SELECT id, attachment_path, created_at, name, amount 
        FROM opportunity_attachments 
        WHERE lead_id = $order_id
          AND tbl_lead_product_id = $main_product_id
          AND product_id = $sub_product_id
          AND status = 1
        ORDER BY created_at DESC";

$result = db_query($sql);

$html = '';
$counter = 1;

while ($row = db_fetch_array($result)) {
    $fileUrl = "uploads/" . $row['attachment_path'];
    $piName = !empty($row['name']) ? $row['name'] : 'N/A';
    $piAmount = !empty($row['amount']) ? "₹" . $row['amount'] : 'N/A';
    
    $html .= "<tr>
                <td>{$counter}</td>
                <td><a href='{$fileUrl}' target='_blank'>{$row['attachment_path']}</a></td>
                <td>{$piName}</td>
                <td>{$piAmount}</td>
                <td>{$row['created_at']}</td>
                <td>
                    <a href='{$fileUrl}' target='_blank' class='btn btn-sm btn-info'>
                        <i class='mdi mdi-eye'></i>
                    </a>
                </td>
              </tr>";
    $counter++;
}

if ($counter === 1) {
    $html = "<tr><td colspan='4' class='text-center'>No attachments found</td></tr>";
}

// Directly send raw HTML in JSON
echo json_encode(['html' => $html]);
