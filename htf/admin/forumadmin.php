<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=forumadmin";
$adminfile="data/admin.php";
$forumfile="data/forumdata.php";
if (file_exists($forumfile)) 
{
	$forumarray=openfile($forumfile);
	$count=count($forumarray);
	if($count==0) $count=1;
}
$adminarray=openfile($adminfile);
empty($adminarray)? $adminarray[0]="" : true;
$acount=count($adminarray);	
$onlyforum="";
if(!$selectedid)
	$selectedid=1;
for($i=0; $i<$count; $i++)
{
	$detail=explode("|",$forumarray[$i]);			
	if ($detail[1]!="category"){
		if($detail[4]==$selectedid){
			for($j=0;$j<$acount;$j++){
				$temp=explode("|",$adminarray[$j]);
				if($temp[1]==$selectedid)
				{
					$admindetail=explode("|",readover("$userpath/$temp[2].php"));
					$lastlogintime=date('Y-m-d H:i',$admindetail[19]);
					$oldadmin[]=$temp[2];
					$oldfid[]=$temp[1];
					$forumadmin.="<tr><td bgcolor=$b align=center>$temp[2]</td><td bgcolor=$b align=center>$detail[2]</td><td bgcolor=$b align=center>$lastlogintime</td></tr>";
				}
			}
			$onlyforum.="<option value=$detail[4] name=selectedid selected>$detail[2]</option>";
		}
		else
			$onlyforum.="<option value=$detail[4] name=selectedid>$detail[2]</option>";
	}
}
$onlyforum.="</select>";
if(!$action)
{		
	eval("dooutput(\"".gettmp('forumadmin')."\");");
}
elseif($action=="add"||$action=="del")
{	
	if(empty($adminname))
		adminmsg("�û���Ϊ��");
	$adminname=str_replace("|","",$adminname); 
	$adminname=stripslashes($adminname);
	$adminname=trim($adminname);
	if ($action=="add") 
	{
		if(!file_exists("$userpath/$adminname.php"))
			adminmsg("����������ע���Ա");
		for($i=0;$i<$acount;$i++)
		{	
			if($adminname==$oldadmin[$i]&&$fid==$oldfid[$i])
				adminmsg("�� �� �� �� �� ��");
		}

		$superadgroup=getusergroup($adminname);
		if($superadgroup!='superadmin' && $superadgroup!='ctuser' && $superadgroup!='manager')
		{
			changegroup($adminname,'admin');
		}
		//$newadminarray=implode("",$adminarray);
		$newadminarray="<?die;?>|$fid|$adminname|\n";
		writeover($adminfile,$newadminarray,"ab");
	}
	elseif($action=="del")
	{
		if(empty($adminname))
			adminmsg("�û���Ϊ��");
		if(!file_exists("$userpath/$adminname.php"))
			adminmsg("�����ڸ��û������û������ѱ�ɾ��");
		$count=count($adminarray);
		if($count==0) $count=1;
		$haveadminnum=0;
		for ($i=0; $i<$count; $i++)
		{
			$detail=explode("|",$adminarray[$i]);
			if ($detail[2]==$adminname)
			{
				$haveadminnum++;
			}
			if ($detail[1]==$fid && $detail[2]==$adminname)
			{
				unset($adminarray[$i]);
			}
			if (!file_exists("$dbpath/$detail[1]")) 
			{
				unset($adminarray[$i]);
			}
		}
		if($haveadminnum<2)
		{
			$superadgroup=getusergroup($adminname);
			if($superadgroup!='superadmin' && $superadgroup!='ctuser')
			{
				$postmagroup=getusergroup($adminname,Y);
				changegroup($adminname,$postmagroup);
			}
		}
		$newadmindb=implode("",$adminarray);
		writeover($adminfile,$newadmindb);
	}
	adminmsg("�� �� �� �� �� ��");
}
?>