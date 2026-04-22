
<?php if($_GET['review']=='on' && $stage=='') 
{ ?>       
<script>
$(document).ready(function(){
    $('#no_stage_review').modal('show');
    $(".modalMinimize").on("click", function(){
        $("body").css({"overflow":"visible"});
$modalCon = $(this).closest("#no_stage_review").attr("id");  

$apnData = $(this).closest("#no_stage_review");

$modal = "#" + $modalCon;

$(".modal-backdrop").addClass("display-none");   

$($modal).toggleClass("min");  

  if ( $($modal).hasClass("min") ){ 

      $(".minmaxCon").append($apnData);  

      $(this).find("i").toggleClass( 'fa-minus').toggleClass( 'fa-clone');

    } 
    else { 

            $(".container").append($apnData); 

            $(this).find("i").toggleClass( 'fa-clone').toggleClass( 'fa-minus');

          };

});

$("button[data-dismiss='modal']").click(function(){   
    $("body").css({"overflow":"hidden"});
$(this).closest(".mymodal").removeClass("min");

$(".container").removeClass($apnData);   

$(this).next('.modalMinimize').find("i").removeClass('fa fa-clone').addClass( 'fa fa-minus');

}); 

});

function lc_yes()
{
 $('#review_yes').show();
}

function save_lc_yes()
{
   var stage= $( "#lc_stage option:selected" ).text(); 
   var substage= $( "#lc_substage option:selected" ).text(); 
   var id='<?=$_GET['id']?>';
    if(stage !=''){
         
        $.ajax({
            type :'post',
            url : 'change_stage.php',
            data :{stage:stage,substage:substage,lead_id:id,self_review:'yes'},
            success:function(res){
                if(res == 'success'){
                     swal({title:"Done!",  text:"Stage changed Successfully.",  type:"success"}, function() {
                        $('#no_stage_review').modal('hide');
                        window.location='dashboard.php';
                          });

                }else{
                    swal({title:"Error!",  text:res,  type:"error"}, function() {
                                    
                                });

                }

            }



        });

    }
}


function lc_no()
{
    swal({   
            title: "Are you sure?",   
            text: "There is no caller remarks!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes!",   
            cancelButtonText: "No, Cancel!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) {
                $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&type=lc",
                success: function(result){
            if(result)
            {
            swal({title:"Done!",  text:"Review Completed!.",  type:"success"}, function() {
				window.location='partner_view.php?id=<?=$_GET['id']?>';
                $('#no_stage_review').modal('hide');
                window.location='dashboard.php';
                });
            }
               }});
                  
            } else {     
                swal("Cancelled", "Review unchanged!", "error");   
                
            } 
        });
}

</script>

<?php } ?>
 <!-- No Stage Self Review -->

 <!-- Self Review for LC Stage -->
