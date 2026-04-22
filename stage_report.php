<?php include('includes/header.php');
admin_page();
if ($_GET['f_date'] && $_GET['t_date']) {
    $f_dat = $_GET['f_date'];
    $t_dat = $_GET['t_date'];
} else {
    $t_dat = date('Y-m-d');
    $f_dat = date('Y-m-d', strtotime("- 7 days"));
}

if ($_REQUEST['product']) {
    $p_dat .= " and tp.product_id='" . $_REQUEST['product'] . "'";
}
if ($_REQUEST['product_type']) {
    $p_dat .= " and tp.product_type_id='" . $_REQUEST['product_type'] . "'";
}
if ($_REQUEST['partner']) {
    $contd .= " and id='" . $_REQUEST['partner'] . "'";
}
?>
<style>
    .table th {
        font-size: 12px;
    }

    .table td,
    .table th {
        padding: 4px;
    }
	.report_table thead th{
		    border-bottom: 0px solid #e9ecef;
	}
</style>
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

                                    <small class="text-muted">Home >VAR Stage Report</small>
                                    <h4 class="font-size-14 m-0 mt-1">VAR Stage Report</h4>
                                </div>
                            </div>
<div class="clearfix"></div>
                            <div class="btn-group float-right" role="group" style="margin-top:12px;">

                                <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>

                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                                        <form method="get" name="search">

                                            <?php if ($_SESSION['sales_manager'] != 1) {
                                                $res = db_query("select * from partners where status='Active'");
                                            } else {
                                                $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                            }
                                            ?>
                                            <div class="form-group ">
                                                <select name="partner" id="partner" class="form-control">
                                                    <option value="">Select Partner</option>
                                                    <?php while ($row = db_fetch_array($res)) { ?>
                                                        <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group ">
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
                                                    <div id="product_type">
                                                        <select name="product_type" class="form-control">
                                                            <option value="">Select Product Type</option>
                                                        </select>
                                                    </div>
                                                <?php } ?>

                                            </div>
                                            <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                    <input type="text" value="<?php echo @$_GET['f_date'] ?>" class="form-control" id="f_date" name="f_date" placeholder="Date From" />

                                                    <input type="text" value="<?php echo @$_GET['t_date'] ?>" class="form-control" id="t_date" name="t_date" placeholder="Date To" />
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>

                                        </form>
                                    </div>
                                </div>

                            </div>

                           
                                <table id="example23" class="table display nowrap table-striped report_table" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>

                                            <th style="width:200px;">Name</th>
                                            <th colspan="2" style="width:110px;">Post Profiling</th>
                                            <th colspan="2" style="width:110px;">Prospecting
                                            </th>
                                            <th colspan="2" style="width:110px;">License Compliance</th>
                                            <th colspan="2" style="width:110px;">Customer Connect</th>
                                            <th colspan="2" style="width:110px;">Quote</th>
                                            <th colspan="2" style="width:110px;">Follow-Up</th>
                                            <th colspan="2" style="width:110px;">Commit</th>
                                            <th colspan="2" style="width:110px;">EU PO Issued</th>
                                            <th colspan="2" style="width:110px;">B + B</th>
                                            <th colspan="2" style="width:110px;">Total</th>

                                        </tr>
                                        <tr>
                                            <th style="width:200px;"></th>
                                            <th>Account</th>
                                            <th>License</th>
                                            <th>Account</th>
                                            <th>License</th>
                                            <th>Account</th>
                                            <th>License</th>
                                            <th>Account</th>
                                            <th>License</th>
                                            <th>Account</th>
                                            <th>License</th>
                                            <th>Account</th>
                                            <th>License</th>
                                            <th>Account</th>
                                            <th>License</th>
                                            <th>Account</th>
                                            <th>License</th>
                                            <th>Account</th>
                                            <th>License</th>
                                            <th>Account</th>
                                            <th>License</th>
                                            <!-- <th>Account</th>
                                            <th>License</th> -->
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if ($_SESSION['sales_manager'] != 1) {
                                            $sql = db_query("select * from partners where reseller_id!='' and status='Active' $contd order by partners.id desc");
                                        } else {
                                            $sql = db_query("select * from partners where reseller_id!='' and status='Active' and id in (" . $_SESSION['access'] . ") $contd order by partners.id desc");
                                        }

                                        while ($data = db_fetch_array($sql)) {

                                            $post_a = getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Post Profiling' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $post_l = getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Post Profiling' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $prospecting_a = getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Prospecting' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $prospecting_l = getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Prospecting' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $legal_a = getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='License Compliance' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $legal_l = getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='License Compliance' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $customer_a = getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Customer Connect' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $customer_l = getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Customer Connect' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $quote_a = getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Quote' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $quote_l = getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Quote' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $follow_a = getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Follow-Up' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $follow_l = getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Follow-Up' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $commit_a = getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Commit' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $commit_l = getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Commit' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $eupo_a = getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='EU PO Issued' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $eupo_l = getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='EU PO Issued' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $booking_a = getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Booking' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $booking_l = getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Booking' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $oem_a = getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='OEM Billing' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            $oem_l = getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='OEM Billing' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat);

                                            // $closed_lost_a = getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Closed Lost' and o.license_type='Commercial' and date(o.partner_close_date)>='".$f_dat."' and date(o.partner_close_date)<='".$t_dat."' ". $p_dat);

                                            // $closed_lost_l = getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.team_id='" . $data['id'] . "' and o.stage='Closed Lost' and o.license_type='Commercial' and date(o.partner_close_date)>='".$f_dat."' and date(o.partner_close_date)<='".$t_dat."' ". $p_dat);

                                            $total_a = $post_a + $prospecting_a + $legal_a + $customer_a + $quote_a + $follow_a + $commit_a + $eupo_a + $booking_a + $oem_a;
                                            $total_l = $post_l + $prospecting_l + $legal_l + $customer_l + $quote_l + $follow_l + $commit_l + $eupo_l + $booking_l + $oem_l;
                                            $grand_a += $total_a;
                                            $grand_l += $total_l;
                                        ?>

                                            <tr>

                                                <td style="width:200px;"><?= $data['2'] ?></td>
                                                <td><?= $post_a ?></td>
                                                <td><?= $post_l ?></td>
                                                <td><?= $prospecting_a ?></td>
                                                <td><?= $prospecting_l ?></td>
                                                <td><?= $legal_a ?></td>
                                                <td><?= $legal_l ?></td>
                                                <td><?= $customer_a ?></td>
                                                <td><?= $customer_l ?></td>
                                                <td><?= $quote_a ?></td>
                                                <td><?= $quote_l ?></td>
                                                <td><?= $follow_a ?></td>
                                                <td><?= $follow_l ?></td>
                                                <td><?= $commit_a ?></td>
                                                <td><?= $commit_l ?></td>
                                                <td><?= $eupo_a ?></td>
                                                <td><?= $eupo_l ?></td>
                                                <td><?= $booking_a + $oem_a ?></td>
                                                <td><?= $booking_l + $oem_l ?></td>
                                                <!-- <td><?= $oem_a ?></td>
                                            <td><?= $oem_l ?></td> -->
                                                <!-- <td><?= $closed_lost_a ?></td>
                                            <td><?= $closed_lost_l ?></td> -->
                                                <td><?= $total_a ?></td>
                                                <td><?= $total_l ?></td>

                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>


