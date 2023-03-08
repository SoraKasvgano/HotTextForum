<?php
$ifecho = array
(
	'sdm_friend1' =>'<!--',
	'sdm_friend2' =>'<-->',
	'sdm_mail1' =>'<!--',
	'sdm_mail2' =>'<-->'
);
require("./global.php");
if ($groupid=='guest'){

	showmsg("请先登陆论坛,才能使用邮件功能");
}
if ((strpos($username,"/")!==false) || (strpos($username,"..")!==false))
{
	require ("./header.php");
	$msg_guide=headguide("Send Email Error");
	$msg_info="您的提交中含有不可接受的字符 / 或 .. ";
	showmsg("您的提交中含有不可接受的字符 / 或 .. ");
}
if ($action!='mailto' && $action!='tofriend') $action='mailto';
if($action=='mailto')
{
	$secondname="发送邮件";
	$ifdisabled='disabled';
	$mail_action="给{$username}发送邮件";
	$ifecho[sdm_mail1]="";$ifecho[sdm_mail2]="";
}
require './require/forum.php';
if($action=='tofriend')
{
	if(empty($forumcount)) list($forumcount,$forumarray)=getforumdb();
	showforuminfo();
	$filearray=explode("|",readover("$dbpath/$fid/$tid.php"));
	$secondname="$fid_name";
	$secondurl="forum.php?fid=$fid&page=$fpage";////////
	$thirdname="$filearray[5]";$thirdurl="topic.php?fid=$fid&tid=$tid";
	$atc_name="向您推荐:{$filearray[5]}";
	$ifecho[sdm_friend1]="";$ifecho[sdm_friend2]="";
	$mail_action="推荐给朋友";
}
$idarray=explode("|",readover("$userpath/$htfid.php"));
$idarray[2]="";
if ($_POST['step']=="2")
{
	$sendtoemail=$_POST['sendtoemail'];
	$fromemail=$_POST['fromemail'];
	if(!$sendtoemail)
		$sendtoemail=$to_mail;
	if (empty($emailcontent) || strlen($emailcontent)<=20)
	{
		$email_error="请填写邮件内容并在20字节之上";
	}
	if (!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,3}$",$sendtoemail) || !ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,3}$",$fromemail))
	{
		$email_error="信箱不符合检查标准，请确认没有错误";
	}
	if($email_error){
		showmsg($email_error);
	}
	if (@mail("$sendtoemail","$subject","$emailcontent","From: $fromemail\nReply-To:$fromemail\nX-Mailer: {$db_bbsname}邮件系统")) 
		$msg_info="to:{$sendtoemail}的邮件发送成功";
	else 
		$msg_info="由于服务器邮件系统配置不正确,邮件发送失败";
	require "./header.php";
	$msg_guide=headguide($secondname,$secondurl,$thirdname,$thirdurl);
	showmsg($msg_info);
}
if ($username && file_exists("$userpath/$username.php")) 
{
	$userdb=readover("$userpath/$username.php");
	$userarray=explode("|",$userdb);
	if($userarray[22]!='1')
		showmsg("用户$username 拒绝接收邮件");
	$to_mail=$userarray[3];
	$to_user=$userarray[1];
	if($userarray[4]!='yes' && $groupid!='superadmin' && $groupid!='manager' && $htfid!=$manager)
	{
		$type="type=hidden";
		$info='邮箱已设置保密不影响邮件发送';
	}
	else
		$type="type='text'";
}
else 
{
	$to_mail="";
	$to_user="";
}
if ($_POST['step']!="2")
{
	require "./header.php";
	$msg_guide=headguide($secondname,$secondurl,$thirdname,$thirdurl);
	include PrintEot('sendmail');footer();
}
?>