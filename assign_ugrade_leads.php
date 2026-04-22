<?php include('includes/header.php');admin_page();?>
<?php
if($_POST['partner'])
{
	
	if(count($_POST['ids']))
	{
		foreach ($_POST['ids'] as $id)
		{
			$sql=db_query("update upgrade_leads set reseller='".$_POST['partner']."' where id=".$id);
			
		}
	}
	
	redir("assign_ugrade_leads.php?add=success",true);
	  
}?>
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
                       <h3 class="text-themecolor m-b-0 m-t-0">Align Upgrade Leads</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Align Upgrade Leads</li>
                        </ol>
                    </div>
                    <div class="col-md-7 col-4 align-self-center">
                        <div class="d-flex m-t-10 justify-content-end">
                             
                            
                            <div class="">
                               
                            </div>
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
                                

								<?php if($_GET['add']=='success') { ?>
<div class="alert alert-success">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Leads aligned to partner Sucessfully!
                                        </div>
<?php } ?>
<?php if($_GET['update']=='success') { ?>
<div class="alert alert-success">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Upadated</h3> Order Updated Sucessfully!
                                        </div>
<?php } ?>
<form action="#" method="post">
<div class="col-6"><h3>Reassign to Reseller</h3><?php $res=db_query("select * from partners"); 
													
													 
													
													?>
                                                    <div class="input-group"><select name="partner" id="partner" required class="form-control">
													 <option>---Select---</option>
													 <?php while($row=db_fetch_array($res))
													 { ?>
												 <option <?=(($row['id']==$reseller)?'Selected':'')?> value='<?=$row['id']?>'><?=$row['name']?></option>
													 <?php } ?>
													 </select> 
													 <div class="input-group-btn col-md-2"><button class="btn btn-primary" type="submit">Save</button></div></div>
													 </div>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                               <tr>
                                                <th><input type="checkbox" id="check_all"/><label for="check_all">All</label></th>
												<th>Native Reseller</th>
                                                <th>End User</th>
                                              
                                                <th>End User Contact</th>
                                                <th>Contact License Email</th>
                                                <th>Phone</th>
												<th>Quantity</th>
                                             
												</tr>
                                        </thead>
                                         <tbody>
                                        <?php $sql=db_query("select * from upgrade_leads where reseller=45 order by id desc");
										
										while($data=db_fetch_array($sql)){
										 if(is_numeric($data['reseller'])) {
										?>
										
										<tr>
												<td><input type="checkbox" name="ids[]" value="<?=$data['id']?>" id="check_<?=$data['id']?>"/><label for="check_<?=$data['id']?>"></label></td>
                                                <td><?=$data['native_lead']?></td>
                                                <td><?=$data['eu_name']?></td>
                                                <td><?=$data['eu_contact']?></td>
                                                <td><?=$data['contact_email']?></td>
                                                <td><?=$data['mobile_number']?></td>
                                                <td><?=$data['quantity']?></td>
                                                
                                                 
                                            </tr>
										<?php } } ?>
                                        </tbody>
                                         
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        </form>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-theme="red" class="red-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme working">4</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme ">12</a></li>
                            </ul>
                            <ul class="m-t-20 chatonline">
                                <li><b>Chat option</b></li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
 
<?php include('includes/footer.php') ?>
<script>
 $("#check_all").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
        
  $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).ready(function() {
            var table = $('#example').DataTable({
               
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
				
                "displayLength": 25,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                }
            });
            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
		$("select[name='myTable_length']").append("<option value='500'>500</option>");
    });
    $('#example23').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
	 
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