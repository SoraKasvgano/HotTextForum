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
//会员在线函数
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
			if($offset=strpos($onlineuser,"\n".$htfid.'|')){//加|精确用户,not strrpos
				$offset+=1;
				$inselectfile='N';
				writeinline("data/online.php",$newonline,$offset);
			}elseif($offset=strpos($onlineuser,str_pad(' ',$db_olsize)."\n")){
				writeinline("data/online.php",$newonline,$offset);
			}else{
				$offset=filesize("data/online.php");/*得到加到最后行的起始指针*/
				writeover("data/online.php",$newonline,"ab");
			}
		}
		if ($db_today && $timestamp-$lastvisit>$db_onlinetime) {
			include './require/today.php';//省时省地方
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
//guest在线函数
function addguestfile($offset) 
{
	global $timestamp,$onlineip,$tid,$fid,$star_action,$db_olsize;
	if (strlen($fid)>3)$fidwt='';else $fidwt=$fid;
	if (strlen($tid)>7)$tidwt='';else $tidwt=$tid;
	$wherebbsyou=getuseraction($fid,$star_action);
	$acttime=date("m-d H:i",$timestamp);
	$inselectfile='Y';
	$newonline="$onlineip|$timestamp|<FiD>$fidwt|$tidwt|$wherebbsyou|$acttime|";//<FiD>主要用于forum.php里快速找到指定的版块游客
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
*如果能在登陆与注册是用cookie做个判断以下写入就可完全避免,有兴趣可以尝试!
*$inselectfile的作用是假如等于Y 表示可能游客登陆成会员,或从会员退出成游客!
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

/*在索引中找到一行中的任意数据(需要在函数前进行判断)的指针$offset,然后将新数据$data覆盖这一行*/
function writeinline($filename,$data,$offset)
{
	$fp=fopen($filename,"rb+");
	flock($fp,LOCK_EX);
	fseek($fp,$offset);
	fputs($fp,$data);
	fclose($fp);
}
/*在索引中找到最后的所有空行并删除之.将索引中找到一记录不符合条件的以空行填充.并进行在线会员数缓存*/
function substrnbsp($filename)
{
	global $db_olsize,$timestamp,$db_onlinetime,$guestinbbs,$userinbbs;
	$addnbsp=str_pad(" ",$db_olsize)."\n";
	$addfb=str_pad("<?die;?>",$db_olsize)."\n";
	$P_array=array();
	$cutsize=$db_olsize+1;$step=$olnum=0;$onlinetime=$timestamp-$db_onlinetime;
	$fp=fopen($filename,"rb+");
	fseek($fp,0,SEEK_END);
	while(ftell($fp)>$cutsize &&$step<20000){//可以用filesize代替 哨兵$step
		$step++;
		$offset=-($cutsize*$step);
		fseek($fp,$offset,SEEK_END);
		$line=fread($fp,28);//读取28个字节.已经包含时间
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
	fseek($fp,0);//可去
	fputs($fp,$addfb);//可去
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
		$whereyou='论坛首页';
		for ($i=0; $i<$forumcount; $i++) 
		{
			$detail=explode("|", trim($forumarray[$i]));
			if ($detail[4]==$id)
			{
				$detail[2]=preg_replace("/\<(.+?)\>/eis","",$detail[2]);//去除html标签
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
			case 'hm': return '论坛首页';
			case 'mb': return '会员列表';
			case 'sc': return '搜索程序';
			case 'rg': return '注册中';
			case 'vt': return '今日到访会员';
			case 'hp': return '查看帮助';
			case 'nt': return '公告中心';
			default : return '论坛首页';
		}
	}
	return '论坛首页';
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
		strpos($messages,"|0|")!==false? $gotnewmsg=1:$gotnewmsg=0;/*如果含有|0|字符说明有短消息.当然如果用openfile的话可以知道有几条*/
	}
	Cookie("gotnewmsg",$gotnewmsg,0);/*节省系统资源*/
	return $gotnewmsg;
}
?>