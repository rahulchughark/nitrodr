<?php

include('includes/header.php');
include_once('helpers/dashboard_helper.php');

?> <style>
  body[data-layout=horizontal] .page-content {
    padding-bottom: 20px;
  }
  /* bar-graph css */
  .highcharts-figure,
  .highcharts-data-table table {
    min-width: 210px;
    max-width: 500px;
    margin: 0 auto;
  }

  .highcharts-credits {
    display: none;
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

  .table-fixed-column-outter {
    position: relative;
    margin: 1rem auto;
    max-width: 100%;
  }

  .table-fixed-column-inner {
    overflow-x: scroll;
    overflow-y: visible;
    margin-left: 150px;
  }

  .table1 {
    margin-bottom: 0.25rem;
  }

  .table1.table-fixed-column {
    table-layout: fixed;
    width: 100%
  }

  .table1 td,
  .table1 th {
    width: 240px;
    min-height: 43px;
    height: 43px;
  }

  .table1 th:first-child,
  .table1 tr td:first-child {
    position: absolute;
    left: 0;
    width: 150px;
    line-height: 43px;
    min-height: 43px;
  }

  .table-nowrap td,
  .table-nowrap th {
    text-align: center;
  }

  .welcome-text-card {
    height: calc(100vh - 130px);
  }

  .welcome-text-card .card-body {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .welcome-text-card h1 {
    font-size: 40px
  }
</style>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-flex align-items-center justify-content-between dashboard-title-box">
            <h4 class="mb-0 font-size-18">Dashboard</h4>
            <div class="page-title-right"></div>
          </div>
        </div>
      </div>
      <div class="row"> <?php                
                if($_SESSION['user_type'] == 'MNGR')
                {
                    $users1 = db_query("select id,role from users where team_id='" . $_SESSION['team_id'] . "' and status='Active' ");
                    $ids = array();
    
                    while ($uid = db_fetch_array($users1)) {
                        $ids[] = $uid['id'];
                    }
    
                    $idds = implode(',', $ids);
                    $userCond = " o.team_id='" . $_SESSION['team_id'] . "' ";
                    $userCondA = " o.team_id='" . $_SESSION['team_id'] . "' and a.added_by in (" . $idds . ")";
                }elseif($_SESSION['user_type'] == 'USR'){
                    $userCond = " o.team_id='" . $_SESSION['team_id'] . "' and o.created_by = '".$_SESSION['user_id']."' ";
                    $userCondA = " o.team_id='" . $_SESSION['team_id'] . "' and a.added_by=" . $_SESSION['user_id'];
                }
                // print_r($_SESSION);die;
                    $currentMonth = date('n');
                    $currentYear = date('Y');
                    $platformAccounts = getSingleresult("SELECT COUNT(o.id) from orders as o left join tbl_lead_product as tlp on tlp.lead_id=o.id WHERE $userCond and tlp.product_type_id='1' and o.status='Approved' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    $platformQuanity = getSingleresult("SELECT SUM(o.quantity) from orders as o left join tbl_lead_product as tlp on tlp.lead_id=o.id WHERE $userCond and tlp.product_type_id='1' and o.status='Approved' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    
                    $platBokkAccounts = getSingleresult("SELECT COUNT(o.id) from orders as o left join tbl_lead_product as tlp on tlp.lead_id=o.id WHERE $userCond and tlp.product_type_id='2' and o.status='Approved' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    $platBokkQuanity = getSingleresult("SELECT SUM(o.quantity) from orders as o left join tbl_lead_product as tlp on tlp.lead_id=o.id WHERE $userCond and tlp.product_type_id='2' and o.status='Approved' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    
                    $kitAccounts = getSingleresult("SELECT COUNT(o.id) from orders as o left join tbl_lead_product as tlp on tlp.lead_id=o.id WHERE $userCond and tlp.product_type_id='3' and o.status='Approved' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    $kitQuanity = getSingleresult("SELECT SUM(o.quantity) from orders as o left join tbl_lead_product as tlp on tlp.lead_id=o.id WHERE $userCond and tlp.product_type_id='3' and o.status='Approved' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    
                    $platBokKitAccounts = getSingleresult("SELECT COUNT(o.id) from orders as o left join tbl_lead_product as tlp on tlp.lead_id=o.id WHERE $userCond and tlp.product_type_id='4' and o.status='Approved' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    $platBokKitQuanity = getSingleresult("SELECT SUM(o.quantity) from orders as o left join tbl_lead_product as tlp on tlp.lead_id=o.id WHERE $userCond and tlp.product_type_id='4' and o.status='Approved' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);

                    $pendingAccounts = getSingleresult("SELECT COUNT(o.id) from orders as o WHERE $userCond and o.agreement_type='Fresh' and o.status='Pending' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    $pendingQuanity = getSingleresult("SELECT SUM(o.quantity) from orders as o WHERE $userCond and o.agreement_type='Fresh' and o.status='Pending' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    
                    $qualifiedAccounts = getSingleresult("SELECT COUNT(o.id) from orders as o WHERE $userCond and o.agreement_type='Fresh' and o.status='Approved' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    $qualifiedQuanity = getSingleresult("SELECT SUM(o.quantity) from orders as o WHERE $userCond and o.agreement_type='Fresh' and o.status='Approved' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    
                    $cancelledAccounts = getSingleresult("SELECT COUNT(o.id) from orders as o WHERE $userCond and o.agreement_type='Fresh' and o.status='Cancelled' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    $cancelledQuanity = getSingleresult("SELECT SUM(o.quantity) from orders as o WHERE $userCond and o.agreement_type='Fresh' and o.status='Cancelled' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);                    
                    
                    $resubAccounts = getSingleresult("SELECT COUNT(o.id) from orders as o WHERE $userCond and o.agreement_type='Fresh' and o.status='Undervalidation' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    $resubQuanity = getSingleresult("SELECT SUM(o.quantity) from orders as o WHERE $userCond and o.agreement_type='Fresh' and o.status='Undervalidation' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                
                    $today = date("Y-m-d");

                    $subMittedCount = getSingleresult("SELECT COUNT(o.id) from orders as o WHERE $userCond and o.agreement_type='Fresh' and DATE(o.created_date) = '".$today."'");
                    
                    $visit_lead = getSingleresult("select count(distinct(a.id)) from activity_log as a left join orders as o on a.pid=o.id where $userCondA and o.agreement_type='Fresh' and a.call_subject like '%visit%' and DATE(a.created_date) = '".$today."'");

                    $not_visit_lead = getSingleresult("select count(distinct(a.id)) from activity_log as a left join orders as o on a.pid=o.id where $userCondA and o.agreement_type='Fresh' and a.call_subject not like '%visit%' and DATE(a.created_date) = '".$today."'");
 
                    $demo_done_lead = getSingleresult("select count(distinct(o.id)) from orders as o left join lead_modify_log as lm on lm.lead_id = o.id where $userCond and o.agreement_type='Fresh' and lm.modify_name='Demo' and o.add_comm='Demo Completed' and DATE(lm.created_date) = '".$today."'");
             
                    $demo_arranged_lead = getSingleresult("select count(distinct(o.id)) from orders as o left join lead_modify_log as lm on lm.lead_id = o.id where $userCond and o.agreement_type='Fresh' and lm.modify_name='Demo' and o.add_comm='Demo Arranged' and DATE(lm.created_date) = '".$today."'");
             
                    $stagesQ = db_query("SELECT * from stages");
                    while($stage = db_fetch_array($stagesQ)){

                        $a = getSingleresult("SELECT count(o.id) from orders as o where o.stage='".$stage['stage_name']."' and".$userCond." and o.agreement_type='Fresh' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                        $stageA[] = $a;
                    }

            ?> <div class="col-xl-4 mb-4">
            <div class="dashboard-card mb-0 h-100">
              <h5 class="card-title">Product wise active data</h5>
            <div class="card-body dashboard-box-container">
              <div class="row">
                <div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-hover table-centered table-nowrap mb-0">
                      <thead>
                        <tr>
                          <th>Product</th>
                          <th># Accounts</th>
                          <th> # Students</th>
                        </tr>
                      </thead>
                      <tbody style="text-align:center;">
                        <tr>
                          <td>Platform</td>
                          <td> <?= $platformAccounts ?> </td>
                          <td> <?= $platformAccounts ? $platformQuanity : 0 ?> </td>
                        </tr>
                        <tr>
                          <td>Platform + Book</td>
                          <td> <?= $platBokkAccounts ?> </td>
                          <td> <?= $platBokkAccounts ? $platBokkQuanity : 0 ?> </td>
                        </tr>
                        <tr>
                          <td>Kit</td>
                          <td> <?= $kitAccounts ?> </td>
                          <td> <?= $kitAccounts ? $kitQuanity : 0 ?> </td>
                        </tr>
                        <tr>
                          <td>Platform + Book+KIT</td>
                          <td> <?= $platBokKitAccounts ?> </td>
                          <td> <?= $platBokKitAccounts ? $platBokKitQuanity : 0 ?> </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>Total</th>
                          <th> <?= ($platformAccounts+$platBokkAccounts+$kitAccounts+$platBokKitAccounts) ?> </th>
                          <th> <?= ($platformQuanity+$platBokkQuanity+$kitQuanity+$platBokKitQuanity) ?> </th>
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
        <div class="col-xl-4 mb-4">
          <div class="dashboard-card mb-0 h-100">
            <h5 class="card-title">Today's activity</h5>
            <div class="card-body dashboard-box-container">
              <div class="row">
                <div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-hover table-centered table-nowrap mb-0">
                      <thead>
                        <tr>
                          <th>Activity</th>
                          <th>Count</th>
                        </tr>
                      </thead>
                      <tbody style="text-align:center;">
                        <tr>
                          <td>New data submitted</td>
                          <td> <?= $subMittedCount ?> </td>
                        </tr>
                        <tr>
                          <td>Visits done</td>
                          <td> <?= $visit_lead ?> </td>
                        </tr>
                        <tr>
                          <td>Calls done</td>
                          <td> <?= $not_visit_lead ?> </td>
                        </tr>
                        <tr>
                          <td>Demo Arranged</td>
                          <td> <?= $demo_arranged_lead ?> </td>
                        </tr>
                        <tr>
                          <td>Demo done</td>
                          <td> <?= $demo_done_lead ?> </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!--end table-responsive-->
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 mb-4">
          <div class="dashboard-card mb-0 h-100">
            <h5 class="card-title">Monthly data counter</h5>
            <div class="card-body dashboard-box-container">
              <div class="row">
                <div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-hover table-centered table-nowrap mb-0">
                      <thead>
                        <tr>
                          <th>Status</th>
                          <th># Accounts</th>
                          <th> # Students</th>
                        </tr>
                      </thead>
                      <tbody style="text-align:center;">
                        <tr>
                          <td>Pending</td>
                          <td> <?= $pendingAccounts ?> </td>
                          <td> <?= $pendingAccounts ? $pendingQuanity : 0 ?> </td>
                        </tr>
                        <tr>
                          <td>Qualified</td>
                          <td> <?= $qualifiedAccounts ?> </td>
                          <td> <?= $qualifiedAccounts ? $qualifiedQuanity : 0 ?> </td>
                        </tr>
                        <tr>
                          <td>Unqualified</td>
                          <td> <?= $cancelledAccounts ?> </td>
                          <td> <?= $cancelledAccounts ? $cancelledQuanity : 0 ?> </td>
                        </tr>
                        <tr>
                          <td>Re-submission</td>
                          <td> <?= $resubAccounts ?> </td>
                          <td> <?= $resubAccounts ? $resubQuanity : 0 ?> </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th># Submitted</th>
                          <th> <?= ($pendingAccounts+$qualifiedAccounts+$cancelledAccounts+$resubAccounts) ?> </th>
                          <th> <?= ($pendingQuanity+$qualifiedQuanity+$cancelledQuanity+$resubQuanity) ?> </th>
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
      </div>
      <div class="row">
        <div class="col-xl-4 mb-4">
          <div class="dashboard-card">
            <h5 class="card-title">Stage wise Progress</h5>
            <div class="card-body">
              <div id="Stage" style="height:240px"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end main content--> <?php

        include('includes/footer.php') ?>
<!-- JAVASCRIPT -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/variable-pie.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
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
<script>
  const names = ['Data', 'Potential', 'Demo', 'Quote', 'Follow-up', 'Nego', 'Commit', 'Po-CIF Issued', 'Advance Payment', 'Billing'];

  function getRandomData() {
    const data = [];
    dataS = [ < ? = $stageA[0] ? > , < ? = $stageA[1] ? > , < ? = $stageA[2] ? > , < ? = $stageA[3] ? > , < ? = $stageA[4] ? > , < ? = $stageA[5] ? > , < ? = $stageA[6] ? > , < ? = $stageA[7] ? > , < ? = $stageA[8] ? > , < ? = $stageA[9] ? > ];
    for (let i = 0; i < 10; i++) {
      data.push([names[i], dataS[i]]);
    }
    return data;
  }
  const chart = Highcharts.chart('Stage', {
    chart: {
      type: 'bar',
      marginLeft: 130
    },
    title: {
      text: 'Stages'
    },
    yAxis: {
      title: {
        text: ''
      }
    },
    xAxis: {
      type: 'category',
      min: 0,
      labels: {
        animate: false
      }
    },
    legend: {
      enabled: false
    },
    series: [{
      zoneAxis: 'x',
      zones: [{
        value: 1,
        color: '#ff4d40'
      }],
      dataLabels: {
        enabled: true,
        format: '{y:,.2f}'
      },
      dataSorting: {
        enabled: true,
        sortKey: 'y'
      },
      data: getRandomData()
    }]
  });
  // setInterval(function () {
  //     chart.series[0].setData(
  //         getRandomData(),
  //         true,
  //         { duration: 2000 }
  //     );
  // }, 3000);
</script>