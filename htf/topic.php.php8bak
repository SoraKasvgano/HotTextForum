<?php
require './global.php';
require './data/level.php';
require './require/forum.php';
require './require/numofpage.php';
//require './require/htfxiu.php';
require './require/bbscode.php';
$ifecho = array
(
	'read_sadm1' =>'<!--',
	'read_sadm2' =>'-->',
	'read_login1'=>'<!-- ',
	'read_login2'=>' -->'
);

if (!file_exists("$dbpath/$fid/$tid.php"))
{include('require/url_error.php');}
if(empty($forumcount)) list($forumcount,$forumarray)=getforumdb();



htf_forumcheck();
list($fid_post,$fid_hide,$fid_sell,$fid_Tread)=explode("~",$fid_Cconcle);

if($groupid=='guest' && $fid_Tread==2){
	showmsg("版块设置: 您还没登陆,无法浏览单一主题!");
}
/**
*用户组权限判断
*/
if(!$allowvisit && $gp_ifread==0){
	showmsg("你所属的用户组没有浏览帖子的权限");
}
unset($forumarray);
$secondname=$fid_name;
$secondurl="forum.php?fid=$fid&page=$fpage";

/**
*获取管理员权限之人
*/
list($forum_admin,$father_admin,$fidadminarray)=getforumadmin();

if (!$page) $page=1;
$readdb=array();
$articlearray=openfile("$dbpath/$fid/$tid.php");
$topic_detail=explode("|",$articlearray[0]);
list($tpc_fb,$tpc_covert,$tpc_author,$rd_icon,$tpc_date,$tpc_title,$tpc_ip,$tpc_sign,$tpc_download,$tpc_rvrc,$tpc_buy,$tpc_ipfrom,$tpc_ifconvert,$tpc_concle,$tpc_content,$tpc_null1)=$topic_detail;
list($rd_hit,$rd_islock,$rd_null)=explode(',',$tpc_covert);
$rd_hit=trim($rd_hit);
strlen($rd_hit)<9 && $rd_hit++;
//$rd_hit=str_pad($rd_hit,8);
$hitwrite="<?die;?>|$rd_hit";
if($fp=fopen("$dbpath/$fid/$tid.php","rb+")){
	flock($fp,LOCK_EX);
	fseek($fp,0);
	fputs($fp,$hitwrite);
	fclose($fp);
}

$count=count($articlearray);

if ($rd_islock==0 || $rd_islock==1)
{$digestaction='digest';$digestname="[<a href='masingle.php?action=$digestaction&fid=$fid&tid=$tid' title='{精华}帖子'>精华</a>]";}
elseif($rd_islock==2 || $rd_islock==3)
{$digestaction='undigest';$digestname="[<a href='masingle.php?action=$digestaction&fid=$fid&tid=$tid' title='{取消精华}帖子'>取消精华</a>]";;}//模版中控制

if ($count%$db_readperpage==0) //$count $db_readperpage topic.php?fid=$fid&tid=$tid&
	$numofpage=$count/$db_readperpage;
else
	$numofpage=floor($count/$db_readperpage)+1;
if ($page=='lastpost' || $page>$numofpage) //$page
	$page=$numofpage;
$pagemin=min(($page-1)*$db_readperpage , $count-1);
$pagemax=min($pagemin+$db_readperpage-1, $count-1);
$fengye=numofpage($count,$page,$numofpage,"topic.php?fid=$fid&tid=$tid&");//文章数,页码,共几页,路径


$titletop=str_replace('&#39','`',$tpc_title);
$firstname="您是本帖的第 <span class=bold>$rd_hit</span> 个阅读者";
$tpctitle=' - '.$titletop;
require './header.php';
$msg_guide=headguide($secondname,$secondurl,$titletop,'',$firstname);
$admin_check=0;
if ($groupid=='superadmin' || $htfid==$manager)
	$settopatc="[<a href=top.php?fid=$fid&tid=$tid&oldtitle=".rawurlencode($tpc_title)." title='总置顶'>总置顶</a>]";
if ($groupid!='guest' && (($forum_admin && in_array($htfid,$forum_admin)) ||($father_admin && in_array($htfid,$father_admin) || $groupid=='superadmin') || $htfid==$manager))
{
	$admin_check=1;$ifecho['read_sadm1']=$ifecho['read_sadm2']='';
}

