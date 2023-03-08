<?php

!function_exists('readover') && exit('Forbidden');

//版块会员验证
function htf_forumcheck($G_C=0)
{
	global $allowvisit,$fid_type,$htfid,$action,$groupid,$tid,$fid,$fid_pwd,$pwdcheck,$cookietime,$manager,$fid_skin,$fid_type2,$skin;
    showforuminfo($G_C);//直接在此加入此函数可以有效利用资源,加快速度

	/**
	* 取得版块类型
	*/
	$fid_type=trim($fid_type);
	
	/**
	* 正规版块只有注册会员才能进入
	*/
	if($fid_type=='former' && $groupid=='guest')
		showmsg('本版块为正规版块,只有注册会员才能进入');

	/**
	* 隐藏版块只有管理员才能进入
	*/
	if($fid_type=='hidden' && $htfid!=$manager)
		showmsg('本版块为隐藏版块,只有管理员才能进入');

	/**
	* 版块风格设置
	*/
	if(!empty($fid_skin)&&file_exists("style/$fid_skin.php")){
		$skin=$fid_skin;
	}

	/**
	* 加密版块版块
	*/
	if($fid_type2=='jiami' && $pwdcheck!=$fid && $htfid!=$manager)
	{
		global $htf_action,$htf_password;
		if(!$htf_action){
			//global $printmsgpwd,$userpath,$starttime,$tablewidth,$mtablewidth,$tablecolor,$imgpath,$stylepath,$tplpath;
			global $printmsgpwd;
			$shouldwritepwd="请输入密码&nbsp;
			<input type=password size=20 maxlength=75 name=htf_password>
			<input type=hidden name=fid value=$fid>
			<input type=hidden name=htf_action value=htf_fpwd>
			<input type=submit value='确 定'>";
			$printmsgpwd="
			<table width=100% border=0 cellspacing=1 cellpadding=3>
			<tr><td><form methor=post action='forum.php'>
			<center><br>{$error}<br>{$shouldwritepwd}</center>
			</form></td></tr></table>";

			showmsg("本版块为加密版块,需密码验证( 游客无权登陆此版块)");
		}
		else{
			if($fid_pwd==md5($htf_password) && $groupid!='guest'){
				
				/**
				* 不同版块不同密码
				*/
				$pwdcheck=$fid;
				Cookie('pwdcheck',$pwdcheck);
			}
			elseif($groupid=='guest'){
				showmsg("游客无权登陆加密版块");
			}
			else{
				showmsg("密码错误错误,请重新输入密码");
			}
		}
	}
	if($allowvisit && @strpos($allowvisit,','.$groupid.',')===false && $htfid!=$manager)
		showmsg("对不起,本版块为认证版块,您没有权限进去");
}

//版块状态函数
function showforuminfo($G_C)
{
	global $ifchildrenforum,$allowvisit,$allowpost,$allowdownload,$allowupload,$allowreply,$fid_name,$fid_type,$fid_father,$fid_pwd,$fid_skin,$fid_type2,$fid_Cconcle,$fid_perpage,$fid_Pconcle,$fid_ifchildren,$forumcount,$fid,$forumarray;
	/**
	* 版块数据数组已经在外部定义过了 统一为$forumarray
	*/
	for ($i=0; $i<$forumcount; $i++) 
	{
		$detail=explode("|", $forumarray[$i]);
		if ($detail[1]=='category') continue;
		if ($detail[4]==$fid)
		{
			if($G_C!=0){
				
				$C_detail=explode("|", $forumarray[$i+1]);
				if($C_detail[5]==$fid){
					$ifchildrenforum=1;
				}
			}
			$fid_type=$detail[1];
			$fid_name=$detail[2];
			$fid_father=$detail[5];
			$fid_pwd=$detail[6]; 
			$fid_skin=$detail[7];
			$fid_Cconcle=$detail[8];
			$fid_Pconcle=$detail[11];
			$fid_perpage=$detail[12];
			$fid_ifchildren=$detail[14];//判断是否全部显示子版块
			list($allowvisit,$allowpost,$allowdownload,$allowupload,$allowreply)=explode("~",$detail[16]);
			if($fid_pwd!='') 
				$fid_type2='jiami';
			break;
		}
	}
}
function getforumadmin($forumall='N')
{
	global $fid,$fid_father;
	$forum_admin=$father_admin=$fidadminarray=array();
	$adminarray=openfile("data/admin.php");
	$count=count($adminarray);
	for ($i=0; $i<$count; $i++) 
	{
		$temp=explode("|", trim($adminarray[$i]));
		if($forumall=='Y') $fidadminarray[$temp[1]][]=$temp[2];/*获取各版块管理员*/
		if ($temp[1]==$fid) 
			$forum_admin[]=$temp[2];
		if($fid_father)
		{
			if($temp[1]==$fid_father)
				$father_admin[]=$temp[2];
		}
	}
	return array($forum_admin,$father_admin,$fidadminarray);
}
?>