<?php if($_GET['review']=='on' && $stage=='License Compliance'){ ?>
    <script>
        $(document).ready(function(){
    $('#lc_stage_review').modal('show');
    $(".modalMinimize").on("click", function(){
        $("body").css({"overflow":"visible"});
$modalCon = $(this).closest("#lc_stage_review").attr("id");  

$apnData = $(this).closest("#lc_stage_review");

$modal = "#" + $modalCon;

$(".modal-backdrop").addClass("display-none");   

$($modal).toggleClass("min");  

  if ( $($modal).hasClass("min") ){ 

      $(".minmaxCon").append($apnData);  

      $(this).find("i").toggleClass( 'fa-minus').toggleClass( 'fa-clone');

    } 
    else { 

            $(".container").append($apnData); 

            $(this).find("i").toggleClass( 'fa-clone').toggleClass( 'fa-minus');

          };

});

$("button[data-dismiss='modal']").click(function(){   
    $("body").css({"overflow":"hidden"});
$(this).closest(".mymodal").removeClass("min");

$(".container").removeClass($apnData);   

$(this).next('.modalMinimize').find("i").removeClass('fa fa-clone').addClass( 'fa fa-minus');

}); 

});

function yes_lc_stage()
{
 $('#lc_stage_yes').show();
 $('#lc_stage_no').hide();
}

function save_lc_yes()
{
   var stage= $( "#quote_stage option:selected" ).text(); 
   var id='<?=$_GET['id']?>';
    if(stage !=''){
         
        $.ajax({
            type :'post',
            url : 'change_stage.php',
            data :{stage:stage,lead_id:id,self_review:'yes'},
            success:function(res){
                if(res == 'success'){
                     swal({title:"Done!",  text:"Stage changed Successfully.",  type:"success"}, function() {
                        $('#lc_stage_review').modal('hide');
                        window.location='dashboard.php';
                          });

                }else{
                    swal({title:"Error!",  text:res,  type:"error"}, function() {
                                    
                    });

                }

            }



        });

    }
}
function no_lc_stage()
{
    $('#lc_stage_no').show();
    $('#lc_stage_yes').hide();
}

function no_lc_stage_action()
{
    var action= $( "#action_quote option:selected" ).text();  
    swal({   
            title: "Are you sure?",   
            text: action,   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes!",   
            cancelButtonText: "No, Cancel!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) {
                $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&type=quote&action="+action,
                success: function(result){
            if(result)
            {
            swal({title:"Done!",  text:"Review Completed!.",  type:"success"}, function() {
				//window.location='partner_view.php?id=<?=$_GET['id']?>';
                $('#lc_stage_review').modal('hide');
                window.location='dashboard.php';
                });
            }
               }});
                  
            } else {     
                swal("Cancelled", "Review unchanged!", "error");   
                
            } 
        });
}


    </script>
<?php }
?>

  <!-- End Self Review for LC Stage -->
