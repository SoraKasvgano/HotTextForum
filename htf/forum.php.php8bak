<?php
$ifecho = array
(
	'trd_anc1' =>'<!--',
	'trd_anc2' =>'-->',
	'trd_ol1' =>'<!--',
	'trd_ol2' =>'-->'
);
require './global.php';
require './require/forum.php';
require './require/numofpage.php';

if (empty($fid) || !file_exists("$dbpath/$fid")) {include('require/url_error.php');}

if(empty($forumcount)) list($forumcount,$forumarray)=getforumdb();
htf_forumcheck(1);

if (empty($page)) $page=1;
/**
* 获取管理员权限
*/
list($forum_admin,$father_admin,$fidadminarray)=getforumadmin('Y');
require './header.php';
$trd_check=0;
if($groupid!='guest'){
	$lastlogindate=$htfdb[19];//上次登陆时间
	if(($forum_admin && in_array($htfid,$forum_admin)) ||($father_admin && in_array($htfid,$father_admin)) ||$groupid=='superadmin' || $htfid==$manager)
		$trd_check=1;
}

$fid_father && $trd_fatherlink="<a href='forum.php?fid=$fid_father'>点击进入一级版块</a>";
if ($db_threadonline==1){

	$trd_hide=$trd_nothide=$trd_guest=0;
	$guestarray=readover('data/guest.php');
	$detail=explode("<FiD>$fid",$guestarray);
	unset($guestarray);
	$trd_guest=count($detail)-1;
	$onlinearray=openfile('data/online.php');
	$count_ol=count($onlinearray);
	for ($i=1; $i<$count_ol; $i++)
	{
		$detail=explode("|",$onlinearray[$i]);
		if ($detail[3]==$fid)
		{
			switch($detail[5])
			{
				case 'manager':$img='0'; break;
				case 'superadmin':$img='1';break;
				case 'admin':$img='2';break;
				case 'rzuser':$img='3';break;
				case 'ctuser':$img='4';break;
				default:$groupname='会员';$img='5';//$groupname=$ltitle[$groupid];
			}
			if($trd_nothide%8==0)$trd_onlineinfo.="</tr><tr>";
			if($detail[0]=='<>') {$img='5';$trd_hide++; continue;} else $trd_nothide++;
			$trd_onlineinfo.="<td width=12%>&nbsp;<img src='$imgpath/$stylepath/group/$img.gif' align='absbottom'><a href=usercp.php?action=show&username=".rawurlencode($detail[0]).">$detail[0]</a></td>";
		}
	}
	unset($onlinearray);
	if($trd_onlineinfo)
	{
		$ifecho['trd_ol1']=$ifecho['trd_ol2']='';
	}
	$trd_sumonline2=$trd_nothide+$trd_guest+$trd_hide;
	$trd_sumonline1=$trd_nothide+$trd_hide;
	$thread_online='fonline';
}
if($search=='digest'){
	$msg_guide=headguide($fid_name,"forum.php?fid=$fid",'精华区');
}
else{
	$guide="<a href='forum.php?fid=$fid&search=digest'>查看本版精华区</a>";
	$msg_guide=headguide($fid_name,"forum.php?fid=$fid",'','',$guide);
}

