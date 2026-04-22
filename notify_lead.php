<?php include('includes/include.php');

$id           = $_POST['id'];
$title        = $_POST['title'];
$company_name = $_POST['company_name'];
$submitted_by = $_POST['submitted_by'];
$sender_type  = $_POST['sender_type'];
$partner_name = $_POST['partner_name'];
$sender_id    = $_POST['sender_id'];
$receiver_id  = @implode(',', $_POST['receiver_id']);

// if ($id) {

//     $insert = saveNotification('lead_notification', $id, $title, $company_name, $submitted_by, $sender_type, $partner_name, $sender_id, $receiver_id);
//     // print_r($insert);die;
//     if ($insert) {
//         echo '* save new notification success';
//     }


// $sql = db_query("select * from orders where id='" . $id . "'");
// $row_data = db_fetch_array($sql);

// $select_query = db_query("select * from lead_notification where type_id='" . $id . "' and sender_id=" . $_SESSION['user_id']);


// if (mysqli_num_rows($select_query) > 0) {

//     $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $id . "','Request Status','" . $row_data['lead_type'] . "','LC',now(),'" . $_SESSION['user_id'] . "')");
// }
// }

if (isset($_POST['view'])) {

    $query = "SELECT * FROM lead_notification where is_read = 0 and sender_type = 'Partner' ORDER BY id DESC";
    $result = db_query($query);

    $output = '';
    if (mysqli_num_rows($result) > 0) {
        //  while ($row = db_fetch_array($result)) {
        foreach ($result as $row) {
            $output .= '
      
       <a class="text-reset notification-item" href="view_order.php?id=' . $row['type_id'] . '">
       <div class="media">
                                            <div class="avatar-xs mr-3">
                                                <span class="avatar-title bg-warning rounded-circle font-size-16">
                                                    <i class="mdi mdi-message"></i>
                                                </span>
                                            </div>
                                            <div class="media-body">
                                            <h6 class="mt-0 mb-1 font-size-15">' . $row["title"] . '</h6><br />
                                            <div class="text-muted">
                                                    <p class="mb-1 font-size-12">
        Submitted By:' . $row["submitted_by"] . '<br />
        Partner Name:' . $row["partner_name"] . '<br />
        Company Name:' . $row["company_name"] . '<br />
        </p>
                                                </div>
                                            </div>
                                        </div>
       </a>
       
       ';
        }
    } else {
        $output .= '
         <a href="#" class="text-reset notification-item"><div class="media">No Notification Found</div></a>';
    }
    $count = mysqli_num_rows($result);
    $data = array(
        'notification' => $output,
        'unseen_notification'  => $count
    );

    echo json_encode($data);
}
