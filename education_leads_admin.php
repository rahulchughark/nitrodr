<?php include('includes/header.php');
//print_r($_SESSION['user_id']);die;
if ($_SESSION['user_type'] != 'REVIEWER') admin_page();
ini_set('max_execution_time', 0);

if ($_POST['save_csv']) {
    function struuid($entropy)
    {
        $s = uniqid("", '');
        $num = hexdec(str_replace(".", "", (string) $s));
        $index = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($index);
        $out = '';
        for ($t = floor(log10($num) / log10($base)); $t >= 0; $t--) {
            $a = floor($num / pow($base, $t));
            $out = $out . substr($index, $a, 1);
            $num = $num - ($a * pow($base, $t));
        }
        return strtolower($out);
    }

    $filename = $_FILES["file"]["tmp_name"];
    if ($_FILES["file"]["size"] > 0) {
        $row = 1;
        $file = fopen($filename, "r");
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            if ($row != 1) {
                $close_date       = date("Y-m-d", strtotime($getData[32]));

                $eu_nameArray = explode(",", $getData[19]);
                $eu_emailArray = explode(",", $getData[20]);
                $eu_mobileArray = explode(",", $getData[23]);
                $eu_designationArray = explode(",", $getData[24]);

                $eu_name = $eu_nameArray[0];
                $eu_email = $eu_emailArray[0];
                $eu_mobile = $eu_mobileArray[0];
                $eu_designation = $eu_designationArray[0];

                if ((count($eu_nameArray) == count($eu_emailArray)) && (count($eu_nameArray) == count($eu_mobileArray)) && (count($eu_nameArray) == count($eu_designationArray))) {

                    $l = 0;
                    foreach ($eu_nameArray as $arrayvalue) {

                        $contactArray[$l] = array($eu_nameArray[$l], $eu_emailArray[$l], $eu_mobileArray[$l], $eu_designationArray[$l]);
                        $l++;
                    }
                } else {

                    echo "<script type=\"text/javascript\">
                    alert(\"Please check contact values count at row no " . $row . " and try again with same and next rows data.\");
                    window.location = \"manage_orders.php\"
                  </script>";
                }

                if ($getData[34] == 'Approved') {
                    $approval_time = date('Y-m-d H:i:s');
                    $close_time = date('Y-m-d', strtotime('+29 days', strtotime(date('Y-m-d h:i:s'))));
                    $code = struuid(true);
                }


                $sql = "INSERT INTO `orders`(r_name,r_email,r_user,created_by,`source`,`lead_type`, `company_name`, `parent_company`, `landline`, `industry`, `sub_industry`, `region`, `address`, `pincode`, `state`, `city`, `country`, `eu_name`, `eu_email`, `eu_landline`, `department`, `eu_mobile`, `eu_designation`, `eu_role`, `quantity`, `team_id`,association_name,`os`, `version`, `runrate_key`,`partner_close_date`, `license_type`,`status`,`caller`, `account_visited`, `visit_remarks`, `confirmation_from`,`stage`,created_date,code,approval_time,close_time,campaign_type,data_ref) VALUES ('" . $getData[0] . "','" . $getData[1] . "','" . htmlspecialchars($getData[2], ENT_QUOTES) . "','" . intval($getData[3]) . "','" . htmlspecialchars($getData[6], ENT_QUOTES) . "','" . htmlspecialchars($getData[7], ENT_QUOTES) . "','" . htmlspecialchars_decode($getData[8], ENT_NOQUOTES) . "','" . htmlspecialchars_decode($getData[9], ENT_NOQUOTES) . "','" . htmlspecialchars($getData[10], ENT_QUOTES) . "','" . intval($getData[11]) . "','" . intval($getData[12]) . "','" . htmlspecialchars($getData[13], ENT_QUOTES) . "','" . htmlspecialchars_decode($getData[14], ENT_NOQUOTES) . "','" . htmlspecialchars($getData[15], ENT_QUOTES) . "','" . intval($getData[16]) . "','" . htmlspecialchars($getData[17], ENT_QUOTES) . "','" . htmlspecialchars($getData[18], ENT_QUOTES) . "','" . $eu_name . "','" . $eu_email . "','" . htmlspecialchars($getData[21], ENT_QUOTES) . "','" . htmlspecialchars($getData[22], ENT_QUOTES) . "','" . $eu_mobile . "','" . $eu_designation . "','" . htmlspecialchars($getData[25], ENT_QUOTES) . "','" . intval($getData[26]) . "','" . intval($getData[27]) . "','" . htmlspecialchars($getData[28], ENT_QUOTES) . "','" . htmlspecialchars($getData[29], ENT_QUOTES) . "','" . htmlspecialchars($getData[30], ENT_QUOTES) . "','" . htmlspecialchars($getData[31], ENT_QUOTES) . "','" . $close_date . "','" . htmlspecialchars($getData[33], ENT_QUOTES) . "','" . htmlspecialchars($getData[34], ENT_QUOTES) . "','" . intval($getData[35]) . "','" . htmlspecialchars($getData[36], ENT_QUOTES) . "','" . htmlspecialchars_decode($getData[37], ENT_NOQUOTES) . "','" . htmlspecialchars($getData[38], ENT_QUOTES) . "','" . htmlspecialchars($getData[39], ENT_QUOTES) . "',now(),'" . $code . "','" . $approval_time . "','" . $close_time . "','" . intval($getData[40]) . "',1 )";
                $result = db_query($sql);
                $lead_id = get_insert_id();

                $activity_log = "INSERT INTO `activity_log`(`pid`, `description`,`activity_type`,call_subject,added_by,data_ref) VALUES ('" . $lead_id . "','" . htmlspecialchars_decode($getData[37], ENT_NOQUOTES) . "','Lead','" . $getData[41] . "','" . intval($getData[3]) . "',1)";
                $activity_result = db_query($activity_log);


                $selectQuery = "SELECT id FROM `orders` ORDER BY id DESC limit 1";
                $Res = db_query($selectQuery);
                while ($uid = db_fetch_array($Res)) {
                    $orderId = $uid['id'];
                    $sql2 = "INSERT INTO `tbl_lead_product`(`product_id`, `lead_id`,`product_type_id`,created_at) VALUES ('" . $getData[4] . "','" . $orderId . "','" . $getData[5] . "',now())";
                    $result2 = db_query($sql2);

                    $rcount = 1;
                    foreach ($contactArray as $contactValue) {
                        if ($rcount != 1) {
                            $sql3 = "INSERT INTO `tbl_lead_contact`(`lead_id`, `eu_name`,`eu_email`,`eu_mobile`,`eu_designation`) VALUES ('" . $orderId . "','" . $contactValue[0] . "','" . $contactValue[1] . "','" . $contactValue[2] . "','" . $contactValue[3] . "')";
                            $result3 = db_query($sql3);
                        }

                        $rcount++;
                    }
                }
            }
            $row++;
        }
        echo "<script type=\"text/javascript\">
          alert(\"CSV File has been successfully Imported.\");
          window.location = \"manage_orders.php\"
        </script>";
        fclose($file);
    } else {
        echo "<script type=\"text/javascript\">
          alert(\"An error occured while uploading file.\");
          window.location = \"manage_orders.php\"
        </script>";
    }
}

if ($_POST['save_iss_csv']) {

    $filename = $_FILES["file"]["tmp_name"];
    if ($_FILES["file"]["size"] > 0) {
        $row = 1;
        $file = fopen($filename, "r");
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            if ($row != 1) {

                $sql = "INSERT INTO `orders`(r_name,r_email,r_user,created_by,`source`,`lead_type`, `company_name`, `industry`, `sub_industry`, `region`, `address`, `pincode`, `state`, `city`, `country`, `eu_name`, `eu_email`, `eu_mobile`, `eu_designation`, `quantity`, `team_id`, `license_type`,`status`,`caller`,allign_to,created_date,iss,is_iss_lead, `account_visited`,campaign_type,data_ref) VALUES ('" . $getData[0] . "','" . $getData[1] . "','" . htmlspecialchars($getData[2], ENT_QUOTES) . "','" . intval($getData[3]) . "','" . htmlspecialchars($getData[6], ENT_QUOTES) . "','" . htmlspecialchars($getData[7], ENT_QUOTES) . "','" . htmlspecialchars($getData[8], ENT_NOQUOTES) . "','" . intval($getData[9]) . "','" . intval($getData[10]) . "','" . htmlspecialchars($getData[11], ENT_QUOTES) . "','" . htmlspecialchars($getData[12], ENT_NOQUOTES) . "','" . htmlspecialchars($getData[13], ENT_QUOTES) . "','" . intval($getData[14]) . "','" . htmlspecialchars($getData[15], ENT_QUOTES) . "','" . htmlspecialchars($getData[16], ENT_QUOTES) . "','" . htmlspecialchars($getData[17], ENT_QUOTES) . "','" . $getData[18] . "','" . htmlspecialchars($getData[19], ENT_QUOTES) . "','" . htmlspecialchars($getData[20], ENT_QUOTES) . "','" . intval($getData[21]) . "','" . intval($getData[22]) . "','" . htmlspecialchars($getData[23], ENT_QUOTES) . "','" . htmlspecialchars($getData[24], ENT_QUOTES) . "','" . intval($getData[25]) . "','" . intval($getData[26]) . "',now(),1,1,'No','" . intval($getData[27]) . "',1)";
                $result = db_query($sql);
                $lead_id = get_insert_id();

                $selectQuery = "SELECT id FROM `orders` ORDER BY id DESC limit 1";
                $Res = db_query($selectQuery);
                while ($uid = db_fetch_array($Res)) {
                    $orderId = $uid['id'];
                    $sql2 = "INSERT INTO `tbl_lead_product`(`product_id`, `lead_id`,`product_type_id`,created_at) VALUES ('" . $getData[4] . "','" . $orderId . "','" . $getData[5] . "',now())";
                    $result2 = db_query($sql2);
                }

                $activity_log = "INSERT INTO `activity_log`(`pid`, `description`,`activity_type`,call_subject,added_by,data_ref) VALUES ('" . $lead_id . "','" . htmlspecialchars($getData[29], ENT_QUOTES) . "','Lead','" . $getData[28] . "','" . intval($getData[3]) . "',1)";
                $activity_result = db_query($activity_log);
            }
            $row++;
        }
        echo "<script type=\"text/javascript\">
          alert(\"CSV File has been successfully Imported.\");
          window.location = \"manage_orders.php\"
        </script>";
        fclose($file);
    } else {
        echo "<script type=\"text/javascript\">
          alert(\"An error occured while uploading file.\");
          window.location = \"manage_orders.php\"
        </script>";
    }
}
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
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Leads</small>
                                    <h4 class="font-size-14 m-0 mt-1">Leads</h4>
                                </div>
                            </div>

                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Lead Added Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Lead Updated Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['m'] == 'nodata') { ?>
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-danger"><i class="fa fa-check-circle"></i> Error!</h3> No data found!
                                </div>
                            <?php } ?>

                            <div class="btn-group float-right" role="group" style="margin-top:12px;">

                                <?php if ($_SESSION['user_type'] == 'SUPERADMIN') { ?>

                                    <a href="javascript:void(0);" onclick="show_import()"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Import Fresh Leads" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus "></i></button></a>

                                    <a href="javascript:void(0);" onclick="show_iss_import()" class=" ml-1"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Import ISS Leads" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus "></i></button></a>

                                <?php }
                                if ($_SESSION['user_type'] != 'OPERATIONS EXECUTIVE' && $_SESSION['user_type'] != 'ISS MNGR' && $_SESSION['download_status'] == 1) { ?>

                                    <a href="javascript:void(0);" id="sdfexport"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="SFDC Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-share"></i></button></a>

                                <?php } ?>

                                <?php if($_SESSION['download_status'] == 1){ ?>    
                                <a href="export_admin_lead.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>&dtype=<?= @$_GET['date_type'] ?>&license='Education'">
                                    <button data-toggle="tooltip" data-placement="left" title="" data-original-title="Excel Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-download"></i>
                                    </button>
                                </a>
                                <?php } ?>  
                                <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>


                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                        <form method="get" name="search" class="form-horizontal" role="form">
                                            <input type="hidden" name="counter" value="<?= $_GET['counter'] ?>">
                                            <input type="hidden" name="d_from" value="<?= $_GET['d_from'] ?>">
                                            <input type="hidden" name="d_to" value="<?= $_GET['d_to'] ?>">
                                            <input type="hidden" name="progress" value="<?= $_GET['progress'] ?>">
                                            <input type="hidden" name="lc_count" value="<?= $_GET['lc_count'] ?>">

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
                                                <div class="form-group col-md-4">
                                                    <select name="date_type" class="form-control" id="date_type">
                                                        <option value="created">Created Date</option>
                                                        <option <?= (($_GET['date_type'] == 'approved_date') ? 'selected' : '') ?> value="approved_date">Actioned Date</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <select name="status" class="form-control">
                                                        <option value="">Select Status</option>
                                                        <option value="Approved" <?= (($_GET['status'] == 'Approved') ? 'selected' : '') ?>>Qualified</option>
                                                        <option value="Cancelled" <?= (($_GET['status'] == 'Cancelled') ? 'selected' : '') ?>>Unqualified</option>
                                                        <option value="Pending" <?= (($_GET['status'] == 'Pending') ? 'selected' : '') ?>>Pending</option>
                                                        <option value="Undervalidation" <?= (($_GET['status'] == 'Undervalidation') ? 'selected' : '') ?>>Re-submission</option>
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="form-group col-md-4">
                                                    <select name="partner[]" id="partner" class="multiselect_partner form-control" data-live-search="true" multiple>
                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (in_array($row['id'], $partner) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
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
                            <div class="table-responsive" id="MyDiv">

                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">S.No.</th>
                                            <!-- <th>SFDC <i class="fa fa-question-circle-o" title="Select entries you don&apos;t want to export!" data-toggle="tooltip"></i></th> -->
                                            <th data-sortable="true">DR Code</th>
                                            <th style="width:10%" data-sortable="true">Reseller name(Submitted by)</th>
                                            <th data-sortable="true">Lead Type</th>
                                            <th data-sortable="true">Quantity</th>
                                            <!-- <th data-sortable="true">Product Name</th> -->
                                            <th data-sortable="true">Company Name</th>
                                            <th data-sortable="true">Date of Submission</th>
                                            <th data-sortable="true">Status</th>
                                            <th data-sortable="true">Stage</th>
                                            <th data-sortable="true">Caller Name</th>
                                            <th data-sortable="true">Close Date</th>
                                            <th data-sortable="true">Data Reference</th>
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
                buttons: [
                    <?php if($_SESSION['download_status'] == 1){ ?>
                    'copy', 'csv', 'excel',  'print', 'pageLength',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    }
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
                    url: "get_education_leads_admin.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        d.partner = '<?= @implode('","', $_GET['partner']) ?>';
                        d.dtype = '<?= $_GET['date_type'] ?>';
                        d.product = '<?= @implode('","', $_GET['product']) ?>';
                        d.product_type = '<?= @implode('","', $_GET['product_type']) ?>';
                        d.counter = '<?= $_GET['counter'] ?>';
                        d.date_from = '<?= $_GET['date_from'] ?>';
                        d.date_to = '<?= $_GET['date_to'] ?>';
                        d.progress = '<?= $_GET['progress'] ?>';
                        d.lead_status = '<?= $_GET['status'] ?>';
                        d.actioned_by = '<?= @implode('","', $_GET['actioned_by']) ?>';
                        d.lc_count = '<?= $_GET['lc_count'] ?>';
                        d.validation_type = '<?= $_GET['validation_type'] ?>';
                    },

                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                },
                "order": [
                    [7, "desc"]
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],

                'columns': [{
                        data: 'id'
                    },
                    // {
                    //     data: 'check'
                    // },
                    {
                        data: 'code'
                    },
                    {
                        data: 'r_name'
                    },
                    {
                        data: 'lead_type'
                    },
                    {
                        data: 'quantity'
                    },
                    // {
                    //     data: 'product_name'
                    // },
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
                    {
                        data: 'data_ref'
                    }

                ]
            });


            $(document).ready(function() {

                $('#sdfexport').click(function() {
                    var dfrom = '<?= @$_GET['d_from'] ?>';
                    var dto = '<?= @$_GET['d_to'] ?>';
                    var dtype = '<?= @$_GET['date_type'] ?>';
                    var ltype = "Education";
                    var val = [];
                    $(':checkbox:checked').each(function(i) {
                        val[i] = $(this).val();
                    });

                    val = val.join("_");
                    val = val.toString();

                    //console.log(val);
                    //document.location.href = 'export_orders.php?lead='+val;
                    document.location.href = 'sfdc_export.php?lead=' + val + '&d_from=' + dfrom + '&d_to=' + dto + '&dtype=' + dtype + '&license=' + ltype;
                    //console.log(val);
                    //{ lead: val,d_from:d_from,d_to:d_to }, // data to be submit


                });


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
                $('.product_data').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                });
                $('.multiselect_actionedBy').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Actioned By',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                });
                $('.multiselect_type').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product Type',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                });
            });

            function clear_search() {
                window.location = 'education_leads_admin.php';
            }

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            function show_import() {
                $.ajax({
                    type: 'POST',
                    url: 'import_leads.php',
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });

            }

            function show_iss_import() {
                $.ajax({
                    type: 'POST',
                    url: 'import_iss_leads.php',
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function chage_stage(stage, id) {
                if (stage != '') {
                    $.ajax({
                        type: 'post',
                        url: 'change_stage.php',
                        data: {
                            stage: stage,
                            lead_id: id
                        },
                        success: function(res) {
                            var res = $.trim(res);
                            if (res == 'success') {
                                swal({
                                    title: "Done!",
                                    text: "Stage changed Successfully.",
                                    type: "success"
                                }, function() {
                                    //window.location = "manage_orders.php";
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

            function stage_change(ids, id) {
                //$('.preloader').show();
                var page_access = 'true';
                $.ajax({
                    type: 'POST',
                    url: 'stage_change.php',
                    data: {
                        pid: id,
                        ids: ids,
                        page_access: page_access
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function chage_stage(stage, id, ids, substage, op, order_price, date1, instalment1, date2, instalment2, date3, instalment3, date4, instalment4, date5, instalment5, date6, instalment6, Psubstage) {

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
        </script>

        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 315);
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
                                $('#product_type').html(html);

                            },
                        });
                    }
                });
            });


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

            function convertDate(dateString) {
                var p = dateString.split(/\D/g)
                return [p[2], p[1], p[0]].join("-")
            }
        </script>