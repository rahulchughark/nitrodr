<?php include('includes/header.php');?>



<!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">View Call</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">View Call</li>
                        </ol>
                    </div>
                    <div class="col-md-7 col-4 align-self-center">
                        
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
				<?php  if($_REQUEST['id']) { $sql=db_query("select * from call_quality where id=".$_REQUEST['id']);
				$data=db_fetch_array($sql);
				@extract($data);
				}
				else
				{
					redir("call_quality.php",true);
				}
				?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Call Information</h4>
                                <h6 class="card-subtitle"></h6>
                                <table style="clear: both" class="table table-bordered table-striped" id="user">
                                    <tbody>
                                        <tr>
                                            <td width="35%">Date</td>
                                            <td width="65%"><?=date('d-m-Y',strtotime($created_date))?></td>
                                        </tr>
                                        <tr>
                                            <td>Caller Name</td>
                                            <td>
                                               <?=getSingleresult("select name from callers where id =".$caller)?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Extension</td>
                                            <td>
                                         <?=$extension?>  
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Type of Call</td>
                                            <td>
                                         <?=$call_type?>  
                                            </td>
                                        </tr>
                                       <?php if($profiling_call_type) { ?>  <tr>
                                            <td>Type of Profiling Call</td>
                                            <td>
                                         <?=$profiling_call_type?>  
                                            </td>
                                        </tr>
                                        <?php  } ?>
                                        <tr>
                                            <td>Company Name</td>
                                            <td>
                                         <?=$company_name?>  
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Industry</td>
                                            <td>
                                         <?=getSingleresult("select name from industry where id='".$industry."'")?>  
                                            </td>
                                        </tr>
                                     <?php if($sub_industry)  { ?>  <tr>
                                            <td>Sub Industry</td>
                                            <td>
                                         <?=getSingleresult("select name from sub_industry where id='".$sub_industry."'")?>  
                                            </td>
                                        </tr>
                                     <?php } ?>
                                        <tr>
                                            <td>Mobile</td>
                                            <td>
                                         <?=$customer_phone?>  
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
						<div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Call Quality</h4>
                                <h6 class="card-subtitle"></h6>
                                <table style="clear: both" class="table table-bordered table-striped" id="user">
                                    <tbody>
                                        <tr>
                                            <td width="35%">Opening & Closing</td>
                                            <td width="65%"><?php echo "<div class='progress'>
    <div class='progress-bar bg-primary active progress-bar-striped' role='progressbar' style='width: ".(($opening_closing/20)*100)."%;height:15px;' role='progressbar'><span style='font-weight:900'> ".(($opening_closing/20)*100)."%</span> </div>
    </div>" ?></td>
                                        </tr>
                                        <tr>
                                            <td width="35%">Flow of Call & Tone</td>
                                            <td width="65%"><?php echo "<div class='progress'>
    <div class='progress-bar bg-primary active progress-bar-striped' role='progressbar' style='width: ".(($flow_tone/20)*100)."%;height:15px;' role='progressbar'><span style='font-weight:900'> ".(($flow_tone/20)*100)."% </span></div>
    </div>" ?></td>
                                        </tr>
                                        <tr>
                                            <td width="35%">Data Acquiring (Designation, Email)</td>
                                            <td width="65%"><?php echo "<div class='progress'>
    <div class='progress-bar bg-primary active progress-bar-striped' role='progressbar' style='width: ".(($data_aquiring/20)*100)."%;height:15px;' role='progressbar'><span style='font-weight:900'> ".(($data_aquiring/20)*100)."% </span></div>
    </div>" ?></td>
                                        </tr>
										<tr>
                                            <td width="35%">Detailed Info (TAT, Internal Audit)</td>
                                            <td width="65%"><?php echo "<div class='progress'>
    <div class='progress-bar bg-primary active progress-bar-striped' role='progressbar' style='width: ".(($detail_info/20)*100)."%;height:15px;' role='progressbar'> <span style='font-weight:900'>".(($detail_info/20)*100)."% </span></div>
    </div>" ?></td>
                                        </tr>
										<tr>
                                            <td width="35%">SFDC update, Mail Sent</td>
                                            <td width="65%"><?php echo "<div class='progress'>
    <div class='progress-bar bg-primary active progress-bar-striped' role='progressbar' style='width: ".(($sfdc_update_email/20)*100)."%;height:15px;' role='progressbar'><span style='font-weight:900'> ".(($sfdc_update_email/20)*100)."% </span></div>
    </div>" ?></td>
                                        </tr>
										<tr>
                                            <td width="35%">Final Score</td>
                                            <td width="65%"><?php echo "<div class='progress'>
    <div class='progress-bar bg-success active progress-bar-striped' role='progressbar' style='width: ".$total_score."%;height:15px;' role='progressbar'><span style='font-weight:900'> ".$total_score."% </span></div>
    </div>" ?></td>
                                        </tr>
										 
										
                                    </tbody>
                                </table>
                            </div>
                        </div>
						<div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Comments</h4>
                                <h6 class="card-subtitle"></h6>
                                <table style="clear: both" class="table table-bordered table-striped" id="user">
                                    <tbody>
                                        <tr>
                                            <td width="35%">Good Points Observed</td>
                                            <td width="65%"> <?=$good_points?></td>
                                        </tr>
                                         
                                        <tr>
                                            <td>Improvement Required</td>
                                            <td>
                                                 <?=$improvement_required?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Over all Call Feebdack</td>
                                            <td>
                                                 <?=$overall_feedback?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Call Recording Attachment</td>
                                            <td>
                                                <?php echo '<audio style="width:220px" controls>
    <source src='.SITE_PATH.$call_attachment.' type="audio/wav">
    Your browser does not support the audio tag.
  </audio>'; ?>
                                            </td>
                                        </tr>
                                        <?php if($other_attachment) { ?> 
										<tr>
                                            <td>PDF Attchment</td>
                                            <td>
                                               <a href="<?=$other_attachment?>" target="_blank" >View</a>
                                            </td>
                                        </tr>
                                        <?php } ?>
										 <tr>
                                         <td colspan=2>
                                         <a href="add_call_quality.php?id=<?=$id?>" class="btn btn-success">Edit</a>
                                         <button type="button" onclick="javascript:history.go(-1)" class="btn btn-inverse">Back</button>
                                         </td>
                                         </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
						 
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                 
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
<div id="myModal" class="modal fade" role="dialog">
  

