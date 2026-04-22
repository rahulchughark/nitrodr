<?php

$whatsappNotication = db_query("select description,id from whatsapp_notification where mobile = ".'9458642637'." AND seen = 0");
?>

<a href="void:javascript(0)"><img src="images/whatsapp.png"/> <span class="notif-count">
                                <?php echo $whatsappNotication->num_rows; ?></span></a>
                            <div class="whatspp-dropdown-main">
                                <div class="notification-header">
                                    Notifications <div class="close-notif"><i class="fas fa-times"></i></div>
                                </div>
                                <div class="notif-outer">
                                 <?php
                                   while ($notification = db_fetch_array($whatsappNotication)) {
                                 ?>
                                    <a href="#" class="notif-list">
                                        <div class="avtar">
                                            <i class="fab fa-whatsapp"></i>
                                        </div>
                                        <div>
                                            <!-- <h3>Whatsa</h3> -->
                                            <p><?php echo $notification['description']; ?></p>
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