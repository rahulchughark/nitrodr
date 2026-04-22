<?php include('includes/header.php');?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
 <?php if($_POST['review_edit'])
 {
    $query=db_query("update lead_review set is_review=0,removed_by='".$_SESSION['user_id']."' where lead_id='".$_POST['pid']."'");
    $o_stage=getSingleresult("select stage from orders where id='".$_POST['pid']."'");
    $log_query=db_query("insert into review_log (lead_id,old_stage,new_stage,sub_stage,comment,added_by) values ('".$_POST['pid']."','".$o_stage."','".$_POST['stage']."','".$_POST['substage']."','".$_POST['comment']."','".$_SESSION['name']."')");
    if($_POST['stage']!='EU PO Issued' &&  $_POST['stage']!='Booking' && $_POST['stage']!='OEM Billing')
    {
    $query=db_query("update orders set prospecting_date='".date('Y-m-d')."', stage='".$_POST['stage']."',add_comm='".$_POST['substage']."' where id='".$_POST['pid']."'");
    }
    else
    {
        $query=db_query("update orders set stage='".$_POST['stage']."',add_comm='".$_POST['substage']."' where id='".$_POST['pid']."'");
       
    }
 } 
 ?>
<!-- ============================================================== -->
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
                       <h3 class="text-themecolor m-b-0 m-t-0">Review Leads</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Leads</li>
                        </ol>
                    </div>
                    <div class="col-md-7 col-4 align-self-center">
                        <div class="d-flex m-t-10 justify-content-end">
                             
                            
                            
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body  fixed-table-body">
                            <!--    <h4 class="card-title">Data Export</h4>
                               
                                <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6>-->
                                <div style="float:;margin-right:20px">
                                
                         <form method="get" name="search">
                         <div class="row">
                         <div class="col-md-2">
                             <input type="text" value="<?php echo @$_GET['d_from']?>" class="datepicker form-control" id="d_from" name="d_from" placeholder="Date From" />
                             </div>
                            
                             <div class="col-md-2">
                             <input type="text" value="<?php echo @$_GET['d_to']?>" class="datepicker form-control" id="d_to" name="d_to" placeholder="Date To" />
                             </div>
                             <div class="col-md-3">
                             <?php $res=db_query("select * from partners"); 
													
													//print_r($res); die;
													
													?>
                                 <select name="partner" id="partner" class="form-control">
													 <option value="" >---Select Partner---</option>
													 <?php while($row=db_fetch_array($res))
													 { ?>
												 <option <?=(($_GET['partner']==$row['id'])?'selected':'')?>  value='<?=$row['id']?>'><?=$row['name']?></option>
													 <?php } ?>
													 </select>
                             </div>
                             <div class="col-md-3">
                              <select class="form-control" id="is_review" name="is_review" >
                             <option value="">---Review Status---</option>
                             <option <?=(($_REQUEST['is_review']=='1')?'selected':'')?> value="1">Pending</option>
                             <option <?=(($_REQUEST['is_review']=='0')?'selected':'')?> value="0">Done</option>
                             </select>
                             </div>
                             <div class="col-md-2">
                             <input type="submit" class="btn btn-primary" value="Search" />
                             <input class="btn btn-danger" type="button" value="Clear" onclick="clear_search()" />
                             </div>
                             
                             
                             </div>
                         </form>
                     </div> 
								<?php if($_GET['add']=='success') { ?>
<div class="alert alert-success">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Lead Added Successfully!
                                        </div>
<?php } ?>
<?php if($_GET['update']=='success') { ?>
<div class="alert alert-success">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Lead Updated Successfully!
                                        </div>
<?php } ?>
<?php if($_GET['m']=='nodata') { ?>
<div class="alert alert-danger">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                            <h3 class="text-danger"><i class="fa fa-check-circle"></i> Error!</h3> No data found!
                                        </div>
<?php } ?>	                                
                                <div class="table-responsive">
								 
                                    <table id="leads"  class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" >
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
											    <th>Reseller name(Submitted by)</th>
                                                <th>Lead Type</th>
                                                <th>Quantity</th>
                                                <th>Company Name</th>
                                                <th>Date of Submission</th>
											    <th>Last Stage</th>
                                                <th>Added On</th>
                                                <th>Review Status</th>
											     
                                                </tr>
                                        </thead>
                                        
                                         
                                    </table>
                                </div>
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
            <div id="myModal1" class="modal" role="dialog">
  

  </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
 
