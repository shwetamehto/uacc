<?php
require_once(dirname(__FILE__)."/"."dbbase.php");

header('Content-type: text/plain');
header("Cache-Control: no-cache, must-revalidate"); 

$ret['success'] = 0;
 $location = $_GET['m'];

if($location=="") {
	$ret['desc'] = "missing param: building_id";
	echo json_encode($ret);
	exit;
}
$location = intval($location, 10);


    $thisdb = new dbbase();
try{
    $thisdb -> connect();
}catch(Exception $e){
	$ret['desc'] = $e -> getMessage();
	echo json_encode($ret);
    exit();
}

    $thisdb -> select_db();
/*
SELECT first_name, last_name, time, content status_id, member_id 
FROM 
(select * From Status
WHERE building_id = 2 
) as s 
LEFT JOIN 
Member as m
On s.member_id = m.member_id 


*/
// 
$sql = "SELECT first_name, last_name, time, content, s.status_id, s.member_id ".
"FROM ".
"(select * From Status ".
"WHERE building_id = $location".
") as s LEFT JOIN Member as m On s.member_id = m.member_id ".
"ORDER BY s.status_id desc ".
"LIMIT 0 , 70 ";
// 
  // $ret['debug'] = $sql;
	$arr = $thisdb -> select($sql);
    $thisdb -> close();
    $thisdb = null ;
	// print_r($arr);

date_default_timezone_set('US/Eastern');


	foreach ($arr as $key=>$val)   {  
		$arr[$key]['time'] = date('m-d G:i:s', strval($val['time']));
	}

 	$ret['success'] = 1;
	$ret['data'] = $arr;
	echo json_encode($ret);
exit;
?>