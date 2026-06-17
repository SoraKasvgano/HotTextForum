<?php
!defined('SAFE') && exit('Forbidden');
$a='#739ace';
$b='#ffffff';
$c='#ffffff';
$htf_version="1.0.7";
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
	$_POST[$_key]=str_replace('|','│',$_value);
	$$_key=$_POST[$_key];
}

foreach($_GET as $_key=>$_value){
	$_GET[$_key]=str_replace('|','│',$_value);
	$$_key=$_GET[$_key];
}
$REQUEST_URI=$_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI']:$_SERVER['PHP_SELF'];
$sysos=PHP_OS;
$cptop="<table width=95% align=center cellspacing=0 cellpadding=0 bgcolor=739ace><tr><td><table width=100% cellspacing=1 cellpadding=3><tr><td class=head>";
$cpbottom="</td></tr></table></td></tr></table>";
$admintopad="<tr><td colspan=2><table width=100% border=0 cellspacing=2 cellpadding=5 ><tr class=head><td width=30%><a href='http://www.htf.1m.cn'><font color='#ffffff'><span class=bold>检查最新版本</span></font></a></td><td width=30% align=center ><a href='http://www.htf.1m.cn' target=_blank><font color='#ffffff'><span class=bold>htf 用户交流</font></span></a></td><td width=30% align=right><a href='index.php'><font color='#ffffff'><span class=bold>论坛首页</span></font></a></TD></tr></table></td></tr>";
$adminbottomad="<tr><td bgcolor=$b valign=middle align=left colspan=2><blockquote><br><br><br><br><hr size=0 noshade color='#000000' width=100%><center><font style=font-size: 11px; font-family: Tahoma, Verdana, Arial>Powered by <a href='http://www.htf.1m.cn' target=_blank> <span class=bold>htf</span> $htf_version</a> &nbsp;&copy; 2005,<b style='color: #FF9900'>htf.1m.cn</span> <span class=bold><a href='http://www.htf.1m.cn' target=_blank>HTF</a></span></font></blockquote></td></tr>";
$admin_login_ip=$_SERVER['REMOTE_ADDR'];
$timestamp=time();
$oldstarttime=date("Y-m-d H:i",$timestamp);


if(strpos($REQUEST_URI,'?')===false || $adminjob=='settings') $ob_check=1;/*解决打开 ob_gzhandler 进后台出现下载问题*/
require "./data/config.php";
require "./data/level.php";

if(!is_writeable('session') && !chmod('session',0777)){
	die('请设置 session 目录为可写模式(777)');
}else{
	session_set_cookie_params(0,$ckpath,$ckdomain);
	session_name('S');
	session_save_path('session');
	session_cache_limiter('private, must-revalidate');
	session_start();
}
if($adminjob=='quit'){
	session_unset();
	eval("\$leftforum = \"".gettmp('adminlogin')."\";");
	adminmsg('成 功 退 出 管 理<br><br><a href=index.php>进 入 首 页</a>');
}


$bbsrecordfile="data/admin_record.php";
$F_count=F_L_count($bbsrecordfile,2000);
$L_T=1200-($timestamp-filemtime($bbsrecordfile));
$L_left=15-$F_count;
if($F_count>15 && $L_T>0){
	eval("\$leftforum = \"".gettmp('adminlogin')."\";");
	adminmsg("已经连续 $F_count 次进行无效登陆,您将在 20 分钟内无法正常登陆后台,还剩余 $L_T 秒");
}

if (file_exists("install.php")){
	eval("\$leftforum = \"".gettmp('adminlogin')."\";");
	adminmsg("install.php 文件仍然在您的服务器上，请马上利用 FTP 来将其删除！！ 当你删除之后，刷新本页面重新进入管理中心。");
}
if (file_exists("data/manager.php")) 
	include("./data/manager.php");
else{
	eval("\$leftforum = \"".gettmp('adminlogin')."\";");
	adminmsg("论坛管理员文件不存在，请重新上传 manager.php文件");
}

if($_POST['admin_pwd'] && $_POST['admin_name']){
	$_SESSION['htfadminid']=$admin_name=$_POST['admin_name'];
	$_SESSION['htfadminpwd']=$admin_pwd=md5($_POST['admin_pwd']);
}else{
	$admin_name=$_SESSION['htfadminid'];
	$admin_pwd=$_SESSION['htfadminpwd'];
}

