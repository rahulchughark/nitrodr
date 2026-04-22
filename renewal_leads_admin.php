<?php 

// echo "<pre>";
// print_r($_GET);
// exit;

include('includes/header.php');
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
        $statusU = $_GET['status'];
        if(!empty($statusU)){
            foreach ($statusU as $pr) {
                $urlCond.= "&status[]=$pr";
            }
        }
        $sub_productU = $_GET['sub_product'];
        if(!empty($sub_productU)){
            foreach ($sub_productU as $pr) {
                $urlCond.= "&sub_product[]=$pr";
            }
        }
        $school_boardU = $_GET['school_board'];
        if(!empty($school_boardU)){
            foreach ($school_boardU as $pr) {
                $urlCond.= "&school_board[]=$pr";
            }
        }
        $sourceU = $_GET['source'];
        if(!empty($sourceU)){
            foreach ($sourceU as $pr) {
                $urlCond.= "&source[]=$pr";
            }
        }
        $tagU = $_GET['tag'];
        if(!empty($tagU)){
            foreach ($tagU as $pr) {
                $urlCond.= "&tag[]=$pr";
            }
        }
        $partnerU = $_GET['partner'];
        if(!empty($partnerU)){
            foreach ($partnerU as $pr) {
                $urlCond.= "&partner[]=$pr";
            }
        }
        $stageU = $_GET['stage'];
        if(!empty($stageU)){
            foreach ($stageU as $pr) {
                $urlCond.= "&stage[]=$pr";
            }
        }
        $sub_stageU = $_GET['sub_stage'];
        if(!empty($sub_stageU)){
            foreach ($sub_stageU as $pr) {
                $urlCond.= "&sub_stage[]=$pr";
            }
        }

        ?> 
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
                                    <div class="media bredcrum-title">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
        
                                            <small class="text-muted">Home >Renewal Leads</small>
                                            <h4 class="font-size-14 m-b-14 mt-1">Renewal Leads</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div role="group" >
                                    <?php if($_SESSION['download_status'] == 1){ ?>
                                        <!-- <a href="javascript:void(0);" id="sdfexport"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="SFDC Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-share"></i></button></a> -->

                                        <a href="export_leads_admin_opportunity.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>&dtype=<?= @$_GET['date_type'] ?>&type=Renewal<?= $urlCond ?>">
                                            <button title="Excel Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-download"></i>
                                            </button>
                                        </a>
                                        <?php } ?>    
                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                        <div class="dropdown dropdown-lg">

                                            <div class="dropdown-menu dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">
                                                <form method="get" name="search" class="form-horizontal" role="form">

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

                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select class="form-control" name="ownership">
                                                                <option value="">Lead Ownership</option>
                                                                <option <?= (($_GET['ownership'] == 'all') ? 'selected' : '') ?> value="all">All</option>
                                                                <option <?= (($_GET['ownership'] == 'my_leads') ? 'selected' : '') ?> value="my_leads">My Leads</option>
                                                                <option <?= (($_GET['ownership'] == 'assigned_to_me') ? 'selected' : '') ?> value="assigned_to_me">Assigned To Me</option>
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
                                                                <option value="<?= $tags['id'] ?>" <?= (@in_array($tags['id'], $tag) ? 'selected' : '') ?>><?= $tags['name'] ?></option>
                                                            <?php } ?>
                                                            </select>
                                                            </div>

                                                    </div>
                                                    <?php 
                                                    if ($_SESSION['user_type'] != 'SALES MNGR') {
                                                        $res = db_query("select * from partners where status='Active'");
                                                    } else {
                                                        $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                                    }
                                                    ?>

                                                    <div class="row">
                                                    <?php if($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') { ?>
                                                        <div class="form-group col-md-4 col-xl-3">
                                                            <select name="just_partner" class=" form-control" data-live-search="true">
                                                                    <option value=''>Select Just Partners</option>
                                                                    <option value='Yes' <?= $_GET['just_partner'] == 'Yes' ? 'selected' : '' ?>>Yes</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4 col-xl-3">
                                                            <select name="partner[]" id="partner" class="multiselect_partner form-control" data-live-search="true" multiple>
                                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                                    <option <?= (in_array($row['id'], $partner) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>

                                                    <?php } ?>

                                                   
                                            <?php if($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') { ?>
                                                <div class="form-group col-md-4 col-xl-3">
                                                    <select name="isupsell" class="form-control">
                                                        <option value="">Select Upsell</option>
                                                        <option value="Yes" <?= (isset($_GET['isupsell']) && $_GET['isupsell'] == 'Yes') ? 'selected' : '' ?>>Yes</option>
                                                        <option value="No" <?= (isset($_GET['isupsell']) && $_GET['isupsell'] == 'No') ? 'selected' : '' ?>>No</option>
                                                    </select>
                                                </div>
                                            <?php } ?>
                                        
                                                    <div class="form-group col-md-4 col-xl-3">
                                                            <?php if ($_GET['partner']) { ?>

                                                                <select name="users[]" class="multiselect_user1 form-control" data-live-search="true" multiple>

                                                                    <?php $query = db_query('SELECT * FROM users WHERE team_id in ("' . implode('", "', $_GET['partner']) . '") and status="Active"  ORDER BY name ASC');
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (@in_array($row['id'], $users ?? []) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            <?php } else { ?>
                                                                <div id="users">
                                                                </div>
                                                            <?php } ?>
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
                                                        <div class="form-group col-md-4 col-xl-3">
                                                        <select name="stage[]" data-live-search="true" multiple class="form-control" id="multiselect">
                                                            <?php while ($stag = db_fetch_array($stageList)) { ?>
                                                                <option value="<?= $stag['stage_name'] ?>" <?= (in_array($stag['stage_name'], $stage) ? 'selected' : '') ?>><?= $stag['stage_name'] ?></option>
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
                                                                        <option <?= (@in_array($row['id'], $city) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= $row['city'] ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            <?php } else { ?>
                                                                <div id="cityD">
                                                                </div>
                                                            <?php } ?>
                                                        </div>


                                                        <div class="form-group col-md-4 col-xl-3">
                                                             
                                                                <select name="main_product[]" id="main_product" class="multiple_select_product form-control" multiple>
                                                                    <?php
                                                                    
                                                                    $resTP = db_query("SELECT * FROM tbl_main_product_opportunity where status = 1  order by id desc");
                                                                    while ($row = db_fetch_array($resTP)) { ?>
                                                                        <option <?= (@in_array($row['id'], $main_product ?? []) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                    <?php  } ?>

                                                                </select>
                                                               
                                                            </div>


                                                            <div class="form-group col-md-4 col-xl-3" id="sub_product_id">
                                                            <?php if ($_GET['main_product']) { ?>
                                                                <select name="sub_product_data[]" class="multiselect_sub_product form-control" data-live-search="true" multiple>

                                                                    <?php 
                                                                    // print_r($state);die;
                                                                    $query = db_query("SELECT * FROM tbl_product_opportunity where main_product_id  IN (" . implode(",", $main_product) . ")");
                                                                    
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (@in_array($row['id'], $sub_product_data) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= $row['product_name'] ?></option>
                                                                    <?php } ?>
                                                                </select>                                                                   

                                                            <?php } else { ?>
                                                                <div id="sub_product_id">
                                                                </div>
                                                               <?php } ?>
                                                            </div>


                                                    

                                                        <div class="form-group col-md-4 col-xl-2">
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

                            <!-- <div class="btn-group float-right">
                                <div class="d-flex  justify-content-end">

                                </div>
                            </div> -->

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

                            

                            <div class="table-responsive">

                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                           <tr>
                                           <th>S.No.</th>
                                                <th style="min-width: 200px">Reseller Name</th>
                                                <th>School Board</th>
                                                <th style="min-width: 200px">School Name</th>
                                                <th style="min-width: 300px">Product</th>
                                                <th>Quantity</th>
                                                <th>Grand Total</th>
                                                <th>Date of Submission</th>
											    <th>Status</th>
                                                <th>Qualified Status</th>
											    <th>Stage</th>
											    <th>Sub Stage</th>
											    <th>Demo Arranged</th>
											    <th>Demo Completed</th>
											    <th>Proposal Shared</th>
											    <th>Demo Login</th>
											    <th>DL + PS</th>
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
 <?php 
//  if (count($partner) > 1) {
//             $part = implode(',', $partner);
//         } else {
//             $part = $_GET['partner'][0];
//         } 

if (safe_count($campaign) > 1) {
        $campaign_arr = safe_implode('","', $campaign);
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
        language: {
            paginate: {
                previous: '<i class="fas fa-arrow-left"></i>',
                next: '<i class="fas fa-arrow-right"></i>'
            }
        },

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
            url: "get_renewal_leads_admin.php", // json datasource
            type: "post", // method  , by default get
            data: function(d) {
                d.d_from = "<?= $_GET['d_from'] ?>";
                    d.d_to = "<?= $_GET['d_to'] ?>";
                    d.d_type = "<?= $_GET['dtype'] ?>";
                    d.fin_year = "<?= $_GET['fin_year'] ?>";
                    d.upsell = "<?= $_GET['upsell'] ?>";
                    d.isupsell = "<?= $_GET['isupsell'] ?>";
                    d.just_partner = "<?= $_GET['just_partner'] ?>";
                    d.sub_product = '<?= json_encode($_GET['sub_product']) ?>';
                    d.stage = '<?= json_encode($_GET['stage']) ?>';
                    d.sub_stage = '<?= json_encode($_GET['sub_stage']) ?>';
                    d.status = '<?= json_encode($_GET['status']) ?>';
                    d.users = '<?= json_encode($_GET['users']) ?>';
                    d.quantity = '<?= json_encode($_GET['quantity']) ?>';
                    d.state = '<?= json_encode($_GET['state']) ?>';
                    d.city = '<?= json_encode($_GET['city']) ?>';
                    d.school_board = '<?= json_encode($_GET['school_board']) ?>';
                    d.partner = '<?= json_encode($_GET['partner']) ?>';
                    d.lead_status = '<?= json_encode($_GET['lead_status']) ?>';
                    d.source = '<?= json_encode($_GET['source']) ?>';
                    d.tag = '<?= json_encode($_GET['tag']) ?>';
                    d.product = '<?= json_encode($_GET['product']) ?>';
                    d.product_typeDS = '<?= json_encode($_GET['product_typeDS']) ?>';
                    d.productDS = '<?= json_encode($_GET['productDS']) ?>';
                    d.product_opp = '<?= json_encode($_GET['product_opp']) ?>';
                    d.product_opp_type = '<?= json_encode($_GET['product_opp_type']) ?>';
                    d.type = '<?= json_encode($_GET['type']) ?>';
                    d.main_product = '<?= json_encode($_GET['main_product']) ?>';
                    d.sub_product_data = '<?= json_encode($_GET['sub_product_data']) ?>';
                    d.upsellreport = '<?= json_encode($_GET['upsellreport']) ?>';
                    d.ownership = '<?= json_encode($_GET['ownership']) ?>';
            },
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
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
                 { data: 'r_name' },
                 {data:'school_board'},
                 { data: 'school_name' },
                 { data: 'product' },
                 { data: 'quantity' },
                 { data: 'grand_total' },
                   {data:'created_date'},
                   {data:'status'},
                   {data:'qualified_status'},
                  {data:'stage', className: 'text-nowrap'},
                  {data:'sub_stage'},
                  {data:'demo_arranged'},
                  {data:'demo_completed'},
                  {data:'proposal_shared'},
                  {data:'demo_login'},
                  {data:'demo_login+proposal_shared'},  
                  {data:'close_date'},
                   
                
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
        window.location = 'renewal_leads_admin.php';
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
        $('.dataTables_wrapper').height(wfheight - 325);
        $("#leads").tableHeadFixer();

    });

    $(document).ready(function() {

                $('#sdfexport').click(function() {
                    var dfrom = '<?= @$_GET['d_from'] ?>';
                    var dto = '<?= @$_GET['d_to'] ?>';
                    var dtype = '<?= @$_GET['date_type'] ?>';
                    var ltype = "Renewal";
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
                $('.multiselect_user1').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select User',
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

                $('.multiple_select_product').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });

                 $('.multiselect_sub_product').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Sub Product',
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



                 $('#main_product').on('change', function() {
                     var e = $(this).val();
                        if (e) {
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_sub_product_load.php',
                                data: 'product=' + e,
                                success: function(html) {
                                    //alert(html);
                                    $("#sub_product_id").html(html);
                                    console.log(html);
                                    // $('#cityD').html(html);
                                }
                            });
                        }

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
                            data: 'partner=' + partnerID,
                            success: function(html) {
                                //alert(html);
                                $('#users').html(html);
                            }
                        });
                    }
                });
            });

</script>