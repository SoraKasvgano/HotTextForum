<?php
$mtime = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];
empty($db_debug) && error_reporting(0);
unset($GLOBALS,$_ENV,$HTTP_ENV_VARS,$_REQUEST,$HTTP_POST_VARS,$HTTP_GET_VARS,$HTTP_POST_FILES,$HTTP_COOKIE_VARS);
if(!ini_get('register_globals'))
{
	extract($_GET,EXTR_SKIP);
	extract($_POST,EXTR_SKIP);
	extract($_FILES,EXTR_SKIP);
	extract($_COOKIE,EXTR_SKIP);
}
foreach($_POST as $_key=>$_value){
	$_POST[$_key]=str_replace('|','��',str_replace("$","&ensp;$",$_POST[$_key]));
	$$_key=$_POST[$_key];
}
foreach($_GET as $_key=>$_value){
	$_GET[$_key]=str_replace('|','��',str_replace("$","&ensp;$",$_GET[$_key]));
	$$_key=$_GET[$_key];
}
$fid=(int)$fid;
$tid=(int)$tid;
$REQUEST_URI=$_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI']:$_SERVER['PHP_SELF'];

if(getenv('HTTP_CLIENT_IP')){
	$onlineip=getenv('HTTP_CLIENT_IP');
}elseif(getenv('HTTP_X_FORWARDED_FOR')){
	$onlineip=getenv('HTTP_X_FORWARDED_FOR');
}else{
	$onlineip=$HTTP_SERVER_VARS['REMOTE_ADDR'];
}
$onlineip =substrs($onlineip,16);

isset($_COOKIE['skinco']) && $skin=$_COOKIE['skinco'];

/**
* ��̳��������
*/
require("data/manager.php");
require("data/config.php");
$db_obstart==1 ? ob_start('ob_gzhandler') : ob_start();
$db_http!='N' ? $imgpath=$db_http:$imgpath='./'.$picpath;
$htf_version='1.0.7';
$db_olsize=96;

/**
* ��̳����
*/
if ($db_bbsifopen==0){
	session_set_cookie_params(0,$ckpath,$ckdomain);
	session_name('S');
	session_save_path('session');
	session_start();
	if ($_SESSION['htfadminid']!=$manager || $_SESSION['htfadminpwd']!=$manager_pwd){
		showmsg($db_whybbsclose);
	}
}
unset($manager_pwd);
/**
* ʱ���:$timestamp �ͽ�������ʱ���:$tdtime
*/
$timestamp=time();
$db_cvtime!=0 && $timestamp=$timestamp+$db_cvtime*60;
$cookietime = $timestamp+31536000;
$td=floor($timestamp/3600);
$t=getdate($timestamp);
$hour=$t['hours'];
$tdtime=($td-$hour)*3600;

/**
* htf��Ա����ʱ�����
*/
$addontime=0;
$onbbstime=$timestamp-$lastvisit;
if($onbbstime<3600 && $db_ifonlinetime){
	$addontime=1;
}

/**
* htf �û���֤
*/
$runfc='N';
if($timestamp-$lastvisit>$db_onlinetime || ($_COOKIE['lastfid']!='' && $star_action=='hm') || (isset($fid) && $fid!=$_COOKIE['lastfid']))
{
	$runfc='Y';
	Cookie('lastfid',$fid);
	include "./require/userglobal.php";
}
$htfid=str_replace('..','',$_COOKIE['htfid']);
$htfpwd=$_COOKIE['htfpwd'];
if($htfid!='' && file_exists("$userpath/$htfid.php") && strlen($htfpwd)>=16){
	$htfdb=userloginfo();
	$groupid=$htfdb[5];
	$userrvrc=floor($htfdb[17]/10);
	if($runfc=='Y' && $htfid!='')
	{
		Cookie('forum_online','',0);
		$gotnewmsg=getusermsg($htfid);
		$groupid=$htfdb[5]=savecheck($groupid);
		$alt_offset=addonlinefile($ol_offset,$htfdb[1]);
		if($alt_offset!=$ol_offset)Cookie('ol_offset',$alt_offset,0);
	}
}else{
	$groupid='guest';
	unset($htfdb);$htfid='';
	if($runfc=='Y')
	{
		$alt_offset=addguestfile($ol_offset);
		if($alt_offset!=$ol_offset)Cookie('ol_offset',$alt_offset,0);
	}
}

/**
* ����ˢ�¿���
*/
if ($db_refreshtime!=0)
{
	if($REQUEST_URI==$_COOKIE['lastpath'] && $onbbstime<$db_refreshtime){

		showmsg("������ʾ��ֹ,ԭ��:����ͬһURL��ˢ��ʱ��С��{$db_refreshtime}��");
	}
	Cookie('lastpath',$REQUEST_URI);
}
Cookie('lastvisit',$timestamp);

/**
* IP��ֹ
*/
useripban();

/**
* �������cookie ����
*/
if(isset($_COOKIE['pwdcheck']) && $groupid=='guest') Cookie('pwdcheck','',0);

