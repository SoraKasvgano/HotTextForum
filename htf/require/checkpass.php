<?php

!function_exists('readover') && exit('Forbidden');

function Loginout($htfdb){
	global $groupid,$htfid,$db_onlinetime,$userpath;
	if($htfdb[0]=='<?die;?>' && $htfdb[1]==$htfid){
		$htfdb[20]-=$db_onlinetime*1.5;
		writeover("$userpath/$htfid.php",implode("|",$htfdb));
	}
	if($groupid=='banned'){
		Cookie('forbiduser',$htfid);//禁止受限制用户发言
	}
	$htfid="";$htfpwd="";$hideid="";$lastvisit="";
	Cookie('htfid','',0);
	Cookie('htfpwd','',0);
	Cookie('hideid','',0);
	Cookie('lastvisit','',0);
}
function checkpass($username,$password)
{
	global $userpath,$timestamp,$onlineip;
	$U_F_N="$userpath/$username.php";
	$E_T_S=@filemtime($U_F_N);
	if (file_exists($U_F_N) && strpos($username,".")===false){
		$userarray=explode("|",readover($U_F_N));
		$logininfo=Ex_plode("~",$userarray[30],3);
		$e_login=explode(",",$logininfo[3]);
		if($e_login[0]!=$onlineip || ($timestamp-$E_T_S)>600 || $e_login[1]>1 ){
			if(strlen($userarray[2])==16){
				$password=substr($password,8,16);
			}
			if($userarray[1]==$username && $userarray[2]==$password){
				$L_groupid=str_replace('..','',$userarray[5]);
				$hp=1;
			}else{
				$userarray[0]='<?die;?>';
				$e_login[0]=$onlineip;
				$L_T=$e_login[1];
				$L_T ? $e_login[1]--:$e_login[1]=5;
				$logininfo[3]=implode(",",$e_login);
				$userarray[30]=implode("~",$logininfo);
				$userdb=implode("|",$userarray);
				writeover($U_F_N,$userdb);unset($userdb);
				$hp=2;//密码错误,您还可以尝试 $e_login[1] 次
			}
		}else{
			$L_T=600-($timestamp-$E_T_S);
			$hp=3;
		}
	}
	return array($hp,$L_T,$L_groupid,$password);
}
function cvipfrom($onlineip)
{
	$detail=explode(".",$onlineip);
	if (file_exists("data/ip/$detail[0].txt"))
		$filename="data/ip/$detail[0].txt";
	else
		$filename="data/ip/0.txt";
	for ($i=0; $i<=3; $i++)
	{
		$detail[$i]     = sprintf("%03d", $detail[$i]);
	}
	$onlineip=join(".",$detail);
	$db=fopen($filename,"rb");
	flock($db,LOCK_SH);
	$onlineipdb=fread($db,filesize($filename));
	if($htfset=strpos($onlineipdb,"$detail[0].$detail[1].$detail[2]")){
		$ipfrom=ipselect($db,$htfset,$onlineip);
	}elseif($htfset=strpos($onlineipdb,"$detail[0].$detail[1]")){
		$ipfrom=ipselect($db,$htfset,$onlineip);
	}elseif($htfset=strpos($onlineipdb,$detail[0])){
		$ipfrom=ipselect($db,$htfset,$onlineip);
	}
	fclose($db);
	if(empty($ipfrom)) $ipfrom='未知地址';
	return $ipfrom;
}
function ipselect($db,$offset,$onlineip){
	fseek($db,$offset,SEEK_SET);
	$getcontent=fgets($db,100);
	$iparray=explode("|",$getcontent);
	if ($onlineip>=$iparray[0] && $onlineip<=$iparray[1]) return $iparray[2].$iparray[3];
}
?>