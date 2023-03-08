<?php
$star_action='hm';
require './data/level.php';
require './global.php';
require './header.php';
$ifecho = array
(
	'id_pt1'  =>'<!--',	'id_pt2'  =>'-->',
	'id_bs1'  =>'<!--',	'id_bs2'  =>'-->',
	'id_lg1'  =>'<!--',	'id_lg2'  =>'-->'
);
$admindb=openfile('data/admin.php');
$count=count($admindb);
for ($i=0; $i<$count; $i++)
{
	$detail=explode("|", trim($admindb[$i]));
	$adminarray[]=$detail[2];
	$fidadminarray[$detail[1]][]=$detail[2];
}
$level=$ltitle[$groupid];
unset($admindb,$lpic,$lpost);

if($groupid=='guest'){
	$ifecho['id_nlg1']=$ifecho['id_nlg2']='';
}
else{
	$lastlodate=date($db_tformat,$htfdb[19]);
	$ifecho['id_lg1']=$ifecho['id_lg2']='';
}
if($db_todaypost){
	$ifecho['id_pt1']=$ifecho['id_pt2']='';
}

if(empty($forumcount)) list($forumcount,$forumarray)=getforumdb();
$forumdb=array();
for ($i=0; $i<$forumcount; $i++)
{
	$detail=explode("|",$forumarray[$i]);
	if ($detail[1]!="category" && $detail[5]==0 && forumpermission($htfid,$detail[1],$detail[4])) 
	{
		$forum=array();
		$forum['type']='forum';
		$forum['fid']=$detail[4];
		$forum['info']=$detail[3];
		if($db_indexfmlogo==2)
		{
			if($detail[15]!='')$forum['logo']="<img align=left src=$detail[15] border=0>";
		}
		elseif($db_indexfmlogo==1)
		{
			$forumlogofile="$imgpath/$stylepath/forumlogo/$forum[fid].gif";
			$forum['logo']="<img align=left src=$forumlogofile border=0>";
		}
		$alter='';
		$alter=explode("|",readover("$dbpath/$forum[fid]/status.php"));
		$alter[1]=str_replace('%a%','',$alter[1]);
		$forum['tpc']=$alter[7]+$alter[8];
		$alter[6]?$forum['atc']=$alter[6]:$forum['atc']=0;
		$access=explode("~",$detail[16]);
		if (empty($access[0])|| strpos($access[0],','.$groupid.',')!==false || $htfid==$manager){
			$forum['pic'] = $htfdb[19]<$alter[5] && ($alter[5]+172800>$timestamp) ? 'new' : 'old';
			$forum['newtitle']=$alter[1];
			$forum['newpost']=$alter[3]!=''?"<a href='topic.php?fid=$alter[4]&page=lastpost#lastatc'>$alter[3]</a><br>von: <a href=usercp.php?action=show&username=".rawurlencode($alter[2]).">$alter[2]</a>&nbsp;":'暂无内容';
		}else{
			$forum['pic']='lock';
			$forum['newpost']='认证论坛';
		}
		$forum['name']=$detail[2];
		if($detail[6]!='') $forum['name'].=" <font color=gray>[已经加密]</font>";
		if($fidadminarray[$forum['fid']])
		{
			$count=count($fidadminarray[$forum['fid']]);
			for ($j=0; $j<$count; $j++) 
			{
				if ($j==4) {$forum['admin'].='...'; break;}
				$adminname=$fidadminarray[$forum['fid']][$j];
				$forum['admin'].="<a href=usercp.php?action=show&username=".rawurlencode($adminname).">$adminname</a> ";
			}
		}
		$forumdb[]=$forum;
	}
	elseif($detail[1]=="category")
	{
		$forum['type']='category';
		$forum['name']=$detail[2];
		$forumdb[]=$forum;
	}
}
unset($forumarray,$forum);
@include "./data/indexcache.php";
if($db_indexmqshare){
	$index_link="<marquee scrolldelay=100 scrollamount=4 onmouseout='if (document.all!=null){this.start()}' onmouseover='if (document.all!=null){this.stop()}' behavior=alternate>".$index_link.'</marquee>';
}
list($bbsfb,$bbsnewer,$bbstotleuser)=explode("|",readover("data/bbsnew.php"));
list($bbsfb,$bbsol,$bbsoltime)=explode("|",readover("data/bbsonline.php"));
list($bbsfb,$bbstpc,$bbsatc,$bbstoday,$bbsyestoday,$bbsmost,$bbspostcontrol,$bbsbirthcontrol,$bbsstar,$bbsrich,$bbslucher,$bbsbirthman)=explode("|",readover("data/bbsatc.php"));
$rawnewuser=rawurlencode($bbsnewer);/*解除中文链接问题*/
$rawhtfid=rawurlencode($htfid);

