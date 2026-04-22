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

?>

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

                                    <small class="text-muted">Home >KRA</small>
                                    <h4 class="font-size-14 m-0 mt-1">VAR Team Structure</h4>
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
                                                        <option <?= (in_array($row['id'], $_GET['partner']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group" style="margin-top: 25px;">
                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                            </div>
                                       </div>

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
                                            <th>Authorization Level</th>
                                            <th colspan="4">As per Authorization</th>
                                            <th colspan="4">Current Status</th>
                                            <th colspan="4">Deficit</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>Sales</th>
                                            <th>ISS</th>
                                            <th>AE</th>
                                            <th>Installation</th>
                                            <th>Sales</th>
                                            <th>ISS</th>
                                            <th>AE</th>
                                            <th>Installation</th>
                                            <th>Sales</th>
                                            <th>ISS</th>
                                            <th>AE</th>
                                            <th>Installation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($_SESSION['sales_manager'] != 1) {
                                            $sql = db_query("select * from partners where reseller_id!='' $contd and status='Active'");
                                            } else {
                                            $sql = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active' and reseller_id!='' $contd");
                                            }

                                        //$sql = db_query("select * from partners where reseller_id!='' $contd and status='Active'");
                                        $i = 1;
                                        while ($data = db_fetch_array($sql)) {

                                            $category = getSingleresult("select category from partners where id=" . $data['id']);

                                            switch ($category) {
                                                case "Platinum":
                                                    $sales_team = 2;
                                                    $iss_team = 3;
                                                    $ae_team = 1;
                                                    $installation = 1;
                                                    break;
                                                case "Gold":
                                                    $sales_team = 2;
                                                    $iss_team = 2;
                                                    $ae_team = 1;
                                                    $installation = 1;
                                                    break;
                                                case "Silver":
                                                    $sales_team = 1;
                                                    $iss_team = 1;
                                                    $ae_team = 1;
                                                break;
                                                case "ROI Gold":
                                                    $sales_team = 1;
                                                    $iss_team = 1;
                                                    $ae_team = 1;
                                                break;
                                                case "ROI Silver":
                                                    $sales_team = 1;
                                                    $iss_team = 1;
                                                    //$ae_team = 1;
                                                break;
                                                 default:
                                                     $sales_team = 1;
                                                     $iss_team = 1;
                                                     $ae_team = 1;
                                            }

                                            $total_sales += $sales_team;
                                            $total_iss += $iss_team;
                                            $total_ae += $ae_team;
                                            $total_installation += $installation;
 
                                            $var = getSingleresult("select cdgs_target from partners where id=" . $data['id']);

                                            $actual_sal_count = getSingleresult("select count(id) from users where team_id='" . $data['id'] . "' and role ='SAL' and status='Active'");
                                            $actualSal_total += $actual_sal_count;

                                            $actual_iss_count = getSingleresult("select count(id) from users where team_id='" . $data['id'] . "' and role ='TC' and status='Active'");
                                            $actualISS_total += $actual_iss_count;

                                            $actual_ae_count = getSingleresult("select count(id) from users where team_id='" . $data['id'] . "' and role ='AE' and status='Active'");
                                            $actualAE_total += $actual_ae_count;

                                            $actual_installation_count = getSingleresult("select count(id) from users where team_id='" . $data['id'] . "' and role ='Installation' and status='Active'");
                                            $actualInst_total += $actual_installation_count;

                                            $deficit_sales = ($sales_team-$actual_sal_count >0)?$sales_team-$actual_sal_count:0;
                                            $deficitSal_total += $deficit_sales;

                                            $deficit_iss = ($iss_team-$actual_iss_count >0)?$iss_team-$actual_iss_count:0;
                                            $deficitISS_total += $deficit_iss;

                                            $deficit_ae = ($ae_team-$actual_ae_count >0)?$ae_team-$actual_ae_count:0;
                                            $deficitAE_total += $ae_team-$actual_ae_count;

                                            $deficit_installation = ($installation-$actual_installation_count >0)?$installation-$actual_installation_count:0;

                                            $deficitInst_total += $installation-$actual_installation_count;
                                        ?>
                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><?= $data[2] ?></td>
                                                <td><?= $category ?></td>
                                                <td><?= $sales_team ?></td>
                                                <td><?= $iss_team ?></td>
                                                <td><?= $ae_team ?></td>
                                                <td><?= $installation ?></td>
                                                <td><?= $actual_sal_count ?></td>
                                                <td><?= $actual_iss_count ?></td>
                                                <td><?= $actual_ae_count ?></td>
                                                <td><?= $actual_installation_count ?></td>
                                                <td><?= $deficit_sales ?></td>
                                                <td><?= $deficit_iss?></td>
                                                <td><?= $deficit_ae?></td>
                                                <td><?= $deficit_installation?></td>
                                            </tr>
                                        <?php $i++;
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                                    <tr>
                                                        
                                                        <th colspan="3">Total</th>
                                                        <th><?= $total_sales?></th>
                                                        <th><?= $total_iss?></th>
                                                        <th><?= $total_ae?></th>
                                                        <th><?= $total_installation ?></th>
                                                        <th><?= $actualSal_total?></th>
                                                        <th><?= $actualISS_total?></th>
                                                        <th><?= $actualAE_total?></th>
                                                        <th><?= $actualInst_total?></th>
                                                        <th><?= $deficitSal_total?></th>
                                                        <th><?= $deficitISS_total?></th>
                                                        <th><?= $deficitAE_total?></th>
                                                        <th><?= $deficitInst_total?></th>
                                                    </tr>
                                                </tfoot>
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
                window.location = 'admin_kra_team.php';
            }
        </script>
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#example23").tableHeadFixer();

            });
        </script>