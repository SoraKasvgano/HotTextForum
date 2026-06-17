<?php

!function_exists('readover') && exit('Forbidden');
/**
* 版块权限判断
*/
if($allowreply && strpos($allowreply,",$groupid,")===false && $htfid!=$manager){

	showmsg('本论坛只有特定用户组才能回复主题');
}
if(!$allowreply && $gp_ifreply==0){
	showmsg("你所属的用户组没有回复帖子的权限");
}
if (!file_exists("$dbpath/$fid/$tid.php")) {include("./require/url_error.php");}
$oldfiledb=openfile("$dbpath/$fid/$tid.php");
$tpclist=$oldfiledb[0];
$tpcarray=explode("|",$tpclist);
$replytitle=$tpcarray[5];$tpc_author=$tpcarray[2];/*主要因为convert函数需要$tpc_author变量*/
list($topic_hit,$topic_style,$null)=explode(",",$tpcarray[1]);
if ($htfid!=$manager && ($topic_style==1 || $topic_style==3||$topic_style==5)){

	showmsg("该贴已被锁定，不可回复");
}
if(!$_POST['step'])
{
	require './require/bbscode.php';
	$post_status="直接回复文章";
	$hideemail="disabled";
	if ($action=="quote") 
	{
		$post_status="引用并回复文章";
		if ($oldfiledb[$article])
		{
			$oldfilearray=explode("|",$oldfiledb[$article]);
			$old_author=$oldfilearray[2];
			$replytitle=$oldfilearray[5];$wtof_oldfile=$use_date=date($db_tformat,$oldfilearray[4]);$old_content=$oldfilearray[14];
			$old_content=preg_replace("/\[hide=(.+?)\](.+?)\[\/hide\]/is","[color=red]浏览此贴需要威望[/color]",$old_content);
			$old_content=preg_replace("/\[post\](.+?)\[\/post\]/is","[color=red]此处是被引用的隐藏贴[/color]",$old_content);
			$old_content=preg_replace("/\[sell=(.+?)\](.+?)\[\/sell\]/is","[color=red]此处是被引用的出售贴[/color]",$old_content);
			$old_content=preg_replace("/\[quote\](.*)\[\/quote\]/is","",$old_content);
			$old_content=str_replace("<br>","\n",$old_content);$old_content=str_replace("<br />","\n",$old_content);
			$bit_content = explode("\n",$old_content);
			if (count($bit_content) > 5)
			{
				$old_content = "$bit_content[0]\n$bit_content[1]\n$bit_content[2]\n$bit_content[3]\n$bit_content[4]\n.......";
			}
			$atc_content="[quote][b]下面是引用{$old_author}于{$wtof_oldfile}发表的 {$replytitle}:[/b]\n{$old_content}[/quote]\n";
		}
	}
	include "./header.php";
	$post_reply="<br><center>主题回顾</center>";
	$count=count($oldfiledb)-1;
	$lastreply =max($count-$db_showreplynum,0);

	for ($i=$count; $i>=$lastreply; $i--) 
	{
		$detail=explode("|",$oldfiledb[$i]);
		$post_reply.="<table width=70% align=center cellspacing=1 cellpadding=2 style='TABLE-LAYOUT: fixed;WORD-WRAP: break-word'><tr><td>$detail[2] : $detail[5]</td></tr><tr><td>".convert($detail[14],$db_htfreply)."</td></tr></table><hr size=1 color=$tablecolor width=80%>";
	}
	$replytitle==''?$atc_title='Re:'.$tpcarray[5]:$atc_title='Re:'.$replytitle;//索引设计时为了减少空间,回复的主题可能为空,所以默认为回复主题!
	$msg_guide=headguide($secondname,$secondurl,"发表回复");
	include PrintEot('post');footer();
}
elseif ($_POST['step']==2) 
{
	$atc_title=& $_POST['atc_title'];
	$atc_title=safeconvert(stripslashes($atc_title));
	$reply_check=check_data();
	unset($atc_content);
	$atc_content=& $_POST['atc_content'];
	if(!$htfid)$htfid='guest';
	/**
	*下句主要是为了节省数据的重复,可以用智能判断
	*/
	if (!$atc_iconid)
		$atc_iconid="R";
	$_POST['atc_title']=='Re:'."$replytitle" ? $atc_title='':$atc_title=$_POST['atc_title'];
	$atc_content=safeconvert(stripslashes($atc_content));
	include './require/postupload.php';
	if ($reply_check)
	{
		if ($_POST['atc_autourl']=="1"){
			$atc_content=autourl($atc_content);
			$atc_requirervrc=='1' && $atc_content="[hide=".$atc_rvrc."]".$atc_content."[/hide]";
			$atc_hide=='1' && $atc_content="[post]".$atc_content."[/post]";
			$lxcontent=convert($atc_content,$db_htfpost);
			$ifconvert=$lxcontent==$atc_content ? 1 : 2;
			unset($lxcontent);
			//if($atc_requiresell=='1')
			//	$atc_content="[sell=".$atc_money."]".$atc_content."[/sell]";//去掉注释就可以在回复里加出售贴了
		}else{
			$ifconvert=1;
		}
		$ifconvert=$lxcontent==$atc_content ? 1 : 2;
		$atc_title=str_replace("&ensp;$","$",$atc_title);
		$filename="$dbpath/$fid/list.php";
		list($toparray,$topdetail)=searchtop($filename,$db_linesize,$tid);//未锁定写入
		if(is_array($topdetail)){
			$topdetail[6]=count($oldfiledb);
			$topdetail[7]=$htfid.",".$timestamp;
			$top_s=trim(implode("|",$topdetail));
			$newtop=str_pad($top_s,$db_linesize)."\n";
			//array_unshift($toparray,$newtop);
			//$topspace=str_pad(' ',$db_linesize)."\n";
			//$toparray=array_pad($toparray,$db_topnum,$topspace);
			$writedb=$newtop.implode("",$toparray);

			writeselect($filename,$writedb,1,$db_linesize);
			unset($toparray,$topdetail);
		}
		else{
			list($fp,$temparray,$post_tpc,$fastwrite)=readsearch($filename,$tid,$db_linesize);
			if($post_tpc[5]==$tid){
				$post_tpc[6]=count($oldfiledb);
				$post_tpc[7]=$htfid.",".$timestamp;
				$replyline=str_pad(trim(implode("|",$post_tpc)),$db_linesize)."\n";
				write_alt($fp,$temparray,$fastwrite,$replyline);
				fclose($fp);
				unset($temparray,$writearray,$post_tpc);
			}
			else{
				fclose($fp);//$fp为在函数readsearch打开的指针
				showmsg("读取数据错误,原因：索引文件被破坏,请到后台修复索引文件!");
			}
		}
		/*$newposttime=date($db_tformat,$timestamp);
		$writenewpost="<?die;?>|Re:$replytitle|$htfid|$newposttime|$atc_iconid|$tid|$fid|$fid_name||\n"; 
		if ($fid!=$db_recycle)
			writeover("data/newpost.php",$writenewpost,"ab+"); */
		//$file_line=array($atc_title,$htfid,$atc_content,$timestamp,$ip,$atc_iconid);
		if (!$atc_usesign)
			$atc_usesign="0";

		if ($db_replysendmail==1){
			$E_array=explode(",",$tpcarray[13]);
			if ($E_array[0]==1 && $htfid != $tpcarray[2]){
				$receiver = $tpcarray[2];
				$old_title=$tpcarray[5];
				$detail = explode("|",readover("$userpath/$receiver.php"));
				$send_address= $detail[3];
				$textmessage="Hi, $receiver ,\n    我是{$db_bbsname}邮件大使，\n    你在{$db_bbsname}发表的文章: $old_title\n    现在有人回复.快来关注一下吧\n    {$db_bbsurl}/topic.php?fid=$fid&tid=$tid\n    下次再有人参与主题时,我将不来打扰了\n\n___________________________________\n欢迎访问 {$db_wwwname}\n本论坛由htf Studio制作,欢迎光临htf论坛 http://www.htf.com/bbs ";
				if ($detail[22]=="1"){
					if(mail("$send_address", "$receiver您在{$db_bbsname}的帖子有回复", "$textmessage","From: $db_ceoemail\nReply-To: $db_ceoemail\nX-Mailer: {$db_bbsname}邮件系统")){
						$ifmail="已经发送邮件通知主题订阅者";
						//$E_array[0]=0;
					}
					else
						$ifmail="发送通知邮件失败";
				}
			}
		}
		$atc_content=trim(str_replace("&ensp;$","$",$atc_content));
		//$atc_email!=1 && $atc_email='';$E_hide!=1 && $E_hide='';
		$writetodb="<?die;?>||$htfid|$atc_iconid|$timestamp|$atc_title|$onlineip|$atc_usesign|$htfupload|||$ipfrom|$ifconvert|$atc_email|$atc_content||\n";
		writeover("$dbpath/$fid/$tid.php",$writetodb,"ab");
		//$page=floor($post_tpc[6]/$db_readperpage)+1;
		bbspostguide($replytitle);//传递原标题!
		refreshto("topic.php?fid=$fid&tid=$tid&page=lastpost#lastatc","{$ifmail}<a href=forum.php?fid=$fid>[ 回复帖子成功点击进入主题列表 ]</a>");
	}
	else
	{
		include "./header.php";
		$msg_guide=headguide($secondname,$secondurl,"发生错误");
		//在上面已经定义过 $msg_info
		showmsg($msg_info);
	}
}
?>