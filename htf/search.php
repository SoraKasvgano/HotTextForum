<?php

$ifecho = array
(
	'sc_nokey1' =>'<!--', 'sc_nokey2'  =>'<-->',
	'sc_key1'   =>'<!--', 'sc_key2'    =>'<-->',
	'sc_more1'  =>'<!--', 'sc_more2'   =>'-->',
	'sc_nomore1'=>'<!--', 'sc_nomore2' =>'<-->',
	'sc_page1'  =>'<!--', 'sc_page2'   =>'-->'
);
require("global.php");
Cookie('lastpage','',0);Cookie('ifmore','',0);
@set_time_limit(0);
$secondname="搜索程式";
$secondurl="search.php";

/**
*用户组权限判断
*/
$gp_ifsearch==0 && showmsg("权限错误:你所属的用户组不能使用搜索功能");

if(empty($forumcount))
	list($forumcount,$forumarray)=getforumdb();
$searchedforum='';
for ($i=0; $i<$forumcount; $i++){
	$detail=explode("|",$forumarray[$i]);
	if ($detail[1]!="category"){
		list($allowvisit,$allowpost,$allowdownload,$allowupload)=explode("~",$detail[16]);
		if(!$allowvisit || strpos($allowvisit,",$groupid,")!==false || $htfid==$manager){
			$detail[2]=str_replace('~','',$detail[2]);
			$sch_foruminfo.= "<OPTION value='$detail[4]~$detail[2]'>";
			if ($detail[5]!=0)
				$sch_foruminfo.='|-';
			$sch_foruminfo.="$detail[2]</OPTION>";
			if ($keyword){
				if (empty($seekfid) || $seekfid==0){
					$searchedforum[]=$detail[4];
					$fid=$detail[4];
					$forumname[$fid]=$detail[2];
				}elseif(empty($searchedforum)){
					$detail=explode("~",$seekfid);
					$searchedforum[]=$detail[0];
					$seekfid=$detail[0];
					$forumname[$seekfid]=$detail[1];
				}
			}
		}
	}
}
if (empty($keyword)){
	$ifecho[sc_nokey1]="";$ifecho[sc_nokey2]="";

	!$sch_foruminfo && showmsg('你所属的用户组没有浏览版块的权限');

	$sch_foruminfo="<OPTION value='0'>全部版块</OPTION>".$sch_foruminfo;
	require "./header.php";
	$msg_guide=headguide($secondname);
	include PrintEot('search');footer();
}
if(strlen($keyword)<=2){

	showmsg("搜索帖子程式!发生错误,原因:<br><br>搜索内容长度要大于2<br>");
}
if($gp_searchtime!=0 && !isset($step)){
	$seachtime=$timestamp-$lasttime;
	if ($keyword!= $_COOKIE['lastseach'] && $seachtime<$gp_searchtime){
	
		showmsg("对不起{$gp_searchtime}秒内只能进行一次搜索");
	}
	Cookie('lastseach',$keyword);
	Cookie('lasttime',$timestamp);
}
$ifecho[sc_key1]="";$ifecho[sc_key2]="";
$keyword=safeconvert($keyword);
$keyword=rawurldecode($keyword);
$cachefile=rawurldecode($cachefile);




$fcount=count($searchedforum);
if (empty($method))
	$method="or";
if (empty($page))
	$page=1;
