<?php

!function_exists('adminmsg') && exit('Forbidden');

@set_time_limit(0);
$ifecho=array(
	"bakout1"	   => "<!--","bakout2"        => " -->",
	"bakin1"	   => "<!--","bakin2"      => " -->",
	);
if(!$bakjob)$bakjob='bakout';
if($bakjob=='bakout')
{
	$basename="admin.php?adminjob=bak&bakjob=bakout";
	if(!$action)
	{
		$ifecho[bakout1]="";$ifecho[bakout2]="";
		substr($sysos, 0, 3) == 'WIN' ? $bakbit=1024 :$bakbit=4096;
		$title="论坛数据备份";$action='bakatc';
		$bakpath="bak/".time();
		@mkdir('bak',0777);
		eval("dooutput(\"".gettmp('admin_bak')."\");");
	}
	elseif($action=='user')
	{
		@mkdir("$bakpath",0777);
		$type="用户与设置";$baktime=date("Y-m-d H:i",time());
		if(!$step){
			if($baktype==forumset||$baktype==alldata)
			{
				baksysinfo("$bakpath","data/forumdata.php","<--forumdb-->");
				baksysinfo("$bakpath","data/admin.php","<--admindb-->");
				baksysinfo("$bakpath","data/cache.php","<--cachedb-->");
				baksysinfo("$bakpath","data/bbsnew.php","<--bbsnewdb-->");
				baksysinfo("$bakpath","data/bbsatc.php","<--bbsatcdb-->");
				baksysinfo("$bakpath","data/sharebbs.php","<--sharebbsdb-->");
				baksysinfo("$bakpath","data/config.php","<--configdb-->");
				baksysinfo("$bakpath","data/userarray.php","<--userarraydb-->");
			}
		}
		if($baktype==data||$baktype==alldata)
			bakuser("$bakpath");
	}
	elseif($action=='process')
	{
		$type="帖子";$baktime=date("Y-m-d H:i",time());
		@mkdir("$bakpath",0777);
		bakatc("$bakpath",$persize);//可以在这里控制每次备份的帖子个数
	}
	adminmsg("备 份 成 功");
}
if($bakjob=='bakin')
{
	$basename="admin.php?adminjob=bak&bakjob=bakin";
	if(!is_dir("bak"))
		mkdir("bak",0777);
	$bakdir=opendir("bak/");
	while (false!==($bakfile=readdir($bakdir)))
	{
		if (($bakfile!=".") && ($bakfile!="..") && ($bakfile!=""))
		{
			unset($baktype);
			if(file_exists("bak/$bakfile/user1.php")) 
			{
				$baktype='用户';$baktime=date("Y-m-d H:i",filemtime("bak/$bakfile"));
			}
			if(file_exists("bak/$bakfile/system.php")) 
			{
				isset($baktype) ? $baktype.='与设置':$baktype.='设置';
				$baktime=date("Y-m-d H:i",filemtime("bak/$bakfile"));
			}
			if(file_exists("bak/$bakfile/atc1.php"))
			{
				$baktype='帖子数据';
				$baktime=date("Y-m-d H:i",filemtime("bak/$bakfile"));
			}
			$bakshow.="<tr>
					 <td bgcolor=$b align=center><input type='checkbox' name='delid[$i]' value=$bakfile></td>
					 <td bgcolor=$b align=center>$bakfile</td>
					 <td bgcolor=$b align=center>$htf_version</td>
					 <td bgcolor=$b align=center>$baktype</td>
					 <td bgcolor=$b align=center>$baktime</td>
					 </tr>";
		}
	}
	closedir($bakdir);
	$ifecho[bakin1]="";$ifecho[bakin2]="";
	if(!$action)
	{
		eval("dooutput(\"".gettmp('admin_bak')."\");");
	}
	if(is_dir("$bakpath/")&&$action!=del)
	{
		if(file_exists("$bakpath/system.php")) 
			$bakinfo=gets("$bakpath/system.php",1024);
		elseif(file_exists("$bakpath/user1.php"))
			$bakinfo=gets("$bakpath/system.php",1024);
		elseif(file_exists("$bakpath/atc1.php")) 
			$bakinfo=gets("$bakpath/system.php",1024);
		else 
			adminmsg("导入文件不存在...<br><br>");
		if(!ereg("<--htfbak-->",$bakinfo)&&$action!='bakstep')
			adminmsg("导入文件不符合 htf 备份文件格式...<br><br>");
	}
	if($action=="bakinall")
	{
		if(!$step && file_exists("$bakpath/system.php"))
		{
			if($in_setting==1)bakinsysinfo($bakpath,"data/config.php","<--configdb-->",'系统设置');
			if($in_forum==1)bakinsysinfo($bakpath,"data/forumdata.php","<--forumdb-->",'版块');
			bakinsysinfo($bakpath,"data/admin.php","<--admindb-->",'版主');
			bakinsysinfo($bakpath,"data/cache.php","<--cachedb-->",'用户组');
			bakinsysinfo($bakpath,"data/bbsnew.php","<--bbsnewdb-->",'会员数统计信息');
			bakinsysinfo($bakpath,"data/bbsatc.php","<--bbsatcdb-->",'帖子统计信息');
			if($in_share==1)bakinsysinfo($bakpath,"data/sharebbs.php","<--sharebbsdb-->",'联盟论坛');
			bakinsysinfo($bakpath,"data/userarray.php","<--userarraydb-->",'用户缓冲');
		}

		if(file_exists("$bakpath/user1.php")&& ($in_user==1 || $step)){
			$stepdb=opendir("$bakpath/");
			while (false!==($stepfile=readdir($stepdb))){
				if (($stepfile!=".") && ($stepfile!="..") && ($stepfile!="")&&strpos($stepfile,".php")&&strpos($stepfile,"user")!==false)
					$baknum++;
			}
			closedir($stepdb);
			if($baknum==0) adminmsg("无备份文件");
			if(!$step) $step=1;
			if($step<=$baknum)
				bakinuser($bakpath,$step);
			else{
				$step--;
				adminmsg("已全部导入<br><br>总共导入{$step}个备份文件");
			}
		}
		adminmsg('已导入您所选择的备份数据');

	}
	elseif($action=='bakstep')
	{
		$stepdb=opendir("$bakpath/");
		while (false!==($stepfile=readdir($stepdb)))
		{
			if (($stepfile!=".") && ($stepfile!="..") && ($stepfile!="")&&strpos($stepfile,".php"))
			{
				$baknum++;
			}
		}
		closedir($stepdb);
		if($baknum==0) adminmsg("无备份文件");
		if(!$step) $step=0;
		$step++;
		bakinatc($bakpath,$step);
		if($step<$baknum)
		{
			$url="admin.php?adminjob=bak&bakjob=bakin&action=bakstep&step=$step&bakpath=$bakpath";
			adminmsg("已导入{$step}个备份文件,程序将自动导入余下部分.....",1);
		}
		else
		{
			adminmsg("已全部导入<br><br>总共导入{$step}个备份文件");
		}
	}
	elseif($action==del)
	{
		$dcount=count($delid);
		if(!$dcount) adminmsg("没有选择要删除的备份文件");
		for($i=0;$i<$dcount;$i++)
		{
			if(file_exists("bak/$delid[$i]/"))
			{
				deldir("bak/$delid[$i]/");
				$delfname.=$delid[$i]."<br>";
			}
		}
		if(!$delfname)adminmsg('没有此备份文件,可能已删除');
		adminmsg("已删除文件：<br>$delfname");
	}
}
function bakatc($bakpath,$persize)
{
	global $htf_version,$baktime,$type,$dbpath,$step,$url,$num1start,$num2start,$db_linesize;
	if(!$step)$step=0;
	if(!$num1)$num1=0;
	if(!$num1start)$num1start=0;
	if(!$num2start)$num2start=0;
	$readsize=10000*($db_linesize+1);/*对list索引只备份前10000主题*/
	$fdb=opendir("$dbpath/");
	while (false!==($forfile=readdir($fdb)))
	{
		if($forfile!="." && $forfile!=".." && is_numeric($forfile))
		{
			$num1++;$num2=0;
			if($num1>=$num1start){
				$atcdb=opendir("$dbpath/$forfile/");
				while (false!==($tid=readdir($atcdb)))
				{
					$num2++;
					if($num2>$num2start){
						if ($tid!="." && $tid!="..")
						{
							//$atccontent=;
							//$atccontent = str_replace("\n","<--1-->",$atccontent);
							//$atccontent = str_replace("\t","",$atccontent);
							//$atccontent = str_replace("\r","",$atccontent);
							$writeatc.=$forfile."<--3-->".$tid."<--3-->".readover("$dbpath/$forfile/$tid","rb",$readsize)."<--n-->";
							$baklen=strlen($writeatc);//echo$baklen.'<br>';
							if($baklen>=$persize*1024) break;
						}
					}
				}

				closedir($atcdb);
				if($baklen>=$persize*1024)
				{
					$writeatc = str_replace("\n","<--1-->",$writeatc);
					$writeatc = str_replace("<--n-->","\n",$writeatc);
					$writeatc = str_replace("\t","",$writeatc);
					$writeatc = str_replace("\r","",$writeatc);
					$step++;
					if($step==1) $writeatc="<--htfbak-->$htf_version<--htfbak-->$type<--htfbak-->$baktime\n".$writeatc;
					writeover("{$bakpath}/atc{$step}.php",$writeatc);
					$writeatc="";
					$url="admin.php?adminjob=bak&bakjob=bakout&action=process&num1start=$num1&num2start=$num2&step=$step&bakpath=$bakpath&persize=$persize";
					adminmsg("已生成{$step}个备份文件,程序将自动备份余下部分......",1);
				}
				$num2start=0;
			}
		}
	}
	closedir($fdb);
	$step++;
	writeover("$bakpath/atc{$step}.php",$writeatc);
	adminmsg("共{$step}个备份文件,已全部备份完毕。");	
}
function bakuser($bakpath)
{
	global $baktype,$userpath,$htf_version,$type,$baktime,$url,$step,$num1start;
	$peruser=4000;
	if(!$step) $step=0;
	if(!$num1start) $num1start=0;
	$userdb=opendir("$userpath/");
	while (false!==($username=readdir($userdb)))	{
		if (($username!=".") && ($username!="..") && strpos($username,".php")!==false)//&& strpos($username,"|")===false && strpos($username,"\\")===false
		{
			$num1++;
			if($num1>$num1start)
			{
				$writeuserdb.=$username."<--user-->".readover("$userpath/$username")."<--n-->";
				if($num1>($step+1)*$peruser){$noend=Y; break;}
			}
		}
	}
	closedir($userdb);
	$step++;
	if($step==1) $writeuserdb="<--htfbak-->$htf_version<--htfbak-->$type<--htfbak-->$baktime\n".$writeuserdb;
	$atcid=floor($step/4)+1;
	$writeuserdb=str_replace("\n","<br>",$writeuserdb);
	$writeuserdb=str_replace("<--n-->","\n",$writeuserdb);
	$writeuserdb=str_replace("\r","",$writeuserdb);
	writeover("$bakpath/user{$atcid}.php",$writeuserdb,"ab");
	if($noend==Y)
	{
		$alread=$step*$peruser;
		$url="admin.php?adminjob=bak&bakjob=bakout&action=user&baktype=$baktype&step=$step&bakpath=$bakpath&num1start=$num1";
		adminmsg("已备份{$alread}个用户资料,程序将自动备份余下部分......",1);
	}
	else
		adminmsg("共{$atcid}个备份文件,已全部备份完毕。");
}
function baksysinfo($bakpath,$bakpath1,$sign)
{
	global $htf_version,$type,$baktime;
	$forumdata=readover("$bakpath1");
	$forumdata = str_replace("\n","<--1-->",$forumdata);
	$forumdata = str_replace("\t","",$forumdata);
	$forumdata = str_replace("\r","",$forumdata);
	$forumdata="$sign\n".$forumdata."\n$sign";
	if(file_exists("$bakpath/system.php"))
		$oldwritedb=readover("$bakpath/system.php");
	else
		$oldwritedb="<--htfbak-->$htf_version<--htfbak-->$type<--htfbak-->$baktime";
	$newwritedb=$oldwritedb."\n".$forumdata;
	writeover("$bakpath/system.php",$newwritedb);
}
function bakinatc($bakpath,$num)
{
	global $dbpath;
	//if(file_exists("$bakpath/atc{$num}.php"))
	//{
		$bakdb=readover("$bakpath/atc{$num}.php");
		$farray=explode("\n",$bakdb);
		$fcount=count($farray);
		if(!$farray[$fcount-1])$fcount--;
		$num==1 ? $starti=1: $starti=0;
		for($fi=$starti;$fi<$fcount;$fi++)
		{
			$ffarray=explode("<--3-->",$farray[$fi]);
			@mkdir("$dbpath/$ffarray[0]",0777);
			$writeto=str_replace("<--1-->","\n",$ffarray[2]);
			$writeto=str_replace("\r","",$writeto);/*1.6版后对 \r 是屏蔽符号.没有此行将出现不可预料的错误*/
			@writeover("$dbpath/$ffarray[0]/$ffarray[1]",$writeto);
		}
	//}
}
function bakinuser($bakpath,$step)
{
	global $userpath,$msg,$action,$step1,$url;
	if(file_exists("$bakpath/user{$step}.php"))
	{
		$bakdb=readover("$bakpath/user{$step}.php");
		$userarray=explode("\n",$bakdb);
		$fcount=count($userarray);
		if(!$userarray[$fcount-1])$fcount--;
		if(!$step1) $step1=0;
		$peruser=2000;
		$start=$step1*$peruser+1;
		$end=($step1+1)*$peruser;
		$end=min($end,$fcount);
		for($i=$start;$i<$end;$i++)
		{
			$detail=explode("<--user-->",$userarray[$i]);
			$detail[1]=str_replace("\r","",$detail[1]);/*1.6版后对 \r 是屏蔽符号.没有此行将出现不可预料的错误*/
			if(strpos($detail[0],"|")===false && strpos($detail[0],"\\")===false)
				writeover("$userpath/$detail[0]",$detail[1]);
		}
		if($end<$fcount)
		{
			$step1++;
			$already=$step1*$peruser;
			$url="admin.php?adminjob=bak&bakjob=bakin&action=$action&step1=$step1&step=$step&bakpath=$bakpath";
			adminmsg("已导入{$already}个用户资料，程序将自动导入余下部分......",1);
		}
		else{
			$step++;
			$url="admin.php?adminjob=bak&bakjob=bakin&action=$action&step=$step&bakpath=$bakpath";
			adminmsg("已导入{$step}个备份文件,程序将自动导入余下部分.....",1);
		}
	}
}
function bakinsysinfo($bakpath,$bakpath1,$sign,$info)
{
	global $msg;
	if(file_exists("$bakpath/system.php"))
	{
		$bakdb=readover("$bakpath/system.php");
		$bakarray=explode("$sign",$bakdb);
		if(!$bakarray[1])
		{
			$msg.="没有{$info}备份数据...<br><br>";		
		}
		else
		{
			$msg.="{$info}数据导入成功...<br><br>";
			$forumarray=explode("<--1-->",$bakarray[1]);
			$forumdb=implode("\n",$forumarray);
			$forumdb=trim($forumdb);
			writeover("$bakpath1",$forumdb."\n");
		}
	}
		
}
?>