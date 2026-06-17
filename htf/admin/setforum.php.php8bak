<?php
!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=setforum";
$forumfile="data/forumdata.php";
if (file_exists($forumfile))
{
	$forumarray=openfile($forumfile);
	$count=count($forumarray);
	updatacache_f();

	if($count==0) $count=1;
}
if (empty($_POST['action']))
{
	$cateAndforum="";
	$onlyforum="";
	for($i=0; $i<$count; $i++)
	{
		$detail=explode("|",$forumarray[$i]);
		if(empty($fid)) $fid=$detail[4];
		if ($detail[1]=="category")
		{
			if($detail[4]==$fid){
				$ifecho[cate1]='<!--';
				$ifecho[cate2]='-->';
				$ifselected="selected";
				$forumname=$detail[2];
				$forumintroduce="";
				$forumpwd="";
			}
			else{
				$ifselected="";
			}
			$cateAndforum.="<option value='$detail[4]' $ifselected>$detail[2]</option>";
		}
		else{
			if($detail[4]==$fid){
				$ifselected="selected";
				$ftype[$detail[1]]="selected";
				$fstyle[$detail[7]]="selected";
				//$ffid[$detail[5]]="selected";
				$forumname=$detail[2];
				$forumintroduce=$detail[3];
				$forumpwd=$detail[6];
				$forumlogo=$detail[15];
				$detail[14]==1 ? $checked1="checked":$checked2="checked";
				$C_detail=explode("~",$detail[8]);
				$C_detail[0]!=2 ? $jiamicheck1="checked":$jiamicheck2="checked";
				$C_detail[1]!=2 ? $hidecheck1="checked":$hidecheck2="checked";
				$C_detail[2]!=2 ? $sellcheck1="checked":$sellcheck2="checked";
				$C_detail[3]!=2 ? $TRcheck1="checked":$TRcheck2="checked";
				$permision=explode("~",$detail[16]);
				list($P_Prvrc,$P_Rrvrc,$P_Pmoney,$P_Rmoney,$P_Drvrc,$P_Dmoney)=explode("~",$detail[11]);
				$P_topicN=$detail[12];
				is_numeric($P_Prvrc)&&$P_Prvrc/=10;
				is_numeric($P_Rrvrc)&&$P_Rrvrc/=10;
				is_numeric($P_Drvrc)&&$P_Drvrc/=10;
				$announcetitle=str_replace("<","《",$detail[11]);
				$forumannounce=str_replace("<br>","\n",$detail[12]);
			}
			else{
					$ifselected="";
				}
				if ($detail[5]!=0 && $detail[5]!="")
					$writenbsp="&nbsp;&nbsp;";
				else
					$writenbsp="";
				$cateAndforum.="<option value='$detail[4]' $ifselected>{$writenbsp}|- $detail[2]</option>";
				$onlyforum.="<option value='$detail[4]'>{$writenbsp}|- $detail[2]</option>";
		}
	}
	$cateAndforum.="</select>"; $onlyforum.="</select>";
		

	$setfid_style="<select name='forumskin'><option value=''>请选择风格</option>";
	$db=opendir("style/");
	while (false!==($skinfile=readdir($db)))
	{
		if (($skinfile!=".") && ($skinfile!=".."))
		{
			$skinfile=str_replace(".php","",$skinfile);
			$setfid_style.="<option value=$skinfile $fstyle[$skinfile]>$skinfile</option>";
		}
	}
	closedir($db);
	$setfid_style.="</select>";

	$visitper=explode(",",$permision[0]);
	$postper=explode(",",$permision[1]);
	$downper=explode(",",$permision[2]);
	$upper=explode(",",$permision[3]);
	$replyper=explode(",",$permision[4]);
	foreach($visitper as $value)
		$visitchecked[$value]='checked';
	foreach($postper as $value)
		$postcheck[$value]='checked';
	foreach($downper as $value)
		$downloadcheck[$value]='checked';
	foreach($upper as $value){
		$uploadcheck[$value]='checked';
	}
	foreach($replyper as $value){
		$replycheck[$value]='checked';
	}
	$num=0;
	$allowvisit=$allowpost=$allowdownload=$allowupload=$allowreply="<table cellspacing='0' cellpadding='0' border='0' width='100%' align='center'><tr>";
	//unset($ltitle['guest']);
	//unset($ltitle['manager']);
	foreach($ltitle as $key=>$value){
		$htm_tr='';
		$num++;
		$num%5==0?$htm_tr='</tr><tr>':'';
		$allowvisit.="<td><input type='checkbox' name='ifvisit[]' value='$key' $visitchecked[$key]>$value</td>$htm_tr";
		$allowpost.="<td><input type='checkbox' name='ifpost[]' value='$key' $postcheck[$key]>$value</td>$htm_tr";
		$allowdownload.="<td><input type='checkbox' name='ifdownload[]' value='$key' $downloadcheck[$key]>$value</td>$htm_tr";
		$allowupload.="<td><input type='checkbox' name='ifupload[]' value='$key' $uploadcheck[$key]>$value</td>$htm_tr";
		$allowreply.="<td><input type='checkbox' name='ifreply[]' value='$key' $replycheck[$key]>$value</td>$htm_tr";
	}
	$allowvisit.="</tr></table>";
	$allowpost.="</tr></table>";
	$allowdownload.="</tr></table>";
	$allowupload.="</tr></table>";
	$allowreply.="</tr></table>";
	eval("dooutput(\"".gettmp('setforum')."\");");
}
elseif ($_POST['action']=="newforum") 
{
	$newforum="";
	if ($setfid_type=="category") 
	{
		//建立分类
		$cate_name=str_replace("|","",$setfid_name);
        $categoryid=time();
		$msg="成功建立分类区";
        $newforum="<?die;?>|category|$cate_name||$categoryid|\n";
	}
	else
	{
		//add forum
		$setfid_introduce=str_replace("|","",$setfid_introduce); 
		$setfid_name=str_replace("|","",$setfid_name); 
		$setfid_name=stripslashes($setfid_name);
		$setfid_introduce=stripslashes($setfid_introduce); 
		//$forumskin=str_replace("|","",$forumskin);
		
		for ($i=0; $i<$count; $i++) 
		{
			$iddetail=explode("|",$forumarray[$i]);
			if ($iddetail[1]!="category") $existsfid[]=$iddetail[4];
		}
		$existsfile=1;
		while(file_exists("$dbpath/$existsfile") && ($existsfid && in_array($existsfile,$existsfid))) $existsfile++;
		$setfid_fid=$existsfile;
		if (!file_exists("$dbpath/$setfid_fid")) 
		{
			if(mkdir("$dbpath/$setfid_fid",0777)){
				$msg="<b>成功建立版块{$setfid_fid}</b>";
				$headfb=str_pad('<?die;?>',$db_linesize)."\n";
				$tpc_top=array();
				$topspace=str_pad(' ',$db_linesize)."\n";
				$tpc_top=array_pad($tpc_top,$db_topnum,$topspace);
				$tpc_top=implode("",$tpc_top);
				writeover("$dbpath/$setfid_fid/list.php",$headfb.$tpc_top);
				$statusdata="<?die;?>||||||0|0|0||";
				writeover("$dbpath/$setfid_fid/status.php",$statusdata);
			}
			else
				$msg="<b>建立目录{$setfid_fid}失败，请手工建立目录{$setfid_fid},在版块维护创建版块索引,并设置权限为777</b>";
		}
		else
		{
			$msg="<b>成功建立版块 ID为:{$setfid_fid} 由于此目录本来已经存在请设置此目录属性为777并查验里面数据内容,删除不相干的数据</b>";
			chmod("$dbpath/$setfid_fid",0777);
		}
		$newforum="<?die;?>|$setfid_type|$setfid_name|$setfid_introduce|$setfid_fid|$setfid_fatherid||||||||1||||\n";
	}
	array_push($forumarray,$newforum);
	$forumarray=array_values($forumarray);//重建一次.防止出错
	if($setfid_fatherid!=0){
		$newforum=implode("",$forumarray);
		$count++;//版块数加1
		getnewforum();
	}else{
		$newforum=implode("",$forumarray);
	}
	writeover($forumfile,$newforum);
	updatacache_f();
	adminmsg("$msg");
}
elseif ($_POST['action']=="linemodify")
{
//改变版块或类别之间的顺序
	$msg="修改版块或类别的顺序";
	$newline="";
	for ($i=0; $i<$count; $i++)
	{
		$detail=explode("|",$forumarray[$i]);
		if ($detail[4]!=$line1) $newline.=$forumarray[$i];
		if ($detail[4]==$line2) 
		{
			for ($j=0; $j<$count; $j++) 
			{
				$detail2=explode("|",$forumarray[$j]);
				if ($detail2[4]==$line1) $newline.=$forumarray[$j];
			}
		}
	}
	writeover($forumfile,$newline);
	updatacache_f();
	adminmsg("$msg");
}
elseif ($_POST['action']=="modify") 
{
//删除/修改版块信息
	$msg="删除/修改版块信息";
	for ($i=0; $i<$count; $i++) 
	{
		$detail=explode("|",$forumarray[$i]);
		if ($detail[4]==$selectfid)
		{
			if ($job!="delete") 
			{
				if($detail[1]=="category"){
					empty($setfid_name)? $detail[2]=$forumname:$detail[2]=$setfid_name;
				}
				else
				{
					if (!empty($forumtype)||$forumtype!="") $detail[1]=$forumtype;
					if ($job=="addchildren") $detail[5]=$fid;
					if ($job=="delchildren") $detail[5]=0;
					empty($setfid_name)? $detail[2]=$forumname:$detail[2]=$setfid_name;
					if (!empty($setfid_introduce)) $detail[3]=$setfid_introduce;
					if (!empty($setfid_pwd)&&strlen($setfid_pwd)!=32)$setfid_pwd=md5($setfid_pwd);
					$forumannounce=str_replace("\n","<br>",$forumannounce);
					$forumannounce=str_replace("\r","",$forumannounce);
					$detail[6]=$setfid_pwd;$detail[7]=$forumskin;
					$detail[8]=$post.'~'.$hide.'~'.$sell.'~'.$C_Tread;
					$detail[9]='';$detail[10]='';
					$detail[11]='';
					is_numeric($S_Prvrc) && $S_Prvrc*=10;
					is_numeric($S_Rrvrc) && $S_Rrvrc*=10;
					is_numeric($S_Drvrc) && $S_Drvrc*=10;
					if($S_Prvrc!=''||$S_Rrvrc!=''||$S_Pmoney!=''||$S_Rmoney!=''||$S_Drvrc!=''||$S_Dmoney!=''){
						$detail[11]=$S_Prvrc.'~'.$S_Rrvrc.'~'.$S_Pmoney.'~'.$S_Rmoney.'~'.$S_Drvrc.'~'.$S_Dmoney;
					}
					$detail[12]=$S_topicN;
					$detail[14]=$setifchildren;$detail[15]=$setfid_logo;
					if($forumskin=="默认") $detail[7]='';
				}
				$ifvisit && $visitper=','.implode(",",$ifvisit).',';
				$ifpost && $postper=','.implode(",",$ifpost).',';
				$ifdownload && $downper=','.implode(",",$ifdownload).',';
				$ifupload && $uploadper=','.implode(",",$ifupload).',';
				$ifreply && $replyper=','.implode(",",$ifreply).',';

				$forumarray[$i]="$detail[0]|$detail[1]|$detail[2]|$detail[3]|$detail[4]|$detail[5]|$detail[6]|$detail[7]|$detail[8]|$detail[9]|$detail[10]|$detail[11]|$detail[12]|$detail[13]|$detail[14]|$detail[15]|$visitper~$postper~$downper~$uploadper~$replyper|";
				$forumarray[$i]=str_replace("\n","",$forumarray[$i])."\n";
			}
			elseif($job=='delete'){
				unset($forumarray[$i]);
				if(is_dir("$dbpath/$selectfid"))
					deldir("$dbpath/$selectfid");
			}
			break;
			//要删除目录得手动删除了
		}
	}
	if ($job=="addchildren"){
		$newforum=implode("",$forumarray);
		getnewforum();
	}else{
		$newforum=implode("",$forumarray);
	}
	writeover($forumfile,$newforum);
	$basename.="&fid=$selectfid";
	updatacache_f();
	adminmsg("$msg");
}