if ($ifchildrenforum)
{
	showchildreninfo();
	for($i=0;$i<$fchildnum;$i++)
	{
		if ($fchild_type[$i]!='hidden' || $htfid==$manager)
			forumchildren($i);
	}
	$thread_children='sforum';
	if($fid_ifchildren==1)
	{
		include PrintEot('childmain');footer();
	}
}
unset($forum_admin,$father_admin,$fidadminarray,$forumarray);
$filename="$dbpath/$fid/list.php";
$fid_perpage>0 && $db_perpage=$fid_perpage;
if(empty($search)){
	list($articleshow,$topicnum)=readfrombot($filename,$db_linesize);
	if($page==1){
		$threadtop=gettoparray($filename,$db_linesize);
		$articleshow=array_merge($articleshow,$threadtop);
		$topcount=count($threadtop);
		unset($threadtop);
	}/*解决一个丢失帖子的假像*/
}
else{
	$articleshow=array();
	$atcstart=($page-1)*$db_perpage;
	if($search=='digest'){
		if(file_exists("data/digest/digest{$fid}.php")){
			$digest=openfile("data/digest/digest{$fid}.php");
			$digestarray=explode("|",$digest[2]);
			$schcount=count($digestarray);
			$schcount--;
			$digestarray=array_slice ($digestarray,$atcstart,$db_perpage);
			foreach($digestarray as $value){
				$atcarray=openfile("$dbpath/$fid/$value.php");
				$tpcdetail=explode("|",$atcarray[0]);
				list($rd_hit,$ifdigest,$null)=explode(",",$tpcdetail[1]);
				if($ifdigest==2||$ifdigest==3){
					$repley=count($atcarray)-1;
					$lastinfo=explode("|",$atcarray[$repley]);
					$articleshow[]="|||||$value|$repley|$lastinfo[2],$lastinfo[4]|||";
				}
			}
		}
	}
	else{
		$fp=fopen($filename,"rb");
		flock($fp,LOCK_SH);
		$count=$step=0;$readsize=$db_linesize+1;
		while (!feof($fp)){
			$step++;
			$offset=-$readsize*$step;
			fseek($fp,$offset,SEEK_END);
			$listfget=fread($fp,$readsize);
			$listfgetarray=explode("|",$listfget);
			$lastdata=explode(',',$listfgetarray[7]);
			if ($timestamp-$lastdata[1]<=$search*84600)
			{
				$articleshow[]=$listfget;
				$count++;
			}
			elseif($lastdata[1]) break;
		}
		fclose($fp);
		$schcount=count($articleshow);
		$articleshow=array_reverse($articleshow);
		$articleshow=array_slice ($articleshow,$atcstart,$db_perpage);
	}
}
$search?$count=$schcount:$count=$topicnum;
if ($count%$db_perpage==0)
	$numofpage=$count/$db_perpage;  //$numofpage为 一共多少页
else
	{$numofpage=floor($count/$db_perpage)+1;}
$totlepage=$numofpage;
if ($page>$numofpage)
	$page=$numofpage;
$pagemax=min($db_perpage+$topcount,count($articleshow));

$fenye=numofpage($count,$page,$numofpage,"forum.php?fid=$fid&search=$search&");//此函数在require/numofpage.php

$threaddb=array();
if ($trd_check==1)
{
	if(empty($managemode))
	{$adminforum1='帖子管理';$concle=1;}
	elseif($concle==2)
	{$adminforum1='帖子管理';$managemode="";Cookie("managemode","",0);$concle=1;}
	else
	{$adminforum1='退出管理';Cookie("managemode","1",0);$concle=2;}
	$ifecho['trd_admin1']=$ifecho['trd_admin2']='';
	$trd_adminhide="<form action=mawhole.php method=post><input type=hidden name=fid value=$fid>";
}

$msgarray=openfile('data/bulletin.php');
if($msgarray[0]!=''){
	$ifecho['trd_anc1']=$ifecho['trd_anc2']='';
	$detail=explode("|",$msgarray[0]);
	$rawnotic=rawurlencode($detail[1]);
}

$db_ftopnum=5;
$toparray=openfile('data/top.php');
$fcount=count($toparray);
if(!$toparray[$fcount-1])$fcount--;
$fcount=min($fcount,$db_ftopnum);
$topdb=array();
for($i=0;$i<$fcount;$i++)
{
	list($topfb,$top[author],$top[atcid],$top[settime],$top[title])=explode("|",$toparray[$i]);
	$top[rawauthor]=rawurlencode($top[author]);
	$topdb[]=$top;
}

