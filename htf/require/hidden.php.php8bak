<?php

!function_exists('readover') && exit('Forbidden');

$newonline="<>|$timestamp|$onlineip|$fidwt|$tidwt|$groupid|$wherebbsyou|$acttime|$htfid|";
$newonline=str_pad($newonline,$db_olsize)."\n";
if(isset($offset) && checkinline("data/online.php",$offset,$htfid)){
	$inselectfile='N';
	writeinline("data/online.php",$newonline,$offset);
}else{
	$onlineuser=readover("data/online.php");
	if($offset=strpos($onlineuser,'|'.$htfid.'|')){
		$inselectfile='N';
		$offset=strpos($onlineuser,"\n",$offset-$db_olsize);$offset+=1;/*会员名不在开始需要转换指针*/
		writeinline("data/online.php",$newonline,$offset);
	}elseif($offset=strpos($onlineuser,str_pad(' ',$db_olsize)."\n")){
		writeinline("data/online.php",$newonline,$offset);
	}else{
		writeover("data/online.php",$newonline,"ab");
	}
}
?>