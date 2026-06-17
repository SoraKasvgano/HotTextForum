<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=addadmin";
if(file_exists("data/admin/$admin_name.php")&&$admin_name!=$manager)/*论坛创始人不受任何限制*/
{
	if($checkpower<>1)
	{
		adminmsg("权限错误 ,你不是超级管理员,只有论坛的创始者才拥有此权限!");
	}
}
if (empty($action))
{
	$oldinfo='';
	$db=opendir("data/admin/");
	while (false!==($userfile=readdir($db)))
	{
		if (($userfile!=".") && ($userfile!="..") && ($userfile!="") && strpos($userfile,".php"))
		{
			$array=explode("|",readover("data/admin/$userfile"));
			unset($select);unset($unselect);
			if($array[3]==0) 
				$select='selected'; 
			else 
				$unselect='selected';
			eval("\$oldinfo.=\"".gettmp('addadmin_1')."\";");
		}
	}
	closedir($db);
	eval("dooutput(\"".gettmp('addadmin_2')."\");");
}
elseif($action=="edit")
{
	if(file_exists("data/admin/".$oldadmin.".php"))
	{
		$array=explode("|",readover("data/admin/$oldadmin.php"));
		if($oldpwd=="已md5加密")
			$newadpwd=$array[2];
		else
			$newadpwd=md5($oldpwd);
		if ($oldadmin==$oldname)
		{
			$dbinfo="$array[0]|$array[1]|$newadpwd|$adminpower|$array[4]|";
			writeover("data/admin/$oldadmin.php",$dbinfo);
		}
		else
		{
			$dbinfo="$array[0]|$oldname|$newadpwd|$adminpower|$array[4]|";
			if(file_exists("$userpath/$oldname.php"))
				writeover("data/admin/$oldname.php",$dbinfo);
			else 
				adminmsg("管理员必须是论坛注册用户");
			if(file_exists("data/admin/$oldadmin.php")) 
				unlink("data/admin/$oldadmin.php");
		}
		adminmsg("成功修改管理员信息");
	}
	else
		adminmsg("发生未知错误");
}
elseif ($action=="addnew")
{
	$newadname = str_replace("\t","",$newadname);
	$newadname = str_replace("<","&lt;",$newadname);
	$newadname = str_replace(">","&gt;",$newadname);
	$newadname = str_replace("\r","<br>",$newadname);
	$newadname = str_replace("\n","",$newadname);
	$newadname = str_replace("|","│",$newadname);
	$newadname = str_replace("  "," &nbsp;",$newadname);
	$admindata=time();
	$newadpwd=md5($newadpwd);
	$dbinfo="<?die;?>|$newadname|$newadpwd|$adminpower|$admindata|";
	if(file_exists("$userpath/$newadname.php"))
	{
		if(file_exists("data/admin/$newadname.php"))
			adminmsg("此管理员已经存在");
		changegroup($newadname,'manager',Y);
		writeover("data/admin/$newadname.php",$dbinfo);
		adminmsg("成功添加管理员 $newadname");
	}
	else 
		adminmsg("管理员必须是论坛注册用户");
}
elseif ($action=="del") 
{
	if(file_exists("data/admin/$newadmin.php")) 
		unlink("data/admin/$newadmin.php");
	if(ifadmin($newadmin))
		changegroup($newadmin,'admin',Y);
	else
	{
		$postmagroup=getusergroup($newadmin,Y);
		changegroup($newadmin,$postmagroup,Y);
	}
	adminmsg("成功删除 $newadmin");
}
?>