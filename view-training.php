<?php

$requestData = $_POST;

$data = isset($requestData['ticket']) ? $requestData['ticket'] : [];
$communications = isset($requestData['ticket']['data']['communications']) ? $requestData['ticket']['data']['communications'] : [];


?>

  <?php if (!empty($data)) { ?>
                <div class="row">
                    <div class="col">
                        <div class="card mb-0">
                            <div class="card-body main-card-body">
                                <div id="chat-box" style="height: 280px" class="overflow-auto p-md-3">
                                    <?php 
                                    foreach ($communications as $comm): 
                                        if ($comm['role_id'] != 1): ?>
                                            <div class="message user">
                                                <div class="messageTime">
                                                    <?= $comm['created_at'] ?>
                                                </div>
                                                <div class="content">
                                                    <div class="user-text">
                                                        <p>
                                                         <?= !empty(trim($comm['message'])) ? strip_tags($comm['message']) : 'No message' ?>
                                                        </p>
                                                        <?php
                                                if (!empty($comm['attachment']) && $comm['attachment'] !== '0') {
                                                $attachments = json_decode($comm['attachment'], true);

                                                if (is_array($attachments)) {
                                                    echo '<div class="attachments mt-2">';

                                                    foreach ($attachments as $file) {

                                                        $filePath = $file['file_path'];
                                                        $cdnUrl   = 'https://d1zhyzsdjajqno.cloudfront.net/' . $filePath;

                                                        // 🔍 Detect extension
                                                        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                                                        // 🖼 IMAGE
                                                        if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {

                                                            echo '<div>
                                                                <img src="'.$cdnUrl.'"
                                                                    onclick="openAttachmentModal(\''.$cdnUrl.'\', \'image\')"
                                                                    style="width:80px;cursor:pointer;margin-right:6px;border-radius:4px;"></div>
                                                            ';

                                                        // 🎥 VIDEO
                                                        } elseif (in_array($ext, ['mp4','webm','ogg'])) {

                                                            echo '
                                                                <div style="display:inline-block;cursor:pointer;margin-right:6px"
                                                                    onclick="openAttachmentModal(\''.$cdnUrl.'\', \'video\')">
                                                                    <video style="width:80px" muted>
                                                                        <source src="'.$cdnUrl.'" type="video/'.$ext.'">
                                                                    </video>
                                                                </div>
                                                            ';

                                                        // 📄 OTHER FILES (xlsx, pdf, doc, etc.)
                                                        } else {

                                                            echo '
                                                                <div>
                                                                    <a href="'.$cdnUrl.'" target="_blank" title="Open file">
                                                                        <i class="mdi mdi-paperclip" style="font-size:15px;"></i>
                                                                        View '.strtoupper($ext).'
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
                                                    <div class="userIcon"><img src="images/user.jpg" alt=""></div>
                                                    
                                                     
                                                </div>
                                            </div>
                                    <?php endif; endforeach; ?>

                                    <?php 
                                    foreach ($communications as $comm): 
                                        if ($comm['role_id'] == 1): ?>
                                            <div class="message bot">
                                                <div class="showTime">
                                                    <?= $comm['created_at'] ?>
                                                </div>
                                                <div class="botMessage">
                                                    <div class="botIcon"><img src="images/wtsUser.png" alt=""></div>
                                                    <div class="bot-text">
                                                        <p><?= !empty(trim($comm['message'])) ? strip_tags($comm['message']) : '' ?></p>
                                                        <?php
                                                if (!empty($comm['attachment']) && $comm['attachment'] !== '0') {
                                                $attachments = json_decode($comm['attachment'], true);

                                                if (is_array($attachments)) {
                                                    echo '<div class="attachments mt-2">';

                                                    foreach ($attachments as $file) {

                                                        $filePath = $file['file_path'];
                                                        $cdnUrl   = 'https://d1zhyzsdjajqno.cloudfront.net/' . $filePath;

                                                        // 🔍 Detect extension
                                                        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                                                        // 🖼 IMAGE
                                                        if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {

                                                            echo '<div>
                                                                <img src="'.$cdnUrl.'"
                                                                    onclick="openAttachmentModal(\''.$cdnUrl.'\', \'image\')"
                                                                    style="width:80px;cursor:pointer;margin-right:6px;border-radius:4px;"></div>
                                                            ';

                                                        // 🎥 VIDEO
                                                        } elseif (in_array($ext, ['mp4','webm','ogg'])) {

                                                            echo '
                                                                <div style="display:inline-block;cursor:pointer;margin-right:6px"
                                                                    onclick="openAttachmentModal(\''.$cdnUrl.'\', \'video\')">
                                                                    <video style="width:80px" muted>
                                                                        <source src="'.$cdnUrl.'" type="video/'.$ext.'">
                                                                    </video>
                                                                </div>
                                                            ';

                                                        // 📄 OTHER FILES (xlsx, pdf, doc, etc.)
                                                        } else {

                                                            echo '
                                                                <div>
                                                                    <a href="'.$cdnUrl.'" target="_blank" title="Open file">
                                                                        <i class="mdi mdi-paperclip" style="font-size:15px;"></i>
                                                                        View '.strtoupper($ext).'
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
                                    <?php endif; endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="query-wrapper">
                            <div class="ticket-status-wrapper">
                                <h3 class="mb-4">Ticket Details</h3>
                                <div class="border-bottom pb-2 mb-2">
                                    <h3>Ticket ID :</h3>
                                    <p><?= $data['ticket_id'] ?? null ?></p>
                                </div>
                                <div class="border-bottom pb-2 mb-2">
                                    <h3>Ticket Subject :</h3>
                                    <p><?= $data['title'] ?? null ?></p>
                                </div>
                                <div class="border-bottom pb-2 mb-2">
                                    <h3>Ticket Status :</h3>
                                    <p><?= $data['query_status_name'] ?? null ?></p>
                                </div>
                                <div class="border-bottom pb-2 mb-2">
                                    <h3>Ticket Raised On :</h3>
                                    <p><?= $data['training_dateTime'] ?? null ?></p>
                                </div>
                                <div class="border-bottom pb-2 mb-2">
                                    <h3>
                                    Ticket Opened By :
                                    <span style="font-size: 14px; font-weight: 500;">
                                        <?= $data['addedByName'] ?? null  ?>
                                    </span>
                                    </h3>
                                </div>
                                <div class="border-bottom pb-2 mb-2">
                                    <h3>
                                    Ticket Closed By :
                                    <span style="font-size: 14px; font-weight: 500;">
                                        <?= $data['closed_by'] ?? null  ?>
                                    </span>
                                    </h3>
                                </div>
                                <div class="border-bottom pb-2 mb-2">
                                    <h3>
                                    Ticket Closed On :
                                    <span style="font-size: 14px; font-weight: 500;">
                                        <?= date('d-M-Y h:i A',strtotime($data['closed_time'])) ?? null  ?>
                                    </span>
                                    </h3>
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




