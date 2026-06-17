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
include_once './require/security_integration.php'; // Security: CSRF + Rate Limiting
Cookie('lastpage','',0);Cookie('ifmore','',0);
@set_time_limit(0);
$secondname="������ʽ";
$secondurl="search.php";

/**
*�û���Ȩ���ж�
*/
$gp_ifsearch==0 && showmsg("Ȩ�޴���:���������û��鲻��ʹ����������");

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

	!$sch_foruminfo && showmsg('���������û���û���������Ȩ��');

	$sch_foruminfo="<OPTION value='0'>ȫ�����</OPTION>".$sch_foruminfo;
	require "./header.php";
	$msg_guide=headguide($secondname);
	include PrintEot('search');footer();
}
if(strlen($keyword)<=2){

	showmsg("�������ӳ�ʽ!��������,ԭ��:<br><br>�������ݳ���Ҫ����2<br>");
}

// Security: Rate Limiting for search
apply_rate_limit('search', 20, 300); // 20 searches per 5 minutes

if($gp_searchtime!=0 && !isset($step)){
	$seachtime=$timestamp-$lasttime;
	if ($keyword!= $_COOKIE['lastseach'] && $seachtime<$gp_searchtime){
	
		showmsg("�Բ���{$gp_searchtime}����ֻ�ܽ���һ������");
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
if(!$cachefile)$cachefile=$keyword.$sch_time.$sch_area.$method.$seekfid; //�����ļ�Ŀ¼��
//echo$page.'*<1>*'.$lastpage;echo'<br>';
if(!file_exists("data/cache/$cachefile.txt")||$page>$lastpage||$step)
{
	//echo $page;echo '<br>';echo $lastpage;echo '<br>';echo $step;
	if(!$step){
		deldir("data/cache/");
		/**
		* ������־
		*/
		writeover("data/log_search.php","<?die;?>|$htfid|$onlineip|$timestamp|$keyword|$sch_area|\n","ab");
	}
	if(!$rstcount)$rstcount=0;if(!$more)$more=0;if(!$start)$start=0;if(!$step)$step=0;if(!$startnum)$startnum=0;
	$schednum=0;//$schednum������ÿ����������������$gp_schpernum��ÿ����ת������������   $start:�ϴ������İ��ID
	$keywordarray=explode("��",$keyword);
	$keycount=count($keywordarray);
	if($sch_area=="C"){
		include'./require/schall.php';
	}elseif($sch_area=="A"){
		for ( $j = 0; $j < $keycount; $j++){
			$keywordarray[$j].="|";/*��������׼ȷƥ��*/
		}
		include'./require/schpart.php';
	}else{
		include'./require/schpart.php';
	}
	if (!file_exists("data/cache/$cachefile.txt")){
		showmsg("û �� �� Ҫ �� �� �� �� �� <br><br><br><a href='search.php'>�� �� �� ��</a></li></ul>");
	}
}
/************��ȡ�����ļ�********************************/
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
$msg_guide=headguide($secondname,$secondurl,"����\"$keyword\"");
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