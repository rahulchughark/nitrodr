<?php

      $usrID = $_SESSION['user_id'];
      $phoneNo2 = $euData['eu_mobile'];

      
      $isInvitationSent2 = $dataObj->checkInvitationSent($usrID,$_REQUEST['id']/*Lead ID*/,$phoneNo2);

      // user_id = ".$usrID." and lead_id = ".$_REQUEST['id']." and
      $isInvitationAccept2 = db_query("select * from whatsapp_messages where phone = '".$phoneNo2."' and from_webhook = 1 ");

      $isInvitationAcceptStatus2 = mysqli_num_rows($isInvitationAccept2);
?>
<div class="whatsapp-chat-modal modal fade " id="whatsappModal<?= $counter ?>" tabindex="-1" aria-labelledby="whatsappModal<?= $counter ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="whatsappModal">Whatsapp Chat </h5>
        <p class="d-none chatting-phone-number"></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      
      <div class="modal-body whatsapp-message-box" id="whatsapp-message-box">       
      </div>
      
      <div class="modal-footer">
        
            <div class="type-message <?= $isInvitationAcceptStatus2 > 0 || !$isInvitationSent2 ? '' : 'd-none'  ?>" id="message-box-text">
        
                    <textarea class="form-control" id="query-text<?= $counter ?>" name="prompt" placeholder="Type your Message..." 
                    <?= !$isInvitationSent2 ? 'disabled' : '' ?> required> <?= !$isInvitationSent2 ? 'Sent Invitation' : "" ?></textarea>
                    
                    <button type="button" id="submit-btn" data-counter="<?= $counter ?>" data-leadid="<?php echo $_REQUEST['id']; ?>" class="btn btn-send whatsApp-messages-btn w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M1.94619 9.31543C1.42365 9.14125 1.41953 8.86022 1.95694 8.68108L21.0431 2.31901C21.5716 2.14285 21.8747 2.43866 21.7266 2.95694L16.2734 22.0432C16.1224 22.5716 15.8178 22.59 15.5945 22.0876L12 14L18 6.00005L10 12L1.94619 9.31543Z"></path></svg>
                    </button>
            </div>


        <div class="type-message <?= $isInvitationAcceptStatus2 == 0 && $isInvitationSent2 > 0 ? '' : 'd-none'  ?>" id="inivation-sent-message">
            <p class="text-center">We have sent the invitation to the user. Once they reply or accept, you can start the conversation.</p>
        </div>
        
      </div>
    </div>
  </div>
</div>
