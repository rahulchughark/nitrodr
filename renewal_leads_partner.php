<?php include('includes/header.php');

if (isset($_POST['save_notification'])) {
    $id           = $_POST['pid'];
    $title        = $_POST['title'];
    $company_name = $_POST['company_name'];
    $submitted_by = $_POST['submitted_by'];
    $sender_type  = $_POST['sender_type'];
    $partner_name = $_POST['partner_name'];
    $sender_id    = $_POST['sender_id'];
    $receiver_id  = @implode(',', $_POST['receiver_id']);
    $initiate_reason  = $_POST['initiate_reason'];
    $visit_done = $_POST['visit_done'];
    $usage_confirmed  = @implode(',', $_POST['usage_confirmed']);
    $confirmation_received = $_POST['confirmation_received'];
    $role = $_POST['role'];
    $designation = $_POST['designation'];
    $validation_type = $_POST['validation_type'];
//$attachment = $_POST["user_attachment"];
//print_r($attachment);

if ($_FILES["user_attachment"]) {
    $target_dir = "uploads/";
    $target_file = $target_dir . time() . basename($_FILES["user_attachment"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($_FILES["user_attachment"]["size"] > 4000000) {
        echo "<script>alert('Sorry, your file is too large!')</script>";
        redir("add_leads.php", true);
    } else {
        move_uploaded_file($_FILES["user_attachment"]["tmp_name"], $target_file);
    }
}

// print_r($_FILES["user_attachment"]["name"]);
//     print_r($_FILES["user_attachment"]);die;

    if ($usage_confirmed || $role || $designation || $validation_type || $_FILES["user_attachment"]) {
        $lead_update = db_query("update orders set confirmation_from='" . $confirmation_received . "',eu_role='" . $role . "',eu_designation='" . $designation . "',validation_type='". $validation_type ."',user_attachement='".$target_file."' where id=" . $id);
    }

    $activityLog_insert = db_query("insert into activity_log(`pid`,description,`activity_type`,`call_subject`,`added_by`,`is_intern`,`action_plan`,`data_ref`)values('" . $id . "','". $initiate_reason ."','Lead','Profiling Call','" . $_SESSION['user_id'] . "',0,'".$_POST['action_plan']."',1)");


    $insert = saveNotification('lead_notification', $id, $title, $company_name, $submitted_by, $sender_type, $partner_name, $sender_id, $receiver_id, $initiate_reason, $visit_done, $usage_confirmed);
    // print_r($insert);die;
    if ($insert) {
        echo '* save new notification success';
    }

    $sql = db_query("select * from orders where id='" . $id . "'");
    $row_data = db_fetch_array($sql);

    $select_query = db_query("select * from lead_notification where type_id='" . $id . "' and sender_id=" . $_SESSION['user_id']);


    if (mysqli_num_rows($select_query) > 0) {

        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $id . "','Request Status','" . $row_data['lead_type'] . "','LC',now(),'" . $_SESSION['user_id'] . "')");

        if ($row_data['eu_role'] != $_POST['eu_role']) {
            $modify_name = $_POST['eu_role'];
            $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $id . "','Role','" . $row_data['eu_role'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
        }
        if ($row_data['confirmation_from'] != $_POST['confirmation_from']) {
            $modify_name = $_POST['confirmation_from'];
            $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $id . "','Usage Confirmation Received from','" . $row_data['confirmation_from'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
        }
        if ($row_data['eu_designation'] != $_POST['eu_designation']) {
            $modify_name = $_POST['eu_designation'];
            $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $id . "','Designation','" . $row_data['eu_designation'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
        }
    }
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
                            <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home >Renewal Leads</small>
                                            <h4 class="font-size-14 mb-0 mt-1">Renewal Leads</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="btn-group float-right" role="group">
                                        <a href="export_partner_lead.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>">
                                            <button data-toggle="tooltip" data-placement="left" title="" data-original-title="Excel Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light h-100"><i class="ti-share "></i></button></a>
                                        <button type="button" class="btn btn-xs btn-light ml-1  " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                        <div class="dropdown dropdown-lg">

                                            <div class="dropdown-menu1 dropdown-menu-right  filter_wrap_2" id="filter-container" role="menu">
                                                <form method="get" name="search" class="form-horizontal" role="form">

                                                    <input type="hidden" name="untouched" value="<?= $_GET['untouched'] ?>">
                                                    <input type="hidden" name="score" value="<?= $_GET['score'] ?>">
                                                    <input type="hidden" name="stages" value="<?= $_GET['stages'] ?>">
                                                    <input type="hidden" name="month" value="<?= $_GET['month'] ?>">
                                                    <input type="hidden" name="year" value="<?= $_GET['year'] ?>">
                                                    <input type="hidden" name="meter" value="<?= $_GET['meter'] ?>">
                                                
                                                    
                                                    <?php 
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
                                        
                                                ?>
                                            
                                                <div class="row">
                                                    <div class="form-group col-md-4 col-xl-3">
                                                        <select name="dtype" class="form-control" id="date_type">
                                                        <option value="">Select Date Type</option>
                                                        <option <?= (($_GET['dtype'] == 'created') ? 'selected' : '') ?> value="created">Created Date</option>

                                                                <option <?= (($_GET['dtype'] == 'actioned_date') ? 'selected' : '') ?> value="actioned_date">Actioned Date</option>

                                                                <option <?= (($_GET['dtype'] == 'close') ? 'selected' : '') ?> value="close">Close Date</option>

                                                                <option <?= (($_GET['dtype'] == 'stage') ? 'selected' : '') ?> value="stage">Stage Date</option>

                                                                <option <?= (($_GET['dtype'] == 'lead_status') ? 'selected' : '') ?> value="lead_status">Lead Status Change</option>

                                                                <option <?= (($_GET['dtype'] == 'sub_stage') ? 'selected' : '') ?> value="sub_stage">Sub Stage</option>

                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4 col-xl-3">
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
                                                    <div class="form-group col-md-4 col-xl-3">
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
                                                        <div class="form-group col-md-4 col-xl-3">
                                                            <select name="sub_product[]" class="multiselect_productType form-control" multiple>
                                                                <?php $resTP = db_query("select * from tbl_product_pivot where status=1 and product_id='2'");
                                                                while ($row = db_fetch_array($resTP)) { ?>
                                                                    <option <?= (in_array($row['id'], $sub_product) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['product_type'] ?></option>
                                                                <?php  } ?>

                                                            </select>
                                                        </div>
                                                </div>

                                                <div class="row">

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
                                                    <div class="form-group col-md-4 col-xl-3">
                                                    <select name="stage[]" data-live-search="true" multiple class="form-control" id="multiselect">
                                                        <?php while ($stag = db_fetch_array($stageList)) { ?>
                                                            <option value="<?= $stag['stage_name'] ?>" <?= (in_array($stag['stage_name'], $stage ?? []) ? 'selected' : '') ?>><?= $stag['stage_name'] ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>


                                                    <div class="form-group col-md-4 col-xl-3" id="sub_stageD">
                                                                <?php if ($_GET['stage']) { ?>

                                                                    <select name="sub_stage[]" class="multiselect_sub_stage form-control" data-live-search="true" multiple>

                                                                        <?php $query = db_query('select * from sub_stage where stage_name IN ("' . implode('", "', $stage) . '")');
                                                                        while ($row = db_fetch_array($query)) { ?>
                                                                            <option <?= (@in_array($row['name'], $sub_stage ?? []) ? 'selected' : '') ?> value="<?= $row['name'] ?>"><?= ucwords($row['name']) ?></option>
                                                                        <?php } ?>
                                                                    </select>

                                                                <?php } else { ?>
                                                                    <div id="sub_stageD">
                                                                    </div>
                                                                <?php } ?>
                                                    </div>


                                                    <div class="form-group col-md-4 col-xl-3">
                                                        <select name="school_board[]" id="multiselect_school_board" class="form-control" multiple>
                                                        <option <?= (@in_array('CBSE', $school_board ?? []) ? 'selected' : '') ?> value="CBSE">CBSE</option>
                                                        <option <?= (@in_array('ICSE', $school_board ?? []) ? 'selected' : '') ?> value="ICSE">ICSE</option>
                                                        <option <?= (@in_array('IB', $school_board ?? []) ? 'selected' : '') ?> value="IB">IB</option>
                                                        <option <?= (@in_array('IGCSE', $school_board ?? []) ? 'selected' : '') ?> value="IGCSE">IGCSE</option>
                                                        <option <?= (@in_array('STATE', $school_board ?? []) ? 'selected' : '') ?> value="STATE">STATE</option>
                                                        <option <?= (@in_array('Others', $school_board ?? []) ? 'selected' : '') ?> value="Others">Others</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4 col-xl-3">
                                                            <select name="source[]" class="form-control" id="lead_source" placeholder="" multiple>
                                                                    <?php $res = db_query("select * from lead_source where status=1");
                                                                while ($row = db_fetch_array($res)) { ?>
                                                                    <option <?= (@in_array($row['lead_source'], $source ?? []) ? 'selected' : '') ?> value="<?= $row['lead_source'] ?>"><?= $row['lead_source'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                    </div>

                                                    <?php
                                                                    $sqlTag = "select * from tag where 1";
                                                                    $tagList = db_query($sqlTag);
                                                                    ?>

                                                                    <?php if (!is_array($tag)) {
                                                                        $val = $tag;
                                                                        $tag = array();
                                                                        $tag['0'] = $val;
                                                                    } ?>
                                                            <div class="form-group col-md-4 col-xl-3">
                                                            <select name="tag[]" data-live-search="true" multiple class="form-control" id="multiselecttag">
                                                                <?php while ($tags = db_fetch_array($tagList)) { ?>
                                                                    <option value="<?= $tags['id'] ?>" <?= (@in_array($tags['id'], $tag ?? []) ? 'selected' : '') ?>><?= $tags['name'] ?></option>
                                                                <?php } ?>
                                                                </select>
                                                                </div>
                                                                <div class="form-group col-md-4 col-xl-3">
                                                                <select name="state[]" class="form-control" id="multiselect_state" multiple>
                                                                    <?php $ress = db_query("select * from states");
                                                                    while ($row = db_fetch_array($ress)) { ?>
                                                                        <option <?= (@in_array($row['id'], $state ?? []) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                    <?php  } ?>

                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4 col-xl-3" id="cityD">
                                                                <?php if ($_GET['state']) { ?>

                                                                    <select name="city[]" class="multiselect_city form-control" data-live-search="true" multiple>

                                                                        <?php 
                                                                        // print_r($state);die;
                                                                        $query = db_query("SELECT * FROM cities where state_id  IN (" . implode(",", $state) . ")");
                                                                        while ($row = db_fetch_array($query)) { ?>
                                                                            <option <?= (@in_array($row['id'], $city ?? []) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= $row['city'] ?></option>
                                                                        <?php } ?>
                                                                    </select>

                                                                <?php } else { ?>
                                                                    <div id="cityD">
                                                                    </div>
                                                                <?php } ?>
                                                            </div>

                                                    <div class="form-group col-md-4 col-xl-3">
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


                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Order Added Successfully!
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
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Order Updated Successfully!
                                </div>
                            <?php } ?>

                           

                            <div class="table-responsive">
                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                            <tr>
                                            <th>S.No.</th>
												<th>DR Code</th>
                                                <th>Reseller Name</th>
                                                <th>School Board</th>
                                                <th>School Name</th>
                                                <th>Number of Students</th>
                                                <th>Sub Product</th>
                                                <th>Date of Submission</th>
											    <th>Status</th>
											    <th>Stage</th>
                                                <th>Sub Stage</th>
											    <th>Demo Arranged</th>
											    <th>Demo Completed</th>
											    <th>Proposal Shared</th>
											    <th>Demo Login</th>
											    <th>DL + PS</th>
                                                <th>Qualified Status</th>
											    <th>Closed Date</th>
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
        <div id="myModal1" class="modal fade" role="dialog">


        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->

        <?php include('includes/footer.php') ?>
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable();
                $(document).ready(function() {
                    var table = $('#example').DataTable({
                        "columnDefs": [{
                            "visible": false,
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

                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                ],
                lengthMenu: [
                    [15, 25, 50, 100, 500, 1000],
                    ['15', '25', '50', '100', '500', '1000']
                ],
                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                "processing": true,
                "serverSide": true,
                stateSave: true,
                "ajax": {
                    url: "get_renewal_leads_partner.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                    d.d_from = "<?= $_GET['d_from'] ?>";
                    d.d_to = "<?= $_GET['d_to'] ?>";
                    d.d_type = "<?= $_GET['dtype'] ?>";
                    d.sub_product = '<?= json_encode($_GET['sub_product']) ?>';
                    d.state = '<?= json_encode($_GET['state']) ?>';
                    d.city = '<?= json_encode($_GET['city']) ?>';
                    d.sub_stage = '<?= json_encode($_GET['sub_stage']) ?>';                    
                    d.status = '<?= json_encode($_GET['status']) ?>';
                    d.users = '<?= json_encode($_GET['users']) ?>';
                    d.quantity = '<?= json_encode($_GET['quantity']) ?>';
                    d.state = '<?= json_encode($_GET['state']) ?>';
                    d.school_board = '<?= json_encode($_GET['school_board']) ?>';
                    d.source = '<?= json_encode($_GET['source']) ?>';
                    d.tag = '<?= json_encode($_GET['tag']) ?>';
                    
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="13">No data found on server!</th></tr></tbody>');
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

                'columns': [
                    { data: 'id' },
                 { data: 'code' },
                 { data: 'r_name' },
                 {data:'school_board'},
                 { data: 'school_name' },
                 { data: 'quantity' },
                 { data: 'sub_product' },
                   {data:'created_date'},
                   {data:'status'},
                  {data:'stage'},
                  {data:'sub_stage'},
                  {data:'demo_arranged'},
                  {data:'demo_completed'},
                  {data:'proposal_shared'},
                  {data:'demo_login'},
                  {data:'demo_login+proposal_shared'},  
                  {data:'qualified_status'},

                  {data:'close_date'},
                   
                
              ]
            });

            // Order by the grouping
            // $('#leads tbody').on('click', 'tr.group', function() {
            //     var currentOrder = table.order()[0];
            //     if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
            //         table.order([2, 'desc']).draw();
            //     } else {
            //         table.order([2, 'asc']).draw();
            //     }
            // });

            $(document).ready(function() {
                $('.product_data').on('change', function() {
                    //alert('abc');
                    var productID = $(this).val();
                    //alert(productID);
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


            function send_notification(title, company_name, submitted_by, id, sender_type, partner_name, sender_id, receiver_id) {
                $.ajax({
                    type: 'POST',
                    url: 'add_notification.php',
                    data: {
                        id: id,
                        title: title,
                        company_name: company_name,
                        submitted_by: submitted_by,
                        sender_type: sender_type,
                        partner_name: partner_name,
                        sender_id: sender_id,
                        receiver_id: receiver_id
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);
                        $('#myModal1').modal('show');
                    }
                });
            }


            function delete_notification(id) {
                swal({
                    title: "Are you sure?",
                    text: "Are you sure you want to delete request ?",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonText: "Yes!",
                    confirmButtonColor: "#ec6c62"
                }, function() {
                    $.ajax({
                            type: 'POST',
                            url: 'notifyLead_partner.php',
                            data: {
                                id: id,
                            },
                            success: function(response) {
                                return false;
                            }
                        }).done(function(data) {
                            swal("Request deleted successfully!");
                            setTimeout(function() {
                                location.reload();
                            }, 1000)
                        })
                        .error(function(data) {
                            swal("Oops", "We couldn't connect to the server!", "error");
                        });
                })
            }

            // function send_message() {
            //     swal({
            //         title: "Oops,Unable to convert this account for LC Calling!!",
            //         text: "For better progression on LC, Visit is required.",
            //         type: "warning",
            //         closeOnConfirm: false,
            //         confirmButtonText: "Ok",
            //         confirmButtonColor: "#ec6c62"
            //     });
            // }

            $(document).ready(function() {
                $('#plan_of_action').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Plan of Action'
                });
            });

            function clear_search() {
                window.location = 'renewal_leads_partner.php';
            }

            // $(function() {
            //     $('.datepicker').daterangepicker({

            //         "singleDatePicker": true,
            //         "showDropdowns": true,
            //         locale: {
            //             format: 'YYYY-MM-DD'
            //         },
            //         //startDate: '2017-01-01',
            //         //autoUpdateInput: false,

            //     });
            // });

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

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
                                    if (result == 1) {
                                        swal({
                                            title: "Done!",
                                            text: "Lead Re-Loged.",
                                            type: "success"
                                        }, function() {
                                            //location.reload();
                                            $('#leads').DataTable().ajax.reload();

                                        });
                                    } else {
                                        swal("Can't Relog Lead!", "Lead already logged once in the past!", "error");
                                    }
                                }
                            });

                        } else {
                            swal("Cancelled", "Lead unchanged!", "error");
                        }
                    });
                }
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
                        var stage = $('#dd_stage').val();
                        var stage = $.trim(stage);
                        var user_type = '<?= $_SESSION['user_type'] ?>';
                        if ((stage == 'EU PO Issued') && (user_type == 'USR' || user_type == 'PUSR')) {
                            $("#save_button").prop('disabled', true);
                        }
                        $('.preloader').hide();
                    }
                });
            }

            function get_change_data(pid, ids) {
                var stage = $('#dd_stage :selected').text();
                var stagevalue = $('#dd_stage :selected').val();
                var substage = $('#add_comment_dd :selected').text();
                var substagevalue = $('#add_comment_dd :selected').val();
                if (stagevalue == '') {
                    swal("Please select stage first.");
                    return false;
                }
                if (substagevalue == '') {
                    swal('Please select sub stage first');
                    return false;
                }

                if (substage == 'Lost to competition') {
                    var Psubstage = $('#add_Pcomment_dd :selected').text();
                } else {
                    $('#add_Pcomment_dd option:selected').remove()
                }

                if (substage == '100% Advance Received' || substage == 'Payment Against Delivery') {
                    var op = $("input[name='op']:checked").val();

                } else if (substage == 'Payment in Installments') {
                    var order_price = $("input[name=order_price]").val();
                    var date1 = $("input[name=date1]").val();
                    var instalment1 = $("input[name=instalment1]").val();
                    var date2 = $("input[name=date2]").val();
                    var instalment2 = $("input[name=instalment2]").val();
                    var date3 = $("input[name=date3]").val();
                    var instalment3 = $("input[name=instalment3]").val();
                    var date4 = $("input[name=date4]").val();
                    var instalment4 = $("input[name=instalment4]").val();
                    var date5 = $("input[name=date5]").val();
                    var instalment5 = $("input[name=instalment5]").val();
                    var date6 = $("input[name=date6]").val();
                    var instalment6 = $("input[name=instalment6]").val();
                    //chage_stage(stage,pid,ids,substage,op,date1,instalment1,date2,instalment2,date3,instalment3,date4,instalment4);
                }



            }

            function chage_stage(stage, id, ids, substage, payment_status, attachments,demo_datetime) {

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
                $('.dataTables_wrapper').height(wfheight - 310);
                $("#leads").tableHeadFixer();

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
                $('.multiselect_sub_stage').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Sub Stage',
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
                $('#multiselect_school_board').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select School Board',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#lead_source').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Lead Source',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselecttag').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Tag',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_state').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select State',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_city').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select City',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });



            $(document).ready(function() {
                $('#multiselect').on('change', function() {

                var e = $(this).val();
                    if (e) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxusers.php',
                            data: 'stage=' + e,
                            success: function(html) {
                                //alert(html);
                                $('#sub_stageD').html(html);
                            }
                        });
                    }
                });
            });

                            $(document).ready(function() {
                $('#multiselect_state').on('change', function() {

                var e = $(this).val();
                    if (e) {
                        $.ajax({
                            type: 'POST',
                            url: 'general_changes.php',
                            data: 'state=' + e,
                            success: function(html) {
                                //alert(html);
                                $('#cityD').html(html);
                            }
                        });
                    }
                });
            });
        </script>