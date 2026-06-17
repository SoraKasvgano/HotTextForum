<?php
require './global.php';
if(!$action) $action='sendpwd';
if($action=='sendpwd')
{
	if(!$_POST['step'])
	{
		require "./header.php";
		$msg_guide=headguide("密码发送");
		include PrintEot('sendpwd');
		footer();
	}
	if($_POST['step']==2)
	{
		$pwd_user=$_POST['pwd_user'];
		if(file_exists("$userpath/$pwd_user".".php"))
		{
			$userarray=explode("|",readover("$userpath/$pwd_user.php"));
			//$userarray=getuserarray($pwd_user); 此函数只针对注册用户还可用!
			$send_email=$userarray[3];
			$submit=$userarray[19]+$userarray[20];
			$submit.=substr($userarray[2],10);
			$sendmessage=
			"下面的网址请到修改密码： \n $db_bbsurl/sendpwd.php?action=getback&username=".rawurlencode($pwd_user)."&submit=$submit\n修改后请牢记您的密码\n欢迎来到 {$db_bbsname}我们的网址是:{$db_bbsurl}\n";
			if(@mail($send_email, "$db_bbsname 密码重发", $sendmessage, "From: $db_ceoemail\nReply-To: ".$db_ceoemail."\nX-Mailer: PHP/" . phpversion()))
				$msg_info="我们已经成功发送您的密码到您的注册邮箱:{$send_email}请查收!";
			else
				$msg_info="偿试发送邮件失败,您的服务器邮件配置出错!";
			require "./header.php";
			showmsg($msg_info);
		}
		else
		{
			showmsg("用户数据库无此用户,请返回检查!");
		}
	}
	/**
	* Email 验证
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
			showmsg("用户数据库无此注册邮箱,请返回检查!");
		}
		$sendmessage=
			"下面的网址请到修改密码: \n{$email_db}\n修改后请牢记您的密码\n欢迎来到 {$db_bbsname}我们的网址是:{$db_bbsurl}\n";
		if(@mail($pwd_email, "$db_bbsname 密码重发", $sendmessage, "From: $db_ceoemail\nReply-To: ".$db_ceoemail."\nX-Mailer: PHP/" . phpversion()))
			$msg_info="我们已经成功发送您的密码到您的注册邮箱:{$pwd_email}请查收!";
		else
			$msg_info="偿试发送邮件失败,您的服务器邮件配置出错!";
		showmsg($msg_info);
	}*/
}
elseif($action=='getback')
{
	require "./header.php";
	$msg_guide=headguide("取回密码");
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
					$msg_info="两次密码输入不一致，请重先输入";
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
					$msg_info="密码修改成功";
				}
				showmsg($msg_info);
			}
		}
	}
	$msg_info="用户不存在.或验证失败";
	showmsg($msg_info);
}
?> 