for($i=$pagemax-1;$i>=0;$i--)
{
	$thread=array();
	if(empty($articleshow[$i])){
		continue;
	}
	list($lst_fb,$lst_null,$lst_null,$lst_author,$lst_null1,$thread['filename'],$thread['reply'],$lst_lastpost,$lst_ifinheard,$lst_null2)=explode("|",$articleshow[$i]);
	if(''==($articlearray=gets("$dbpath/$fid/$thread[filename].php",150))) continue;
	list($tpc_fb,$tpc_covert,$thread['author'],$rd_icon,$tpc_date,$thread['title'],$tpc_ip,$tpc_sign,$tpc_download,$tpc_rvrc,$tpc_buy,$tpc_from,$tpc_fconvert,$tpc_email,$tpc_content,$tpc_null1)=explode("|",$articlearray);
	list($thread['hit'],$rd_islock,$rd_title)=explode(',',$tpc_covert);
	if($rd_title){
		$titledetail=explode("~",$rd_title);
		if($titledetail[0])$thread['title']="<font color=$titledetail[0]>$thread[title]</font>";
		if($titledetail[1])$thread['title']="<b>$thread[title]</b>";
		if($titledetail[2])$thread['title']="<i>$thread[title]</i>";
		if($titledetail[3])$thread['title']="<u>$thread[title]</u>";
	}
	$thread['rawauthor']=rawurlencode($thread['author']);
	if(isset($managemode)&&$managemode ==1&&$trd_check==1)
	{
		$thread['atcma']='[';
		if($rd_islock==0 || $rd_islock==1)
			$thread['atcma'].="<a href='masingle.php?action=digest&fid=$fid&tid=$thread[filename]' title='加入精华帖'>精</a>";//digest
		elseif($rd_islock==2 || $rd_islock==3)//投票帖无精华的选项
			$thread['atcma'].="<a href='masingle.php?action=undigest&fid=$fid&tid=$thread[filename]' title='取消精华帖子'>除</a>";//digest
		if($rd_islock==0 || $rd_islock==2 || $rd_islock==4)
			$thread['atcma'].="<a href='masingle.php?action=lock&fid=$fid&tid=$thread[filename]' title='锁定帖子不让会员再回复帖子'>锁</a>";
		else
			$thread['atcma'].="<a href='masingle.php?action=unlock&fid=$fid&tid=$thread[filename]' title='解除锁定帖子'>解</a>";
		$thread['atcma'].="<a href='masingle.php?action=move&fid=$fid&tid=$thread[filename]' title='移动帖子到另一个版块'>移</a>";
		$thread['atcma'].="<a href='masingle.php?action=copy&fid=$fid&tid=$thread[filename]' title='复制帖子到另一个版块'>复</a>";
		if($lst_ifinheard>=3)
			$thread['atcma'].="<a href='masingle.php?action=unheadtopic&fid=$fid&tid=$thread[filename]' title='取消置顶:不再让主题置顶'>消</a>";
		else
			$thread['atcma'].="<a href='masingle.php?action=headtopic&fid=$fid&tid=$thread[filename]' title='置顶主题:将主题置顶,以便会员查看'>顶</a>";
		$thread['atcma'].="<a href='masingle.php?action=pushtopic&fid=$fid&tid=$thread[filename]' title='提前帖子'>提</a>";
		$thread['atcma'].="<a href='masingle.php?action=edit&fid=$fid&tid=$thread[filename]' title='编辑标题'>亮</a>";
		$thread['atcma'].="<a href='masingle.php?action=del&fid=$fid&tid=$thread[filename]' title='删除帖子'>删</a>]";
		$thread['atcma']='<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td align=right><font color=#ff8ba2>'.$thread['atcma'].'</font></td></tr></table>';
	}
	if($rd_islock==4)
		$thread['status']="<img src='$imgpath/$stylepath/thread/vote.gif' border=0>";
	elseif ($rd_islock==5)
		$thread['status']="<img src='$imgpath/$stylepath/thread/votelock.gif' border=0>";
	elseif ($lst_ifinheard>=3){
		$thread['status']="<img src='$imgpath/$stylepath/thread/headtopic.gif' border=0>";
	}else{
		$thread['status']="<img src='$imgpath/$stylepath/thread/topicnew.gif' border=0>";
		if ($thread['reply']>=10)
			$thread['status']="<img src='$imgpath/$stylepath/thread/topichot.gif' border=0>";
		if ($rd_islock==1 || $rd_islock==3)
			$thread['status']="<img src='$imgpath/$stylepath/thread/topiclock.gif' border=0>";
	}
	if ($thread['reply']+1>$db_readperpage)
	{
		if (($thread['reply']+1)%$db_readperpage==0)
			$numofpage=($thread['reply']+1)/$db_readperpage;
		else
			$numofpage=floor(($thread['reply']+1)/$db_readperpage)+1;
		$thread['ispage']=' ';
		$thread['ispage'].="[ <img src='$imgpath/$stylepath/file/multipage.gif' border=0><span style='font-size:7pt;font-family:verdana;'>";
		for ($j=1; $j<=$numofpage; $j++)
		{
			if ($j==6){
				$thread['ispage'].=" ... <a style='color:000066' href='topic.php?fid=$fid&tid=$thread[filename]&page=$numofpage&fpage=$page'>$numofpage</a>";
				break;
			}
			$thread['ispage'].=" <a style='color:505060' href='topic.php?fid=$fid&tid=$thread[filename]&page=$j&fpage=$page'>$j</a>";
		}
		$thread['ispage'].='</span> ]';
	}
	$thread['content']='主题发表于:'. date($db_tformat,$tpc_date);
	$postdetail=explode(",",$lst_lastpost);
	$thread['lpauthor']=$postdetail[0];
	$thread['rawlpauthor']=rawurlencode($postdetail[0]);
	$timecut=$timestamp-$postdetail[1];
	if($tpc_download!='') $thread['titleadd']=" <img src='$imgpath/$stylepath/file/attc.gif' align='absbottom' border=0>";else $thread['titleadd']="";
	if ($timecut<=$db_newtime)
		$thread['titleadd'].=" <img src='$imgpath/$stylepath/file/new.gif' border=0 alt='论坛新帖标志'>";
	if ($rd_islock==2 || $rd_islock==3)
		$thread['titleadd'].=" <img src='$imgpath/$stylepath/file/digest.gif' border=0 alt='论坛精华帖标志'>";
	$thread['lstptime']=date($db_tformat,$postdetail[1]);
	if ($trd_check==1){
		$thread['adminbox']="<input type='checkbox' name='tidarray[]' value=$thread[filename]>";
	}
	if($db_threademotion){
		if ($rd_icon=="R")
			$rd_icon=mt_rand(1,14);
		$thread['useriocn']="<img src='$imgpath/post/emotion/$rd_icon.gif' border=0> ";
	}
	$threaddb[]=$thread;
}
unset($articleshow,$thread,$msgarray,$toparray);
$db_perpage=count($threaddb);
@include './data/forumcache.php';
if($db_threadshowpost==1 && $gp_ifpost==1)
{
	unset($titletop1);
	$psot_sta='new';
	if($db_signhtfcode==1)
	{
		$htfcode='<br><a href=\'faq.php?faqjob=1#5\'> htf Code 开启</a>';
		if ($db_htfpic['pic']==1)
			$htfcode.="<br> [img] - 开启";
		else
			$htfcode.="<br> [img] - 关闭";
		if ($db_htfpic['flash']==1)
			$htfcode.="<br> [flash] - 开启";
		else
			$htfcode.="<br> [flash] - 关闭";
	}
	else
	{
		$htfcode='<br><a href=\'faq.php?faqjob=1#5\'>htf Code</a>关闭';
	}
	if($groupid=='guest')
	{
		$ifecho['read_login1']="";$ifecho['read_login2']="";
	}
	$fastpost='fastpost';
	list($fid_post,$fid_hide,$fid_sell,$fid_Tread)=explode("~",$fid_Cconcle);
	$fid_post==2 ? $htmlpost="disabled" : $htmlpost='';
	$fid_hide==2 ? $htmlhide="disabled" : $htmlhide="";
	$fid_sell==2 ? $htmlsell="disabled" : $htmlsell="";


}