<!-- Self Review for quote Stage -->
<?php if($_GET['review']=='on' && $stage=='Quote'){ ?>
    <script>
        $(document).ready(function(){

            $('#fup_stage').on('change', function() {
                if(this.value=='Customer is not responding, we will try again')
                {
                    $('#followup').show();
                }
                else
                {
                    $('#follow_date').hide();
                }
});

    $('#quote_stage_review').modal('show');
    $(".modalMinimize").on("click", function(){
        $("body").css({"overflow":"visible"});
$modalCon = $(this).closest("#quote_stage_review").attr("id");  

$apnData = $(this).closest("#quote_stage_review");

$modal = "#" + $modalCon;

$(".modal-backdrop").addClass("display-none");   

$($modal).toggleClass("min");  

  if ( $($modal).hasClass("min") ){ 

      $(".minmaxCon").append($apnData);  

      $(this).find("i").toggleClass( 'fa-minus').toggleClass( 'fa-clone');

    } 
    else { 

            $(".container").append($apnData); 

            $(this).find("i").toggleClass( 'fa-clone').toggleClass( 'fa-minus');

          };

});

$("button[data-dismiss='modal']").click(function(){   
    $("body").css({"overflow":"hidden"});
$(this).closest(".mymodal").removeClass("min");

$(".container").removeClass($apnData);   

$(this).next('.modalMinimize').find("i").removeClass('fa fa-clone').addClass( 'fa fa-minus');

}); 

});

function yes_quote_stage()
{
 $('#quote_stage_yes').show();
 $('#quote_stage_no').hide();
}
 
 function show_sub(a)
 {
    if(a=='Customer asked to call back')
    {
        $('#lc_substagefup').show();
        $('#lc_substageCommit').hide();
    }

    
    else if(a=='Customer is positive')
    {
        $('#lc_substageCommit').show();
        $('#lc_substagefup').hide();
    }
else
{
    $('#lc_substagefup').hide();
    $('#lc_substageCommit').hide();
}
 }

  
 
function save_follow_yes()
{
   var action= $( "#fup_stage option:selected" ).text(); 
   var id='<?=$_GET['id']?>';
  
    if(action !=''){
       
        swal({   
            title: "Are you sure?",   
            text: action,   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes!",   
            cancelButtonText: "No, Cancel!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){  
              if(isConfirm && action=='Customer asked to call back')    
              {
                
               var substage= $( "#lc_substagefup option:selected" ).text();   
                $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&action=Customer asked to call back",
                success: function(result){
                }
                });  
                $.ajax({
            type :'post',
            url : 'change_stage.php',
            data :{stage:'Follow-Up',lead_id:id,substage:substage,self_review:'yes'},
            success:function(res){
                if(res == 'success'){
                     swal({title:"Done!",  text:"Stage changed Successfully to Follow-Up.",  type:"success"}, function() {
                        $('#quote_stage_review').modal('hide');
                        window.location='dashboard.php';
                          });

                }else{
                    swal({title:"Error!",  text:res,  type:"error"}, function() {
                                    
                                });

                }

            }



        });
              }
               else if(isConfirm && action=='Customer is positive')    
              {
               
               var substage= $( "#lc_substageCommit option:selected" ).text();     
                $.ajax({
            type :'post',
            url : 'change_stage.php',
            data :{stage:'Commit',substage:substage,lead_id:id,self_review:'yes'},
            success:function(res){
                if(res == 'success'){
                     swal({title:"Done!",  text:"Stage changed Successfully to Commit.",  type:"success"}, function() {
                        $('#quote_stage_review').modal('hide');
                        window.location='dashboard.php';
                          });

                }else{
                    swal({title:"Error!",  text:res,  type:"error"}, function() {
                                    
                                });

                }

            }



        });
              }
              else
              {
            if (isConfirm) {
                if(action=='Customer is not responding, we will try again')
                {
                    var fup_date=$('#followup').val();
                }
                $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&type=fup&action="+action+"&fupdate="+fup_date,
                success: function(result){
            if(result)
            {
             if(action=='Looking for best price' || action=='Customer denied')  
             {
                $.ajax({
            type :'post',
            url : 'change_stage.php',
            data :{stage:'Follow-Up',lead_id:id,self_review:'yes'},
            success:function(res){
                if(res == 'success'){
                   // consol.log("ok");
                }
            }
             }); 
             }
            swal({title:"Done!",  text:"Review Completed!.",  type:"success"}, function() {
				//window.location='partner_view.php?id=<?=$_GET['id']?>';
                $('#quote_stage_review').modal('hide');
                window.location='dashboard.php';
                });
            }
               }});
                  
            } else {     
                swal("Cancelled", "Review unchanged!", "error");   
                
            } 
          }
        });

    }
}
function no_quote_stage()
{
    $('#quote_stage_no').show();
    $('#quote_stage_yes').hide();
}

function no_follow_stage_action()
{
    var action= $( "#action_follow option:selected" ).text();  
    var id='<?=$_GET['id']?>';

  swal({   
            title: "Are you sure?",   
            text: 'Would you like to ' +action,   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes!",   
            cancelButtonText: "No, Cancel!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){  

            if (isConfirm && action=='Drop') {
                $.ajax({
            type :'post',
            url : 'change_stage.php',
            data :{stage:'Closed Lost',lead_id:id,self_review:'yes'},
            success:function(res){
                if(res == 'success'){
                    swal({title:"Done!",  text:"Review Completed!.",  type:"success"}, function() {
				 
                $('#quote_stage_review').modal('hide');
                	 
                    window.location='dashboard.php';
                });  
                }
            }
             }); 
            }
            else if (isConfirm) {
                $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&type=quote&action="+action,
                success: function(result){
            if(result)
            {

                $.ajax({
            type :'post',
            url : 'change_stage.php',
            data :{stage:'License Compliance',lead_id:id,self_review:'yes'},
            success:function(res){
                if(res == 'success'){
                   // consol.log("ok");
                }
            }
             }); 
            swal({title:"Done!",  text:"Review Completed!.",  type:"success"}, function() {
				//window.location='partner_view.php?id=<?=$_GET['id']?>';
                $('#quote_stage_review').modal('hide');
                window.location='dashboard.php';
                });
            }
               }});
                  
            } else {     
                swal("Cancelled", "Review unchanged!", "error");   
                
            } 
        });  
}


    </script>
