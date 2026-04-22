<?php include('includes/include.php');

$id           = $_POST['id'];
$user_id      = $_POST['user_id'];
$type_id      = $_POST['type_id'];
$delete_id    = $_POST['id'];

if ($id) {

    $update = db_query("update lead_notification set is_read = 1 where id ='$id' and receiver_id='$user_id'");
}

//for deleting notification
if ($delete_id) {
    $delete_query = db_query("delete from lead_notification where type_id=" . $delete_id);

    $select_log = db_query("select * from lead_modify_log where type = 'Request Status' and lead_id=" . $delete_id);
    $data_lead = db_fetch_array($select_log);

    if (mysqli_num_rows($select_log) > 0) {

        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Request Delete Status','" . $data_lead['previous_name'] . "','LC',now(),'" . $_SESSION['user_id'] . "')");
    }
}

if (isset($_POST['view'])) {
    $query = "SELECT * FROM lead_notification where is_read = 0 and (sender_type='Admin' or sender_type ='Reviewer') and receiver_id =" . $_SESSION['user_id'] . " ORDER BY id DESC";
    $result = db_query($query);

    $output = '';

    if (mysqli_num_rows($result) > 0) {

        foreach ($result as $row) {
            $output .= '
      
       <a href="javascript:void(0)" class="text-reset notification-item" onclick="updateNotificationPartner(' . $row['id'] . ',' . $_SESSION['user_id'] . ',' . $row['type_id'] . ')">
       <div class="media">
                                        <div class="avatar-xs mr-3">
                                            <span class="avatar-title bg-warning rounded-circle font-size-16">
                                                <i class="mdi mdi-message"></i>
                                            </span>
                                        </div>
                                        <div class="media-body">
                                        <h6 class="mt-0 mb-1 font-size-15">' . $row["title"] . '</h6>
                                        <div class="text-muted">
                                            <p class="mb-1 font-size-12">
        Submitted By:' . $row["submitted_by"] . '<br />
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
        <div class="text-muted">
        <p class="mb-1 font-size-12"><a href="#" class="text-bold text-italic">No Notification Found</a></p>
        </div>';
    }

    $count = mysqli_num_rows($result);
    $data = array(
        'notification' => $output,
        'unseen_notification'  => $count
    );

    echo json_encode($data);
}
