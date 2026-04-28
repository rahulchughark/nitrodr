


<div class="whatsapp-chat-modal modal fade" id="whatsappModal-static" tabindex="-1" aria-labelledby="whatsappModal-static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="whatsappModal-static">Whatsapp Chat</h5>
        <p class="d-none chatting-phone-number"></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div id="modal-container-box">
      
      
      </div>



    </div>
  </div>
</div>




  <!-- footer -->
  <!-- ============================================================== -->
  <footer class="footer">
      <div class="container-fluid">
          <div class="row">
              <div class="col-sm-12 text-center">
                  <script>
                      document.write(new Date().getFullYear())
                  </script> © Nitro DR
              </div>
              <!-- <div class="col-sm-6">
                  <div class="text-sm-right d-none d-sm-block">
                      ARK Infosolutions

                  </div>
              </div> -->
          </div>
      </div>
  </footer>
  </div>
  <!-- end main content-->

  </div>
  <!-- END layout-wrapper -->
  <!-- ============================================================== -->

  <!-- JAVASCRIPT -->
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/metisMenu.min.js"></script>
  <script src="js/simplebar.min.js"></script>
  <script src="js/waves.min.js"></script>
  <script src="js/bootstrap-table.min.js"></script>
  <script src="js/bootstrap-table.ints.js"></script>
  <!-- Required datatable js -->
  <script src="js/jquery.dataTables.min.js"></script>
  <script src="js/dataTables.bootstrap4.min.js"></script>
  <!-- Buttons examples -->
  <script src="js/dataTables.buttons.min.js"></script>
  <script src="js/buttons.bootstrap4.min.js"></script>
  <script src="js/jszip.min.js"></script>
  <script src="js/pdfmake.min.js"></script>
  <script src="js/vfs_fonts.js"></script>
  <script src="js/buttons.html5.min.js"></script>
  <script src="js/buttons.print.min.js"></script>
  <script src="js/buttons.colVis.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/tableHeadFixer.js"></script>
  <script src="css/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script src="js/select2.min.js"></script>
  <!-- Ion Range Slider-->
  <script src="js/ion.rangeSlider.min.js"></script>
  <script src="js/bootstrap-colorpicker.min.js"></script>
  <script src="js/jquery.inputmask.bundle.min.js"></script>

  <!-- Range slider init js-->
  <script src="js/range-sliders.init.js"></script>
  <script src="js/form-advanced.init.js"></script>
  <script src="js/app.js"></script>
  <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
  <script type="text/javascript">
     Pusher.logToConsole = true;

    var pusher = new Pusher('b2125d64edf5e1a092e2', {
      cluster: 'ap2'
    });
  </script>
  <script src="js/whatsapp-chatting.js"></script>
  <script src="js/jquery.slimscroll.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>

  <script src="assets/plugins/sweetalert/sweetalert.min.js"></script>

  <script>
    $.extend(true, $.fn.dataTable.defaults, {
    language: {
        processing: `
            <div class="loader-outer"><span class="loader"></span></div>
        `
    },
    processing: true
});
</script>

  <script>
      ! function(window, document, $) {
          "use strict";
          $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(), $(".skin-square input").iCheck({
              checkboxClass: "icheckbox_square-green",
              radioClass: "iradio_square-green"
          }), $(".touchspin").TouchSpin(), $(".switchBootstrap").bootstrapSwitch();

      }(window, document, jQuery);
  </script>
  <!-- ============================================================== -->
  <!-- Style switcher -->
  <!-- ============================================================== -->

  <script>
      // When the user scrolls down 20px from the top of the document, show the button
      window.onscroll = function() {
          if (typeof scrollFunction === 'function') {
              scrollFunction();
          }
      };

      // When the user clicks on the button, scroll to the top of the document
      function topFunction() {
          document.body.scrollTop = 0;
          document.documentElement.scrollTop = 0;
      }
  </script>

  <script src="js/jquery.floatThead.min.js"></script>
  <script src="js/jquery.floatThead-slim.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
  <script type="text/javascript">
  // Default Configuration
    $(document).ready(function() {
      toastr.options = {
      'closeButton': true,
      'debug': false,
      'newestOnTop': false,
      'progressBar': false,
      'positionClass': 'toast-top-right',
      'preventDuplicates': false,
      'showDuration': '1000',
      'hideDuration': '1000',
      'timeOut': '5000',
      'extendedTimeOut': '1000',
      'showEasing': 'swing',
      'hideEasing': 'linear',
      'showMethod': 'fadeIn',
      'hideMethod': 'fadeOut',
    };

    });

  // Toast Type

    //toastr.success('You clicked Success toast');
    //toastr.info('You clicked Info toast')
    //toastr.error('You clicked Error Toast')
    //toastr.warning('You clicked Warning Toast')


  // Toast Position
    $('#position').click(function(event) {
      var pos = $('input[name=position]:checked', '#positionForm').val();
      toastr.options.positionClass = "toast-" + pos;
      toastr.options.preventDuplicates = false;
      toastr.info('This sample position', 'Toast Position')
    });
  </script>
  <script>
      $(function() {
          $('table.demo2').floatThead({
              position: 'fixed',
              scrollingTop: 65
          });
          $('table.demo2 thead').css('background', '#ECEFF1');
      });
  </script>
  <script>
      function select_license() {
          $('#licence_type').modal('show');
      }
  </script>
  <script>
   
      $('input[name="license_type_radio"]').on('click', function() {
          //alert('clicked');
          window.location = $(this).val();
      });

      function select_product() {
          $('#product_data').modal('show');
          //$('.modal-footer').hide();
      }

      function select_product_intern() {
          $('#product_data_intern').modal('show');
          //$('.modal-footer').hide();
      }

      function select_product_dvr() {
          $('#product_dvr').modal('show');
          //$('.modal-footer').hide();
      }

      $('#product_of_interest').on('change', function() {
          var poiID = $(this).val();
          $('#product').html('<option value="">---Select---</option>');
          $('#product').prop('disabled', true);
          $('#productType').html('<div class="col-md-12" style="padding:0"><label class="control-label">Sub Product</label><select class="form-control" disabled><option value="">---Select---</option></select></div>');
          $('#productDescription').html('<label class="control-label">Description</label><select class="form-control" disabled><option value="">---Select---</option></select>');
          
          if (poiID) {
              $.ajax({
                  type: 'POST',
                  url: 'ajaxProduct.php',
                  data: { poi_id: poiID },
                  success: function(response) {
                      $('#product').html(response);
                      $('#product').prop('disabled', false);
                  },
                  error: function() {
                      $('#product').html('<option value="">Error loading products</option>');
                  }
              });
          }
      });

      $('#product').on('change', function() {

          var productID = $(this).val();
          $('#productDescription').html('<label class="control-label">Description</label><select class="form-control" disabled><option value="">---Select---</option></select>');
          
          if (productID) {
              $.ajax({
                  type: 'POST',
                  url: 'ajaxProduct.php',
                  data: 'product=' + productID,
                  success: function(response) {
                      $('.modal-footer').show();
                      $('#productType').html(response);
                  },
                  error: function() {
                      $('#productType').html('There was an error!');
                  }
              });
          } else {
              $('#productType').html('<div class="col-md-12" style="padding:0"><label class="control-label">Sub Product</label><select class="form-control" disabled><option value="">---Select---</option></select></div>');
          }
      });

      $(document).on('change', '#product_type', function() {
          var pivotID = $(this).val();
          $('#productDescription').html('<label class="control-label">Description</label><select class="form-control" disabled><option value="">---Select---</option></select>');
          if (pivotID) {
              $.ajax({
                  type: 'POST',
                  url: 'ajax_common.php',
                  data: { pivot_id: pivotID },
                  success: function(response) {
                      $('#productDescription').html(response);
                  },
                  error: function() {
                      $('#productDescription').html('<label class="control-label">Description</label><select class="form-control" disabled><option value="">---Select---</option></select>');
                  }
              });
          }
      });

    $('#submit_btn').on('click', function() {
        //alert('clicked');
        if (($('#product').val() == " ") || ($('#product').val() == undefined)) {
            swal('Select product type!!');
            return true;
        }

        if (($('#product_type').val() == "") || ($('#product_type').val() == undefined)) {
        var product_type = "";
        }else{
          var product_type = $('#product_type').val();
        }
        var data = $('.product_data').serialize();
        var product = $('#product').val();
        var product_of_interest = $('#product_of_interest').val() || '';
        var interestQuery = '&product_of_interest=' + encodeURIComponent(product_of_interest);
        var description = $('select[name="description"]').val() || '';
        var descriptionQuery = '&description=' + encodeURIComponent(description);
        var license_type = $('#license_type').val() || '';
        var licenseQuery = '&license_type=' + encodeURIComponent(license_type);
        var renewal_type = $('#renewal_type').val() || '';
        var renewalQuery = '&renewal_type=' + encodeURIComponent(renewal_type);
      
        if(product){
            var user_type = "<?=$_SESSION['user_type']?>";
            if(user_type == 'CLR'){
                            window.location = 'add_leads.php?lead=' + product + '&type=' + product_type + interestQuery + '&description=' + description + licenseQuery + renewalQuery;
            }else if(user_type == 'MNGR' || user_type == 'USR'){
                            window.location = 'add_order.php?lead=' + product + '&type=' + product_type + interestQuery + '&description=' + description + licenseQuery + renewalQuery;
            }else{
                            window.location = 'add_leads.php?lead=' + product + '&type=' + product_type + interestQuery + '&description=' + description + licenseQuery + renewalQuery;
            }

        }

        });

      //for dvr
      $('#productDVR').on('change', function() {

          var productID = $(this).val();
          //alert(productID);
          if (productID) {
              $.ajax({
                  type: 'POST',
                  url: 'ajaxProduct.php',
                  data: 'product_dvr=' + productID,
                  success: function(response) {
                      $('.modal-footer').show();
                      $('#productDVRType').html(response);

                  },
                  error: function() {
                      $('#productDVRType').html('There was an error!');
                  }
              });
          } else {
              $('#productDVRType').html('<option value="" style="color:red">Select product first</option>');
          }
      });

            $('#submit_dvr').on('click', function() {
      //alert('clicked');
          if (($('#productDVR').val() == "") || ($('#productDVR').val() == undefined)) {
              swal('Select product type!!');
              return true;
          }
          if (($('#product_type_dvr').val() == "") || ($('#product_type_dvr').val() == undefined)) {
            var product_type = "";
          }else{
            var product_type = $('#product_type_dvr').val();
          }
          var data = $('.product_dvr').serialize();
          var product = $('#productDVR').val();
          
          // alert(product_type);
          if(product){
              window.location = 'add_dvr.php?lead=' + product + '&type=' + product_type;
          }


          });

      //Intern form

      $('#product_intern').on('change', function() {

        var productID = $(this).val();
          //alert(productID);
          if (productID) {
              $.ajax({
                  type: 'POST',
                  url: 'ajaxProduct.php',
                  data: 'product=' + productID,
                  success: function(response) {
                      $('.modal-footer').show();
                      $('#productType').html(response);
                      // $('.selectpicker').selectpicker({
                      //     //style: 'btn-primary',
                      //     //size: 2
                      // });

                  },
                  error: function() {
                      $('#productTypeIntern').html('There was an error!');
                  }
              });
          } else {
              $('#productTypeIntern').html('<option value="" style="color:red">Select product first</option>');
          }
      });

      $('#submit_btn_intern').on('click', function() {
        //   alert('clicked');
          if (($('#product_intern').val() == " ") || ($('#product_intern').val() == undefined)) {
            swal('Select product type!!');
            return true;
        }

        if (($('#product_type').val() == "") || ($('#product_type').val() == undefined)) {
        var product_type = "";
        }else{
          var product_type = $('#product_type').val();
        }
        var data = $('.product_data_intern').serialize();
        var product = $('#product_intern').val();
        //alert(data);
        if(product){
            var user_type = "<?=$_SESSION['user_type']?>";
              window.location = 'add_iss_leads.php?lead=' + product + '&type=' + product_type;
        }


      });

      function show_followup() {
          $.ajax({
              type: 'POST',
              url: 'follow_upReminder.php',
              success: function(response) {
                  $("#selfReview").html();
                  $("#selfReview").html(response);
                  $('#selfReview').modal('show');
              }
          });
      }

      // function show_selfreview() {
      //     $.ajax({
      //         type: 'POST',
      //         url: 'self_review.php',
      //         success: function(response) {
      //             $("#selfReview").html();
      //             $("#selfReview").html(response);
      //             $('#selfReview').modal('show');
      //         }
      //     });
      // }
  </script>
  

  <script type="text/javascript">

      $(document).ready(function() {
          $('#filter-box').click(function(event) {
              event.stopPropagation();
              $('#filter-container').toggle();
          });

          $(document).click(function(event) {
              var formContainer = $("#filter-container");
              var btnLink = $("#filter-box");
              if (formContainer.has(event.target).length === 0 && btnLink.has(event.target).length === 0) {
                  formContainer.hide();
              }
          });
      });


        function change_status(leadStatus,pid,ids){
            $.ajax({
                    type: 'POST',
                    url: 'general_changes.php',
                    data: {
                        leadStatus:leadStatus,
                        pid:pid,
                        type:'status'
                    },
                    success: function(res) {
                            var res = $.trim(res);
                            if (res == 'success') {
                                swal({
                                    title: "Done!",
                                    text: "Status changed Successfully.",
                                    type: "success"
                                }, function() {
                                    $('#myModal1').modal('hide');
                                    $('#leads').DataTable().ajax.reload();
                                });

                            } else {
                                swal({
                                    title: "Error!",
                                    text: res,
                                    type: "error"
                                }, function() {

                                });
                            }
                        }
                });
        }



    //      function markNotificationAsSeen(id, order_id) {
    //       if (!id) {
    //           console.error("Invalid ID passed to markNotificationAsSeen");
    //           return;
    //       }

    //       console.log("Function called successfully with ID:", id);

    //       $.ajax({
    //           url: 'update_seen_status.php',
    //           method: 'POST',
    //           data: { notification_id: id },
    //           success: function(response) {
    //              if(response == 1){
    //               location.href = "view_order.php?id=" + order_id;
    //              }
    //               console.log('Notification seen status updated:', response);
    //           },
    //           error: function(xhr, status, error) {
    //               console.error('AJAX error:', error);
    //           }
    //       });
    //   }


    function markNotificationAsSeen(id = null, order_id = null, phone_number = null) {
    if (!id && !phone_number) {
        console.warn("Either notification_id or phone_number must be provided.");
        return;
    }

    console.log("Function called with:", { id, order_id, phone_number });

    $.ajax({
        url: 'update_seen_status.php',
        method: 'POST',
        data: {
            notification_id: id,
            phone_number: phone_number
        },
        success: function(response) {
            if (response == 1 && order_id) {
                location.href = "view_order.php?id=" + order_id;
            }
            console.log('Notification seen status updated:', response);
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
        }
    });
}


      // $(document).ready(function () {     
      
      // });
      // fetchWhatsappNotification();
      // function fetchWhatsappNotification(){
      //   $('#whatsapp-notification').load('whatsapp_notification.php');
      // }
      
      // function showNotificationBar(){
      //       $('.whatspp-dropdown-main').addClass('active');
      //   }

      // function closeNotificationBar(){
      //       $('.whatspp-dropdown-main').hide();
      //   }


      function whatsappMessageBoxModal(index){
        
        const modal = $('#whatsappModal'+index);
        modal.addClass('custom-backdrop');
 
            modal.modal({
                backdrop: false,
                show: false
            });

      }

     
     function updateUserWiseKRA(e, cls, id, key_subject, type, user_id) {
        const isChecked = e.target.checked; // Determine if the checkbox is checked

        // Combine class name and ID to get the full selector (e.g., ".some-class-5")
        const targetClass = `.${cls}${id}`;
        const element = document.querySelectorAll(targetClass); // Select the element

     
        if (element) {
          
            if (isChecked) {
                // element.classList.remove("disabled"); // Add 'disabled' class
               element.forEach(el => {
                console.log(el.classList);
                  el.classList.remove('disabled');
              });
               updateStatucKRATargetValue(id,key_subject,type,user_id,isChecked);
            } else {        
                // element.classList.add("disabled"); // Remove 'disabled' class
               element.forEach(el => {
                el.classList.add('disabled');
             });
               updateStatucKRATargetValue(id,key_subject,type,user_id,isChecked);
            }
        } else {
        
            // Element not found — optional debug log
            // console.warn(`Element with class ${targetClass} not found`);
        }
    }


    function updateStatucKRATargetValue(id, key_subject, type, user_id,status) {
      $.ajax({
          url: 'ajax_update_kra_target.php', // Change to your actual endpoint
          type: 'POST',
          data: {
              id: id,
              key_subject: key_subject,
              type: type,
              user_id: user_id,
              status:status,
              onlyStatusUpdate:true
          },
          success: function(response) {
              console.log("Status updated:", response);
              // Optionally show success feedback to user
          },
          error: function(xhr, status, error) {
              console.error("Error updating status:", error);
              // Optionally show error message
          }
      });
}