<?php }

if($_GET['review']=='on' && $stage=='Follow-Up'){ ?>

<script>
        $(document).ready(function(){

            $('#followup_stage').on('change', function() {
               // alert("hq");
                if(this.value=='Yes, Positive but unsure on closure date')
                {
                    $('#flup_date').show();
                }
                else
                {
                    $('#flup_date').hide();
                }
});

    $('#follow_stage_review').modal('show');
    $(".modalMinimize").on("click", function(){
        $("body").css({"overflow":"visible"});
$modalCon = $(this).closest("#follow_stage_review").attr("id");  

$apnData = $(this).closest("#follow_stage_review");

$modal = "#" + $modalCon;

$(".modal-backdrop").addClass("display-none");   

$($modal).toggleClass("min");  

  if ( $($modal).hasClass("min") ){ 

      $(".minmaxCon").append($apnData);  

      $(this).find("i").toggleClass( 'fa-minus').toggleClass( 'fa-clone');

    } 
    else { 

            $(".container").append($apnData); 

            $(this).find("i").toggleClass( 'fa-clone').toggleClass( 'fa-minus');

          };

});

$("button[data-dismiss='modal']").click(function(){   
    $("body").css({"overflow":"hidden"});
$(this).closest(".mymodal").removeClass("min");

$(".container").removeClass($apnData);   

$(this).next('.modalMinimize').find("i").removeClass('fa fa-clone').addClass( 'fa fa-minus');

}); 

});

function yes_follow_stage()
{
 $('#follow_stage_yes').show();
 
}

function save_follow_yes2()
{
   var action= $( "#followup_stage option:selected" ).text(); 
   var id='<?=$_GET['id']?>';
  
    if(action !=''){
       
        swal({   
            title: "Are you sure?",   
            text: action,   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes!",   
            cancelButtonText: "No, Cancel!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){  
              if(isConfirm && action=='Yes, Positive but unsure on closure date')    
              {
                if(action=='Yes, Positive but unsure on closure date')
                {
                    var fup_date=$('#followup').val();
                }
                $.ajax({
            type :'post',
            url : 'change_stage.php',
            data :{stage:'Follow-Up',lead_id:id,self_review:'yes'},
            success:function(res){
                if(res == 'success'){
                    $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&type=comm&action="+action+"&fupdate="+fup_date,
                success: function(result){
                    
                }
            });
                     swal({title:"Done!",  text:"Stage changed Successfully to Follow-Up.",  type:"success"}, function() {
                        $('#follow_stage_review').modal('hide');
                        window.location='dashboard.php';
                          });

                }else{
                    swal({title:"Error!",  text:res,  type:"error"}, function() {
                                    
                                });

              
            }


  }

        });
              }
               else if(isConfirm && action=='Yes, Positive. Expecting Closure')    
              {
                  
                $.ajax({
            type :'post',
            url : 'change_stage.php',
            data :{stage:'Commit',lead_id:id,self_review:'yes'},
            success:function(res){
                if(res == 'success'){
                     swal({title:"Done!",  text:"Stage changed Successfully to Commit.",  type:"success"}, function() {
                        $('#follow_stage_review').modal('hide');
                        window.location='dashboard.php';
                          });

                }else{
                    swal({title:"Error!",  text:res,  type:"error"}, function() {
                                    
                                });

                }

            }



        });
              }
              else
              {
            if (isConfirm) {
                
                $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&type=comm&action="+action,
                success: function(result){
            if(result)
            {
             
            swal({title:"Done!",  text:"Review Completed!.",  type:"success"}, function() {
				//window.location='partner_view.php?id=<?=$_GET['id']?>';
                $('#follow_stage_review').modal('hide');
                window.location='dashboard.php';
                });
            }
               }});
                  
            } else {     
                swal("Cancelled", "Review unchanged!", "error");   
                
            } 
          }
        });

    }
}
function no_follow_stage()
{
    $('#follow_stage_review').modal('hide');
    window.location='dashboard.php';
}

 

    </script>

