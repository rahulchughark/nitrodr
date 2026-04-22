<?php include('includes/header.php');

$f_dat = mysqli_real_escape_string($GLOBALS['dbcon'], htmlentities($_GET['f_date']));
$t_dat = mysqli_real_escape_string($GLOBALS['dbcon'], htmlentities($_GET['t_date']));


if ($_GET['f_date'] && $_GET['t_date']) {

    $contd .= " and ((i.date1 BETWEEN '$f_dat' and '$t_dat') || (i.date2 BETWEEN '$f_dat' and '$t_dat')|| (i.date3 BETWEEN '$f_dat' and '$t_dat')|| (i.date4 BETWEEN '$f_dat' and '$t_dat')|| (i.date5 BETWEEN '$f_dat' and '$t_dat')|| (i.date6 BETWEEN '$f_dat' and '$t_dat'))";
}
if ($_GET['payment_partner'] == 'Clear') {
    $contd .= " and p.var_status=1";
} else if ($_GET['payment_partner'] == 'Pending') {
    $contd .= " and p.var_status=0";
} else if ($_GET['payment_ark'] == 'Clear') {
    $contd .= " and p.ark_status=1";
} else if ($_GET['payment_ark'] == 'Pending') {
    $contd .= " and p.ark_status=0";
}

