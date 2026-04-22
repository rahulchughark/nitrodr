<?php include('includes/header.php');admin_page();?>
<?php
// echo $_SESSION['team_id']; die;
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
if (isset($_POST['partner']) && isset($_POST['caller']) && $_POST['partner'] != '' && $_POST['caller'] != '') {

    if (count($_POST['ids'])) { 

        $r_user = getSingleresult("select name from users where id=" . $_POST['users']);
        $r_name = getSingleresult("select name from partners where id=" . $_POST['partner']);
    $r_email = getSingleresult("select email from users where id=" . $_POST['users']);

        foreach ($_POST['ids'] as $id) {

            // fetch renewal leads data
            $select_query = db_query("SELECT * FROM `renewal_leads` where id=".$id);
            $data = db_fetch_array($select_query);
            // print_r($renewal_data);die();

            // insert data in orders table
            $code = struuid(true);
            $approval_time = date('Y-m-d h:i:s');
            $close_time = date('Y-m-d', strtotime('+29 days', strtotime(date('Y-m-d h:i:s'))));
            
         $query =  insertRenewalLeadData('orders', $code, $r_name, $r_email, $r_user, $data['cdgs_number'], $data['lisence_end_date'], $data['eu_name'], $data['parent_company'], $data['industry'], $data['sub_industry'], $data['region'], $data['eu_address'], $data['pincode'], $data['state'], $data['city'], $data['eu_contact'], $data['contact_email'], $data['mobile_number'], $data['eu_designation'], $data['quantity'], $data['runrate_key'], $_POST['users'], $data['created_date'], $_POST['partner'], $_POST['caller'], $approval_time, $close_time, $data['partner_close_date']);

            $order_id = getSingleresult("select id from orders ORDER BY id DESC limit 1");

            $product_ins_query = "INSERT INTO `tbl_lead_product`(`product_id`, `lead_id`,`product_type_id`) VALUES ('2','".$order_id."','6')";
            $result = db_query($product_ins_query);

            $sql = db_query("delete from renewal_leads where id=" . $id);
        }
    } 

    redir("assign_renewal_leads.php?add=success", true);
} else if (isset($_POST['caller']) && $_POST['caller'] != '' && $_POST['partner'] == '') {
    if (count($_POST['ids'])) {
        foreach ($_POST['ids'] as $id) {
            $sql = db_query("update renewal_leads set caller='" . $_POST['caller'] . "' where id=" . $id);
        }
    }

    redir("assign_renewal_leads.php?add=success", true);
}else if (isset($_POST['partner']) && $_POST['partner'] != '' && $_POST['caller'] == '') {
    if (count($_POST['ids'])) {
        $r_user = getSingleresult("select name from users where id=" . $_POST['users']);
        $r_name = getSingleresult("select name from partners where id=" . $_POST['partner']);
    $r_email = getSingleresult("select email from users where id=" . $_POST['users']);

        foreach ($_POST['ids'] as $id) { 

            // fetch renewal leads data
            $select_query = db_query("SELECT * FROM `renewal_leads` where id=".$id);
            $data = db_fetch_array($select_query);
            // print_r($renewal_data);die();

            // insert data in orders table
            $code = struuid(true);
            $approval_time = date('Y-m-d h:i:s');
            $close_time = date('Y-m-d', strtotime('+29 days', strtotime(date('Y-m-d h:i:s'))));
            
         $query =  insertRenewalLeadData('orders', $code, $r_name, $r_email, $r_user, $data['cdgs_number'], $data['lisence_end_date'], $data['eu_name'], $data['parent_company'], $data['industry'], $data['sub_industry'], $data['region'], $data['eu_address'], $data['pincode'], $data['state'], $data['city'], $data['eu_contact'], $data['contact_email'], $data['mobile_number'], $data['eu_designation'], $data['quantity'], $data['runrate_key'], $_POST['users'], $data['created_date'], $_POST['partner'], $data['caller'], $approval_time, $close_time, $data['partner_close_date']);

            $order_id = getSingleresult("select id from orders ORDER BY id DESC limit 1");

            $product_ins_query = "INSERT INTO `tbl_lead_product`(`product_id`, `lead_id`,`product_type_id`) VALUES ('2','".$order_id."','6')";
            $result = db_query($product_ins_query);

            $sql = db_query("delete from renewal_leads where id=" . $id);
        }
    }

    redir("assign_renewal_leads.php?add=success", true);
}

