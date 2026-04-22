<?php

include('includes/header.php');
include_once('helpers/dashboard_helper.php');
$helper = new DataController;
$user_id = $_SESSION['user_id'];
$fTime = $_GET['ftime'] ? $_GET['ftime'] : NULL;
$tTime = $_GET['ttime'] ? $_GET['ttime'] : NULL;
$requestUser = $_GET['user'] ? $_GET['user'] : NULL;


$from = $_GET['ftime'] ?? null;
$to   = $_GET['ttime'] ?? null;

if ($from && $to) {
    $dateRangeLabel = date('d M-Y', strtotime($from)) . ' To ' . date('d M-Y', strtotime($to));
} elseif ($from || $to) {
    $date = $from ?: $to;
    $dateRangeLabel = date('d M-Y', strtotime($date));
} else {
    $dateRangeLabel = date('d M-Y');
}


$months = [
    1  => 'January',
    2  => 'February',
    3  => 'March',
    4  => 'April',
    5  => 'May',
    6  => 'June',
    7  => 'July',
    8  => 'August',
    9  => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December'
];




$query = "select * from users where 1 ".$con." and user_type IN ('CLR', 'DA') and role != 'PARTNER'";

// Final order
$query .= " ORDER BY name ASC";

// Execute query
$sql = db_query($query);

$rowValue = [];
$allUsersFilter = [];
                    
$rowValue[] = ['id'=>0,'name'=>'Total Activity Report'];
$allUsersFilter[] = ['id'=>0,'name'=>'Total Activity Report'];
               


while($data=db_fetch_array($sql)){
    
    $allUsersFilter[] = $data;

  if($requestUser && $requestUser == $data['id']){
    $rowValue[] = $data;
  }elseif($requestUser != 0 && $requestUser == ""){
    $rowValue[] = $data;
  }elseif($requestUser == "all" || !isset($_GET['user'])){
    $rowValue[] = $data;
  }

}



?> 
<style>
  body[data-layout=horizontal] .page-content {
    padding-bottom: 20px;
  }

  /* .table th:first-child {
    width: 50px
  }

 .table th:not(:nth-child(2)), .table td:not(:nth-child(2)) {
    text-align: center;
} */

.dropdown-sm .dropdown-menu1 {
    max-width: 400px;
}

.table-title {
    font-size: 18px;
    font-weight: 600;
    text-align: center;
}
  