// let kraTargetTimeout = null;
//       function updateKRATargetValue(event, keyID, keySubject, type,userID) {
//       const value = event.target.value;

//       $.ajax({
//           url: 'ajax_update_kra_target.php', // Replace with your PHP handler
//           type: 'POST',
//           data: {
//               value: value,
//               key_id: keyID,
//               key_subject: keySubject,
//               type: type,
//               user_id:userID
//           },
//           success: function(response) {
//               console.log('Server response:', response);
//               // Optionally show success feedback to user
//           },
//           error: function(xhr, status, error) {
//               console.error('AJAX error:', error);
//               // Optionally show error message to user
//           }
//       });
//      }


let kraTargetTimeout = null;

function updateKRATargetValue(event, keyID, keySubject, type, userID) {
    const value = event.target.value;

    // Clear previous timeout
    clearTimeout(kraTargetTimeout);

    // Set a new timeout to delay API call
    kraTargetTimeout = setTimeout(() => {
        $.ajax({
            url: 'ajax_update_kra_target.php',
            type: 'POST',
            data: {
                value: value,
                key_id: keyID,
                key_subject: keySubject,
                type: type,
                user_id: userID
            },
            success: function(response) {
                console.log('Server response:', response);
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
            }
        });
    }, 600); // 600ms after last keyup
}




