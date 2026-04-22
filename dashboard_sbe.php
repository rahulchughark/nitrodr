<?php

include('includes/header.php');sbe_dashboard();
include_once('helpers/dashboard_helper.php');

if (!$_GET['date_from']) {
    $_GET['date_from'] = date('Y-m-d');
}
if (!$_GET['date_to']) {
    $_GET['date_to'] = date('Y-m-d');
}

?>
<style>
    /* bar-graph css */
    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 210px;
        max-width: 500px;
        margin: 0 auto;
    }

    #container {
        height: 300px;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #EBEBEB;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }

    /* bar-graph css ends */

    /* pie chart css */

    #highcharts-figure,
    #highcharts-data-table table {
        min-width: 320px;
        max-width: 500px;
        margin: 0 auto;
    }

    .container {
        height: 170px;
    }

    #highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #EBEBEB;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    #highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    #highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    #highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    #highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    #highcharts-data-table tr:hover {
        background: #f1f7ff;
    }

    .table-nowrap td,
    .table-nowrap th {

        text-align: center;
    }
</style>


<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <?php
            if ($_SESSION['user_type'] == 'ISS SBE MNGR') {
                $date_from = $_GET['date_from'];
                $date_to = $_GET['date_to'];

                if (!$_POST['date_from']) {
                    $date_from = $date_from;
                    $date_to = $date_to;
                } else {
                    $date_from = $_POST['date_from'];
                    $date_to = $_POST['date_to'];
                }

                $iss_users = db_query("select users.id,users.role,c.id as caller,c.name from users left join callers as c on users.id=c.user_id where users.user_type='CLR' and users.role='ISS' and users.status='Active' ");
                $ids = array();

                while ($uid = db_fetch_array($iss_users)) {
                    $ids[] = $uid['caller'];
                    $user_ids[] = $uid['id'];
                    $names[] = $uid['name'];
                }
                $iss_ids = implode(',', $ids);
                $caller_ids = implode(',', $user_ids);
                $iss_names = implode("','", $names);
                //print_r($caller_ids);

                if ($_SESSION['user_type'] == 'ISS SBE MNGR') {
                    $contd = " and o.caller in (" . $iss_ids . ")";
                    $user_id = " and a.added_by in (" . $caller_ids . ")";
                } else {
                    $contd = " and o.caller=" . $_SESSION['caller'];
                    $user_id = " and a.added_by=" . $_SESSION['user_id'];
                }

            ?>
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between dashboard-title-box">
                            <h4 class="mb-0 font-size-18">Corel ISS SBE Manager Dashboard</h4>

                            <div class="page-title-right">
                                <form class="form-inline" role="form" method="post">
                                    <div class="input-daterange input-group" id="date_range1">
                                        <input type="text" class="form-control" name="date_from" value="<?= $_POST['date_from'] ? $_POST['date_from'] : $_GET['date_from'] ?>" placeholder=" Date From" />
                                        <input type="text" class="form-control ml-1" name="date_to" value="<?= $_POST['date_to'] ? $_POST['date_to'] : $_GET['date_to'] ?>" placeholder=" Date To" />
                                    </div>


                                    <button type="submit" class="btn btn-sm noti-icon waves-effect dashboard-search-icon">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php if ($_SESSION['user_type'] == 'ISS SBE MNGR') { ?>
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-body dashboard-box-container">
                                    <h5 class="card-title mb-2">SBE Data Qualified</h5>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-centered table-nowrap mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr. Number</th>
                                                            <th>VAR Name</th>
                                                            <th>Accounts Qualified</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php 
                                                        $account = dataCounterSBEMngr($date_from, $date_to, $iss_ids);

                                                        $i = 1;
                                                        $total = 0;
                                                        while ($row = db_fetch_array($account)) {
                                                            $total += $row['count(o.id)'];
                                                        ?>
                                                            <tr>
                                                                <td> <?= $i ?> </td>
                                                                <td><a target="_blank" href="orders_caller.php?counter=<?= $row['team_id'] ?>&d_from=<?= $date_from ?>&d_to=<?= $date_to ?>&type=iss_sbe"><?= $row['r_name'] ?></a> </td>
                                                                <td> <?= $row['count(o.id)'] ?></td>

                                                            </tr>
                                                        <?php $i++;
                                                        } ?>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th>Total</th>
                                                            <th><?= $total ?></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <!--end table-responsive-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }  ?>

                    <!-- <div class="col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-2">My daily score</h5>
                                <div id="dr-timespan_iss" style="height:200px"></div>
                            </div>
                        </div>
                    </div> -->

                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-body dashboard-box-container">
                                <h5 class="card-title mb-2">BD/Incoming to LC counter</h5>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-centered table-nowrap mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. Number</th>
                                                        <th>VAR Name</th>
                                                        <th>Accounts Converted</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php
                                                    // print_r($iss_names);
                                                    if ($_SESSION['user_type'] == 'ISS SBE MNGR')
                                                    {
                                                        $account_lc = LCBDCounterSBEMngr($date_from, $date_to, $iss_names);
                                                    }

                                                    // print_r($account_lc);
                                                    $i = 1;
                                                    $total = 0;
                                                    foreach ($account_lc as $value) {

                                                        $total += $value['count(lm.lead_id)'];
                                                    ?>
                                                        <tr>
                                                            <td><?= $i ?></td>
                                                            <td><a target="_blank" href="orders_caller.php?lc_count=<?= $value['r_name'] ?>&d_from=<?= $date_from ?>&d_to=<?= $date_to ?>&type=iss_sbe"><?= $value['r_name'] ?></a></td>
                                                            <td><?= $value['count(lm.lead_id)'] ?></td>
                                                        </tr>
                                                    <?php $i++;
                                                    }  ?>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th><?= $total ?></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <!--end table-responsive-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-body dashboard-box-container">
                                <h5 class="card-title mb-3">Untouched Accounts In</h5>
                                <div class="col-xl-12 text-center">
                                    <ul class="list-unstyled list-inline">
                                        <li class="list-inline-item px-3">
                                            <h5 class="text-warning mt-0">
                                               
                                                <?= getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2 or tp.product_type_id=3) and o.license_type='Commercial' and  o.stage NOT IN('Booking','OEM Billing','Closed Lost','Billed To Other Re-Seller','Hold License Certificate/Copy') and o.status='Approved' and o.quantity >= 3 ".$contd." AND NOT EXISTS( SELECT a.pid,a.created_date FROM activity_log a WHERE a.pid=o.id $user_id and a.created_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) "); ?>
                                            </h5>
                                            <a target="_blank" href="orders_caller.php?untouched=7&type=iss_sbe"><small class="font-size-14 text-muted">Last 7 Days</small></a>
                                        </li>
                                        <li class="list-inline-item px-3">
                                            <h5 class="text-warning mt-0">
                                                <?= getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2 or tp.product_type_id=3) and o.license_type='Commercial' and  o.stage NOT IN('Booking','OEM Billing','Closed Lost','Billed To Other Re-Seller','Hold License Certificate/Copy') and o.status='Approved' and o.quantity >= 3 ".$contd." AND NOT EXISTS( SELECT a.pid,a.created_date FROM activity_log a WHERE a.pid=o.id $user_id and a.created_date >= DATE_SUB(CURDATE(), INTERVAL 15 DAY)) "); ?>

                                            </h5>
                                            <a target="_blank" href="orders_caller.php?untouched=15&type=iss_sbe"><small class="font-size-14 text-muted">Last 15 Days</small></a>
                                        </li>
                                        <li class="list-inline-item px-3">
                                            <h5 class="text-warning mt-0">
                                                <?= getSingleresult("select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2 or tp.product_type_id=3) and o.license_type='Commercial' and  o.stage NOT IN('Booking','OEM Billing','Closed Lost','Billed To Other Re-Seller','Hold License Certificate/Copy') and o.status='Approved' and o.quantity >= 3 ".$contd." AND NOT EXISTS( SELECT a.pid,a.created_date FROM activity_log a WHERE a.pid=o.id $user_id and a.created_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)) "); ?>
                                            </h5>
                                            <a target="_blank" href="orders_caller.php?untouched=30&type=iss_sbe"><small class="font-size-14 text-muted">Last 30 Days</small></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    

                    <!-- <div class="col-xl-4">
                        <div class="card">
                            <div class="card-body dashboard-box-container">
                                <h5 class="card-title mb-2">POA Summary</h5>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-centered table-nowrap mb-0">
                                                <thead>
                                                    <tr>                                                       
                                                        <th>POA Subject</th>
                                                        <th># Accounts</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php
                                            
                                                     if($_SESSION['user_type'] == 'ISS SBE MNGR'){
                                                        // $res = "select DISTINCT(o.id),a.action_plan from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log a on o.id=a.pid left join callers c on c.id=o.caller where tp.product_type_id in (1,2) and o.license_type='Commercial' and a.is_intern=0 and o.caller in (".$iss_ids.") and date(a.created_date)>='" . $date_from . "' and date(a.created_date)<='" . $date_to . "' and a.action_plan in ('Need More Validation','Turns Negative','Drop') group by a.pid having (count(a.pid)>0) order by a.created_date desc";
                                                        // print_r($res);
                                                        // $res = poaNeedMoreValidationMngr($iss_ids,$date_from, $date_to);
                                                    }else{ 
                                                        // $res = poaNeedMoreValidation($_SESSION['caller'],$date_from, $date_to);
                                                    } 

                                                    // while($row= db_fetch_array($res)){
                                                    //     $poa_subject = $row['action_plan'];
                                                    // }

                                                    ?>
                                                        <tr>                                                           
                                                            <td>Need more validation</td>
                                                            <td> </td>                                                                                                                       
                                                        </tr>
                                                        <tr>                                                          
                                                            <td>Drop</td>
                                                            <td><?php if($_SESSION['user_type'] == 'ISS SBE MNGR'){
                                                                // echo poaDropMngr($iss_ids,$date_from, $date_to);
                                                            }else{ 
                                                                echo poaDrop($_SESSION['caller'],$date_from, $date_to);
                                                            } ?>
                                                            </td>
                                                        </tr>
                                                        <tr>                                                          
                                                            <td>Turns Negative</td>
                                                            <td><?php if($_SESSION['user_type'] == 'ISS MNGR' || $_SESSION['user_type']=='TEAM LEADER'){
                                                               // print_r("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log a on o.id=a.pid left join callers c on c.id=o.caller where tp.product_type_id in (1,2) and o.license_type='Commercial' and a.is_intern=0 and a.action_plan='Turns Negative' and o.caller in (".$iss_ids.") and date(a.created_date)>='" . $date_from . "' and date(a.created_date)<='" . $date_to . "' group by a.pid ");
                                                                // echo poaTurnsNegativeMngr($iss_ids,$date_from, $date_to);
                                                            } ?>
                                                            </td>
                                                        </tr>
                                                  
                                                </tbody>
                                                <tfoot>
                                                    <tr>                                                      
                                                        <th>Total</th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        end table-responsive
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                </div>
            <?php } ?>

        </div> <!-- container-fluid -->

    </div>
    <!-- End Page-content  -->