</style>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">   
                <div class="row">
                    <div class="col mb-4">
                        <div class="page-title-box pl-0 d-flex align-items-center justify-content-between dashboard-title-box">
                             <?php if($_SESSION['user_type'] == 'ADMIN'): ?> 
                            <h4 class="mb-0 font-size-18"><?= $dateRangeLabel ?>  Activity Report</h4>
                        <?php endif ?>
                            <div class="page-title-right"></div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                        <div class="dropdown dropdown-sm">

                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">
                               
                                <form class="form-horizontal" role="form">                                    
                                    <div class="form-group">
                                        <label for="from_time">From Range</label>
                                        <input type="date" id="from_time" class="form-control" name="ftime" value="<?= $fTime ?>"> 
                                    </div>

                                    <div class="form-group">
                                        <label for="to_range">To Range</label>
                                        <input type="date" id="to_range" class="form-control" name="ttime"  value="<?= $tTime ?>"> 
                                    </div>
                                    <?php if($_SESSION['user_type'] == 'ADMIN'): ?>
                                    <div class="form-group">
                                        <label for="users">User</label>
                                        <select class="form-control" name="user">
                                            <option value="all">Select User</option>
                                           <?php foreach($allUsersFilter as $user): ?>
                                           <option <?= $requestUser && $requestUser == $user['id'] ? 'selected' : '' ?> value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                                           <?php endforeach ?>
                                        </select>
                                    </div>
                                <?php endif; ?>

                                    <div class="col-md-12 text-right mt-2">
                                        <button type="submit" class="btn btn-primary font-14"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                        <button type="button" class="btn btn-danger" onclick="clearFilterDateRange()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">    
                <?php if($_SESSION['user_type'] != 'ADMIN'): ?>   

                <?php

                // Fetches all KRA data types except those related to Call Logs.
                $kraTargets = $helper->getTodayModifyNamesByUserIdKRA($user_id,$fTime,$tTime);

                // Retrieves only KRA records related to Call Logs.
                $callLogKraTarget = $helper->getDailyKRAReportByCallLog($user_id,$fTime,$tTime);

                $valData['id'] = $_SESSION['user_id'];

                ?>

                    <div class="col-md-6">
                        <h3 class="table-title"><?= $dateRangeLabel ?> Activity Report</h3>
                        <div class="table-responsive">
                            <table class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Activity </th>
                                    
                                        <th>Achived</th>
                                    </tr>
                                </thead>
                                <tbody>
                                       <?php foreach ($callLogKraTarget as $i => $callLog) :
                                        $callLeadId = $helper->getActivityLeadIDsCallLog($valData['id'],$fTime,$tTime,$i);
                                       
                                        ?>
                                            <tr>
                                              <td><?= $i ?></td>
                                              <td><a target="_blank" href="filtered_leads_list.php?ids=<?= rtrim($callLeadId, ',') ?>"><?= $callLog ?></a></td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <?php foreach ($kraTargets as $key => $target) : 
                                        $leadID = $helper->getLeadIDsFromActivity($valData['id'],$fTime,$tTime,$key);
                                                                               
                                        ?>
                                            <tr>
                                              <td><?= $key ?></td>
                                              <td>                                                  
                                                  <a target="_blank" href="filtered_leads_list.php?ids=<?= rtrim($leadID['leadIDs'], ',') ?>"> 
                                                    <?= $leadID['count'] ?></a>
                                              </td>
                                             </tr>
                                        <?php endforeach; ?>

                                        <?php if(count($callLogKraTarget) == 0 && count($kraTargets) == 0): ?>
                                        <tr>
                                            <td colspan="2" style="text-align: center;">No Activity Recorded</td>
                                        </tr>
                                        <?php endif; ?>
                                   
                                    </tbody>
                            </table>
                        </div>
                    </div>
               
                <?php 

                elseif($_SESSION['user_type'] == 'ADMIN'):    

                ?>

                    <?php foreach($rowValue as $valData):
                    
                    // Fetches all KRA data types except those related to Call Logs.
                    $kraTargets = $helper->getTodayModifyNamesByUserIdKRA($valData['id'],$fTime,$tTime);

                    // Fetch all Activity Leads Id
                    $activityLeadIDs = $helper->getTodayModifyNamesByUserIdKRA($valData['id'],$fTime,$tTime,true);


                    $callLogKraTarget = $helper->getDailyKRAReportByCallLog($valData['id'],$fTime,$tTime);
   
                     ?>              
                    <div class="col-md-4 mb-4">
                        <h3 class="table-title"><?= $valData['name'] ?></h3>
                        
                        <div class="table-responsive">
                            <table class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Activity </th>
                                        <th>Achived  </th>
                                    </tr>
                                </thead>
                                <tbody>
                                       <?php foreach ($callLogKraTarget as $i => $callLog) : 
                                        $callLeadId = $helper->getActivityLeadIDsCallLog($valData['id'],$fTime,$tTime,$i);
                                         ?>
                                            <tr>
                                              <td><?= $i ?></td>
                                              <td><a target="_blank" href="filtered_leads_list.php?ids=<?= rtrim($callLeadId, ',') ?>"><?= $callLog ?></a></td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <?php foreach ($kraTargets as $key => $target) : 
                                        $leadID = $helper->getLeadIDsFromActivity($valData['id'],$fTime,$tTime,$key);
                                        ?>                                 
                                      
                                            <tr>
                                              <td><?= $key ?></td>
                                              <td><a target="_blank" href="filtered_leads_list.php?ids=<?= rtrim($leadID['leadIDs'], ',') ?>"> <?= $leadID['count'] ?></a>
                                              </td>

                                            </tr>
                                        <?php endforeach; ?>

                                        <?php if(count($callLogKraTarget) == 0 && count($kraTargets) == 0): ?>
                                        <tr>
                                            <td colspan="2" style="text-align: center;">No Activity Recorded</td>
                                        </tr>
                                        <?php endif; ?>
                                   
                                    </tbody>
                                
                                </table>
                        </div>
                    </div>


                    <?php
                     
                     endforeach;

                 endif;
                   
                    ?>
                     


                </div>

            </div>
        </div>

      
    </div>      
  </div>
</div>
<!-- end main content--> 
 <?php include('includes/footer.php') ?>