function KRAFilterByMonth(event) {
    const month = event.target.value;
    const url = new URL(window.location.href);

    // Update or set the "month" query parameter
    url.searchParams.set('month', month);

    // Redirect to updated URL
    window.location.href = url.toString();

}



function clearFilterMonth(){
  const url = new URL(window.location.href);

  // Remove the 'month' parameter
  url.searchParams.delete('month');

  // Redirect to the new URL without the parameter
  window.location.href = url.pathname + url.search;
}


function clearFilterDateRange(){
  const url = new URL(window.location.href);

  // Remove the 'month' parameter
  url.searchParams.delete('ftime');
  url.searchParams.delete('ttime');
  url.searchParams.delete('user');

  // Redirect to the new URL without the parameter
  window.location.href = url.pathname + url.search;
}


  </script>

<script>
// Pusher.logToConsole = true;

// var pusher = new Pusher('b2125d64edf5e1a092e2', {
//     cluster: 'ap2'
// });

// Pusher.logToConsole = true;

// var pusher = new Pusher('b2125d64edf5e1a092e2', {
//     cluster: 'ap2'
// });

var channel = pusher.subscribe('reminder-channer-notification');
loginUserID = <?= $_SESSION['user_id']; ?>

channel.bind('reminder-event-notification', function(data) {
    if(loginUserID == data.user_id){
      $("#reminderDate").html(data.reminder_date);
      $("#reminderTime").html(data.reminder_time);
      $("#reminderRemark").html(data.remarks);
      $("#reminderSubject").html(data.subject);
      $("#reminderSchoolName").html(data.school_name);
    // alert("New notification: " + data.message); // Ensure this is reached
      $('#iphoneModal').modal('show');
    }
});


