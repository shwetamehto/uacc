<?php
require_once(dirname(__FILE__)."/"."dbbase.php");
header('Content-type: text/plain');
header("Cache-Control: no-cache, must-revalidate"); 

$in_user = $_POST['u'];
$in_building = $_POST['m'];
$in_content = $_POST['c'];
$ret['success'] = 0;

if($in_user=="" || $in_building=="" || $in_content=="" ) {
	$ret['desc'] = "missing params";
	echo json_encode($ret);
	exit;
}

    $thisdb = new dbbase();
try{
    $thisdb -> connect();
}catch(Exception $e){
    $ret['desc'] = $e -> getMessage();
	echo json_encode($ret);
	exit;
}
    $thisdb -> select_db();

	$arr['content'] = $in_content;
	$arr['member_id'] = $in_user;
	$arr['building_id'] = $in_building;
	$arr['time'] = time();
	//$ret['debug'] = $arr;
	// $ret['sql'] = "INSERT INTO `Status`(`".implode('`,`', array_keys($arr))."`) VALUES('".implode("','", $arr)."')";

	$ret['desc'] = $thisdb -> insert("Status", $arr);

    $thisdb -> close();
    $thisdb = null ;
		
	$ret['success'] = 1;
	echo json_encode($ret);
exit;

?>