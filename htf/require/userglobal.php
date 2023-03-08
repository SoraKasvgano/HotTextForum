<?php
!function_exists('readover') && exit('Forbidden');

function checkinline($filename,$offset,$keyword)
{
	global $db_olsize;
	if($offset%($db_olsize+1)!=0) return false;
	$fp=fopen($filename,"rb");
	flock($fp,LOCK_SH);
	fseek($fp,$offset);
	$Checkdata=fread($fp,$db_olsize);
	fclose($fp);
	if(strpos($Checkdata,$keyword.'|')!==false) return true;else return false;
}
//��Ա���ߺ���
function addonlinefile($offset,$htfid)
{
	global $groupid,$lastvisit,$hideid,$timestamp,$onlineip,$db_onlinetime,$fid,$tid,$star_action,$tdtime,$db_olsize,$db_today;

	if (strlen($fid)>3)$fidwt='';else $fidwt=$fid;
	if (strlen($tid)>7)$tidwt='';else $tidwt=$tid;
	$wherebbsyou=getuseraction($fid,$star_action);
	$acttime=date("m-d H:i",$timestamp);
	$inselectfile='Y';
	if($hideid!=1)
	{
		$newonline="$htfid|$timestamp|$onlineip|$fidwt|$tidwt|$groupid|$wherebbsyou|$acttime|";
		$newonline=str_pad($newonline,$db_olsize)."\n";
		
		if(isset($offset) && checkinline("data/online.php",$offset,$htfid)){
			$inselectfile='N';
			writeinline("data/online.php",$newonline,$offset);
		}else{
			//writeover("debug.php","$offset online.php");
			$onlineuser=readover("data/online.php");
			if($offset=strpos($onlineuser,"\n".$htfid.'|')){//��|��ȷ�û�,not strrpos
				$offset+=1;
				$inselectfile='N';
				writeinline("data/online.php",$newonline,$offset);
			}elseif($offset=strpos($onlineuser,str_pad(' ',$db_olsize)."\n")){
				writeinline("data/online.php",$newonline,$offset);
			}else{
				$offset=filesize("data/online.php");/*�õ��ӵ�����е���ʼָ��*/
				writeover("data/online.php",$newonline,"ab");
			}
		}
		if ($db_today && $timestamp-$lastvisit>$db_onlinetime) {
			include './require/today.php';//ʡʱʡ�ط�
		}
	}
	elseif($hideid==1) 
	{
		include('./require/hidden.php');
	}
	if($inselectfile=='Y')
	{
		addselectfile("data/guest.php");
	}
	return $offset;
}
//guest���ߺ���
function addguestfile($offset) 
{
	global $timestamp,$onlineip,$tid,$fid,$star_action,$db_olsize;
	if (strlen($fid)>3)$fidwt='';else $fidwt=$fid;
	if (strlen($tid)>7)$tidwt='';else $tidwt=$tid;
	$wherebbsyou=getuseraction($fid,$star_action);
	$acttime=date("m-d H:i",$timestamp);
	$inselectfile='Y';
	$newonline="$onlineip|$timestamp|<FiD>$fidwt|$tidwt|$wherebbsyou|$acttime|";//<FiD>��Ҫ����forum.php������ҵ�ָ���İ���ο�
	$newonline=str_pad($newonline,$db_olsize)."\n";
	if(isset($offset) && checkinline("data/guest.php",$offset,$onlineip)){
		$inselectfile='N';
		writeinline("data/guest.php",$newonline,$offset);
	}else{
		//writeover("debug.php","$offset guest.php");
		$onlineuser=readover("data/guest.php");
		if($offset=strpos($onlineuser,$onlineip.'|')){
			$inselectfile='N';
			writeinline("data/guest.php",$newonline,$offset);
		}elseif($offset=strpos($onlineuser,str_pad(' ',$db_olsize)."\n")){
			writeinline("data/guest.php",$newonline,$offset);
		}else{
			$offset=filesize("data/guest.php");
			writeover("data/guest.php",$newonline,"ab");
		}
	}
	if($inselectfile=='Y')
	{
		addselectfile("data/online.php");
	}
	return $offset;
}
/*
*������ڵ�½��ע������cookie�����ж�����д��Ϳ���ȫ����,����Ȥ���Գ���!
*$inselectfile�������Ǽ������Y ��ʾ�����ο͵�½�ɻ�Ա,��ӻ�Ա�˳����ο�!
*/
function addselectfile($selectfile)
{
	global $onlineip,$db_olsize;
	$onlineuser=readover($selectfile);
	if($offset=strpos($onlineuser,$onlineip))
	{
		$offset=strpos($onlineuser,"\n",$offset-$db_olsize);$offset+=1;
		writeinline($selectfile,str_pad(' ',$db_olsize)."\n",$offset);
	}
	substrnbsp($selectfile);
}

