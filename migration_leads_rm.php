<?php include('includes/header.php');
        if (!$_GET['partner']) {
            $partner = array();
        } else {
            $partner = $_GET['partner'];
        }
        if (!$_GET['stage']) {
            $stage = array();
        } else {
            $stage = $_GET['stage'];
        }
        ?> -->
<!-- Start right Content here -->
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

                                    <small class="text-muted">Home >Renewal Leads</small>
                                    <h4 class="font-size-14 m-b-14 mt-1">Renewal Leads</h4>
                                </div>
                            </div>

                            <div class="btn-group float-right">
                                <div class="d-flex  justify-content-end">

                                </div>
                            </div>

                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Order Updated Successfully!
                                </div>
                            <?php } ?>
                           
                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Order Added Successfully!
                                </div>
                            <?php } ?>


                            <div class="btn-group float-right" role="group" >

                                <div class="dropdown dropdown-lg">

                                    <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" id="filter-container" role="menu">
                                        <form method="get" name="search" class="form-horizontal" role="form">

                                            <!-- <div class="row"> -->

                                                <!-- <div class="form-group col-md-3"> -->

                                                  <!-- <select class="form-control" name="sub_pro_type">
                                                    <option value="">Sub Product Type</option>
                                                    <option <?= (($_GET['sub_pro_type'] == 'Subscription Renewal') ? 'selected' : '') ?> value="Subscription Renewal">Subscription Renewal</option>
                                                    <option <?= (($_GET['sub_pro_type'] == 'Migration') ? 'selected' : '') ?> value="Migration">Migration</option>

                                                 </select> -->
                                               <!-- </div> -->
                                           </br>

                                                 <!-- <div class="form-group col-md-3"> -->
                                                    <!-- <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                    <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button> -->
                                                <!-- </div> -->

                                                <!-- <div class="form-group col-md-3">
                                                    <?php
                                                    $access = getSingleresult("select access from callers where user_id='" . $_SESSION['user_id'] . "'");
                                                    if ($access) {
                                                        $res = db_query("select * from partners where id in (" . $access . ")");
                                                    } else {
                                                        $res = db_query("select * from partners");
                                                    }

                                                    ?>
                                                    <select name="partner" id="partner" class="product_data form-control">
                                                        <option value="">Select Partner</option>
                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div> -->


                                                <!-- <div class="form-group col-md-3">

                                                    <select class="form-control" name="dtype">
                                                        <option value="">Date Type</option>
                                                        <option <?= (($_GET['dtype'] == 'created') ? 'selected' : '') ?> value="created">Created Date</option>
                                                        <option <?= (($_GET['dtype'] == 'close') ? 'selected' : '') ?> value="close">Close Date</option>
                                                        <option <?= (($_GET['dtype'] == 'license_end') ? 'selected' : '') ?> value="license_end">License End Date</option>

                                                    </select>
                                                </div>
 -->
                                                <!-- <div class="form-group col-md-4">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['license_from'] ?>" class="form-control" id="license_from" name="license_from" placeholder="Date From" autocomplete="off" />

                                                        <input type="text" value="<?php echo @$_GET['license_to'] ?>" class="form-control" id="license_to" name="license_to" placeholder="Date To" autocomplete="off" />
                                                    </div>
                                                </div> -->
                                            </div>

                                            <div class="row">
                                                <!-- <div class="form-group col-md-3">
                                                    <?php
                                                    $months = array();
                                                    for ($i = 0; $i < 12; $i++) {
                                                        $timestamp = mktime(0, 0, 0, date('n') + $i, 1);
                                                        $months[date('n', $timestamp)] = date('F', $timestamp);
                                                    }
                                                    ?>
                                                    <select name="license_end_month" class="product_data form-control">
                                                        <option value="">License End Month</option>
                                                        <?php
                                                        foreach ($months as $num => $name) { ?>
                                                            <option value="<?= $num ?>" <?php if (@$_GET['license_end_month'] == $num) {
                                                                                            echo "selected";
                                                                                        } ?>><?= $name ?></option>
                                                        <?php  }
                                                        ?>
                                                    </select>
                                                </div> -->

                                               
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>

                            <div class="table-responsive">

                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">S.No.</th><!-- 
                                            <th data-sortable="true">Submitted by</th>
                                            <th data-sortable="true">License Number</th> -->
                                            <th data-sortable="true">License End Date</th>
                                            <th data-sortable="true">Quantity</th>
                                            <th data-sortable="true">Company Name</th>
                                            <!-- <th data-sortable="true">Date of Submission</th> -->
                                            <th data-sortable="true">Sub Product Type</th>
                                            <th data-sortable="true">Status</th>
                                            <th data-sortable="true">Stage</th>
                                            <th data-sortable="true">Caller</th>
                                            <th data-sortable="true">Close Date</th>
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
 <?php 
//  if (count($partner) > 1) {
//             $part = implode(',', $partner);
//         } else {
//             $part = $_GET['partner'][0];
//         } 

if (count($campaign) > 1) {
        $campaign_arr = implode('","', $campaign);
        //print_r($campaign_arr);die;
    } else if (!$campaign_flag) {
        $campaign_arr = $_GET['campaign'][0];
    } else {
        $campaign_arr = $_GET['campaign'];
    }

