<?php
include_once('helpers/DataController.php');

      $usrID = $_POST['user_id'];
      $phoneNo = $_POST['phone'];
      $leadID = $_POST['lead_id'];
      $dataObj = new DataController;

      
      $isInvitationSent = $dataObj->checkInvitationSent($usrID,$leadID/*Lead ID*/,$phoneNo);

      // user_id = ".$usrID." and lead_id = ".$leadID." and
      $isInvitationAccept = db_query("select * from whatsapp_messages where phone = '".$phoneNo."' and from_webhook = 1 ");

      $isInvitationAcceptStatus = mysqli_num_rows($isInvitationAccept);
?>
<div class="modal-body whatsapp-body-message" id="whatsapp-body-message">
 
</div>
      <div class="modal-footer">
            <div class="type-message <?= $isInvitationAcceptStatus > 0 || !$isInvitationSent ? '' : 'd-none'  ?>" id="message-box-text">
        
                    <textarea class="form-control" id="message-box" name="prompt" 
                    placeholder="Type your Message..." 
                    <?= !$isInvitationSent ? 'disabled' : '' ?> required> <?= !$isInvitationSent ? 'Sent Invitation' : "" ?>
                    </textarea>
                    
                    <button type="button" id="submit-btn"
                     data-leadid="<?php echo $leadID; ?>"
                     data-phone="<?php echo $phoneNo; ?>" 
                     data-userid="<?php echo $usrID; ?>" 
                            class="btn btn-send" onClick="return sendMessageButton(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M1.94619 9.31543C1.42365 9.14125 1.41953 8.86022 1.95694 8.68108L21.0431 2.31901C21.5716 2.14285 21.8747 2.43866 21.7266 2.95694L16.2734 22.0432C16.1224 22.5716 15.8178 22.59 15.5945 22.0876L12 14L18 6.00005L10 12L1.94619 9.31543Z"></path></svg>
                    </button>
            </div>

        <div class="type-message <?= $isInvitationAcceptStatus == 0 && $isInvitationSent > 0 ? '' : 'd-none'  ?>"
         id="inivation-sent-message">
            <p class="text-center">We have sent the invitation to the user. Once they reply or accept, you can start the conversation.</p>
        </div>
</div>