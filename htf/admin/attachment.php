<?php

!function_exists('adminmsg') && exit('Forbidden');

@set_time_limit(0);
$basename="admin.php?adminjob=attachment";
if(empty($step))
{
	require './require/numofpage.php';
	$upload_path=opendir("$attachpath/");
	while(false!==($upload_file=readdir($upload_path)))
	{
		if(($upload_file!=".") && ($upload_file!="..") && ($upload_file!="")) 
		{
			$filezie=filesize("$attachpath/$upload_file");
			$array[$upload_file]=$filezie;
			$Asize+=$filezie;
		}
	}
	closedir($upload_path);
	@arsort($array);//����������������
	$upload_array=@array_keys($array);
	$Asize=ceil($Asize/(1024*1024));
	$count=count($upload_array);/*��������*/
	if($showbad=='Y')$db_perpage=$count;
	if (!$page) $page=1;
	if ($count%$db_perpage==0) 
		$numofpage=floor($count/$db_perpage);
	else 
		$numofpage=floor($count/$db_perpage)+1;
	if ($page>$numofpage)	
		$page=$numofpage;
	$pagemin=min(($page-1)*$db_perpage , $count-1);  
	$pagemax=min($pagemin+$db_perpage-1, $count-1);
	$fenye=numofpage($count,$page,$numofpage,"$basename&");
	if($count!=0){
		for($i=$pagemin;$i<=$pagemax;$i++)
		{
			$detail=explode("_",$upload_array[$i]);
			$FID=$detail[0];$TID=$detail[1];
			unset($filearray);/*ѭ������.һ��Ҫunset*/
			$file_db=readover("$dbpath/$FID/$TID.php");
			if(strpos($file_db,$upload_array[$i])!==false){
				if($showbad=='Y') continue;
				$ifright="������Ч";
				$where="topic.php?fid=$FID&tid=$TID";
			}else{
				$ifright="<font color=red>������Ч</font>";
				$where="forum.php?fid=$FID";
			}
			$filezie=$array[$upload_array[$i]];
			$filezie=ceil($filezie/1024);
			$admin_atc.="<tr bgcolor=$b><td><a href=$attachpath/$upload_array[$i]>$upload_array[$i]</a></td><td>$filezie (k)</td><td><a href=$where>�鿴��������</a></td><td>$ifright</td><td><input type='checkbox' name='aidarray[]' value=$upload_array[$i]></td></tr>";
		}
	}
	eval("dooutput(\"".gettmp('attachment')."\");");
}
elseif($_POST['step']==2)
{
	$count=count($aidarray);
	for($i=0;$i<$count;$i++)
	{
		if(@unlink("$attachpath/$aidarray[$i]")){
			$delnum++;
			$delname.="{$aidarray[$i]}<br>";
		}
	}
	$msg="��ɾ��{$count}��<br>��ɾ��:<br>$delname";
	adminmsg($msg);
}
?>