</div>
<!-- end main content-->


<?php include('includes/footer.php') ?>

<!-- JAVASCRIPT -->

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/variable-pie.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<?php
if ($_SESSION['team_id'] == 23 || $_SESSION['team_id'] == 36 || $_SESSION['team_id'] == 30 || $_SESSION['team_id'] == 46) {
    if ($_SESSION['user_type'] == 'ISS SBE MNGR') {
        if (!isset($_POST['search_btn'])) {
?>
            <script>
                $(function() {
                    $.ajax({
                        type: 'POST',
                        url: 'self_review.php',
                        success: function(response) {
                            $("#selfReview").html();
                            $("#selfReview").html(response);
                            $('#selfReview').modal('show');
                        }
                    });
                });
            </script>
<?php }
    }
} ?>




<script language="JavaScript">
    $(function() {
        $('#date_range').datepicker({
            format: 'yyyy-mm-dd',
            //startDate: '-3d',
            autoclose: !0

        });
    });
    $(function() {
        $('#date_range1').datepicker({
            format: 'yyyy-mm-dd',
            //startDate: '-3d',
            autoclose: !0

        });
    });
</script>
<?php
if (($_SESSION['user_type'] == 'ISS SBE MNGR')) { ?>

    <script language="JavaScript">
        Highcharts.chart('dr-timespan_iss', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },

            /** to hide highchart logo */
            credits: {
                enabled: false
            },
            plotOptions: {
                series: {
                    cursor: 'pointer',
                    dataLabels: {

                        formatter: function() {
                            return '<strong>' + this.point.name + '</strong>: ' + this.point.y;
                        }
                    }
                },
                pie: {
                    // size: '200%',
                    dataLabels: {
                        enabled: true,
                        distance: 10,
                        style: {
                            fontWeight: 'bold',
                            color: 'black'
                        }
                    },
                    // startAngle: -90,
                    // endAngle: 90,
                    // center: ['40%', '100%'],

                    showInLegend: true
                }
            },
            // plotOptions: {
            //         pie: {
            //             //allowPointSelect: true,
            //             cursor: 'pointer',
            //             dataLabels: {

            //                 formatter: function() {
            //                     return '<strong>' + this.point.name + '</strong>: ' + this.point.y;
            //                 }
            //             },
            //             showInLegend: true
            //         }
            //     },

            series: [{
                innerSize: '50%',
                name: '',
                //colorByPoint: true,
                data: [{
                    name: 'Accounts qualified',
                    y: <?= scoreQualifiedCaller($_GET['date_from'], $_SESSION['caller']);
                        //getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.license_type='Commercial' and o.dvr_flag!=1 and o.status='Approved' and o.is_iss_lead = 0 and date(o.approval_time)='" . $_GET['date_from'] . "' and o.caller=".$_SESSION['caller']) 
                        ?>,
                    color: 'red',

                    selected: true
                }, {
                    name: 'log a calls',
                    y: <?= scoreLogsCaller($_GET['date_from'], $_SESSION['user_id']);
                        // getSingleresult("select count(DISTINCT(a.id)) from activity_log as a left join tbl_lead_product as tp on a.pid=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and date(a.created_date)='" . $_GET['date_from'] . "' and a.added_by=" . $_SESSION['caller'])
                        ?>,
                    color: 'green'
                }, {
                    name: 'Leads shared to VAR',
                    y: <?= VARLeadsCaller($_GET['date_from'], $_SESSION['user_id']);
                        //getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.license_type='Commercial' and o.dvr_flag!=1 and o.is_iss_lead = 1 and date(o.created_date)='" . $_GET['date_from'] . "' and o.created_by=".$_SESSION['caller']) 
                        ?>,
                    color: 'yellow'
                }]
            }]
        });
    </script>

