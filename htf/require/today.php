<?php

!function_exists('readover') && exit('Forbidden');

$filename='data/today.php';
$dbtdsize=100;
if(file_exists($filename)){
	$todaydata=readover($filename);
	if($offset=strpos($todaydata,"\n".$htfid.'|')){/*使用精确匹配 必须是"\n".$htfid.'|'*/
		$offset+=1;
		if($fp=@fopen($filename,"rb+")){
			flock($fp,LOCK_EX);
			list($node,$yestime)=nodeinfo($fp,$dbtdsize,$offset);/*修改头结点*/
			$nowfp=$offset/($dbtdsize+1);
			if("$nowfp"!=$node && $node!=''){
				fputin($fp,$node,$dbtdsize,$nowfp);/*修改头结点指向的数据段*/
				list($oldprior,$oldnext)=fputin($fp,$nowfp,$dbtdsize,'node',$node);/*修改需要更新的数据*/
				if($oldprior!='node'){
					fputin($fp,$oldprior,$dbtdsize,'M',$oldnext);/*修改前一结点的后趋*/
				}
				if($oldnext!='NULL' && $oldprior!='node'){
					fputin($fp,$oldnext,$dbtdsize,$oldprior);/*修改后一结点的前趋*/
				}
			}
			fclose($fp);
		}
	}else{
		$offset=filesize($filename);
		if($fp=@fopen($filename,"rb+")){
			flock($fp,LOCK_EX);
			list($node,$yestime)=nodeinfo($fp,$dbtdsize,$offset);
			if($node!=''){/*修改头结点*/
				$nowfp=$offset/($dbtdsize+1);
				if($node!='NULL') {
					fputin($fp,$node,$dbtdsize,$nowfp);
				}
				if($node!=$nowfp) fputin($fp,$nowfp,$dbtdsize,'node',$node,Y);/*添加数据*/
			}
			fclose($fp);
		}
	}
}
if($yestime!=$tdtime) {
	@unlink($filename);
	writeover($filename,str_pad("<?die;?>|NULL|$tdtime|",$dbtdsize)."\n");/*24小时初始化一次*/
}
function fputin($fp,$offset,$dbtdsize,$prior='M',$next='M',$ifadd='N')
{
	$offset=$offset*($dbtdsize+1);/*将行数转换成指针偏移量*/
	fseek($fp,$offset,SEEK_SET);
	if($ifadd=='N'){
		$iddata=fread($fp,$dbtdsize);
		$idarray=explode("|",$iddata);
		fseek($fp,$offset,SEEK_SET);
	}
	if($next!='M' && $prior!='M'){/*说明这一数据是被更改的数据段.需要对其他辅助信息进行更改*/
		global $htfid,$timestamp,$onlineip,$htfdb;
		$idarray[0]=$htfid;$idarray[3]=$htfdb[8];
		if($ifadd!='N') $idarray[4]=$timestamp;
		$idarray[5]=$timestamp;$idarray[6]=$onlineip;$idarray[7]=$htfdb[16];$idarray[8]=$htfdb[17];
	}
	if($prior=='M') $prior=$idarray[1];
	if($next=='M') $next=$idarray[2];
	$data="$idarray[0]|$prior|$next|$idarray[3]|$idarray[4]|$idarray[5]|$idarray[6]|$idarray[7]|$idarray[8]|";
	$data=str_pad($data,$dbtdsize)."\n";/*定长写入*/
	fputs($fp,$data);
	return array($idarray[1],$idarray[2]);/*传回数据更新前的上一结点和下一结点*/
}
function nodeinfo($fp,$dbtdsize,$offset)
{
	$offset=$offset/($dbtdsize+1);
	$node=fread($fp,$dbtdsize);
	$nodedb=explode("|",$node);/*头结点在第二个数据段*/
	if(is_int($offset)){
		$nodedata=str_pad("<?die;?>|$offset|$nodedb[2]|",$dbtdsize)."\n";
		fseek($fp,0,SEEK_SET);/*将指针放于文件开头*/
		fputs($fp,$nodedata);
		return array($nodedb[1],$nodedb[2]);
	}else{
		return '';
	}
}
?>