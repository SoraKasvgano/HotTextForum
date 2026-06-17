<?php
/**
 * HotTextForum - Responsive Index Page
 * Mobile-First Design with HTML5 Semantic Structure
 */
$star_action='hm';
$page_start_time = microtime(true);

require './data/level.php';
require './global.php';
require './header_responsive.php';

// Page metadata
$page_title = '首页';
$page_keywords = $db_bbsname . ',论坛,社区';
$page_description = $db_bbsname . ' - 首页';

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
		$forum['info']=htmlspecialchars($detail[3], ENT_QUOTES, 'UTF-8');
		if($db_indexfmlogo==2)
		{
			if($detail[15]!='')$forum['logo']="<img class='forum-icon' src='".htmlspecialchars($detail[15], ENT_QUOTES, 'UTF-8')."' alt=''>";
		}
		elseif($db_indexfmlogo==1)
		{
			$forumlogofile="$imgpath/$stylepath/forumlogo/$forum[fid].gif";
			$forum['logo']="<img class='forum-icon' src='".htmlspecialchars($forumlogofile, ENT_QUOTES, 'UTF-8')."' alt=''>";
		}
		$alter='';
		$alter=explode("|",readover("$dbpath/$forum[fid]/status.php"));
		$alter[1]=str_replace('%a%','',$alter[1]);
		$forum['tpc']=$alter[7]+$alter[8];
		$alter[6]?$forum['atc']=$alter[6]:$forum['atc']=0;
		$access=explode("~",$detail[16]);
		if (empty($access[0])|| strpos($access[0],','.$groupid.',')!==false || $htfid==$manager){
			$forum['pic'] = $htfdb[19]<$alter[5] && ($alter[5]+172800>$timestamp) ? 'new' : 'old';
			$forum['newtitle']=htmlspecialchars($alter[1], ENT_QUOTES, 'UTF-8');
			$forum['newpost']=$alter[3]!=''?"<a href='topic.php?fid=".urlencode($alter[4])."&page=lastpost#lastatc'>".htmlspecialchars($alter[3], ENT_QUOTES, 'UTF-8')."</a><br>by: <a href='usercp.php?action=show&username=".rawurlencode($alter[2])."'>".htmlspecialchars($alter[2], ENT_QUOTES, 'UTF-8')."</a>&nbsp;":'暂无帖子';
		}else{
			$forum['pic']='lock';
			$forum['newpost']='认证论坛';
		}
		$forum['name']=htmlspecialchars($detail[2], ENT_QUOTES, 'UTF-8');
		if($detail[6]!='') $forum['name'].=" <span class='text-muted'>[已经关闭]</span>";
		if($fidadminarray[$forum['fid']])
		{
			$count=count($fidadminarray[$forum['fid']]);
			for ($j=0; $j<$count; $j++)
			{
				if ($j==4) {$forum['admin'].='...'; break;}
				$adminname=$fidadminarray[$forum['fid']][$j];
				$forum['admin'].="<a href='usercp.php?action=show&username=".rawurlencode($adminname)."'>".htmlspecialchars($adminname, ENT_QUOTES, 'UTF-8')."</a> ";
			}
		}
		$forumdb[]=$forum;
	}
	elseif($detail[1]=="category")
	{
		$forum['type']='category';
		$forum['name']=htmlspecialchars($detail[2], ENT_QUOTES, 'UTF-8');
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
$rawnewuser=rawurlencode($bbsnewer);
$rawhtfid=rawurlencode($htfid);

if($online){
	$online1=$online;
	Cookie('online1',$online);
}
if($db_indexonline&&$online1!='no')
		$doonlinefu=1;
elseif($online1=='yes')
		$doonlinefu=1;
@include_once 'data/olcache.php';
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
	ob_end_clean();
	showmsg("状态:人数已满,论坛在线会员人数已经达到最大值{$onlinecount_lmt},请稍后再试!");
}
?>

