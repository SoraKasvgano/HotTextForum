<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=postcache";
$forumfile="data/postcache.php";
include"./$forumfile";
if(empty($action)){
	$count=count($motion);
	for($i=1;$i<=$count;$i++)
	{
		//echo"$motion[i][0],$motion[i][1],$motion[i][2]";exit;
		if(strlen($motion[$i][1]>=30))$motion[$i][1]=substr($motion[$i][1],0,30)."...";
		$motioninfo.=
				 "<tr>
				 <td bgcolor=$b><input type='checkbox' name='motionarray[$i]' value='$i'></td>
				 <td bgcolor=$b>{$motion[$i][0]}</td>
				 <td bgcolor=$b>{$motion[$i][1]}</td>
				 <td bgcolor=$b>{$motion[$i][2]}</td>
				 </tr>";			
	}
	$count=count($face);
	for($i=0;$i<$count;$i++)
	$faceinfo.= "<tr>
				 <td bgcolor=$b ><input type='checkbox' name='facearray[$i]' value='$i'></td>
				 <td bgcolor=$b align=center>{$face[$i]}</td>
				 </tr>";
	eval("dooutput(\"".gettmp('postcache')."\");");
}
elseif($action==addact)
{
	if (empty($motion1) || empty($motion2) || empty($motion3))
		adminmsg("动作内容不完整，请完整填写");
	$motion1=stripslashes(ieconvert($motion1));
	$motion2=stripslashes(ieconvert($motion2));	
	$motion3=stripslashes(ieconvert($motion3));	
	$writedb="<?php\n\$motion=array(\n";
	foreach($motion as $key => $value){
		$writedb.="'$key'=>array(\n\t'$value[0]',\n\t'$value[1]',\n\t'$value[2]'\n\t),\n";
	}
	$key++;
	$writedb.="'$key'=>array(\n\t'$motion1',\n\t'$motion2',\n\t'$motion3'\n\t)\n";
	$writedb.=");\n";
	$writedb.="\$face=array(\n";
	foreach($face as $value){
		$writedb.="\t'$value',\n";
	}
	$writedb.=');';
	writeover($forumfile,$writedb);
	adminmsg("已完成设置");
}
elseif($action==addface)
{
	if (empty($face1))
		adminmsg("表情内容为空，请完整填写");
	$face1=stripslashes(ieconvert($face1));
	$writedb="<?php\n\$motion=array(\n";
	foreach($motion as $key => $value){
		$writedb.="'$key'=>array(\n\t'$value[0]',\n\t'$value[1]',\n\t'$value[2]'\n\t),\n";
	}
	$writedb.=");\n";
	$writedb.="\$face=array(\n";
	foreach($face as $value){
		$writedb.="\t'$value',\n";
	}
	$writedb.="\t'$face1'\n";
	$writedb.=');';
	writeover($forumfile,$writedb);
	adminmsg("已完成设置");
}
elseif($action==delact)
{
	foreach($motionarray as $value)
		unset($motion[$value]);
	$writedb="<?php\n\$motion=array(\n";
	foreach($motion as $key => $value){
		$writedb.="'$key'=>array(\n\t'$value[0]',\n\t'$value[1]',\n\t'$value[2]'\n\t),\n";
	}
	$writedb.=");\n";
	$writedb.="\$face=array(\n";
	foreach($face as $value){
		$writedb.="\t'$value',\n";
	}
	$writedb.=');';
	writeover($forumfile,$writedb);
	adminmsg("已完成设置");
}
elseif($action==delface)
{
	foreach($facearray as $value)
		unset($face[$value]);
	$writedb="<?php\n\$motion=array(\n";
	foreach($motion as $key => $value){
		$writedb.="'$key'=>array(\n\t'$value[0]',\n\t'$value[1]',\n\t'$value[2]'\n\t),\n";
	}
	$writedb.=");\n";
	$writedb.="\$face=array(\n";
	foreach($face as $value){
		$writedb.="\t'$value',\n";
	}
	$writedb.=');';
	writeover($forumfile,$writedb);
	adminmsg("已完成设置");
}
?>