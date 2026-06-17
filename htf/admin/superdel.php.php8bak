<?php

!function_exists('adminmsg') && exit('Forbidden');

$ifecho=array(
	"del_msg1"	   => "<!--","del_msg2"     => " -->",
	"del_men1"	   => "<!--","del_men2"     => " -->"
	);
@set_time_limit(0);
if($action==msgdel)
{
	$basename="admin.php?adminjob=superdel&action=msgdel";
	if (!$_POST['job']) 
	{
		$ifecho[del_msg1]=$ifecho[del_msg2]="";
		eval("dooutput(\"".gettmp('superdel')."\");");
	}
	elseif ($_POST['job']=="rec") 
	{	
		$dir='data'."/".$msgpath."/";
		$db=opendir("$dir");
		$count=0;
		while (false!==($msgfile=readdir($db)))
		{
			if(strpos($msgfile,"1.php"))
			{
				if(@unlink("$dir$msgfile"))
				{
					$count++;
					$msg.="({$count}) $msgfile <font color=green>删除成功</font><BR>";
				}
				else
				{
					$msg.="$msgfile <font color=green>删除失败</font><BR>";
				}
			}
		}
		closedir($db);
	}
	elseif ($_POST['job']=="snd")
	{	
		$dir='data'."/".$msgpath."/";
		$db=opendir("$dir");
		$count=0;
		while (false!==($msgfile=readdir($db)))
		{
			if(strpos($msgfile,"2.php"))
			{
				if(@unlink("$dir$msgfile"))
				{
					$count++;
					$msg.="({$count}) $msgfile <font color=green>删除成功</font><BR>";
				}
				else
				{
					$msg.="$msgfile <font color=green>删除失败</font><BR>";
				}
			}
		}
		closedir($db);
	}
	elseif ($_POST['job']=="readrec") 
	{	
		$dir='data'."/".$msgpath."/";
		$db=opendir("$dir");
		$count=0;
		while (false!==($msgfile=readdir($db)))
		{
			if($offset=strpos($msgfile,".php"))
			{
				$username=substr($msgfile,0,$offset-1);
				if(!file_exists("$userpath/$username.php")){
					if(@unlink("$dir$msgfile")){
						$count++;
						$msg.="({$count}) $msgfile <font color=green>用户不存在删除成功</font><BR>";
					}
				}
			}
			if(strpos($msgfile,"1.php"))
			{
				if(msg_read($msgfile))
				{
					if(@unlink("$dir$msgfile"))
					{
						$count++;
						$msg.="({$count}) $msgfile <font color=green>删除成功</font><BR>";
					}
					else
						$msg.="$msgfile <font color=green>删除失败</font><BR>";	
				}
			}
		}
		closedir($db);
	}
	elseif ($_POST['job']=="delmsg") 
	{	
		$htf_name=explode(",",$htf_delname);
		$couter=count($htf_name);
		for($i=0;$i<$couter;$i++)
		{
			if(file_exists("data/$msgpath/{$htf_name[$i]}1.php"))
			{
				if(@unlink("data/$msgpath/{$htf_name[$i]}1.php"))
				{	
					$count1++;
					$msg.="({$count1}) $htf_name[$i] <font color=green>删除成功</font><BR>";
				}
				else
					$msg.="$htf_name[$i] <font color=green>删除失败</font><BR>";
			}
			if(file_exists("data/$msgpath/{$htf_name[$i]}2.php"))
			{
				if(@unlink("data/$msgpath/{$htf_name[$i]}2.php"))
				{
					$count2++;	
					$msg.="({$count2}) $htf_name[$i] <font color=green>删除成功</font><BR>";
				}
				else
					$msg.="$htf_name[$i] <font color=green>删除失败</font><BR>";
			}
		}
	}
	if(!$msg)
		$msg="没有找到符合要求的数据记录";
	adminmsg($msg);
}
if($action==men)
{
	$basename="admin.php?adminjob=superdel&action=men";
	if($_POST['K_del_user']==1){
		foreach($K_user as $user){
			if($user && in_array($user,$K_array)){
				if(@unlink("./$userpath/$user"))
				{
					$count++;
					if($offset=strpos($user,".php"))
					{
						$name=substr($user,0,$offset);
						$msg.="$name 成功删除<br>";
						@unlink("./data/$msgpath/{$name}1.php");
						@unlink("./data/$msgpath/{$name}2.php");
					}
				}else{
					$msg.="$user <font color=red>删除失败</font><br>";
				}
			}
		}
		adminmsg($msg."共删除 $count 个符合要求的会员");
	}
	elseif(!$_POST['job']) 
	{
		$ifecho[del_men1]=$ifecho[del_men2]="";
		eval("dooutput(\"".gettmp('superdel')."\");");
	}
	elseif($_POST['job'])
	{	
		$dir=$userpath."/";
		$db=opendir("./$dir");
		$count=0;
		while (false!==($userfile=readdir($db)))
		{
			if($userfile!="." && $userfile!="..")
			{//if2
				$userarray=explode("|",readover("$dir$userfile"));
				if($userarray[5]!='manager')/*管理员不允许删除*/
				{
					$K_del='';
					$userarray[8]=(int)date("Ymd",$userarray[8]);
					$userarray[20]=(int)date("Ymd",$userarray[20]);
					if ((empty($postnum) || $userarray[16]<$postnum) 
						&& (empty($regdate) || $userarray[8]<$regdate) 
						&& (empty($lastlogin) || $userarray[20]<$lastlogin)
						&& (empty($userip) || $userarray[29]==$userip)
						&& (empty($E_check) || $userarray[27]!=1))
					{
						
						if($postnum || $regdate || $lastlogin || $userip || $E_check) $K_del='Y';
					}
					if($K_del=='Y'){
						$count++;
						$K_db.="<tr>
						<input type=hidden name='K_user[]' value=$userfile>
						<td bgcolor=$b><a href=admin.php?adminjob=setuser&action=view&setusername=".rawurlencode($userarray[1]).">$userarray[1]</a></td>
						<td bgcolor=$b>$userarray[16]</td>
						<td bgcolor=$b>$userarray[8]</td>
						<td bgcolor=$b>$userarray[20]</td>
						<td bgcolor=$b>$userarray[29]</td>
						<td bgcolor=$b><input type='checkbox' name='K_array[]' value=$userfile checked></td>
						</tr>";
					}
				}
			}//if2
		}//while
		closedir($db);
		eval("dooutput(\"".gettmp('deluser')."\");");
	}
}
function msg_read($filename)
{
	global $msgpath;
	$txt=openfile("data/$msgpath/$filename");
	$count=count($txt);
	for($i=0;$i<$count;$i++)
	{
		$detail=explode("|",$txt[$i]);
		if($detail[5]==1) 
		return 1;
	}
}