<div class="clearfix"></div>
                                <table id="" class="table display nowrap table-striped"  data-mobile-responsive="true" cellspacing="0" width="100%" style="width:100%;">
                                    <tbody>
                                        <tr style="text-align:center;">
                                            <td style="width:160px;">Grand Total</td>
                                            <td style="width:75px;"><?= getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='Post Profiling' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat); ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='Post Profiling' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat); ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='Prospecting' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat) ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='Prospecting' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat); ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='License Compliance' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat) ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='License Compliance' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat); ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='Customer Connect' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat) ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='Customer Connect' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat); ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='Quote' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat) ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='Quote' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat) ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='Follow-Up' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat) ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='Follow-Up' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat) ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='Commit' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat) ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='Commit' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat) ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select count(distinct(o.id)) as leads from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='EU PO Issued' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat) ?></td>
                                            <td style="width:75px;"><?= getSingleresult("select IFNULL(sum(distinct(o.quantity)),0) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where o.stage='EU PO Issued' and o.license_type='Commercial' and date(o.partner_close_date)>='" . $f_dat . "' and date(o.partner_close_date)<='" . $t_dat . "' " . $p_dat) ?></td>
                                            <td style="width:75px;"><?= $booking_a + $oem_a ?></td>
                                            <td style="width:75px;"><?= $booking_l + $oem_l ?></td>
                                            <th style="width:75px;"><?= $grand_a ?></th>
                                            <th style="width:75px;"><?= $grand_l ?></th>
                                        </tr>
                                    </tbody>
                                </table>


                           

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <div id="myModal" class="modal fade" role="dialog">


        </div>
        <?php include('includes/footer.php') ?>
        <script>
            // $(document).ready(function() {
            //     $('#myTable').DataTable();
            //     $(document).ready(function() {
            //         var table = $('#example').DataTable({
            //             "columnDefs": [{
            //                 "visible": false,
            //                 "targets": 2
            //             }],
            //             "order": [
            //                 [2, 'desc']
            //             ],
            //             buttons: [
            //                 'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            //             ],

            //             "pageLength": 25,
            //             "drawCallback": function(settings) {
            //                 var api = this.api();
            //                 var rows = api.rows({
            //                     page: 'current'
            //                 }).nodes();
            //                 var last = null;
            //                 api.column(2, {
            //                     page: 'current'
            //                 }).data().each(function(group, i) {
            //                     if (last !== group) {
            //                         $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
            //                         last = group;
            //                     }
            //                 });
            //             }
            //         });
            //         // Order by the grouping
            //         $('#example tbody').on('click', 'tr.group', function() {
            //             var currentOrder = table.order()[0];
            //             if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
            //                 table.order([2, 'desc']).draw();
            //             } else {
            //                 table.order([2, 'asc']).draw();
            //             }
            //         });
            //     });
            // });
            $('#example23').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                ]
            });

            function change_goal(a) {
                $.ajax({
                    type: 'POST',
                    url: 'get_goal.php',
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
            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            function clear_search() {
                window.location = 'stage_report.php';
            }
        </script>
        <script>
            jQuery("#search_toogle").click(function() {
                jQuery(".search_form").toggle("fast");
            });

            var wfheight = $(window).height();

            $('.fixed-table-body').height(wfheight - 440);



            $('.fixed-table-body').slimScroll({
                color: '#00f',
                size: '10px',
                height: 'auto',


            });
			
			 $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 330);
                $("#example23").tableHeadFixer();

            });
        </script>