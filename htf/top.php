<?php
require'./global.php';
require'./header.php';
$ifecho=array('addnew1'=>'<!-- ','addnew2'=>'-->');			
$secondname='���ö�����';
$filename="data/top.php";
$secondurl="top.php";
if($groupid=='guest'){

	showmsg('��û�����ö�����Ȩ��');
}

//������֤��ʼ!
$ma_check=0;
if ($groupid!='guest' && $htfid==$manager)
	$ma_check=1;
if ($groupid=='superadmin')
	$ma_check=1;
if ($ma_check==0){
	showmsg("��û��Ȩ�����в���,�����Ժ��ʵ���ݵ�¼(����Ա)");
}
if($action==add){
	$msg_guide=headguide($secondname);
	$check=1;
	if (empty($newtitle) || strlen($newtitle)>50){
		$check=0;
		$msg_info="���ⳤ�ȴ���,�������(0-50)�ֽ���.";
	}
	elseif(empty($forumid)||empty($atcid)){
		$check=0;
		$msg_info="���ID������IDΪ��,��������д.";
	}
	elseif(!is_numeric($forumid)||!is_numeric($atcid)){
		$check=0;
		$msg_info="�Ƿ����ID������ID,����Ϊ����.";
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
	showmsg("���ö��������");
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

			showmsg("���ⳤ�ȴ���,�������(0-50)�ֽ���.");
		}
		$time=date($db_tformat,$timestamp);
		$oldinfo[$mid]="<?php die();?>|$oldauthor|$oldatcid|$time|$newtitle|\n";
		$message=implode("",$oldinfo);
		writeover($filename,$message);
		showmsg("���ö��������");
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
		showmsg("���������");
	}
	else{
		$msg_info='���ö�����';
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
		$msginfo.="<tr><td align=center bgcolor=$forumcolorone><input type='checkbox' name='msgarray[$i]' value=2></td><td align=center bgcolor=$forumcolorone>$message[1]</td><td align=center bgcolor=$forumcolorone><a href=\"$secondurl?action=edit&mid=$i\" title=�༭���ö���>$message[4]</a></td><td align=center bgcolor=$forumcolorone><a href=\"$message[2]\">$message[3]</a></td></tr>";
	}
}
else
	$count=0;
$guide="Ŀǰ���� <span class=bold>$count</span> ƪ���ö�����";
$msg_guide=headguide($secondname,$secondurl.'','',$guide);
include PrintEot('top');footer();
?>