/*���������ҵ�һ���е���������(��Ҫ�ں���ǰ�����ж�)��ָ��$offset,Ȼ��������$data������һ��*/
function writeinline($filename,$data,$offset)
{
	$fp=fopen($filename,"rb+");
	flock($fp,LOCK_EX);
	fseek($fp,$offset);
	fputs($fp,$data);
	fclose($fp);
}
/*���������ҵ��������п��в�ɾ��֮.���������ҵ�һ��¼�������������Կ������.���������߻�Ա������*/
function substrnbsp($filename)
{
	global $db_olsize,$timestamp,$db_onlinetime,$guestinbbs,$userinbbs;
	$addnbsp=str_pad(" ",$db_olsize)."\n";
	$addfb=str_pad("<?die;?>",$db_olsize)."\n";
	$P_array=array();
	$cutsize=$db_olsize+1;$step=$olnum=0;$onlinetime=$timestamp-$db_onlinetime;
	$fp=fopen($filename,"rb+");
	fseek($fp,0,SEEK_END);
	while(ftell($fp)>$cutsize &&$step<20000){//������filesize���� �ڱ�$step
		$step++;
		$offset=-($cutsize*$step);
		fseek($fp,$offset,SEEK_END);
		$line=fread($fp,28);//��ȡ28���ֽ�.�Ѿ�����ʱ��
		if(empty($end)){
			if(strpos($line,"|")!==false || ftell($fp)<=$cutsize){
				$end=$offset;//break
			}
		}
		if(strpos($line,"|")!==false){
			$detail=explode("|",$line);
			if($detail[1]<$onlinetime){
				$P_array[]=$offset;
			}else{
				$olnum++;
			}
		}
	}
	$p_count=count($P_array);
	flock($fp,LOCK_EX);
	fseek($fp,0);//��ȥ
	fputs($fp,$addfb);//��ȥ
	for($i=0;$i<$p_count;$i++){
		fseek($fp,$P_array[$i],SEEK_END);fputs($fp,$addnbsp);
	}
	if(isset($end)) ftruncate($fp,filesize($filename)+$end+$cutsize);
	fclose($fp);
	@include './data/olcache.php';
	if($filename=="data/guest.php"){$guestinbbs=$olnum;$userinbbs++;}else{$userinbbs=$olnum;$guestinbbs++;}
	$olcache="<?php\n\$userinbbs=$userinbbs;\n\$guestinbbs=$guestinbbs;\n?>";
	writeover('data/olcache.php',$olcache);
}
function getuseraction($id,$action)
{
	global $forumarray,$forumcount;
	list($forumcount,$forumarray)=getforumdb();
	if($id)
	{
		$whereyou='��̳��ҳ';
		for ($i=0; $i<$forumcount; $i++) 
		{
			$detail=explode("|", trim($forumarray[$i]));
			if ($detail[4]==$id)
			{
				$detail[2]=preg_replace("/\<(.+?)\>/eis","",$detail[2]);//ȥ��html��ǩ
				$detail[2]=substrs($detail[2],16);
				$whereyou=$detail[2];break;
			}
		}
		return $whereyou;
	}
	elseif($action)
	{
		switch($action)
		{
			case 'hm': return '��̳��ҳ';
			case 'mb': return '��Ա�б�';
			case 'sc': return '��������';
			case 'rg': return 'ע����';
			case 'vt': return '���յ��û�Ա';
			case 'hp': return '�鿴����';
			case 'nt': return '��������';
			default : return '��̳��ҳ';
		}
	}
	return '��̳��ҳ';
}
function savecheck($groupid){
	global $htfid;
	if($groupid=='superadmin'||$groupid=='rzuser'||$groupid=='ctuser'){
		$superadmindb=openfile("data/cache.php");
		switch ($groupid){
		case'superadmin':
			if(strpos($superadmindb[0],"|$htfid|")===false)$groupid='0';break;
		case'rzuser':
			if(strpos($superadmindb[1],"|$htfid|")===false)$groupid='0';break;
		case'ctuser':
			if(strpos($superadmindb[2],"|$htfid|")===false)$groupid='0';break;
		}
	}
	return str_replace('..','',$groupid);
}
function getusermsg($htfid){
	global $msgpath;
	if (file_exists("data/$msgpath/{$htfid}1.php")){
		$messages=readover("data/$msgpath/{$htfid}1.php");
		strpos($messages,"|0|")!==false? $gotnewmsg=1:$gotnewmsg=0;/*�������|0|�ַ�˵���ж���Ϣ.��Ȼ�����openfile�Ļ�����֪���м���*/
	}
	Cookie("gotnewmsg",$gotnewmsg,0);/*��ʡϵͳ��Դ*/
	return $gotnewmsg;
}
?>