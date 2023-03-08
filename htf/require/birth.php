<?php

!function_exists('readover') && exit('Forbidden');

//生日统计函数
if($bbsbirthcontrol<$tdtime||$birth=='tongji')
{
	$bbsbirthman='';
	$bbsbirthcontrol=$tdtime;
	$get_today=date("Y-m-j",$timestamp);
	$get_today_array=explode("-",$get_today);
	$todayyear=$get_today_array[0];
	$gettoday=(int)$get_today_array[1]."/".$get_today_array[2];
	$userdbarray=explode("\n",readover("data/userarray.php"));
	$count=count($userdbarray);
	if($count>10000) @set_time_limit(0);
	for ($i=1;$i<$count;$i++)
	{
		if (!trim($userdbarray[$i])) continue;
		$userfile=$userpath."/".trim($userdbarray[$i])."."."php";
		if (!file_exists($userfile)) continue;
		$userarray=explode("|",readover($userfile));
		$userbirtharray=explode("/",$userarray[21]);
		$user_birth=$userbirtharray[1]."/".$userbirtharray[2];
		if ($user_birth==$gettoday)
		{
			$bbsbirthman.=$userarray[1].",";
		}
	}
	$writebbsatcdb="<?die;?>|$bbstpc|$bbsatc|$bbstoday|$bbsyestoday|$bbsmost|$bbspostcontrol|$bbsbirthcontrol|$bbsstar|$bbsrich|$bbslucher|$bbsbirthman|";
	writeover("data/bbsatc.php",$writebbsatcdb);
}
$birthmen=explode(',',$bbsbirthman);
$birthnum=count($birthmen)-1;
if($birthnum==0) 
	$index_birth.= "今天论坛里没有人过生日!";
else
{
	$t=getdate($timestamp); 
	$todayyear=$t['year'];
	for($i=0;$i<$birthnum;$i++)
	{
		if(file_exists("$userpath/$birthmen[$i].php"))
		{
			$userarray=explode("|",readover("$userpath/$birthmen[$i].php"));
			$userbirtharray=explode("/",$userarray[21]);
			$age=$todayyear-$userbirtharray[0];
			if ($userarray[7]==1) 
				$ageinfo="先生今天".$age."周岁了";
			elseif ($userarray[7]==2) 
				$ageinfo="小姐今天".$age."周岁了";
			else 
				$ageinfo="今天".$age."周岁了";
			$index_birth.="<a href=usercp.php?action=show&username=".rawurlencode($userarray[1])." title='{$userarray[1]}{$ageinfo},生日快乐!'>$userarray[1]</a>&nbsp;";
		}
	}
}
?>