/*
*管理员验证
*/
if (($admin_name!=$manager || $admin_pwd!=$manager_pwd || strlen($admin_pwd)<16) && !checkpass($admin_name,$admin_pwd)) 
{
	if ($admin_name<>"")
	{
		session_unset();
		$new_adminrecord="<?die;?>|$admin_name|$_POST[admin_pwd]|Logging Failed|$admin_login_ip|$timestamp|\n";
		writeover($bbsrecordfile,$new_adminrecord,"ab");
	}
	eval("\$leftforum = \"".gettmp('adminlogin')."\";");
	
	if($_POST['Login_f']==1){
		adminmsg("密码错误,您还可以尝试 $L_left 次");
	}

	eval("dooutput(\"".gettmp('tpl_login')."\");");
}else{
	$_SESSION['htfadminid']=$_SESSION['htfadminid'];
	$_SESSION['htfadminpwd']=$_SESSION['htfadminpwd'];
}
eval("\$leftforum = \"".gettmp('admin_left')."\";");

//管理员日记
$new_adminrecord="<?die;?>|$admin_name||$basename?adminjob=$adminjob$action|$admin_login_ip|$timestamp|\n";
writeover($bbsrecordfile,$new_adminrecord,"ab");
//日记结束


function Cookie($ck_Var,$ck_Value,$ck_Time='F',$ck_O='Y'){
  	global $cookietime,$ckpath,$ckdomain;
    $ck_Time=='F' && $ck_Time = $cookietime;
	$ck_O=='Y' ? setCookie($ck_Var,$ck_Value,$ck_Time,$ckpath,$ckdomain):setCookie($ck_Var,$ck_Value,$ck_Time);
}
//获得模版函数
function gettmp($tmp,$EXT="htm") 
{
	$path='admin';
	$thiss=implode("",openfile("tmp/$path/$tmp.$EXT"));
	$thiss=addslashes($thiss);
	return $thiss;
}
//页面输出函数
function dooutput($vartext)
{
	global $db_footertime,$starttime,$db_obstart,$ob_check;
	//ob_end_clean();
	!$ob_check && $db_obstart==1 ? ob_start('ob_gzhandler') : ob_start();
	$vartext = stripslashes($vartext);
    echo "$vartext";
    exit;
}
function checkpass($admin_name,$admin_pwd)
{
	global $checkpower;
	if (!$admin_name) return false;
	if (!$admin_pwd) return false;
	if (!file_exists("data/admin/$admin_name.php") || strpos($admin_name,"..")!==false || strpos($admin_name,".")!==false) return false;
	$admin_info=explode("|",readover("data/admin/$admin_name.php"));
	$adminpwd=$admin_info[2];
	$checkpower=$admin_info[3];
	if ($admin_pwd==$adminpwd)
		return true;
	else 
		return false;
}

