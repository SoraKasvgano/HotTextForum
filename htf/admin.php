<?php
define('SAFE',true);
$ifecho=array(
	"set_index1"   => "<!--","set_index2"   => " -->",	
	"set_view1"    => "<!--","set_view2"    => " -->",
	"set_yes1"     => "<!--","set_yes2"     => " -->",
	"adm_log1"     => "<!--","adm_log2"     => " -->",
	"adm_log3"     => "<!--","adm_log4"     => " -->",
	"send_index1"  => "<!--","send_index2"  => " -->",
	"send_err1"    => "<!--","send_err2"    => " -->",
	"send_foru1"   => "<!--","send_foru2"   => " -->",
	"send_foru3"   => "<!--","send_foru4"   => " -->",
	"anc_edit1"    => "<!--","anc_edit2"    => " -->"
	//"anc_add1"	   => "<!--","anc_add2"     => " -->"
	);
$basename="admin.php";//放到 !$adminjob里
require "./admin/admincp.php";
if(!$adminjob)
{
	list($bbsfb,$bbsnewer,$bbstotleuser)=explode("|",readover("data/bbsnew.php"));
	list($bbsfb,$bbstpc,$bbsatc,$bbstoday,$bbsyestoday,$bbsmost,$bbspostcontrol,$bbsbirthcontrol,$bbsstar,$bbsrich,$bbslucher,$bbsbirthman)=explode("|",readover("data/bbsatc.php"));
	$systemtime=date("F j,Y,g:i:s a");
	if($bbstotleuser<0)$bbstotleuser=1;
	$bbstotleuser==0?$admin_rateperson==0:$admin_rateperson=floor($bbsatc/$bbstotleuser);
	$sysversion=PHP_VERSION;
	if (isset($_COOKIE))
		$ifcookie="通 过";
	else
		$ifcookie="失 败";
	eval("dooutput(\"".gettmp('admin')."\");");
}
if($adminjob=='bak'){
	include "admin/bak.php";
}
elseif($adminjob=='setforum'){
	include "admin/setforum.php";
}
elseif($adminjob=='setstyles'){
	include "admin/setstyles.php";
}
elseif($adminjob=='settings'){
	include "admin/settings.php";
}
elseif($adminjob=='superdel'){
	include "admin/superdel.php";
}
elseif($adminjob=='addadmin'){
	include "admin/addadmin.php";
}
elseif($adminjob=='setuser'){
	include "admin/setuser.php";
}
elseif($adminjob=='addgpmenber'){
	include "admin/addgpmenber.php";
}
elseif($adminjob=='forumadmin'){
	include "admin/forumadmin.php";
}
elseif($adminjob=='updatagroup'){
	include "admin/updatagroup.php";
}
elseif($adminjob=='announcement'){
	include "admin/announcement.php";
}
elseif($adminjob=='share'){
	include "admin/share.php";
}
elseif($adminjob=='fbusername'){
	include "admin/fbusername.php";
}
elseif($adminjob=='setbwd'){
	include "admin/setbwd.php";
}
elseif($adminjob=='ipban'){
	include "admin/ipban.php";
}
elseif($adminjob=='level'){
	include "admin/level.php";
}
elseif($adminjob=='record'){
	include "admin/record.php";
}
elseif($adminjob=='forumcp'){
	include "admin/forumcp.php";
}
elseif($adminjob=='sendmsg'){
	include "admin/sendmsg.php";
}
elseif($adminjob=='mailuser'){
	include "admin/mailuser.php";
}
elseif($adminjob=='giveuser'){
	include "admin/giveuser.php";
}
elseif($adminjob=='newcheck'){
	include "admin/newcheck.php";
}
elseif($adminjob=='postcache'){
	include "admin/postcache.php";
}
elseif($adminjob=='attachment'){
	include "admin/attachment.php";
}
elseif($adminjob=='setgroup'){
	include "admin/setgroup.php";
}
elseif($adminjob=='quit'){
	Cookie('htfadminid',"",0,'N');
	Cookie('htfadminpwd',"",0,'N');
	adminmsg("成 功 退 出 管 理<br><br><a href=index.php>进 入 首 页</a>");
}
?>