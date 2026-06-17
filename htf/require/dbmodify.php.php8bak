<?php

!function_exists('readover') && exit('Forbidden');

function shownextname($id) 
{ 
	global $dbpath,$db_linesize; 
	$filecount=floor(filesize("$dbpath/$id/list.php")/($db_linesize+1));
	while (file_exists("$dbpath/$id/$filecount.php")) 
		$filecount++; 
	return $filecount;
}
function searchtop($filename,$db_linesize,$tid)
{
	global $db_topnum;
	$offset=($db_linesize+1);$readb=array();
	if($fp=fopen($filename,"rb")){
		flock($fp,LOCK_SH);
		fseek($fp,$offset,SEEK_SET);
		while(!feof($fp)){
			$topdb=fread($fp,$offset);
			if(strpos($topdb,"|")!==false){
				if(strpos($topdb,"||$tid|")!==false){
					$topdetail=explode("|",$topdb);
				}
				else
					$readb[]=$topdb;
			}else{
				break;
			}
		}
		fclose($fp);
	}
	return array($readb,$topdetail);
}
function writeselect($filename,$data,$line,$db_linesize)
{
	$offset=($db_linesize+1)*$line;
	if($fp=@fopen($filename,"rb+")){
		flock($fp,LOCK_EX);
		fseek($fp,$offset,SEEK_SET);
		fputs($fp,$data);
		fclose($fp);
	}
}
function readsearch($filename,$tid,$db_linesize)
{
	$size=2000;
	$step=0;$end=0;$readsize=$db_linesize+1;$scharray=array();
	if($fp=@fopen($filename,"rb+")){
		flock($fp,LOCK_EX);
		while(!feof($fp)){
			$step++;
			$offset=-$readsize*$step;
			fseek($fp,$offset,SEEK_END);
			$line=fread($fp,$readsize);
			if(strpos($line,"||$tid|")!==false){
				$detail=explode("|",$line);
				break;
			}elseif($step<$size){
				$scharray[]=$line;
			}else{
				$fastwrite='Y';
			}
		}
		fseek($fp,$offset,SEEK_END);
		return array($fp,array_reverse($scharray),$detail,$fastwrite);
	}
}

function write_del($fp,$writearray,$fastwrite)
{
	global $db_linesize;
	if($fastwrite!="Y"){
		$writedb=implode("",$writearray);
		fputs($fp,$writedb);
		ftruncate($fp,ftell($fp));
	}else{
		fputs($fp,str_pad(' ',$db_linesize)."\n");
	}
}
function write_alt($fp,$writearray,$fastwrite,$inline)
{
	global $db_linesize;
	if($fastwrite!="Y"){
		array_push($writearray,$inline);
		$writedb=implode("",$writearray);
		fputs($fp,$writedb);
	}else{
		fputs($fp,str_pad(' ',$db_linesize)."\n");
		fseek($fp,0,SEEK_END);
		fputs($fp,$inline);
	}
}
/*删除移动管理时最新信息索引更新函数*/
function getnewstatus($id)
{
	global $dbpath,$db_linesize;
	$filename="$dbpath/$id/list.php";
	if($fp=@fopen($filename,"rb")){
		flock($fp,LOCK_SH);
		fseek($fp,-($db_linesize+1),SEEK_END);
		$newstatus=fread($fp,$db_linesize+1);
		fclose($fp);
	}
	if(strpos($newstatus,"|")!==false){
		$newtpcarray=explode("|",$newstatus);
		$newtpcdb=explode(",",$newtpcarray[7]);
		$lasttpc=explode("|",gets("$dbpath/$id/$newtpcarray[5].php",150));
		$lasttime=date("Y-m-j g:i A",$newtpcdb[1]);
		$lasttid="$id&tid=$newtpcarray[5]";
	}
	return array($lasttid,$lasttpc[5],$newtpcdb[0],$newtpcdb[1],$lasttime);
}
function Move_topic($fid,$tid,$gotoboard,$nextname){
	global $dbpath,$attachpath;
	$att_db=openfile("$dbpath/$fid/$tid.php");
	$att_count=count($att_db);
	for($att_i=0;$att_i<$att_count;$att_i++){
		$att_linedb=explode("|",$att_db[$att_i]);
		if($att_linedb[8]!=''){
			$att_s_linedb=explode("~",$att_linedb[8]);
			$att_linedb[8]='';
			foreach($att_s_linedb as $tpc_download){
				if($tpc_download){
					$att_one_array=explode(",",$tpc_download);
	
					$dfurl=explode("_",$att_one_array[0]);
					@rename("$attachpath/$att_one_array[0]","$attachpath/$gotoboard".'_'.$nextname.'_'.$dfurl[2]);
					$tpc_download=str_replace($att_one_array[0],$gotoboard.'_'.$nextname.'_'.$dfurl[2],$tpc_download);
					$att_linedb[8].=$tpc_download.'~';/*放在此处纠错能力更强*/
				}
			}
			//$att_linedb[8]=str_replace($fid.'_'.$tid.'_',$gotoboard.'_'.$nextname.'_',$att_linedb[8]);
			$att_db[$att_i]=implode("|",$att_linedb);
		}
	}
	$att_write_db=implode("",$att_db);
	writeover("$dbpath/$gotoboard/$nextname.php",$att_write_db);
}
?>