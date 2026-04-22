<?php include('includes/header.php'); ?>
<!-- ============================================================== -->

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

                                    <small class="text-muted">Home >Leads</small>
                                    <h4 class="font-size-14 m-0 mt-1">Leads</h4>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Lead Added Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['fail'] == 'ext') { ?>
                                <div class="alert alert-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-warning"><i class="fa fa-check-circle"></i> Error!</h3>Daily quota for leads exhausted.
                                </div>
                            <?php } ?>
                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Lead Updated Successfully!
                                </div>
                            <?php } ?>



                            <div class="table-responsive">
                                <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                    <a href="add_iss_leads.php"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Add Leads" class="right-side bottom-right waves-effect waves-light btn-light btn btn-circle btn-xs pull-right m-l-10"><i class="ti-plus "></i></button></a>

                                    <button type="button" class="btn btn-xs btn-light ml-1" id="filter-box" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>
                                    <div class="dropdown dropdown-lg">

                                        <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">
                                            <form method="get" name="search" class="form-material">
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
                                            if ($_SESSION['sales_manager'] != 1) {
                                                $res = db_query("select * from partners where status='Active'");
                                            } else {
                                                $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                            }
                                            ?>
                                           
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <select name="dtype" class="form-control" id="date_type">
                                                    <option value="">Select Date Type</option>
                                                    <option <?= (($_GET['dtype'] == 'created') ? 'selected' : '') ?> value="created">Created Date</option>
                                                            <option <?= (($_GET['dtype'] == 'actioned_date') ? 'selected' : '') ?> value="actioned_date">Actioned Date</option>
                                                            <option <?= (($_GET['dtype'] == 'close') ? 'selected' : '') ?> value="close">Close Date</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
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
                                                <div class="form-group col-md-3">
                                                    <select name="status[]" class="form-control multiselect_status" data-live-search="true" multiple>
                                                    <option <?= (in_array('Approved', $status) ? 'selected' : '') ?> value="Approved">Qualified</option>
                                                            <option <?= (in_array('Cancelled', $status) ? 'selected' : '') ?> value="Cancelled">Unqualified</option>
                                                            <option <?= (in_array('Undervalidation', $status) ? 'selected' : '') ?> value="Undervalidation">Under Validation</option>
                                                            <option <?= (in_array('Pending', $status) ? 'selected' : '') ?> value="Pending">Pending</option>
                                                            <option <?= (in_array('Already locked', $status) ? 'selected' : '') ?> value="Already locked">Already locked</option>
                                                            <option <?= (in_array('Insufficient Information', $status) ? 'selected' : '') ?> value="Insufficient Information">Insufficient Information</option>
                                                            <option <?= (in_array('Incorrect Information', $status) ? 'selected' : '') ?> value="Incorrect Information">Incorrect Information</option>
                                                            <option <?= (in_array('Out Of Territory', $status) ? 'selected' : '') ?> value="Out Of Territory">Out Of Territory</option>
                                                            <option <?= (in_array('Duplicate Record Found', $status) ? 'selected' : '') ?> value="Duplicate Record Found">Duplicate Record Found</option>
                                                    </select>
                                                </div>
                                                <?php if (!is_array($sub_product)) {
                                                        $val = $sub_product;
                                                        $sub_product = array();
                                                        $sub_product['0'] = $val;
                                                        $sub_product_flag = 1;
                                                    }
                                                     ?>
                                                    <div class="form-group col-md-3">
                                                        <select name="sub_product[]" class="multiselect_productType form-control" multiple>
                                                            <?php $resTP = db_query("select * from tbl_product_pivot where status=1 and product_id='1'");
                                                            while ($row = db_fetch_array($resTP)) { ?>
                                                                <option <?= (in_array($row['id'], $sub_product) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['product_type'] ?></option>
                                                            <?php  } ?>

                                                        </select>
                                                    </div>
                                            </div>

                                            <div class="row">

                                                <div class="form-group col-md-3">
                                                    <select name="partner[]" id="partner" class="multiselect_partner form-control" data-live-search="true" multiple>
                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (in_array($row['id'], $partner) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <?php
                                                        $sqlStage = "select * from stages where 1";
                                                        $stageList = db_query($sqlStage);
                                                        ?>

                                                        <?php if (!is_array($stage)) {
                                                            $val = $stage;
                                                            $stage = array();
                                                            $stage['0'] = $val;
                                                            $st_flag = 1;
                                                        } ?>
                                                <div class="form-group col-md-3">
                                                <select name="stage[]" data-live-search="true" multiple class="form-control" id="multiselect">
                                                    <?php while ($stag = db_fetch_array($stageList)) { ?>
                                                        <option value="<?= $stag['stage_name'] ?>" <?= (in_array($stag['stage_name'], $stage) ? 'selected' : '') ?>><?= $stag['stage_name'] ?></option>
                                                    <?php } ?>
                                                    </select>
                                                    </div>

                                                <div class="form-group col-md-2">
                                                    <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                    <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                </div>
                                            </div>
                                            </form>

                                        </div>
                                    </div>

                                </div>



                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                        <th>S.No.</th>
												<th>DR Code</th>
                                                <th>Reseller Name</th>
                                                <th>School Board</th>
                                                <th>Organization Name</th>
                                                <th>Number of Students</th>
                                                <th>Sub Product</th>
                                                <th>Date of Submission</th>
											    <th>Status</th>
											    <th>Stage</th>
											    <th>Closed Date</th>
                                        </tr>

                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

</div>


<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<div id="myModal1" class="modal" role="dialog">


</div>

<?php include('includes/footer.php') ?>
<script>
    $('#leads').DataTable({
        dom: 'Bfrtip',
        "displayLength": 15,

        "scrollX": false,
        "fixedHeader": true,

        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
        ],
        lengthMenu: [
            [15, 25, 50, 100, 500, 1000,20000,50000],
            ['15', '25', '50', '100', '500', '1000', '20000', '50000']
        ],
        "processing": false,
        "serverSide": true,
        "ajax": {
            url: "get_iss_leads.php", // json datasource
            type: "post", // method  , by default get
            data: function(d) {
                d.d_from = "<?= $_GET['d_from'] ?>";
                    d.d_to = "<?= $_GET['d_to'] ?>";
                    d.d_type = "<?= $_GET['dtype'] ?>";
                    d.sub_product = '<?= json_encode($_GET['sub_product']) ?>';
                    d.stage = '<?= json_encode($_GET['stage']) ?>';
                    d.status = '<?= json_encode($_GET['status']) ?>';
                    d.users = '<?= json_encode($_GET['users']) ?>';
                    d.quantity = '<?= json_encode($_GET['quantity']) ?>';
                    d.state = '<?= json_encode($_GET['state']) ?>';
                    d.school_board = '<?= json_encode($_GET['school_board']) ?>';
                    d.partner = '<?= json_encode($_GET['partner']) ?>';
            },
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                $("#leads_processing").css("display", "none");

            }
        }
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
                $('.multiselect_status').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Status',
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
                $('#multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Stage',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });

    function clear_search() {
        window.location = 'iss_leads.php';
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

<script>
    // jQuery("#search_toogle").click(function() {
    //     jQuery(".search_form").toggle("fast");
    // });

    // var wfheight = $(window).height();
    // $('.fixed-table-body').height(wfheight - 195);
    // $('.fixed-table-body').slimScroll({
    //     color: '#00f',
    //     size: '10px',
    //     height: 'auto',

    // });

    function stage_change(ids, id) {
        //$('.preloader').show(); 
        $.ajax({
            type: 'POST',
            url: 'stage_change.php',
            data: {
                pid: id,
                ids: ids
            },
            success: function(response) {
                $("#myModal1").html();
                $("#myModal1").html(response);

                $('#myModal1').modal('show');
                $('.preloader').hide();
            }
        });
    }

    function chage_stage(stage, id, ids, substage, payment_status, attachments, demo_datetime) {

        if (stage != '') {
            var formData = new FormData();
            formData.append('stage', stage);
            formData.append('substage', substage);
            formData.append('lead_id', id);
            formData.append('payment_status', payment_status);
            formData.append('demo_datetime', demo_datetime);

            // Append files to FormData
            for (var i = 0; i < attachments.length; i++) {
                formData.append('attachments[]', attachments[i]);
            }

            $('#myModal1').modal('hide');
            $.ajax({
                type: 'post',
                url: 'change_stage.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    // console.log('h'+res+'h');
                    var res = $.trim(res);
                    if (res == 'success') {

                        swal({
                            title: "Done!",
                            text: "Stage changed Successfully.",
                            type: "success"
                        }, function() {
                            $('#myModal1').modal('hide');
                            var idss = "'but" + id + "'";
                            var link = stage + '<a href="javascript:void(0)" title="Change Stage" id=but' + id + ' onclick="stage_change(' + idss + ',' + id + ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>'
                            $("#" + ids).parent().html(link);
                        });

                    } else {
                        swal({
                            title: "Error!",
                            text: res,
                            type: "error"
                        }, function() {

                        });
                    }
                }
            });
        }
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
        $('.dataTables_wrapper').height(wfheight - 325);
        $("#leads").tableHeadFixer();

    });
</script>