/**
* �û���Ȩ��
*/
if(!@include "./data/groupdb/group_$groupid.php")include "./data/groupdb/group_default.php";


/**
* �����Ա����
*/
$surpadmin=$manager;
if (file_exists("data/admin/$htfid.php") && $htfid!='')$manager=$htfid;

/*
* * * ����Ϊ htf Board ͨ�ú��� * * *
*/
function showmsg($msg_info,$url='',$time=1){
	extract($GLOBALS, EXTR_SKIP);
	global $stylepath,$tablewidth,$tplpath;
	include_once "./header.php";
	$msg_guide=headguide("��̳��ʾ");
	include_once PrintEot('showmsg');
}
function Cookie($ck_Var,$ck_Value,$ck_Time='F'){
  	global $cookietime,$ckpath,$ckdomain;
    if($ck_Time=='F') $ck_Time = $cookietime;
    setCookie($ck_Var,$ck_Value,$ck_Time,$ckpath,$ckdomain);
}
function getforumdb()
{
	$forumarray=openfile('data/forumdata.php');
	$forumcount=count($forumarray);
	return array($forumcount,$forumarray);
}
function useripban()
{
	global $forbidip,$htfid,$onlineip,$imgpath,$stylepath;
	include "./data/ipbans.php";
	$count = count($banip);
	for ($i=0; $i<$count; $i++){
		if (empty($banip[$i])) continue;
		if (strpos($onlineip,$banip[$i])!==false ){
			$error='ipbanned';
			break;
		}
	}
	if(isset($error)){
		include "./header.php";
		$msg_info="����IP������,��������̳,��Ҫ���⵷��,��������,����ϵ����Ա!";
		include PrintEot('showmsg');
	}
}
function SafePath($Path){
	if(strpos($Path,'..')!==false){
		showmsg('�Ƿ�����,�뷵��.....');
	}

}
function gets($filename,$value)
{
	SafePath($filename);
	if($handle=@fopen($filename,"rb")){
		flock($handle,LOCK_SH);
		$getcontent=fread($handle,$value);//fgets����
		fclose($handle);
	}
	return $getcontent;
}
function readover($filename,$method="rb")
{
	SafePath($filename);
	if($handle=@fopen($filename,$method)){
		flock($handle,LOCK_SH);
		$filedata=fread($handle,filesize($filename));
		fclose($handle);
	}
	return $filedata;
}
function writeover($filename,$data,$method="rb+")
{
	SafePath($filename);
	touch($filename);/*�ļ��������򴴽�֮.���Բ���file_exists��֤�������������ļ���������.���Խ��Ч���൱*/
	//if(!file_exists($filename)) $method="ab";
	$handle=fopen($filename,$method);
	flock($handle,LOCK_EX);
	fputs($handle,$data);
	if($method=="rb+") ftruncate($handle,strlen($data));
	fclose($handle);
}
function readlock($filename,$method="rb+")
{
	SafePath($filename);
	$file=fopen($filename,$method);
	flock($file,LOCK_EX);
	$filedata=fread($file,filesize($filename));
	rewind($file);
	return array($file,$filedata);
}
function writelock($filename,$data,$handle)
{
	fputs($handle,$data);
	ftruncate($handle,strlen($data));
	fclose($handle);
}
/*
*�˺��������ļ����ڻ�������Ϊ�ն��᷵�� $filedb[0]="" ���ʱע��ѭ��
*/
function openfile($filename)
{
	$filedata=readover($filename);
	$filedata=str_replace("\n","\n<:htf:>",$filedata);
	$filedb=explode("<:htf:>",$filedata);
	//array_pop($filedb);
	$count=count($filedb);
	if($filedb[$count-1]==''||$filedb[$count-1]=="\r"){unset($filedb[$count-1]);}
	if(empty($filedb)){$filedb[0]="";}
	return $filedb;
}
function PrintEot($tmp,$EXT="htm")
{
	global $tplpath;
	SafePath($tmp);
	if(empty($tmp)) $tmp='N';
	file_exists("./tmp/$tplpath/$tmp.$EXT")?$path="./tmp/$tplpath/$tmp.$EXT":$path="./tmp/htf/$tmp.$EXT";
	return $path;
}
function footer()
{
	global $db_obstart,$db_footertime,$starttime,$mtablewidth,$footbg,$db_ceoconnect,$htf_version,$imgpath,$stylepath,$db_bbsname,$tablewidth;
	$db_obstart==1 ? $ft_gzip="Gzip enabled":$ft_gzip="Gzip disabled";
	if ($db_footertime==1){
		$mtime = explode(' ', microtime());
		$totaltime = number_format(($mtime[1] + $mtime[0] - $starttime), 6);
		$htf_spend="Processed in $totaltime second(s)";
	}
	include PrintEot('footer');
	exit;
}
function headguide($secondname="",$secondurl="",$thirdname="",$thirdurl="",$guide="")
{
	global $db_bbsname,$imgpath,$stylepath,$tablewidth,$fid;
	$homepic = "<img src='$imgpath/$stylepath/index/home.gif' align=absbottom>";/*Ϊ������ͼƬ�ļ�*/
	$headguide="<table width=$tablewidth border=0 cellspacing=0 cellpadding=0 align=center><tr><td align=left>$homepic <span class=bold><a href='index.php'>$db_bbsname</a>";
	if ($secondname){
		if($secondurl!="")
			$headguide.="&nbsp;->&nbsp;<a href='$secondurl'>$secondname</a>";
		else
			$headguide.="&nbsp;->&nbsp;$secondname";
	}
	if ($thirdname){
		if($thirdurl!="")
			$headguide.="&nbsp;->&nbsp;<a href='$thirdurl'>$thirdname</a>";
		else
			$headguide.="&nbsp;->&nbsp;$thirdname";
	}
	$headguide.="</span></td><td align=right>$guide</td></tr><tr><td height=5></td></tr></table><br>";
	return $headguide;
}
function refreshto($URL,$content,$statime=1){
	extract($GLOBALS, EXTR_SKIP);
	if($db_ifjump){
		ob_end_clean();
		global $tplpath;/*ģ��Ŀ¼����*/
		$db_obstart==1 ? ob_start('ob_gzhandler') : ob_start();
		$db_http!='N' ? $imgpath=$db_http:$imgpath=$picpath;
		if (empty($skin)) $skin=$db_defaultstyle;
		if(file_exists("style/$skin.php") && strpos($skin,'..')===false){
			@include ("style/$skin.php");
		}else{
			@include ("style/htf.php");
		}
		include PrintEot('css');
		include PrintEot('refreshto');
		exit;
	}
	else
		header("Location: $URL");/*header�Բ���NT�ռ�ʹ��ʱ�����д���*/
}
function safeconvert($msg){
	$msg = str_replace('&amp;','&',$msg);
	$msg = str_replace('&nbsp;',' ',$msg);
	if(strpos($msg,"&ensp;")===false) {
		$msg = str_replace('&','&amp;',$msg);/*�Լ�����̳��Ч*/
	}
	$msg = str_replace('"','&quot;',$msg);
	$msg = str_replace("'",'&#39',$msg);
	$msg = str_replace("\t","   &nbsp;  &nbsp;",$msg);
	$msg = str_replace("<","&lt;",$msg);
	$msg = str_replace(">","&gt;",$msg);
	$msg = str_replace("\r","",$msg);
	$msg = str_replace("\n","<br />",$msg);
	$msg = str_replace("|","��",$msg);//&#124
	$msg = str_replace("   "," &nbsp; ",$msg);#�༭��ʽʱ�Ƚ���Ч
	return $msg;
}
function dtchange($user,$wwz,$postn,$money){
	global $userpath;
	$filename="$userpath/$user.php";
	if(file_exists($filename)){
		list($file,$userinfo)=readlock($filename);
		$userarray=explode("|",$userinfo);
		$userarray[16]=$userarray[16]+$postn;
		$userarray[17]=$userarray[17]+$wwz;
		$userarray[18]=$userarray[18]+$money;
		$dbuser=implode("|",$userarray);
		writelock($filename,$dbuser,$file);
	}
}