</div>

<?php include('includes/footer.php') ?>
<script>
function chage_stage(a)
{
	if(a=='Closed Lost')
	{
	 $( "#add_comment" ).show();
	 $("#add_comment_dd").attr("required","required");
	 	$( "#payment" ).hide();
	 $("#payment_dd").removeAttr("required","required");
	 $("#op").hide();
	 $("#pay_tab").hide();
	}
	else if(a=='EU PO Issued')
	{
		$( "#add_comment" ).hide();
	 $("#add_comment_dd").removeAttr("required","required");
		$( "#payment" ).show();
	 $("#payment_dd").attr("required","required");
	}
	else
	{
		$( "#add_comment" ).hide();
	 $('#add_comment_dd').removeAttr("required","required");
	 	 	$( "#payment" ).hide();
	 $("#payment_dd").removeAttr("required","required");
	 $("#op").hide();
	 $("#pay_tab").hide();
	}
}

function payment_option(val)
{
	//alert(val);
if(val=='100% Payment Received' || val=='Payment After Software Delivery')
{
	$("#op").show();
	$("#pay_tab").hide();
}
else if(val=='Payment in Installments'){
$("#pay_tab").show();
$("#op").hide();
}
else if(val=='Payment Not Clear' || val=='')
{
	//alert(12);
	$("#pay_tab").hide();
	("#op").hide();	
}
}
function add_activity(a)
{
	 $.ajax({  
    type: 'POST',  
    url: 'add_activity.php',
	data:{pid:a},
    success: function(response) { 
	$("#myModal").html();
          $("#myModal").html(response);
        $('#myModal').modal('show');
 }
     });
}	
 function view_activity(a)
{
	var type='Lead';
	 $.ajax({  
    type: 'POST',  
    url: 'view_activity.php',
	data:{pid:a,type:type},
    success: function(response) { 
	$("#myModal").html();
          $("#myModal").html(response);
        $('#myModal').modal('show');
 }
     });
}	

function relog(id)
{
	if(id) {
      swal({   
            title: "Are you sure?",   
            text: "You want to relog the same lead!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes, Re-Log it!",   
            cancelButtonText: "No, cancel modification!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) {
                $.ajax({url: "relog_lead.php?id=<?=$_GET['id']?>", success: function(result){
            if(result)
            {
            swal({title:"Done!",  text:"Lead Re-Loged.",  type:"success"}, function() {
				location.reload();
       
                });
            }
               }});
                  
            } else {     
                swal("Cancelled", "Lead unchanged!", "error");   
            } 
        });
    }
}
$(document).ready(function(){
            $('#md_checkbox_21').click(function(){
              
                if($(this).is(':checked'))
				{
					 $( "#ltype" ).show();
					  $( "#sub_btn" ).show();
	 $("#ltype_dd").prop('required',true);
				}
				else
				{
					$( "#sub_btn" ).hide();
				 $( "#ltype" ).hide();
				 $("#ltype_dd").prop('required',false);	
				}
        });
		

});

$(function() {
    $('.datepicker').daterangepicker({
        
      "singleDatePicker": true,
    "showDropdowns": true,
	  minDate:new Date(),
     locale: {
      format: 'YYYY-MM-DD'
    },
	autoUpdateInput: false,
 
        
    }, function(start, end) {
	$(this.element).val(start.format('YYYY-MM-DD'));
	});
	
	
	
});

function change_quantity(a)
{
	document.getElementById("quant").innerHTML='<input type="text" value="'+a+'" id="new_quantity"/> <button onclick="save_newqty()" class="btn btn-warning">Save</button>'
	
}
function save_newqty()
{
	var newquant=document.getElementById("new_quantity").value;
	if(newquant) {
      swal({   
            title: "Are you sure?",   
            text: "You want to change the quantity for this lead!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes, Change!",   
            cancelButtonText: "No, cancel modification!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) {
                $.ajax({url: "update_quantity.php?id=<?=$_GET['id']?>&quantity="+newquant, success: function(result){
            if(result)
            {
            swal({title:"Done!",  text:"Lead Modified.",  type:"success"}, function() {
				location.reload();
       
                });
            }
               }});
                  
            } else {     
                swal("Cancelled", "Lead unchanged!", "error");   
            } 
        });
    }
}

function change_user(id,team_id)
{
	 $.ajax({  
    type: 'POST',  
    url: 'ajax_change_user.php',
	data:{id:id,team_id:team_id},
    success: function(response) { 
	$("#myModal").html();
          $("#myModal").html(response);
        $('#myModal').modal('show');
 }
     });
}

		</script>