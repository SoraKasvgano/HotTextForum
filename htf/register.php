<?php
$star_action='rg';
$ifecho = array
(
	'rg_info1' =>'<!--',
	'rg_info2' =>'-->',
	'rg_detail1' =>'<!--',
	'rg_detail2' =>'-->',
	'rg_rg1' =>'<!--',
	'rg_rg2' =>'-->'
);
require  "./global.php";
require  "./data/dbreg.php";
if($db_allowsameip=='Y'){
	if(file_exists("data/ip_cache.txt"))
	{
		$ipdata=readover("data/ip_cache.txt");
		if(strpos($ipdata,"<$onlineip>")!==false){

				showmsg("注册IP错误:同一IP24小时内只能注册一次！");
		}
	}
}
if($vip=='jihuo')
{
	if(file_exists("$userpath/$vipname.php"))
	{
		$userarray=explode("|",readover("$userpath/$vipname.php"));
		if($pwd==$userarray[27])//利用时间戳验证
		{
			$userarray[27]=1;
			$userdb=implode("|",$userarray);
			writeover("$userpath/$vipname.php",$userdb);
			require "./header.php";
			$msg_guide=headguide("激活您的帐号");
			$msg_info="成功激活,你的帐号已经激活，谢谢你的支持";
			showmsg("成功激活,你的帐号已经激活，谢谢你的支持");
		}
		else{

			showmsg("激活失败,错误原因:你的用户名不存在或是你的网址不完整！");
		}
	}
}
if ($db_allowregister==0 && ($htfadminid!=$manager || $htfadminpwd!=$manager_pwd)){

	showmsg("对不起，目前论坛禁止新用户注册，请返回。。");
}
if ($groupid=='guest' && $step==2) 
{
	$reg_check=1;
	if (strlen($regname)>$db_regmaxname || strlen($regname)<$db_regminname)
	{
		$error="注册名长度错误，请控制在$db_regminname 与 $db_regmaxname 个汉字以内";
		$reg_check=0;
	}
	$S_key=array('|',' ',"'",'"','/','*',',','~',';','<','>','$',"\\","\r","\t","\n");
	foreach($S_key as $value){
		if (strpos($regname,$value)!==false)
		{ 
			$error="用户名包含不可接受字符( $value )  请使用中英文和数字"; 
			$reg_check=0; 
		}
		if (strpos($regpwd,$value)!==false)
		{ 
			$error="密码包含不可接受字符( $value )  请使用中英文和数字"; 
			$reg_check=0; 
		}
	}
	if(empty($db_rglower)){
		for ($asc=65;$asc<=90;$asc++)
		{ //strtolower() 此函数在一些服务器会产生乱码!
			if (strpos($regname,chr($asc))!==false)
			{
				$error="为了避免论坛用户名混乱,用户名中禁止使用大写字母，请使用小写字母"; 
				$reg_check=0; 
			} 
		}
	}
	$rg_name=safeconvert($regname);
	$rg_name=trim($rg_name);
	$rg_name=stripslashes($rg_name);
	//$rg_name=strtolower($rg_name);
	$regpwd = safeconvert($regpwd);
	$regpwd=trim($regpwd);
	$regpwd=stripslashes($regpwd);
	$rg_pwd=md5($regpwd);
	$rg_homepage =	safeconvert($reghomepage);
	$rg_from	 =	safeconvert($regfrom);
	$rg_introduce=	safeconvert($regintroduce);
	$rg_ifemail=safeconvert($_POST['regifemail']);
	$rg_emailtoall=safeconvert($_POST['regemailtoall']);
	if($regsign!="")
	{
		require './require/bbscode.php';
		if(strlen($regsign)>100){
			$error="注册初始签名长度不可大于100字节"; 
			$reg_check=0;
		}
		$rg_sign	 =	safeconvert($regsign);
		$rg_sign=stripslashes($rg_sign);
		$lxsign=convert($rg_sign,$db_htfpic,2);
		if($lxsign==$rg_sign)//*************************
			$rg_ifconvert=1;
		else
			$rg_ifconvert=2;
	}
	else
		$rg_ifconvert=2;
	$rg_homepage=stripslashes($rg_homepage);
	$rg_introduce=stripslashes($rg_introduce);
	$rg_from=stripslashes($rg_from);
	include("data/wordsfb.php");
	while (list($key,$value)=each($wordsfb))
	{
		if (strpos($rg_sign,$key) !== false)
		{
			$error="<span class=bold>此签名可能有非法言论或是法轮功内容<br><br><font color=red size=5>反对黄色内容，打倒法轮功分子！<br><font color=green>我们鄙视你！</font></font></span>";
			$reg_check=0;
		}
		if (strpos($rg_introduce,$key) !== false)
		{
			$error="<span class=bold>自我介绍可能有非法言论或是法轮功内容<br><br><font color=red size=5>反对黄色内容，打倒法轮功分子！<br><font color=green>我们鄙视你！</font></font></span>";
			$reg_check=0;
		}
	}
	if (file_exists("$userpath/$rg_name.php") || $rg_name=='guest' || $rg_name=='隐身会员') 
	{
		$error="该用户已存在!请重新输入"; 
		$reg_check=0;
	}
	if (empty($regemail)) 
	{
		$error="信箱没有填写，请填写";
		$reg_check=0;
	}
	if (!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[0-9A-Za-z]{1,5}$",$regemail)) 
	{
		$error="信箱不符合检查标准，请确认没有错误"; 
		$reg_check=0;
	}
	else
	{
		$rg_email=$regemail;
	}
	if (file_exists("data/banname.php")) 
	{
		require "./data/banname.php";
		if ($banname && in_array($rg_name,$banname)) 
		{
			$error="此用户名被管理员禁止，请更改或向管理员咨询"; 
			$reg_check=0; 
		}
		$ban_count=count($banname);
		for($i=0;$i<$ban_count;$i++)
		{
			if (strpos($rg_name,$banname)!==false)
			{
				$error="你注册的用户名违反了网络安全法公安网络系统已记录你的IP<br>警告你一次，如果发现再次发现你将负法律责任"; 
				$reg_check=0;
				break; 
			}	
		}
	}
	if (!$regsex) 
		$rg_sex="none";
	else
		$rg_sex=$regsex;
	if (!$regbirthyear||!$regbirthmonth||!$regbirthday)
		$rg_birth="";
	else
		$rg_birth=$regbirthyear."/".$regbirthmonth."/".$regbirthday;
	if (!$regoicq) 
		$rg_oicq="";
	else
		$rg_oicq=$regoicq;
	if (!$reghomepage) 
		$rg_homepage="";
	else
		$rg_homepage=$reghomepage;
	if (!$regfrom) 
		$rg_from="";
	else
		$rg_from=$regfrom;
	if ($regoicq && !ereg("^[0-9]{5,}$",$regoicq)) 
	{
		$error="OICQ号码不正确";
		$reg_check=0;
	}
	/**
	* 这是 email 的验证程序,如果上万的论坛会减慢速度
	*if ($db_regdbemail==0)
	{
		$userdbarray=explode("\n",readover("data/userarray.php"));
		$count=count($userdbarray);
		for ($i=1;$i<$count;$i++)
		{
			if (!trim($userdbarray[$i])) continue;
			$userfile=$userpath."/".trim($userdbarray[$i])."."."php";
			if (!file_exists($userfile)) continue;
			$userarray=explode("|",readover($userfile));
			if ($userarray[3]==$regemail)
			{
				$error="该email已经有人使用了，请不要重复注册!"; 
				$reg_check=0;
				break;
			}
		}//程序健壮性!
	}*/
	//如果 reg_check 等于1 成功注册
	if ($reg_check==1) 
	{
		if($db_ifcheck=='1'){
			$rg_groupid='newrg';
			$reg_date=date($db_tformat,$timestamp);
			writeover('data/newuser_cache.php',"<?die;?>|$rg_name|$reg_date|$onlineip|\n","ab");
		}
		else
			$rg_groupid=0;//后台控制
		if($db_emailcheck==1)
			$rg_yz=$timestamp;
		else
			$rg_yz=1;
		$rg_usermsg="<?die;?>|$rg_name|$rg_pwd|$rg_email|$rg_emailtoall|$rg_groupid|0.gif|$rg_sex|$timestamp|$rg_sign|$rg_introduce|$rg_oicq|$rg_icq|$rg_homepage|$rg_from||0|$db_regrvrc|$db_regmoney|$timestamp|$timestamp|$rg_birth|$rg_ifemail|||||$rg_yz||$onlineip|||||$rg_onlinetime|$rg_ifconvert||";
		writeover("$userpath/$rg_name.php",$rg_usermsg);
		list($fp,$bbsdb)=readlock("data/bbsnew.php");
		list($bbsfb,$bbsnewer,$bbstotleuser)=explode("|",$bbsdb);
		$bbsnewer=$rg_name;
		$bbstotleuser++;
		writelock("data/bbsnew.php","<?die;?>|$bbsnewer|$bbstotleuser|",$fp);
		writeover('data/userarray.php',$rg_name."\n","ab");
		$htfid=$rg_name;
		$htfpwd=$rg_pwd;
		$iptime=$timestamp+86400;
		writeover('data/ip_cache.txt',"<$onlineip>","ab");
		Cookie("ifregip",$onlineip,$iptime);
		Cookie("htfpwd",$htfpwd);
		Cookie("htfid",$htfid);
		Cookie('lastvisit','',0);//将$lastvist清空以将刚注册的会员加入今日到访会员中
		//addonlinefile();
		//发送邮件
		if($db_regsendemail==1)
		{
			//$email_pwd=$rg_pwd;
			$title=$rg_name." 您好,感谢您注册$db_bbsname"; 
			$emailmsg=$addusername.",您好！\n\n"; 
			$emailmsg.=$bbs_title."欢迎您的到来！\n"; 
			if($db_emailcheck==1)
			{
				$emailmsg.="首先您得激活您的用户名(点击下行网址激活,如果用户名是中文请复制下行网址激活)\n";
				$emailmsg.="{$db_bbsurl}/register.php?vip=jihuo&vipname=$rg_name&pwd=$timestamp\n";
				$title="激活您在 {$db_bbsname} 会员帐号的必要步骤!"; 
			}
			$emailmsg.="您的注册名为:{$rg_name}\n您的密码为:{$regpwd}\n请尽快删除此邮件，以免别人偷看到你的密码\n\n如果忘了密码，可以到论坛写信请坛主重新设定\n请查看论坛各版的发贴规则，以免帖子被删除\n论坛地址：{$db_bbsurl}\n";
			if(@mail("$rg_email","$title","$emailmsg","From: $manager<".$db_ceoemail.">\nReply-To:$db_ceoemail\nX-Mailer: 论坛邮件快递"))
				$ifmail="我们已经发送了一封邮件到您的邮箱，请查收!";
			else
				$ifmail="偿试发送邮件失败，也许是空间禁用了mail()函数!";
		}
		//发送结束
		//发送短消息		
		if ($db_regsendmsg)
		{
			$db_welcomemsg=str_replace("{\$rg_name}",$rg_name,$db_welcomemsg);
			$new="<?die;?>|系统信息|欢迎光临[{$db_bbsname}]，祝您愉快！|$timestamp|$db_welcomemsg|0|\n";
			writeover("data/$msgpath/{$rg_name}1.php",$new);
		}
		refreshto("./index.php",'恭喜您，注册已经成功'.$ifmail.'现在可以开始使用您的会员权利了');
	}
	if ($reg_check==0)
	{
		showmsg("注册出现错误:错误原因：<br><br>$error<br>");
	}
}
if ($groupid!='guest' && !$step){

	showmsg("您已经是注册成员，请不要重复注册.");
}
if($db_reg==0 &&!$htf)
{
	if ($groupid=='guest' && !$step)
	{
		$ifecho[rg_info1]="";$ifecho[rg_info2]="";$ifecho[rg_detail1]="";$ifecho[rg_detail2]="";
		require "./header.php";	
		$regpermint=$db_rgpermit;
		$msg_guide=headguide("注册");
		include PrintEot('register');footer();
	}
}
else
	$htf=1;
if ($groupid=='guest' && !$step && $htf==1)
{
	if($db_regdetail==1)  {$ifecho[rg_detail1]="";$ifecho[rg_detail2]="";}
	$ifecho[rg_rg1]="";$ifecho[rg_rg2]="";
	if($db_emailcheck==1)$tpemailcheck='<font color=red>所有帐号需要EMAIL激活,请如实填写</font>';
	require "./header.php";
	$msg_guide=headguide("注册");
	include PrintEot('register');footer();
}
?>