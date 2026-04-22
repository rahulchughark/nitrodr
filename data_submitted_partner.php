<?php include('includes/header.php');
if ($_SESSION['user_type'] != 'REVIEWER') admin_page();
ini_set('max_execution_time', 0);
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
                            <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home >Data Submitted Partner</small>
                                            <h4 class="font-size-14 m-0 mt-1">Data Submitted Partner</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="" role="group">

                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                        <div class="dropdown dropdown-lg">

                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                                <form method="get" name="search" class="form-horizontal" role="form">

                                                    <?php if (!is_array($partner)) {
                                                        $val = $partner;
                                                        $partner = array();
                                                        $partner['0'] = $val;
                                                    }
                                                    if (!is_array($product)) {
                                                        $val = $product;
                                                        $product = array();
                                                        $product['0'] = $val;
                                                    }
                                                    if (!is_array($product_type)) {
                                                        $val = $product_type;
                                                        $product_type = array();
                                                        $product_type['0'] = $val;
                                                    }
                                                        $res = db_query("select * from partners where status='Active' order by name");
                                                    ?>
                                                
                                                    <div class="row">
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <div class="input-daterange input-group" id="datepicker-close-date">
                                                                <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                                <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                            </div>
                                                        </div>
                                                        <?php if (!is_array($status)) {
                                                                $val = $status;
                                                                $status = array();
                                                                $status['0'] = $val;
                                                                $status_flag = 1;
                                                            }
                                                            ?>

                                                        <?php if (!is_array($sub_product)) {
                                                                $val = $sub_product;
                                                                $sub_product = array();
                                                                $sub_product['0'] = $val;
                                                                $sub_product_flag = 1;
                                                            }
                                                            ?>
                                                            <div class="form-group col-md-6 col-xl-3">

                                                            <select name="product[]" class="product_data form-control" data-live-search="true" multiple>

                                                                <?php $query = selectProduct('tbl_product');
                                                                while ($row = db_fetch_array($query)) { ?>
                                                                    <option <?= (@in_array($row['id'], $product) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            </div>
                                                            <div class="form-group col-md-6 col-xl-3">

                                                            <?php if ($_GET['product']) { ?>
                                                                <select name="product_type[]" class="multiselect_productType form-control" data-live-search="true" multiple>
                                                                    <?php $query = db_query('select * from tbl_product_pivot where status=1 and product_id in ("' . implode('", "', $_GET['product']) . '")');
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (@in_array($row['id'], $product_type) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_type']) ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <div id="product_type_data">
                                                                <select name="product_type" id="product_type" class="form-control">
                                                                    <option value="">Select Product Type</option>
                                                                </select>    
                                                            </div>
                                                            <?php } ?>

                                                            </div>

                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="partner[]" id="partner" class="multiselect_partner form-control" data-live-search="true" multiple>
                                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                                    <option <?= (@in_array($row['id'], $partner) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-3">
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
												<th style="min-width: 220px">Partner Name</th>
                                                <th colspan="3">Received</th>
                                                <th colspan="3">Qualified</th>
                                                <th colspan="3">Re-Submission</th>
                                                <th colspan="3">Unqualified</th>
                                                <th colspan="3">Pending</th>
                                                <th colspan="3">On-Hold</th>
                                            </tr>
                                            <tr>
                                                <th></th>
												<th></th>
                                                <th>Fresh</th>
                                                <th>Opportunity</th>
                                                <th>Renewal</th>
                                                <th>Fresh</th>
                                                <th>Opportunity</th>
                                                <th>Renewal</th>
                                                <th>Fresh</th>
                                                <th>Opportunity</th>
                                                <th>Renewal</th>
                                                <th>Fresh</th>
                                                <th>Opportunity</th>
                                                <th>Renewal</th>
                                                <th>Fresh</th>
                                                <th>Opportunity</th>
                                                <th>Renewal</th>
                                                <th>Fresh</th>
                                                <th>Opportunity</th>
                                                <th>Renewal</th>
                                            </tr>
                                    </thead>


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
                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
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
                "ajax": {
                    url: "get_data_submitted_partner.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                    d.d_from = "<?= $_GET['d_from'] ?>";
                    d.d_to = "<?= $_GET['d_to'] ?>";
                    d.partner = '<?= json_encode($_GET['partner']) ?>';
                    d.product = '<?= json_encode($_GET['product']) ?>';
                    d.product_type = '<?= json_encode($_GET['product_type']) ?>';
                    },

                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                },
                "order": [
                    [1, "desc"]
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],

                'columns': [
                 { data: 'id' },
                 {data: 'r_name' },
                 {data:'receivedF'},
                 {data:'receivedO'},
                 {data:'receivedR'},
                 {data:'qualifiedF'},
                 {data:'qualifiedO'},
                 {data:'qualifiedR'},
                 {data:'re_submissionF'},
                 {data:'re_submissionO'},
                 {data:'re_submissionR'},
                 {data:'unqualifiedF'},
                 {data:'unqualifiedO'},
                 {data:'unqualifiedR'},
                 {data:'pendingF'},
                 {data:'pendingO'},
                 {data:'pendingR'},
                 {data:'on_holdF'},
                 {data:'on_holdO'},
                 {data:'on_holdR'},
              ]
            });



            $(document).ready(function() {
                $('.multiselect_partner').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Partner',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    maxHeight: 150
                });
                $('.multiselect_productType').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product Type',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.product_data').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_productType').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product Type',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });

            function clear_search() {
                window.location = 'data_submitted_partner.php';
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
                $('.dataTables_wrapper').height(wfheight - 311);
                $("#leads").tableHeadFixer();

            });

            $(document).ready(function() {
                $('.product_data').on('change', function() {
                    //alert('abc');
                    var productID = $(this).val();
                    //alert(productID);
                    if (productID) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxProductTypeAdmin.php',
                            data: 'product_id=' + productID,
                            success: function(html) {
                                $('#product_type_data').html(html);

                            },
                        });
                    }
                });
            });

            function convertDate(dateString) {
                var p = dateString.split(/\D/g)
                return [p[2], p[1], p[0]].join("-")
            }
        </script>