if($online){
	$online1=$online;
	Cookie('online1',$online);
}
if($db_indexonline&&$online1!='no')
		$doonlinefu=1;
elseif($online1=='yes')
		$doonlinefu=1;
@include_once 'data/olcache.php';/*避免调用两次,不过第一次时会发现在线少一,刷新就恢复了*/
$usertotal=$guestinbbs+$userinbbs;
if ($doonlinefu==1){
	$index_whosonline=bbsonline();
}
unset($fidadminarray,$adminarray);
if($bbsol<$usertotal){
	$bbsol=$usertotal;
	$bbsoltime=$timestamp;
	$writebbsatc="<?die();?>|$bbsol|$bbsoltime|";
	writeover("data/bbsonline.php",$writebbsatc);
}
$mostinbbstime=date($db_tformat,$bbsoltime);
if($usertotal>=$db_onlinelmt && $db_onlinelmt!=0){

	ob_end_clean();//将header.php输出的头信息去除
	showmsg("状态:发生错误,论坛在线会员数已经达到最大值{$onlinecount_lmt},请稍后再来!");
}
include PrintEot('index');footer();
//首页在线函数
function bbsonline()
{
	global $imgpath,$stylepath,$groupid,$tablecolor,$db_showguest,$timestamp,$db_onlinetime,$adminarray,$htfid,$superadmin,$manager,$surpadmin,$online;
	global $db_sofast,$db_olsize;
	$flag=-1;
	$admincheck=0;
	if ($groupid!='guest' && (($adminarray && in_array($htfid,$adminarray)) || $groupid=='superadmin'|| $htfid==$manager ))
	{
		$admincheck=1;
	}
	$onlinearray=openfile("data/online.php");
	$count_ol=count($onlinearray);
	if($onlinearray[0]=='') $count_ol=0;
	for($i=1; $i<$count_ol; $i++)
	{
		if(strpos($onlinearray[$i],"|") !==false){
			$onlinedb=explode("|",$onlinearray[$i]);
			$inread='';
			if($onlinedb[4]) $inread='(帖子)';
			switch($onlinedb[5])
			{
				case 'manager':$img='0'; break;
				case 'superadmin':$img='1';break;
				case 'admin':$img='2';break;
				case 'rzuser':$img='3';break;
				case 'ctuser':$img='4';break;
				default:$img='5';
			}
			if($onlinedb[0]=='<>'){
				$img='5';$onlinedb[0]='隐身会员';
				if($htfid==$manager)
					$adminonly="隐身:$onlinedb[8]\n";
			}
			else{
				$adminonly='';
			}
			if($admincheck===1)
			{
				$adminonly="{$adminonly}I P : $onlinedb[2]\n";
			}
			$onlineinfo="{$adminonly}论坛: $onlinedb[6]{$inread}\n时间: $onlinedb[7]";
			$flag++;
			if($flag%7===0) $index_whosonline.='</tr><tr>';
			$index_whosonline.="<td width=14%><img src='$imgpath/$stylepath/group/$img.gif' align='absbottom'></a><a href=usercp.php?action=show&username=".rawurlencode($onlinedb[0])." title='$onlineinfo'>$onlinedb[0]</a></td>";
		}
	}
	unset($onlinearray);
	if($db_showguest===1){
		$guestarray=openfile("data/guest.php");
		$unregcount=count($guestarray);
		for ($i=1;$i<$unregcount; $i++){
			if(strpos($guestarray[$i],"|")!==false){
				$guestdb=explode("|",$guestarray[$i]);
				$inread='';
				if($guestdb[3]) $inread='(帖子)';
				if($admincheck===1){
					$ipinfo="I P : {$guestdb[0]}\n";
				}
				$onlineinfo="{$ipinfo}论坛: $guestdb[4]{$inread}\n时间: {$guestdb[5]}";
				$flag++;
				if($flag%7===0)
					$index_whosonline.='</tr><tr>'; 
				$index_whosonline.="<td width=14%><img src='$imgpath/$stylepath/group/6.gif' align='absbottom'><a title='$onlineinfo'>guest</a></td>";		
			}
		}
		unset($guestarray);
	}
	return $index_whosonline;
}
function forumpermission($user,$type,$forumid){
	global $fidadminarray,$groupid;
	$check=0;
	if ($type!='hidden')$check=1;
	elseif($user<>'' &&(($fidadminarray[$forumid] && in_array($user,$fidadminarray[$forumid])) || $groupid=='manager'))$check=1;
	return $check;
}
?>