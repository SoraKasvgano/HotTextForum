<?php
$ifecho = array
(
	'ps_lg1'   =>'<!--',
	'ps_lg2'   =>'-->',
	'ps_down1' =>'<!--',
	'ps_down2' =>'-->',
	'vt1'      =>'<!--',
	'vt2'      =>'-->'
);
require './global.php';
require './require/forum.php';
if(!$fid || !file_exists("$dbpath/$fid")){include("require/url_error.php");}


/**
* ��ע���û���������
*/
if($db_postallowtime && $timestamp-$htfdb[8]<$db_postallowtime*3600){

	showmsg("ע���û�{$db_postallowtime}Сʱ�ڲ��ܷ������¡�");
}


list($forumcount,$forumarray)=getforumdb();
htf_forumcheck();

$guide="{$fid_name} ������";
$secondname="$fid_name";
$secondurl="forum.php?fid=$fid";
$basename="post.php";



$top_post=0;

/**
* �������οͷ����������ο͵�����Ϊ0
*/
$groupid=='guest' && $userrvrc=0;

if(!$action) $action="new";
/**
* ������ֻ������ͶƱ
*/
if($fid_type=='vote' && $action=='new' && $htfid!=$manager)
	showmsg('ͶƱ���ֻ������ͶƱ');
if (!$_POST['step']){
	include'./data/postcache.php';
	list($fid_post,$fid_hide,$fid_sell,$fid_Tread)=explode("~",$fid_Cconcle);

	$fid_post==2 ? $htmlpost="disabled" : $htmlpost='';
	$fid_hide==2 ? $htmlhide="disabled" : $htmlhide="";
	$fid_sell==2 ? $htmlsell="disabled" : $htmlsell="";

	if ((($action=="new" || $action=="reply" || $action=="quote") && $db_allowupload)||($htfid==$manager && $action!="modify")) {
		$ifecho['ps_down1']="";
		$ifecho['ps_down2']="";
	}
	$db_replysendmail!=1 && $hideemail="disabled";
	if($gp_ifuploadrvrc==1){
		$pt_downrvrc1="��������:<input type='text' name='atc_downrvrc1' value='0' size='1'>";
		$pt_downrvrc2="��������:<input type='text' name='atc_downrvrc2' value='0' size='1'>";
		$pt_downrvrc3="��������:<input type='text' name='atc_downrvrc3' value='0' size='1'>";
		$pt_downrvrc4="��������:<input type='text' name='atc_downrvrc4' value='0' size='1'>";
	}
}
elseif($_POST['step']){
	require './require/dbmodify.php';
	require './require/bbscode.php';
	include("data/wordsfb.php");
	if($wordsfb){
		foreach($wordsfb as $key=>$value){
			if(strpos($atc_title,$key)!==false){
				showmsg("���������в������� , �뷵��");
			}
		}
	}
	if($_COOKIE['lastip']<>$onlineip){
		require_once('./require/checkpass.php');
		$ipfrom=cvipfrom($onlineip);
		$ipfrom = str_replace("\n","",$ipfrom);
		Cookie('ipfrom',$ipfrom);
		Cookie('lastip',$onlineip);
	}
}
//$atc_email=$_POST['atc_email'];

/**
* ��̳��ȫ����
*/
if($forbiduser==$htfid && $groupid!='banned'){

	$_COOKIE['forbiduser']='';
	Cookie('forbiduser','',0);
}
if($_COOKIE['forbiduser'] && $htfid!=$manager){

	showmsg("���Ѿ�������!");
}
if($groupid=='banned'){//��ֹ�������û�����

    Cookie('forbiduser',$htfid);
	showmsg("��������ԭ��,��������,��ֻ̳�����Ѻõ����ѣ�");
}

/**
* ��Ҫ��֤�û�ֻ��ͨ������Ա��֤����ܷ���
*/
if($groupid=='newrg'){

	showmsg("�㻹ûͨ������Ա��֤,��Ҫͨ������Ա��֤���ܷ��ԣ�");
}
if($groupid!='guest'){
	//$htfdb define in global.php
	//$userrvrc=floor($htfdb[17]/10);
	$usermoney=$htfdb[18];
	if($htfdb[27]>2){/*��Ҫ��֤��Ϊʱ���,һ������2*/

		showmsg("���ĵ����ʼ���:{$htfdb[3]}�����յ�һ����֤�ʼ���ȷ���������Ч�ԣ��û��յ��ʼ��������˺ź����ӵ��������Ȩ��!<br>�뵽��ע�����õ�Email��鿴��֤�ʼ������������û��� лл!");
	}
	$userlastptime=$htfdb[24];

}
/**
* ��ˮԤ��
*/

if ($action!="modify" && $gp_postpertime && $timestamp-$userlastptime<=$gp_postpertime){

	showmsg("��ˮԤ�������Ѿ��򿪣���{$gp_postpertime}���ڲ��ܷ���");
}
$fid_father && $fatherid=$fid_father;