<!-- Welcome Section -->
<section class="welcome-section mb-4" aria-label="欢迎信息">
	<div class="card">
		<div class="card-body">
			<h1 class="text-center mb-3" style="font-size:2rem;font-weight:700">
				欢迎访问 <?php echo htmlspecialchars($db_bbsname, ENT_QUOTES, 'UTF-8'); ?>
			</h1>
			<?php if($groupid!='guest'): ?>
			<p class="text-center text-secondary">
				欢迎回来, <strong><?php echo htmlspecialchars($htfid, ENT_QUOTES, 'UTF-8'); ?></strong>
				<?php if(isset($lastlodate)): ?>
				| 上次登录: <?php echo htmlspecialchars($lastlodate, ENT_QUOTES, 'UTF-8'); ?>
				<?php endif; ?>
			</p>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- Statistics Section -->
<section class="stats-section mb-4" aria-label="论坛统计">
	<div class="row">
		<div class="col-12 col-md-3 mb-3">
			<div class="card text-center">
				<div class="card-body p-3">
					<div style="font-size:2rem;font-weight:700;color:#1890ff"><?php echo $bbstotleuser; ?></div>
					<div class="text-secondary">会员总数</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-3 mb-3">
			<div class="card text-center">
				<div class="card-body p-3">
					<div style="font-size:2rem;font-weight:700;color:#52c41a"><?php echo $bbstpc; ?></div>
					<div class="text-secondary">主题总数</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-3 mb-3">
			<div class="card text-center">
				<div class="card-body p-3">
					<div style="font-size:2rem;font-weight:700;color:#faad14"><?php echo $bbsatc; ?></div>
					<div class="text-secondary">回复总数</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-3 mb-3">
			<div class="card text-center">
				<div class="card-body p-3">
					<div style="font-size:2rem;font-weight:700;color:#13c2c2"><?php echo $usertotal; ?></div>
					<div class="text-secondary">当前在线</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Forum List -->
<section class="forum-section" aria-label="版块列表">
	<div class="forum-list">
		<?php foreach($forumdb as $forum): ?>
			<?php if($forum['type'] == 'category'): ?>
				<!-- Category Header -->
				<div class="card">
					<div class="card-header" style="background:#667eea;color:#fff;font-weight:700;font-size:1.125rem">
						<?php echo $forum['name']; ?>
					</div>
				</div>
			<?php else: ?>
				<!-- Forum Item -->
				<article class="forum-item" itemscope itemtype="http://schema.org/DiscussionForumPosting">
					<?php if(isset($forum['logo']) && $forum['logo']): ?>
						<?php echo $forum['logo']; ?>
					<?php else: ?>
						<div class="forum-icon" style="width:48px;height:48px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:1rem">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="#999">
								<path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
							</svg>
						</div>
					<?php endif; ?>

					<div class="forum-content">
						<h2 class="forum-title" itemprop="name">
							<a href="topic.php?fid=<?php echo urlencode($forum['fid']); ?>">
								<?php echo $forum['name']; ?>
							</a>
						</h2>

						<?php if(isset($forum['info']) && $forum['info']): ?>
						<p class="forum-desc" itemprop="description">
							<?php echo $forum['info']; ?>
						</p>
						<?php endif; ?>

						<div class="forum-stats">
							<span>主题: <strong><?php echo $forum['tpc']; ?></strong></span>
							<span>回复: <strong><?php echo $forum['atc']; ?></strong></span>
							<?php if(isset($forum['admin']) && $forum['admin']): ?>
							<span class="hide-mobile">版主: <?php echo $forum['admin']; ?></span>
							<?php endif; ?>
						</div>

						<?php if(isset($forum['newpost']) && $forum['newpost']): ?>
						<div class="forum-latest mt-2" style="font-size:0.875rem">
							<span class="text-muted">最新: </span><?php echo $forum['newpost']; ?>
						</div>
						<?php endif; ?>
					</div>

					<div class="forum-status" style="flex-shrink:0;margin-left:1rem">
						<?php if($forum['pic'] == 'new'): ?>
							<span class="badge badge-primary">NEW</span>
						<?php elseif($forum['pic'] == 'lock'): ?>
							<span class="badge badge-danger">锁定</span>
						<?php endif; ?>
					</div>
				</article>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</section>