function getnewforum(){
	global $forumarray,$count;
	for($i=0; $i<$count; $i++) {
		$detail=explode("|",$forumarray[$i]);
		if ($detail[1]!="category"){
			if($detail[5]!=0){
				creatnewforum($detail[5],$forumarray[$i]);
			}
		}
	}
}
function creatnewforum($fid,$child){
	global $forumarray,$count,$newforum;
	for($i=0; $i<$count; $i++) {
		$detail=explode("|",$forumarray[$i]);
		if ($detail[1]!="category"){
			if($detail[4]==$fid){
				$newforum=str_replace($child,"",$newforum);
				$newforum=str_replace($forumarray[$i],$forumarray[$i].$child,$newforum);
				break;
			}
		}
	}
}
function updatacache_f(){
	global $db_recycle;
	$forumfile="data/forumdata.php";
	$cachefile="data/forumcache.php";
	if(!file_exists($cachefile) or @filemtime($forumfile)>@filemtime($cachefile)) {
		$forumarray=openfile($forumfile);
		$count=count($forumarray);
		for ($i=0; $i<$count; $i++)
		{
			$add='';
			$forumdb=explode("|",$forumarray[$i]);
			$forumdb[2]=preg_replace("/\<(.+?)\>/eis","",$forumdb[2]);//去除html标签
			if ($forumdb[1]=='category')
				$forumcache.="<option value=''>$forumdb[2]</option>";
			elseif($forumdb[1]!='hidden' && $db_recycle!=$forumdb[4]){
				if($forumdb[5]>0) $add=' &nbsp;';
				$forumcache.="<option value=$forumdb[4]>$add &nbsp;|- $forumdb[2]</option>";
			}
		}
		writeover($cachefile,"<?php\n\$forumcache=\"$forumcache\"\n?>");
	}
}
?>