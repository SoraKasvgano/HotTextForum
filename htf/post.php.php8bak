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
* 新注册用户发帖控制
*/
if($db_postallowtime && $timestamp-$htfdb[8]<$db_postallowtime*3600){

	showmsg("注册用户{$db_postallowtime}小时内不能发表文章。");
}


list($forumcount,$forumarray)=getforumdb();
htf_forumcheck();

$guide="{$fid_name} 发贴区";
$secondname="$fid_name";
$secondurl="forum.php?fid=$fid";
$basename="post.php";



$top_post=0;

/**
* 若允许游客发帖，设置游客的威望为0
*/
$groupid=='guest' && $userrvrc=0;

if(!$action) $action="new";
/**
* 调查版块只允许发起投票
*/
if($fid_type=='vote' && $action=='new' && $htfid!=$manager)
	showmsg('投票版块只允许发起投票');
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
		$pt_downrvrc1="所需威望:<input type='text' name='atc_downrvrc1' value='0' size='1'>";
		$pt_downrvrc2="所需威望:<input type='text' name='atc_downrvrc2' value='0' size='1'>";
		$pt_downrvrc3="所需威望:<input type='text' name='atc_downrvrc3' value='0' size='1'>";
		$pt_downrvrc4="所需威望:<input type='text' name='atc_downrvrc4' value='0' size='1'>";
	}
}
elseif($_POST['step']){
	require './require/dbmodify.php';
	require './require/bbscode.php';
	include("data/wordsfb.php");
	if($wordsfb){
		foreach($wordsfb as $key=>$value){
			if(strpos($atc_title,$key)!==false){
				showmsg("您的主题有不良言语 , 请返回");
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
* 论坛安全屏蔽
*/
if($forbiduser==$htfid && $groupid!='banned'){

	$_COOKIE['forbiduser']='';
	Cookie('forbiduser','',0);
}
if($_COOKIE['forbiduser'] && $htfid!=$manager){

	showmsg("您已经被禁言!");
}
if($groupid=='banned'){//禁止受限制用户发言

    Cookie('forbiduser',$htfid);
	showmsg("由于种种原因,您被禁言,论坛只面向友好的朋友！");
}

/**
* 需要验证用户只有通过管理员验证后才能发帖
*/
if($groupid=='newrg'){

	showmsg("你还没通过管理员验证,需要通过管理员验证才能发言！");
}
if($groupid!='guest'){
	//$htfdb define in global.php
	//$userrvrc=floor($htfdb[17]/10);
	$usermoney=$htfdb[18];
	if($htfdb[27]>2){/*需要验证的为时间戳,一定大于2*/

		showmsg("您的电子邮件是:{$htfdb[3]}您将收到一封验证邮件以确认邮箱的有效性，用户收到邮件并激活账号后才能拥有正常的权限!<br>请到您注册所用的Email里查看验证邮件并激活您的用户名 谢谢!");
	}
	$userlastptime=$htfdb[24];

}
/**
* 灌水预防
*/

if ($action!="modify" && $gp_postpertime && $timestamp-$userlastptime<=$gp_postpertime){

	showmsg("灌水预防机制已经打开，在{$gp_postpertime}秒内不能发贴");
}
$fid_father && $fatherid=$fid_father;

if($db_signhtfcode){

	$htfcode="<br><a href=\"faq.php?faqjob=1#5\"> htf Code 开启</a>";
	$db_htfpic['pic'] ? $htfcode.="<br> [img] - 开启" : $htfcode.="<br> [img] - 关闭";
	$db_htfpic['flash'] ? $htfcode.="<br> [flash] - 开启" : $htfcode.="<br> [flash] - 关闭";
}
else{
	$htfcode="<br><a href=\"faq.php?faqjob=1#5\">htf Code</a>关闭";
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
* 论坛发帖信息函数
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
		$atc_title="Re:$atc_title";//本来为了空间复杂度所以回复时$atc_title为空!
	$use_date=date($db_tformat,$timestamp);
	$new_line="<?die;?>|$atc_title|$htfid|$use_date|{$fid}&tid={$tid}|$timestamp|$sta_atcnum|$sta_tpcnum|$sta_childtpcnum|$sta_null1|";//status.php数据库段请参照这里
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
	$userarraypost=explode("|",readover("$userpath/$bbsstar.php")); //今日发贴最多
	if($dir_todaypost>=$userarraypost[28])
		$bbsstar=$htfid;
	if($top_post){
		$bbstpc++;
	}
	if($bbspostcontrol<$tdtime){//昨日0点时间控制发贴//
	
		$bbsyestoday=$bbstoday;
		$bbstoday=0;
		//删除当天注册ip记录//
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
		if(is_numeric($groupid)){//判断将管理层隔开
			include './data/level.php';
			$nextgroupid=$groupid+1;
			if($lpost[$nextgroupid] && $dir_post>=$lpost[$nextgroupid])
				$dir_groupid++;//会员的等级提升
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

		$dir_losttitle='';//此数据段暂留!
		$dir_lastaddrst="$fid&tid=$tid";//最后发表链接
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
		$msg_info="文章少于管理员指定的长度.最小为$db_postmin";
		$check=0;
	}
	if (strlen($atc_content)>=$db_postmax){
		$msg_info="文章超过管理员指定的长度.最大为$db_postmax";
		$check=0;
	}
	if(!$atc_title){
		$msg_info="标题为空.请填写标题!"; 
		$check=0;
	}
	if (strlen($atc_title)>=120){
		$msg_info="标题太长了.请不要超过120字节!"; 
		$check=0;
	}
	if ($atc_rvrc>abs($userrvrc)){
		$msg_info="你填写的威望值大于你目前的威望,不可大于$userrvrc"; 
		$check=0;
	}
	if ($atc_money>100){
		$msg_info="你填写的金钱大于你目前的金钱,不可大于100"; 
		$check=0;
	}
	#投票的空选项  未完成
	if ($type=="vote" && empty($vt_select)){
		$msg_info="不接受空选项";
		$check=0;
	}
	return $check;
}

//自动url转变函数
function autourl($message){
	global $db_autoimg;
	if($db_autoimg==1){
		$message= preg_replace(array(
					"/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+\.gif)/i",
					"/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+\.jpg)/i"
				), array(
					"[img]\\1\\3[/img]",
					"[img]\\1\\3[/img]"
				), ' '.$message);
	}
	$message= preg_replace(	array(
					"/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp|gopher|news|telnet|mms|rtsp):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+)/i",
					"/(?<=[^\]a-z0-9\/\-_.~?=:.])([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4}))/i"
				), array(
					"[url]\\1\\3[/url]",
					"[email]\\0[/email]"
				), ' '.$message);
	return $message;
}
?>