<!-- Online Users Section (optional) -->
<?php if($doonlinefu == 1 && isset($index_whosonline)): ?>
<section class="online-section mt-4" aria-label="在线用户">
	<div class="card">
		<div class="card-header">
			<strong>在线用户</strong> (共 <?php echo $usertotal; ?> 人: <?php echo $userinbbs; ?> 会员, <?php echo $guestinbbs; ?> 访客)
			<span class="text-muted" style="font-size:0.875rem">
				| 最高在线: <?php echo $bbsol; ?> 人 (<?php echo htmlspecialchars($mostinbbstime, ENT_QUOTES, 'UTF-8'); ?>)
			</span>
		</div>
		<div class="card-body">
			<table style="width:100%">
				<tr>
					<?php echo $index_whosonline; ?>
				</tr>
			</table>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Today's Posts (optional) -->
<?php echo $ifecho['id_pt1']; ?>
<section class="today-posts mt-4" aria-label="今日发帖">
	<div class="card">
		<div class="card-header">
			<strong>今日统计</strong>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-6 col-md-3 text-center mb-3">
					<div style="font-size:1.5rem;font-weight:700;color:#52c41a"><?php echo $bbstoday; ?></div>
					<div class="text-secondary">今日发帖</div>
				</div>
				<div class="col-6 col-md-3 text-center mb-3">
					<div style="font-size:1.5rem;font-weight:700;color:#1890ff"><?php echo $bbsyestoday; ?></div>
					<div class="text-secondary">昨日发帖</div>
				</div>
				<div class="col-6 col-md-3 text-center mb-3">
					<div style="font-size:1.5rem;font-weight:700;color:#faad14"><?php echo $bbsmost; ?></div>
					<div class="text-secondary">历史最高</div>
				</div>
				<div class="col-6 col-md-3 text-center mb-3">
					<div>最新会员: <a href="usercp.php?action=show&username=<?php echo $rawnewuser; ?>"><?php echo htmlspecialchars($bbsnewer, ENT_QUOTES, 'UTF-8'); ?></a></div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php echo $ifecho['id_pt2']; ?>

<?php
// Show statistics in footer
$show_stats = true;

require './footer_responsive.php';

// Forum permission check function
function forumpermission($user,$type,$forumid){
	global $fidadminarray,$groupid;
	$check=0;
	if ($type!='hidden')$check=1;
	elseif($user<>'' &&(($fidadminarray[$forumid] && in_array($user,$fidadminarray[$forumid])) || $groupid=='manager'))$check=1;
	return $check;
}

// Online users display function
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
			if($onlinedb[4]) $inread='(阅读)';
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
					$adminonly="姓名:$onlinedb[8]\n";
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
			$onlinedb[0] = htmlspecialchars($onlinedb[0], ENT_QUOTES, 'UTF-8');
			$index_whosonline.="<td width=14%><img src='$imgpath/$stylepath/group/$img.gif' style='vertical-align:middle'> <a href='usercp.php?action=show&username=".rawurlencode($onlinedb[0])."' title='".htmlspecialchars($onlineinfo, ENT_QUOTES, 'UTF-8')."'>$onlinedb[0]</a></td>";
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
				if($guestdb[3]) $inread='(阅读)';
				if($admincheck===1){
					$ipinfo="I P : {$guestdb[0]}\n";
				}
				$onlineinfo="{$ipinfo}论坛: $guestdb[4]{$inread}\n时间: {$guestdb[5]}";
				$flag++;
				if($flag%7===0)
					$index_whosonline.='</tr><tr>';
				$index_whosonline.="<td width=14%><img src='$imgpath/$stylepath/group/6.gif' style='vertical-align:middle'> <span title='".htmlspecialchars($onlineinfo, ENT_QUOTES, 'UTF-8')."'>guest</span></td>";
			}
		}
		unset($guestarray);
	}
	return $index_whosonline;
}
?>
