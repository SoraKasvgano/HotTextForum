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
				adminmsg("�� �� �� �� �� Ϊ ��");
			}
			elseif(in_array($newuserid,$detail))
			{
				adminmsg("�� �� �� �� �� {$groupname}");
			}
			$ifadmingroup=getusergroup($newuserid);
			if($ifadmingroup=='admin' && $groupname!=superadmin && $groupname!=banned && $groupname!=ctuser)
				adminmsg("���û�Ϊ����,�ȼ�����{$groupname}");
			for($t=0;$t<$count;$t++)
			{
				$temp=explode("|",$adminarray[$t]);
				if(in_array($newuserid,$temp)){
					$oldgroup=$temp[1];
					adminmsg("���û��Ѿ�������{$oldgroup}�飬ȷ��Ҫ��������{$groupname}��<br><br>������{$oldgroup}����ɾ����.");
				}
			}
			$detail[$dcount-1]="$newuserid|\n";
			$adminarray[$i]=implode("|",$detail);
			$db=implode("",$adminarray);
			changegroup($newuserid,$groupname);			
			writeover($cachefile,$db);
			adminmsg("�� �� {$groupname} �� ��");				
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
			adminmsg("�� ɾ �� �� {$groupname}");
		}
	}
}
?>