channel.bind_global(function(eventName, data) {
    // console.log("Event received:", eventName, data);
});




function showHideIndex(index,eventType){
// eventType : 1 (Show), 2 (Hide)

  if(eventType == 1){
    $("#short-description-"+index).addClass('d-none');
    $("#full-description-"+index).removeClass('d-none');
  }else if(eventType == 2){
    $("#short-description-"+index).removeClass('d-none');
    $("#full-description-"+index).addClass('d-none');
  }


}

</script>

<script>
    function toggleFixedQuantity() {
    const isFixedSelect = document.getElementById('isFixedSelect');
    const fixedField = document.getElementById('fixedQuantityField');

    // If elements don't exist → stop function (prevents error)
    if (!isFixedSelect || !fixedField) {
        console.warn("Required elements not found in DOM");
        return;
    }

    const isFixed = isFixedSelect.value;

    if (isFixed === '1') {
        fixedField.classList.remove('d-none');
    } else {
        fixedField.classList.add('d-none');
    }
}

// Run on page load only if elements exist
document.addEventListener('DOMContentLoaded', toggleFixedQuantity);


    // function toggleFixedQuantity() {
    //     const isFixed = document.getElementById('isFixedSelect').value;
    //     const fixedField = document.getElementById('fixedQuantityField');
    //     if (isFixed === '1') {
    //         fixedField.classList.remove('d-none');
    //     } else {
    //         fixedField.classList.add('d-none');
    //     }
    // }

    // // Optional: Run on page load
    // document.addEventListener('DOMContentLoaded', toggleFixedQuantity);


