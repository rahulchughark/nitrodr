<?php include('includes/include.php');

$date = date('Y-m-d');
$cur_time = date('H:i');
$followUp_data = db_query("select * from follow_up_notification where status='Not Started' and user_id=" . $_SESSION['user_id']);

$i = 0;
if (mysqli_num_rows($followUp_data)) {

    while ($data = db_fetch_array($followUp_data)) {
        $follow_up_time[] = $data['follow_up_time'];
        $follow_up_date[] = $data['follow_up_date'];
        //  $time = in_array($cur_time,$follow_up_time);
    }
    //print_r($follow_up_time[$i]);
    if (in_array($date, $follow_up_date) && in_array($cur_time, $follow_up_time)) { ?>
        <script>
            $(function() {
                $.ajax({
                    type: 'POST',
                    url: '#',

                    success: function(data) {
                        window.location.href = 'header.php'
                    }
                });
            });
        </script>
<?php $i++;
    } else {
        echo "<pre>";
        echo "error";
    }
} else {
    echo "<pre>";
    echo "not added";
} ?>