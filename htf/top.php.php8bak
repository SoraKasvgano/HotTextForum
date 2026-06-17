<?php
require'./global.php';
require'./header.php';
$ifecho=array('addnew1'=>'<!-- ','addnew2'=>'-->');			
$secondname='总置顶管理';
$filename="data/top.php";
$secondurl="top.php";
if($groupid=='guest'){

	showmsg('你没有总置顶管理权限');
}

//管理验证开始!
$ma_check=0;
if ($groupid!='guest' && $htfid==$manager)
	$ma_check=1;
if ($groupid=='superadmin')
	$ma_check=1;
if ($ma_check==0){
	showmsg("您没有权利进行操作,请您以合适的身份登录(管理员)");
}
if($action==add){
	$msg_guide=headguide($secondname);
	$check=1;
	if (empty($newtitle) || strlen($newtitle)>50){
		$check=0;
		$msg_info="标题长度错误,请控制在(0-50)字节内.";
	}
	elseif(empty($forumid)||empty($atcid)){
		$check=0;
		$msg_info="版块ID或帖子ID为空,请完整填写.";
	}
	elseif(!is_numeric($forumid)||!is_numeric($atcid)){
		$check=0;
		$msg_info="非法版块ID和帖子ID,必须为数字.";
	}
	if($check==0){
		showmsg($msg_info);
	}
	$newtitle=stripslashes(safeconvert($newtitle));
	if (file_exists($filename))
		$message=readover($filename);
	$time=date($db_tformat,$timestamp);
	$message="<?die;?>|$htfid|topic.php?fid=$forumid&tid=$atcid|$time|$newtitle|\n".$message;
	writeover($filename,$message);	
	showmsg("总置顶设置完成");
}
elseif($action==edit)
{
	$action=edit;
	$ifecho[addnew1]='';$ifecho[addnew2]='';
	if(!$step){
		if (file_exists($filename)){
			$messagearray=openfile($filename);
			$msg_info=explode("|",$messagearray[$mid]);
			$oldauthor=$msg_info[1];
			$oldatcid=$msg_info[2];
			$oldtitle=$msg_info[4];
		}
	}
	elseif($step==2){
		$oldinfo=openfile($filename);
		$newtitle=stripslashes(safeconvert($newtitle));
		if(!empty($forumid) && !empty($atcid))
			$oldatcid="topic.php?fid=$forumid&tid=$atcid";
		if (empty($newtitle) || strlen($newtitle)>50){

			showmsg("标题长度错误,请控制在(0-50)字节内.");
		}
		$time=date($db_tformat,$timestamp);
		$oldinfo[$mid]="<?php die();?>|$oldauthor|$oldatcid|$time|$newtitle|\n";
		$message=implode("",$oldinfo);
		writeover($filename,$message);
		showmsg("总置顶设置完成");
	}
}
elseif($action==del)
{
	if (file_exists($filename))
	{
		$messagearray=openfile($filename);
		$count=count($messagearray);
		for($i=0;$i<$count;$i++)
		{
			if($msgarray[$i]==2)
				unset($messagearray[$i]);
		}
		$message=implode(" ",$messagearray);
		writeover($filename,$message);
		showmsg("已完成设置");
	}
	else{
		$msg_info='无置顶文章';
		showmsg($msg_info);
	}
}
if(!$action)$action=add;
if(@filesize($filename)!=0){
	$messagearray=openfile($filename);
	$count=count($messagearray);
	for($i=0;$i<$count;$i++){
		$message=explode("|",$messagearray[$i]);
		if(strlen($message[2])>=30)$message[2]=substr($message[2],0,30)."...";
		$msginfo.="<tr><td align=center bgcolor=$forumcolorone><input type='checkbox' name='msgarray[$i]' value=2></td><td align=center bgcolor=$forumcolorone>$message[1]</td><td align=center bgcolor=$forumcolorone><a href=\"$secondurl?action=edit&mid=$i\" title=编辑该置顶贴>$message[4]</a></td><td align=center bgcolor=$forumcolorone><a href=\"$message[2]\">$message[3]</a></td></tr>";
	}
}
else
	$count=0;
$guide="目前共有 <span class=bold>$count</span> 篇总置顶文章";
$msg_guide=headguide($secondname,$secondurl.'','',$guide);
include PrintEot('top');footer();
?>