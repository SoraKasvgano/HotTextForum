<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=sendmsg";
$topname="短消息批量发送 ";
$introduce="如非必要，请不要利用本程序乱用此功能";
$textcontent="你好，欢迎光临http://www.htf.1m.cn.";
$extra="";
$timestamp = time();

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
				$userarray[]=$detail[2];
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
		$usertosend=trim($userarray[$i]);
		$memberfile=$userpath."/".$usertosend."."."php";
		list($dir_fb,$dir_name,$dir_pwd,$dir_email)	= explode("|",readover($memberfile));
		if($usertosend && file_exists($memberfile))
		{
			if (file_exists("data/$msgpath/{$usertosend}1.php"))
				$msg=openfile("data/$msgpath/{$usertosend}1.php"); 
			else 
				$msg[0]="";
			$subject=stripslashes(safeconvert($subject));
			$sendmessage=$text;
			$sendmessage=stripslashes(safeconvert($sendmessage));
			$sendmessage=str_replace("\$email",$dir_email,$sendmessage);
			$sendmessage=str_replace("\$htfid",$dir_name,$sendmessage);
			$sendmessage=str_replace("\$password",$dir_pwd,$sendmessage);
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