<?php }

if($_GET['review']=='on' && $stage=='Commit'){ ?>

    <script>
    function change_subfup(a)
 {
    if(a=='Yes, Positive but unsure on closure date')
    {
        $('#fp_substagefup').show();
        $('#fp_substageCommit').hide();
    }

    
    else if(a=='Yes, Purchase Order received')
    {
        $('#fp_substageCommit').show();
        $('#fp_substagefup').hide();
    }
else
{
    $('#fp_substagefup').hide();
    $('#fp_substageCommit').hide();
}
 }
            $(document).ready(function(){
    
                $('#commit_stage').on('change', function() {
                   // alert("hq");
                    if(this.value=='Yes, Positive but unsure on closure date')
                    {
                        $('#commit_date').show();
                    }
                    else
                    {
                        $('#commit_date').hide();
                    }
    });
    
        $('#commit_stage_review').modal('show');
        $(".modalMinimize").on("click", function(){
            $("body").css({"overflow":"visible"});
    $modalCon = $(this).closest("#commit_stage_review").attr("id");  
    
    $apnData = $(this).closest("#commit_stage_review");
    
    $modal = "#" + $modalCon;
    
    $(".modal-backdrop").addClass("display-none");   
    
    $($modal).toggleClass("min");  
    
      if ( $($modal).hasClass("min") ){ 
    
          $(".minmaxCon").append($apnData);  
    
          $(this).find("i").toggleClass( 'fa-minus').toggleClass( 'fa-clone');
    
        } 
        else { 
    
                $(".container").append($apnData); 
    
                $(this).find("i").toggleClass( 'fa-clone').toggleClass( 'fa-minus');
    
              };
    
    });
    
    $("button[data-dismiss='modal']").click(function(){   
        $("body").css({"overflow":"hidden"});
    $(this).closest(".mymodal").removeClass("min");
    
    $(".container").removeClass($apnData);   
    
    $(this).next('.modalMinimize').find("i").removeClass('fa fa-clone').addClass( 'fa fa-minus');
    
    }); 
    
    });


function yes_commit_stage()
{
 $('#commit_stage_yes').show();
 
}


function save_commit_yes()
{
   var action= $( "#commit_stage option:selected" ).text(); 
   var id='<?=$_GET['id']?>';
  
    if(action !=''){
       
        swal({   
            title: "Are you sure?",   
            text: action,   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes!",   
            cancelButtonText: "No, Cancel!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){  
               
            if(isConfirm && action=='Yes, Purchase Order received')    
              {
                var substage= $("#fp_substageCommit option:selected" ).text();
                $.ajax({
            type :'post',
            url : 'change_stage.php',
            data :{stage:'EU PO Issued',substage:substage,lead_id:id,self_review:'yes'},
            success:function(res){
                if(res == 'success'){
                    $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&type=eupo&action="+action,
                success: function(result){
                    
                }
            });
                     swal({title:"Done!",  text:"Stage changed Successfully to EUPO Issued.",  type:"success"}, function() {
                        $('#follow_stage_review').modal('hide');
                        window.location='dashboard.php';
                          });


                        }


            }

      });
              }
            else if(isConfirm && action=='Yes, Positive but unsure on closure date')    
              {
                var substage= $( "#fp_substagefup option:selected" ).text(); 
                if(action=='Yes, Positive but unsure on closure date')
                {
                    var fup_date=$('#followup').val();
                }
                $.ajax({
            type :'post',
            url : 'change_stage.php',
            data :{stage:'Follow-Up',substage:substage,lead_id:id,self_review:'yes'},
            success:function(res){
                if(res == 'success'){
                    $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&type=comm&action="+action+"&fupdate="+fup_date,
                success: function(result){
                    
                }
            });
                     swal({title:"Done!",  text:"Stage changed Successfully to Follow-Up.",  type:"success"}, function() {
                        $('#follow_stage_review').modal('hide');
                        window.location='dashboard.php';
                          });

                }else{
                    swal({title:"Error!",  text:res,  type:"error"}, function() {
                                    
                                });

                }

            }



        });
              } else if (isConfirm) {
                
                $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&type=comm&action="+action,
                success: function(result){
            if(result)
            {
             
            swal({title:"Done!",  text:"Review Completed!.",  type:"success"}, function() {
				//window.location='partner_view.php?id=<?=$_GET['id']?>';
                $('#follow_stage_review').modal('hide');
                window.location='dashboard.php';
                });
            }
               }});
                  
            } else {     
                swal("Cancelled", "Review unchanged!", "error");   
                
            } 
       
        });

    }
}


