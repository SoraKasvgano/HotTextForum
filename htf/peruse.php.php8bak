<?php
require "./global.php";

if($previewjob=='preview'){
	require './require/bbscode.php';
	require"./header.php";
	if (empty($skin)) $skin=$db_defaultstyle;
	if(file_exists("style/$skin.php") && strpos($skin,'..')===false){
		@include ("style/$skin.php");
	}else{
		@include ("style/htf.php");
	}
	$msg_guide=headguide("帖子预览");
	$preatc=safeconvert($preatc);
	$preatc=convert($preatc,$db_htfpost);
	$notice_info="<tr><TD class=head>帖子预览</td></tr><tr><td bgcolor=$forumcolorone>$preatc</td></tr>";
	/*这里模版放在公告的模版中*/
	include PrintEot('notice');footer();
}elseif($rd_previous==1){
	if(!$fid || !$tid)
	{
		header("Location: index.php");
		exit;
	}
	$rd_count=0;
	$nexttopic=0;
	$step=1;
	$db=fopen("$dbpath/$fid/list.php","rb");
	flock($db,LOCK_SH);
	while (!feof($db) && $rd_count!=1)
	{
		$offset=-($db_linesize+1)*$step;
		fseek($db,$offset,SEEK_END);
		$step++;
		if($nexttopic==1)
			$rd_count=1;
		$articledb=fread($db,$db_linesize+1);
		$articleshow[]=$articledb;
		$articlearray=explode("|",$articledb);
		if($articlearray[5]==$tid)
			$nexttopic=1;
	}
	fclose($db);
	$count=count($articleshow);
	if($count<=1)
	{
		header("Location: topic.php?fid=$fid&tid=$tid&fpage=$fpage");
		exit;
	}
	if($goto=="previous")
	{
		if(strpos($articleshow[$count-3],"|")===false)
		{
			header("Location: topic.php?fid=$fid&tid=$tid&fpage=$fpage");
			exit;
		}
		$tidarray=explode("|",$articleshow[$count-3]);
		$tid=$tidarray[5];
		header("Location: topic.php?fid=$fid&tid=$tid&fpage=$fpage");
		exit;
	}
	if($goto=="next")
	{
		if(strpos($articleshow[$count-1],"|")===false)
		{
			header("Location: topic.php?fid=$fid&tid=$tid&fpage=$fpage");
			exit;
		}
		$tidarray=explode("|",$articleshow[$count-1]);
		$tid=$tidarray[5];
		header("Location: topic.php?fid=$fid&tid=$tid&fpage=$fpage");
		exit;
	}
}elseif($action=='download'){
	require './require/forum.php';
	list($forumcount,$forumarray)=getforumdb();
	htf_forumcheck();
	/**
	* 版块权限判断
	*/
	if($allowdownload && strpos($allowdownload,",$groupid,")===false && $htfid!=$manager){

		showmsg('对不起，本论坛只有特定用户可以下载附件，请返回');
	}
	/**
	* 用户组权限判断
	*/
	if(!$allowdownload && $gp_ifdownload==0){

		showmsg("你所属的用户组没有下载附件的权限");
	}
	if($groupid!='guest'){
		$oldattachdb=Ex_plode("~",$htfdb[30],2);
		if(($timestamp-$oldattachdb[2])<15){
			showmsg("请不要在15秒内连续下载! 请稍后再下载!");
		}else{
			$oldattachdb[2]=$timestamp;
		}
		$htfdb[30]=implode("~",$oldattachdb);
		writeover("$userpath/$htfid.php",implode("|",$htfdb));
	}
	/**
	* 如果帖子不存在,避免生成一个空文件
	*/
	
	if($articledb=readover("$dbpath/$fid/$tid.php")){
		$articlearray=explode("\n",$articledb);
		$detail=explode("|",$articlearray[$i]);
		$theatt=explode("~",$detail[8]);
		if($theatt[$id]){
			list($dfurl,$dfname,$dfhit,$dfrvrc,$dfinfo)=explode(",",$theatt[$id]);
			if($gp_ifuploadrvrc==1 && $userrvrc<$dfrvrc){
				$msg_info="你的威望小于下载附件所需威望.";
			}
			$dfhit++;
			$download1="$dfurl,$dfname,$dfhit,$dfrvrc,$dfinfo";
			if(!is_readable("$attachpath/$dfurl")){
				$msg_info="附件不存在.下载失败!";
			}
			if(isset($msg_info)){

				showmsg($msg_info);
			}
			$articledb=str_replace($theatt[$id],$download1,$articledb);
			writeover("$dbpath/$fid/$tid.php",$articledb);
		}
	}

	$filename =basename("$attachpath/$dfurl");
	$fileext =$attach_ext = substr(strrchr($dfurl,'.'),1);
	strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')!==false && $dfinfo=='torrent' && $attachpath='inline';
	ob_end_clean();
	header('Last-Modified: '.gmdate('D, d M Y H:i:s',$cookietime).' GMT');
	header('Pragma: no-cache');
	header('Content-Encoding: none');
	
	header('Content-Disposition: '.$attachpath.'; filename='.$filename);

	header('Content-type: '.$fileext);
	$downcontent=readover("$attachpath/$dfurl");
	echo $downcontent;
	exit;
}elseif($action=='deldownfile'){

	require './require/forum.php';
	list($forum_admin,$father_admin,$fidadminarray)=getforumadmin();
	$articledb=readover("$dbpath/$fid/$tid.php");
	$articlearray=explode("\n",$articledb);
	$detail=explode("|",$articlearray[$i]);
	if ($groupid!='guest' && (($forum_admin && in_array($htfid,$forum_admin)) ||($father_admin && in_array($htfid,$father_admin) || $groupid=='superadmin') || $htfid==$manager ||$detail[2]==$htfid))//最后一个验证为允许作者删除自己的附件
	{
		$theatt=explode("~",$detail[8]);
		list($dfurl,$dfname,$dfhit,$dfrvrc,$dfinfo)=explode(",",$theatt[$id]);
		$writedb=str_replace($theatt[$id].'~','',$articledb);/* ~ :去除多附件标识符*/
		writeover("$dbpath/$fid/$tid.php",$writedb);
		unlink("$attachpath/$dfurl");
		refreshto("topic.php?fid=$fid&tid=$tid","删除附件成功");
	}
	else{
		showmsg('您的无权限删除附件');
	}
}elseif($action=='favor'){
	if($groupid=='guest'){
		showmsg("对不起，你还没登录，只有注册会员才能使用收藏夹功能。");
	}
	$favorfile="data/favor/$htfid.php";
	if(!$job){
		require'./header.php';

		$msg_guide=headguide('收藏夹','peruse.php?aciton=favor');
		$rawhtfid=rawurlencode($htfid);
		if(filesize($favorfile)!=0){
			$favorarray=openfile($favorfile);
			foreach($favorarray as $favor)
				$favordb[]=explode("|",$favor);
		}
		include PrintEot('favor');footer();
	}
	elseif($job=='favor'){
		if(empty($fid) || empty($tid)){
			showmsg('您的操作有错误: 没有指定收藏的主题');
		}
		$topic_db=explode("|",gets("$dbpath/$fid/$tid.php",200));
		$favordb="$topic_db[5]|$topic_db[2]|$topic_db[4]|$fid|$tid|";
		$olddb=readover($favorfile);
		if(strlen($olddb)>4000){
			showmsg("收藏夹已满，请整理收藏夹。");
		}
		if(strpos($olddb,$favordb)!==false){
			showmsg("您已经收藏了该主题。");
		}
		$fid_name=Get_forum_name($fid);
		writeover($favorfile,'<?die;?>|'.$favordb.$fid_name."\n",'ab');
		refreshto("peruse.php?action=favor","该主题已经成功收藏");

	}
	elseif($job=='clear'){

		$favorarray=openfile($favorfile);
		foreach($delid as $id){
			if(isset($id))
				unset($favorarray[$id]);
		}
		$favordb=implode("",$favorarray);
		writeover($favorfile,$favordb);
		refreshto("peruse.php?action=favor","成功取消收藏此主题");

	}
}elseif($action=='viewnew'){
	if(file_exists("style/$skin.php") && strpos($skin,'..')===false){
		@include ("style/$skin.php");
	}else{
		@include ("style/htf.php");
	}
	$yeyestyle=='no' ? $i_table="bgcolor=$tablecolor" : $i_table="class=i_table";
	$css='css';

	$forumarray=openfile('data/forumdata.php');
	foreach($forumarray as $value){
		$detail=explode("|",$value);
		if($detail[1]!='category'){
			$allowvisit=explode("~",$detail[16]);
			$forum[$detail[4]]=$allowvisit[0];
		}
	}

	$newdb=openfile("data/newpost.php");
	$count=count($newdb);
	for($i=$count-1;$i>=0;$i--)
	{
		if(($count-$i)>35){ unset($newdb[$i]);continue;}
		if($newdb[$i]!="\n" && $newdb[$i]!="" )
		{
			list($fb,$new_title,$new_author,$new_time,$new_icon,$new_tid,$new_fid,$new_fidname,$new_null)=explode("|",$newdb[$i]);
			
			if(!$forum[$new_fid] ||strpos($forum[$new_fid],",$groupid,")!==false || $htfid==$manager){
				if($new_icon=="R")
					$new_icon=mt_rand(1,14);
				$new_title=str_replace('%a%',"<img src=$imgpath/$stylepath/file/attc.gif border=0>",$new_title);
				$newpost.="<tr><td align=left bgcolor=#FFFFFF>&nbsp;&nbsp;<img src=$imgpath/post/emotion/$new_icon.gif border=0>&nbsp;&nbsp;<a href=topic.php?fid=$new_fid&tid=$new_tid target=_blank title=\"帖子发表时间: $new_time\">$new_title</a></td><td align=right bgcolor=#FFFFFF>[<a href=usercp.php?action=show&username=".rawurlencode($new_author)." title=\"点击查看{$new_author}的资料\" target=_blank>$new_author</a>]&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
			}
		}
		else
			unset($newdb[$i]);
	}
	$writenewpost=implode("",$newdb);
	writeover("data/newpost.php",$writenewpost);
	require "./header.php";
	$msg_guide=headguide("最新帖子");
	include PrintEot('newpost');footer();
	exit;
}elseif($action=='viewtody'){
	$star_action='vt';
	require "./header.php";
	if($db_today==0){

		showmsg("后台核心设置关闭统计.需要管理员打开统计才能使用此功能!");
	}
	require './require/numofpage.php';
	$check_admin="N";
	if ($htfid==$manager) 
		$check_admin="Y";
	if ($groupid=='superadmin')
	{
		$check_admin="Y";
	}
	if(file_exists("data/admin.php")) 
	{
		$adminarray=openfile("data/admin.php");
		$count=count($adminarray);
		for ($i=0; $i<$count; $i++) 
		{
			$detail=explode("|", trim($adminarray[$i]));
			if($htfid==$detail[2])
				$check_admin="Y";
		}
	}
	if (empty($page))
		$page=1;
	$filename='data/today.php';
	$dbtdsize=100+1;
	$seed=$page*$db_perpage;$count=0;
	if($fp=@fopen($filename,"rb")){
		flock($fp,LOCK_SH);
		$node=fread($fp,$dbtdsize);
		$nodedb=explode("|",$node);/*头结点在第二个数据段*/
		$nodefp=$dbtdsize*$nodedb[1];
		fseek($fp,$nodefp,SEEK_SET);
		$todayshow=fseeks($fp,$dbtdsize,$seed);/*传回数组*/
		fseek($fp,0,SEEK_END);
		$count=floor(ftell($fp)/$dbtdsize)-1;
		fclose($fp);
	}
	if ($count%$db_perpage==0) 
		$numofpage=$count/$db_perpage;  //$numofpage为 一共多少页
	else
		$numofpage=floor($count/$db_perpage)+1; 
	if ($page>$numofpage)
		$page=$numofpage;
	$pagemin=min(($page-1)*$db_perpage , $count-1);  
	$pagemax=min($pagemin+$db_perpage-1, $count-1);
	$msg_guide=headguide("今日共 $count 会员到访");
	$fenye=numofpage($count,$page,$numofpage,"peruse.php?action=viewtody&");
	for ($i=$pagemin; $i<=$pagemax; $i++) 
	{
		if (!trim($todayshow[$i]))
			continue;
		list($inbbs[user],$null1,$null2,$inbbs[rgtime],$inbbs[logintime],$inbbs[intime],$inbbs[ip],$inbbs[post],$inbbs[rvrc],$null)=explode("|",$todayshow[$i]);
		$inbbs[rawuser]=rawurlencode($inbbs[user]);
		$inbbs[rvrc]=floor($inbbs[rvrc]/10);
		$inbbs[rgtime]=date($db_tformat,$inbbs[rgtime]);
		$inbbs[logintime]=date($db_tformat,$inbbs[logintime]);
		$inbbs[intime]=date($db_tformat,$inbbs[intime]);
		if ($check_admin=="N")
		{
			$inbbs[ip]="secret";
		}
		$inbbsdb[]=$inbbs;
	}
	include PrintEot('todayinbbs');footer();
}elseif($action=='buytopic'){
	require "./require/forum.php";
	//showforuminfo();
	$msg_info="";
	if (!file_exists("$userpath/$buys.php")||!file_exists("$dbpath/$fid/$tid.php")||$buys!=$htfid||$buys==$sells||$sellmoney>100||$sellmoney<0)
	{
		$msg_info='论坛安全屏蔽,用户名不存在或是帖子不存在等,请不要尝试!';
	}
	if (!file_exists("$userpath/$sells.php"))
	{
		$msg_info='发生错误,出售贴子的人已经被删除了!';
	}
	$buyrarray=explode("|",readover("$userpath/$buys.php"));
	if($buyrarray[18]<$sellmoney)
	{
		$msg_info='你的金钱不足,不能购买此贴.';
	}
	if($msg_info!="")
	{
		require "./header.php";
		showmsg($msg_info);
	}
	$buyrarray[18]=$buyrarray[18]-$sellmoney;
	$writerbuy=implode("|",$buyrarray);
	writeover("$userpath/$buys.php",$writerbuy);
	list($file,$selluserinfo)=readlock("$userpath/$sells.php");
	$sellrarray=explode("|",$selluserinfo);
	$sellrarray[18]=$sellrarray[18]+$sellmoney;
	$writersell=implode("|",$sellrarray);
	writelock("$userpath/$sells.php",$writersell,$file);
	$articledb=openfile("$dbpath/$fid/$tid.php");
	$article=0;//修改此处可以增加回复贴也可以出售帖子 只要在模版里加上 input 这个变量就可以了
	$atcarray=explode("|",$articledb[$article]);
	empty($atcarray[10])?$atcarray[10]=$buys:$atcarray[10].=",".$buys;
	$articledb[$article]=implode("|",$atcarray);
	$articledb=implode("",$articledb);
	writeover("$dbpath/$fid/$tid.php",$articledb);
	refreshto("topic.php?fid=$fid&tid=$tid","购买帖子成功");
}elseif($votejop=='vote'){
	require "./require/dbmodify.php";
	require "./require/forum.php";
	htf_forumcheck();

	/**
	* 用户组权限判断
	*/
	if($gp_ifvote==0){
		showmsg("你所属的用户组没有投票权限");
	}

	if (empty($fid) || empty($tid) || !file_exists("{$dbpath}/$fid/{$tid}vote.php"))
	{include('require/url_error.php');}
	$db=gets("$dbpath/$fid/$tid.php",200);
	$tpcarray=explode("|",$db);
	$tpcinfo=explode(",",$tpcarray[1]);
	if($tpcinfo[1]==5){

		showmsg("投票失败,帖子已被锁定！");
	}

	$voteopts=explode("|",readover("$dbpath/$fid/{$tid}vote.php"));
	$votearray = unserialize($voteopts[1]);

	
	if(!$voteaction){
		foreach($votearray['options'] as $option){
			if(in_array($htfid,$option[2])){

				showmsg("您已经参与了这次投票,请不要作弊");
			}
		}
	}
	

	if(count($voteid)>$votearray['multiple'][1]){

		showmsg("投票个数超过指定个数");
	}

	if($voteaction=='modify'){
		foreach($votearray['options'] as $key=>$option){
			foreach($option[2] as $vid=>$value){
				if($value==$htfid){
					$votearray['options'][$key][1]--;
					unset($votearray['options'][$key][2][$vid]);
				}
			}
		}

	}

	foreach($voteid as $id){
		//$votearray['options'][$id][2]=array();
		//$votearray['options'][$id][1]=0;
		$votearray['options'][$id][1]++;
		$votearray['options'][$id][2][]=$htfid;
	}
	$voteopts =serialize($votearray);
	writeover("$dbpath/$fid/{$tid}vote.php","<?die;?>|$voteopts");

	list($fp,$temparray,$detail,$fastwrite)=readsearch("$dbpath/$fid/list.php",$tid,$db_linesize);
	$details=explode(",",$detail[7]);
	$detail[7]=$details[0].",".$timestamp; 
	$newline=implode("|",$detail);
	write_alt($fp,$temparray,$fastwrite,$newline);
	fclose($fp);
	refreshto("topic.php?fid=$fid&tid=$tid","投票成功");
}
function fseeks($fp,$dbtdsize,$seed)
{
	$num=0;
	while($break!=1 && $num<$seed){
		$num++;
		$sdata=fread($fp,$dbtdsize);
		$sdb=explode("|",$sdata);
		$sdbnext=$sdb[2]*$dbtdsize;
		if($sdbnext!='NULL'){
			fseek($fp,$sdbnext,SEEK_SET);
		}else{
			$break=1;
		}
		$todayshow[]=$sdata;
	}
	return $todayshow;
}
function Get_forum_name($id){
	list($forumcount,$forumarray)=getforumdb();
	for ($i=0; $i<$forumcount; $i++) 
	{
		$detail=explode("|",trim($forumarray[$i]));
		if ($detail[4]==$id)
		{
			$forum_name=$detail[2];
			break;
		}
	}
	return $forum_name;
}
?>