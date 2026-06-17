<?php
require './global.php';
include_once './require/checkpass.php';
$pre_urlarray=explode('/',$_SERVER['HTTP_REFERER']);
//$urlcount=count($pre_urlarray);$pre_url=$nowurl[$urlcount-1];
$pre_url=array_pop($pre_urlarray);
if(!strpos($pre_url, '.php') || strpos($pre_url,'login.php') || strpos($pre_url,'register.php')) {
	$pre_url = 'index.php';
}


if ($groupid!='guest'&& $action!="quit"){
	showmsg("对不起！！你已经登陆，不能重复登陆！！");
}
if (!$action) $action="login";
if ($action=="login")
{
	if (!$step)
	{
		$jumpurl=$pre_url;
		require("header.php");
		$msg_guide=headguide("登录论坛");
		include PrintEot('login');footer();	
	}
	elseif($_POST['step']==2)
	{
		unset($hp);
		if($loginuser && $loginpwd)
		{
			$loginpwd=md5($loginpwd);
			list($hp,$L_T,$L_groupid,$loginpwd)=checkpass($loginuser,$loginpwd);
		}
		if($hp==1)
		{
			@include "./data/groupdb/group_$L_groupid.php";
			if(!$gp_ifhide && $hideid==1)
			{
				$hideid=0;$ifhiden=' -- 您所在的用户组不能使用隐身登陆';
			}
			$htfid=$loginuser;
			$htfpwd=$loginpwd;
			Loginipwrite($htfid);
			if($cktime!=0)
			{
				$cktime+=$timestamp;
			}
			Cookie('htfid',$htfid,$cktime);
			Cookie('htfpwd',$htfpwd,$cktime);
			Cookie('lastvisit','',0);//将$lastvist清空以将刚注册的会员加入今日到访会员中
			if($hideid==1) Cookie('hideid',$hideid,$cktime);
			if(empty($jumpurl)) $jumpurl="index.php";
			refreshto($jumpurl,'您已经登录成功'.$ifhiden);
		}elseif($hp==2){
			$msg="密码错误,您还可以尝试 $L_T 次";
		}elseif($hp==3){
			$msg="已经连续 6 次密码输入错误,您将在 10 分钟内无法正常登陆,还剩余 $L_T 秒";
		}else{
			$msg='该用户不存在,请核对!';
		}
		showmsg($msg);
	}
}
elseif($action=="quit")
{
	Loginout($htfdb);
	refreshto($pre_url,'状态：您已经成功退出');/*退出url 不要使用$pre_url 因为如果在修改密码后会造成一个循环跳转*/
}
function Loginipwrite($htfid){
	global $timestamp,$userpath,$onlineip;
	$filename="$userpath/$htfid.php";
	$userdb=explode("|",readover($filename));
	$userdb[0]='<?die;?>';//保证用户的安全性
	$userdb[1]=$htfid;
	$userdb[19]=$userdb[20];
	$userdb[20]=$timestamp;$userdb[29]=$onlineip;
	$logininfo=Ex_plode("~",$userdb[30],3);
	$e_login=explode(",",$logininfo[3]);$e_login[0]=$onlineip;$e_login[1]=6;
	$logininfo[3]=implode(",",$e_login);
	$userdb[30]=implode("~",$logininfo);
	$userdb=implode("|",$userdb);
	writeover($filename,$userdb);
}
?>