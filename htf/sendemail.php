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

	showmsg("���ȵ�½��̳,����ʹ���ʼ�����");
}
if ((strpos($username,"/")!==false) || (strpos($username,"..")!==false))
{
	require ("./header.php");
	$msg_guide=headguide("Send Email Error");
	$msg_info="�����ύ�к��в��ɽ��ܵ��ַ� / �� .. ";
	showmsg("�����ύ�к��в��ɽ��ܵ��ַ� / �� .. ");
}
if ($action!='mailto' && $action!='tofriend') $action='mailto';
if($action=='mailto')
{
	$secondname="�����ʼ�";
	$ifdisabled='disabled';
	$mail_action="��{$username}�����ʼ�";
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
	$atc_name="�����Ƽ�:{$filearray[5]}";
	$ifecho[sdm_friend1]="";$ifecho[sdm_friend2]="";
	$mail_action="�Ƽ�������";
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
		$email_error="����д�ʼ����ݲ���20�ֽ�֮��";
	}
	if (!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,3}$",$sendtoemail) || !ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,3}$",$fromemail))
	{
		$email_error="���䲻���ϼ���׼����ȷ��û�д���";
	}
	if($email_error){
		showmsg($email_error);
	}
	if (@mail("$sendtoemail","$subject","$emailcontent","From: $fromemail\nReply-To:$fromemail\nX-Mailer: {$db_bbsname}�ʼ�ϵͳ")) 
		$msg_info="to:{$sendtoemail}���ʼ����ͳɹ�";
	else 
		$msg_info="���ڷ������ʼ�ϵͳ���ò���ȷ,�ʼ�����ʧ��";
	require "./header.php";
	$msg_guide=headguide($secondname,$secondurl,$thirdname,$thirdurl);
	showmsg($msg_info);
}
if ($username && file_exists("$userpath/$username.php")) 
{
	$userdb=readover("$userpath/$username.php");
	$userarray=explode("|",$userdb);
	if($userarray[22]!='1')
		showmsg("�û�$username �ܾ������ʼ�");
	$to_mail=$userarray[3];
	$to_user=$userarray[1];
	if($userarray[4]!='yes' && $groupid!='superadmin' && $groupid!='manager' && $htfid!=$manager)
	{
		$type="type=hidden";
		$info='���������ñ��ܲ�Ӱ���ʼ�����';
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