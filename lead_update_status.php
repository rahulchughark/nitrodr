<?php include('includes/header.php');
//admin_page();
?>

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card lead_update_status" >

                        <div class="card-body">
                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">
                                    <small class="text-muted">Home >Lead Update Status Report</small>
                                    <h4 class="font-size-14 m-0 mt-1">Lead Update Status Report</h4>
                                </div>
                            </div>

                       <div class="clearfix"></div>  
					    <form id="mass_lead" method="post">

                            <div class="btn-group float-right" role="group" style="margin-top:-30px;">
                                    <div class="dropdown dropdown-lg">

                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                        <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                            <div class="row">
                                                <?php if ($_SESSION['user_type'] != 'MNGR') { ?>
                                                    <div class="form-group col-md-4">
                                                        <?php if ($_SESSION['sales_manager'] != 1) {
                                                            $res = db_query("select * from partners where status='Active'");
                                                        } else {
                                                            $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                                        } ?>
                                                        <select name="partner" id="partner" class="form-control">
                                                            <option value="">Partner</option>
                                                            <?php while ($row = db_fetch_array($res)) { ?>
                                                                <option <?= (($_GET['partner'] == $row['name']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <?php if ($_GET['partner']) { ?>

                                                            <select name="users" class="form-control">

                                                                <?php $query = db_query("SELECT * FROM users WHERE team_id = " . $_GET['partner'] . " and status='Active'  ORDER BY name ASC");
                                                                while ($row = db_fetch_array($query)) { ?>
                                                                    <option <?= (in_array($row['id'], $users) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                                <?php } ?>
                                                            </select>

                                                        <?php } else { ?>

                                                            <select name="users" id="users" class="form-control">
                                                                <option value="">Select Submitted By</option>

                                                            </select>

                                                        <?php } ?>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="form-group col-md-4">
                                                        <select name="users" class="form-control">
                                                            <option value="">Select Submitted By</option>
                                                            <?php $query = db_query("SELECT * FROM users WHERE team_id = " . $_SESSION['team_id'] . " and status='Active'  ORDER BY name ASC");
                                                            while ($row = db_fetch_array($query)) { ?>
                                                                <option <?= (in_array($row['id'], $users) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                <?php } ?>
                                                <div class="form-group col-md-4">
                                                    <select class="form-control" id="cat_type" name="cat_type">
                                                        <option value="">Field Category</option>
                                                        <option <?= (($_GET['cat_type'] == 'Stage') ? 'selected' : '') ?> value="Stage">Stage</option>
                                                        <option <?= (($_GET['cat_type'] == 'logCall') ? 'selected' : '') ?> value="logCall">Log a Call</option>
                                                        <option <?= (($_GET['cat_type'] == 'Close Date') ? 'selected' : '') ?> value="Close Date">Close Date</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <select class="form-control" id="contains" name="contains">
                                                        <option value="">Contains</option>
                                                        <option <?= (($_GET['contains'] == 'updated') ? 'selected' : '') ?> value="updated">Updated</option>
                                                        <option <?= (($_GET['contains'] == 'not_updated') ? 'selected' : '') ?> value="not_updated">Not Updated</option>

                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <select class="form-control" id="duration" name="duration">
                                                        <option value="">Duration</option>
                                                        <option <?= (($_GET['duration'] == '3days') ? 'selected' : '') ?> value="3days">In 3 Days</option>
                                                        <option <?= (($_GET['duration'] == '7days') ? 'selected' : '') ?> value="7days">In 7 Days</option>
                                                        <option <?= (($_GET['duration'] == '10days') ? 'selected' : '') ?> value="10days">More than 10 Days</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From">

                                                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To">
                                                    </div>
                                                </div>


                                                <div class="form-group col-md-12">
                                                    <button type="button" id="search" name="data_search" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>

                                                    <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </form>

                            
							  
                                <div id="table_wrapper">
								
								
                                </div>
                         
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div id="myModal1" class="modal" role="dialog">

        </div>


        <?php include('includes/footer.php') ?>
        <script>
            $(document).ready(function() {
                $('#search').on('click', function() {
                    if ($('#d_from').val() == "" && $('#d_to').val() == "" && $('#cat_type').val() == "" && $('#contains').val() == "" && $('#partner').val() == "" && $('#users').val() == "" && $('#duration').val() == "") {
                        swal('Select filter data!!');
                        return false;
                    }
                    var formdata = $("#mass_lead").serialize()

                    $.ajax({
                        type: 'post',
                        url: 'getLeadStatus.php',
                        data: formdata,
                        success: function(response) {
                            $("#table_wrapper").html(response);
                        }
                    });
                    return false;
                });
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

            function clear_search() {
                window.location = 'lead_update_status.php';
            }


            $(document).ready(function() {
                $('.multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
            });

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
                $('.dataTables_wrapper').height(wfheight - 310);
				 $('.lead_update_status').height(wfheight - 135);
                $("#leads").tableHeadFixer();

            });
        </script>