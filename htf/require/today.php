<?php

!function_exists('readover') && exit('Forbidden');

$filename='data/today.php';
$dbtdsize=100;
if(file_exists($filename)){
	$todaydata=readover($filename);
	if($offset=strpos($todaydata,"\n".$htfid.'|')){/*ʹ�þ�ȷƥ�� ������"\n".$htfid.'|'*/
		$offset+=1;
		if($fp=@fopen($filename,"rb+")){
			flock($fp,LOCK_EX);
			list($node,$yestime)=nodeinfo($fp,$dbtdsize,$offset);/*�޸�ͷ���*/
			$nowfp=$offset/($dbtdsize+1);
			if("$nowfp"!=$node && $node!=''){
				fputin($fp,$node,$dbtdsize,$nowfp);/*�޸�ͷ���ָ������ݶ�*/
				list($oldprior,$oldnext)=fputin($fp,$nowfp,$dbtdsize,'node',$node);/*�޸���Ҫ���µ�����*/
				if($oldprior!='node'){
					fputin($fp,$oldprior,$dbtdsize,'M',$oldnext);/*�޸�ǰһ���ĺ���*/
				}
				if($oldnext!='NULL' && $oldprior!='node'){
					fputin($fp,$oldnext,$dbtdsize,$oldprior);/*�޸ĺ�һ����ǰ��*/
				}
			}
			fclose($fp);
		}
	}else{
		$offset=filesize($filename);
		if($fp=@fopen($filename,"rb+")){
			flock($fp,LOCK_EX);
			list($node,$yestime)=nodeinfo($fp,$dbtdsize,$offset);
			if($node!=''){/*�޸�ͷ���*/
				$nowfp=$offset/($dbtdsize+1);
				if($node!='NULL') {
					fputin($fp,$node,$dbtdsize,$nowfp);
				}
				if($node!=$nowfp) fputin($fp,$nowfp,$dbtdsize,'node',$node,Y);/*�������*/
			}
			fclose($fp);
		}
	}
}
if($yestime!=$tdtime) {
	@unlink($filename);
	writeover($filename,str_pad("<?die;?>|NULL|$tdtime|",$dbtdsize)."\n");/*24Сʱ��ʼ��һ��*/
}
function fputin($fp,$offset,$dbtdsize,$prior='M',$next='M',$ifadd='N')
{
	$offset=$offset*($dbtdsize+1);/*������ת����ָ��ƫ����*/
	fseek($fp,$offset,SEEK_SET);
	if($ifadd=='N'){
		$iddata=fread($fp,$dbtdsize);
		$idarray=explode("|",$iddata);
		fseek($fp,$offset,SEEK_SET);
	}
	if($next!='M' && $prior!='M'){/*˵����һ�����Ǳ����ĵ����ݶ�.��Ҫ������������Ϣ���и���*/
		global $htfid,$timestamp,$onlineip,$htfdb;
		$idarray[0]=$htfid;$idarray[3]=$htfdb[8];
		if($ifadd!='N') $idarray[4]=$timestamp;
		$idarray[5]=$timestamp;$idarray[6]=$onlineip;$idarray[7]=$htfdb[16];$idarray[8]=$htfdb[17];
	}
	if($prior=='M') $prior=$idarray[1];
	if($next=='M') $next=$idarray[2];
	$data="$idarray[0]|$prior|$next|$idarray[3]|$idarray[4]|$idarray[5]|$idarray[6]|$idarray[7]|$idarray[8]|";
	$data=str_pad($data,$dbtdsize)."\n";/*����д��*/
	fputs($fp,$data);
	return array($idarray[1],$idarray[2]);/*�������ݸ���ǰ����һ������һ���*/
}
function nodeinfo($fp,$dbtdsize,$offset)
{
	$offset=$offset/($dbtdsize+1);
	$node=fread($fp,$dbtdsize);
	$nodedb=explode("|",$node);/*ͷ����ڵڶ������ݶ�*/
	if(is_int($offset)){
		$nodedata=str_pad("<?die;?>|$offset|$nodedb[2]|",$dbtdsize)."\n";
		fseek($fp,0,SEEK_SET);/*��ָ������ļ���ͷ*/
		fputs($fp,$nodedata);
		return array($nodedb[1],$nodedb[2]);
	}else{
		return '';
	}
}
?>