<?php } else if ($_SESSION['user_type'] == 'ISS SBE MNGR') { ?>

    <script language="JavaScript">
        Highcharts.chart('dr-timespan_iss', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },

            /** to hide highchart logo */
            credits: {
                enabled: false
            },
            plotOptions: {
                series: {
                    cursor: 'pointer',
                    dataLabels: {

                        formatter: function() {
                            return '<strong>' + this.point.name + '</strong>: ' + this.point.y;
                        }
                    }
                },
                pie: {
                    // size: '200%',
                    dataLabels: {
                        enabled: true,
                        distance: 10,
                        style: {
                            fontWeight: 'bold',
                            color: 'black'
                        }
                    },

                    showInLegend: true
                }
            },
            series: [{
                name: '',
                //colorByPoint: true,
                data: [{
                    name: 'Accounts qualified',
                    y: <?= scoreQualifiedMngr($_GET['date_from'], $iss_ids);
                        //getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.license_type='Commercial' and o.dvr_flag!=1 and o.status='Approved' and o.is_iss_lead = 0 and date(o.approval_time)='" . $_GET['date_from'] . "' and o.caller in (".$iss_ids.")") 
                        ?>,
                    color: 'red',

                    selected: true
                }, {
                    name: 'log a calls',
                    y: <?= scoreLogsMngr($_GET['date_from'], $caller_ids);
                        //getSingleresult("select count(DISTINCT(a.id)) from activity_log as a left join tbl_lead_product as tp on a.pid=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and date(a.created_date)='" . $_GET['date_from'] . "' and a.added_by in (" . $iss_ids.")")
                        ?>,
                    color: 'green'
                }, {
                    name: 'Leads shared to VAR',
                    y: <?= VARLeadsMngr($_GET['date_from'], $caller_ids)
                        //getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.license_type='Commercial' and o.dvr_flag!=1 and o.is_iss_lead = 1 and date(o.created_date)='" . $_GET['date_from'] . "' and o.created_by in (".$iss_ids.")") 
                        ?>,
                    color: 'yellow'
                }]
            }]
        });
    </script>
<?php } ?>



<script>
    $(document).ready(function() {

        var wfheight = $(window).height();
        $('.fixed-table-body').height(wfheight - 530);

        $('.fixed-table-body').slimScroll({
            color: '#00f',
            size: '10px',
            height: 'auto',


        });

    });
</script>