function no_commit_stage()
{
    $('#commit_stage_review').modal('hide');
    window.location='dashboard.php';
}

    </script>
<?php } if($_GET['review']=='on' && $stage=='EU PO Issued'){ ?>

<script>
        $(document).ready(function(){

            $('#eupo_stage').on('change', function() {
               // alert("hq");
                if(this.value=='Payment is not clear, but we will process this order' || this.value=='Payment is not clear, order can not process in this month')
                {
                    $('#eupo_date').show();
                }
                else
                {
                    $('#eupot_date').hide();
                }
});

    $('#eupo_stage_review').modal('show');
    $(".modalMinimize").on("click", function(){
        $("body").css({"overflow":"visible"});
$modalCon = $(this).closest("#eupo_stage_review").attr("id");  

$apnData = $(this).closest("#eupo_stage_review");

$modal = "#" + $modalCon;

$(".modal-backdrop").addClass("display-none");   

$($modal).toggleClass("min");  

  if ( $($modal).hasClass("min") ){ 

      $(".minmaxCon").append($apnData);  

      $(this).find("i").toggleClass( 'fa-minus').toggleClass( 'fa-clone');

    } 
    else { 

            $(".container").append($apnData); 

            $(this).find("i").toggleClass( 'fa-clone').toggleClass( 'fa-minus');

          };

});

$("button[data-dismiss='modal']").click(function(){   
    $("body").css({"overflow":"hidden"});
$(this).closest(".mymodal").removeClass("min");

$(".container").removeClass($apnData);   

$(this).next('.modalMinimize').find("i").removeClass('fa fa-clone').addClass( 'fa fa-minus');

}); 

});


function yes_eupo_stage()
{
$('#eupo_stage_yes').show();

}


function save_eupo_yes()
{
var action= $( "#eupo_stage option:selected" ).text(); 
var id='<?=$_GET['id']?>';

if(action !=''){
   
    swal({   
        title: "Are you sure?",   
        text: action,   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Yes!",   
        cancelButtonText: "No, Cancel!",   
        closeOnConfirm: false,   
        closeOnCancel: false 
    }, function(isConfirm){  
           
        if(isConfirm && action=='Yes, Order is Processed')    
          {
            
            $.ajax({
        type :'post',
        url : 'change_stage.php',
        data :{stage:'Booking',lead_id:id,self_review:'yes'},
        success:function(res){
            if(res == 'success'){
                $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&type=booking&action="+action,
            success: function(result){
                
            }
        });
                 swal({title:"Done!",  text:"Stage changed Successfully to Booking.",  type:"success"}, function() {
                    $('#follow_stage_review').modal('hide');
                    window.location='dashboard.php';
                      });


                    }


        }

  });
          }
        else if(isConfirm && (action=='Payment is not clear, but we will process this order' || action=='Payment is not clear, order can not process in this month' ))    
          {
          
        var fup_date=$('#followup').val();
            $.ajax({
        type :'post',
        url : 'change_stage.php',
        data :{stage:'EU PO Issued',lead_id:id,self_review:'yes'},
        success:function(res){
            if(res == 'success'){
                $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&type=eupo&action="+action+"&fupdate="+fup_date,
            success: function(result){
                
            }
        });
                 swal({title:"Done!",  text:"Stage changed Successfully to Follow-Up.",  type:"success"}, function() {
                    $('#follow_stage_review').modal('hide');
                    window.location='dashboard.php';
                      });

            }else{
                swal({title:"Error!",  text:res,  type:"error"}, function() {
                                
                            });

            }

        }



    });
          } else if (isConfirm) {
            
            $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&type=comm&action="+action,
            success: function(result){
        if(result)
        {
         
        swal({title:"Done!",  text:"Review Completed!.",  type:"success"}, function() {
            //window.location='partner_view.php?id=<?=$_GET['id']?>';
            $('#follow_stage_review').modal('hide');
            window.location='dashboard.php';
            });
        }
           }});
              
        } else {     
            swal("Cancelled", "Review unchanged!", "error");   
            
        } 
   
    });

}
}


