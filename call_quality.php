<?php include('includes/header.php');?>
 
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
                       <h3 class="text-themecolor m-b-0 m-t-0">Call Quality</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Call Quality</li>
                        </ol>
                    </div>
                    <div class="col-md-7 col-4 align-self-center">
                        <div class="d-flex m-t-10 justify-content-end">
                             
                            <div class="">
                               <a href="add_call_quality.php"><button  data-toggle="tooltip" data-placement="left" title="" data-original-title="Add Call Review" class="right-side bottom-right waves-effect waves-light btn-success btn btn-circle btn-lg pull-right m-l-10"><i class="ti-plus text-white"></i></button></a>
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
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="media bredcrum-title">
                                            <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                            <div class="media-body">

                                                <small class="text-muted">Home > Call Quality</small>
                                                <h4 class="font-size-14 m-0 mt-1">Call Quality</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-xs btn-light ml-1  " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                        <div class="dropdown dropdown-lg">
                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap filter_wrap_2" id="filter-container" role="menu">
                                                <form method="get" name="search">
                                                    <div class="form-group">
                                                        <input type="text" value="<?php echo @$_GET['d_from']?>" class="datepicker form-control" id="d_from" name="d_from" placeholder="Date From" />
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" value="<?php echo @$_GET['d_to']?>" class="datepicker form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                    </div>
                                                    <input type="submit" class="btn btn-success" value="Search" />
                                                    <input type="button" class="btn btn-red" value="Clear" onclick="clear_search()" />
                                                </form>                
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<?php if($_GET['add']=='success') { ?>
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                        <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Call Review Added Sucessfully!
                                    </div>
                                <?php } ?>
                                <?php if($_GET['update']=='success') { ?>
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                        <h3 class="text-success"><i class="fa fa-check-circle"></i> Upadated</h3> Call Review Updated Sucessfully!
                                    </div>
                                <?php } ?>
                                <div class="table-responsive m-t-40">
                                    <table id="leads" class="display nowrap table table-hover table-striped table-bordered font-14" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                               <tr>
                                                <th>S.No.</th>
												<th>Caller Name</th>
                                                <th>Extension</th>
                                                <th>Type of Call</th>
                                                 
                                                <th>Company Name</th>
                                                <th>Call Quality Score</th>
											    <th>Recording</th>
											    <th>Date Added</th>
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
                <!-- <div class="right-sidebar">
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
                </div> -->
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
 
<?php include('includes/footer.php') ?>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).ready(function() {
            var dataTable = $('#leads').DataTable( {
		dom: 'Bfrtip',
        language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
        ],
		lengthMenu: [
        [ 10, 25, 50, 100,500,1000 ],
        [ '10', '25', '50','100','500', '1000' ]
    ],
            "processing": true,
            "serverSide": true,
            "ajax":{
                url :"get_call_quality.php", // json datasource
                type: "post",  // method  , by default get
				data:function ( d ) {
                d.d_from = "<?=$_GET['d_from']?>";
                d.d_to = "<?=$_GET['d_to']?>";
				 //d.partner = "<?=$_GET['partner']?>";
                // d.custom = $('#myInput').val();
                // etc
					},
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display","none");
 
                }
            }
        } );
            // Order by the grouping
            // $('#leads tbody').on('click', 'tr.group', function() {
            //     var currentOrder = table.order()[0];
            //     if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
            //         table.order([2, 'desc']).draw();
            //     } else {
            //         table.order([2, 'asc']).draw();
            //     }
            // });
        });
    });
 
function clear_search()
{
window.location='call_quality.php';
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

    </script>