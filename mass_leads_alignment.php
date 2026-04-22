<?php include('includes/header.php');

$_GET['d_from'] = htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8');
$_GET['d_to'] = htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8');

if ($_GET['d_from'] && $_GET['d_to']) {
	if ($_GET['d_from'] == $_GET['d_to']) {
		$dat = " and 1";
	} else {
		$dat = " and  ((DATE(o.created_date) BETWEEN  '" . $_GET['d_from'] . "' and '" . $_GET['d_to'] . "'))";
	}
}

if ($_GET['partner']) {
	$dat .= " and o.r_name='" . $_GET['partner'] . "'";
}
if($_GET['product'])
{
$dat.=" and p.product_id='".$_GET['product']."'";
}
if($_GET['product_type'])
{
$dat.=" and p.product_type_id='".$_GET['product_type']."'";
}
// if($_GET['caller'])
// {
// $dat.=" and callers.name='".$_GET['caller']."'";
// }
$queryy = db_query("select DISTINCT(o.created_by) from orders as o left join tbl_lead_product as p on o.id=p.lead_id left join users on o.created_by=users.id where 1 " . $dat);
?>

<style>
    .table > thead > tr > th{
        z-index: 9!important;
    }
    
    table .custom-checkbox   {
        margin: 0;
    }

    thead .custom-checkbox:before {
        background-color: #efd6c6;
    }

    table .custom-checkbox label {
        color: #1B274D;
        font-size: 14px;
    }

    thead .custom-checkbox label:before {
        border-color: #1B274D; 
    }

    thead .custom-checkbox input:checked + label:before, thead .custom-checkbox input:checked + label:after {
        border-color: #1B274D;
    }


    tbody tr:nth-child(odd) .custom-checkbox:before {
        background-color: #f8f9fa;
    }

    tbody tr:nth-child(even) .custom-checkbox:before {
        background-color: rgb(247 247 248);
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
                                    <div class="media bredcrum-title">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                        <small class="text-muted">Home > Mass Lead Assignment</small>
                                        <h4 class="font-size-14 m-0 mt-1">Mass Lead Assignment</h4>
                                    </div>
                                </div>
                                </div>
                                <div class="col-auto">
                                    <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                    <!-- <button id="exportMass" data-toggle="tooltip" data-placement="left" title="" data-original-title="Excel Export" class="btn btn-xs btn-light ml-1"> -->
                                                            <!-- <i class="ti-download"></i></button> -->

                                        <div class="dropdown dropdown-lg">
                                            <button type="button" class="btn btn-xs btn-light ml-1  " aria-expanded="false" id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                                <form method="get" name="search" id="search-form">

                                                <div class="row" id="caller_section">
                                                            <div class="form-group col-md-6 col-xl-4">
                                                                    <div class="reassign_caller">
                                                                        <div class="row">
                                                                            <div class="col">
                                                                                <label>Reassign To New User</label>
                                                                                <?php $res = massLead_NewCaller('callers'); ?>
                                                                                <select name="caller" id="caller" class="form-control">
                                                                                    <option value="">Select</option>
                                                                                    <?php while ($row = db_fetch_array($res)) { ?>
                                                                                        <option value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-auto pl-0">
                                                                                <input style="margin-top:30px;" type="button" class="btn btn-primary" name="save" id="save" value="Save" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--reassign_caller-->
                                                                </div>
                                                            </div>
                                                <div class="row align-items-end" id="partner_section">
                                                            <div class="form-group col-md-4 col-xl-4 ">
                                                                    <div class="reassign_partner">
                                                                        <label>Reassign To Partner</label>
                                                                        <?php $res = db_query("SELECT id,name from partners where status='Active' order by name"); ?>
                                                                        <select name="partnerF" id="partnerF" class="form-control" data-live-search="true">
                                                                            <option value="">Select Partner</option>
                                                                            <?php while ($row = db_fetch_array($res)) { ?>
                                                                            <option value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <div id="usersD">
                                                                <select id="userF" name="userF" class="form-control" data-live-search="true" >
                                                                    <option value="">Select User</option>
                                                                </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <input type="button" class="btn btn-primary" name="save" value="Save" id="saveF"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                            <?php
                                                                    if (!is_array($partner)) {
                                                                        $val = $partner;
                                                                        $partner = array();
                                                                        $partner['0'] = $val;
                                                                    }
                                                                    if (!is_array($school_board)) {
                                                                        $val = $school_board;
                                                                        $school_board = array();
                                                                        $school_board['0'] = $val;
                                                                    }
                                                                    if (!is_array($sub_source)) {
                                                                        $val = $sub_source;
                                                                        $sub_source = array();
                                                                        $sub_source['0'] = $val;
                                                                    }
                                                                    if (!is_array($state)) {
                                                                        $val = $state;
                                                                        $state = array();
                                                                        $state['0'] = $val;
                                                                    }
                                                                    if (!is_array($city)) {
                                                                        $val = $city;
                                                                        $city = array();
                                                                        $city['0'] = $val;
                                                                    }
                                                                    if (!is_array($iss)) {
                                                                        $val = $iss;
                                                                        $iss = array();
                                                                        $iss['0'] = $val;
                                                                    }
                                                                    if (!is_array($tag)) {
                                                                        $val = $tag;
                                                                        $tag = array();
                                                                        $tag['0'] = $val;
                                                                    }
                                                            ?>
                                                        <div class="row">
                                                        <?php if($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'TEAM LEADER') { ?>
                                                            <div class="form-group col-md-4">
                                                                <?php $res = db_query("select * from partners where status='Active'"); ?>
                                                                <select name="partner[]" id="partnermulti" class="form-control" multiple>
                                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                                            <option <?= (@in_array($row['id'], $partner) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                        <?php } ?>
                                                    <div class="form-group col-md-4">
                                                                <?php $ress = db_query("select DISTINCT(school_board) from orders where school_board IS NOT NULL AND school_board <> ''"); ?>
                                                                <select name="school_board[]" id="multi_school_board" class="form-control" multiple>
                                                                    <?php while ($roww = db_fetch_array($ress)) { ?>
                                                                        <option <?= (@in_array($roww['school_board'], $school_board) ? 'selected' : '') ?> value='<?= $roww['school_board'] ?>'><?= $roww['school_board'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                    <div class="form-group col-md-4">
                                                                <?php $res = db_query("select * from sub_lead_source where status=1"); ?>
                                                                <select name="sub_source[]" id="multi_sub_source" class="form-control" multiple>
                                                                    <?php while ($row = db_fetch_array($res)) { ?>
                                                                        <option <?= (@in_array($row['sub_lead_source'], $sub_source) ? 'selected' : '') ?> value='<?= $row['sub_lead_source'] ?>'><?= $row['sub_lead_source'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        <div class="form-group col-md-4">
                                                                    <?php $res = db_query("select * from states order by name"); ?>
                                                                    <select name="state[]" id="multistate" class="form-control" multiple>
                                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                                            <option <?= (@in_array($row['id'], $state) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                        <div class="form-group col-md-4">
                                                                    <?php $res = db_query("select * from cities order by city"); ?>
                                                                    <select name="city[]" id="multicity" class="form-control" multiple>
                                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                                            <option <?= (@in_array($row['id'], $city) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['city'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            <?php
                                                            if($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'TEAM LEADER') { 
                                                                ?>
                                                                <div class="form-group col-md-4">
                                                                    <?php 
                                                                    if($_SESSION['user_type'] == 'TEAM LEADER'){
                                                                        $res = db_query("select user_id as id,name from callers where id in (".$_SESSION['caller'].") order by name"); 
                                                                    }else{
                                                                        $res = db_query("select * from users where role='ISS' and user_type in ('CLR','TEAM LEADER') order by name"); 
                                                                    }
                                                                    // print_r($_SESSION);die; 
                                                                    ?>
                                                                    <select name="iss[]" id="multiiss" class="form-control" multiple>
                                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                                            <option <?= (@in_array($row['id'], $iss) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <?php } ?>
                                                                <div class="form-group col-md-4">
                                                                    <?php $res = db_query("select * from tag order by name"); ?>
                                                                    <select name="tag" id="multitag" class="form-control" >
                                                                    <option value="">Select Tag</option>
                                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                                            <option <?= (@in_array($row['id'], $tag) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <select class="form-control" id="license_type" name="license_type">
                                                                        <option value="">License Type</option>
                                                                        <option <?= (($_REQUEST['license_type'] == 'Fresh') ? 'selected' : '') ?> value="Fresh">Fresh</option>
                                                                        <option <?= (($_REQUEST['license_type'] == 'Renewal') ? 'selected' : '') ?> value="Renewal">Renewal</option>
                                                                        <option <?= (($_REQUEST['license_type'] == 'Opportunity') ? 'selected' : '') ?> value="Opportunity">Opportunity</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From">

                                                                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To">
                                                                    </div>
                                                                </div>
                                                                <!-- <div class="form-group col-md-4">
                                                                    <select name="product" class="product_data form-control">
                                                                        <option value="">Select Product</option>
                                                                        <?php $query = selectProduct('tbl_product');
                                                                        while ($row = db_fetch_array($query)) { ?>
                                                                            <option <?= (($_GET['product'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div> -->
                                                            
                                                                <!-- <div class="form-group col-md-4"  id="product_type">
                                                                    <select name="product_type"  class="form-control">
                                                                        <option value="">Select Product Type</option>
                                                                    </select>                                                  
                                                                </div> -->

                                                                <div class="form-group col-md-4">
                                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            

                            <div class="col-md-12">
                                <div class="table-responsive">

                                    <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th data-sortable="true">S.No.</th>
                                            <th class="">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" value="" id="check_all" /><label for="check_all" class="ml-1 mb-0 fw-bold">All</label></th>
                                            </div>    
                                            <th data-sortable="true">Reseller name(Submitted by)</th>
                                            <th data-sortable="true">School Name</th>
                                            <th data-sortable="true">Quantity</th>
                                            <th data-sortable="true">Date of Submission</th>
                                            <th data-sortable="true">End User Contact</th>
                                            <th data-sortable="true">Status</th>
                                            <th data-sortable="true">License Type</th>
                                            <th data-sortable="true">Tag</th>
                                            <th data-sortable="true">Submitted By/Align To</th>
                                        </tr>
                                        </thead>


                                    </table>
                                </div>
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

    <?php


    include('includes/footer.php') ?>
        <script>
    $("#check_all").change(function() {
        $(".datatable-checkbox").prop('checked', $(this).prop("checked"));
    });

    $('#leads').DataTable({
            "stateSave": true,
            dom: 'Bfrtip',
            "displayLength": 25,
            language: {
                paginate: {
                    previous: '<i class="fas fa-arrow-left"></i>',
                    next: '<i class="fas fa-arrow-right"></i>'
                }
            },
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            ],
            lengthMenu: [
                [5, 15, 25, 50, 100, 500, 1000],
                ['5', '15', '25', '50', '100', '500', '1000']
            ],
            "processing": true,
            "serverSide": true,
            "retrieve": true,
            //"paging": false,
            "ajax": {
                "url": "massLeads_ajax.php", // json datasource
                "type": "post", // method  , by default get
                "data": function(d) {
                    d.d_from       = "<?= $d_from ?>";
                    d.d_to         = "<?= $d_to ?>";
                    d.partner      = '<?= safe_implode(',', $_GET['partner']) ?>';
                    d.state      = '<?= safe_implode(',', $_GET['state']) ?>';
                    d.sub_source      = '<?= safe_implode(',', $_GET['sub_source']) ?>';
                    // d.sub_source      = '<?= json_encode($_GET['sub_source']) ?>';
                    d.city      = '<?= safe_implode(",", $_GET['city']) ?>';
                    d.school_board      = '<?= safe_implode(',', $_GET['school_board']) ?>';
                    // d.school_board      = '<?= json_encode($_GET['school_board']) ?>';
                    d.iss      = '<?= safe_implode(',', $_GET['iss']) ?>';
                    d.userF = "<?= $_GET['userF'] ?>";
                    d.tag = "<?= $_GET['tag'] ?>";
                    d.license_type = "<?= $_GET['license_type'] ?>";
                },
                "error": function() { // error handling
                    $(".employee-grid-error").html("");
                    $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display", "none");
                }
            },
            columnDefs: [{
                orderable: false,
                targets: 1
            }],
            'columns': [
                {
                    data: 'serial'
                },
                {
                    data: 'id'
                },
                {
                    data: 'partner_name'
                },
                {
                    data: 'company_name'
                },
                {
                    data: 'quantity'
                },
                {
                    data: 'created_date'
                },
                {
                    data: 'end_user'
                },
                {
                    data: 'status', className: 'text-nowrap'
                },
                {
                    data: 'license_type', className: 'text-nowrap'
                },
                {
                    data: 'tag', className: 'text-nowrap'
                },
                {
                    data: 'user_name'
                },

            ],
        });

    $(document).ready(function() {
                $('.product_data').on('change', function() {
                    var productID = $(this).val();
                    if (productID) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxProductTypeAdmin.php',
                            data: 'product=' + productID,
                            success: function(html) {
                                $('#product_type').html(html);

                            },
                        });
                    }
                });
            });

            $(document).ready(function() {
                    var productID = '<?= $_GET['product']; ?>' ;
                    var productType = '<?= $_GET['product_type']; ?>' ;
                    if(productID){
                    if (productID) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxProductTypeAdmin.php',
                            data: {
                                        productID: productID,
                                        productTypee: productType
                                },
                            success: function(html) {
                                $('#product_type').html(html);

                            },
                        });
                    }
                }
            });

            function clear_search() {
                window.location = 'mass_leads_alignment.php';
            }
            // $(document).ready(function() {
            //     $('.multiselect').multiselect({
            //         buttonWidth: '100%',
            //         includeSelectAllOption: true,
            //         nonSelectedText: 'Select an Option',
            //         enableFiltering: true,
            //         enableCaseInsensitiveFiltering: true,
            //     });
            // });

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });
        </script>
           <script>
            var wfheight = $(window).height();

            $('.fixed-table-body').height(wfheight - 310);
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 310);
                $("#leads").tableHeadFixer();

            });

            function relog(id) {
                if (id) {
                    swal({
                        title: "Are you sure?",
                        text: "You want to relog the same lead!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, Re-Log it!",
                        cancelButtonText: "No, cancel modification!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "relog_lead.php?id=" + id,
                                success: function(result) {
                                    if (result) {
                                        swal({
                                            title: "Done!",
                                            text: "Lead Re-Loged.",
                                            type: "success"
                                        }, function() {
                                            //location.reload();
                                            $('#leads').DataTable().ajax.reload();

                                        });
                                    }
                                }
                            });

                        } else {
                            swal("Cancelled", "Lead unchanged!", "error");
                        }
                    });
                }
            }

            $(document).ready(function() {
            $('#exportMass').click(function() {
            var dfrom = '<?= $_GET['d_from'] ?>';
            var dto = '<?= $_GET['d_to'] ?>';
            var userF = '<?= $_GET['userF'] ?>';
            var partner = '<?= $_GET['partner'] ?>';
            var license_type = '<?= $_GET['license_type'] ?>';
            var product = '<?= $_GET['product'] ?>';
            var product_type = '<?= $_GET['product_type'] ?>';
            var val = [];
            $(':checkbox:checked').each(function(i) {
                val[i] = $(this).val();
            });
            val = val.join("_");
            val = val.toString();
            document.location.href = 'export_massLeads.php?d_from=' + dfrom + '&d_to=' + dto + '&userF=' + userF + '&partner=' + partner + '&lead_type=' + lead_type + '&license_type=' + license_type + '&product=' + product + '&product_type=' + product_type + '&industry=' + industry;
            //console.log(val);
            //{ lead: val,d_from:d_from,d_to:d_to }, // data to be submit
        });
    });

    $("#check_all").change(function() {
                $(".datatable-checkbox").prop('checked', $(this).prop("checked"));
            });
            $(document).ready(function() {
                $('#save').on('click', function() {
                    // var formdata = $("#mass_lead").serialize()
                    var check_data = $(".datatable-checkbox").is(':checked') ? 1 : 0;
                    var checkbox = $('input.datatable-checkbox:checked').map(function(_, el) {
                        return $(el).val();
                    }).get(checkbox);
                    var caller_id = $('#caller').find(":selected").val();
                    if (check_data == 1 && caller_id) {
                        swal({
                            title: "Are you sure?",
                            text: "Are you sure that you want to assign new caller?",
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            confirmButtonText: "Yes!",
                            confirmButtonColor: "#ec6c62"
                        }, function() {
                            $.ajax({
                                    type: 'post',
                                    url: 'get_mass_leads.php',
                                    data: {
                                        ids: checkbox,
                                        caller: caller_id,
                                    },
                                    success: function(response) {
                                                // window.location.reload(); 
                                                $('#leads').DataTable().ajax.reload();
                                    }
                                }).done(function(data) {
                                    swal("Caller successfully assigned!", "success");
                                    // $('#orders-history').load(document.URL + ' #orders-history');
                                })
                                .error(function(data) {
                                    swal("Oops", "We couldn't connect to the server!", "error");
                                });
                        });
                    } else if (check_data == 1) {
                        swal({
                            title: "Select Caller",
                            icon: "warning",
                        });
                        return true;
                    } else if (caller_id) {
                        swal({
                            title: "You must check at least 1 box",
                            icon: "warning",
                        });
                        return true;
                    }
                });

            });
    $("#check_all").change(function() {
                $(".datatable-checkbox").prop('checked', $(this).prop("checked"));
            });
            $(document).ready(function() {
                $('#saveF').on('click', function() {
                    // var formdata = $("#mass_lead").serialize()
                    var check_data = $(".datatable-checkbox").is(':checked') ? 1 : 0;
                    var checkbox = $('input.datatable-checkbox:checked').map(function(_, el) {
                        return $(el).val();
                    }).get(checkbox);
                    var partnerF = $('#partnerF').find(":selected").val();
                    var userF = $('#userF').find(":selected").val();
                    if (check_data == 1 && partnerF && userF) {
                        swal({
                            title: "Are you sure?",
                            text: "Are you sure that you want to assign to partner?",
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            confirmButtonText: "Yes!",
                            confirmButtonColor: "#ec6c62"
                        }, function() {
                            $.ajax({
                                    type: 'post',
                                    url: 'get_mass_leads.php',
                                    data: {
                                        ids: checkbox,
                                        partnerF: partnerF,
                                        userF: userF,
                                    },
                                    success: function(response) {
                                                // window.location.reload(); 
                                                $('#leads').DataTable().ajax.reload();
                                    }
                                }).done(function(data) {
                                    swal("Partner successfully assigned!", "success");
                                    // $('#orders-history').load(document.URL + ' #orders-history');
                                })
                                .error(function(data) {
                                    swal("Oops", "We couldn't connect to the server!", "error");
                                });
                        });
                    } else if (check_data == 1) {
                        swal({
                            title: "Select Partner",
                            icon: "warning",
                        });
                        return true;
                    } else if (caller_id) {
                        swal({
                            title: "You must check at least 1 box",
                            icon: "warning",
                        });
                        return true;
                    }
                });

            });

            $(document).ready(function() {
                $('#multistate').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select State',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#partnermulti').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Partner',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multi_sub_source').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Sub Source',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multi_school_board').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select School Board',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multicity').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select City',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiiss').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Allign To',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });

            $(document).ready(function() {
                $('#partnerF').on('change', function() {
                    var partnerID = $(this).val();
                    if (partnerID) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxusers.php',
                            data: 'partnerF=' + partnerID,
                            success: function(html) {
                                $('#usersD').html(html);
                            }
                        });
                    }
                });
            });
        </script>
        