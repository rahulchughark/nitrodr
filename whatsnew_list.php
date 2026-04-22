<?php include('includes/header.php');admin_page();
?>
<!-- ============================================================== -->
        <!-- Page wrapper  -->
        <div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Whats New</small>
                                    <h4 class="font-size-14 m-0 mt-1">Whats New</h4>
                                </div>
                            </div>
                             
                            
                       

								<?php if($_GET['add']=='success') { ?>
                                        <div class="alert alert-success">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> User Added Successfully!
                                        </div>
                                    <?php } ?>
                                    <?php if($_GET['email']=='fail') { ?>
                                     <div class="alert alert-warning">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Warning!</h3> User with this email already exists!
                                        </div>
                                    <?php } ?>


                                <div class="table-responsive">
								 <div class="btn-group float-right" role="group" style="margin-top:12px;">
                               <a id="addwhatnew"><button  data-toggle="tooltip" data-placement="left" title="" data-original-title="Add Whats new" class="btn btn-xs btn-light ml-1"><i class="ti-plus "></i></button></a>
                        </div>
                                    <table id="example23" data-order='[[ 3, "desc" ]]' class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th data-sortable="true">Title</th>
                                                <th data-sortable="true">Description</th>
                                                 <th data-sortable="true">Created By</th>
                                                <th data-sortable="true">Created Date</th>
                                            </tr>
                                        </thead>
                                       
                                        <tbody>
                                        <?php $sql = db_query("select what.*,users.name from what_new AS what LEFT JOIN users ON what.created_by = users.id ORDER BY what.id DESC");
										
										while($data=db_fetch_array($sql)){
										//print_r($data); die;
										?>
										
										    <tr id="tr-id-1" class="tr-class-1">
                                                <td id="td-id-1" class="td-class-1"><?=$data['title']?></td>
                                                <td><?=$data['description']?></td>        
                                                 <td><?=$data['name']?></td>
                                                <td><?=$data['created_at']?></td>
                                            </tr>
										<?php } ?>
                                        </tbody>
                                    </table>
                                    </div>

</div> <!-- end col -->
</div> <!-- end row -->
</div>
</div> <!-- container-fluid -->
</div>
<!-- End Page-content -->


<div id="myModal" class="modal fade" role="dialog">
</div>

 
<?php include('includes/footer.php') ?>
<script>

    $('#example23').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf'
        ]
    });




      $(document).ready(function() {

        $('#addwhatnew').click(function(){
           $.ajax({
            type : 'POST',
            url : 'whatsnew.php',
            success : function(res){               
              $('#myModal').html('');
               $('#myModal').html(res);
              $('#myModal').modal('show');
            }

           });
        })



     });

      

    </script>
<script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 310);
                $("#example23").tableHeadFixer();

            });
</script>
