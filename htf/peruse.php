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
	$msg_guide=headguide("����Ԥ��");
	$preatc=safeconvert($preatc);
	$preatc=convert($preatc,$db_htfpost);
	$notice_info="<tr><TD class=head>����Ԥ��</td></tr><tr><td bgcolor=$forumcolorone>$preatc</td></tr>";
	/*����ģ����ڹ����ģ����*/
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
	* ���Ȩ���ж�
	*/
	if($allowdownload && strpos($allowdownload,",$groupid,")===false && $htfid!=$manager){

		showmsg('�Բ��𣬱���ֻ̳���ض��û��������ظ������뷵��');
	}
	/**
	* �û���Ȩ���ж�
	*/
	if(!$allowdownload && $gp_ifdownload==0){

		showmsg("���������û���û�����ظ�����Ȩ��");
	}
	if($groupid!='guest'){
		$oldattachdb=Ex_plode("~",$htfdb[30],2);
		if(($timestamp-$oldattachdb[2])<15){
			showmsg("�벻Ҫ��15������������! ���Ժ�������!");
		}else{
			$oldattachdb[2]=$timestamp;
		}
		$htfdb[30]=implode("~",$oldattachdb);
		writeover("$userpath/$htfid.php",implode("|",$htfdb));
	}
	/**
	* ������Ӳ�����,��������һ�����ļ�
	*/
	
	if($articledb=readover("$dbpath/$fid/$tid.php")){
		$articlearray=explode("\n",$articledb);
		$detail=explode("|",$articlearray[$i]);
		$theatt=explode("~",$detail[8]);
		if($theatt[$id]){
			list($dfurl,$dfname,$dfhit,$dfrvrc,$dfinfo)=explode(",",$theatt[$id]);
			if($gp_ifuploadrvrc==1 && $userrvrc<$dfrvrc){
				$msg_info="�������С�����ظ�����������.";
			}
			$dfhit++;
			$download1="$dfurl,$dfname,$dfhit,$dfrvrc,$dfinfo";
			if(!is_readable("$attachpath/$dfurl")){
				$msg_info="����������.����ʧ��!";
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
	if ($groupid!='guest' && (($forum_admin && in_array($htfid,$forum_admin)) ||($father_admin && in_array($htfid,$father_admin) || $groupid=='superadmin') || $htfid==$manager ||$detail[2]==$htfid))//���һ����֤Ϊ��������ɾ���Լ��ĸ���
	{
		$theatt=explode("~",$detail[8]);
		list($dfurl,$dfname,$dfhit,$dfrvrc,$dfinfo)=explode(",",$theatt[$id]);
		$writedb=str_replace($theatt[$id].'~','',$articledb);/* ~ :ȥ���฽����ʶ��*/
		writeover("$dbpath/$fid/$tid.php",$writedb);
		unlink("$attachpath/$dfurl");
		refreshto("topic.php?fid=$fid&tid=$tid","ɾ�������ɹ�");
	}
	else{
		showmsg('������Ȩ��ɾ������');
	}
}elseif($action=='favor'){
	if($groupid=='guest'){
		showmsg("�Բ����㻹û��¼��ֻ��ע���Ա����ʹ���ղؼй��ܡ�");
	}
	$favorfile="data/favor/$htfid.php";
	if(!$job){
		require'./header.php';

		$msg_guide=headguide('�ղؼ�','peruse.php?aciton=favor');
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
			showmsg('���Ĳ����д���: û��ָ���ղص�����');
		}
		$topic_db=explode("|",gets("$dbpath/$fid/$tid.php",200));
		$favordb="$topic_db[5]|$topic_db[2]|$topic_db[4]|$fid|$tid|";
		$olddb=readover($favorfile);
		if(strlen($olddb)>4000){
			showmsg("�ղؼ��������������ղؼС�");
		}
		if(strpos($olddb,$favordb)!==false){
			showmsg("���Ѿ��ղ��˸����⡣");
		}
		$fid_name=Get_forum_name($fid);
		writeover($favorfile,'<?die;?>|'.$favordb.$fid_name."\n",'ab');
		refreshto("peruse.php?action=favor","�������Ѿ��ɹ��ղ�");

	}
	elseif($job=='clear'){

		$favorarray=openfile($favorfile);
		foreach($delid as $id){
			if(isset($id))
				unset($favorarray[$id]);
		}
		$favordb=implode("",$favorarray);
		writeover($favorfile,$favordb);
		refreshto("peruse.php?action=favor","�ɹ�ȡ���ղش�����");

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
				$newpost.="<tr><td align=left bgcolor=#FFFFFF>&nbsp;&nbsp;<img src=$imgpath/post/emotion/$new_icon.gif border=0>&nbsp;&nbsp;<a href=topic.php?fid=$new_fid&tid=$new_tid target=_blank title=\"���ӷ���ʱ��: $new_time\">$new_title</a></td><td align=right bgcolor=#FFFFFF>[<a href=usercp.php?action=show&username=".rawurlencode($new_author)." title=\"����鿴{$new_author}������\" target=_blank>$new_author</a>]&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
			}
		}
		else
			unset($newdb[$i]);
	}
	$writenewpost=implode("",$newdb);
	writeover("data/newpost.php",$writenewpost);
	require "./header.php";
	$msg_guide=headguide("��������");
	include PrintEot('newpost');footer();
	exit;
}elseif($action=='viewtody'){
	$star_action='vt';
	require "./header.php";
	if($db_today==0){

		showmsg("��̨�������ùر�ͳ��.��Ҫ����Ա��ͳ�Ʋ���ʹ�ô˹���!");
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
		$nodedb=explode("|",$node);/*ͷ����ڵڶ������ݶ�*/
		$nodefp=$dbtdsize*$nodedb[1];
		fseek($fp,$nodefp,SEEK_SET);
		$todayshow=fseeks($fp,$dbtdsize,$seed);/*��������*/
		fseek($fp,0,SEEK_END);
		$count=floor(ftell($fp)/$dbtdsize)-1;
		fclose($fp);
	}
	if ($count%$db_perpage==0) 
		$numofpage=$count/$db_perpage;  //$numofpageΪ һ������ҳ
	else
		$numofpage=floor($count/$db_perpage)+1; 
	if ($page>$numofpage)
		$page=$numofpage;
	$pagemin=min(($page-1)*$db_perpage , $count-1);  
	$pagemax=min($pagemin+$db_perpage-1, $count-1);
	$msg_guide=headguide("���չ� $count ��Ա����");
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
		$msg_info='��̳��ȫ����,�û��������ڻ������Ӳ����ڵ�,�벻Ҫ����!';
	}
	if (!file_exists("$userpath/$sells.php"))
	{
		$msg_info='��������,�������ӵ����Ѿ���ɾ����!';
	}
	$buyrarray=explode("|",readover("$userpath/$buys.php"));
	if($buyrarray[18]<$sellmoney)
	{
		$msg_info='��Ľ�Ǯ����,���ܹ������.';
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
	$article=0;//�޸Ĵ˴��������ӻظ���Ҳ���Գ������� ֻҪ��ģ������� input ��������Ϳ�����
	$atcarray=explode("|",$articledb[$article]);
	empty($atcarray[10])?$atcarray[10]=$buys:$atcarray[10].=",".$buys;
	$articledb[$article]=implode("|",$atcarray);
	$articledb=implode("",$articledb);
	writeover("$dbpath/$fid/$tid.php",$articledb);
	refreshto("topic.php?fid=$fid&tid=$tid","�������ӳɹ�");
}elseif($votejop=='vote'){
	require "./require/dbmodify.php";
	require "./require/forum.php";
	htf_forumcheck();

	/**
	* �û���Ȩ���ж�
	*/
	if($gp_ifvote==0){
		showmsg("���������û���û��ͶƱȨ��");
	}

	if (empty($fid) || empty($tid) || !file_exists("{$dbpath}/$fid/{$tid}vote.php"))
	{include('require/url_error.php');}
	$db=gets("$dbpath/$fid/$tid.php",200);
	$tpcarray=explode("|",$db);
	$tpcinfo=explode(",",$tpcarray[1]);
	if($tpcinfo[1]==5){

		showmsg("ͶƱʧ��,�����ѱ�������");
	}

	$voteopts=explode("|",readover("$dbpath/$fid/{$tid}vote.php"));
	$votearray = unserialize($voteopts[1]);

	
	if(!$voteaction){
		foreach($votearray['options'] as $option){
			if(in_array($htfid,$option[2])){

				showmsg("���Ѿ����������ͶƱ,�벻Ҫ����");
			}
		}
	}
	

	if(count($voteid)>$votearray['multiple'][1]){

		showmsg("ͶƱ��������ָ������");
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
	refreshto("topic.php?fid=$fid&tid=$tid","ͶƱ�ɹ�");
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