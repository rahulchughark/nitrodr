
var globalPhone = null;
var globalLeadID = null;
var globalUserID = null;



function openWhatsappModal(element) {
    const leadId = element.getAttribute('data-leadid');
    const phone = element.getAttribute('data-phone');
    const userId = element.getAttribute('data-userid');
    loadModalHtmlBox(leadId,phone,userId);
    markNotificationAsSeen(null,null,phone);
    fetchWhatsappNotification();
    $(".not-count-"+phone).html("0");

    // $('#whatsappModal-static').modal('show');

}


// setInterval(function() {
//     console.log("This message prints every 1 second",globalPhone);
// }, 1000); // 1000 milliseconds = 1 second



function loadModalHtmlBox(leadId,phone,userId){

      $.ajax({
        url: 'whatsapp_popup.php',
        method: 'POST',
        data: { lead_id: leadId, phone: phone, user_id:userId },
        success: function(response) {
            $('#modal-container-box').html(response);
            $('#whatsappModal-static').modal('show');
            getWhatsAppMessages(phone,leadId);
        },
        error: function() {
            alert('Failed to load WhatsApp popup.');
        }
    });
}



$(".whatsapp-btn-icon").on('click',function(){
    var phone = $(this).data('phone');
    var leadID = $(this).data('leadid');

    // Set global variables
    globalPhone = phone;
    globalLeadID = leadID;
    $(".chatting-phone-number").html(phone);
    
    getWhatsAppMessages(phone,leadID);   
    
});


// let audio = new Audio('assets/not.mp3');
// let soundEnabled = false;

// Preload audio (won’t play yet)
// audio.load();

// Listen for first user interaction
// document.addEventListener('click', () => {
//     if (!soundEnabled) {
//         audio.play().then(() => {
//             soundEnabled = true;
//             audio.pause(); // Pause immediately to avoid sound
//             console.log("Sound unlocked and ready.");
//         }).catch(err => {
//             console.error("Audio unlock failed:", err);
//         });
//     }
// }, { once: true });


// Enable pusher logging - don't include this in production
   

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
  
    getWhatsAppMessages(globalPhone,globalLeadID); 
    fetchWhatsappNotification();  
    markNotificationAsSeen(null,null,globalPhone);
    fetchWhatsappNotification();
    getWhatsAppNotificationCount(data['mobile']);
   
    
    });


     

     fetchWhatsappNotification();
    //   function fetchWhatsappNotification(){
    //     $('#whatsapp-notification').load('whatsapp_notification.php');
    //   }
      
      function showNotificationBar(){
            $('.whatspp-dropdown-main').addClass('active');
        }

      function closeNotificationBar(){
            $('.whatspp-dropdown-main').hide();
        }

function getWhatsAppMessages(phoneNumber,leadID){
    $(".whatsapp-message-box").html("");
    $.ajax({
        type: 'POST',
        url: 'whatsapp_messages.php',
        data: {
            phone: phoneNumber,
            leadID:leadID
        },
        success: function(response) {
            // console.log(response);
           $(".whatsapp-body-message").html(response);
        }
    });
}

// $(".whatsApp-messages-btn").on("click",function(){
//     var counter = $(this).data("counter");
//     var messageBox = counter == undefined ? $("#query-text").val() : $("#query-text"+counter).val();
//     var sendTo = $(".chatting-phone-number").html();
//     var leadID = $(this).data('leadid');



//     $.ajax({
//         type: 'POST',
//         url: 'whatsapp_outgoing.php',
//         data: {
//             message: messageBox,
//             leadID: leadID,
//             sendTo: sendTo
//         },
//         success: function(response) {
//             let resp = JSON.parse(response);
//             if(resp.status == 500){
//                 alert("Something went wrong");
//                 return;
//             }
//             getWhatsAppMessages(sendTo,leadID);
//             $("#query-text").val("");
//             if(resp.is_invite){
//                 $("#message-box-text").addClass('d-none');
//                 $("#inivation-sent-message").removeClass('d-none');
//             }else{
//                 $("#message-box-text").removeClass('d-none');
//                 $("#inivation-sent-message").addClass('d-none');
//             }
            
//         }
//     });
 

// }); 


function sendMessageButton(element){
    const leadId = element.getAttribute('data-leadid');
    const phone = element.getAttribute('data-phone');
    const userId = element.getAttribute('data-userid');
    
    globalPhone = phone;
    globalLeadID = leadId;
    globalUserID = userId;

    var messageBox = $("#message-box").val();



        $.ajax({
        type: 'POST',
        url: 'whatsapp_outgoing.php',
        data: {
            message: messageBox,
            leadID: leadId,
            sendTo: phone
        },
        success: function(response) {
            let resp = JSON.parse(response);
            if(resp.status == 500){
                alert("Something went wrong");
                return;
            }
            
            loadModalHtmlBox(leadId,phone,userId);

            // $("#query-text").val("");
            // if(resp.is_invite){
            //     $("#message-box-text").addClass('d-none');
            //     $("#inivation-sent-message").removeClass('d-none');
            // }else{
            //     $("#message-box-text").removeClass('d-none');
            //     $("#inivation-sent-message").addClass('d-none');
            // }
            
        }
    });

}



// $(".whatsApp-messages-btn").on("click",function(){
//     var counter = $(this).data("counter");
//     var messageBox = counter == undefined ? $("#query-text").val() : $("#query-text"+counter).val();
//     var sendTo = $(".chatting-phone-number").html();
//     var leadID = $(this).data('leadid');



//     $.ajax({
//         type: 'POST',
//         url: 'whatsapp_outgoing.php',
//         data: {
//             message: messageBox,
//             leadID: leadID,
//             sendTo: sendTo
//         },
//         success: function(response) {
//             let resp = JSON.parse(response);
//             if(resp.status == 500){
//                 alert("Something went wrong");
//                 return;
//             }
//             getWhatsAppMessages(sendTo,leadID);
//             $("#query-text").val("");
//             if(resp.is_invite){
//                 $("#message-box-text").addClass('d-none');
//                 $("#inivation-sent-message").removeClass('d-none');
//             }else{
//                 $("#message-box-text").removeClass('d-none');
//                 $("#inivation-sent-message").addClass('d-none');
//             }
            
//         }
//     });
 

// }); 


// modal close then set null value
$('#whatsappModal-static').on('hidden.bs.modal', function () {
    globalPhone = null;
    globalLeadID = null;
    globalUserID = null;
});


function getWhatsAppNotificationCount(phoneNumber){
    return ;
    // $(".whatsapp-message-box").html("");
    // $.ajax({
    //     type: 'POST',
    //     url: 'whatsapp_notification.php',
    //     data: {
    //         phone: phoneNumber,
    //         isCountOnly:true
    //     },
    //     success: function(response) {
    //         $(".not-count-"+phoneNumber).html(response);
    //         // console.log("count data :)",response);
    //     //    $(".whatsapp-body-message").html(response);
    //     }
    // });
}