<?php

!function_exists('adminmsg') && exit('Forbidden');

require './require/numofpage.php';
if($goto=='log')
{
	$basename="admin.php?adminjob=record&goto=log";/*在adminmsg中有用到此变量*/
	$bbsrecordfile="data/admin_record.php";
}elseif($goto=='forum'){
	$basename="admin.php?adminjob=record&goto=forum";
	$bbsrecordfile="data/log_forum.php";
	$forumarray=openfile('data/forumdata.php');
	$forumcount=count($forumarray);
	for ($i=0; $i<$forumcount; $i++) 
	{
		$detail=explode("|", trim($forumarray[$i]));
		if($detail[1]=='category') continue;
		$whereadmin[$detail[4]]=$detail[2];
	}
}elseif($goto=='search'){
	$basename="admin.php?adminjob=record&goto=search";/*在adminmsg中有用到此变量*/
	$bbsrecordfile="data/log_search.php";
}
$bbslogfiledata=openfile($bbsrecordfile);
$bbslogfiledata=array_reverse($bbslogfiledata);
$count=count($bbslogfiledata);
if($del=='Y'){
	if($count>100){
		$output=array_slice($bbslogfiledata,0,100);
		$output=array_reverse($output);
		$output=implode("",$output);
		writeover($bbsrecordfile,$output);
		adminmsg("成功删除多余的管理日志");
	}else{
		adminmsg("管理日志少于100不允许删除!!");
	}
}
if (!$page) $page=1;
if ($count%$db_perpage==0) 
	$numofpage=floor($count/$db_perpage);
else 
	$numofpage=floor($count/$db_perpage)+1;
if ($page>$numofpage)	
	$page=$numofpage;
$pagemin=min(($page-1)*$db_perpage , $count-1);  
$pagemax=min($pagemin+$db_perpage-1, $count-1);
if($goto=='log')
{
	$fenye=numofpage($count,$page,$numofpage,"admin.php?adminjob=record&goto=log&");
	for($i=$pagemin; $i<=$pagemax; $i++)
	{
	  $detail=explode("|",$bbslogfiledata[$i]);
	  $htfdate=date("Y-m-d h:m",$detail[5]);
	  $adlogfor.="<tr bgcolor=$b><td>$detail[1]</td><td>$detail[2]</td><td>$detail[3]</td><td>$detail[4]</td><td>$htfdate</td></tr>";
	}
	eval("dooutput(\"".gettmp('admin_record')."\");");
}elseif($goto=='forum'){
	$fenye=numofpage($count,$page,$numofpage,"admin.php?adminjob=record&goto=forum&");
	for($i=$pagemin; $i<=$pagemax; $i++)
	{
		$detail=explode("|",$bbslogfiledata[$i]);
		$htfdate=date("Y-m-d h:m",$detail[9]);
		$where_log=$whereadmin[$detail[2]];
		$adlogfor.="<tr bgcolor=$b><td>$detail[10]</td><td>$detail[5]</td><td><a href=forum.php?fid=$detail[2]>$where_log</a></td><td><font color=green>$detail[1]</font>  <font color=green>原因</font>：$detail[6] <font color=green>操作</font>：威望 $detail[7] 金钱 $detail[8]</td><td>$detail[11]</td><td>$htfdate</td></tr>";
	}
	if(!file_exists($bbsrecordfile)) unset($adlogfor);
	eval("dooutput(\"".gettmp('admin_forum')."\");");
}elseif($goto=='search'){
	$fenye=numofpage($count,$page,$numofpage,"admin.php?adminjob=record&goto=search&");
	for($i=$pagemin; $i<=$pagemax; $i++)
	{
	  $detail=explode("|",$bbslogfiledata[$i]);
	  $S_data=date("Y-m-d h:m",$detail[3]);
	  if($detail[5]=='C'){
		  $S_method='搜索内容';
	  }elseif($detail[5]=='A'){
		  $S_method='搜索作者';
	  }else{
		  $S_method='搜索标题';
	  }
	  $adlogfor.="<tr bgcolor=$b><td><a target=_blank href=usercp.php?action=show&username=".rawurlencode($detail[1]).">$detail[1]</a></td><td>$detail[2]</td><td>$S_data</td><td>$detail[4]</td><td>$S_method</td></tr>";
	}
	if(!file_exists($bbsrecordfile)) unset($adlogfor);
	eval("dooutput(\"".gettmp('log_search')."\");");
}
?>