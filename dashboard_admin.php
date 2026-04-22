<?php

include('includes/header.php');
include_once('helpers/dashboard_helper.php');

if (!$_GET['date_from']) {
    $_GET['date_from'] = date('Y-m-d');
}
if (!$_GET['date_to']) {
    $_GET['date_to'] = date('Y-m-d');
}

?>
<style>
    body[data-layout=horizontal] .page-content {
        padding-bottom: 40px;
    }

    .page-content {
        height: calc(100vh - 200px);
        overflow: auto;
    }
    table tbody tr td{
        height:30px
    }
    /* .highcharts-credits{
        display: none;
    } */
    .scrollme  {
    overflow-x: auto;
    height:200px;

}

.scrollme thead{
position:sticky;
}
tr.clickable-row:hover{
  cursor: pointer;
 
}
</style>


<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
        <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between dashboard-title-box">
                            <h4 class="mb-0 font-size-18 font-weight-bold">Dashboard</h4>

                            <div class="page-title-right">
                                <!-- <form class="form-inline" role="form">
                                    <div class="input-daterange input-group" id="date_range1">
                                        <input type="text" class="form-control" name="date_from" placeholder=" Date From" />
                                        <input type="text" class="form-control ml-1" name="date_to" placeholder=" Date To" />
                                    </div>


                                    <button type="submit" class="btn btn-sm noti-icon waves-effect dashboard-search-icon">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </form> -->
                            </div>

                        </div>
                    </div>
                </div>

                <?php
                    $currentMonth = date('n');
                    $currentYear = date('Y');
                    $firstDayOfMonth = date('Y-m-01');
                    $lastDayOfMonth = date('Y-m-t');

                    $subMittedCount = getSingleresult("SELECT COUNT(o.id) from orders as o WHERE o.agreement_type='Fresh' and o.agreement_type='Fresh' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);

                    $pendingAccounts = getSingleresult("SELECT COUNT(o.id) from orders as o WHERE o.agreement_type='Fresh' and o.status='Pending' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    
                    $qualifiedAccounts = getSingleresult("SELECT COUNT(o.id) from orders as o WHERE o.agreement_type='Fresh' and o.status='Approved' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    
                    $cancelledAccounts = getSingleresult("SELECT COUNT(o.id) from orders as o WHERE o.agreement_type='Fresh' and o.status='Cancelled' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                    
                    $resubAccounts = getSingleresult("SELECT COUNT(o.id) from orders as o WHERE o.agreement_type='Fresh' and o.status='Undervalidation' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);

                    $onholdAccounts = getSingleresult("SELECT COUNT(o.id) from orders as o WHERE o.agreement_type='Fresh' and o.status='On-Hold' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);

                    $stagesQ = db_query("SELECT * from stages");
                    while($stage = db_fetch_array($stagesQ)){

                        $a = getSingleresult("SELECT count(o.id) from orders as o where o.stage='".$stage['stage_name']."' and o.agreement_type='Fresh' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear);
                        $stageA[] = $a;
                        $stageUrl[] = 'manage_orders.php?stage[]='.$stage['stage_name'].'&d_from='.$firstDayOfMonth.'&d_to='.$lastDayOfMonth;
                    }

                    $topStudQ = db_query("SELECT o.id,o.quantity,o.r_name,o.school_name,o.city,o.stage from orders as o where o.agreement_type='Fresh' and MONTH(o.created_date) = ".$currentMonth." AND YEAR(o.created_date) = ".$currentYear." order by o.quantity limit 10");
                ?>

                <div class="row">
                    <div class="col-xl-6 mb-4">
                        <div class="dashboard-card mb-0 h-100">
                            <h5 class="card-title">Monthly data counter</h5>
                            <div class="card-body dashboard-box-container">
                               <div id="Stage" style="height:240px"></div> 
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 mb-4">
                        <div class="dashboard-card mb-0 h-100">
                            <h5 class="card-title">Stage wise Progress</h5>
                            <div class="card-body dashboard-box-container">
                                <div id="trellis"  style="height:240px">
                            </div>
                        </div>
                    </div>


</div>



</div>

<?php

        if ($_SESSION['sales_manager'] == 1) {
            $sales = " and o.team_id in (" . $_SESSION['access'] . ") ";
            $sm_user = " and sm_user =" . $_SESSION['user_id'];
            $active_users = " and team_id in (" . $_SESSION['access'] . ") ";
        }

        // $query1 = inactiveTeamDVR(date('Y-m-d', strtotime('-1 days')), $sm_user);
        // while ($row = db_fetch_array($query1)) {
        //     $arr1[] = $row['name'];
        // }

        $query2 = inactiveTeamlogLead(date('Y-m-d', strtotime('-1 days')), $sm_user);
        while ($row = db_fetch_array($query2)) {
            $arr2[] = $row['name'];
        }

        // $query3 = inactiveTeamlogLapsed(date('Y-m-d', strtotime('-1 days')), $sm_user);
        // while ($row = db_fetch_array($query3)) {
        //     $arr3[] = $row['name'];
        // }

        // $query4 = inactiveTeamlogRaw(date('Y-m-d', strtotime('-1 days')), $sm_user);
        // while ($row = db_fetch_array($query4)) {
        //     $arr4[] = $row['name'];
        // }
        $query5 = inactiveTeamStage(date('Y-m-d', strtotime('-1 days')), $sm_user);
        while ($row = db_fetch_array($query5)) {
            $arr5[] = $row['name'];
        }

        $result = @array_intersect($arr2, $arr5);

        // $query6 = inactiveTeamDVRDate7($sm_user);

        // while ($row = db_fetch_array($query6)) {
        //     $arr6[] = $row['name'];
        // }
        $query7 = inactiveTeamlogLeadDate7($sm_user);
        while ($row = db_fetch_array($query7)) {
            $arr7[] = $row['name'];
        }

        // $query8 = inactiveTeamlogLapsedDate7($sm_user);
        // while ($row = db_fetch_array($query8)) {
        //     $arr8[] = $row['name'];
        // }

        // $query9 = inactiveTeamlogRawDate7($sm_user);
        // while ($row = db_fetch_array($query9)) {
        //     $arr9[] = $row['name'];
        // }
        $query10 = inactiveTeamStageDate7($sm_user);
        while ($row = db_fetch_array($query10)) {
            $arr10[] = $row['name'];
        }
        $result1 = @array_intersect($arr7,$arr10);

        // $inactive15_daysDVR =  inactiveTeamDVRDate15($sm_user);

        // while ($row = db_fetch_array($inactive15_daysDVR)) {
        //     $arr11[] = $row['name'];
        // }

        $inactive15_daysLead = inactiveTeamlogLeadDate15($sm_user);

        while ($row = db_fetch_array($inactive15_daysLead)) {
            $arr12[] = $row['name'];
        }

        // $inactive15_daysLapsed =  inactiveTeamlogLapsedDate15($sm_user);

        // while ($row = db_fetch_array($inactive15_daysLapsed)) {
        //     $arr13[] = $row['name'];
        // }

        // $inactive15_daysRaw = inactiveTeamlogRawDate15($sm_user);

        // while ($row = db_fetch_array($inactive15_daysRaw)) {
        //     $arr14[] = $row['name'];
        // }

        $inactive15_daysStage = inactiveTeamStageDate15($sm_user);

        while ($row = db_fetch_array($inactive15_daysStage)) {
            $arr15[] = $row['name'];
        }

        $result2 = @array_intersect($arr12,$arr15);

?>
                                                      <?php if(!empty($result)){
                                                             foreach ($result as $value) { 

                                                                $yes[] = $value;

                                                              }}
                                                              if (@count($result1) != 0) {
                                                                foreach ($result1 as $value1) { 
                                                                    $days7[] = $value1;
                                                                 }
                                                            } 
                                                            if (@count($result2) != 0) {
                                                                foreach ($result2 as $value2) { 
                                                                    $days15[] = $value2 ;
                                                                 }
                                                            }

                                                            ?>
<div class="row">
    <div class="col-xl-6 mb-4">
        <div class="dashboard-card mb-0 h-100">
            <h5 class="card-title">Inactive Partners @ DR</h5>
            <div class="card-body dashboard-box-container">
                <div class="table-responsive scrollme" style="height: 200px;">
                    <table class="table table-hover table-centered table-nowrap mb-0">
                        <thead>
                        <tr>
                            <td style="font-weight:bold">Yesterday(<?php echo count($yes) ?>)</td>
                            <td style="font-weight:bold">Last 7 days(<?php echo count($days7) ?>)</td>
                            <td style="font-weight:bold">Last 15 days(<?php echo count($days15) ?>)</td>
                        </tr>
                        </thead>
                                            <tbody style="height: 200px;overflow-y:scoll">
                    
                        <?php
                        $maxRows = max(count($yes), count($days7), count($days15));
                        for ($i = 0; $i < $maxRows; $i++) { ?>
                            <tr>
                                <td><?php echo isset($yes[$i]) ? $yes[$i] : ''; ?></td>
                                <td><?php echo isset($days7[$i]) ? $days7[$i] : ''; ?></td>
                                <td><?php echo isset($days15[$i]) ? $days15[$i] : ''; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6 mb-4">
        <div class="dashboard-card mb-0 h-100">
            <h5 class="card-title">Top active deal size</h5>
            <div class="card-body dashboard-box-container">
                <div class="table-responsive" >
                    <table class="table table-hover table-centered table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th># No. of student</th>
                                <th>Account Name</th>
                                <th>City</th>
                                <th>Stage</th>
                                <th>Reseller Name</th>

                            </tr>
                        </thead>


                        <tbody> 
                            <?php
                                                        while ($row = db_fetch_array($topStudQ)) {
                                                            $idd = $row['id'];
                            ?>
                                                    <tr data-href="view_order.php?id=<?= $idd ?>" class='clickable-row' >
                                                        <td><?= $row['quantity'] ?></td>
                                                        <td><?= $row['school_name'] ?></td>
                                                        <td><?= getSingleresult("SELECT city from cities where id='".$row['city']."'"); ?></td>
                                                        <td><?= $row['stage'] ?></td>
                                                        <td><?= $row['r_name'] ?></td>
                                                        </tr> 
                            <?php                              
                            } 
                            ?>


                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</div>



</div>       
              
</div>
    </div>
        </div>

        <?php     include('includes/footer.php') ?>

<!-- JAVASCRIPT -->

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/variable-pie.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

        <script>
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }
            const names = ['Data Received','Pending', 'Qualified', 'Unqualified', 'On-hold', 'Re-submission'];

function getRandomData() {
    const currentDate = new Date();
    const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    const formattedFirstDay = formatDate(firstDayOfMonth);
    const formattedLastDay = formatDate(lastDayOfMonth);
//     const baseURL = window.location.origin;
// console.log(baseURL);
    const data = [];
    dataS = [<?= $subMittedCount ?>,<?= $pendingAccounts ?>,<?= $qualifiedAccounts ?>,<?= $cancelledAccounts ?>,<?= $onholdAccounts ?>,<?= $resubAccounts ?>];
    for (let i = 0; i < 6; i++) {
        if(names[i] == 'Re-submission'){
            status = '&status[]=Undervalidation'
        }else if(names[i] == 'Data Received'){
            status = '';
        }else if(names[i] == 'Pending'){
            status = '&status[]=Pending';
        }else if(names[i] == 'Qualified'){
            status = '&status[]=Approved';
        }else if(names[i] == 'Unqualified'){
            status = '&status[]=Cancelled';
        }else if(names[i] == 'On-hold'){
            status = '&status[]=On-Hold';
        }
        // data.push([names[i],dataS[i]]);
        data.push({
                    name: names[i],
                    y: dataS[i],
                    url: 'search_orders.php?dtype=&d_from='+ formattedFirstDay +'&d_to='+ formattedLastDay +status
                }); 
    }

    return data;
}

const chart = Highcharts.chart('Stage', {

    chart: {
        type: 'bar',
        marginLeft: 130
    },

    title: {
        text: 'Monthly Data Counter'
    },
    credits: {
            enabled: false
        },

    yAxis: {
        title: {
            text: 'Values'
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
        data: getRandomData(),
        point: {
                    events: {
                        click: function () {
                            window.open(this.url, '_blank');
                        }
                    }
                }
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
<script>
    const charts = [],
    containers = document.querySelectorAll('#trellis '),
    datasets = [{
   
        name: 'Stage',
        data: [<?= $stageA[0] ?>,<?= $stageA[1] ?>,<?= $stageA[2] ?>,<?= $stageA[3] ?>,<?= $stageA[4] ?>,<?= $stageA[5] ?>,<?= $stageA[6] ?>,<?= $stageA[7] ?>,<?= $stageA[8] ?>,<?= $stageA[9] ?>],
        url: ['<?= $stageUrl[0] ?>','<?= $stageUrl[1] ?>','<?= $stageUrl[2] ?>','<?= $stageUrl[3] ?>','<?= $stageUrl[4] ?>','<?= $stageUrl[5] ?>','<?= $stageUrl[6] ?>','<?= $stageUrl[7] ?>','<?= $stageUrl[8] ?>','<?= $stageUrl[9] ?>'],

    }
    ];

datasets.forEach(function (dataset, i) {
    charts.push(Highcharts.chart(containers[i], {

        chart: {
            type: 'bar',
            marginLeft: i === 0 ? 150 : 10
        },

        title: {
            text: dataset.name,
            align: 'center',
            x: i === 0 ? 10 : 0
        },

        credits: {
            enabled: false
        },

        xAxis: {
            categories: ['Data', 'Potential', 'Demo', 'Quote', 'Follow-up', 'Nego', 'Commit', 'Po-CIF Issued', 'Advance Payment', 'Billing'],
            labels: {
                enabled: i === 0
            }
        },

        yAxis: {
            allowDecimals: false,
            title: {
                text: null
            },
            min: 0,
            max: 60
        },

        legend: {
            enabled: false
        },

        series: [{
            name: dataset.name,
            data: dataset.data.map(function (value, index) {
                return {
                    y: value,
                    url: dataset.url[index]
                };
            }),
            cursor: 'pointer', // Change cursor on hover to indicate clickable
            point: {
                events: {
                    click: function() {
                        // Redirect to the URL corresponding to the clicked bar
                        window.open(this.url, '_blank');
                    }
                }
            }
        }]

    }));
});

</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const tableRows = document.querySelectorAll("table tbody tr");
    tableRows.forEach(row => {
      row.addEventListener("click", function() {
        const url = this.getAttribute("data-href");
        if (url) {
          window.open(url, "_blank");
        }
      });
    });
  });
</script>