?>
<style>
    #blur_data {

        color: transparent;
        text-shadow: 0 0 8px #000;
    }

    #var_color {
        color: darkblue;
        font-weight: bold;
    }

    #ark_color {
        color: darkgreen;
        font-weight: bold;
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
                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >EMI Tracker</small>
                                    <h4 class="font-size-14 m-0 mt-1">EMI Tracker</h4>
                                </div>
                            </div>


                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                                        <form method="get" name="search">

                                            <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                    <input type="text" class="form-control" value="<?php echo @$_GET['f_date'] ?>" id="f_date" name="f_date" placeholder="Date" />
                                                    <input type="text" class="form-control" value="<?php echo @$_GET['t_date'] ?>" id="t_date" name="t_date" placeholder="Date" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <select name="payment_partner" class="form-control">
                                                    <option value="">Payment to Partner</option>
                                                    <option <?= (($_GET['payment_partner'] == 'Clear') ? 'selected' : '') ?> value="Clear">Clear</option>
                                                    <option <?= (($_GET['payment_partner'] == 'Pending') ? 'selected' : '') ?> value="Pending">Pending</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select name="payment_ark" class="form-control">
                                                    <option value="">Payment to ARK</option>
                                                    <option <?= (($_GET['payment_ark'] == 'Clear') ? 'selected' : '') ?> value="Clear">Clear</option>
                                                    <option <?= (($_GET['payment_ark'] == 'Pending') ? 'selected' : '') ?> value="Pending">Pending</option>
                                                </select>
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
                                            <th>Account Name</th>
                                            <th>Quantity</th>
                                            <th>Closed Date</th>
                                            <th>1st Installment Date</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>2nd Installment Date</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>3rd Installment Date</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>4th Installment Date</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>5th Installment Date</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>6th Installment Date</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>Order Price</th>
                                            <th>Payment Received to VAR</th>
                                            <th>Payment Received to ARK</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $sql = db_query("select o.id as lead_id,o.company_name,o.quantity,o.partner_close_date,i.* from orders as o left join installment_details as i on o.id=i.pid left join payment_status as p on o.id=p.lead_id where i.pid=o.id and o.team_id=" . $_SESSION['team_id'] . " and o.license_type='Commercial' $contd order by i.id desc");

                                        $i = 1;
                                        while ($data = db_fetch_array($sql)) {

                                            $total_var = db_query("select payment_var1,payment_var2,payment_var3,payment_var4,payment_var5,payment_var6 from payment_status where installment_id=" . $data['id']);
                                            foreach ($total_var as $value) {
                                                $totalVarAmount = array_sum($value);
                                                $sum_var += $totalVarAmount;
                                                //print_r($totalVarAmount);
                                            }
                                            $total_ark = db_query("select payment_ark1,payment_ark2,payment_ark3,payment_ark4,payment_ark5,payment_ark6 from payment_status where installment_id=" . $data['id'] . " and lead_id=" . $data['pid']);
                                            foreach ($total_ark as $value) {
                                                $totalARKAmount = array_sum($value);
                                                $sum_ark += $totalARKAmount;
                                                //print_r($totalARKAmount);
                                            }

                                            $select_query = db_query("select * from payment_status where installment_id=" . $data['id']);
                                            $select_status = db_fetch_array($select_query);


                                        ?>

                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><?= $data['company_name'] ?></td>
                                                <td><?= $data['quantity'] ?></td>
                                                <td><?= date('d-m-Y', strtotime($data['partner_close_date'])) ?></td>

                                                <td><?= $data['date1'] ? date('d-m-Y', strtotime($data['date1'])) : 'N/A' ?></td>
                                                <td><?= $data['instalment1'] ?>
                                                    <br>
                                                    <?php if ($data['instalment1']) {
                                                        if ($select_status['payment_ark1'] != 0) { ?>

                                                            <a title="Payment Cleared to VAR" name="payment_var"><span id="blur_data" style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a title="Payment Transfer to ARK" name="payment_ark"><span id="blur_data" style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a></span>

                                                        <?php } else if ($select_status['payment_var1'] != 0) { ?>

                                                            <a title="Payment Cleared to VAR" name="payment_var"><span id="blur_data" style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a href="javascript:void(0)" title="Payment Transfer to ARK" onClick="payment_ark(<?= $data['id'] ?>,<?= $data['pid'] ?>,'payment_ark1',<?= $data['instalment1'] ?>)" name="payment_ark"><span style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a>

                                                        <?php  } else { ?>

                                                            <a href="javascript:void(0)" title="Payment Cleared to VAR" onClick="payment_var(<?= $data['id'] ?>,<?= $data['pid'] ?>,'payment_var1',<?= $data['instalment1'] ?>)" name="payment_var"><span style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a href="javascript:void(0)" title="Payment Transfer to ARK" onClick="payment_ark(<?= $data['id'] ?>,<?= $data['pid'] ?>,'payment_ark1',<?= $data['instalment1'] ?>)" name="payment_ark"><span style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a>

                                                    <?php }
                                                    } ?>
                                                </td>
                                                <?php

                                                if ($select_status['payment_var1'] != 0) { ?>
                                                    <td id="var_color">Cleared to VAR</td>
                                                <?php } else if ($select_status['payment_ark1'] != 0) { ?>
                                                    <td id="ark_color">Transfer to ARK</td>
                                                <?php } else { ?>
                                                    <td></td>
                                                <?php } ?>


                                                <td><?= $data['date2'] ? date('d-m-Y', strtotime($data['date2'])) : 'N/A' ?></td>
                                                <td><?= $data['instalment2'] ?>
                                                    <br>
                                                    <?php if ($data['instalment2']) {
                                                        if ($select_status['payment_ark2'] != 0) { ?>
                                                            <a title="Payment Cleared to VAR" name="payment_var"><span id="blur_data" style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a title="Payment Transfer to ARK" name="payment_ark"><span id="blur_data" style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a></span>

                                                        <?php } else if ($select_status['payment_var2'] != 0) { ?>
                                                            <a title="Payment Cleared to VAR" name="payment_var"><span id="blur_data" style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a href="javascript:void(0)" title="Payment Transfer to ARK" onClick="payment_ark(<?= $data['id'] ?>,<?= $data['pid'] ?>,'payment_ark2',<?= $data['instalment2'] ?>)" name="payment_ark2"><span style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a>
                                                        <?php  } else { ?>

                                                            <a href="javascript:void(0)" title="Payment Cleared to VAR" onClick="payment_var(<?= $data['id'] ?>,<?= $data['pid'] ?>,'payment_var2',<?= $data['instalment2'] ?>)" name="payment_var2"><span style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a href="javascript:void(0)" title="Payment Transfer to ARK" onClick="payment_ark(<?= $data['id'] ?>,<?= $data['pid'] ?>,'payment_ark2',<?= $data['instalment2'] ?>)" name="payment_ark2"><span style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a>
                                                    <?php  }
                                                    } ?>

                                                </td>

                                                <?php
                                                if ($select_status['payment_var2'] != 0) { ?>
                                                    <td id="var_color">Cleared to VAR</td>
                                                <?php } else if ($select_status['payment_ark2'] != 0) { ?>
                                                    <td id="ark_color">Transfer to ARK</td>
                                                <?php } else { ?>
                                                    <td></td>
                                                <?php } ?>

                                                <td><?= $data['date3'] ? date('d-m-Y', strtotime($data['date3'])) : 'N/A' ?></td>
                                                <td><?= $data['instalment3'] ?>
                                                    <br>
                                                    <?php if ($data['instalment3']) {
                                                        if ($select_status['payment_ark3'] != 0) { ?>
                                                            <a title="Payment Cleared to VAR" name="payment_var"><span id="blur_data" style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a title="Payment Transfer to ARK" name="payment_ark"><span id="blur_data" style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a></span>

                                                        <?php } else if ($select_status['payment_var3'] != 0) { ?>
                                                            <a title="Payment Cleared to VAR" name="payment_var"><span id="blur_data" style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a href="javascript:void(0)" title="Payment Transfer to ARK" onClick="payment_ark(<?= $data['id'] ?>,<?= $data['pid'] ?>,'payment_ark3',<?= $data['instalment3'] ?>)" name="payment_ark3"><span style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a>
                                                        <?php  } else { ?>

                                                            <a href="javascript:void(0)" title="Payment Cleared to VAR" onClick="payment_var(<?= $data['id'] ?>,<?= $data['pid'] ?>,'payment_var3',<?= $data['instalment3'] ?>)" name="payment_var3"><span style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a href="javascript:void(0)" title="Payment Transfer to ARK" onClick="payment_ark(<?= $data['id'] ?>,<?= $data['pid'] ?>,'payment_ark3',<?= $data['instalment3'] ?>)" name="payment_ark3"><span style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a>
                                                    <?php  }
                                                    } ?>

                                                </td>

                                                <?php
                                                if ($select_status['payment_var3'] != 0) { ?>
                                                    <td id="var_color">Cleared to VAR</td>
                                                <?php } else if ($select_status['payment_ark3'] != 0) { ?>
                                                    <td id="ark_color">Transfer to ARK</td>
                                                <?php } else { ?>
                                                    <td></td>
                                                <?php } ?>
                                                <td><?= $data['date4'] ? date('d-m-Y', strtotime($data['date4'])) : 'N/A' ?></td>
                                                <td><?= $data['instalment4'] ?>
                                                    <br>
                                                    <?php if ($data['instalment4']) {
                                                        if ($select_status['payment_ark4'] != 0) { ?>
                                                            <a title="Payment Cleared to VAR" name="payment_var"><span id="blur_data" style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a title="Payment Transfer to ARK" name="payment_ark"><span id="blur_data" style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a></span>

                                                        <?php } else if ($select_status['payment_var4'] != 0) { ?>
                                                            <a title="Payment Cleared to VAR" name="payment_var"><span id="blur_data" style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a href="javascript:void(0)" title="Payment Transfer to ARK" onClick="payment_ark(<?= $data['id'] ?>,<?= $data['pid'] ?>,'payment_ark4',<?= $data['instalment4'] ?>)" name="payment_ark3"><span style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a>
                                                        <?php  } else { ?>

                                                            <a href="javascript:void(0)" title="Payment Cleared to VAR" onClick="payment_var(<?= $data['id'] ?>,<?= $data['lead_id'] ?>,'payment_var4',<?= $data['instalment4'] ?>)" name="payment_var4"><span style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a href="javascript:void(0)" title="Payment Transfer to ARK" onClick="payment_ark(<?= $data['id'] ?>,<?= $data['lead_id'] ?>,'payment_ark4',<?= $data['instalment4'] ?>)" name="payment_ark4"><span style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a>
                                                    <?php  }
                                                    } ?>

                                                </td>

                                                <?php
                                                if ($select_status['payment_var4'] != 0) { ?>
                                                    <td id="var_color">Cleared to VAR</td>
                                                <?php } else if ($select_status['payment_ark4'] != 0) { ?>
                                                    <td id="ark_color">Transfer to ARK</td>
                                                <?php } else { ?>
                                                    <td></td>
                                                <?php } ?>

                                                <td><?= $data['date5'] ? date('d-m-Y', strtotime($data['date5'])) : 'N/A' ?></td>
                                                <td><?= $data['installment5'] ?>
                                                    <br>
                                                    <?php if ($data['installment5']) {
                                                        if ($select_status['payment_ark5'] != 0) { ?>
                                                            <a title="Payment Cleared to VAR" name="payment_var"><span id="blur_data" style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a title="Payment Transfer to ARK" name="payment_ark"><span id="blur_data" style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a></span>

                                                        <?php } else if ($select_status['payment_var5'] != 0) { ?>
                                                            <a title="Payment Cleared to VAR" name="payment_var"><span id="blur_data" style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a href="javascript:void(0)" title="Payment Transfer to ARK" onClick="payment_ark(<?= $data['id'] ?>,<?= $data['pid'] ?>,'payment_ark5',<?= $data['instalment5'] ?>)" name="payment_ark5"><span style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a>
                                                        <?php  } else { ?>

                                                            <a href="javascript:void(0)" title="Payment Cleared to VAR" onClick="payment_var(<?= $data['id'] ?>,<?= $data['lead_id'] ?>,'payment_var5',<?= $data['installment5'] ?>)" name="payment_var5"><span style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a href="javascript:void(0)" title="Payment Transfer to ARK" onClick="payment_ark(<?= $data['id'] ?>,<?= $data['lead_id'] ?>,'payment_ark5',<?= $data['installment5'] ?>)" name="payment_ark5"><span style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a>
                                                    <?php  }
                                                    } ?>

                                                </td>
                                                <?php
                                                if ($select_status['payment_var5'] != 0) { ?>
                                                    <td id="var_color">Cleared to VAR</td>
                                                <?php } else if ($select_status['payment_ark5'] != 0) { ?>
                                                    <td id="ark_color">Transfer to ARK</td>
                                                <?php } else { ?>
                                                    <td></td>
                                                <?php } ?>

                                                <td><?= $data['date6'] ? date('d-m-Y', strtotime($data['date6'])) : 'N/A' ?></td>
                                                <td><?= $data['installment6'] ?>
                                                    <br>
                                                    <?php if ($data['installment6']) {
                                                        if ($select_status['payment_ark6'] != 0) { ?>
                                                            <a title="Payment Cleared to VAR" name="payment_var"><span id="blur_data" style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a title="Payment Transfer to ARK" name="payment_ark"><span id="blur_data" style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a></span>

                                                        <?php } else if ($select_status['payment_var6'] != 0) { ?>
                                                            <a title="Payment Cleared to VAR" name="payment_var"><span id="blur_data" style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a href="javascript:void(0)" title="Payment Transfer to ARK" onClick="payment_ark(<?= $data['id'] ?>,<?= $data['pid'] ?>,'payment_ark6',<?= $data['instalment6'] ?>)" name="payment_ark6"><span style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a>
                                                        <?php  } else { ?>

                                                            <a href="javascript:void(0)" title="Payment Cleared to VAR" onClick="payment_var(<?= $data['id'] ?>,<?= $data['lead_id'] ?>,'payment_var6',<?= $data['installment6'] ?>)" name="payment_var6"><span style="color:blue"><i style="font-size:18px" class="mdi mdi-credit-card-multiple"></i></a></span>
                                                            <a href="javascript:void(0)" title="Payment Transfer to ARK" onClick="payment_ark(<?= $data['id'] ?>,<?= $data['lead_id'] ?>,'payment_ark6',<?= $data['installment6'] ?>)" name="payment_ark6"><span style="color:green"><i style="font-size:18px" class="mdi mdi-bank"></i></a>
                                                    <?php  }
                                                    } ?>

                                                </td>
                                                <?php
                                                if ($select_status['payment_var6'] != 0) { ?>
                                                    <td id="var_color">Cleared to VAR</td>
                                                <?php } else if ($select_status['payment_ark6'] != 0) { ?>
                                                    <td id="ark_color">Transfer to ARK</td>
                                                <?php } else { ?>
                                                    <td></td>
                                                <?php } ?>

                                                <td><?= $data['order_price'] ?></td>

                                                <td>
                                                    <?php if (mysqli_num_rows($total_var) > 0) {
                                                        echo $totalVarAmount;
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php if (mysqli_num_rows($total_var) > 0) {
                                                        echo $totalARKAmount;
                                                    } ?></td>
                                            </tr>
                                        <?php $i++;
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5">Total</th>
                                            <td><?= getSingleresult("select IFNULL(sum(i.instalment1),0) from orders as o left join installment_details as i on o.id=i.pid left join payment_status as p on o.id=p.lead_id where o.team_id=" . $_SESSION['team_id'] . " and o.license_type='Commercial' $contd"); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td><?= getSingleresult("select IFNULL(sum(i.instalment2),0) from orders as o left join installment_details as i on o.id=i.pid left join payment_status as p on o.id=p.lead_id where o.team_id=" . $_SESSION['team_id'] . " and o.license_type='Commercial' $contd"); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td><?= getSingleresult("select IFNULL(sum(i.instalment3),0) from orders as o left join installment_details as i on o.id=i.pid left join payment_status as p on o.id=p.lead_id where o.team_id=" . $_SESSION['team_id'] . " and o.license_type='Commercial' $contd"); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td><?= getSingleresult("select IFNULL(sum(i.instalment4),0) from orders as o left join installment_details as i on o.id=i.pid left join payment_status as p on o.id=p.lead_id where o.team_id=" . $_SESSION['team_id'] . " and o.license_type='Commercial' $contd"); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td><?= getSingleresult("select IFNULL(sum(i.installment5),0) from orders as o left join installment_details as i on o.id=i.pid left join payment_status as p on o.id=p.lead_id where o.team_id=" . $_SESSION['team_id'] . " and o.license_type='Commercial' $contd"); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td><?= getSingleresult("select IFNULL(sum(i.installment6),0) from orders as o left join installment_details as i on o.id=i.pid left join payment_status as p on o.id=p.lead_id where o.team_id=" . $_SESSION['team_id'] . " and o.license_type='Commercial' $contd"); ?></td>
                                            <td></td>
                                            <td><?= getSingleresult("select IFNULL(sum(i.order_price),0) from orders as o left join installment_details as i on o.id=i.pid left join payment_status as p on o.id=p.lead_id where o.team_id=" . $_SESSION['team_id'] . " and o.license_type='Commercial' $contd"); ?></td>
                                            <td><?= $sum_var ?></td>
                                            <td><?= $sum_ark ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <div id="myModal" class="modal fade" role="dialog">


    </div>
    <?php include('includes/footer.php') ?>
    <script>
        $('#example23').DataTable({
            "displayLength": 15,
            dom: 'Bfrtip',
            buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true },
            { extend: 'print', footer: true },
            {extend: 'pageLength'}
        ],
            lengthMenu: [
                [15, 25, 50, 100, 500, 1000],
                ['15', '25', '50', '100', '500', '1000']
            ],
        });



        $(function() {
            $('#datepicker-close-date').datepicker({
                format: 'yyyy-mm-dd',
                //startDate: '-3d',
                autoclose: !0

            });

        });

        function payment_var(id, lead_id, var1, installment1) {
            // alert(var1);
            swal({
                title: "Are you sure?",
                text: "Are you sure you want to clear payment to VAR ?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Yes!",
                confirmButtonColor: "#ec6c62"
            }, function() {
                $.ajax({
                        type: 'POST',
                        url: 'notify_payment.php',
                        data: {
                            id: id,
                            lead_id: lead_id,
                            var: var1,
                            installment1: installment1
                        },
                        success: function(response) {
                            return false;
                        }
                    }).done(function(data) {
                        swal("Payment to VAR updated successfully!");
                        setTimeout(function() {
                            location.reload();
                        }, 2000)
                    })
                    .error(function(data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
            })
        }

        function payment_ark(id, lead_id, ark1, installment1) {

            swal({
                title: "Are you sure?",
                text: "Are you sure you want to transfer payment to ARK ?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Yes!",
                confirmButtonColor: "#ec6c62"
            }, function() {
                $.ajax({
                        type: 'POST',
                        url: 'notify_payment.php',
                        data: {
                            id: id,
                            lead_id: lead_id,
                            ark: ark1,
                            installment1: installment1
                        },
                        success: function(response) {
                            return false;
                        }
                    }).done(function(data) {
                        swal("Payment to ARK updated successfully!");
                        setTimeout(function() {
                            location.reload();
                        }, 2000)
                    })
                    .error(function(data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
            })
        }

        function clear_search() {
            window.location = 'var_emi_tracker.php';
        }
    </script>
    <script>
        $(document).ready(function() {
            var wfheight = $(window).height();
            $('.dataTables_wrapper').height(wfheight - 310);
            $("#example23").tableHeadFixer();

        });
    </script>