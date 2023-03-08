<?php

!function_exists('adminmsg') && exit('Forbidden');
@set_time_limit(0);
$basename="admin.php?adminjob=setuser";
list($bbsfb,$bbsnewer,$bbstotleuser)=explode("|",readover("data/bbsnew.php"));
if (empty($action)) 
{
	$ifecho[set_index1]="";$ifecho[set_index2]="";
	eval("dooutput(\"".gettmp('admin_set')."\");");
}
elseif ($action=='updatecount')
{
	$ifecho[set_updata1]="";$ifecho[set_updata2]="";
	if($jop=='countnoly')
	{
		$usercount=0;
		$db=opendir("$userpath/");
		while (false!==($userfile=readdir($db))){
			if (($userfile!=".") && ($userfile!="..")&& ($userfile!=".php")&& strpos($userfile,".php"))
				$usercount++;
		}
		closedir($db);
		$bbstotleuser=$usercount;
		$writebbsnewdb="<?die;?>|$bbsnewer|$bbstotleuser|";
		writeover("data/bbsnew.php",$writebbsnewdb);
		adminmsg("用户总数更新成功,当前共有{$usercount}个注册用户");
	}
	if(!$htfi) {$htfi=0;writeover("data/userarray.php","");}
	$db=opendir("$userpath/");
	$starwhile=$htfi;
	while (false!==($userfile=readdir($db)))
	{
		if (($userfile!=".") && ($userfile!="..")){
			$whilecount++;
			if($htfi<$starwhile+6000 && $whilecount>=$starwhile){
				$htfi++;
				$userarray=explode("|",readover("$userpath/$userfile"));
				$arrayexplode.="{$userarray[1]},{$userarray[8]}|";
			}
		}
	}
	closedir($db);
	$arrayexplode=trim($arrayexplode);
	writeover("data/userarray.php",$arrayexplode,"ab");
	if($htfi<$whilecount){
		$url="admin.php?adminjob=setuser&action=updatecount&htfi=$htfi";
		adminmsg("已缓冲更新{$htfi}个用户,程序将自动更新剩下的部分,请耐心等候......",1);
	}
	$arrayexplode=readover("data/userarray.php");
	$rant_array=explode("|",$arrayexplode);
	$rantcount=count($rant_array);
	for ($i=0; $i<$rantcount-1; $i++){
		$plodearray=explode(',',$rant_array[$i]);
		$user_array[$plodearray[0]]=$plodearray[1];
	}
	asort($user_array);//排列用户组
	reset($user_array);
	$user_array=array_keys($user_array);
	$writedb=implode("\n",$user_array)."\n";
	$writedb="<?die;?>\n".$writedb;
	writeover("data/userarray.php",$writedb);
	$rantcount--;
	adminmsg("用户列表已经更新,当前共有{$rantcount}个注册用户！！");
}
elseif ($action=="uprongdata")
{
	$db=opendir("$userpath/");
	if(!$htfi) $htfi=0;
	if(!$badman) $badman=0;
	$starwhile=$htfi;
	while (false!==($userfile=readdir($db))) 
	{
		if(strpos($userfile,"|")!==false || strpos($userfile,"\\")!==false)
			unlink("$userpath/$userfile");
		elseif (($userfile!=".") && ($userfile!="..")&& ($userfile!=".php")) 
		{
			
			$whilecount++;
			if($htfi<$starwhile+6000 && $whilecount>=$starwhile)
			{
				$htfi++;
				$userfilename=explode(".",$userfile);
				//$user_array[]=$userfilename[0];
				$userarray=explode("|",readover("$userpath/$userfile"));
				if($userarray[27]!=1){
					$userarray[27]=1;
					$writedb=implode("|",$userarray);
					writeover("$userpath/$userfile",$writedb);
					$badman++;
				}elseif(!ereg("^[0-9]{1,10}",$userarray[8])){
					$overtime=time();
					$writedb=implode("|",$userarray);
					writeover("$userpath/$userfile",$writedb);
					$badman++;
				}elseif($userarray[27]!=1 || $userarray[0]!='<?die;?>' || $userarray[1]!=$userfilename[0] || $userarray[16]==''|| $userarray[17]==''|| $userarray[18]==''){
					$userarray[0]='<?die;?>'; $userarray[1]=$userfilename[0]; 
					$userarray[16]=='' ? $userarray[16]=0:'';
					$userarray[17]=='' ? $userarray[17]=0:'';
					$userarray[18]=='' ? $userarray[18]=10:'';
					$writedb=implode("|",$userarray);
					writeover("$userpath/$userfile",$writedb);
					$badman++;
				}
			}
		}
		if($htfi<$whilecount)
		{
			closedir($db);
			$url="admin.php?adminjob=setuser&action=uprongdata&htfi=$htfi&badman=$badman";
			adminmsg("已查询{$htfi}个用户,并纠正了{$badman}个用户!查询将自动更新剩下的部分,请耐心等候......",1);
		}
	}
	closedir($db);
	adminmsg("查询完毕,纠正了{$badman}个用户");
}
elseif ($action=="view") {
	$ifecho[set_view1]="";$ifecho[set_view2]="";
	if (empty($setusername) || $setusername=="." || $setusername==".." || !file_exists("$userpath/$setusername.php"))
	{
		adminmsg("非法的用户名");
	}
	list($dir_fb,$dir_name,$dir_pwd,$dir_email,$dir_publicmail,$dir_groupid,$dir_icon,$dir_gender,$dir_regdate,$dir_sign,$dir_introduce,$dir_oicq,$dir_icq,$dir_homepage,$dir_from,$dir_level,$dir_post,$dir_rvrc,$dir_money,$dir_lasttime,$dir_thistime,$dir_birth,$dir_receivemail,$dir_tuiji,$dir_lastpost,$dir_losttitle,$dir_lastaddrst,$dir_yz,$dir_todaypost,$dir_group,$dir_sx,$dir_star,$dir_xuni,$dir_main,$dir_onlinetime,$dir_signchange,$dir_null)= explode("|",readover("$userpath/$setusername.php"));
	$dir_regdate=date("Y-m-d H:i",$dir_regdate);
	$dir_rvrc=floor($dir_rvrc/10);
	eval("dooutput(\"".gettmp('admin_set')."\");");
}
elseif ($action=="edit"){
	$editfile="$userpath/$setusername.php";
	if (empty($setusername) || $setusername=="." || $setusername==".." || !file_exists($editfile))
		adminmsg("非 法 的 用 户 名");
	list($dir_fb,$dir_name,$dir_pwd,$dir_email,$dir_publicmail,$dir_groupid,$dir_icon,$dir_gender,$dir_regdate,$dir_sign,$dir_introduce,$dir_oicq,$dir_icq,$dir_homepage,$dir_from,$dir_honor,$dir_post,$dir_rvrc,$dir_money,$dir_lasttime,$dir_thistime,$dir_birth,$dir_receivemail,$dir_tuiji,$dir_lastpost,$dir_losttitle,$dir_lastaddrst,$dir_yz,$dir_todaypost,$dir_group,$dir_sx,$dir_star,$dir_xuni,$dir_main,$dir_onlinetime,$dir_signchange,$dir_null)= explode("|",readover($editfile));
	$dir_rvrc=floor($dir_rvrc/10);
	$maindb=explode("<",$dir_main);/* 分割号 为 < */
	if ($_POST['step']!="yes") 
	{
		$ltitle=$ltitle[$dir_groupid];
		$dir_regdate=date('Y-m-d G:i:s', $dir_regdate);
		$ifecho[set_yes1]="";$ifecho[set_yes2]="";
		list($P_S_money,$P_S_name,$P_S_add,$P_S_bankN,$P_S_phone,$P_S_adr)=explode("\t",$maindb[0]);
		$dir_sign = str_replace("<br>","\n",$dir_sign);
		$dir_sign = str_replace("<br />","\n",$dir_sign);
		eval("dooutput(\"".gettmp('admin_set')."\");");
	}
	else 
	{
		if ($edit_pwd!="已md5加密") $dir_pwd=md5($edit_pwd);
		if ($edit_email!='') $dir_email=$edit_email;
		if ($edit_oicq!='') $dir_oicq=$edit_oicq;
		if ($newhomepage!='')$dir_homepage=$edit_homepage;
		if ($edit_from!='') $dir_from=$edit_from;
		if ($edit_post!='') $dir_post=$edit_post;
		if ($edit_regdata!='')$dir_regdate=strtotime($edit_regdata);
		if ($edit_money!='') $dir_money=$edit_money;
		if ($edit_rvrc!='') $dir_rvrc=$edit_rvrc*10;
		if ($edit_sign!='') $dir_sign=safeconvert($edit_sign);
		if ($S_money || $S_name || $S_add || $S_bankN || $S_phone || $S_adr){
			$maindb[0]=safeconvert($S_money)."\t".safeconvert($S_name)."\t".safeconvert($S_add)."\t".safeconvert($S_bankN)."\t".safeconvert($S_phone)."\t".safeconvert($S_adr);/* 分割号 为 "\t" */
		}
		$dir_main=implode("<",$maindb);
		$dir_honor=safeconvert($edit_honor);
		writeover($editfile,"$dir_fb|$dir_name|$dir_pwd|$dir_email|$dir_publicmail|$dir_groupid|$dir_icon|$dir_gender|$dir_regdate|$dir_sign|$dir_introduce|$dir_oicq|$dir_icq|$dir_homepage|$dir_from|$dir_honor|$dir_post|$dir_rvrc|$dir_money|$dir_lasttime|$dir_thistime|$dir_birth|$dir_receivemail|$dir_tuiji|$dir_lastpost|$dir_losttitle|$dir_lastaddrst|$dir_yz|$dir_todaypost|$dir_group|$dir_sx|$dir_star|$dir_xuni|$dir_main|$dir_onlinetime|$dir_signchange|$dir_null");
		$basename.="&action=edit&setusername=$setusername";
		adminmsg("编辑用户成功");
	}
}
elseif ($action=="kill") 
{

	if (empty($setusername) || $setusername=="." || $setusername==".." || !file_exists("$userpath/$setusername.php")) 
	{
		adminmsg("非法的用户名");
	}
	$usergroup=getusergroup($setusername);
	if($usergroup=='manager')
		adminmsg("不能删除论坛管理员");
	if (@unlink("$userpath/$setusername.php")){
		$kill="用户已经从数据库中完全删除了";
		$bbstotleuser--;
		$array=openfile('data/userarray.php','N');
		while ($name = current($array)) {
			if (trim($name) == $setusername) {
				$num=key($array);
				break;//跳出
			}
			next($array);
		}
		if($num){
			unset($array[$num]);
			$count=count($array)-1;
			writelist('data/userarray.php',$array);
		}
		$writebbsnewdb="<?die;?>|$array[$count]|$bbstotleuser|";
		writeover("data/bbsnew.php",$writebbsnewdb);
	}
	else 
		$kill= "删除用户发生了未知错误，请检查用户目录属性";
	adminmsg($kill);
}
?>