$("#product_group_status").on("change", function() {
    let status = $(this).val();
    status == 1 
        ? $("#product-group-container").removeClass('d-none') 
        : $("#product-group-container").addClass('d-none');
});


function loadSubProducts(mainProductId,sub_product = 0, id = 0) {

    if (!mainProductId) {
        $('#subProductSelect').html('<option value="">---Select---</option>');
        return;
    }

    $.ajax({
        url: 'ajax_get_sub_products.php',
        type: 'POST',
        data: { main_product_id: mainProductId,sub_product:sub_product,get_id:id },
        success: function(response) {
            $('#subProductSelect').html(response);
        },
        error: function() {
            alert('Failed to load sub products.');
        }
    });

}




</script>


<!-- Bootstrap Multiselect dropdown Done button  Globally -->
<script>
    $(document).on('shown.bs.dropdown', '.btn-group', function () {
        const $container = $(this).find('.multiselect-container');

        // Wrap options once
        if (!$container.find('.multiselect-scroll').length) {
            const $items = $container.children('li:not(.multiselect-footer)');

            $items.wrapAll('<div class="multiselect-scroll"></div>');

            // Add footer
            $container.append(`
                <li class="multiselect-footer">
                    <button type="button"
                            class="btn btn-sm btn-primary done-btn">
                        Done
                    </button>
                </li>
            `);
        }
    });

    $(document).on('click', '.done-btn', function (e) {
        e.preventDefault();
        $(this).closest('.btn-group').find('button.multiselect').trigger('click');
    });


$(document).ajaxError(function (event, xhr, settings) {

    if (xhr.status === 401) {

        let res = {};
        try {
            res = JSON.parse(xhr.responseText);
        } catch (e) {}

        swal({
            title: "Session Expired",
            text: res.message || "Your session has expired. Please login again.",
            type: "error",
            closeOnClickOutside: false,
            closeOnEsc: false
        }, function () {
            window.location.replace("index.php");
        });
    }

});

</script>



  </body>

  </html>