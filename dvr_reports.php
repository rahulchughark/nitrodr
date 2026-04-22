<?php include('includes/header.php');
admin_page();

if ($_GET['d_from'] && $_GET['d_to']) {
    $dat1 = $_GET['d_from'];
    $dat2 = $_GET['d_to'];
} else {
    $dat1 = date('Y-m-d');
    $dat2 = date('Y-m-d');
}

// if ($_REQUEST['product']) {
//     $p_dat .= " and p.product_id='" . $_REQUEST['product'] . "'";
//     $r_dat .= " and raw_leads.product_id='" . $_REQUEST['product'] . "'";
// }
// if ($_REQUEST['product_type']) {
//     $p_dat .= " and p.product_type_id='" . $_REQUEST['product_type'] . "'";
//     $r_dat .= " and raw_leads.product_type_id='" . $_REQUEST['product_type'] . "'";
// }

if ($_REQUEST['partner']) {
    $contd .= ' and id in ("' . stripslashes(implode('","',$_REQUEST["partner"])) . '")';
}

// if ($_REQUEST['ark_users']) {
//     $dat .= " and id='" . $_REQUEST['ark_users'] . "'";
//     //$u_dat .=" and id='". $requestData['ark_users'] ."' ";
// }

?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >VAR Activity Report</small>
                                    <h4 class="font-size-14 m-0 mt-1">VAR Activity Report</h4>
                                </div>
                            </div>


                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                            <button type="button" class="btn btn-xs btn-light ml-1  " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">

                                <div class="dropdown-menu1 dropdown-menu-right filter_wrap filter_wrap_2" id="filter-container" role="menu">
                                        <form method="get" name="search" class="form-horizontal" role="form">


                                            <?php if ($_SESSION['sales_manager'] != 1) {
                                                $res = db_query("select * from partners where status='Active'");
                                            } else {
                                                $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                            }
                                            ?>
                                            <div class="form-group">
                                                <select name="partner[]" id="partner" class="multiselect_partner form-control" data-live-search="true" multiple>
                                                  
                                                    <?php while ($row = db_fetch_array($res)) { ?>
                                                        <option <?= (@in_array($row['id'],$_GET['partner']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <!-- <div class="form-group">
                                                <select name="product" class="product_data form-control">
                                                    <option value="">Select Product</option>
                                                    <?php $query = selectProduct('tbl_product');
                                                    while ($row = db_fetch_array($query)) { ?>
                                                        <option <?= (($_GET['product'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <?php if ($_GET['product']) { ?>
                                                    <select name="product_type" id="product_type" class="form-control">
                                                        <option value="">Select Product Type</option>
                                                        <?php $query = selectProductType('tbl_product_pivot', $_GET['product']);
                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option <?= (($_GET['product_type'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_type']) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } else { ?>
                                                    <select name="product_type" id="product_type" class="form-control">
                                                        <option value="">Select Product Type</option>
                                                    </select>
                                                <?php } ?>

                                            </div> -->

                                            <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date1">
                                                    <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                    <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="example_dv" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Reseller Organization</th>
                                            <th># Data Qualified</th>
                                            <th># LC Qualified</th>
                                            <!-- <th># Converted to LC</th> -->
                                            <th># BD Qualified</th>
                                            <th># Incoming Qualified</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if ($_SESSION['sales_manager'] != 1) {
                                            $sql_n = db_query("select * from partners where reseller_id!='' $contd and status='Active' order by partners.id desc");
                                        } else {
                                            $sql_n = db_query("select * from partners where reseller_id!='' $contd and status='Active' and id in (" . $_SESSION['access'] . ") order by partners.id desc");
                                        }
                                        $j = 1;

                                        $re_log = db_query("select distinct(o.id) from lead_modify_log as lm left join orders as o on lm.lead_id=o.id where o.license_type='Commercial' and o.dvr_flag=0 and o.iss is NULL and lm.type='Re-log Status' and lm.previous_name='Expired' and lm.modify_name='Qualified'");
                                        while ($re_log_arr = db_fetch_array($re_log)) {
                                            $ids[] = $re_log_arr['id'];
                                        }
                                        if(!empty($ids))
                                        {
                                            $idds = @implode(',', $ids);
                                        }else{
                                            $idds = "''";
                                        }
                                        
                                        //print_r($idds);
                                        $iss_users = db_query("select c.name from users left join callers as c on users.id=c.user_id where users.user_type='CLR' and users.role='ISS' and users.status='Active' ");

                                        while ($uid = db_fetch_array($iss_users)) {
                                            $caller_name[] = $uid['name'];
                                        }
                                        
                                        $iss_names = @implode("','", $caller_name);

                                        while ($data_n = db_fetch_array($sql_n)) {

                                          $converted_data = dvrReportsConvertedData($data_n['id'],$dat1,$dat2,$iss_names,$dat);
                                          //db_query("select * from (select count(DISTINCT(o.id)),o.id from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2) and  (date(lm.created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') and o.team_id='" . $data_n['id'] . "' and o.license_type='Commercial' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' $dat GROUP BY o.id) t1 INNER JOIN (select count(DISTINCT(o.id)),o.id from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2) and  (date(lm.created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') and o.license_type='Commercial' and o.team_id='" . $data_n['id'] . "' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name in ('" . $iss_names . "') $dat GROUP BY o.id) t2 on t1.id=t2.id");
                                          $total=0;
                                          while ($row = db_fetch_array($converted_data)) {
                                          $total += $row['count(DISTINCT(o.id))'];
                                        }
                                        ?>

                                            <tr>
                                                <td><?= $j ?></td>
                                                <td><?= $data_n['2'] ?></td>
                                                <td><?= dvrReportsDataQualified($data_n['id'],$dat1,$dat2,$idds); ?></td>

                                                <td><?= dvrReportsLCQualified($data_n['id'],$dat1,$dat2,$idds); ?></td>

                                                <!-- <td><?= $total?></td> -->

                                                <td><?= dvrReportsBDQualified($data_n['id'],$dat1,$dat2,$idds); ?></td>

                                                <td><?= dvrReportsIncomingQualified($data_n['id'],$dat1,$dat2,$idds);  ?></td>

                                            </tr>
                                        <?php $j++; } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->

    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <div id="myModal" class="modal fade" role="dialog">


    </div>
    <?php include('includes/footer.php') ?>
    <script>
        $(document).ready(function() {
            var table = $('#example_dv').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                ],
                lengthMenu: [
                    [10, 25, 50, 100, 500, 1000],
                    ['10', '25', '50', '100', '500', '1000']
                ],
                "displayLength": 10,
            });
        });

        $(document).ready(function() {
            $('.multiselect_partner').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Partner',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });
        });
        // $(document).ready(function() {
        //     $('.product_data').on('change', function() {
        //         var productID = $(this).val();
        //         if (productID) {
        //             $.ajax({
        //                 type: 'POST',
        //                 url: 'ajaxProductTypeAdmin.php',
        //                 data: 'product=' + productID,
        //                 success: function(html) {
        //                     $('#product_type').html(html);

        //                 },
        //             });
        //         }
        //     });
        // });


        $(function() {
            $('#datepicker-close-date1').datepicker({
                format: 'yyyy-mm-dd',
                //startDate: '-3d',
                autoclose: !0
            });
        });


        function clear_search() {
            window.location = 'dvr_reports.php';
        }
    </script>