if($db_signhtfcode){

	$htfcode="<br><a href=\"faq.php?faqjob=1#5\"> htf Code ����</a>";
	$db_htfpic['pic'] ? $htfcode.="<br> [img] - ����" : $htfcode.="<br> [img] - �ر�";
	$db_htfpic['flash'] ? $htfcode.="<br> [flash] - ����" : $htfcode.="<br> [flash] - �ر�";
}
else{
	$htfcode="<br><a href=\"faq.php?faqjob=1#5\">htf Code</a>�ر�";
}
!$atc_type && $atc_type="0";

if ($action=="new"||$action=="vote"){

	include './require/postnew.php';
}elseif ($action=="reply" || $action=="quote"){

	include './require/postreply.php';
}elseif ($action=="modify"){

	include './require/postmodify.php';
}

/**
* ��̳������Ϣ����
*/
function bbspostguide($atc_title){

	global $db_hour,$checkuplode,$groupid,$htfdb,$gp_uploadmoney,$dbpath,$timestamp,$top_post,$fatherid,$countlist,$htfid,$postid,$fid,$tid,$userpath,$tdtime,$db_moneyname,$fid_Pconcle,$db_tformat;
	global $db_dtpostrvrc,$db_dtreplyrvrc,$db_dtpostmoney,$db_dtreplymoney;
	$msg_infofile=readover("$dbpath/$fid/status.php");
	
	if($fid_Pconcle!=''){
		list($P_Prvrc,$P_Rrvrc,$P_Pmoney,$P_Rmoney,$P_Drvrc,$P_Dmoney)=explode("~",$fid_Pconcle);
		is_numeric($P_Prvrc) && $db_dtpostrvrc=$P_Prvrc;
		is_numeric($P_Rrvrc) && $db_dtreplyrvrc=$P_Prvrc;
		is_numeric($P_Pmoney) && $db_dtpostmoney=$P_Prvrc;
		is_numeric($P_Rmoney) && $db_dtreplymoney=$P_Prvrc;
	}
	list($sta_fb,$sta_topic,$sta_author,$sta_time,$sta_tid,$sta_timestamp,$sta_atcnum,$sta_tpcnum,$sta_childtpcnum,$sta_null1,$sta_null2)=explode("|",$msg_infofile);
	$sta_atcnum++;
	if($top_post){
		$sta_tpcnum++;
	}
	else
		$atc_title="Re:$atc_title";//����Ϊ�˿ռ临�Ӷ����Իظ�ʱ$atc_titleΪ��!
	$use_date=date($db_tformat,$timestamp);
	$new_line="<?die;?>|$atc_title|$htfid|$use_date|{$fid}&tid={$tid}|$timestamp|$sta_atcnum|$sta_tpcnum|$sta_childtpcnum|$sta_null1|";//status.php���ݿ�����������
	writeover("$dbpath/$fid/status.php",$new_line);
	if($fatherid){
		$fatherstfile=readover("$dbpath/$fatherid/status.php");
		list($fa_fb,$fa_topic,$fa_author,$fa_time,$fa_tid,$fa_timestamp,$fa_atcnum,$fa_tpcnum,$fa_childtpcnum,$fa_null1)=explode("|",$fatherstfile);
		$fa_atcnum++;
		if($top_post){
			$fa_childtpcnum++;
		}
		$fatheragin="<?die;?>|$atc_title|$htfid|$use_date|{$fid}&tid={$tid}|$timestamp|$fa_atcnum|$fa_tpcnum|$fa_childtpcnum|$fa_null1|";
		writeover("$dbpath/$fatherid/status.php",$fatheragin);
	}
	list($dir_fb,$dir_name,$dir_pwd,$dir_email,$dir_publicmail,$dir_groupid,$dir_icon,$dir_gender,$dir_regdate,$dir_sign,$dir_introduce,$dir_oicq,$dir_icq,$dir_homepage,$dir_from,$dir_honor,$dir_post,$dir_rvrc,$dir_money,$dir_lasttime,$dir_thistime,$dir_birth,$dir_receivemail,$dir_tuiji,$dir_lastpost,$dir_losttitle,$dir_lastaddrst,$dir_yz,$dir_todaypost,$dir_group,$dir_sx,$dir_star,$dir_xuni,$dir_badman,$dir_onlinetime,$dir_signchange,$dir_null)=$htfdb;
	list($bbsfb,$bbstpc,$bbsatc,$bbstoday,$bbsyestoday,$bbsmost,$bbspostcontrol,$bbsbirthcontrol,$bbsstar,$bbsrich,$bbslucher,$bbsbirthman)=explode("|",readover("data/bbsatc.php"));
	$userarraypost=explode("|",readover("$userpath/$bbsstar.php")); //���շ������
	if($dir_todaypost>=$userarraypost[28])
		$bbsstar=$htfid;
	if($top_post){
		$bbstpc++;
	}
	if($bbspostcontrol<$tdtime){//����0��ʱ����Ʒ���//
	
		$bbsyestoday=$bbstoday;
		$bbstoday=0;
		//ɾ������ע��ip��¼//
		if(file_exists('data/ip_cache.txt'))
			unlink('data/ip_cache.txt');
	}
	$bbstoday++;
	if($bbsmost<$bbstoday)
		$bbsmost=$bbstoday;
	$bbspostcontrol=$tdtime;
	$bbsatc++;
	$writebbsatcdb="<?die;?>|$bbstpc|$bbsatc|$bbstoday|$bbsyestoday|$bbsmost|$bbspostcontrol|$bbsbirthcontrol|$bbsstar|$bbsrich|$bbslucher|$bbsbirthman|";
	writeover("data/bbsatc.php",$writebbsatcdb);
	if(file_exists("data/set_cache.php")){

		list($set_fb,$set_control,$set_tdtime,$set_null)=explode("|",readover("data/set_cache.php"));
		$dtdirtime=$set_control*$db_hour;
		if($dtdirtime>24)$dtdirtime=0;
	}
	else
		$dtdirtime=0;
	if(($timestamp-$set_tdtime)>$dtdirtime*3600){

		include "./require/postconcle.php";
	}
	//if($tdtime>=$userarraypost[24])
	//	$userarraypost[28]='0';
	if($groupid!='guest'){
		if($tdtime>=$dir_lastpost)
			$dir_todaypost=1;
		else
			$dir_todaypost++;
		$dir_lastpost=$timestamp;
		$dir_post++;
		if(is_numeric($groupid)){//�жϽ���������
			include './data/level.php';
			$nextgroupid=$groupid+1;
			if($lpost[$nextgroupid] && $dir_post>=$lpost[$nextgroupid])
				$dir_groupid++;//��Ա�ĵȼ�����
		}
		if($checkuplode==1) $dir_money=$dir_money-$gp_uploadmoney;
		if($top_post){
			$dir_rvrc=$dir_rvrc+$db_dtpostrvrc;
			$dir_money=$dir_money+$db_dtpostmoney;
		}
		else{
			$dir_rvrc=$dir_rvrc+$db_dtreplyrvrc;
			$dir_money=$dir_money+$db_dtreplymoney;
		}

		$dir_losttitle='';//�����ݶ�����!
		$dir_lastaddrst="$fid&tid=$tid";//��󷢱�����
		//$userdb=implode("|",$userarray);
		$userdb="$dir_fb|$dir_name|$dir_pwd|$dir_email|$dir_publicmail|$dir_groupid|$dir_icon|$dir_gender|$dir_regdate|$dir_sign|$dir_introduce|$dir_oicq|$dir_icq|$dir_homepage|$dir_from|$dir_honor|$dir_post|$dir_rvrc|$dir_money|$dir_lasttime|$dir_thistime|$dir_birth|$dir_receivemail|$dir_tuiji|$dir_lastpost|$dir_losttitle|$dir_lastaddrst|$dir_yz|$dir_todaypost|$dir_group|$dir_sx|$dir_star|$dir_xuni|$dir_badman|$dir_onlinetime|$dir_signchange|$dir_null";
		writeover("$userpath/$htfid.php",$userdb);
	}else{
		Cookie('userlastptime',$timestamp);
	}
}
function check_data($type="post"){

	global $atc_content,$atc_title,$msg_info,$vt_select,$db_postmin,$db_postmax,$userrvrc,$atc_rvrc,$atc_money,$usermoney;
	$check=1;
	if (strlen($atc_content)<=$db_postmin){
		$msg_info="�������ڹ���Աָ���ĳ���.��СΪ$db_postmin";
		$check=0;
	}
	if (strlen($atc_content)>=$db_postmax){
		$msg_info="���³�������Աָ���ĳ���.���Ϊ$db_postmax";
		$check=0;
	}
	if(!$atc_title){
		$msg_info="����Ϊ��.����д����!"; 
		$check=0;
	}
	if (strlen($atc_title)>=120){
		$msg_info="����̫����.�벻Ҫ����120�ֽ�!"; 
		$check=0;
	}
	if ($atc_rvrc>abs($userrvrc)){
		$msg_info="����д������ֵ������Ŀǰ������,���ɴ���$userrvrc"; 
		$check=0;
	}
	if ($atc_money>100){
		$msg_info="����д�Ľ�Ǯ������Ŀǰ�Ľ�Ǯ,���ɴ���100"; 
		$check=0;
	}
	#ͶƱ�Ŀ�ѡ��  δ���
	if ($type=="vote" && empty($vt_select)){
		$msg_info="�����ܿ�ѡ��";
		$check=0;
	}
	return $check;
}

//�Զ�urlת�亯��
function autourl($message){
	global $db_autoimg;
	if($db_autoimg==1){
		$message= preg_replace(array(
					"/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\��]+\.gif)/i",
					"/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\��]+\.jpg)/i"
				), array(
					"[img]\\1\\3[/img]",
					"[img]\\1\\3[/img]"
				), ' '.$message);
	}
	$message= preg_replace(	array(
					"/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp|gopher|news|telnet|mms|rtsp):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\��]+)/i",
					"/(?<=[^\]a-z0-9\/\-_.~?=:.])([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4}))/i"
				), array(
					"[url]\\1\\3[/url]",
					"[email]\\0[/email]"
				), ' '.$message);
	return $message;
}
?>