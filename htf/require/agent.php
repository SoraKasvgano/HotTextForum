<?php

!function_exists('readover') && exit('Forbidden');

//浏览器函数
function expinfo($agent) 
{
	$expser="";$expserver="";
    //$agent = $GLOBALS["HTTP_USER_AGENT"];
	if (preg_match("/Mozilla/",$agent) && preg_match("/MSIE/",$agent))
	{
		$temp = explode("(", $agent); $anc=$temp[1];
		$temp = explode(";",$anc); $anc=$temp[1];
		$temp = explode(" ",$anc);$expserver=$temp[2];
		$expserver =preg_replace("/([\d\.]+)/","\\1",$expserver);
		$expserver = " $expserver";
		$expser = "Internet Explorer";
	}
	elseif (preg_match("/Mozilla/",$agent) && !preg_match("/MSIE/",$agent)) 
	{
		$temp =explode("(", $agent); $anc=$temp[0];
        $temp =explode("/", $anc); $expserver=$temp[1];
        $temp =explode(" ",$expserver); $expserver=$temp[0];
        $expserver =preg_replace("/([\d\.]+)/","\\1",$expserver);
        $expserver = " $expserver";
        $expser = "Netscape Navigator";
    }
	if ($expser!="") 
	{
		$expseinfo = "$expser$expserver";
	}
	else
	{
		$expseinfo = "未知的浏览器";
	}
	return $expseinfo;
}
//会员操作系统
function sysinfo($agent) 
{
	$sys="";
	//$agent = $GLOBALS["HTTP_USER_AGENT"];
	if (preg_match('/win/i',$agent) && preg_match('/nt 5\.1/i',$agent))
	{
		$sys="Windows XP";
	}
	elseif (preg_match('/win/i',$agent) && preg_match('/98/',$agent)) 
	{
		$sys="Windows 98";
	}
	elseif (preg_match('/win/i',$agent) && preg_match('/nt 5\.0/i',$agent)) 
	{
		$sys="Windows 2000";
	}
	elseif (preg_match('/win 9x/i',$agent) && strpos($agent, '4.90')) 
	{		
		$sys="Windows ME";
	}
	elseif (preg_match('/win/i',$agent) && strpos($agent, '95')) 
	{
		$sys="Windows 95"; 
    }
	elseif (preg_match('/win/i',$agent) && preg_match('/nt/i',$agent)) 
	{
		$sys="Windows NT";
    }
	elseif (preg_match('/win/i',$agent) && preg_match('/32/',$agent)) 
	{
		$sys="Windows 32";
	}
	elseif (preg_match('/linux/i',$agent)) 
	{
		$sys="Linux";
	}
	elseif (preg_match('/unix/i',$agent)) 
	{
		$sys="Unix";
	}
	elseif (preg_match('/ibm/i',$agent) && preg_match('/os/i',$agent)) 
	{
		$sys="IBM OS/2";
	}
	elseif (preg_match('/NetBSD/i',$agent)) 
	{
		$sys="NetBSD";
	}
	elseif (preg_match('/BSD/i',$agent)) 
	{
		$sys="BSD";
	}
	elseif (preg_match('/FreeBSD/i',$agent)) 
	{
		$sys="FreeBSD";
	}
	else
		$sys = "Unknown";
	return $sys;
}
?>