include PrintEot('forum');footer();
//子版块函数
function forumchildren($i)
{
	global $htfid,$manager,$groupid,$imgpath,$stylepath,$trd_showchildren,$fidadminarray,$lastlogindate,$forumfontcolor,$dbpath,$fchild_id,$fchild_name,$fchild_logo,$fchild_ac,$fchild_pwd,$fchild_info,$fchild_childnum;
	

	$alter=explode("|",readover("$dbpath/$fchild_id[$i]/status.php"));
	$modifytime=$alter[5];
	$access=explode("~",$fchild_ac[$i]);
	if (empty($access[0])|| strpos($access[0],','.$groupid.',')!==false || $htfid==$manager){
		$forumpic=$lastlogindate<$alter[5] && ($alter[5]+172800>$timestamp) ? 'new' : 'old';
		$new_post=$alter[3]!=''?"<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td align=center title='$alter[1]'><a href='topic.php?fid=$alter[4]&page=lastpost#lastatc'>$alter[3]</a><br>von: <a href=usercp.php?action=show&username=".rawurlencode($alter[2]).">$alter[2]</a></td></tr></table>":'暂无内容';
	}else{
		$forumpic='lock';
		$new_post='认证论坛';
	}
	
	$forumname="<a href='forum.php?fid=$fchild_id[$i]'><font face=verdana class='fnamecolor'>{$fchild_name[$i]}</font></a>";
    if($fchild_pwd[$i]!="")
		$forumname.=" <font color=gray>[已经加密]</font>";
	if (!empty($fidadminarray[$fchild_id[$i]]))
	{
		$count=count($fidadminarray[$fchild_id[$i]]);
		for ($j=0; $j<$count; $j++)
		{
			if ($j==3) {$admin_list.='<br><span class=bold>...</span>'; break;}
			$adminname=$fidadminarray[$fchild_id[$i]][$j];
			$admin_list.="<a href=usercp.php?action=show&username=".rawurlencode($adminname).">$adminname</a> ";
		}
	}
	else
		$admin_list.="招聘中";
	$trd_showchildren.="<tr height=41><td width=5% align=center valign=middle class='f_two'><img src='$imgpath/$stylepath/$forumpic.gif' border=0></td><td width=56% class='f_one' align=left><a href='forum.php?fid=$fchild_id[$i]'>{$forumlogo}</a>{$forumname}<br>{$fchild_info[$i]}</td><td width=10% class='f_two' align=center style='word-break: keep-all'>{$admin_list}</td><td width=10% class='f_one' valign=middle align=center><span class=bold>$alter[6]</span></td><td width=19% class='f_two' align=center>$new_post</td></tr>";
}
//获取子版块状态函数
function showchildreninfo()
{
	global $forumcount,$fid,$forumarray,$fchild_type,$fchild_name,$fchild_logo,$fchild_pwd,$fchild_ac,$fchild_id,$fchildnum,$fchild_info,$fchild_childnum;
    $fchildnum=0;
    for ($i=0; $i<$forumcount; $i++)
	{
		$detail=explode("|", trim($forumarray[$i]));
		if ($detail[5]==$fid)
		{
			$fchild_type[$fchildnum]=$detail[1];
			$fchild_name[$fchildnum]=$detail[2];
			$fchild_id[$fchildnum]=$detail[4];
			$fchild_info[$fchildnum]=$detail[3];
			$fchild_logo[$fchildnum]=$detail[15];
			$fchild_pwd[$fchildnum]=$detail[6];
			$fchild_ac[$fchildnum]=$detail[16];
			$fchild_childnum[$fchildnum]=$detail[13];//n级版块名
			$fchildnum++;
		}
	}
}
function gettoparray($filename,$db_linesize)
{
	global $db_topnum;
	$offset=($db_linesize+1);$num=0;$readb=array();
	if($fp=fopen($filename,"rb")){
		flock($fp,LOCK_SH);
		fseek($fp,$offset,SEEK_SET);
		while(!feof($fp)&&$num<$db_topnum){
			$topdb=fgets($fp,100);
			if(strpos($topdb,"|") !==false){
				if(strlen($topdb)!=$db_linesize+1)
					$topdb=str_pad(trim($topdb),$db_linesize)."\n";
				$readb[]=$topdb;
			}elseif($num>2){
				break;
			}
			$num++;
		}
		fclose($fp);
	}
	return array_reverse($readb);
}
function readfrombot($filename,$db_linesize)
{
	global $db_topnum,$page,$db_perpage;
	
	$total=$page*$db_perpage;
	$persize=$db_linesize+1;
	$num=0;$readb=array();
	if($fp=fopen($filename,"rb")){
		flock($fp,LOCK_SH);
		fseek($fp,0,SEEK_END);
		$filesize=ftell($fp);
		$topicnum=floor($filesize/$persize)-$db_topnum-1;
		$linestart=min($total,$topicnum);
		$linenum=$linestart-$total+$db_perpage;
		$offset=max(-$persize*$linestart,-($filesize-$persize*($db_topnum+1)));
		fseek($fp,$offset,SEEK_END);
		$readb=fread($fp,$linenum*($db_linesize+1));
		fclose($fp);
		$readb=trim($readb);
		$readb=explode("\n",$readb);
	}
	return array($readb,$topicnum);
}
?>