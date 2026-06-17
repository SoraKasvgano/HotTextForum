<?php
require './global.php';
require './require/bbscode.php';
require './data/level.php';
//require './require/htfxiu.php';//插件形象
$ifecho = array
(
	'pr_gd1'  =>'<!--',	'pr_gd2'  =>'-->'
);
if ($groupid=='guest'){

	showmsg('对不起!! 你还没有登陆或注册，暂时不能查看会员资料!!');
}
if (empty($action)) $action='modify';
$rawhtfid=rawurlencode($htfid);
if ($action=='show') 
{
	require './header.php';
	$msg_guide=headguide('会员资料');
	if($htfid==$username)
		$ifecho[pr_gd1]=$ifecho[pr_gd2]='';
	if (!$username || strpos($username,'/')!==false || $username=='.' || ereg("\.\.",$username) || !file_exists("$userpath/$username.php")) {
		
		showmsg('状态：发生错误，您所指定的用户不存在');
	}
	else
		list($dir_fb,$dir_name,$dir_pwd,$dir_email,$dir_publicmail,$dir_groupid,$dir_icon,$dir_gender,$dir_regdate,$dir_sign,$dir_introduce,$dir_oicq,$dir_icq,$dir_homepage,$dir_from,$dir_level,$dir_fatie,$dir_weiwang,$dir_money,$dir_lasttime,$dir_thistime,$dir_birth,$dir_receivemail,$dir_tuiji,$dir_lastpost,$dir_null1,$dir_lastaddrst,$dir_yz,$dir_todaypost,$dir_lastip,$dir_sx,$dir_star,$dir_xingxiang,$dir_iffangzui,$dir_onlinetime,$dir_signchange,$dir_null)=explode("|",readover("$userpath/$username.php"));
	$rawusername=rawurlencode($username);
	$dir_weiwang=floor($dir_weiwang/10);
	if ($dir_homepage && strpos($dir_homepage,"://")==false)
		$dir_homepage="http://$dir_homepage";
	/*
	*插件形象
	*/
	/*if($dir_xingxiang==1)
		$usericon=gethtfxiu($dir_name,$postxuni,140,226);
	else
	{
		if ($dir_icon=="")
			$usericon="<img src=\"$imgpath/face/0.gif\" width=%70>";
		else*/
			$usericon=showfacedesign($dir_icon);
	//}
	$level=$ltitle[$dir_groupid];
	//门派插件未写
	if ($dir_publicmail==1) 
		$sendemail="<a href=sendemail.php?username=$dir_name>$dir_email</a>";
	else 
	{
		$sendemail="<a href=sendemail.php?username=$dir_name>给{$dir_name}发邮件</a>";
		if($htfid==$manager)
			$sendemail.="( $dir_email )";
	}
	$lasttime=date($db_tformat,$dir_lasttime);

	if($posttime) $posttime=date($db_tformat,$dir_lastpost);
	else $posttime="x";
	if(!$dir_todaypost||$dir_lastpost<$tdtime) $dir_todaypost=0;
	$averagepost=floor($dir_fatie/(ceil($timestamp-$dir_regdate)/(3600*24)));
	$show_regdate=date($db_tformat,$dir_regdate);
	if($dir_gender==1)
		$usersex="男";
	elseif($dir_gender==2)
		$usersex="女";
	elseif($dir_gender==none)
		$usersex="保密";
	if(!$dir_birth)$dir_birth="未填";
	$tempsign=convert($dir_sign,$db_htfpic,2);
	if(!$dir_oicq) $dir_oicq="未填";
	if(!$dir_icq) $dir_icq="未填";
	if($dir_level) $honorlevel="<tr><td width=40% bgcolor=$forumcolorone>头衔:</td><td bgcolor=$forumcolorone>$dir_level</td></tr>";
	if($dir_onlinetime && $db_ifonlinetime) 
	{
		$useronlinetime=floor($dir_onlinetime/3600);
		$printonlinetime="<tr><td bgcolor=$forumcolorone>在线时间:</td><td bgcolor=$forumcolorone>$useronlinetime 小时</td></tr>";
	}
	$timestamp-$dir_thistime<$db_onlinetime*1.5 ? $ifonline="在线":$ifonline="离线";

	if($dir_lastaddrst)$printlastpost="<tr><td bgcolor=$forumcolorone>最后发表位置:</td><td bgcolor=$forumcolorone><a href=topic.php?fid={$dir_lastaddrst}>查看最后发贴</a></td></tr>";
	include PrintEot('showuserdb');footer();
}
if ($action=="modify")
{
	if (empty($_POST['step']))
	{
		require "./header.php";
		$msg_guide=headguide("会员资料");
		list($dir_fb,$dir_name,$dir_pwd,$dir_email,$dir_publicmail,$dir_groupid,$dir_icon,$dir_gender,$dir_regdate,$dir_sign,$dir_introduce,$dir_oicq,$dir_icq,$dir_homepage,$dir_from,$dir_level,$dir_fatie,$dir_weiwang,$dir_money,$dir_lasttime,$dir_thistime,$dir_birth,$dir_receivemail,$dir_tuiji,$dir_lastpost,$dir_null1,$dir_lastaddrst,$dir_yz,$dir_todaypost,$dir_lastip,$dir_sx,$dir_star,$dir_xingxiang,$dir_iffangzui,$dir_onlinetime,$dir_signchange,$dir_null)=explode("|",readover("$userpath/$htfid.php"));
		if($dir_publicmail) $ifchecked="checked";
		$db=opendir("style/");
		if(!empty($_COOKIE['skinco']))
			$selected[$skinco]='selected';
		else
			$selected[$db_defaultstyle]='selected';
		while (false!==($skinfile=readdir($db)))
		{
			if (($skinfile!=".") && ($skinfile!="..")  ) 
			{
				$skinfile=str_replace(".php","",$skinfile);
				$choseskin.="<option value=$skinfile $selected[$skinfile]>$skinfile</option>";
			}
		}
		closedir($db);
		if($db_signhtfcode){
			$htfcode="<br><a href='faq.php?faqjob=1#5'> htf Code 开启</a>";
			if ($db_signhtfcode){
				if ($db_htfpic['pic'])
					$htfcode.="<br> [img] - 开启";
				else 
					$htfcode.="<br> [img] - 关闭";
				if ($db_htfpic['flash'])
					$htfcode.="<br> [flash] - 开启";
				else 
					$htfcode.="<br> [flash] - 关闭";
			}
		}
		else
		{
			$htfcode="<br><a href='faq.php?faqjob=1#5'>htf Code</a>关闭";
		}
		$iconarray=explode('%',$dir_icon);
		if(!$gp_ifportait)
			$portait="<br> <span class=bold>自定义头像：</span>- 您所处的用户组无权限";
		else
		{
			$portait="<br> <span class=bold>自定义头像：</span>- 被管理员开启";
			$portait2="<br>图像位置：<input name=proownportait[0] value='$iconarray[1]' type=text size=35 >输入完整的 URL 路径。<br>图像宽度：<input name=proownportait[1] value='$iconarray[2]' type=text size=2 maxlength=3 >必须是 0 -- 215 之间的一个整数。<br>图像高度：<input name=proownportait[2] value='$iconarray[3]' type=text size=2 maxlength=3 >必须是 0 -- 250 之间的一个整数。</td></tr>";
		}
		$iconarray[0] && $disabled='checked';
		$dir_receivemail?$email_open='checked':$email_close='checked';
		$sexselect[$dir_gender]="selected";
		$getbirthday = explode("/",$dir_birth);
		$yearslect[$getbirthday[0]]="selected";
        $monthslect[$getbirthday[1]]="selected";
		$dayslect[$getbirthday[2]]="selected";
		$dir_introduce=str_replace('<br />',"\n",$dir_introduce);
		$dir_sign=str_replace('<br />',"\n",$dir_sign);
		if(ereg("^http",$picpath))
		{
			$picpath=basename($picpath);//如果你将图片路径更名为其他服务器上的图片,请务必保持图片目录同名,否则出错不在程序bug 之内
			if(!file_exists($picpath))
				$imgpatherror="--图片路径发生错误,请到后台更正您的图片路径为与您论坛图片保存的目录";
		}
		$img=@opendir("$picpath/face");
		while (false!==($imagearray=@readdir($img)))
		{
			if (($imagearray!=".") && ($imagearray!="..") && ($imagearray!=""))
			{
				if ($imagearray==$iconarray[0])
					$imgselect.= "<option selected value='$imagearray'>$imagearray</option>";
				else 
					$imgselect.="<option value='$imagearray'>$imagearray</option>";
			}
		}
		@closedir($img);
		$allowsignsum=$gp_signnum;
		
		include PrintEot('usercp');footer();
	} 
	elseif($_POST['step']==2)
	{
		$check=1;
		list($dir_fb,$dir_name,$dir_pwd,$dir_email,$dir_publicmail,$dir_groupid,$dir_icon,$dir_gender,$dir_regdate,$dir_sign,$dir_introduce,$dir_oicq,$dir_icq,$dir_homepage,$dir_from,$dir_level,$dir_fatie,$dir_weiwang,$dir_money,$dir_lasttime,$dir_thistime,$dir_birth,$dir_receivemail,$dir_tuiji,$dir_lastpost,$dir_null1,$dir_lastaddrst,$dir_yz,$dir_todaypost,$dir_lastip,$dir_sx,$dir_star,$dir_xingxiang,$dir_iffangzui,$dir_onlinetime,$dir_signchange,$dir_null)=explode("|",readover("$userpath/$htfid.php"));
		if (!empty($propwd)||$dir_email!=$proemail){
			if($dir_pwd!=md5($oldpwd)){

				showmsg("密码验证失败");
			}
		}
		$dir_email=$proemail;
		$dir_oicq=safeconvert($prooicq);
		$dir_icq=safeconvert($proicq);
		$dir_homepage=safeconvert($prohomepage);
		$dir_gender=safeconvert($progender);
		$dir_from=safeconvert($profrom);
		$dir_sign=safeconvert($prosign);
		$dir_introduce=safeconvert($prointroduce);
		if (!empty($propwd))
		{
			$dir_pwd=safeconvert($propwd);
			$dir_pwd=str_replace("\t","",$dir_pwd); 
			$dir_pwd=str_replace("\r","",$dir_pwd); 
			$dir_pwd=str_replace("\n","",$dir_pwd);
			$dir_pwd=md5($dir_pwd);
		}
		$dir_publicmail =safeconvert($propublicemail);
		$dir_receivemail=safeconvert($proreceivemail);
		if($gp_ifportait && !empty($proownportait[0]))
		{
			if (strpos($proownportait[0],'%')!==false) {
				$msg_info="自定义头像不可包含此字符'%',请使用正常URL"; $check=0;
			}
			if(substr($proownportait[0],0,4)!='http'){
				$msg_info="自定义头像路径不合法"; $check=0;
			}
			if (!ereg("^[0-9]{2,3}$",$proownportait[1]) || !ereg("^[0-9]{2,3}$",$proownportait[2]) || $proownportait[1]>215 || $proownportait[1]<0 || $proownportait[2]>250 ||$proownportait[2]<0) {$msg_info="你自定义的图片必须在(0-215)*(0-250)的大小范围里"; $check=0;}

		}
		empty($port) && $proicon='';
		$dir_icon=safeconvert($proicon.'%'.$proownportait[0].'%'.$proownportait[1].'%'.$proownportait[2]);
		if (strpos($dir_pwd,"|")!==false || strpos($dir_pwd,"<")!==false || strpos($dir_pwd,">")!==false)
		{
			$msg_info="密码包含不可接受字符，请使用英文和数字";
			$check=0;
		}
		if (!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[0-9A-Za-z]{1,5}$",$dir_email)) 
		{
			$msg_info="信箱不符合检查标准，请确认没有错误"; 
			$check=0;
		}
		if (!ereg("^[0-9]{0,}$",$dir_oicq))
		{
			$msg_info="ＱＱ号码不正确"; 
			$check=0;
		}
		if (!ereg("^[0-9]{0,}$",$dir_icq))
		{
			$msg_info="ICQ号码不正确"; 
			$check=0;
		}
		$allowsignsum=$gp_signnum;//最大为800,可以做到后台
		if (strlen($dir_sign)>$allowsignsum && $allowsignsum!=0)
		{
			$msg_info="签名不可超过 $allowsignsum 字节"; 
			$check=0;
		}
		if (strlen($dir_introduce)>100)
		{
			
			$msg_info="自我简介不可超过100"; 
			$check=0;
		}
		include("data/wordsfb.php");
		while (list($key,$value)=each($wordsfb))
		{
			if (strpos($dir_sign,$key) != false)
			{
				$msg_info="签名可能有非法言论或是法轮功内容";
				$check=0;
			}
		}
		if(!empty($proyear)||!empty($proyear)||!empty($proyear))
		{
			$dir_birth=safeconvert($proyear."/".$promonth."/".$proday);
		}
		$lxsign=convert($dir_sign,$db_htfpic,2);
		if($lxsign==$dir_sign)
			$dir_signchange=1;
		else
			$dir_signchange=2;
		if($gp_ifhonor) {
			$prohonor=safeconvert($prohonor);
			$dir_level=$prohonor;
		}
		if ($check==0) 
		{
			require "./header.php";
			showmsg($msg_info);
		}
		else 
		{
			$dir_userinfo=array($dir_fb,$dir_name,$dir_pwd,$dir_email,$dir_publicmail,$dir_groupid,$dir_icon,$dir_gender,$dir_regdate,$dir_sign,$dir_introduce,$dir_oicq,$dir_icq,$dir_homepage,$dir_from,$dir_level,$dir_fatie,$dir_weiwang,$dir_money,$dir_lasttime,$dir_thistime,$dir_birth,$dir_receivemail,$dir_tuiji,$dir_lastpost,$dir_null1,$dir_lastaddrst,$dir_yz,$dir_todaypost,$dir_lastip,$dir_sx,$dir_star,$dir_xingxiang,$dir_iffangzui,$dir_onlinetime,$dir_signchange,$dir_null);
			writeover("$userpath/$htfid.php",implode("|",$dir_userinfo));
			if(($_COOKIE['skinco'] || $tpskin!=$db_defaultstyle) && $tpskin !=$_COOKIE['skinco'])//$tpskin风格
			{
				Cookie('skinco',$tpskin);
				refreshto('index.php','状态：设置风格成功');
			}
			refreshto("profile.php?action=show&username=".rawurlencode($htfid),'操作顺利,修改基本信息成功,如果你修改了密码程序将自动退出!需重新登陆!');
		}
	}
}