function no_eupo_stage()
{
$('#eupo_stage_review').modal('hide');
window.location='dashboard.php';
}

</script>
<?php }  if($_GET['review']=='on' && $stage=='Booking'){ ?>

<script>
        $(document).ready(function(){

          
               

    $('#booking_stage_review').modal('show');
    $(".modalMinimize").on("click", function(){
        $("body").css({"overflow":"visible"});
$modalCon = $(this).closest("#booking_stage_review").attr("id");  

$apnData = $(this).closest("#booking_stage_review");

$modal = "#" + $modalCon;

$(".modal-backdrop").addClass("display-none");   

$($modal).toggleClass("min");  

  if ( $($modal).hasClass("min") ){ 

      $(".minmaxCon").append($apnData);  

      $(this).find("i").toggleClass( 'fa-minus').toggleClass( 'fa-clone');

    } 
    else { 

            $(".container").append($apnData); 

            $(this).find("i").toggleClass( 'fa-clone').toggleClass( 'fa-minus');

          };

});

$("button[data-dismiss='modal']").click(function(){   
    $("body").css({"overflow":"hidden"});
$(this).closest(".mymodal").removeClass("min");

$(".container").removeClass($apnData);   

$(this).next('.modalMinimize').find("i").removeClass('fa fa-clone').addClass( 'fa fa-minus');

}); 

});


function yes_booking_stage()
{
$('#booking_stage_yes').show();

}


function save_booking_yes()
{
var action= $( "#booking_stage option:selected" ).text(); 
var id='<?=$_GET['id']?>';

if(action !=''){
   
    swal({   
        title: "Are you sure?",   
        text: action,   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Yes!",   
        cancelButtonText: "No, Cancel!",   
        closeOnConfirm: false,   
        closeOnCancel: false 
    }, function(isConfirm){  
           
        if(isConfirm)    
          {
            var substage= $( "#oemstage option:selected" ).text();
           
            $.ajax({
        type :'post',
        url : 'change_stage.php',
        data :{stage:'OEM Billing',lead_id:id,self_review:'yes'},
        success:function(res){
            if(res == 'success'){
                $.ajax({url: "update_self_review.php?rid=<?=$_GET['id']?>&type=booking&action="+action,
            success: function(result){
                
            }
        });
                 swal({title:"Done!",  text:"Stage changed Successfully to OEM Billing.",  type:"success"}, function() {
                    $('#follow_stage_review').modal('hide');
                    window.location='dashboard.php';
                      });


                    }


        }

  });
         
              
        } else {     
            swal("Cancelled", "Review unchanged!", "error");   
            
        } 
   
    });

}
}


 

</script>
<?php } ?>