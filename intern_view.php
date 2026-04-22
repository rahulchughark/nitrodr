<?php include('includes/header.php'); ?>

<?php


if ($_REQUEST['id']) {

    $sql = db_query("select r.id,r.source,r.company_name,r.parent_company,r.landline,r.industry,r.sub_industry,r.region,r.address,r.pincode,r.state,r.city,r.country,r.eu_name,r.eu_email,r.eu_landline,r.department,r.eu_mobile,r.eu_designation,r.eu_role,r.quantity,r.created_by,r.created_date,r.team_id,r.r_name,r.r_email,r.r_user,r.product_id,tp.product_name,r.association_name,tpp.product_type,r.product_type_id as type_id from raw_leads as r left join tbl_product as tp on r.product_id=tp.id left join tbl_product_pivot as tpp on r.product_type_id=tpp.id where r.id=" . $_REQUEST['id'] );

    $data = db_fetch_array($sql);
    @extract($data);
} else {
    redir("raw_leads.php", true);
}

if ($_POST['association_name']) {
    $res =  db_query("insert into lead_modify_log(`raw_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Association Name','" . $row_data['association_name'] . "','" . $_POST['association_name'] . "',now(),'" . $_SESSION['user_id'] . "')");

    $sql = db_query("update raw_leads set association_name='" . $_POST['association_name'] . "' where id=" . $_GET['id']);
}

if ($_POST['remarks'] && !$_POST['activity_edit']) {

    $res = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by,is_intern,data_Ref) values ('" . $_POST['pid'] . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','Raw','" . $_POST['call_subject'] . "','" . $_SESSION['user_id'] . "',1,1)");
}

