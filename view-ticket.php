<style>
    .ticket-row .label {
    font-weight: 700; /* bold */
}
</style>

<?php

$requestData = $_POST;

$data = isset($requestData['ticket']) ? $requestData['ticket'] : [];
$communications = isset($requestData['ticket']['data']['communications']) ? $requestData['ticket']['data']['communications'] : [];

// echo "<pre>";
// print_r($data);
// exit;
?>

  <?php if (!empty($data)) { ?>
                <div class="row">
                    <div class="col">
                        <div class="card mb-0">
                            <div class="card-body main-card-body">
                               <div id="chat-box" style="height: 280px" class="overflow-auto p-md-3">

<?php foreach ($communications as $comm): ?>

    <?php if ($comm['role_id'] != 1): ?>
        <!-- USER MESSAGE -->
        <div class="message user">
            <div class="messageTime">
                <?= date("d M Y, h:i A", strtotime($comm['t_isCreated'])) ?>
            </div>

            <div class="content">
                <div class="user-text">
                    <p><?= !empty(trim($comm['t_message'])) 
                        ? strip_tags($comm['t_message']) 
                        : 'No message' ?></p>
                         <?php
                if (!empty($comm['t_attach']) && $comm['t_attach'] !== '0') {

                    $attachments = json_decode($comm['t_attach'], true);
                    $extTypes    = json_decode($comm['extType'], true);

                    if (is_array($attachments)) {
                        echo '<div class="attachments mt-2">';

                        foreach ($attachments as $i => $file) {

                            $cdnUrl = 'https://d1zhyzsdjajqno.cloudfront.net/' . $file['fileName'];
                            $type   = $extTypes[$i]['extnType'] ?? 'file';
                          

                            if ($type === 'image') {
                                 echo '<div>
                                            <img src="'.$cdnUrl.'"
                                                onclick="openAttachmentModal(\''.$cdnUrl.'\', \'image\')"
                                                style="width:80px;cursor:pointer;margin-right:6px;border-radius:4px;">
                                                </div>
                                        ';
                            } elseif ($type === 'mp4' || $type === 'video') {
                                 echo '
                                        <div style="display:inline-block;cursor:pointer;margin-right:6px"
                                            onclick="openAttachmentModal(\''.$cdnUrl.'\', \'video\')">
                                            <video style="width:80px" muted>
                                                <source src="'.$cdnUrl.'">
                                            </video>
                                        </div>
                                    ';
                            } else {
                                echo '<div>
                                        <a href="'.$cdnUrl.'" target="_blank" title="Open file">
                                            <i class="mdi mdi-paperclip" style="font-size:15px;">View '.$type.'</i>
                                        </a>
                                        </div>
                                    ';
                            }
                        }

                        echo '</div>';
                    }
                }
                ?>
                </div>
                <div class="userIcon">
                    <img src="images/user.jpg" alt="">
                </div>
                
                
            </div>
        </div>

    <?php else: ?>
        <!-- BOT MESSAGE -->
        <div class="message bot">
            <div class="showTime">
                <?= date("d M Y, h:i A", strtotime($comm['t_isCreated'])) ?>
            </div>

            <div class="botMessage">
                <div class="botIcon">
                    <img src="images/wtsUser.png" alt="">
                </div>

                <div class="bot-text">
                   <p> <?= !empty(trim($comm['t_message'])) 
                        ? strip_tags($comm['t_message']) 
                        : '' ?></p>
                        <?php
            if (!empty($comm['t_attach']) && $comm['t_attach'] !== '0') {

                $attachments = json_decode($comm['t_attach'], true);
                $extTypes    = json_decode($comm['extType'], true);

                if (is_array($attachments)) {
                    echo '<div class="attachments mt-2">';

                    foreach ($attachments as $i => $file) {

                        $cdnUrl = 'https://d1zhyzsdjajqno.cloudfront.net/' . $file['fileName'];
                        $type   = $extTypes[$i]['extnType'] ?? 'file';

                        if ($type === 'image') {
                            echo '<div>
                                    <img src="'.$cdnUrl.'"
                                        onclick="openAttachmentModal(\''.$cdnUrl.'\', \'image\')"
                                        style="width:80px;cursor:pointer;margin-right:6px;border-radius:4px;">
                                        </div>
                                ';
                        } elseif ($type === 'mp4' || $type === 'video') {
                            echo '<div style="display:inline-block;cursor:pointer;margin-right:6px"
                                        onclick="openAttachmentModal(\''.$cdnUrl.'\', \'video\')">
                                        <video style="width:80px" muted>
                                            <source src="'.$cdnUrl.'">
                                        </video>
                                    </div>
                                ';
                        } else {
                              echo '<div>
                                    <a href="'.$cdnUrl.'" target="_blank" title="Open file">
                                        <i class="mdi mdi-paperclip" style="font-size:15px;">View '.$type.'</i>
                                    </a>
                                    </div>
                                ';
                        }
                    }

                    echo '</div>';
                }
            }
            ?>

                </div>

            </div>

           
            
        </div>

    <?php endif; ?>

<?php endforeach; ?>

</div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="query-wrapper">
                            <div class="ticket-status-wrapper">
                                <h3 class="mb-4">Ticket Details</h3>

                                <div class="border-bottom pb-2 mb-2">
                                    <div class="ticket-row">
                                        <span class="label ">Ticket ID :</span>
                                        <span class="value"><?= $data['id'] ? $data['id'] : 'NA' ?></span>
                                    </div>
                                </div>

                                <div class="border-bottom pb-2 mb-2">
                                    <div class="ticket-row">
                                        <span class="label">Ticket Subject :</span>
                                        <span class="value" title="<?= $data['subject'] ?? '' ?>">
                                            <?= $data['subject'] ? $data['subject'] : 'NA' ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="border-bottom pb-2 mb-2">
                                    <div class="ticket-row">
                                        <span class="label">Ticket Status :</span>
                                        <span class="value"><?= $data['status_name'] ? $data['status_name'] : 'NA' ?></span>
                                    </div>
                                </div>

                                <div class="border-bottom pb-2 mb-2">
                                    <div class="ticket-row">
                                        <span class="label">Ticket Raised On :</span>
                                        <span class="value">
                                            <?= $data['created_date'] ? date('d F H:i a', strtotime($data['created_date'])) : 'NA'; ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="border-bottom pb-2 mb-2">
                                    <div class="ticket-row">
                                        <span class="label">Raised By :</span>
                                        <span class="value"><?= $data['query_raised_by'] ? $data['query_raised_by'] : 'NA' ?></span>
                                    </div>
                                </div>

                                <div class="border-bottom pb-2 mb-2">
                                    <div class="ticket-row">
                                        <span class="label">Closed By :</span>
                                        <span class="value">
                                            <?= $data['closed_by_name'] ? $data['closed_by_name'] : 'NA'; ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="border-bottom pb-2 mb-2">
                                    <div class="ticket-row">
                                        <span class="label">Closed On :</span>
                                        <span class="value">
                                            <?= $data['closed_time'] ? date('d M-Y, h:i:s', strtotime($data['closed_time'])) : 'NA'; ?>
                                        </span>
                                    </div>
                                </div>
                                
                                
                            </div>
                        </div>

                    </div>


                </div>
            <?php } else { ?>
                <div class="no-data-container text-center p-5">
                    <div class="no-data-card">
                        <img src="images/no-data.png" alt="No Data" class="no-data-img">
                        <h4 class="mt-3">No Data Found</h4>
                        <p>There is no data to show you right now</p>
                    </div>
                </div>
            <?php } ?>




