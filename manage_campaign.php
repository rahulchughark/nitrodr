<?php
include('includes/header.php');

admin_page();
?>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media ">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Manage Campaign</small>
                                    <h4 class="font-size-14 m-0 mt-1">Manage Campaign</h4>
                                </div>
                            </div>
<div class="clearfix"></div>
                            <?php if ($_GET['add'] == 'success') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Campaign Added Successfully!
                            </div>
                        <?php } ?>

                        <?php if ($_GET['update'] == 'success') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Campaign Updated Successfully!
                            </div>
                        <?php } ?>
                        <?php if ($_GET['email'] == 'fail') { ?>
                            <div class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Warning!</h3> User with this email already exists!
                            </div>
                        <?php } ?>




                        <div class="table-responsive">
						 <div class="btn-group float-right" role="group" style="margin-top:12px;">
                        <a href="add_campaign.php"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Add Campaign" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus"></i></button></a>
 </div>
                            <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th data-sortable="true">S No.</th>
                                        <th data-sortable="true">Campaign Name</th>
                                        <th data-sortable="true">Description</th>
                                        <th data-sortable="true">Start Date</th>
                                        <th data-sortable="true">End Date</th>
                                        <th data-sortable="true">Product Name</th>
                                        <th data-sortable="true">Created By</th>
                                        <th data-sortable="true">Created Date</th>
                                        <th data-sortable="true">Status</th>
                                        <th data-sortable="true">Action</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $i = 1;
                                    $campaignList = get_campaign_data();
                                    foreach ($campaignList as $value) {
                                        // print_r($value);                                   

                                        echo '
                                            <tr>
                                                <td>' . $i . '</td>
                                                <td>' . $value['name'] . '</td>
                                                <td>' . $value['description'] . '</td>
                                                <td>' . date('d F Y',strtotime($value['start_date'])) . '</td>
                                                <td>' . date('d F Y',strtotime($value['end_date'])) . '</td>
                                                <td>' . $value['product_name'] . '</td>
                                                <td>' . $value['user'] . '</td>
                                                <td>' . date('d F Y',strtotime($value['created_at'])) . '</td>
                                                <td>'. (($value['status']==1)?'Active':'Inactive').'</td>
                                                <td><a href="edit_campaign.php?id=' . $value['id'] . '"><i style="font-size:18px" class="mdi mdi-pencil"></i></a></td>
                                                

                                            </tr>';
                                            $i++;
                                    }

                                    ?>


                                </tbody>
                            </table>
                            </div>

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

<?php include('includes/footer.php') ?>
<script>

    $('#example23').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
        ],
        lengthMenu: [
            [15, 25, 50, 100, 500, 1000],
            ['15', '25', '50', '100', '500', '1000']
        ],
        "displayLength": 15,
        columnDefs: [{
            orderable: false,
            targets: 0
        }],
    });
</script>
<script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 310);
                $("#example23").tableHeadFixer();

            });
</script>