function userloginfo(){
	global $htfid,$htfpwd,$timestamp,$db_onlinetime,$addontime,$onbbstime,$userpath,$onlineip,$db_ipcheck;
	$filename="$userpath/$htfid.php";
	$usertemp=readover($filename);
	$detail=explode("|",$usertemp);
	/**
	* ��ip��ͬʱ�����ǰ��λ����ͬ.�����cookie�Զ��˳�
	*/
	if($detail[29]!=$onlineip){
		$iparray=explode(".",$onlineip);
		if(strpos($detail[29],$iparray[0].'.'.$iparray[1])===false) $loginout='Y';
	}
	if($detail[2]!=$htfpwd || (isset($loginout) && $db_ipcheck==1)){
		include './require/checkpass.php';
		Loginout($detail);/*�˴���Ҫ�� header �ڲ���NT�ϻ���ִ���,*/
	}
	if($timestamp-$detail[20]>$db_onlinetime){
		$detail[19]=$detail[20];
		$detail[20]=$timestamp;
	}
	if($addontime==1){
	   $detail[34]+=$onbbstime;
	}
	if($detail[20]==$timestamp || $addontime==1){
	   $useradtime=implode("|",$detail);
	   writeover($filename,$useradtime);
	}
	return $detail;
}
function substrs($content,$length) {
	if(strlen($content)>$length){
		$num=0;
		for($i=0;$i<$length-3;$i++) {
			if(ord($content[$i])>0xa0)$num++;
		}
		$num%2==1 ? $content=substr($content,0,$length-4):$content=substr($content,0,$length-3);
		$content.=' ...';
	}
	return $content;
}
function Ex_plode($df,$detail,$key){
	$array=explode($df,$detail);
	for($i=0;$i<$key;$i++){
		if(!isset($array[$i])) $array[$i]='';
	}
	return $array;
}
?>