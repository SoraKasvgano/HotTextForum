<?php
require "./global.php";
require "./require/dbmodify.php";
include './require/forum.php';
list($bbsfb,$bbstpc,$bbsatc,$bbstoday,$bbsyestoday,$bbsmost,$bbspostcontrol,$bbsbirthcontrol,$bbsstar,$bbsrich,$bbslucher,$bbsbirthman)=explode("|",readover("data/bbsatc.php"));
if(empty($forumcount)) list($forumcount,$forumarray)=getforumdb();
htf_forumcheck();

$secondname="$fid_name";
$secondurl="forum.php?fid=$fid";

$groupid=='guest' && showmsg("程序安全验证失败,我们已经记录你的IP 如果造成损失,我们将追究责任");
empty($action)    && showmsg("版块为空,或帖子数据为空,或没有选择管理条目");

//管理验证开始!

list($forum_admin,$father_admin,$fidadminarray)=getforumadmin();
if(($forum_admin && in_array($htfid,$forum_admin)) ||($father_admin && in_array($htfid,$father_admin)) ||$groupid=='superadmin' || $htfid==$manager) $ma_check=1;else $ma_check=0;

if ($ma_check==0){

	showmsg("您没有权利进行操作,请您以合适的身份登录(管理员,斑竹)");
}
/**
* 特殊版块删除积分获得
*/
if($fid_Pconcle!=''){
	list($P_Prvrc,$P_Rrvrc,$P_Pmoney,$P_Rmoney,$P_Drvrc,$P_Dmoney)=explode("~",$fid_Pconcle);
	is_numeric($P_Drvrc) && $db_dtdelrvrc=$P_Prvrc;
	is_numeric($P_Dmoney) && $db_dtdelmoney=$P_Prvrc;
}

