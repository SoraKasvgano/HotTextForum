<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=addgpmenber";
if(!$groupname)
	$groupname='superadmin';
else
	$basename="admin.php?adminjob=addgpmenber&groupname=$groupname";
$cachefile="data/cache.php";
$adminarray=openfile("$cachefile");
$count=count($adminarray);
for($i=0;$i<$count;$i++)
{
	$detail=explode("|",$adminarray[$i]);
	if($detail[1]==$groupname)
	{
		$dcount=count($detail);
		if (!$action)
		{
			for($j=2;$j<$dcount-1;$j++)
			{
				$user_info.="<tr><td bgcolor=$b><input type='checkbox' name='delid[$j]' value=2></td><td bgcolor=$b align=center>$detail[$j]</td><td bgcolor=$b align=center>$ltitle[$groupname]</td></tr>";
			}
			eval("dooutput(\"".gettmp('admin_addnew')."\");");
		}
		elseif($action=="add")
		{
			$newuserid = safeconvert($newuserid);
			if(!$newuserid)
			{
				adminmsg("用 户 名 不 能 为 空");
			}
			elseif(in_array($newuserid,$detail))
			{
				adminmsg("已 经 存 在 该 {$groupname}");
			}
			$ifadmingroup=getusergroup($newuserid);
			if($ifadmingroup=='admin' && $groupname!=superadmin && $groupname!=banned && $groupname!=ctuser)
				adminmsg("该用户为版主,等级高于{$groupname}");
			for($t=0;$t<$count;$t++)
			{
				$temp=explode("|",$adminarray[$t]);
				if(in_array($newuserid,$temp)){
					$oldgroup=$temp[1];
					adminmsg("该用户已经被列入{$oldgroup}组，确定要把他加入{$groupname}组<br><br>请先在{$oldgroup}组中删除他.");
				}
			}
			$detail[$dcount-1]="$newuserid|\n";
			$adminarray[$i]=implode("|",$detail);
			$db=implode("",$adminarray);
			changegroup($newuserid,$groupname);			
			writeover($cachefile,$db);
			adminmsg("添 加 {$groupname} 成 功");				
		}
		elseif($action=="del")
		{
			for($j=2;$j<$dcount;$j++)
			{
				if($delid[$j]==2)
				{
					if(ifadmin($detail[$j]))
						changegroup("$detail[$j]",'admin');
					else
					{
						$postmagroup=getusergroup($detail[$j],Y);
						changegroup($detail[$j],$postmagroup,Y);
					}
					unset($detail[$j]);
				}
			}
			$adminarray[$i]=implode("|",$detail);
			$db=implode("",$adminarray);
			writeover($cachefile,$db);
			adminmsg("已 删 除 该 {$groupname}");
		}
	}
}
?>