<?php
exit;
include("includes/include.php");
include_once('helpers/DataController.php');
$dataObj = new DataController;
$userID = $_SESSION['user_id'];

// This condition retrieves the count only and then exits without further processing.
if($_REQUEST['isCountOnly']){
   echo $dataObj->getWhatsappNotificationCount($_REQUEST['phone']);
   exit; 
}




//$whatsappNotication = db_query("select description,id from whatsapp_notification where mobile = ".'4521632541'." AND seen = 0");
// $whatsappNotication = db_query("
//     select wn.description, wn.id, o.id AS order_id, o.school_name
//     FROM whatsapp_notification wn
//     JOIN orders o ON wn.mobile = o.eu_mobile
//     WHERE o.created_by = $userID AND wn.seen = 0 GROUP BY wn.mobile");

$whatsappNotication = db_query("
    select wn.description, wn.id, o.id AS order_id, o.school_name
    FROM whatsapp_notification wn
    LEFT JOIN order_important_person AS oi ON wn.mobile = oi.eu_mobile
    LEFT JOIN orders o 
        ON (o.id = oi.order_id OR wn.mobile = o.eu_mobile)
    WHERE (o.created_by = $userID)
      AND wn.seen = 0
    GROUP BY wn.mobile, oi.eu_mobile
");
    

// SELECT wn.*
// FROM whatsapp_notification wn
// JOIN orders o ON wn.mobile = o.eu_mobile
// WHERE o.user_id = 6
// GROUP BY wn.id;

?>


<a href="void:javascript(0)"><img src="images/whatsapp.png"/> <span class="notif-count">
                                <?php echo $whatsappNotication->num_rows; ?></span></a>
                            <div class="whatspp-dropdown-main">

                                <div class="notification-header">
                                    Notifications <div onclick="return closeNotificationBar()" class="close-notif"><i class="fas fa-times"></i></div>
                                </div>
                                
                                <div class="notif-outer">
                                 <?php
                                   while ($notification = db_fetch_array($whatsappNotication)) {
                                 ?>
                                    <a onclick="return markNotificationAsSeen(<?php echo $notification['id'] ?>, <?php echo $notification['order_id'] ?>)"
                                     class="notif-list">
                                        <div class="avtar">
                                            <i class="fab fa-whatsapp"></i>
                                        </div>
                                        <div>
                                            <!-- <h3>Whatsa</h3> -->
                                            <p> <?php echo $notification['school_name']." - ".$notification['description']; ?></p>
                                        </div>
                                    </a>
                                   <?php } ?>

                                   <?php if(!$whatsappNotication->num_rows){ ?>
                                    <a href="#" class="notif-list">
                                        
                                        <div>
                                            <!-- <h3>Whatsa</h3> -->
                                            <p>No Nofitication Yet</p>
                                        </div>
                                    </a>
                                <?php } ?>
                                </div>
                            </div>