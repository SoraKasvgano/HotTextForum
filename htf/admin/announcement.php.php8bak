<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=announcement";
$anfile="data/bulletin.php";
updatacache_a();
if($action=='add')
{
	if (empty($newsubject) || empty($newmessage))
		adminmsg("标题和内容为空，请完整填写");
	$newsubject=stripslashes(ieconvert($newsubject));
	$newmessage=stripslashes(ieconvert($newmessage));	
	if(!$newendtime)$newendtime="不限";
	if (file_exists($anfile)){
		$message=readover($anfile);		
		$message="<?die;?>|$admin_name|$newsubject|$newmessage|$newstarttime|$newendtime|\n".$message;
		writeover($anfile,$message);
	}
	updatacache_a();
	adminmsg("添加公告完成,程序自动跳回公告管理,请等待.....",1,2);
}	
elseif($action=='edit'){
	$ifecho[anc_add1]="<!--";$ifecho[anc_add2]="-->";
	$ifecho[anc_edit1]="";$ifecho[anc_edit2]="";
	if(!$step){
		if (file_exists($anfile)){
			$messagearray=openfile($anfile);
			$msg_info=explode("|",$messagearray[$mid]);
			$oldsubject=$msg_info[2];
			$oldmessage=str_replace("<br>","\n",$msg_info[3]);
			$oldmessage=str_replace("<br />","\n",$oldmessage);
			$oldstarttime=$msg_info[4];
			$oldendtime=$msg_info[5];
		}
	}
	else{
		if(!$newendtime)$newendtime="不限";
		if (file_exists($anfile)){
			$messagearray=openfile($anfile);
			$detail=explode("|",$messagearray[$mid]);
			$newsubject=stripslashes(ieconvert($newsubject));
			$newmessage=stripslashes(ieconvert($newmessage));
			$messagearray[$mid]="<?die;?>|$detail[1]|$newsubject|$newmessage|$newstarttime|$newendtime|\n";
			reset($messagearray);
			$message=implode("",$messagearray);
			writeover($anfile,$message);
		}
		updatacache_a();
		adminmsg("修改公告完成,程序自动跳回公告管理,请等待.....",1,2);
	}		
}
elseif($action=='del')
{
	if (file_exists($anfile))
	{
		$messagearray=openfile($anfile);
		$count=count($messagearray);
		for($i=0;$i<$count;$i++)
		{
			if($msgarray[$i]==2)
				unset($messagearray[$i]);
		}
		$message=implode(" ",$messagearray);
		writeover($anfile,$message);
	}
	updatacache_a();
	adminmsg("已删除所选定的公告,程序自动跳回公告管理,请等待.....",1,2);
}
if (file_exists($anfile))
{
	$msgarray=openfile($anfile);
	$msgarray[0]=='' ? $count=0 :$count=count($msgarray);
	for($i=0;$i<$count;$i++)
	{
		$message=explode("|",$msgarray[$i]);
		if(strlen($message[2])>=30)$message[2]=substr($message[2],0,30)."...";
		$msginfo.=
				 "<tr>
				 <td bgcolor=$b><input type='checkbox' name='msgarray[$i]' value=2></td>
				 <td bgcolor=$b>$message[1]</td>
				 <td bgcolor=$b><a href=\"$basename&action=edit&mid=$i\" title=编辑该公告>$message[2]</a></td>
				 <td bgcolor=$b>$message[4]</td>
				 <td bgcolor=$b>$message[5]</td>
				 </tr>";
		
	}
}
eval("dooutput(\"".gettmp('admin_anc')."\");");
function updatacache_a(){
	$anfile="data/bulletin.php";
	$cachefile="data/indexcache.php";
	@include "./$cachefile";
	if(empty($notice) || !file_exists($cachefile) || @filemtime($anfile)>@filemtime($cachefile)) {
		$msgarray=openfile($anfile);
		$notice='最近没有论坛公告。';
		if($msgarray[0]!=''){
			$detail=explode("|",$msgarray[0]);
			$notice="<a href=bulletin.php?action=1#0>$detail[2]</a>($detail[4])";
		}
		writeover($cachefile,"<?php\n\$notice=\"$notice\";\n\$index_link=\"$index_link\";\n?>");
	}
}
?>