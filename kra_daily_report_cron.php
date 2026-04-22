<?php

// include('includes/header.php');
// include('helpers/DataController.php');
// include('helpers/dashboard_helper.php');
$helper = new DataController;
$user_id = $userID;
$fTime = $FyesterDay;
$tTime = $TyesterDay;
$formatedDateabel = $formatedDate;
$requestUser = $_GET['user'] ? $_GET['user'] : NULL;

$user_type = $userCheck;


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




$query = "select * from users where 1 ".$con." and user_type IN ('CLR') and role != 'PARTNER' AND status = 'Active'";

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
    p{
        margin-bottom: 0 !important;
    }
</style>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">   
                <!-- <div class="row">
                    <h4 style="text-align: center; font-size: 20px; font-weight: bold; margin-bottom: 25px;">Activity Report <?= $formatedDateabel ?> </h4>
                </div> -->

                <div style="width: 100%; margin: auto;font-family: Arial, sans-serif; display: flex; flex-wrap: wrap;">  
                <?php if($user_type != 'ADMIN'): ?>   

                <?php
                $kraTargets = $helper->getTodayModifyNamesByUserIdKRA($user_id,$fTime,$tTime);
                $callLogKraTarget = $helper->getDailyKRAReportByCallLog($user_id,$fTime,$tTime);
                $valData['id'] = $_SESSION['user_id'] ? $_SESSION['user_id'] : $user_id;
                ?>

                        <div style="width: 50%; flex: 0 0 50%; padding-right: 5px; box-sizing: border-box;">
                            <h3 style="text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 15px;"><?= $formatedDateabel ?> Activity Report</h3>
                            <table  style="border-collapse: collapse; margin: auto;  margin-bottom: 20px; font-size: 14px; width: 100%; " cellspacing="0">
                                <thead>
                                    <tr>
                                        <th style="background: rgb(239, 214, 198);padding: 12px 15px;">Activity</th>
                                        <th style="background: rgb(239, 214, 198);padding: 12px 15px;">Achived</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($callLogKraTarget as $i => $callLog): ?>
                                        <tr>
                                            <td style="border: 1px solid #ddd; padding: 10px 15px;"><?= $i ?></td>
                                            <td style="border: 1px solid #ddd; padding: 10px 15px;"><?= $callLog ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php foreach ($kraTargets as $key => $target): 
                                        $leadID = $helper->getLeadIDsFromActivity($valData['id'],$fTime,$tTime,$key);
                                    ?>
                                        <tr>
                                            <td style="border: 1px solid #ddd; padding: 10px 15px;"><?= $key ?></td>
                                            <td style="border: 1px solid #ddd; padding: 10px 15px;">                                        
                                            <?= $leadID['count'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php if(count($callLogKraTarget) == 0 && count($kraTargets) == 0): ?>
                                        <tr>
                                            <td colspan="2" style="border: 1px solid #ddd; padding: 10px 15px; text-align: center;">No Activity Recorded</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
               
                <?php else: ?>    

                    <?php foreach($rowValue as $valData):
                        $kraTargets = $helper->getTodayModifyNamesByUserIdKRA($valData['id'],$fTime,$tTime);
                        $activityLeadIDs = $helper->getTodayModifyNamesByUserIdKRA($valData['id'],$fTime,$tTime,true);
                        $callLogKraTarget = $helper->getDailyKRAReportByCallLog($valData['id'],$fTime,$tTime);
                    ?>
                    
                    
                    <div style="width: 50%; flex: 0 0 50%; padding-left: 5px; box-sizing: border-box;">
                        <h3 style="text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 15px;"><?= $valData['name'] ?></h3>
                        <table style="border-collapse: collapse; margin: auto;  margin-bottom: 20px; font-size: 14px; width: 100%; " cellspacing="0" >
                            <thead style="text-align: left">
                                <tr>
                                    <th style="background: rgb(239, 214, 198);padding: 12px 15px;">Activity</th>
                                    <th style="background: rgb(239, 214, 198);padding: 12px 15px;">Achived</th>
                                </tr>
                            </thead>
                            <tbody>

                                    <?php foreach ($callLogKraTarget as $i => $callLog): ?>
                                                            <tr>
                                                                <td style="border: 1px solid #ddd; padding: 10px 15px;"><?= $i ?></td>
                                                                <td style="border: 1px solid #ddd; padding: 10px 15px;"><?= $callLog ?></td>
                                                            </tr>
                                    <?php endforeach; ?>

                                    <?php foreach ($kraTargets as $key => $target): 
                                                            $leadID = $helper->getLeadIDsFromActivity($valData['id'],$fTime,$tTime,$key);
                                                        ?>
                                                            <tr>
                                                                <td style="border: 1px solid #ddd; padding: 10px 15px;"><?= $key ?></td>
                                                                <td style="border: 1px solid #ddd; padding: 10px 15px;"><?= $leadID['count'] ?></td>
                                                            </tr>
                                    <?php endforeach; ?>

                                    <?php if(count($callLogKraTarget) == 0 && count($kraTargets) == 0): ?>
                                                            <tr>
                                                                <td style="border: 1px solid #ddd; padding: 10px 15px;" colspan="2" style="text-align: center;">No Activity Recorded</td>
                                                            </tr>
                                    <?php endif; ?>

                            
                            </tbody>
                        </table>
                    </div>
 

                        
                    <?php endforeach; ?>

                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>      
  </div>
</div>