if ($page==1 || $numofpage==1)
{
	if($rd_islock>=4)
	{
		include './require/readvote.php';
		$vote_date=date($db_tformat,$tpc_date);
		vote($voteopts);
	}
}
for ($i=$pagemin; $i<=$pagemax; $i++)
{
	$read=array();
	$read['lou']=$i;
	$i==$pagemax ? $read['jupend']='<a name=lastatc></a>':'';
	$i%2==0?$read['colour']=$readcolorone:$read['colour']=$readcolortwo;
	if ($i!=0){
		list($tpc_fb,$tpc_covert,$tpc_author,$rd_icon,$tpc_date,$tpc_title,$tpc_ip,$tpc_sign,$tpc_download,$tpc_rvrc,$tpc_buy,$tpc_ipfrom,$tpc_ifconvert,$tpc_concle,$tpc_content,$tpc_null1)=explode("|",$articlearray[$i]);
	}

	if(file_exists("$userpath/$tpc_author.php")&&($read['author']!=$tpc_author || $xiu[$tpc_author]==1))
	{
		if(empty($userinfo[$tpc_author]))
			$userinfo[$tpc_author]=readover("$userpath/$tpc_author.php");
		$htf=explode("|",$userinfo[$tpc_author]);
		$read['lpic']=$lpic[$htf[5]];
		$read['level']=$ltitle[$htf[5]];
		$read['regdate']=date("Y-m-d",$htf[8]);
		$read['lastlogin']=date("Y-m-d",$htf[20]);
		$xiu[$tpc_author]=$htf[32];
		$signauthor[$tpc_author]=$htf[9];
		$ifsigncovert[$tpc_author]=$htf[35];
		$usergroup[$tpc_author]=$htf[5];
		$read['postnum']=$htf[16];
		$read['aurvrc']=floor($htf[17]/10);
		$read['money']=$htf[18];
		$read['author']=$tpc_author;
		/*if($htf[32]==1)
			$read[face]=gethtfxiu($tpc_author,$i,140,226);//虚拟形象插件接口
		else
		{*/
			if ($htf[6]=='')
				$read['face']="<img src='$imgpath/face/0.gif'>";
			else
				$read['face']=showfacedesign($htf[6]);
		//}
		if($db_ipfrom==1 || $admin_check==1) $read['ipfrom']=' From:'.$tpc_ipfrom;
		$db_showonline==1 && $timestamp-$htf[20]<$db_onlinetime*1.5 ? $read['ifonline']="在线":$read['ifonline']="离线";
		if ($htf[15])
			$read['honor']="<img src='$imgpath/$stylepath/level/rongyu.gif' alt=\"头衔：$htf[15]\">$htf[15]<br>";
		if($db_ifonlinetime)
		{
			$houronline=floor($htf[34]/3600);
			$read['ontime']="在线时间:$houronline 小时<br>";
		}
		$htf[11] && $read['qq']="&nbsp;<a  href='http://search.tencent.com/cgi-bin/friend/user_show_info?ln=$htf[11]' target=_blank>QQ</a>";
		$tpcline[$tpc_author]="";
	}
	elseif(!file_exists("$userpath/$tpc_author.php")){
		$read['author']=$tpc_author;
		$read['face']="<br><br>";$read['lpic']='0.gif';
		$read['level']=$read['postnum']=$read['money']=$read['regdate']=$read['lastlogin']=$read['aurvrc']='*';
	}
	$read['rawauthor']=rawurlencode($read['author']);
	$E_array=explode(",",$tpc_concle);
	if($E_array[1]==1 || ($usergroup[$tpc_author]=='banned' && $admin_check==0) || empty($tpc_content)){
		$tpc_title='';$tpc_ifconvert=1;$tpc_sign=0;
		$tpc_content='+----------------------------------+<br>&nbsp;此人已被禁言<br>+----------------------------------+';

	}
	//版主可见禁言组的发言
	$read['postdate']=date($db_tformat,$tpc_date);

	if($tpc_rvrc==''&&$admin_check!=1)
	{
		$read['ip']='已记录';
		$read['ping']="<a href=\"javascript:scroll(0,0)\">回到顶端</a>";
	}
	elseif($tpc_rvrc!=''){
		list($rvrc,$adminrvrc)=explode(',',$tpc_rvrc);
		$read['ping']="<span class=bold title='评分人:$adminrvrc'>加 <font color=red>$rvrc</font> 分</span>";
	}
	elseif($admin_check==1)
	{
		$read['ip']=$tpc_ip;
		$read['ping']="<select onchange=\"if(this.options[this.selectedIndex].value != '') {window.location=('masingle.php?action=showping&fid=$fid&tid=$tid&article=$i&postrvrc='+this.options[this.selectedIndex].value) }\"><option value=0>0</option><option value=1>1</option><option value=2>2</option><option value=3>3</option><option value=4>4</option><option value=5>5</option><option value=-1>-1</option><option value=-2>-2</option><option value=-3>-3</option><option value=-4>-4</option><option value=-5>-5</option></select>";
	}
	$rd_icon=="R" && $rd_icon=mt_rand(1,14);
	$read['icon']=$rd_icon;
	$tpc_ifconvert!=1 && $tpc_content=convert($tpc_content,$db_htfpost);//动态判断发贴转换
	$read['tpctitle']=$tpc_title;

	if ($tpc_sign==1 && $signauthor[$tpc_author] && !$sign[$tpc_author]){
		if($ifsigncovert[$tpc_author]<>1){
			$signauthor[$tpc_author]=convert($signauthor[$tpc_author],$db_htfpic,2);//动态判断发贴转换
		}
		$sign[$tpc_author]=$signauthor[$tpc_author];
	}
	$read['sign']=$sign[$tpc_author];
	$downattach=$downpic='';
	if($tpc_download!=''){
		$downloaddb=explode("~",$tpc_download);
		foreach($downloaddb as $key=>$tpc_download){
			if($tpc_download){
				//$download=rawurlencode($tpc_download);
				list($dfurl,$dfname,$dfhit,$dfrvrc,$dfinfo)=explode(",",$tpc_download);
				if(file_exists("$attachpath/$dfurl"))
				{
					if ($admin_check==1 || $htfid==$tpc_author)
					{
						$dfadmin="[<a href='peruse.php?action=deldownfile&fid=$fid&tid=$tid&i=$i&id=$key'>删除</a>]";
					}
					if($dfinfo=='img' && $dfrvrc==0){
						$dfurl='<br>'.cvpic("$attachpath/$dfurl",1);
						$downpic.="$dfurl $dfadmin";
					}
					else{
						$dfsize=ceil(filesize("$attachpath/$dfurl")/1024);
						$dfurl="<a href='peruse.php?action=download&fid=$fid&tid=$tid&i=$i&id=$key' target='_blank'><font color=red>$dfname</font></a> ($dfsize K)<br>下载次数:$dfhit";
						if($db_needrvrc==1 || $dfrvrc!=0)$dfurl.="  需要威望:$dfrvrc";
						$downattach.="<br><img src='$imgpath/$stylepath/file/$dfinfo.gif' align=absbottom>  附件：$dfurl $dfadmin";
					}
				}
			}
		}
	}
	$read['tpccontent']=$downpic.'<br><br>'.$tpc_content.'<br>'.$downattach;
	$readdb[]=$read;
}
unset($articlearray,$read,$htf,$ltitle,$lpic,$lpost);

@include './data/forumcache.php';
if($gp_ifpost==1)
{
	if($db_signhtfcode==1)
	{
		$htfcode='<br><a href=\'faq.php?faqjob=1#5\'> htf Code 开启</a>';
		if ($db_htfpic['pic']==1)
			$htfcode.='<br> [img] - 开启';
		else
			$htfcode.='<br> [img] - 关闭';
		if ($db_htfpic['flash']==1)
			$htfcode.='<br> [flash] - 开启';
		else
			$htfcode.='<br> [flash] - 关闭';
	}
	else
	{
		$htfcode='<br><a href=\'faq.php?faqjob=1#5\'>htf Code</a>关闭';
	}
	if($groupid=='guest')
	{
		$ifecho['read_login1']=$ifecho['read_login2']='';
	}
	$psot_sta='reply';
	$titletop1='Re:'.$titletop;
	$fastpost='fastpost';
	$fid_post==2 ? $htmlpost="disabled" : $htmlpost='';
	$fid_hide==2 ? $htmlhide="disabled" : $htmlhide="";
	$fid_sell==2 ? $htmlsell="disabled" : $htmlsell="";

}

include PrintEot('topic');footer();
?>