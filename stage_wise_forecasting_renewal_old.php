<?php include('includes/header.php');
//print_r($_SESSION['user_id']);die;
if ($_SESSION['user_type'] != 'REVIEWER') admin_page();
ini_set('max_execution_time', 0);
if($_GET['d_from'] && $_GET['d_to'])
{
    if($_GET['d_from'] == $_GET['d_to'])
    {
        $dat=" and DATE(created_date)='".$_GET['d_from']."'";	
    } else {
        $dat=" and DATE(created_date)>='".$_GET['d_from']."' and DATE(created_date)<='".$_GET['d_to']."'";	
    }
}
?>
<style>
.dataTable td{
    text-align: left;
    padding: 12px 20px 12px 20px;
}
.dataTables_wrapper .dt-buttons {
    margin-bottom: 0.8rem;
}

.filter_wrap_2 {
    width: 370px;
}
b, strong {
    font-weight: 600;
}
</style>
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
                            <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">

                                            <small class="text-muted">Home >Stage Wise Forecasting</small>
                                            <h4 class="font-size-14 m-0 mt-1">Stage Wise Forecasting</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="btn-group float-right" role="group">
                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>


                                        <div class="dropdown dropdown-lg">

                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                                <form method="get" name="search" class="form-horizontal" role="form">
                                             
                                                    <div class="row">
                                                        <!-- <div class="form-group col-md-3">
                                                            <select name="dtype" class="form-control" id="date_type">
                                                            <option value="">Select Date Type</option>
                                                            <option <?= (($_GET['dtype'] == 'created') ? 'selected' : '') ?> value="created">Created Date</option>
                                                                    <option <?= (($_GET['dtype'] == 'actioned_date') ? 'selected' : '') ?> value="actioned_date">Actioned Date</option>
                                                                    <option <?= (($_GET['dtype'] == 'close') ? 'selected' : '') ?> value="close">Close Date</option>
                                                            </select>
                                                        </div> -->
                                                        <div class="form-group col">
                                                            <div class="input-daterange input-group" id="datepicker-close-date">
                                                                <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                                <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                            </div>
                                                        </div>
                
                                                        <!-- <?php
                                                                $sqlTag = "select * from tag where 1";
                                                                $tagList = db_query($sqlTag);
                                                                ?>

                                                                <?php if (!is_array($tag)) {
                                                                    $val = $tag;
                                                                    $tag = array();
                                                                    $tag['0'] = $val;
                                                                } ?>
                                                        <div class="form-group col-md-3">
                                                        <select name="tag[]" data-live-search="true" multiple class="form-control" id="multiselecttag">
                                                            <?php while ($tags = db_fetch_array($tagList)) { ?>
                                                                <option value="<?= $tags['id'] ?>" <?= (in_array($tags['id'], $tag) ? 'selected' : '') ?>><?= $tags['name'] ?></option>
                                                            <?php } ?>
                                                            </select>
                                                            </div> -->

                                                        <div class="form-group col-auto">
                                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive" id="MyDiv">

                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                            <tr>
                                            <th>S.No.</th>
                                                <th>Stage</th>
                                                <th>Total Amount</th>
                                                <th>Forecasting Percentage</th>
                                                <th>Forecasting Value(Without Tax)</th>
                                                <th>Forecasting Value(With Tax)</th>
                                            </tr>
                                    </thead>
                                    <tfoot>
                                        <?php 
                                            $totalT = getSingleResult("SELECT SUM(grand_total_price) AS total_grand_price FROM orders WHERE stage IN ('Demo','Proposal Submission','Commit','PO/CIF Issued','Advance Payment','Billing') and is_opportunity = 1  and agreement_type='Renewal' $dat");
                                            $perTotal = round(getSingleResult("SELECT SUM(CASE WHEN stage = 'Demo' THEN grand_total_price * 0.20 WHEN stage = 'Proposal Submission' THEN grand_total_price * 0.25 WHEN stage = 'Commit' THEN grand_total_price * 0.50 WHEN stage = 'PO/CIF Issued' THEN grand_total_price * 0.90 WHEN stage = 'Advance Payment' THEN grand_total_price * 0.95 WHEN stage = 'Billing' THEN grand_total_price * 1.00 ELSE 0 END ) AS total_weighted_price FROM orders WHERE stage IN ('Demo','Proposal Submission','Commit','PO/CIF Issued','Advance Payment','Billing') and agreement_type='Renewal' $dat"));
                                            $grandTT = getSingleResult("SELECT SUM(grand_total_price) AS total_grand_price FROM orders WHERE is_opportunity = 1  and agreement_type='Renewal' $dat");
                                        ?>
                                                    <tr class="bg-default" style="border-top: 5px solid #fff;">
                                                        <th></th>
                                                        <th><strong>Total</strong></th>
                                                        <th><strong><?= $totalT ? $totalT : 0 ?></strong></th>
                                                        <th><strong>100%</strong></th>
                                                        <th><strong><?= $perTotal ? $perTotal : 0 ?></strong></th>
                                                        <th></th>
                                                    </tr>
                                                    <tr class="bg-default">
                                                        <th></th>
                                                        <th colspan="2"><strong>Grand Total</strong></th>
                                                        <th colspan="3"><strong><?= $grandTT ? $grandTT : 0 ?></strong></th>
                                                    </tr>
                                                </tfoot>

                                </table>
                            </div>

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        <div id="myModal1" class="modal" role="dialog">


        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->

        <?php include('includes/footer.php') ?>
        <script>
            $('#leads').DataTable({
                dom: 'Bfrtip',
                "displayLength": 15,
                buttons: [
                    <?php if($_SESSION['download_status'] == 1){ ?>
                    'copy', 'csv', 'excel', 'pdf',  'print', 'pageLength',
                    
                <?php }else{ ?> 'pageLength'  <?php } ?>
                    
                ],
                lengthMenu: [
                    [5, 15, 25, 50, 100, 500, 1000],
                    ['5', '15', '25', '50', '100', '500', '1000']
                ],
                "stateSave": true,
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    url: "get_stage_wise_forecasting_renewal.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                    d.d_from = "<?= $_GET['d_from'] ?>";
                    d.d_to = "<?= $_GET['d_to'] ?>";
                    d.d_type = "<?= $_GET['dtype'] ?>";
                    d.tag = '<?= json_encode($_GET['tag']) ?>';
                    },

                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                },
                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],

                'columns': [
                    { data: 'id' },
                 { data: 'stage' },
                 { data: 'total_amount' },
                 {data:'percentage'},
                 { data: 'value' },
                 { data: 'valuewithtax' },
              ]
            });

            $(document).ready(function() {
                $('#multiselecttag').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Tag',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });

            function clear_search() {
                window.location = 'stage_wise_forecasting.php';
            }

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 332);
                $("#leads").tableHeadFixer();

            });


            function convertDate(dateString) {
                var p = dateString.split(/\D/g)
                return [p[2], p[1], p[0]].join("-")
            }
        </script>