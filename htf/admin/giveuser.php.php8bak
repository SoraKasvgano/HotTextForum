<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=giveuser";
$timestamp = time();

$topname="节日送礼";
$introduce="碰上节日,给大家送点礼物";
$textcontent="你好，\$htfid，今天是节日，本论坛全体班竹祝你节日愉快！我们特地赠送你金钱：\$money 、威望：\$rvrc 作为节日礼物，请笑纳。祝你玩得愉快~";
$extra="<tr bgcolor=$b valign=middle><td width=20% align=right value=$value><span class=bold>赠送威望：</span></td><td width=80%><input type=text name=send_rvrc></td></tr><tr bgcolor=$b valign=middle><td width=20% align=right value=$value><span class=bold>赠送金钱：</span></td><td width=80%><input type=text name=send_money></td></tr>";
if ($action!="send")
{
	$ifecho[send_index1]="";$ifecho[send_index2]="";
	eval("dooutput(\"".gettmp('admin_send')."\");");
}
elseif ($action=="send") 
{		
	if (empty($subject) || empty($text))
	{
		$ifecho[send_err1]="";$ifecho[send_err2]="";					
		eval("dooutput(\"".gettmp('admin_send')."\");");
	}	
	if (empty($percount)) $percount=100;
	if (empty($step)) $step=1;
	if (empty($sendto)) $sendto='allusers';
	if ($sendto=='allusers')
	{
		$db=opendir("$userpath/");
		while (false!==($userfile=readdir($db))) {
			if (($userfile!=".") && ($userfile!="..")&& ($userfile!=".php")) {
				$userfilename=explode(".",$userfile);
				$userarray[]=$userfilename[0];
			}
		}
		closedir($db);
	}
	elseif ($sendto=='alladmins') 
	{
		if (file_exists('data/admin.php'))
		{
			$admindb=openfile('data/admin.php');
			$count=count($admindb);
			for ($i=0; $i<$count; $i++)
			{
				$detail=explode("|", trim($admindb[$i]));
				$userarray[]=$detail[2];//第二个数据段
			}
		}
		$userarray = array_unique ($userarray);//移除数组中重复的值
	}
	$count=count($userarray);
	if ($count>($step*$percount)) 
	{
		$lastpage=0;
		$max=$step*$percount;
	}
	else
	{
		$lastpage=1; 
		$max=$count;
	}
	$min=($step-1)*$percount;
	for ($i=$min; $i<$max; $i++) 
	{
		unset($msg);
		$usertosend=$userarray[$i];
		$memberfile=$userpath."/".$usertosend."."."php";
		if($usertosend && file_exists($memberfile))
		{
			$userdb=explode("|",readover("$memberfile"));
			$userdb[17]=$userdb[17]+$send_rvrc*10;
			$userdb[18]=$userdb[18]+$send_money;			
			writeover("$memberfile",implode("|",$userdb));
			if (file_exists("data/$msgpath/{$usertosend}1.php"))
				$msg=openfile("data/$msgpath/{$usertosend}1.php"); 
			else 
				$msg[0]="";
			$subject=stripslashes(safeconvert($subject));
			$sendmessage=$text;
			$sendmessage=stripslashes(safeconvert($sendmessage));
			$sendmessage=str_replace("\$money",$send_money,$sendmessage);
			$sendmessage=str_replace("\$htfid",$userdb[1],$sendmessage);
			$sendmessage=str_replace("\$rvrc",$send_rvrc,$sendmessage);
			$new="<?die;?>|系统信息|$subject|$timestamp|$sendmessage|0|\n";
			$oldcount=count($msg);
			$old=implode("",$msg);
			writeover("data/$msgpath/{$usertosend}1.php",$new.$old);
		}
	}
	$step++;
	if ($lastpage) 
	{	
		$ifecho[send_foru1]="";$ifecho[send_foru2]="";
	}
	else 
	{
		$ifecho[send_foru3]="";$ifecho[send_foru4]="";
	}
	eval("dooutput(\"".gettmp('admin_send')."\");");
}
?>