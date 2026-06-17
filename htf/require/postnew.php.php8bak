<?php

!function_exists('readover') && exit('Forbidden');

/**
* 版块权限判断
*/
if($allowpost && strpos($allowpost,",$groupid,")===false && $htfid!=$manager){

	showmsg('本论坛只有特定用户组才能发表主题');
}

/**
* 用户组权限判断
*/
if($action=="new" && !$allowpost && $gp_ifpost==0){
	showmsg("你所属的用户组没有发表主题的权限");
}elseif($action=="vote" && !$allowpost && $gp_ifpostvote==0){
	showmsg("你所属的用户组没有发表投票的权限");
}

if (!$_POST['step']) 
{
	unset($atc_title);
	include "./header.php";
	if($action=="vote"){
		$post_status="发表投票";
		$msg_guide=headguide($secondname,$secondurl,"发表新投票");			
	}
	elseif($action=="new"){
		$post_status="发表主题";
		$msg_guide=headguide($secondname,$secondurl,"发表新文章");
	}
	include PrintEot('post');footer();
}
elseif($_POST['step']==2)
{
	$atc_title=& $_POST['atc_title'];
	$atc_title=safeconvert(stripslashes($atc_title));
	if($action=="vote"){
		$reply_check=check_data("vote");
		$atc_type="4";
	}
	else{
		$reply_check=check_data();
		$atc_type="0";
	}
	unset($atc_content);
	$atc_content=& $_POST['atc_content'];
	if (!$atc_iconid)
		$atc_iconid="R";
	$atc_content=safeconvert(stripslashes($atc_content));
	$tid=shownextname($fid);

	include './require/postupload.php';/*需要$tid变量位置不能变*/

	
	if ($reply_check){
		if(!$htfid)$htfid='guest';
		$threadnew=$htfid.",".$timestamp;
		$newdb="|||$htfid||$tid|0|$threadnew|0||";//最后个0表示是否置顶贴 1.2.3
		$newlist=str_pad($newdb,$db_linesize)."\n";
		writeover("$dbpath/$fid/list.php",$newlist,"ab");

		if($action=="vote"){
			$vt_select=safeconvert($vt_select);
			strpos($vt_select,'<br />') ? $vt_split='<br />': $vt_split='<br>';
			$vt_selarray=explode($vt_split,$vt_select);

			foreach($vt_selarray as $voteoption){
				$voteoption = trim($voteoption);
				if($voteoption) {
					$votearray['options'][] = array($voteoption, 0,array());
				}
			}
			if($mostvotes && is_numeric($mostvotes))
				$mostvotes>count($vt_selarray)?$mostvotes=count($vt_selarray):'';
			else
				$mostvotes=count($vt_selarray);
			$votearray['multiple'] = array($multiplevote,$mostvotes);
			$voteopts = serialize($votearray);
			writeover("$dbpath/$fid/{$tid}vote.php","<?die?>|$voteopts");
		}
		if ($_POST['atc_autourl']=="1"){
			$atc_content=autourl($atc_content);
			if($atc_requirervrc=='1')
				$atc_content="[hide=".$atc_rvrc."]".$atc_content."[/hide]";
			if($atc_hide=='1')
				$atc_content="[post]".$atc_content."[/post]";
			if($atc_requiresell=='1')
				$atc_content="[sell=".$atc_money."]".$atc_content."[/sell]";
			$lxcontent=convert($atc_content,$db_htfpost);
			$ifconvert=$lxcontent==$atc_content ? 1 : 2;
			unset($lxcontent);
		}else{
			$ifconvert=1;
		}
		$atc_title=str_replace("&ensp;$","$",$atc_title);
		$newposttime=date($db_tformat,$timestamp);
		$writenewpost="<?die;?>|$atc_title|$htfid|$newposttime|$atc_iconid|$tid|$fid|$fid_name||\n"; 
		if ($fid!=$db_recycle)
			writeover("data/newpost.php",$writenewpost,"ab");
		if (!$atc_usesign)
			$atc_usesign="0";
		$tpc_author=$htfid;/*主要因为convert函数需要$tpc_author变量*/

		$atc_content=trim(str_replace("&ensp;$","$",$atc_content));
		$writetodb="<?die;?>|0       ,$atc_type,|$htfid|$atc_iconid|$timestamp|$atc_title|$onlineip|$atc_usesign|$htfupload|||$ipfrom|$ifconvert|$atc_email|$atc_content||\n";
		writeover("$dbpath/$fid/$tid.php",$writetodb,"ab");
		$top_post=1;
		bbspostguide($atc_title);
		refreshto("topic.php?fid=$fid&tid=$tid","<a href=forum.php?fid=$fid>[ 发贴成功点击进入主题列表 ]</a>");
	} 
	else{
		//在上面已经定义过 $msg_info
		showmsg($msg_info);
	}
}
?>