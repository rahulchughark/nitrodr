<?php include('includes/header.php');
admin_page();

$_GET['d_from'] = htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8');
$_GET['d_to'] = htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8');

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
                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Modification Report</small>
                                    <h4 class="font-size-14 m-0 mt-1">Modification Report</h4>
                                </div>
                            </div>

                            <div class="btn-group float-right" role="group" style="margin-top:12px;">

                                <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                                        <form method="get" name="search" id="search-form">

                                            <div class="form-group">
                                                <select class="form-control" name="dtype">
                                                    <option value="">Select Field Category</option>
                                                    <option <?= (($_GET['dtype'] == 'Ownership') ? 'selected' : '') ?> value="Ownership">Ownership</option>
                                                    <option <?= (($_GET['dtype'] == 'Product Type') ? 'selected' : '') ?> value="Product Type">Product Type</option>
                                                    <option <?= (($_GET['dtype'] == 'Company Name') ? 'selected' : '') ?> value="Company Name">Company Name</option>
                                                    <option <?= (($_GET['dtype'] == 'Parent Company') ? 'selected' : '') ?> value="Parent Company">Parent Company Name</option>
                                                    <option <?= (($_GET['dtype'] == 'Industry') ? 'selected' : '') ?> value="Industry">Industry</option>
                                                    <option <?= (($_GET['dtype'] == 'Contact Person') ? 'selected' : '') ?> value="Contact Person">Full Name</option>
                                                    <option <?= (($_GET['dtype'] == 'Email') ? 'selected' : '') ?> value="Email">Email ID</option>
                                                    <option <?= (($_GET['dtype'] == 'Mobile') ? 'selected' : '') ?> value="Mobile">Mobile Number</option>
                                                    <option <?= (($_GET['dtype'] == 'Landline Number1') ? 'selected' : '') ?> value="Landline Number1">Landline Number1</option>
                                                    <option <?= (($_GET['dtype'] == 'Landline Number2') ? 'selected' : '') ?> value="Landline Number2">Landline Number2</option>
                                                    <option <?= (($_GET['dtype'] == 'Quantity') ? 'selected' : '') ?> value="Quantity">Quantity</option>
                                                    <option <?= (($_GET['dtype'] == 'Stage') ? 'selected' : '') ?> value="Stage">Stage</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                    <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                    <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                        </form>
                                    </div>
                                </div>
                            </div>      

                            <div class="table-responsive">

                                <table id="visit_lac" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Lead Owner</th>
                                            <th>Modified By</th>
                                            <th>Company Name</th>
                                            <th>Field Category</th>
                                            <th>Old Value</th>
                                            <th>New Value</th>
                                            <th>Modified Date</th>
                                            <th>Modified Time</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->

    <?php include('includes/footer.php') ?>
    <script>
        $('#visit_lac').DataTable({

            dom: 'Bfrtip',
            "displayLength": 15,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            ],
            lengthMenu: [
                [15, 25, 50, 100, 500, 1000, 10000, ],
                ['15', '25', '50', '100', '500', '1000', '10000']
            ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "getModifiedData.php", // json datasource
                type: "post", // method  , by default get
                data: function(d) {
                    d.d_from = "<?= htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8') ?>";
                    d.d_to = "<?= htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8') ?>";
                    d.dtype = "<?= $_GET['dtype'] ?>";

                },
                error: function() { // error handling
                    $(".employee-grid-error").html("");
                    $("#visit_lac").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display", "none");

                }
            }
        });


        function clear_search() {
            window.location = 'modification_report.php';
        }

        $(function() {
            $('#datepicker-close-date').datepicker({
                format: 'yyyy-mm-dd',
                //startDate: '-3d',
                autoclose: !0

            });

        });
    </script>

    <script>
        $(document).ready(function() {
            var wfheight = $(window).height();
            $('.dataTables_wrapper').height(wfheight - 310);
            $("#visit_lac").tableHeadFixer();

        });
    </script>