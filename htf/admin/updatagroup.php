<?php

!function_exists('adminmsg') && exit('Forbidden');

@set_time_limit(0);
$basename="admin.php?adminjob=updatagroup";
if(!$action)
{
	eval("dooutput(\"".gettmp('admin_cache')."\");");
}
elseif($action==updata)
{
	/*������� if else ��ԭ���ǵ���Ա����1000ʱ.cache.php��Ҫ�Ĳ����Ǹ��Ƕ�����ԭ�л����ϸ���.*/
	if(!$htfi){
		$htfi=0;
		$cachearray=array(
			'superadmin'=>"<?die;?>|superadmin|",
			'rzuser'=>"<?die;?>|rzuser|",
			'ctuser'=>"<?die;?>|ctuser|",
			'djuser'=>"<?die;?>|djuser|",
			'banned'=>"<?die;?>|banned|",
			'null1'=>"<?die;?>|null1|",
			'null2'=>"<?die;?>|null2|"
			);
	}else{
		$cache=explode("\n",readover("data/cache.php"));
		$cachearray=array(
			'superadmin'=>$cache[0],
			'rzuser'=>$cache[1],
			'ctuser'=>$cache[2],
			'djuser'=>$cache[3],
			'banned'=>$cache[4],
			'null1'=>$cache[5],
			'null2'=>$cache[6]
			);
	}
	$starwhile=$htfi;
	include "./data/level.php";
	$db=opendir("$userpath/");
	while (false!==($userfile=readdir($db))){
		if (($userfile!=".") && ($userfile!="..")&& ($userfile!=".php"))
		{				
			$whilecount++;
			if($htfi<$starwhile+1000 && $whilecount>=$starwhile)
			{
				$htfi++;
				updatagroup($userfile);
			}
		}
	}
	closedir($db);
	$cachedb=@implode("\n",$cachearray);
	writeover("data/cache.php",$cachedb);
	if($htfi<$whilecount)
	{
		$url="admin.php?adminjob=updatagroup&action=updata&htfi=$htfi";
		adminmsg("�ѻ������{$htfi}���û�,�����Զ�����ʣ�µĲ���,�����ĵȺ�......",1);
	}
	updataadmin($userfile);
	adminmsg("�� �� �� �� �� ��");
}
elseif($action==onlyone)
{
	$oldcache=openfile("data/cache.php");
	$cachearray=array(
			'superadmin'=>trim($oldcache[0]),
			'rzuser'=>trim($oldcache[1]),
			'ctuser'=>trim($oldcache[2]),
			'djuser'=>trim($oldcache[3]),
			'banned'=>trim($oldcache[4]),
			'null1'=>trim($oldcache[5]),
			'null2'=>trim($oldcache[6])
			);
	$userfile=$updataname.".php";
	updatagroup($userfile);
	$cachedb=@implode("\n",$cachearray);
	writeover("data/cache.php",$cachedb);
	adminmsg("��ɸ����û���Ա�ȼ�");
}
function updatagroup($userfile)
{
	global $cachearray,$userpath,$manager,$lpost;
	$userinfo=readover("$userpath/$userfile");
	$detail=explode("|",$userinfo);
	if(empty($detail[5])) $detail[5]=0;
	if(!ereg("^[0-9]{1,}",$detail[5])&&isset($cachearray[$detail[5]]))
	{
		$cachearray[$detail[5]].="$detail[1]|";
	}
	elseif(ereg("^[0-9]{1,}",$detail[5]))
	{
		if($detail[1]==$manager || file_exists("data/admin/$userfile"))
			changegroup($detail[1],'manager');
		elseif(ifadmin($detail[1]))
			changegroup($detail[1],'admin');
		else
		{
			if(!$lpost[$detail[5]]) $detail[5]=0;					
			while($detail[16]<$lpost[$detail[5]]||$detail[16]>$lpost[$detail[5]+1])
			{
				if($detail[16]<$lpost[$detail[5]])   $detail[5]--;
				if($detail[16]>$lpost[$detail[5]+1]) $detail[5]++;
			}
			$userinfo=implode("|",$detail);
			writeover("$userpath/$userfile",$userinfo);
		}
	}
	elseif($detail[5]=='manager' && $detail[1]!=$manager)
	{
		if (!file_exists("data/admin/$userfile"))
		{
			$managername=explode(".",$userfile);
			$postmagroup=getusergroup($managername[0],Y);
			changegroup($managername[0],$postmagroup,Y);
		}
	}
	elseif($detail[5]=='admin')
	{
		if($detail[1]==$manager || file_exists("data/admin/$userfile"))
			changegroup($detail[1],'manager');
		elseif(ifadmin($detail[1])!=1)
		{
			$postmagroup=getusergroup($detail[1],Y);
			changegroup($detail[1],$postmagroup);
		}
	}
}
function updataadmin()
{
	$adminpath="data/admin.php";
	$forumpath="data/forumdata.php";
	$forumarray=openfile($forumpath);
	$count=count($forumarray);
	for($i=0;$i<$count;$i++)
	{
		$detail=explode("|",$forumarray[$i]);
		if($detail[1]!='category')
		{
			$fidarray[]=$detail[4];/*�õ������ID����*/
		}
	}
	$adminarray=openfile($adminpath);
	$acount=count($adminarray);
	for($j=0;$j<$acount;$j++)
	{
		$temp=explode("|",$adminarray[$j]);
		if(!in_array($temp[1],$fidarray)||empty($temp[2]))
			unset($adminarray[$j]);
	}
	$admindb=@implode("",$adminarray);
	writeover($adminpath,$admindb);
}
?>