if ($_POST['activity_edit']) {
    $res = db_query("update activity_log set description='" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "',call_subject='" . $_POST['call_subject'] . "' where activity_type='Raw' and id=" . $_POST['pid']);
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

                            <!-- <h5 class="card-title">Add Lead</h5>-->

                            <div class="media ">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > View Intern Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">View Intern Lead</h4>
                                </div>


                            </div>
                            <div class="clearfix"></div>
                            <div data-simplebar class="add_lead">
                                <div class="accordion" id="accordionExample2">
                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne2">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne2">
                                                    Lead Modify Log
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne2" class="" aria-labelledby="headingOne2" data-parent="#accordionExample2">

                                            <?php if ($data['lapsed_date'] && $data['lapsed_date'] != '0000-00-00 00:00:00') { ?>
                                                <div class="card-body font-size-13">Lapsed on <strong><?= date('F j, Y, g:i a', strtotime($data['lapsed_date'])) ?></strong></div>

                                                <?php
                                            }
                                            $sql = db_query("select * from lead_modify_log where log_status='Active' AND raw_id=" . $_REQUEST['id'] . " order by id desc");

                                            if (db_num_array($sql) > 0) {
                                                while ($data1 = db_fetch_array($sql)) { ?>
                                                    <div class="card-body font-size-13"> <?= getSingleresult("select name from users where id=" . $data1['created_by']) . (($data1['type'] != 'Request Status' && $data1['type'] != 'Request Delete Status') ? (' has changed <strong>' . $data1['type'] . ' </strong>') : (($data1['type'] == 'Request Status') ? ' <strong>has requested Status Change</strong>' : ' <strong>has deleted Status Change Request</strong>')) ?> from <strong> <?= ($data1['previous_name'] ? $data1['previous_name'] : 'N/A') ?> </strong> to <strong> <?= $data1['modify_name'] ?></strong> on <?= date('F j, Y, g:i a', strtotime($data1['created_date'])) ?>.
                                                    </div>

                                            <?php $count++;
                                                }
                                            } ?>

                                            <div class="card-body font-size-13">Created by <strong><?= getSingleresult("select name from users where id=" . $created_by) ?></strong> on <strong><?= date('F j, Y, g:i a', strtotime($created_date)) ?></strong></div>


                                        </div>

                                    </div>


                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne3">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne3" aria-expanded="true" aria-controls="collapseOne3">
                                                    Product Info
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne3" class="" aria-labelledby="headingOne3" data-parent="#accordionExample2">


                                            <div class="row">
                                                <div class="col-lg-12">

                                                    <table class="table" id="user">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Product Name</td>
                                                                <td width="65%"><?= $product_name ?> <br>
                                                                    <select name="product" class="product_data">
                                                                        <option value="">Select Product</option>
                                                                        <?php
                                                                        $query = selectProductPartner($_SESSION['team_id']);

                                                                        while ($row = db_fetch_array($query)) { ?>
                                                                            <option <?= (($_GET['product'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </td>
                                                                <input type="hidden" name="product_id" id="product_id" value="<?= $product_name ?>" />
                                                                <input type="hidden" name="raw_id" id="raw_id" value="<?= $_REQUEST['id'] ?>" />
                                                            </tr>
                                                            <tr>
                                                                <td>Product Type</td>
                                                                <td>
                                                                    <?= $product_type ?>
                                                                    <div id="product_type"></div>

                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne4">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne4" aria-expanded="true" aria-controls="collapseOne4">
                                                    User Info
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne4" class="" aria-labelledby="headingOne4" data-parent="#accordionExample2">


                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                            <tr>
                                                                <td>Reseller Email</td>
                                                                <td>
                                                                    <?= $r_email ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Submitted By</td>
                                                                <td>
                                                                    <?= $r_user ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne5">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne5" aria-expanded="true" aria-controls="collapseOne5">
                                                    Customer Information
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne5" class="" aria-labelledby="headingOne5" data-parent="#accordionExample2">


                                            <div class="row">

                                                <div class="col-lg-12">

                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Lead Source</td>
                                                                <td width="65%"><?= $source ?></td>
                                                            </tr>

                                                            <tr>
                                                                <td>Company Name</td>
                                                                <td>
                                                                    <?= $company_name ?>
                                                                    <input type="hidden" name="company_name" value="<?= $company_name ?>" id="company">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Parent Company</td>
                                                                <td>
                                                                    <?= $parent_company ?>
                                                                </td>
                                                            </tr>
                                                            <tr>

                                                                <form method="post" name="save_association_form">
                                                                    <?php if ($type_id == 1 || $type_id == 2) {
                                                                        if (!empty($association_name)) { ?>
                                                                            <td>Association Name</td>
                                                                            <td id="edit_association">
                                                                                <?= $association_name ?>
                                                                                <input type="hidden" name="edit_association_name" value="<?= $association_name ?>" id="edit_association_name">
                                                                                <?php if ($created_by == $_SESSION['user_id']) { ?>
                                                                                    <button class="btn1 btn-primary" onclick="change_association('<?= $association_name ?>')">Edit</button>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <?php } else {
                                                                            if ($created_by == $_SESSION['user_id']) { ?>
                                                                                <td>Association Name</td>
                                                                                <td><input type="text" name="association_name" value="<?= $association_name ?>">
                                                                                    <button type="submit" class="btn1 btn-primary">Save</button>
                                                                                </td>
                                                                    <?php }
                                                                        }
                                                                    } ?>
                                                                </form>
                                                            </tr>
                                                            <tr>
                                                                <td>Landline Number</td>
                                                                <td>
                                                                    <?= $landline ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Industry</td>
                                                                <td>
                                                                    <?= getSingleresult("select name from industry where id=" . $industry) ?>
                                                                </td>
                                                            </tr>
                                                            <?php if ($sub_industry) { ?><tr>
                                                                    <td>Sub Industry</td>
                                                                    <td>
                                                                        <?= getSingleresult("select name from sub_industry where id=" . $sub_industry) ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            <tr>
                                                                <td>Region</td>
                                                                <td>
                                                                    <?= $region ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Address</td>
                                                                <td>
                                                                    <?= $address ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Pin Code</td>
                                                                <td>
                                                                    <?= $pincode ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>State</td>
                                                                <td>
                                                                    <?= ($state ? getSingleresult("select name from states where id=" . $state) : '') ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>City</td>
                                                                <td>
                                                                    <?= $city ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Country</td>
                                                                <td>
                                                                    <?= $country ?>
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne6">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                                    Decision Maker/Proprietor/Director/End User Details
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">
                                            <div class="row">
                                                <div class="col-lg-12">

                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Full Name</td>
                                                                <td width="65%"> <?= $eu_name ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email</td>
                                                                <td>
                                                                    <?= $eu_email ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Landline Number</td>
                                                                <td>
                                                                    <?= $eu_landline ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Mobile</td>
                                                                <td>
                                                                    <?= $eu_mobile ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Designation</td>
                                                                <td>
                                                                    <?= $eu_designation ?>
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne7">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne7" aria-expanded="true" aria-controls="collapseOne7">
                                                    Lead Information
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne7" class="" aria-labelledby="headingOne7" data-parent="#accordionExample2">

                                            <div class="row">
                                                <div class="col-lg-12">

                                                    <table class="table">
                                                        <tbody>

                                                            <tr>
                                                                <td>Quantity</td>
                                                                <td id="quant">
                                                                    <?= $quantity ?> User(s)
                                                                    <input type="hidden" name="quantity" value="<?= $quantity ?>" id="qty">
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td>Created on</td>
                                                                <td>
                                                                    <?= date('d-m-Y H:i:s', strtotime($created_date)) ?>
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne8">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne8" aria-expanded="true" aria-controls="collapseOne8">
                                                    Activity History
                                                </button>

                                            </h5>
                                            <a href="" data-toggle="modal" onclick="add_raw_activity(<?= $_GET['id'] ?>)" data-animation="bounce" data-target=".bs-example-modal-center"><button class="btn1 btn-primary ml-2 mt-1">Log a Call</button></a>
                                        </div>

                                        <div id="collapseOne8" class="" aria-labelledby="headingOne8" data-parent="#accordionExample2">




                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php
                                                    $query = access_role_permission();
                                                    $fetch_query = db_fetch_array($query);

                                                    $new = db_query("select id,description,created_date,added_by,call_subject from activity_log where activity_type like '%raw%' and pid='" . $_GET['id'] . "' order by created_date desc");

                                                    $goal = db_query("select * from activity_log where pid='" . $_GET['id'] . "' order by created_date desc");

                                                    $count = mysqli_num_rows($new);
                                                    $i = $count;
                                                    if ($count) {
                                                        echo  ' <table class="table"><thead class="thead-default"><tr><th>S.No</th><th>Subject</th><th>Description</th><th>Added By</th><th>Date</th>';

                                                        if ($fetch_query['edit_log'] == 1) {
                                                            '<th>Action</th>';
                                                        }
                                                        '</tr></thead>';

                                                        while ($data_n = db_fetch_array($new)) { ?>
                                                            <tbody>
                                                                <tr style="text-align:center;">
                                                                    <td><?= $i ?></td>
                                                                    <td><?= ($data_n['call_subject'] ? $data_n['call_subject'] : 'N/A') ?></td>
                                                                    <td><?= $data_n['description'] ?></td>
                                                                    <td><?= (is_numeric($data_n['added_by']) ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User'  WHEN user_type='ADMIN' and sales_manager=0 THEN 'ADMIN' WHEN user_type='ADMIN' and sales_manager=1 THEN 'Sales Manager' WHEN user_type='MNGR' THEN 'Partner Manager' WHEN user_type='SUPERADMIN' THEN 'Superadmin' WHEN user_type='INTERN' THEN 'Corel Intern' ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : '<span style="color:red">(Lead Review!)</span> ' . $data_n['added_by']) ?></td>
                                                                    <td><?= date('d-m-Y H:i:s', strtotime($data_n['created_date'])) ?></td>
                                                                    <?php
                                                                    if ($fetch_query['edit_log'] == 1) { ?>
                                                                        <td><a href="javascript:void(0)" title="Edit" id=but<?= $data['id'] ?> onclick="edit_activity('<?= $data_n['id'] ?>','<?= $company_name ?>')"> <i style="font-size:18px" class="mdi mdi-pencil"></i></a></td>
                                                                    <?php } ?>
                                                                </tr>
                                                            </tbody>
                                                    <?php $i--;
                                                        }
                                                        echo "</table>";
                                                    } ?>


                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="button-items1">
                                        <?php if ($created_by == $_SESSION['user_id']) { ?>

                                            <a href="edit_intern.php?eid=<?= $id ?>"><button type="button" class="btn1 btn-primary mt-2">Edit</button></a>

                                        <?php } else if ($_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'ADMIN') { ?>
                                            <a href="edit_intern.php?eid=<?= $id ?>"><button type="button" class="btn1 btn-primary mt-2">Edit</button></a>
                                        <?php } ?>
                                        <button type="button" onclick="window.location.replace(document.referrer)" class="btn1 btn-danger mt-2">Back</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->


                </div> <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
    </div>
</div>
<!-- End Page-content -->
<div id="myModal" class="modal fade" role="dialog">


</div>
<?php include('includes/footer.php') ?>

<?php $data = db_query("select * from raw_leads where id=" . $_REQUEST['id']);
$data_query = db_fetch_array($data);
if (empty($data_query['product_id']) && empty($data_query['product_type_id'])) { ?>
    <script>
        var id = $('#raw_id').val();
        var company = $('#company').val();
        var qty = $('#qty').val();
        //alert(id);
        $(function() {
            $.ajax({
                type: 'POST',
                url: 'raw_product.php',
                data: {
                    id: id,
                    company: company,
                    qty: qty
                },
                success: function(response) {
                    $("#selfReview").html();
                    $("#selfReview").html(response);
                    $('#selfReview').modal('show');

                }
            });
        });
    </script>
<?php } ?>

<script>
                            function edit_activity(id,company_name) {
                            $.ajax({
                                type: 'POST',
                                url: 'edit_activity.php',
                                data: {
                                    id: id,
                                    company_name:company_name
                                },
                                success: function(response) {
                                    $("#myModal").html();
                                    $("#myModal").html(response);
                                    $('#myModal').modal('show');
                                }
                            });
                        }

    // function chage_stage(a) {
    //     if (a == 'Closed Lost') {
    //         $("#add_comment").show();
    //         $("#add_comment_dd").attr("required", "required");
    //         $("#payment").hide();
    //         $("#payment_dd").removeAttr("required", "required");
    //         $("#op").hide();
    //         $("#pay_tab").hide();
    //     } else if (a == 'EU PO Issued') {
    //         $("#add_comment").hide();
    //         $("#add_comment_dd").removeAttr("required", "required");
    //         $("#payment").show();
    //         $("#payment_dd").attr("required", "required");
    //     } else {
    //         $("#add_comment").hide();
    //         $('#add_comment_dd').removeAttr("required", "required");
    //         $("#payment").hide();
    //         $("#payment_dd").removeAttr("required", "required");
    //         $("#op").hide();
    //         $("#pay_tab").hide();
    //     }
    // }

    // function payment_option(val) {
    //     //alert(val);
    //     if (val == '100% Payment Received' || val == 'Payment After Software Delivery') {
    //         $("#op").show();
    //         $("#pay_tab").hide();
    //     } else if (val == 'Payment in Installments') {
    //         $("#pay_tab").show();
    //         $("#op").hide();
    //     } else if (val == 'Payment Not Clear' || val == '') {
    //         //alert(12);
    //         $("#pay_tab").hide();
    //         ("#op").hide();
    //     }
    // }

    function add_raw_activity(a) {
        $.ajax({
            type: 'POST',
            url: 'add_raw_activity.php',
            data: {
                pid: a
            },
            success: function(response) {
                $("#myModal").html();
                $("#myModal").html(response);
                $('#myModal').modal('show');
            }
        });
    }

    function view_activity(a) {
        var type = 'Lead';
        $.ajax({
            type: 'POST',
            url: 'view_activity.php',
            data: {
                pid: a,
                type: type
            },
            success: function(response) {
                $("#myModal").html();
                $("#myModal").html(response);
                $('#myModal').modal('show');
            }
        });
    }

    function change_association(a) {
        document.getElementById("edit_association").innerHTML = '<input type="text" value="' + a + '" id="new_association"/> <button onclick="save_association()" class="btn btn-warning">Save</button>'

    }

    function save_association() {

        var new_assoc = document.getElementById("new_association").value;

        if (new_assoc) {
            swal({
                title: "Are you sure?",
                text: "You want to change the association name for this lead!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Change!",
                cancelButtonText: "No, cancel modification!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "update_association_name.php?id=<?= $_GET['id'] ?>&raw_association=" + new_assoc,
                        success: function(result) {
                            if (result) {
                                swal({
                                    title: "Done!",
                                    text: "Lead Modified.",
                                    type: "success"
                                }, function() {
                                    location.reload();

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
        //alert("hi");
        $('.product_data').on('change', function() {
            var productID = $(this).val();
            var raw_id = '<?= $_GET['id'] ?>';
            var product_type = '<?= $product_type ?>';

            if (productID) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxProductTypeView.php',
                    data: {
                        product: productID,
                        raw_id: raw_id,
                        product_type: product_type
                    },
                    success: function(html) {
                        $('#product_type').html(html);
                    },
                });
            }
        });
    });



    $(function() {
        $('.datepicker').daterangepicker({

            "singleDatePicker": true,
            "showDropdowns": true,
            minDate: new Date(),
            locale: {
                format: 'YYYY-MM-DD'
            },
            autoUpdateInput: false,


        }, function(start, end) {
            $(this.element).val(start.format('YYYY-MM-DD'));
        });



    });

    function change_quantity(a) {
        document.getElementById("quant").innerHTML = '<input type="text" value="' + a + '" id="new_quantity"/> <button onclick="save_newqty()" class="btn btn-warning">Save</button>'

    }

    function save_newqty() {
        var newquant = document.getElementById("new_quantity").value;
        if (newquant) {
            swal({
                title: "Are you sure?",
                text: "You want to change the quantity for this lead!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Change!",
                cancelButtonText: "No, cancel modification!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "update_quantity.php?id=<?= $_GET['id'] ?>&quantity=" + newquant,
                        success: function(result) {
                            if (result) {
                                swal({
                                    title: "Done!",
                                    text: "Lead Modified.",
                                    type: "success"
                                }, function() {
                                    location.reload();

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


</script>
<script>
    $(document).ready(function() {
        var wfheight = $(window).height();
        $('.add_lead').height(wfheight - 220);
    });
</script>