function gets($filename,$value)
{
	if($handle=@fopen($filename,"rb")){
		flock($handle,LOCK_SH);
		$getcontent=fread($handle,$value);//fgets调试
		fclose($handle);
	}
	return $getcontent;
}
function readover($filename,$method="rb",$readsize="D")
{
	$filesize=filesize($filename);
	if($readsize!="D") $filesize=min($filesize,$readsize);/*备份时解决list的负载控制*/
	if($handle=@fopen($filename,$method)){
		flock($handle,LOCK_SH);
		$filedata=fread($handle,$filesize);
		fclose($handle);
	}
	return $filedata;
}
function writeover($filename,$data,$method="rb+")
{
	touch($filename);/*文件不存在则创建之.可以采用file_exists验证并其他创建文件函数代替.测试结果效率相当*/
	$handle=fopen($filename,$method);
	flock($handle,LOCK_EX);
	fputs($handle,$data);
	if($method=="rb+") ftruncate($handle,strlen($data));
	fclose($handle);
}
function openfile($filename,$style='Y')
{
	if($style=='Y'){
		$filedata=readover($filename);
		$filedata=str_replace("\n","\n<:htf:>",$filedata);
		$filedb=explode("<:htf:>",$filedata);
		//array_pop($filedb);
		$count=count($filedb);
		if($filedb[$count-1]==''||$filedb[$count-1]=="\r"){unset($filedb[$count-1]);}
		if(empty($filedb)){$filedb[0]="";}
		return $filedb;
	}else{
		$filedb=file($filename);
		return $filedb;
	}
}
function adminmsg($msg,$ifjump=0,$time=0)
{
	global $admintopad,$a,$b,$c,$adminbottomad,$basename,$leftforum,$url;
	$ifecho=array("jump1"    => "<!--","jump2"    => " -->");
	if($ifjump==1){$ifecho[jump1]="";$ifecho[jump2]="";}
	if(empty($url)) $url=$basename;
	eval("dooutput(\"".gettmp('admin_msg')."\");");
}
function safeconvert($msg)
{
	$msg = str_replace("\t","",$msg);
	$msg = str_replace("<","&lt;",$msg);  
	$msg = str_replace(">","&gt;",$msg);
	$msg = str_replace("\r","",$msg);
	$msg = str_replace("\n","<br />",$msg);
	$msg = str_replace("|","│",$msg);
	$msg = str_replace("   "," &nbsp; ",$msg);#编辑时比较有效
	return $msg;
}
function ieconvert($msg)
{
	$msg = str_replace('"','&quot;',$msg);
	$msg = str_replace("\t","",$msg);
	$msg = str_replace("\r","",$msg);
	$msg = str_replace("\n","<br />",$msg);
	$msg = str_replace("|","│",$msg);
	$msg = str_replace("   "," &nbsp; ",$msg);#编辑时比较有效
	return $msg;
}
function deldir($path)
{
	$deldb=@opendir("$path/");
	while (false!==($delfile=@readdir($deldb)))
	{
		if (($delfile!=".") && ($delfile!="..") && ($delfile!="")&&strpos($delfile,".php"))
		{
			@unlink("$path/$delfile");
		}
	}
	@closedir($deldb);
	@rmdir($path);
}
function getusergroup($username,$getpostnum='N')
{
	global $userpath;
	include "./data/level.php";
	if(file_exists("$userpath/$username.php"))
	{
		$userinfo=readover("$userpath/$username.php");
		$detail=explode("|",$userinfo);
		if(ereg("^[0-9]{1,}",$detail[5]) || $getpostnum=='Y')
		{
			$lpost[0]=0;
			$count=count($lpost);
			for($i=0;$i<$count;$i++)
			{
				if($detail[16]>=$lpost[$i] && $detail[16]<$lpost[$i+1])
					$detail[5]=$i;
			}
		}
		$group=$detail[5];
	}
	settype($group, "string");
	return $group;
}
/*
*$cgma:为了安全考虑超级管理员采用的是非用户组思想,所以除了在缓冲思想那可以用$cgma=1其他地方请不要加这一设置!
*/
function changegroup($username,$newgroup,$cgma=N)
{
	global $userpath;
	if(file_exists("$userpath/$username.php")){
		$db=readover("$userpath/$username.php");
		$detail=explode("|",$db);
		if($cgma==N && $detail[5]=="manager") adminmsg("不能修改管理员权限");
		if(!is_numeric($detail[5]) && !is_numeric($newgroup) && $detail[5]!='admin' 
			&& ($newgroup!='admin' || ($detail[5]!='superadmin' && $detail[5]!='banned' && 
			$detail[5]!='ctuser'))){
			global $ltitle;
			$N_litle=$ltitle[$detail[5]];
			adminmsg("该用户为非普通用户组:<b>$N_litle</b>  ! 为了避免特殊组记录的混乱 您在修改该特殊用户为其他特殊或管理组时,<br><br>请先到相应的特殊或管理组删除原记录,还原改用户为普通用户组");
		}
		$detail[5]=$newgroup;
		$fp=implode("|",$detail);
		writeover("$userpath/$username.php",$fp);
	}
}


/*
*此函数主要解决 htf 后台批量管理维护list索引文件时,当索引文件大至几十M时产生的无法写入问题
*当然此函数还可以应用于其他比如 userarray.php 这样容易产生负载的数据文件
*/
function writelist($filename,$lst_array)
{
	$size=5000;									 //控制每次list.php每次写入的长度
	$count=floor(count($lst_array)/$size)+1;
	for($i=0;$i<$count;$i++){
		//debug echo "$i 次";
		$array1=array_slice ($lst_array,$i*$size,$size);
		$i!=0 ? $method="ab":$method="wb";/*第一次重建时,一定要清空*/
		writeover($filename,implode("",$array1),$method);
	}
}


function ifadmin($adminname)
{
	$adminpath="data/admin.php";
	$adminarray=openfile($adminpath);
	$acount=count($adminarray);
	$ifadmin=0;
	for($j=0;$j<$acount;$j++)
	{
		$temp=explode("|",$adminarray[$j]);
		if(trim($temp[2])==$adminname)
		{
			$ifadmin=1;
			break;
		}
	}
	return $ifadmin;
}
function ifcheck($var,$out){
	global ${$out.'_Y'},${$out.'_N'};
	if($var) ${$out.'_Y'}="CHECKED"; else ${$out.'_N'}="CHECKED";

}

function F_L_count($filename,$offset)
{
	global $admin_login_ip;
	$count=0;
	if($fp=fopen($filename,"rb")){
		flock($fp,LOCK_SH);
		fseek($fp,-$offset,SEEK_END);
		$readb=fread($fp,$offset);
		fclose($fp);
		$readb=trim($readb);
		$readb=explode("|Logging Failed|$admin_login_ip|",$readb);
		$count=count($readb);
	}
	return $count;
}
?>