?>
<!-- ============================================================== -->
<!-- Start right Content here -->
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

                                    <small class="text-muted">Home >Align Renewal Leads</small>
                                    <h4 class="font-size-14 m-0 mt-1">Align Renewal Leads</h4>
                                </div>
                            </div>


                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Leads aligned Successfully!
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
                                    <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>
                                    <div class="dropdown dropdown-lg">

                                        <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                            <form method="get" name="search" class="form-horizontal" role="form">

                                                <div class="form-group">
                                                    <?php $varres = db_query("select * from renewal_leads where reseller=" . $_SESSION['team_id'] . " GROUP BY native_lead "); ?>
                                                    <select name="var_name" class="product_data form-control">
                                                        <option value="">Select Var Name</option>

                                                        <?php while ($varrow = db_fetch_array($varres)) { ?>
                                                            <option <?= (($varrow['native_lead'] == $_GET['var_name']) ? 'Selected' : '') ?> value='<?= $varrow['native_lead'] ?>'><?= $varrow['native_lead'] ?></option>
                                                        <?php } ?>

                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <?php
                                                    $months = array();
                                                    for ($i = 0; $i < 12; $i++) {
                                                        $timestamp = mktime(0, 0, 0, date('n') + $i, 1);
                                                        $months[date('n', $timestamp)] = date('F', $timestamp);
                                                    }
                                                    ?>
                                                    <select name="license_end_month" class="product_data form-control">
                                                        <option value="">Select End Month</option>
                                                        <?php
                                                        foreach ($months as $num => $name) { ?>
                                                            <option value="<?= $num ?>" <?php if($_GET['license_end_month'] == $num){echo "selected";}?>><?= $name ?></option>
                                                        <?php  }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                <?php
          
                                                  $currently_year = date('Y'); 

                                                  $previous_range = 2000;
                                                  $future_rage = date("Y",strtotime("+5 year"));
                                                  ?>

                                                 <select name="license_end_year" class="product_data form-control">
                                                    <option value="">Select End Year</option>
                                                    <?php
                                                  foreach ( range( $previous_range, $future_rage ) as $i ) { ?>
                                                    <option value="<?=$i ?>" <?= ($i == $_GET['license_end_year'] ? ' selected="selected"' : '') ?> ><?= $i ?></option>
                                                <?php  } ?>
                                                 </select>
                                              </div>

                                                <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                            </form>
                                        </div>
                                    </div>

                                </div>


                                <form action="#" id="tblForm" method="post">
                                    <div class="row justify-content-md-center mb-3">


                                        <div class="col-3">
                                            <h6>Assign to Reseller</h6>
                                            <?php $res = db_query("select * from partners where status='Active' "); ?>
                                            <div class="input-group"><select name="partner" id="partner" class="form-control">
                                                    <option value="">Select Reseller</option>
                                                    <?php while ($row = db_fetch_array($res)) { ?>
                                                        <option <?= (($row['id'] == $reseller) ? 'Selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                               
                                            </div>
                                        </div>

                                        <div class="col-3" id="users_show" style="display: none;">
                                            <h6>Select User</h6>
                                            
                                            <div class="input-group">
                                              <select name="users" id="users" class="form-control ">
                                            <option value="">Select User</option>

                                        </select>
                                               
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <h6>Assign to Caller</h6>
                                            <?php $res = db_query("select callers.* from callers join users on callers.user_id=users.id where users.user_type='RCLR' OR users.user_type='RENEWAL TL' and users.team_id=" . $_SESSION['team_id']." order by callers.name ASC "); ?>
                                            <div class="input-group"><select name="caller" id="caller" class="form-control">
                                                    <option value="">Select Caller</option>
                                                    <?php while ($row = db_fetch_array($res)) { ?>
                                                        <option <?= (($row['id'] == $reseller) ? 'Selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="input-group-btn col-md-2"><button class="btn btn-primary" type="submit">Assign</button></div>
                                            </div>
                                        </div>


                                    </div>
                                    <table id="leads" class="table display nowrap order-column table-striped table-bordered" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th data-sortable="true">S.No.</th>
                                                <th data-sortable="true"><input type="checkbox" id="check_all" /><label for="check_all">&nbsp;&nbsp;All</label></th>
                                                <th data-sortable="true">Native Reseller</th>
                                                <th data-sortable="true">End User</th>
                                                <th data-sortable="true">License Number</th>
                                                <th data-sortable="true">License End Date</th>
                                                <th data-sortable="true">Quantity</th>
                                                <th data-sortable="true">Status</th>
                                                <th data-sortable="true">Stage</th>
                                                <th data-sortable="true">Caller</th>
                                                <th data-sortable="true">Close Date</th>
                                            </tr>
                                        </thead>

                                    </table>
                                </form>
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
    $("#check_all").click(function() {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
    $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": true,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
                "displayLength": 25,

            });
        });
    });

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
            url: "get_assign_renewal_leads.php", // json datasource
            type: "post", // method  , by default get
            data: function(d) {

                d.var_name = "<?= $_GET['var_name'] ?>";
                d.license_end_month = "<?= $_GET['license_end_month'] ?>"; 
                d.license_end_year = "<?= $_GET['license_end_year'] ?>";
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
            targets: 1,
        }],

        'columns': [{
                data: 'id'
            },
            {
                data: 'checkboxinput'
            },
            {
                data: 'native_reseller'
            },
            {
                data: 'end_user'
            },
            {
                data: 'license_number'
            },
            {
                data: 'license_end_date'
            },
            {
                data: 'quantity'
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

    
    // $(document).ready(function(){
    //     $("input[type='checkbox']").on('change', function(){
    //         $(this).closest('div').toggleClass('highlight');
    //     });

    //     $("#check_all").on('click', function(){
    //         $("input[type='checkbox']").prop('checked', true).change();
    //     });
    // });
    $(function() {
        $('#datepicker-close-date').datepicker({
            dateFormat: 'yy',
            format: 'yyyy',
            //startDate: '-3d',
            autoclose: !0

        });

    });

    function clear_search() {
        window.location = 'assign_renewal_leads.php';
    }
</script>

<script>
    $(document).ready(function() {
        var wfheight = $(window).height();
        $('.dataTables_wrapper').height(wfheight - 400);
        $("#leads").tableHeadFixer(); 

    });
</script>

<script>
   
    $("#tblForm").submit(function() {
        var checked = $("#tblForm input:checked").length > 0;
        var partner = $('#partner').val();
        var users = $('#users').val();
        var caller = $('#caller').val();
        if (!checked) {
            alert("Please check at least one checkbox");
            return false;
        }
        if(partner == '' && caller == '' && users == '')
        {
            alert("Please select partner Or caller to assign");
            return false;
        }
        if(partner != '' && users == '')
        {
            alert("Please Select User");
            return false;
        }

        if(partner == '' && users != '')
        {
            alert("Please Select partner");
            return false;
        }

        

    });

    $('#partner').on('change', function() {
                //alert("hi");
                var partnerID = $(this).val();
                if (partnerID) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxusers.php', 
                        data: 'partner_id=' + partnerID,
                        success: function(html) {
                            $('#users_show').css('display','block');
                            $('#users').html(html);
                        }
                    });
                }
            });
</script>