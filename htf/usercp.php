<?php
require './global.php';
require './require/bbscode.php';
require './data/level.php';
include_once './require/security_integration.php'; // Security: CSRF + Rate Limiting
//require './require/htfxiu.php';//�������
$ifecho = array
(
	'pr_gd1'  =>'<!--',	'pr_gd2'  =>'-->'
);
if ($groupid=='guest'){

	showmsg('�Բ���!! �㻹û�е�½��ע�ᣬ��ʱ���ܲ鿴��Ա����!!');
}
if (empty($action)) $action='modify';
$rawhtfid=rawurlencode($htfid);
if ($action=='show') 
{
	require './header.php';
	$msg_guide=headguide('��Ա����');
	if($htfid==$username)
		$ifecho[pr_gd1]=$ifecho[pr_gd2]='';
	if (!$username || strpos($username,'/')!==false || $username=='.' || preg_match("/\.\./",$username) || !file_exists("$userpath/$username.php")) {
		
		showmsg('״̬��������������ָ�����û�������');
	}
	else
		list($dir_fb,$dir_name,$dir_pwd,$dir_email,$dir_publicmail,$dir_groupid,$dir_icon,$dir_gender,$dir_regdate,$dir_sign,$dir_introduce,$dir_oicq,$dir_icq,$dir_homepage,$dir_from,$dir_level,$dir_fatie,$dir_weiwang,$dir_money,$dir_lasttime,$dir_thistime,$dir_birth,$dir_receivemail,$dir_tuiji,$dir_lastpost,$dir_null1,$dir_lastaddrst,$dir_yz,$dir_todaypost,$dir_lastip,$dir_sx,$dir_star,$dir_xingxiang,$dir_iffangzui,$dir_onlinetime,$dir_signchange,$dir_null)=explode("|",readover("$userpath/$username.php"));
	$rawusername=rawurlencode($username);
	$dir_weiwang=floor($dir_weiwang/10);
	if ($dir_homepage && strpos($dir_homepage,"://")==false)
		$dir_homepage="http://$dir_homepage";
	/*
	*�������
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
	//���ɲ��δд
	if ($dir_publicmail==1) 
		$sendemail="<a href=sendemail.php?username=$dir_name>$dir_email</a>";
	else 
	{
		$sendemail="<a href=sendemail.php?username=$dir_name>��{$dir_name}���ʼ�</a>";
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
		$usersex="��";
	elseif($dir_gender==2)
		$usersex="Ů";
	elseif($dir_gender==none)
		$usersex="����";
	if(!$dir_birth)$dir_birth="δ��";
	$tempsign=convert($dir_sign,$db_htfpic,2);
	if(!$dir_oicq) $dir_oicq="δ��";
	if(!$dir_icq) $dir_icq="δ��";
	if($dir_level) $honorlevel="<tr><td width=40% bgcolor=$forumcolorone>ͷ��:</td><td bgcolor=$forumcolorone>$dir_level</td></tr>";
	if($dir_onlinetime && $db_ifonlinetime) 
	{
		$useronlinetime=floor($dir_onlinetime/3600);
		$printonlinetime="<tr><td bgcolor=$forumcolorone>����ʱ��:</td><td bgcolor=$forumcolorone>$useronlinetime Сʱ</td></tr>";
	}
	$timestamp-$dir_thistime<$db_onlinetime*1.5 ? $ifonline="����":$ifonline="����";

	if($dir_lastaddrst)$printlastpost="<tr><td bgcolor=$forumcolorone>��󷢱�λ��:</td><td bgcolor=$forumcolorone><a href=topic.php?fid={$dir_lastaddrst}>�鿴�����</a></td></tr>";
	include PrintEot('showuserdb');footer();
}
if ($action=="modify")
{
	if (empty($_POST['step']))
	{
		require "./header.php";
		$msg_guide=headguide("��Ա����");
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
			$htfcode="<br><a href='faq.php?faqjob=1#5'> htf Code ����</a>";
			if ($db_signhtfcode){
				if ($db_htfpic['pic'])
					$htfcode.="<br> [img] - ����";
				else 
					$htfcode.="<br> [img] - �ر�";
				if ($db_htfpic['flash'])
					$htfcode.="<br> [flash] - ����";
				else 
					$htfcode.="<br> [flash] - �ر�";
			}
		}
		else
		{
			$htfcode="<br><a href='faq.php?faqjob=1#5'>htf Code</a>�ر�";
		}
		$iconarray=explode('%',$dir_icon);
		if(!$gp_ifportait)
			$portait="<br> <span class=bold>�Զ���ͷ��</span>- ���������û�����Ȩ��";
		else
		{
			$portait="<br> <span class=bold>�Զ���ͷ��</span>- ������Ա����";
			$portait2="<br>ͼ��λ�ã�<input name=proownportait[0] value='$iconarray[1]' type=text size=35 >���������� URL ·����<br>ͼ����ȣ�<input name=proownportait[1] value='$iconarray[2]' type=text size=2 maxlength=3 >������ 0 -- 215 ֮���һ��������<br>ͼ��߶ȣ�<input name=proownportait[2] value='$iconarray[3]' type=text size=2 maxlength=3 >������ 0 -- 250 ֮���һ��������</td></tr>";
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
		if(preg_match("/^http/",$picpath))
		{
			$picpath=basename($picpath);//����㽫ͼƬ·������Ϊ�����������ϵ�ͼƬ,����ر���ͼƬĿ¼ͬ��,����������ڳ���bug ֮��
			if(!file_exists($picpath))
				$imgpatherror="--ͼƬ·����������,�뵽��̨��������ͼƬ·��Ϊ������̳ͼƬ�����Ŀ¼";
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
		// Security: Rate Limiting for profile updates
		apply_rate_limit('profile_update', 10, 300); // 10 updates per 5 minutes

		$check=1;
		list($dir_fb,$dir_name,$dir_pwd,$dir_email,$dir_publicmail,$dir_groupid,$dir_icon,$dir_gender,$dir_regdate,$dir_sign,$dir_introduce,$dir_oicq,$dir_icq,$dir_homepage,$dir_from,$dir_level,$dir_fatie,$dir_weiwang,$dir_money,$dir_lasttime,$dir_thistime,$dir_birth,$dir_receivemail,$dir_tuiji,$dir_lastpost,$dir_null1,$dir_lastaddrst,$dir_yz,$dir_todaypost,$dir_lastip,$dir_sx,$dir_star,$dir_xingxiang,$dir_iffangzui,$dir_onlinetime,$dir_signchange,$dir_null)=explode("|",readover("$userpath/$htfid.php"));
		if (!empty($propwd)||$dir_email!=$proemail){
			if($dir_pwd!=md5($oldpwd)){

				showmsg("������֤ʧ��");
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
				$msg_info="�Զ���ͷ�񲻿ɰ������ַ�'%',��ʹ������URL"; $check=0;
			}
			if(substr($proownportait[0],0,4)!='http'){
				$msg_info="�Զ���ͷ��·�����Ϸ�"; $check=0;
			}
			if (!preg_match("/^[0-9]{2,3}$/",$proownportait[1]) || !preg_match("/^[0-9]{2,3}$/",$proownportait[2]) || $proownportait[1]>215 || $proownportait[1]<0 || $proownportait[2]>250 ||$proownportait[2]<0) {$msg_info="���Զ����ͼƬ������(0-215)*(0-250)�Ĵ�С��Χ��"; $check=0;}

		}
		empty($port) && $proicon='';
		$dir_icon=safeconvert($proicon.'%'.$proownportait[0].'%'.$proownportait[1].'%'.$proownportait[2]);
		if (strpos($dir_pwd,"|")!==false || strpos($dir_pwd,"<")!==false || strpos($dir_pwd,">")!==false)
		{
			$msg_info="����������ɽ����ַ�����ʹ��Ӣ�ĺ�����";
			$check=0;
		}
		if (!preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[0-9A-Za-z]{1,5}$/",$dir_email)) 
		{
			$msg_info="���䲻���ϼ���׼����ȷ��û�д���"; 
			$check=0;
		}
		if (!preg_match("/^[0-9]{0,}$/",$dir_oicq))
		{
			$msg_info="�ѣѺ��벻��ȷ"; 
			$check=0;
		}
		if (!preg_match("/^[0-9]{0,}$/",$dir_icq))
		{
			$msg_info="ICQ���벻��ȷ"; 
			$check=0;
		}
		$allowsignsum=$gp_signnum;//���Ϊ800,����������̨
		if (strlen($dir_sign)>$allowsignsum && $allowsignsum!=0)
		{
			$msg_info="ǩ�����ɳ��� $allowsignsum �ֽ�"; 
			$check=0;
		}
		if (strlen($dir_introduce)>100)
		{
			
			$msg_info="���Ҽ�鲻�ɳ���100"; 
			$check=0;
		}
		include("data/wordsfb.php");
		foreach($wordsfb as $key => $value)
		{
			if (strpos($dir_sign,$key) != false)
			{
				$msg_info="ǩ�������зǷ����ۻ��Ƿ��ֹ�����";
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
			if(($_COOKIE['skinco'] || $tpskin!=$db_defaultstyle) && $tpskin !=$_COOKIE['skinco'])//$tpskin���
			{
				Cookie('skinco',$tpskin);
				refreshto('index.php','״̬�����÷��ɹ�');
			}
			refreshto("profile.php?action=show&username=".rawurlencode($htfid),'����˳��,�޸Ļ�����Ϣ�ɹ�,������޸�����������Զ��˳�!�����µ�½!');
		}
	}
}