$atcname="$dbpath/$fid/$tid.php";
if ($action=="showping")
{
	if (!file_exists($atcname)) {include("require/url_error.php");}
	$articlearray=openfile($atcname);
	$count=count($articlearray);
	if (!isset($article) || !$articlearray[$article]){include("require/url_error.php");}
	$pingdetail=explode("|",$articlearray[$article]);
	$tpc_author=$pingdetail[2];
	if($article!=0){$ping_db=explode("|",$articlearray[0]);$tpc_title='Re:'.$ping_db[5];}else{$tpc_title=$pingdetail[5];}
	list($rvrc,$adminrvrc)=explode(",",$pingdetail[9]);
	if (ereg("[0-9]{1,3}$",$rvrc)){

		showmsg("此贴已经被评分,请不要重复,如有问题请联系官方论坛");
	}
	if ($htfid==$tpc_author && $htfid != $manager){

		showmsg("对不起,您不能给自己发表的贴子评分,请返回");
    }
	if ($_POST['step'] != 1)
	{
		require "./header.php";
		$msg_guide=headguide($secondname,$secondurl,'参与评分');
		$posttime=date($db_tformat,$pingdetail[4]);
		if (strlen($pingdetail[14])>300) 
			$pingdetail[14]=substrs($pingdetail[14],294);
		$raw_author=rawurlencode($tpc_author);
		include PrintEot('masingle');footer();
	}
	$pingrvrc=$postrvrc*10;
	$pingdetail[9]="$postrvrc,$adminping";
	$articlearray[$article]=implode("|",$pingdetail);
	$articledb=implode("",$articlearray);
	writeover($atcname,$articledb);
	if($tpc_author!='guest'){
		dtchange($tpc_author,$pingrvrc,0,0);
		$page=floor($article/$db_readperpage)+1;
		$new="<?die;?>|系统信息|你的文章被评分!!|$timestamp|你的帖子<span class=bold>[<a href=\"topic.php?fid=$fid&tid=$tid&page=$page\" target=blank><font color=blue>$tpc_title</font></a>]</span>  得到<font color=blue>$adminping</font>的<font color=red>评价</font>,由此对你积分的影响是<font color=red>$postrvrc</font>威望。|0|\n";
		writeover("data/$msgpath/{$tpc_author}1.php",$new,"ab");//增减威望后发短消息通知作者
	}
	refreshto("topic.php?fid=$fid&tid=$tid","评分成功");
}
if (empty($_POST['step'])) 
{
	require "./header.php";
	$msg_guide=headguide($secondname,$secondurl);
	if($action=="del")
		$ma_whatdo="删除贴子";
	elseif($action=="move") 
		$ma_whatdo="转移贴子：请选择要转移到的版块";
	elseif($action=="copy") 
		$ma_whatdo="复制贴子：请选择要复制到的版块";
	elseif($action=="lock")
		$ma_whatdo="锁住贴子";
	elseif($action=="unlock")
		$ma_whatdo="解锁贴子";
	elseif($action=="digest")
		$ma_whatdo="将贴子加入精华";
	elseif($action=="undigest")
		$ma_whatdo="将贴子取消精华";
	elseif($action=="pushtopic")
		$ma_whatdo="前移贴子";
	elseif($action=="headtopic") 
		$ma_whatdo="置顶贴子";
	elseif($action=="unheadtopic")
		$ma_whatdo="取消置顶";
	elseif($action=="edit"){
		$ma_whatdo="编辑标题";
		$articledb=openfile($atcname);
		$detail=explode("|",$articledb[0]);
		$T_P_C=explode(",",$detail[1]);
		$titledetail=explode("~",$T_P_C[2]);
		$titlecolor=$titledetail[0];
		$ifchecked[$titlecolor]='checked';
		if($titledetail[1]=='1')$ifchecked[1]='checked';
		if($titledetail[2]=='1')$ifchecked[2]='checked';
		if($titledetail[3]=='1')$ifchecked[3]='checked';
	}
	if($action=="move" || $action=="copy") 
	{
		for ($i=0; $i<$forumcount; $i++) 
		{
			$forumdetail=explode("|",$forumarray[$i]);
			if ($forumdetail[1]!="category" && $forumdetail[4]!=$fid)
			{
				if ($forumdetail[5]==0) 
					$mg_jumpforum.="<OPTION value=\"$forumdetail[4]\">$forumdetail[2]";
				else $mg_jumpforum.="<OPTION value=\"$forumdetail[4]\">&nbsp;|- $forumdetail[2]";
			}
		}
	}
	include PrintEot('masingle');footer();
}
$filename="$dbpath/$fid/list.php";
if ($_POST['action']=="move" || $_POST['action']=="copy")
{
	if (file_exists($filename))
	{
		list($toparray,$detaillst)=searchtop($filename,$db_linesize,$tid);
		if(is_array($detaillst)){
			if($_POST['action']=="move"){
				$writedb=implode("",$toparray).str_pad(' ',$db_linesize)."\n";
				writeselect($filename,$writedb,1,$db_linesize);
			}
			unset($toparray);
		}
		else{
			list($fp,$writearray,$detaillst,$fastwrite)=readsearch($filename,$tid,$db_linesize);
			if($_POST['action']=="move"){
				write_del($fp,$writearray,$fastwrite);
			}
			fclose($fp);
		}
		$nextname=shownextname($gotoboard);
		@copy("$dbpath/$fid/{$detaillst[5]}vote.php","$dbpath/$gotoboard/{$nextname}vote.php");
		if($_POST['action']=="copy"){
			@copy("$dbpath/$fid/$detaillst[5].php", "$dbpath/$gotoboard/$nextname.php");
		}elseif($_POST['action']=="move"){

			Move_topic($fid,$detaillst[5],$gotoboard,$nextname);

			@unlink("$dbpath/$fid/$detaillst[5].php");
			@unlink("$dbpath/$fid/{$detaillst[5]}vote.php");
		}
		$tpcdb=gets("$dbpath/$gotoboard/$nextname.php",200);
		$detaillst[1]=$fid;//此功能是在 forum.php页面判定此贴的来源!
		$detaillst[5]=$nextname;
		$detaillst[8]=0;
		$getnewitem=str_pad(trim(implode("|",$detaillst)),$db_linesize)."\n";
		writeover("$dbpath/$gotoboard/list.php",$getnewitem,"ab");
		$tpcdetail=explode("|",$tpcdb);
		$statusdetail=explode("|",readover("$dbpath/$gotoboard/status.php"));
		$statusdetail[0]='<?die;?>';$statusdetail[1]=$tpcdetail[5];$statusdetail[2]=$tpcdetail[2];
		$statusdetail[3]=date($db_tformat,$timestamp);$statusdetail[4]="$gotoboard&tid=$nextname";
		$statusdetail[5]=$timestamp;
		$statusdetail[6]<0?$statusdetail[6]=$detaillst[6]+1:$statusdetail[6]+=$detaillst[6]+1;
		$statusdetail[7]<0?$statusdetail[7]=1:$statusdetail[7]++;
		$writestatus=implode("|",$statusdetail);
		writeover("$dbpath/$gotoboard/status.php",$writestatus);
		if ($_POST['action']=="move")
		{
			list($lasttid,$lasttitle,$lastauthor,$laststamp,$lasttime)=getnewstatus($fid);
			$statusdetail=explode("|",readover("$dbpath/$fid/status.php"));
			$statusdetail[1]=$lasttitle;$statusdetail[2]=$lastauthor;$statusdetail[3]=$lasttime;
			$statusdetail[4]=$lasttid;$statusdetail[5]=$laststamp;
			$statusdetail[6]<$detaillst[6]+1 ? $statusdetail[6]=0 : $statusdetail[6]-=$detaillst[6]+1;
			$statusdetail[7]<0 ? $statusdetail[7]=0 : $statusdetail[7]--;
			$writestatus=implode("|",$statusdetail);
			writeover("$dbpath/$fid/status.php",$writestatus);
		}
		else
		{
			$bbstpc++;
			$bbsatc+=$detaillst[6]+1;
			$writebbsatcdb="<?die;?>|$bbstpc|$bbsatc|$bbstoday|$bbsyestoday|$bbsmost|$bbspostcontrol|$bbsbirthcontrol|$bbsstar|$bbsrich|$bbslucher|$bbsbirthman|";
			writeover("data/bbsatc.php",$writebbsatcdb);
		}
		refreshto("forum.php?fid=$fid","管理程式已经成功执行了操作");
	}
}
//////////////////////////////////////////////////////////////////////
if(file_exists($atcname) && ($_POST['action']=="lock" || $_POST['action']=="unlock" || $_POST['action']=="digest" || $_POST['action']=="undigest"||$_POST['action']=="edit"))
{
	$articledb=openfile($atcname);
	$detail=explode("|",$articledb[0]);
	$T_P_C=explode(",",$detail[1]);
	if ($_POST['action']=="lock")
	{
		if ($T_P_C[1]==0 || $T_P_C[1]==2 || $T_P_C[1]==4)
			$T_P_C[1]=$T_P_C[1]+1;
	}
	elseif ($_POST['action']=="unlock")
	{
		if ($T_P_C[1]==1 || $T_P_C[1]==3 || $T_P_C[1]==5)
			$T_P_C[1]=$T_P_C[1]-1;
	}
	elseif ($_POST['action']=="digest") 
	{
		/*if($htfid==$detail[2])
		{
			showmsg("就算您是管理员也不可精华自己的帖子");
		}*/
		if($T_P_C[1]==0||$T_P_C[1]==1){
			$T_P_C[1]=$T_P_C[1]+2;
			$msgshowrvrc=floor($db_dtjhrvrc/10);
			dtchange($detail[2],$db_dtjhrvrc,"0",$db_dtjhmoney);
			$new="<?die;?>|帖子信息|恭喜恭喜！|$timestamp|恭喜 $htfid 将您的帖子设置为精华贴 这是帖子的最高奖励,因此您的威望增加了{$msgshowrvrc},金钱增加{$db_dtjhmoney}.希望您再接再厉。<a href=topic.php?fid=$fid&tid=$tid target=_blank>点击查看帖子</a>|0|\n";
			writeover("data/$msgpath/{$detail[2]}1.php",$new,"ab");
			!file_exists("data/digest/digest{$fid}.php") && $digsetfb="<?die?>\n$fid\n";
			writeover("data/digest/digest{$fid}.php","$digsetfb$tid|","ab");
		}
	} 
	elseif ($_POST['action']=="undigest")
	{ 
		if ($T_P_C[1]==2 || $T_P_C[1]==3){
			$T_P_C[1]=$T_P_C[1]-2;
			dtchange($detail[2],-$db_dtjhrvrc,"0",-$db_dtjhmoney);
			if(file_exists("data/digest/digest{$fid}.php")){
				$digestarray=openfile("data/digest/digest{$fid}.php");
				$digestdetail=explode("|",$digestarray[2]);
				foreach($digestdetail as $key =>$value){
					if($tid==$value)
						unset($digestdetail[$key]);
				}
				$digestarray[2]=implode("|",$digestdetail);
				$digestdb=implode("",$digestarray);
				writeover("data/digest/digest{$fid}.php",$digestdb);
			}
		}
	}
	elseif ($_POST['action']=="edit"){
		$T_P_C[2]="$title1~$title2~$title3~$title4~$title5~$title6~";
	}
	$detail[1]=implode(",",$T_P_C);
	$articledb[0]=implode("|",$detail);
	$articledb=implode("",$articledb);
	writeover($atcname,$articledb);
}
//////////////////////////////////////////////////////////////////
if (file_exists($filename) && $_POST['action']=="del")
{
	list($toparray,$detail)=searchtop($filename,$db_linesize,$tid);
	if(is_array($detail)){
		$writedb=implode("",$toparray).str_pad(' ',$db_linesize)."\n";
		writeselect($filename,$writedb,1,$db_linesize);
		unset($toparray);
	}
	else{
		list($fp,$writearray,$detail,$fastwrite)=readsearch($filename,$tid,$db_linesize);
		write_del($fp,$writearray,$fastwrite);
		fclose($fp);
	}
	if (file_exists("$dbpath/$db_recycle/list.php")){
		$nextname=shownextname($db_recycle);
	}
	else
	{
		$headfb=str_pad('<?die;?>',$db_linesize)."\n";
		$nextname="1";
	}
	if (file_exists($atcname) && $fid != $db_recycle && $db_recycle!=0)
	{
		$topic_dbname=$detail[5];
		$detail[1]=$fid;
		$detail[5]=$nextname;
		$newlistdb=str_pad(trim(implode("|",$detail)),$db_linesize)."\n";
		writeover("$dbpath/$db_recycle/list.php",$headfb.$newlistdb,'ab');
		Move_topic($fid,$tid,$db_recycle,$nextname);
		//@copy($atcname, "$dbpath/$db_recycle/$nextname.php");
		@copy("$dbpath/$fid/{$tid}vote.php","$dbpath/$db_recycle/{$nextname}vote.php");//投票
		$tpcdb=gets($atcname,200);
		$tpcdetail=explode("|",$tpcdb);
		$topic_author=$tpcdetail[2];
		$topic_name=$tpcdetail[5];
		//修改status.php里的信息
		$statusdetail=Ex_plode("|",readover("$dbpath/$db_recycle/status.php"),9);
		$statustime=date($db_tformat,$timestamp);
		$statusdetail[6]<0 ? $statusdetail[6]=$detail[6]+1 : $statusdetail[6]+=$detail[6]+1;
		$statusdetail[7]<0 ? $statusdetail[7]=1 : $statusdetail[7]++;
		$writestatus="<?die;?>|$tpcdetail[5]|$tpcdetail[2]|$statustime|$db_recycle&tid=$nextname|$timestamp|$statusdetail[6]|$statusdetail[7]||";
		writeover("$dbpath/$db_recycle/status.php",$writestatus);
	}
	$msg_delrvrc=floor($db_dtdelrvrc/10);
	dtchange($topic_author,-$db_dtdelrvrc,"-1",-$db_dtdelmoney);
	//$replycount=$detail[6]+1;
	@unlink("$dbpath/$fid/$topic_dbname.php");
	if (file_exists("$dbpath/$fid/{$topic_dbname}vote.php"))
		@unlink("$dbpath/$fid/{$topic_dbname}vote.php");
	//修改status.php里的信息
	list($lasttid,$lasttitle,$lastauthor,$laststamp,$lasttime)=getnewstatus($fid);
	$statusdetail=explode("|",readover("$dbpath/$fid/status.php"));
	$statusdetail[0]="<?die;?>";
	$statusdetail[1]=$lasttitle;$statusdetail[2]=$lastauthor;$statusdetail[3]=$lasttime;
	$statusdetail[4]=$lasttid;$statusdetail[5]=$laststamp;
	$statusdetail[6]<=$detail[6]+1 ? $statusdetail[6]=0 : $statusdetail[6]-=$detail[6]+1;
	$statusdetail[7]<=0 ? $statusdetail[7]=0 : $statusdetail[7]--;
	$writestatus=implode("|",$statusdetail);
	writeover("$dbpath/$fid/status.php",$writestatus);
	//修改论坛信息
	$bbstpc--;
	$bbsatc-=$detail[6]+1;
	$writebbsatcdb="<?die;?>|$bbstpc|$bbsatc|$bbstoday|$bbsyestoday|$bbsmost|$bbspostcontrol|$bbsbirthcontrol|$bbsstar|$bbsrich|$bbslucher|$bbsbirthman|";
	writeover("data/bbsatc.php",$writebbsatcdb);

	
	$newlog_forum="<?die;?>|删除贴子|$fid||$topic_name|$topic_author|无聊的帖子|-$msg_delrvrc|-$db_dtdelmoney|$timestamp|$htfid|$onlineip|\n";
	writeover("data/log_forum.php",$newlog_forum,"ab");/*最新的在最下面.无负载性*/

	refreshto("forum.php?fid=$fid","管理程式已经成功执行了操作");
}
if ($_POST['step'] && file_exists($filename))
{
	list($writetop,$edittop)=searchtop($filename,$db_linesize,$tid);//未锁定写入
	if(is_array($edittop)){
		if($_POST['action']=="pushtopic")
		{
			$getnewitem=implode("|",$edittop);
			array_unshift($writetop,$getnewitem);
			$writedb=implode("",$writetop);
			writeselect($filename,$writedb,1,$db_linesize);
		}
		elseif($_POST['action']=="unheadtopic")
		{
			$writedb=implode("",$writetop).str_pad(' ',$db_linesize)."\n";
			writeselect($filename,$writedb,1,$db_linesize);
			$edittop[8]=0;
			$getnewitem=implode("|",$edittop);
			writeover($filename,$getnewitem,'ab');
		}
	}
	else{
		list($fp,$writearray,$detail,$fastwrite)=readsearch($filename,$tid,$db_linesize);
		if ($detail[5]==$tid)
		{
			if($_POST['action']=="pushtopic")
			{
				$getnewitem=implode("|",$detail);
				array_push($writearray,$getnewitem);
				$writedb=implode("",$writearray);
				fputs($fp,$writedb);
				fclose($fp);
			}
			elseif($_POST['action']=="headtopic")
			{
				
				$detail[8]=3;
				$getnewitem=implode("|",$detail);
				if(count($writetop)>=$db_topnum){

					showmsg('置顶贴个数已达到指定个数，不能再增加');
				}
				array_unshift($writetop,$getnewitem);
				$writetopdb=implode("",$writetop);
				//echo$writetopdb;exit;

				write_del($fp,$writearray,$fastwrite);

				fseek($fp,$db_linesize+1,SEEK_SET);
				fputs($fp,$writetopdb);
				fclose($fp);
			}
			else
				fclose($fp);
		}
	}
}
refreshto("forum.php?fid=$fid","管理帖子成功");

?>