$maxresult=$page*$db_perpage;
if(!$cachefile)$cachefile=$keyword.$sch_time.$sch_area.$method.$seekfid; //缓冲文件目录。
//echo$page.'*<1>*'.$lastpage;echo'<br>';
if(!file_exists("data/cache/$cachefile.txt")||$page>$lastpage||$step)
{
	//echo $page;echo '<br>';echo $lastpage;echo '<br>';echo $step;
	if(!$step){
		deldir("data/cache/");
		/**
		* 搜索日志
		*/
		writeover("data/log_search.php","<?die;?>|$htfid|$onlineip|$timestamp|$keyword|$sch_area|\n","ab");
	}
	if(!$rstcount)$rstcount=0;if(!$more)$more=0;if(!$start)$start=0;if(!$step)$step=0;if(!$startnum)$startnum=0;
	$schednum=0;//$schednum：控制每次搜索的贴子数，$gp_schpernum：每次跳转搜索的帖子数   $start:上次搜索的版块ID
	$keywordarray=explode("│",$keyword);
	$keycount=count($keywordarray);
	if($sch_area=="C"){
		include'./require/schall.php';
	}elseif($sch_area=="A"){
		for ( $j = 0; $j < $keycount; $j++){
			$keywordarray[$j].="|";/*搜索作者准确匹配*/
		}
		include'./require/schpart.php';
	}else{
		include'./require/schpart.php';
	}
	if (!file_exists("data/cache/$cachefile.txt")){
		showmsg("没 有 您 要 查 找 的 内 容 <br><br><br><a href='search.php'>继 续 搜 索</a></li></ul>");
	}
}
/************读取缓冲文件********************************/
$resultdb=readover("data/cache/$cachefile.txt");
$rstarray=explode("<--->\n",$resultdb);
$rstcount=count($rstarray);
while(!$rstarray[$rstcount-1])$rstcount--;
if($rstcount>=$maxresult)$more=1;
$maxresult=min($maxresult,$rstcount);$nextpage=$page+1;
$prepage=$page-1;
if($page>1)
{$ifecho[sc_page1]="";$ifecho[sc_page2]="";}
if($page>$lastpage){
	$lastpage=$page;
}
if($more==1){
	$ifmore=$more;
}

if($ifmore){
	$ifecho[sc_more1]="";$ifecho[sc_more2]="";
}
else{
	$ifecho[sc_nomore1]="";$ifecho[sc_nomore2]="";
}
require "./header.php";
for($i=($page-1)*$db_perpage; $i<$maxresult; $i++){
	$resulttrd=explode("|",$rstarray[$i]);
	$atcarray=openfile("$dbpath/$resulttrd[1]/$resulttrd[2].php");
	$tpvdetail=explode("|",$atcarray[0]);
	list($rd_hit,$rd_islock,$null)=explode(",",$tpvdetail[1]);
	$repley=count($atcarray)-1;
	$lastinfo=explode("|",$atcarray[$repley]);
	$lastposttime=date($db_tformat,$lastinfo[4]);
	$tpvdetail[5]=str_replace('%a%','',$tpvdetail[5]);
	$tpvdetail[5]="<a target=_blank href=topic.php?fid=$resulttrd[1]&tid=$resulttrd[2]&searchword=".rawurlencode($keyword).">$tpvdetail[5]</a>";
	$lastinfo[2]="<a target=_blank href=usercp.php?action=show&username=".rawurlencode($lastinfo[2]).">$lastinfo[2]</a>";
	$rawurlname=rawurlencode($tpvdetail[2]);
	$searchresult.="
	<tr>
	<td bgcolor=$forumcolorone align=center>$resulttrd[0]</td>
	<td bgcolor=$forumcolorone align=center>&nbsp;$tpvdetail[5]</td>
	<td bgcolor=$forumcolorone align=center><a target=_blank href='usercp.php?action=show&username=$rawurlname'>$tpvdetail[2]</a></td>
	<td bgcolor=$forumcolorone align=center>$repley</td>
	<td bgcolor=$forumcolorone align=center>$rd_hit</td>
	<td bgcolor=$forumcolorone align=center>
	<table bgcolor=$forumcolorone>
	<tr><td align=center class=smalltext>
	<a href='topic.php?fid=$resulttrd[1]&tid=$resulttrd[2]&page=lastpost&searchword=$keyword'>$lastposttime</a><br>von: $lastinfo[2]
	</td>
	</tr></table>
	</td>
	";
}
$msg_guide=headguide($secondname,$secondurl,"搜索\"$keyword\"");
$keyword=rawurlencode($keyword);
include PrintEot('search');footer();
function deldir($path){
	$deldb=opendir("$path/");
	while (false!==($delfile=readdir($deldb))){
		if (($delfile!=".") && ($delfile!="..") && ($delfile!="")&&strpos($delfile,".php")){
			@unlink("$path/$delfile");
		}
	}
	closedir($deldb);
}
?>