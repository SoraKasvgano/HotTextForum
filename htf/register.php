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
include_once './require/security_integration.php'; // Security: CSRF + Rate Limiting
if($db_allowsameip=='Y'){
	if(file_exists("data/ip_cache.txt"))
	{
		$ipdata=readover("data/ip_cache.txt");
		if(strpos($ipdata,"<$onlineip>")!==false){

				showmsg("ע��IP����:ͬһIP24Сʱ��ֻ��ע��һ�Σ�");
		}
	}
}
if($vip=='jihuo')
{
	if(file_exists("$userpath/$vipname.php"))
	{
		$userarray=explode("|",readover("$userpath/$vipname.php"));
		if($pwd==$userarray[27])//����ʱ�����֤
		{
			$userarray[27]=1;
			$userdb=implode("|",$userarray);
			writeover("$userpath/$vipname.php",$userdb);
			require "./header.php";
			$msg_guide=headguide("���������ʺ�");
			$msg_info="�ɹ�����,����ʺ��Ѿ����лл���֧��";
			showmsg("�ɹ�����,����ʺ��Ѿ����лл���֧��");
		}
		else{

			showmsg("����ʧ��,����ԭ��:����û��������ڻ��������ַ��������");
		}
	}
}
if ($db_allowregister==0 && ($htfadminid!=$manager || $htfadminpwd!=$manager_pwd)){

	showmsg("�Բ���Ŀǰ��̳��ֹ���û�ע�ᣬ�뷵�ء���");
}
if ($groupid=='guest' && $step==2)
{
	// Security: Rate Limiting - Prevent registration abuse
	apply_rate_limit('register', 3, 600); // 3 attempts per 10 minutes

	$reg_check=1;
	if (strlen($regname)>$db_regmaxname || strlen($regname)<$db_regminname)
	{
		$error="ע�������ȴ����������$db_regminname �� $db_regmaxname ����������";
		$reg_check=0;
	}
	$S_key=array('|',' ',"'",'"','/','*',',','~',';','<','>','$',"\\","\r","\t","\n");
	foreach($S_key as $value){
		if (strpos($regname,$value)!==false)
		{ 
			$error="�û����������ɽ����ַ�( $value )  ��ʹ����Ӣ�ĺ�����"; 
			$reg_check=0; 
		}
		if (strpos($regpwd,$value)!==false)
		{ 
			$error="����������ɽ����ַ�( $value )  ��ʹ����Ӣ�ĺ�����"; 
			$reg_check=0; 
		}
	}
	if(empty($db_rglower)){
		for ($asc=65;$asc<=90;$asc++)
		{ //strtolower() �˺�����һЩ���������������!
			if (strpos($regname,chr($asc))!==false)
			{
				$error="Ϊ�˱�����̳�û�������,�û����н�ֹʹ�ô�д��ĸ����ʹ��Сд��ĸ"; 
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
			$error="ע���ʼǩ�����Ȳ��ɴ���100�ֽ�"; 
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
	foreach($wordsfb as $key => $value)
	{
		if (strpos($rg_sign,$key) !== false)
		{
			$error="<span class=bold>��ǩ�������зǷ����ۻ��Ƿ��ֹ�����<br><br><font color=red size=5>���Ի�ɫ���ݣ��򵹷��ֹ����ӣ�<br><font color=green>���Ǳ����㣡</font></font></span>";
			$reg_check=0;
		}
		if (strpos($rg_introduce,$key) !== false)
		{
			$error="<span class=bold>���ҽ��ܿ����зǷ����ۻ��Ƿ��ֹ�����<br><br><font color=red size=5>���Ի�ɫ���ݣ��򵹷��ֹ����ӣ�<br><font color=green>���Ǳ����㣡</font></font></span>";
			$reg_check=0;
		}
	}
	if (file_exists("$userpath/$rg_name.php") || $rg_name=='guest' || $rg_name=='������Ա') 
	{
		$error="���û��Ѵ���!����������"; 
		$reg_check=0;
	}
	if (empty($regemail)) 
	{
		$error="����û����д������д";
		$reg_check=0;
	}
	if (!preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[0-9A-Za-z]{1,5}$/",$regemail)) 
	{
		$error="���䲻���ϼ���׼����ȷ��û�д���"; 
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
			$error="���û���������Ա��ֹ������Ļ������Ա��ѯ"; 
			$reg_check=0; 
		}
		$ban_count=count($banname);
		for($i=0;$i<$ban_count;$i++)
		{
			if (strpos($rg_name,$banname)!==false)
			{
				$error="��ע����û���Υ�������簲ȫ����������ϵͳ�Ѽ�¼���IP<br>������һ�Σ���������ٴη����㽫����������"; 
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
	if ($regoicq && !preg_match("/^[0-9]{5,}$/",$regoicq)) 
	{
		$error="OICQ���벻��ȷ";
		$reg_check=0;
	}
	/**
	* ���� email ����֤����,����������̳������ٶ�
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
				$error="��email�Ѿ�����ʹ���ˣ��벻Ҫ�ظ�ע��!"; 
				$reg_check=0;
				break;
			}
		}//����׳��!
	}*/
	//��� reg_check ����1 �ɹ�ע��
	if ($reg_check==1) 
	{
		if($db_ifcheck=='1'){
			$rg_groupid='newrg';
			$reg_date=date($db_tformat,$timestamp);
			writeover('data/newuser_cache.php',"<?die;?>|$rg_name|$reg_date|$onlineip|\n","ab");
		}
		else
			$rg_groupid=0;//��̨����
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
		Cookie('lastvisit','',0);//��$lastvist����Խ���ע��Ļ�Ա������յ��û�Ա��
		//addonlinefile();
		//�����ʼ�
		if($db_regsendemail==1)
		{
			//$email_pwd=$rg_pwd;
			$title=$rg_name." ����,��л��ע��$db_bbsname"; 
			$emailmsg=$addusername.",���ã�\n\n"; 
			$emailmsg.=$bbs_title."��ӭ���ĵ�����\n"; 
			if($db_emailcheck==1)
			{
				$emailmsg.="�������ü��������û���(���������ַ����,����û����������븴��������ַ����)\n";
				$emailmsg.="{$db_bbsurl}/register.php?vip=jihuo&vipname=$rg_name&pwd=$timestamp\n";
				$title="�������� {$db_bbsname} ��Ա�ʺŵı�Ҫ����!"; 
			}
			$emailmsg.="����ע����Ϊ:{$rg_name}\n��������Ϊ:{$regpwd}\n�뾡��ɾ�����ʼ����������͵�����������\n\n����������룬���Ե���̳д����̳�������趨\n��鿴��̳����ķ��������������ӱ�ɾ��\n��̳��ַ��{$db_bbsurl}\n";
			if(@mail("$rg_email","$title","$emailmsg","From: $manager<".$db_ceoemail.">\nReply-To:$db_ceoemail\nX-Mailer: ��̳�ʼ����"))
				$ifmail="�����Ѿ�������һ���ʼ����������䣬�����!";
			else
				$ifmail="���Է����ʼ�ʧ�ܣ�Ҳ���ǿռ������mail()����!";
		}
		//���ͽ���
		//���Ͷ���Ϣ		
		if ($db_regsendmsg)
		{
			$db_welcomemsg=str_replace("{\$rg_name}",$rg_name,$db_welcomemsg);
			$new="<?die;?>|ϵͳ��Ϣ|��ӭ����[{$db_bbsname}]��ף����죡|$timestamp|$db_welcomemsg|0|\n";
			writeover("data/$msgpath/{$rg_name}1.php",$new);
		}
		refreshto("./index.php",'��ϲ����ע���Ѿ��ɹ�'.$ifmail.'���ڿ��Կ�ʼʹ�����Ļ�ԱȨ����');
	}
	if ($reg_check==0)
	{
		showmsg("ע����ִ���:����ԭ��<br><br>$error<br>");
	}
}
if ($groupid!='guest' && !$step){

	showmsg("���Ѿ���ע���Ա���벻Ҫ�ظ�ע��.");
}
if($db_reg==0 &&!$htf)
{
	if ($groupid=='guest' && !$step)
	{
		$ifecho[rg_info1]="";$ifecho[rg_info2]="";$ifecho[rg_detail1]="";$ifecho[rg_detail2]="";
		require "./header.php";	
		$regpermint=$db_rgpermit;
		$msg_guide=headguide("ע��");
		include PrintEot('register');footer();
	}
}
else
	$htf=1;
if ($groupid=='guest' && !$step && $htf==1)
{
	if($db_regdetail==1)  {$ifecho[rg_detail1]="";$ifecho[rg_detail2]="";}
	$ifecho[rg_rg1]="";$ifecho[rg_rg2]="";
	if($db_emailcheck==1)$tpemailcheck='<font color=red>�����ʺ���ҪEMAIL����,����ʵ��д</font>';
	require "./header.php";
	$msg_guide=headguide("ע��");
	include PrintEot('register');footer();
}
?>