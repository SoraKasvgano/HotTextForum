<?php
require './global.php';
if(!$action) $action='sendpwd';
if($action=='sendpwd')
{
	if(!$_POST['step'])
	{
		require "./header.php";
		$msg_guide=headguide("���뷢��");
		include PrintEot('sendpwd');
		footer();
	}
	if($_POST['step']==2)
	{
		$pwd_user=$_POST['pwd_user'];
		if(file_exists("$userpath/$pwd_user".".php"))
		{
			$userarray=explode("|",readover("$userpath/$pwd_user.php"));
			//$userarray=getuserarray($pwd_user); �˺���ֻ���ע���û�������!
			$send_email=$userarray[3];
			$submit=$userarray[19]+$userarray[20];
			$submit.=substr($userarray[2],10);
			$sendmessage=
			"�������ַ�뵽�޸����룺 \n $db_bbsurl/sendpwd.php?action=getback&username=".rawurlencode($pwd_user)."&submit=$submit\n�޸ĺ����μ���������\n��ӭ���� {$db_bbsname}���ǵ���ַ��:{$db_bbsurl}\n";
			if(@mail($send_email, "$db_bbsname �����ط�", $sendmessage, "From: $db_ceoemail\nReply-To: ".$db_ceoemail."\nX-Mailer: PHP/" . phpversion()))
				$msg_info="�����Ѿ��ɹ������������뵽����ע������:{$send_email}�����!";
			else
				$msg_info="���Է����ʼ�ʧ��,���ķ������ʼ����ó���!";
			require "./header.php";
			showmsg($msg_info);
		}
		else
		{
			showmsg("�û����ݿ��޴��û�,�뷵�ؼ��!");
		}
	}
	/**
	* Email ��֤
	*elseif($_POST['step']==3)
	{
		$pwd_email=$_POST['pwd_email'];
		$db=opendir("$userpath/");
		while (false!==($userfile=readdir($db))) 
		{ 
			if (($userfile!=".") && ($userfile!="..") && ($userfile!=""))
			{
				$userdb=openfile("$userpath/".$userfile);
				$userarray=explode("|",$userdb[0]); 
				if($userarray[3]==$pwd_email)
				{
					$submit=$userarray[19]+$userarray[20];
					$submit.=substr($userarray[2],10);
					$email_info="$db_bbsurl/sendpwd.php?action=getback&username=".rawurlencode($userarray[1])."&submit=$submit\n";
					$email_db.=$email_info;
				}
			}
		}
		closedir($db); 
		if (!$email_db){
			showmsg("�û����ݿ��޴�ע������,�뷵�ؼ��!");
		}
		$sendmessage=
			"�������ַ�뵽�޸�����: \n{$email_db}\n�޸ĺ����μ���������\n��ӭ���� {$db_bbsname}���ǵ���ַ��:{$db_bbsurl}\n";
		if(@mail($pwd_email, "$db_bbsname �����ط�", $sendmessage, "From: $db_ceoemail\nReply-To: ".$db_ceoemail."\nX-Mailer: PHP/" . phpversion()))
			$msg_info="�����Ѿ��ɹ������������뵽����ע������:{$pwd_email}�����!";
		else
			$msg_info="���Է����ʼ�ʧ��,���ķ������ʼ����ó���!";
		showmsg($msg_info);
	}*/
}
elseif($action=='getback')
{
	require "./header.php";
	$msg_guide=headguide("ȡ������");
	$basename="sendpwd.php?action=getback&username=$username&submit=$submit";
	if(file_exists("$userpath/$username.php"))
	{
		$db=readover("$userpath/$username.php");
		$detail=explode("|",$db);
		$is_right=$detail[19]+$detail[20];
		$is_right.=substr($detail[2],10);
		if($submit==$is_right){
			if(!$jop)
			{			
				include PrintEot('getpwd');footer();
			}
			elseif($jop==2)
			{
				if($new_pwd!=$pwdreapt)
				{
					$msg_info="�����������벻һ�£�����������";
				}
				else
				{
					$new_pwd=stripslashes($new_pwd);
					$new_pwd=str_replace("\t","",$new_pwd); 
					$new_pwd=str_replace("\r","",$new_pwd); 
					$new_pwd=str_replace("\n","",$new_pwd);
					$new_pwd=md5($new_pwd);
					$db=readover("$userpath/$username.php");
					$detail=explode("|",$db);
					$detail[2]=$new_pwd;
					$db=implode("|",$detail);
					writeover("$userpath/$username.php",$db);
					$msg_info="�����޸ĳɹ�";
				}
				showmsg($msg_info);
			}
		}
	}
	$msg_info="�û�������.����֤ʧ��";
	showmsg($msg_info);
}
?> 