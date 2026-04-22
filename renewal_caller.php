<?php include('includes/header.php'); ?>

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

                                    <small class="text-muted">Home >Renewal Leads</small>
                                    <h4 class="font-size-14 m-0 mt-1">Renewal Leads</h4>
                                </div>
                            </div>

                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Order Added Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Order Updated Successfully!
                                </div>
                            <?php } ?>

                            <div class="table-responsive">
                                <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                    <!-- <a href="export_partner_lead.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>">
                                    <button data-toggle="tooltip" data-placement="left" title="" data-original-title="Excel Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-share "></i></button></a> -->
                                    <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>
                                    <div class="dropdown dropdown-lg">

                                        <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                            <form method="get" name="search" class="form-horizontal" role="form">

                                                <div class="form-group">
                                                    <?php $res = db_query("select * from partners where status='Active' ");
                                                    ?>
                                                    <select name="partner" id="partner" class="product_data form-control">
                                                        <option value="">Select Partner</option>
                                                        <?php
                                                        while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php  } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['license_from'] ?>" class="form-control" id="license_from" name="license_from" placeholder="Date From" autocomplete="off" />

                                                        <input type="text" value="<?php echo @$_GET['license_to'] ?>" class="form-control" id="license_to" name="license_to" placeholder="Date To" autocomplete="off" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <?php
                                                    $months = array();
                                                    for ($i = 0; $i < 12; $i++) {
                                                        $timestamp = mktime(0, 0, 0, date('n') - $i, 1);
                                                        $months[date('n', $timestamp)] = date('F', $timestamp);
                                                    }
                                                    ?>
                                                    <select name="license_end_month" class="product_data form-control">
                                                        <option value="">Select Month</option>
                                                        <?php
                                                        foreach ($months as $num => $name) { ?>
                                                            <option value="<?= $num ?>" <?php if (@$_GET['license_end_month'] == $num) {
                                                                                            echo "selected";
                                                                                        } ?>><?= $name ?></option>
                                                        <?php  }
                                                        ?>
                                                    </select>
                                                </div>

                                                <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                            </form>
                                        </div>
                                    </div>

                                </div>



                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">S.No.</th>
                                            <th data-sortable="true">Reseller (Submitted by)</th>
                                            <th data-sortable="true">Lead Type</th>
                                            <th data-sortable="true">Quantity</th>
                                            <th data-sortable="true">Company Name</th>
                                            <th data-sortable="true">Date of Submission</th>
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
            [15, 25, 50, 100, 500, 1000],
            ['15', '25', '50', '100', '500', '1000']
        ],
        "processing": false,
        "serverSide": true,
        "ajax": {
            url: "get_renewal_callers.php", // json datasource
            type: "post", // method  , by default get
            data: function(d) {
                d.partner = "<?= $_GET['partner'] ?>";
                d.license_from = "<?= $_GET['license_from'] ?>";
                d.license_to = "<?= $_GET['license_to'] ?>";
                d.license_end_month = "<?= $_GET['license_end_month'] ?>";
                // d.custom = $('#myInput').val();
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
            {
                data: 'r_user'
            },
            {
                data: 'lead_type'
            },
            {
                data: 'quantity'
            },
            {
                data: 'company_name'
            },
            {
                data: 'created_date'
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

    $('#industry').on('change', function() {

        var industry = $(this).val();
        //alert(stateID);
        if (industry) {
            $.ajax({
                type: 'POST',
                url: 'ajaxindustry.php',
                data: 'industry_id=' + industry,
                success: function(response) {
                    $('#sub_industry').html(response);
                },
                error: function() {
                    $('#sub_industry').html('There was an error!');
                }
            });
        } else {
            $('#sub_industry').html('<option value="">Select industry first</option>');
        }
    });

    $(document).ready(function() {
        $('#partner').on('change', function() {
            //alert("hi");
            var partnerID = $(this).val();
            if (partnerID) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxusers.php',
                    data: 'partner_id=' + partnerID,
                    success: function(html) {
                        //alert(html);
                        $('#users').html(html);
                    }
                });
            }
        });
    });

    $(function() {
        $('#datepicker-close-date').datepicker({
            format: 'yyyy-mm-dd',
            //startDate: '-3d',
            autoclose: !0

        });

    });

    function clear_search() {
        window.location = 'renewal_caller.php';
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

    function chage_stage(stage, id, ids, substage, op, date1, instalment1, date2, instalment2, date3, instalment3, date4, instalment4) {

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
                    date1: date1,
                    instalment1: instalment1,
                    date2: date2,
                    instalment2: instalment2,
                    date3: date3,
                    instalment3: instalment3,
                    date4: date4,
                    instalment4,
                    instalment4
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
</script>

<script>
    $(document).ready(function() {
        var wfheight = $(window).height();
        $('.dataTables_wrapper').height(wfheight - 320);
        $("#leads").tableHeadFixer();

    });

    $(document).ready(function() {
        $('#multiselect').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select an Option'
        });

        $('#multiselect1').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select an Option'
        });

    });
</script>