<?php include('includes/footer.php') ?>
<script>
    $(document).ready(function() {
		
        $(document).ready(function() {
            var dataTable = $('#leads').DataTable( {
               "stateSave": true,
		dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
        ],
		lengthMenu: [
        [ 15, 25, 50, 100,500,1000 ],
        [ '15', '25', '50','100','500', '1000' ]
    ],
    "processing": true,
            "serverSide": true,
            "ajax":{
                url :"get_review_leads_callers.php", // json datasource
                type: "post",  // method  , by default get
				data:function ( d ) {
                d.d_from = "<?=$_GET['d_from']?>";
                d.d_to = "<?=$_GET['d_to']?>";
                d.partner = "<?=$_GET['partner']?>";
                d.review = "<?=$_GET['is_review']?>";
            
					},
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display","none");
 
                }
            },
            "order": [[ 7, "desc" ]],
            columnDefs: [
               { orderable: false, targets: 0 }
            ],
            'columns': [
                 { data: 'id' },
                  { data: 'r_name' },
                   { data: 'lead_type' },
                   { data: 'quantity' },
                   { data: 'company_name' },
                   {data:'created_date'},
                   {data:'stage'},
                   {data:'added_date'},
                   {data:'is_review'},
                    
                
              ]
            
        } );
            // Order by the grouping
            $('#leads tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });


       /* $('.stagelist').on('change',function(){

            alert(this.val());


        });*/







    });
    
 
function clear_search()
{
window.location='review_leads.php';
}

$(function() {
    $('.datepicker').daterangepicker({
        
      "singleDatePicker": true,
    "showDropdowns": true,
     locale: {
      format: 'YYYY-MM-DD'
    },
//startDate: '2017-01-01',
 //autoUpdateInput: false,
        
    });
});

function chage_stage(stage,id){

//alert(stage + '' +id);


if(stage !=''){
    $.ajax({
        type :'post',
        url : 'change_stage.php',
        data :{stage:stage,lead_id:id},
        success:function(res){
            if(res == 'success'){
                 swal({title:"Done!",  text:"Stage changed Successfully.",  type:"success"}, function() {
                                   //window.location = "manage_orders.php";
                            });

            }else{
                swal({title:"Error!",  text:res,  type:"error"}, function() {
                                
                            });

            }

        }



    });

}



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
                $.ajax({url: "relog_lead.php?id="+id, success: function(result){
            if(result)
            {
            swal({title:"Done!",  text:"Lead Re-Loged.",  type:"success"}, function() {
				//location.reload();
				$('#leads').DataTable().ajax.reload();
       
                });
            }
               }});
                  
            } else {     
                swal("Cancelled", "Lead unchanged!", "error");   
            } 
        });
    }
}

function edit_review(id)
{
    //$('.preloader').show();
    $.ajax({  
    type: 'POST',  
    url: 'review_edit.php',
	data:{pid:id},
    success: function(response) { 
	$("#myModal1").html();
          $("#myModal1").html(response);
        
        $('#myModal1').modal('show');
        $('.preloader').hide();
 }
     });
}
function view_log(id)
{
    //$('.preloader').show();
    $.ajax({  
    type: 'POST',  
    url: 'view_review_log.php',
	data:{pid:id},
    success: function(response) { 
	$("#myModal1").html();
          $("#myModal1").html(response);
        
        $('#myModal1').modal('show');
        $('.preloader').hide();
 }
     });
}



</script>
<script> 
 jQuery("#search_toogle").click(function(){
    jQuery(".search_form").toggle("fast");
});

  var wfheight = $(window).height();
                  
                  $('.fixed-table-body').height(wfheight-195);
                  


      $('.fixed-table-body').slimScroll({
        color: '#00f',
        size: '10px',
       height: 'auto',
	   
      
    });  
	
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>