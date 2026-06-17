<?php
require './global.php';
include_once './require/checkpass.php';
include_once './require/security.php'; // Load security functions

$pre_urlarray=explode('/',$_SERVER['HTTP_REFERER']);
$pre_url=array_pop($pre_urlarray);
if(!strpos($pre_url, '.php') || strpos($pre_url,'login.php') || strpos($pre_url,'register.php')) {
	$pre_url = 'index.php';
}

if ($groupid!='guest'&& $action!="quit"){
	showmsg("对不起！！！你已经登录，不能重复登录！！！");
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
		// CSRF Protection
		if(function_exists('verify_csrf_token')) {
			if(empty($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
				showmsg('安全验证失败，请重新提交！');
			}
		}

		// Rate Limiting - Prevent brute force
		if(function_exists('check_rate_limit')) {
			if(!check_rate_limit('login', 5, 300)) {
				showmsg('登录尝试过于频繁，请5分钟后再试！');
			}
		}

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
				$hideid=0;$ifhiden=' -- 由于你的用户组不能使用隐身，已取消';
			}
			$htfid=$loginuser;
			$htfpwd=$loginpwd;

			// Security: Regenerate session ID to prevent session fixation attack
			if(session_status() === PHP_SESSION_ACTIVE) {
				session_regenerate_id(true);
			}

			Loginipwrite($htfid);

			// Security: Check if password needs rehashing (MD5 to Argon2id migration)
			// Note: This check is for future migration, current system still uses MD5
			if(function_exists('password_needs_rehash_check') && password_needs_rehash_check($htfpwd)) {
				// Mark that this user should update password on next password change
				// For now, we keep compatibility with existing MD5 system
			}

			if($cktime!=0)
			{
				$cktime+=$timestamp;
			}
			Cookie('htfid',$htfid,$cktime);
			Cookie('htfpwd',$htfpwd,$cktime);
			Cookie('lastvisit','',0);//让$lastvist不会跨越过注册的会员，避免错误的用户会员！
			if($hideid==1) Cookie('hideid',$hideid,$cktime);
			if(empty($jumpurl)) $jumpurl="index.php";
			refreshto($jumpurl,'您已经登录成功'.$ifhiden);
		}elseif($hp==2){
			$msg="密码错误,你还可以尝试 $L_T 次";
		}elseif($hp==3){
			$msg="已经错误 6 次！密码被锁定,密码在 10 分钟内将无法输入登录,还剩余 $L_T 秒";
		}else{
			$msg='错误用户名或密码,请核对!';
		}
		showmsg($msg);
	}
}
elseif($action=="quit")
{
	// Security: Destroy session on logout
	if(session_status() === PHP_SESSION_ACTIVE) {
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		session_destroy();
	}

	Loginout($htfdb);
	refreshto($pre_url,'状态：你已经成功退出');/*退出url 需要使用$pre_url 因为有人修改密码，不是一次循环跳转*/
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
