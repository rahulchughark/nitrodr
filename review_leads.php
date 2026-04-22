<?php include('includes/header.php');
if ($_SESSION['user_type'] != 'REVIEWER') admin_page();

include_once('helpers/DataController.php');

$data_log = new DataController();

if ($_POST['review_edit']) {
    if ($_POST['incomplete_check'] == 'yes') 
    {
        $query = db_query("update lead_review set is_review=2 where lead_id='" . $_POST['pid'] . "'");

    } else {

        $query = db_query("update lead_review set is_review=0,removed_by='" . $_SESSION['user_id'] . "',removed_date='".date('Y-m-d H:i:s')."' where lead_id='" . $_POST['pid'] . "'");
        
        $o_stage = getSingleresult("select stage from orders where id='" . $_POST['pid'] . "'");
        $o_lead_type = getSingleresult("select lead_type from orders where id='" . $_POST['pid'] . "'");
        $o_caller = getSingleresult("select caller from orders where id='" . $_POST['pid'] . "'");

        $data = ['lead_id' => $_POST['pid'], 'old_stage' => $o_stage, 'new_stage' => $_POST['stage'], 'sub_stage' => $_POST['add_comm'], 'comment' => $_POST['comment'], 'added_by' => $_SESSION['name'], 'old_lead_type' => $o_lead_type, 'new_lead_type' => $_POST['type_lead'], 'old_caller' => $o_caller, 'new_caller' => $_POST['caller']];
        $log_query = $data_log->insert($data, "review_log");

        //$log_query = db_query("insert into review_log (lead_id,old_stage,new_stage,sub_stage,comment,added_by,old_lead_type,new_lead_type,old_caller,new_caller	) values ('" . $_POST['pid'] . "','" . $o_stage . "','" . $_POST['stage'] . "','" . $_POST['add_comm'] . "','" . $_POST['comment'] . "','" . $_SESSION['name'] . "','" . $o_lead_type . "','" . $_POST['type_lead'] . "','" . $o_caller . "','" . $_POST['caller'] . "')");

        if ($_POST['type_lead']) {
            $update_data = ['lead_type' => $_POST['type_lead']];
            $where = ['id' => $_POST['pid']];
            $sql = $data_log->update($update_data, 'orders', $where);
        }

        if ($_POST['caller']) {
            $update_data = ['caller' => $_POST['caller']];
            $where = ['id' => $_POST['pid']];
            $sql = $data_log->update($update_data, 'orders', $where);
        }

        if ($_POST['stage']) {
            //print_r($_POST);die;
            $query = db_query("update orders set prospecting_date='" . date('Y-m-d') . "', stage='" . $_POST['stage'] . "',add_comm='" . $_POST['add_comm'] . "',add_Parallelcomm='" . $_POST['add_Pcomm'] . "',add_comment='".$_POST['comment']."' where id='" . $_POST['pid'] . "'");

            if ($_POST['sub_stage'] == 'Payment in Installments') {

                $query = db_query("select * from installment_details where pid=" . $_POST['pid']);
                //print_r($query);die;
                if (mysqli_num_rows($query) > 0) {

                    $installment_data = db_query("update installment_details set pid='" . $_POST['pid'] . "',type='Lead',order_price='" . $_POST['order_price'] . "',date1='" . $_POST['date1'] . "',instalment1='" . $_POST['instalment1'] . "',date2='" . $_POST['date2'] . "',instalment2='" . $_POST['instalment2'] . "',date3='" . $_POST['date3'] . "',instalment3='" . $_POST['instalment3'] . "',date4='" . $_POST['date4'] . "',instalment4='" . $_POST['instalment4'] . "',date5='" . $_POST['date5'] . "',installment5='" . $_POST['instalment5'] . "',date6='" . $_POST['date6'] . "',installment6='" . $_POST['instalment6'] . "',added_by='" . $_SESSION['user_id'] . "' where pid='" . $_POST['pid'] . "'");
                } else {

                    $installment_data = db_query("insert into installment_details (`pid`, `type`, `order_price`, `date1`, `instalment1`, `date2`, `instalment2`, `date3`, `instalment3`, `date4`, `instalment4`, `date5`, `installment5`, `date6`, `installment6`, `added_by`) values ('" . $_POST['pid'] . "','Lead','" . $_POST['order_price'] . "','" . $_POST['date1'] . "','" . $_POST['instalment1'] . "','" . $_POST['date2'] . "','" . $_POST['instalment2'] . "','" . $_POST['date3'] . "','" . $_POST['instalment3'] . "','" . $_POST['date4'] . "','" . $_POST['instalment4'] . "','" . $_POST['date5'] . "','" . $_POST['instalment5'] . "','" . $_POST['date6'] . "','" . $_POST['instalment6'] . "','" . $_SESSION['user_id'] . "')");
                }
            }
            if ($_POST['sub_stage'] == '100% Advance Received' || $_POST['sub_stage'] == 'Payment Against Delivery') {
                $ps = db_query("update orders set op_this_month='" . $_POST['op'] . "' where id='" . $_POST['pid'] . "'");
            }
        }

        if ($query) {
            $select_query = db_query("select * from orders where id='" . $_POST['pid'] . "'");
            foreach ($select_query as $value) {
                $team_id = $value['team_id'];
                $company_name = $value['company_name'];
                $quantity = $value['quantity'];
                $city = $value['city'];
                $lead_type = $value['lead_type'];
                $caller = $value['caller'];
                //print_r($team_id);die;
            }
           // $user_email = getSingleresult("select email from users where user_type='USR' and team_id='" . $team_id . "' and status='Active'");

            $manager_email = getSingleresult("select email from users where user_type='MNGR' and team_id='" . $team_id . "' and status='Active'");

            $sm_email = getSingleresult("select users.email as email from users join partners on partners.sm_user=users.id where partners.id='" . $team_id . "' and users.status='Active'");
            if ($sm_email){
                $addCc[] = ($sm_email);
            }
                
            $caller_email = getSingleresult("select users.email as email from users join callers on callers.user_id=users.id where callers.id='" . $caller . "' and users.status='Active'");
            if ($caller_email){
                $addCc[] = ($caller_email);
            }

            $caller_name = getSingleresult("select users.name from users left join callers on callers.user_id=users.id where users.email='".$caller_email."'");

            $users2 = db_query("select users.email,users.name from users left join orders on orders.created_by=users.id where orders.id='" . $_POST['pid'] . "' and users.status='Active' ");

            $users = db_fetch_array($users2);
            $addTo[] = ($users['email']);
            $addCc[] = ($_SESSION['email']);
            $addCc[] = ($manager_email);
            $addCc[] = ("prashant.dongrikar@arkinfo.in");

            $setSubject = "Under Review";
            $body    = "Hi,<br><br> Below account has been actioned as 'Completed' on DR Portal by '<strong>" . $_SESSION['name'] . "</strong>'<br><br>
            <ul>
            <li><b>Account Name</b> : " . ucwords($company_name) . " </li>
            <li><b>Quantity</b> : " . $quantity . " </li>
            <li><b>Lead Type</b> : " . $lead_type . " </li>
            <li><b>City</b> : " . ucwords($city) . " </li>
            <li><b>Review Comments</b> : " . $_POST['comment'] . " </li></ul><br>

            Thanks & Regards,<br>
            DR Support Team
            ";

            sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
        }
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

                                    <small class="text-muted">Home >Review Leads</small>
                                    <h4 class="font-size-14 m-0 mt-1">Review Leads</h4>
                                </div>
                            </div>

                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>


                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                                        <form method="get" name="search" class="form-horizontal" role="form">
										
								

                                            <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                    <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                    <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                </div>
                                            </div>

                                            <?php 
                                             if($_SESSION['sales_manager'] == 1)
                                             {
                                                $res = db_query("select * from partners where id in (".$_SESSION['access'].") ");
                                             }
                                             else
                                             {
                                                $res = db_query("select * from partners");
                                             }
                                            
                                            ?>
                                            <div class="form-group">
                                                <select name="partner" id="partner" class="form-control">
                                                    <option value="">---Select Partner---</option>
                                                    <?php while ($row = db_fetch_array($res)) { ?>
                                                        <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">

                                                <select name="users" id="users" class="form-control ">
                                                    <option value="">---Select User---</option>

                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control" id="is_review" name="is_review">
                                                    <option value="">---Review Status---</option>
                                                    <option <?= (($_REQUEST['is_review'] == '1') ? 'selected' : '') ?> value="1">Pending</option>
                                                    <option <?= (($_REQUEST['is_review'] == '0') ? 'selected' : '') ?> value="0">Done</option>
                                                    <option <?= (($_REQUEST['is_review'] == '2') ? 'selected' : '') ?> value="2">In-Complete</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <select name="product" class="product_data form-control">
                                                    <option value="">Select Product</option>
                                                    <?php $query = selectProduct('tbl_product');
                                                    while ($row = db_fetch_array($query)) { ?>
                                                        <option <?= (($_GET['product'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select name="product_type" id="product_type" class="form-control">
                                                    <option value="">Select Product Type</option>
                                                </select>

                                            </div>
                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                        </form>
                                    </div>
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
                            <div class="table-responsive">

                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Reseller name(Submitted by)</th>
                                            <th>Lead Type</th>
                                            <th>Quantity</th>
                                            <!-- <th>Product Name</th>
                                            <th>Product Type</th> -->
                                            <th>Company Name</th>
                                            <th>Date of Submission</th>
                                            <th>Last Stage</th>
                                            <th>Added On</th>
                                            <th>Review Status</th>
                                            <th>Data Reference</th>
                                            <th>Log</th>
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
                    [15, 25, 50, 100, 500, 1000],
                    ['15', '25', '50', '100', '500', '1000']
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "get_review_leads.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        d.partner = "<?= $_GET['partner'] ?>";
                        d.users = "<?= $_GET['users'] ?>";
                        d.review = "<?= $_GET['is_review'] ?>";
                        d.product = '<?= $_GET['product'] ?>';
                        d.product_type = '<?= $_GET['product_type'] ?>';

                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="12">No data found on server!</th></tr></tbody>');
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
                    // {
                    //     data: 'product_type'
                    // },
                    {
                        data: 'company_name'
                    },
                    {
                        data: 'created_date'
                    },
                    {
                        data: 'stage'
                    },
                    {
                        data: 'added_date'
                    },
                    {
                        data: 'is_review'
                    },
                    {
                        data: 'data_ref'
                    },
                    {
                        data: 'action'
                    },

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



            function clear_search() {
                window.location = 'review_leads.php';
            }

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            function chage_stage(stage, id) {

                //alert(stage + '' +id);


                if (stage != '') {
                    $.ajax({
                        type: 'post',
                        url: 'change_stage.php',
                        data: {
                            stage: stage,
                            lead_id: id
                        },
                        success: function(res) {
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

            function edit_review(id) {
                $.ajax({
                    type: 'POST',
                    url: 'review_edit.php',
                    data: {
                        pid: id,
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function view_log(id) {
                //$('.preloader').show();
                $.ajax({
                    type: 'POST',
                    url: 'view_review_log.php',
                    data: {
                        pid: id
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }
        </script>
        <script>
            jQuery("#search_toogle").click(function() {
                jQuery(".search_form").toggle("fast");
            });

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#leads").tableHeadFixer();

            });
        </script>