<?php include('includes/header.php');
admin_page();

if ($_REQUEST['partner']) {
    $_REQUEST['partner']=  implode(',', $_REQUEST['partner']);
    $contd = " and id in (" . $_REQUEST['partner'].")";
}
if (!$_GET['date_from']) {
    $_GET['date_from'] = date('Y-m-01');
}
if (!$_GET['date_to']) {
    $_GET['date_to'] = date('Y-m-t');
}

$year  =  date('Y');


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
                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >KRA</small>
                                    <h4 class="font-size-14 m-0 mt-1">VAR Organization KRA</h4>
                                </div>
                            </div>
                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                            <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                <div class="dropdown dropdown-lg">

                                <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">


                                        <form method="get" name="search">
                                        <div class="row">
                                            <?php 
                                             if ($_SESSION['sales_manager'] != 1) {
                                                $res = db_query("select * from partners where status='Active'");
                                                } else {
                                                    $res = db_query("select * from partners where status='Active' and id in (" . $_SESSION['access'] . ")");
                                               
                                                }

                                            ?>
                                                <div class="form-group col-md-4">
                                                <label class="control-label">Partner</label>
                                                <select name="partner[]" id="partner" class="multiselect_partner1 form-control" multiple data-live-search="true">
                                                    <?php 
                                                    while ($row = db_fetch_array($res)) { ?>
                                                        <option <?= (in_array($row['id'],$_GET['partner']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                            <label class="control-label">KRA Parameters In</label>
                                                <select name="kra_parameter" class="form-control">
                                                    <option value="">Select</option>
                                                    <option <?=(($_GET['kra_parameter']=='percent_achieved')?'selected':'') ?> value="percent_achieved">%Achieved</option>
                                                    <option <?=(($_GET['kra_parameter']=='achieved')?'selected':'') ?> value="achieved">#Achieved</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                            <label class="control-label">Date Range:</label>
                                                <div class="input-daterange input-group" id="datepicker_close_date">
                                                
                                                    <input type="text" value="<?php echo @$_GET['date_from'] ?>" class="form-control" id="date_from" name="date_from" placeholder="Date From" />

                                                    <input type="text" value="<?php echo @$_GET['date_to'] ?>" class="form-control" id="date_to" name="date_to" placeholder="Date To" />
                                                </div>
                                            </div>
                                        </div>

                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>


                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S No.</th>
                                            <th>VAR Name</th>
                                            <th colspan="7">KRA Parameters</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Seats Closed</th>
                                            <th>Account for Lc calling<br>with mailer validation</th>
                                            <th>New Visit</th>
                                            <th>Total Visit</th>
                                            <th>Total Log a calls</th>
                                            <th>Account for Lc calling<br>with profile remark<br>and Usage confirmation</th>
                                            <th>New Account Call</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                         if ($_SESSION['sales_manager'] != 1) {
                                            $sql = db_query("select * from partners where reseller_id!='' $contd and status='Active'");
                                            } else {
                                            $sql = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active' and reseller_id!='' $contd");
                                            }

                                        
                                        $i = 1;
                                        while ($data = db_fetch_array($sql)) {

                                            $category = getSingleresult("select category from partners where id=" . $data['id']);

                                            switch ($category) {
                                                case "Platinum":
                                                    $sales_team = 2;
                                                    break;
                                                case "Gold":
                                                    $sales_team = 2;
                                                    break;
                                                case "Silver":
                                                    $sales_team = 1;
                                                break;
                                                default:
                                                    $sales_team = 1;
                                            }

                                            $cat_target = db_query("select * from admin_kra where cdgs_category='".$category."' and year=".$year);
                                            $cat_arr = db_fetch_array($cat_target);

                                            //$var = getSingleresult("select cdgs_target from partners where id=" . $data['id']);

                                            $users1 = db_query("select id,role from users where team_id='" . $data['id'] . "' and status='Active' ");
                                            $ids = array();

                                            while ($uid = db_fetch_array($users1)) {
                                                $ids[] = $uid['id'];
                                            }
                                            $user_ids = implode("','", $ids);

                                            $achieved = LCCallingEmailer_KRAadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids);
                                            
                                            // newDR_KRAadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + newDR_iss_KRAadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                            $kra1_var = $cat_arr['new_dr'];
                                            $achieve_percent_kra1 = $achieved / $kra1_var * 100;

                                            $achieve_percent_kra1 = ($achieve_percent_kra1!=NAN) ? round($achieve_percent_kra1) . '%' : '0%';

                                            $log_call = logCall_leadadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + logCall_lapsedadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + logCall_rawadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                            $dvr = dvr_KRAadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                            $converted_dvr = convertedDRVadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                            $achieved2 = $log_call + $dvr + $converted_dvr;

                                            $kra2_var = $cat_arr['monthly_account'];
                                            $achieve_percent_kra2 = $achieved2 / $kra2_var * 100;
                                            $achieve_percent_kra2 = $achieve_percent_kra2 ? round($achieve_percent_kra2) . '%' : '0%';
                                           
                                            $achieved3 = LCCalling_KRAadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids);
                                             //rawLeadsadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                            $kra3_var = $cat_arr['raw_data'];
                                            $achieve_percent_kra3 = $achieved3 / $kra3_var * 100;
                                          
                                            $achieve_percent_kra3 = $achieve_percent_kra3 ? round($achieve_percent_kra3) . '%' : '0%';

                                            $achieved4 = logISS_rawadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids)+logISS_leadadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids)+logISS_lapsedadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                            $kra4_var = $cat_arr['total_log'];
                                            $achieve_percent_kra4 = $achieved4 / $kra4_var * 100;
                                            
                                            $achieve_percent_kra4 = $achieve_percent_kra4 ? round($achieve_percent_kra4) . '%' : '0%';

                                            $achieved5 = sales_targetadmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + iss_sales_targetAdmin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids);
                                            
                                            $achieve_percent_kra5 = $achieved5 / $cat_arr['sales_target'] * 100;
                                            
                                            $achieve_percent_kra5 = $achieve_percent_kra5 ? round($achieve_percent_kra5) . '%' : '0%';

                                            $total_visit = visitRaw_SALES($data['id'], $user_ids, $_GET['date_from'], $_GET['date_to']) + visitLead_SALES($data['id'], $user_ids, $_GET['date_from'], $_GET['date_to']) + visitLapsed_SALES($data['id'], $user_ids, $_GET['date_from'], $_GET['date_to']) + $dvr + $converted_dvr;

                                            $total_visit_percent = $total_visit / 300 * 100;
                                            
                                            $total_visit_percent = $total_visit_percent ? round($total_visit_percent) . '%' : '0%';

                                            $achieved6 = freshCall_lead_admin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + freshCall_DVR_admin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + freshCall_lapsed_admin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + freshCall_raw_admin($data['id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                        $achieve_percent_kra6 = $achieved6 / $cat_arr['new_account'] * 100;
                                        $achieve_percent_kra6 = round($achieve_percent_kra6);

                                        ?>
                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><?= $data[2] ?></td>

                                                <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $achieve_percent_kra5 : $achieved5) ?></td>

                                                <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $achieve_percent_kra1 : $achieved) ?></td>

                                                <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $achieve_percent_kra2 : $achieved2) ?></td>

                                                <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $total_visit_percent : $total_visit) ?></td>

                                                <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $achieve_percent_kra4 : $achieved4) ?></td>

                                                <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $achieve_percent_kra3 : $achieved3) ?></td>

                                                <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $achieve_percent_kra6 : $achieved6) ?></td>
                                            </tr>
                                        <?php $i++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('includes/footer.php') ?>

        <script>
            $('#example23').DataTable({
                "displayLength": 15,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                ],
                lengthMenu: [
                    [15, 25, 50, 100, 500, 1000],
                    ['15', '25', '50', '100', '500', '1000']
                ],
                // "processing": true,
                // "serverSide": true,
                // columnDefs: [{
                //     orderable: false,
                //     targets: 0
                // }],
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.multiselect_partner1').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
            });

            $(function() {
                $('#datepicker_close_date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            function clear_search() {
                window.location = 'admin_kra.php';
            }
        </script>
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#example23").tableHeadFixer();

            });
        </script>