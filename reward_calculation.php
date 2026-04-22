<?php include('includes/include.php'); 

function getStartAndEndDate($week, $year)
{
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d');

    return $ret;
}




$points=36000;
$rank=array();
$rank['0']=$points*22/100;
$rank['1']=$points*17/100;
$rank['2']=$points*12/100;
$rank['3']=$points*10/100;
$rank['4']=$points*9/100;
$rank['5']=$points*8/100;
$rank['6']=$points*7/100;
$rank['7']=$points*6/100;
$rank['8']=$points*5/100;
$rank['9']=$points*4/100;

//if($_GET['week'] && $_GET['dates'])
//{
$week=date('W');

$weekarray = getStartAndEndDate($week, date('Y'));
    //print_r($weekarray);
    
$week_start =  date('Y-m-d', strtotime($weekarray['week_start']));
$week_end = date('Y-m-d', strtotime($weekarray['week_end']));

//$points_date=week_range($_GET['dates']);

$reward_end_day = date('Y-m-d', strtotime('-1 day',strtotime($weekarray['week_end'])));
//print_r($reward_end_day);

if(date('Y-m-d')==$reward_end_day && date('h:i a')=='11:59 pm'){

$sql_z = db_query("select users.id,users.name,users.team_id,sum(user_points.point) as total,user_points.user_id from users left join user_points on users.id=user_points.user_id left join tbl_lead_product as tp on user_points.lead_id=tp.lead_id where (users.team_id!='' and users.team_id!=45) and (users.user_type='USR' or users.user_type='MNGR' ) and user_points.week_number=".$week." and YEAR(user_points.created_date)='" . date('Y') . "' and user_points.point!=0 and tp.product_type_id in (1,2) GROUP by user_points.user_id order by total Desc limit 10");

$i=0;

while($data = db_fetch_array($sql_z))
{
    $insert = db_query("insert into points_rewards  (`user_id`, `week`, `date_from`, `date_to`, `reward_points`) values ('".$data['id']."','".$week."','".$week_start."','".$week_end."','".$rank[$i]."')");

$i++;

echo "added<br>";
}
}
