<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=share";
$filename="data/sharebbs.php";
if (file_exists($filename)) 
{
	$shareforumdata=openfile($filename);
	$count=count($shareforumdata);
	updatacache_s();

}
if (empty($action))
{
	$shareforumselect="<option value=''>请选择...</option>";
	for($i=0; $i<$count; $i++) 
	{
		$detail=explode("|",$shareforumdata[$i]);
		$detail[1]=trim($detail[1]);
		if($detail[1]==$shareid)
		{
			$sharename=$detail[1];
			$shareurl=$detail[2];
			$sharetitle=$detail[3];
			$sharelogo=$detail[4];
			$shareforumselect.="<option value=\"$detail[1]\" selected>$detail[1]</option>";
		}
		else
			$shareforumselect.="<option value=\"$detail[1]\">$detail[1]</option>";
	}
	$shareforumselect.="</select>";
	eval("dooutput(\"".gettmp('admin_link')."\");");
}
elseif ($action=="create") 
{  
	$newstring="";
	//print "<tr><td bgcolor='#e8f4ff' valign=middle align=center colspan=2><b>新建友情链接</b></td></tr><tr><td bgcolor='#f2f8ff' colspan=2>";
	$name=str_replace("|","",$name);
	$name=trim($name);
	$share_url=str_replace("|","",$share_url);
	$title=str_replace("|","",$title);
	$logo=str_replace("|","",$logo);
	$newstring="<?php die();?>|$name|$share_url|$title|$logo|\n";
	if (!empty($name) && !empty($share_url) && !empty($title)) 
	{
		writeover($filename,$newstring,"ab");
	}
	else
		adminmsg( "<br><br>友情链接列表更新失败，请检查所提交数据是否完整<br>");
}
elseif ($action=="modify")
{
	//print "<tr><td bgcolor='#e8f4ff' valign=middle colspan=2><b>修改/删除友情链接</b></td></tr><tr><td bgcolor='#f2f8ff' valign=middle colspan=2>";
	$name=str_replace("|","",$name);
	$share_url=str_replace("|","",$share_url);
	$title=str_replace("|","",$title);
	$logo=str_replace("|","",$logo);
	$new="";
	for ($i=0; $i<$count; $i++)
	{
		$detail=explode("|",$shareforumdata[$i]);
		if ($target==trim($detail[1]))
		{
			if ($job=="modify") 
			{
				//echo "$target";
				if (!empty($name)) 
					$detail[1]=trim($name);
				if (!empty($share_url))
					$detail[2]=$share_url;
				if (!empty($title)) 
					$detail[3]=$title;
				$detail[4]=$logo."|";
				$new.=implode("|",$detail);
			}
			if ($job=="delete")$new.="";
		}
		else 
			$new.=$shareforumdata[$i];
	}
	writeover($filename,$new);
}
updatacache_s();
adminmsg("友情链接管理完成,程序自动跳回管理页面,请等待.....",1,2);
function updatacache_s(){
	$filename="data/sharebbs.php";
	$cachefile="data/indexcache.php";
	@include "./$cachefile";
	if(empty($index_link) || !file_exists($cachefile) || @filemtime($filename)>@filemtime($cachefile)) {
		$sharebbsdb=openfile('data/sharebbs.php');
		$count=$sharebbsdb[0]==''?0:count($sharebbsdb);
		$index_link='';
		for ($i=0; $i<$count; $i++){
			$sharearray= explode("|" , $sharebbsdb[$i]);
			if ($sharearray[4]=="") 
				$linknologo.="&nbsp;<a href='$sharearray[2]' target=_blank title='$sharearray[3]'>[$sharearray[1]]</a>&nbsp;";
			else
				$linkhavelogo.=" <a href='$sharearray[2]' target=_blank><img src=$sharearray[4] width=88 height=31 alt='$sharearray[3]' border=0></a>";
		}
		$index_link="$linknologo<br>$linkhavelogo";
		writeover($cachefile,"<?php\n\$notice=\"$notice\";\n\$index_link=\"$index_link\";\n?>");
	}
}
?>