include('includes/footer.php') ?>
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
            [15, 25, 50, 100, 500, 1000],
            ['15', '25', '50', '100', '500', '1000']
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "get_renewal_leads_admin.php", // json datasource
            type: "post", // method  , by default get
            data: function(d) {
                // d.partners = "<?= $part ?>";
                d.partner = "<?= $_GET['partner'] ?>";
                d.license_from = "<?= $_GET['license_from'] ?>";
                d.license_to = "<?= $_GET['license_to'] ?>";
                d.license_end_month = "<?= $_GET['license_end_month'] ?>";
                d.campaign = '<?= $campaign_arr ?>';
                d.campaign_check = "<?= $_GET['campaign_data'] ?>";
                d.dtype = "<?= $_GET['dtype'] ?>";
                d.sub_pro_type = "Migration";
                // etc
            },
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                $("#leads_processing").css("display", "none");

            }
        },
        "order": [
            [6, "desc"]
        ],
        columnDefs: [{
            orderable: false,
            targets: 0
        }],

        'columns': [{
                data: 'id'
            },
            // {
            //     data: 'r_user'
            // },
            // {
            //     data: 'license_number'
            // },
            {
                data: 'license_end_date'
            },
            {
                data: 'quantity'
            },
            {
                data: 'company_name'
            },
            // {
            //     data: 'created_date'
            // },
            {
                data: 'sub_product_type'
            },
            {
                data: 'status'
            },
            {
                data: 'stage'
            },
            {
                data: 'caller'
            },
            {
                data: 'partner_close_date'
            },
        ]
    });
    // $('#example23').DataTable({
    //     dom: 'Bfrtip',
    //     buttons: [
    //         'copy', 'csv', 'excel', 'pdf', 'print'
    //     ]
    // });

    jQuery("#search_toogle").click(function() {
        jQuery(".search_form").toggle("fast");
    });

    var wfheight = $(window).height();

    $('.fixed-table-body').height(wfheight - 195);



    $('.fixed-table-body').slimScroll({
        color: '#00f',
        size: '10px',
        height: 'auto',


    });

    function show_import() {
        $.ajax({
            type: 'POST',
            url: 'import_renew.php',
            success: function(response) {
                $("#myModal1").html();
                $("#myModal1").html(response);

                $('#myModal1').modal('show');
                $('.preloader').hide();
            }
        });

    }

    function clear_search() {
        window.location = 'migration_leads_rm.php';
    }

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


    function cd_change(ids, id) {
        //$('.preloader').show();
        $.ajax({
            type: 'POST',
            url: 'cd_change.php',
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

    $(function() {
        $('#datepicker-close-date').datepicker({
            format: 'yyyy-mm-dd',
            //startDate: '-3d',
            autoclose: !0

        });

    });

    function change_cdDate(cd_date, id, ids) {
        if (cd_date != '') {
            $('#myModal1').modal('hide');
            $.ajax({
                type: 'post',
                url: 'change_cdDate.php',
                data: {
                    cd_date: cd_date,
                    lead_id: id
                },
                success: function(res) {
                    var res = $.trim(res);

                    if (res == 'success') {
                        swal({
                            title: "Done!",
                            text: "Close Date changed Successfully.",
                            type: "success"
                        }, function() {
                            $('#myModal1').modal('hide');
                            var ids2 = "'but2" + id + "'";
                            //alert(ids2);
                            var newDate = convertDate(cd_date);
                            var link = newDate + '<a href="javascript:void(0)" title="Change Close Date" id=but2' + id + ' onclick="cd_change(' + ids2 + ',' + id + ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>'
                            $("#" + ids).parent().html(link);
                            $('#leads').DataTable().ajax.reload();
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

    function chage_stage(stage, id, ids, substage, op, order_price, date1, instalment1, date2, instalment2, date3, instalment3, date4, instalment4, date5, instalment5, date6, instalment6, Psubstage) {

        //alert(stage + '' +id);
        //alert(substage);

        if (stage != '') {
            $('#myModal1').modal('hide');
            $.ajax({
                type: 'post',
                url: 'change_stage.php',
                data: {
                    stage: stage,
                    substage: substage,
                    lead_id: id,
                    op: op,
                    order_price: order_price,
                    date1: date1,
                    instalment1: instalment1,
                    date2: date2,
                    instalment2: instalment2,
                    date3: date3,
                    instalment3: instalment3,
                    date4: date4,
                    instalment4: instalment4,
                    date5: date5,
                    instalment5: instalment5,
                    date6: date6,
                    instalment6: instalment6,
                    Psubstage: Psubstage
                },
                success: function(res) {
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
                            $('#leads').DataTable().ajax.reload();
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

    function convertDate(dateString) {
        var p = dateString.split(/\D/g)
        return [p[2], p[1], p[0]].join("-")
    }

    $(document).ready(function() {
        $('.multiselect_campaign').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select Campaign'
        });

    });
</script>
<script>
    $(document).ready(function() {
        var wfheight = $(window).height();
        $('.dataTables_wrapper').height(wfheight - 340);
        $("#leads").tableHeadFixer();

    });
</script>