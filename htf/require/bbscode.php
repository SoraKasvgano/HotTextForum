<?php

!function_exists('readover') && exit('Forbidden');

function convert($message,$allow,$type="post") 
{
	global $picpath,$imgpath,$stylepath,$attachpath,$fid_post,$fid_hide,$fid_sell,$admin_check,$tpc_author,$searchword;
	//$message=str_replace("��","|",$message);
	$message =str_replace("<p>","<br><br>",$message);
	$message =str_replace("<br>"," <br>",$message);
	$message =str_replace("[u]","<u>",$message);
	$message =str_replace("[/u]","</u>",$message);
	$message =str_replace("[b]","<b>",$message);
	$message =str_replace("[/b]","</b>",$message);
	$message =str_replace("[i]","<i>",$message);
	$message =str_replace("[/i]","</i>",$message);
	$message =str_replace("[br]","<br>",$message);
	$message =str_replace("[list]","<ul>",$message);
	$message =str_replace("[/list]","</ul>",$message);
	$message =str_replace('[url=&quot;','[url="',$message);
	$message =str_replace('&quot;]','"]',$message);
	$message=str_replace("[:htfupload]",$attachpath,$message);//�˴�λ�ò��ɵ���
	$message=str_replace("[:htffile]",$picpath,$message);//�˴�λ�ò��ɵ���
	$searcharray = array(
		"/\[font=([^\[]*)\](.+?)\[\/font\]/is",
		"/\[color=([#0-9a-z]{1,10})\](.+?)\[\/color\]/is",
		"/\[email=([^\[]*)\](.+?)\[\/email\]/is",
	    "/\[email\]([^\[]*)\[\/email\]/is",
		"/\[size=([^\[]*)\](.+?)\[\/size\]/is",
		"/\[quote\]\s*(.*?)\s*\[\/quote\]/is",
		"/(\[fly\])(.+?)(\[\/fly\])/is",
		"/(\[move\])(.+?)(\[\/move\])/is",
		"/(\[align=)(left|center|right)(\])(.+?)(\[\/align\])/is",
		"/(\[glow=)(\S+?)(\,)(.+?)(\,)(.+?)(\])(.+?)(\[\/glow\])/is"
	);
	$replacearray = array(
		"<font face=\"\\1\">\\2</font>",
		"<font color=\"\\1\">\\2</font>",
		"<a href=\"mailto:\\1\">\\2</a>",
		"<a href=\"mailto:\\1\">\\1</a>",
		"<font size=\"\\1\">\\2</font>",
		"<table cellpadding=0 cellspacing=0 border=0 WIDTH=95% bgcolor=#000000 align=center><tr><td><table width=100% cellpadding=5 cellspacing=1 border=0><TR><TD BGCOLOR=#EFF3F9>\\1</table></table>",
		"<marquee width=90% behavior=alternate scrollamount=3>\\2</marquee>",
		"<marquee scrollamount=3>\\2</marquee>",
		"<DIV Align=\\2>\\4</DIV>",
		"<table width=\\2 style=\"filter:glow(color=\\4, strength=\\6)\"><tr><td>\\8</td></tr></table>"
	);
	$message=preg_replace($searcharray,$replacearray,$message);
	if ($allow['pic']){
		$message = preg_replace("/\[img\](.+?)\[\/img\]/eis","cvpic('\\1')",$message);
    }else{
		$message = preg_replace("/(\[img\])(\S+?)(\[\/img\])/is","<img src='$imgpath/$stylepath/file/img.gif' align='absbottom'> <a target=_blank href='\\2'>images: \\2</a>",$message);
	}
	if ($allow['flash']){
        $message = preg_replace("/(\[flash=)(\S+?)(\,)(\S+?)(\])(\S+?)(\[\/flash\])/is","<OBJECT CLASSID=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" WIDTH=\\2 HEIGHT=\\4><PARAM NAME=MOVIE VALUE=\\6><PARAM NAME=PLAY VALUE=TRUE><PARAM NAME=LOOP VALUE=TRUE><PARAM NAME=QUALITY VALUE=HIGH><EMBED SRC=\\6 WIDTH=\\2 HEIGHT=\\4 PLAY=TRUE LOOP=TRUE QUALITY=HIGH></EMBED></OBJECT><br />[<a target=_blank href=\\6>ȫ������</a>] ",$message);
	}else{
		$message = preg_replace("/(\[flash=)(\S+?)(\,)(\S+?)(\])(\S+?)(\[\/flash\])/is","<img src='$imgpath/$stylepath/file/music.gif' align='absbottom'> <a target=_blank href=\\6>flash: \\6</a>",$message);
	}
	if($type=="post")
	{
		global $motion,$face;
		include_once'./data/postcache.php';
		$count=count($face);
		for($i=0;$i<$count;$i++)
		{
			//$face1=$face[$i];
			$message=str_replace("[s:{$face[$i]}]","<img src='$imgpath/post/smile/{$face[$i]}'>",$message);
		}
		$message=str_replace($searchword,"<b style='color:red;background-color:#ffff66'>$searchword</b></font>",$message);
		$message=str_replace("[s:smile.gif]","<img src='$imgpath/post/smile/smile.gif'>",$message);
		$message=str_replace("[s:mrgreen.gif]","<img src='$imgpath/post/smile/mrgreen.gif'>",$message);
		$message=str_replace("[s:question.gif]","<img src='$imgpath/post/smile/question.gif'>",$message);
		$message=str_replace("[s:wink.gif]","<img src='$imgpath/post/smile/wink.gif'>",$message);
		$message=str_replace("[s:redface.gif]","<img src='$imgpath/post/smile/redface.gif'>",$message);
		$message=str_replace("[s:sad.gif]","<img src='$imgpath/post/smile/sad.gif'>",$message);
		$message=str_replace("[s:cool.gif]","<img src='$imgpath/post/smile/cool.gif'>",$message);
		$message=str_replace("[s:crazy.gif]","<img src='$imgpath/post/smile/crazy.gif'>",$message);
		$act="<font color=red>��������</font>";
		$count=count($motion);
		for($i=1;$i<=$count;$i++)
			$message=str_replace("/{$motion[$i][0]}","$act $tpc_author {$motion[$i][1]}<br><img src=$imgpath/post/act/{$motion[$i][2]} ><br>",$message);

		if ($fid_sell!=2 && strpos($message,"[sell") !== false && strpos($message,"[/sell]") !== false)
		{ 
			$message=preg_replace("/\[sell=(.+?)\](.+?)\[\/sell\]/eis","sell('\\1','\\2')",$message);
		}
		if ($fid_post!=2 && strpos($message,"[post]") !== false && strpos($message,"[/post]") !== false)
		{ 
			$message=preg_replace("/\[post\](.+?)\[\/post\]/eis","post('\\1')",$message);
		}
		if ($fid_hide!=2 && strpos($message,"[hide") !== false && strpos($message,"[/hide]") !== false)
		{ 
			$message=preg_replace("/\[hide=(.+?)\](.+?)\[\/hide\]/eis","hiden('\\1','\\2')",$message);
		}
		if ($allow['mpeg']){
			$message = preg_replace("/\[wmv\]\s*(\S+?)\s*\[\/wmv\]/is","<EMBED src=\\1 HEIGHT=\"256\" WIDTH=\"314\" AutoStart=0></EMBED>",$message);
			$message = preg_replace("/\[rm\]\s*(\S+?)\s*\[\/rm\]/is","<object classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA height=241 id=Player width=316 VIEWASTEXT><param name=\"_ExtentX\" value=\"12726\"><param name=\"_ExtentY\" value=\"8520\"><param name=\"AUTOSTART\" value=\"1\"><param name=\"SHUFFLE\" value=\"0\"><param name=\"PREFETCH\" value=\"0\"><param name=\"NOLABELS\" value=\"0\"><param name=\"CONTROLS\" value=\"ImageWindow\"><param name=\"CONSOLE\" value=\"_master\"><param name=\"LOOP\" value=\"0\"><param name=\"NUMLOOP\" value=\"0\"><param name=\"CENTER\" value=\"0\"><param name=\"MAINTAINASPECT\" value=\"\\1\"><param name=\"BACKGROUNDCOLOR\" value=\"#000000\"></object><br><object classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA height=32 id=Player width=316 VIEWASTEXT><param name=\"_ExtentX\" value=\"18256\"><param name=\"_ExtentY\" value=\"794\"><param name=\"AUTOSTART\" value=\"1\"><param name=\"SHUFFLE\" value=\"0\"><param name=\"PREFETCH\" value=\"0\"><param name=\"NOLABELS\" value=\"0\"><param name=\"CONTROLS\" value=\"controlpanel\"><param name=\"CONSOLE\" value=\"_master\"><param name=\"LOOP\" value=\"0\"><param name=\"NUMLOOP\" value=\"0\"><param name=\"CENTER\" value=\"0\"><param name=\"MAINTAINASPECT\" value=\"0\"><param name=\"BACKGROUNDCOLOR\" value=\"#000000\"><param name=\"SRC\" value=\"\\1\"></object>",$message);
		}else{
			$message = preg_replace("/(\[wmv\])(\S+?)(\[\/wmv\])/is","<img src='$imgpath/$stylepath/file/music.gif' align='absbottom'> <a target=_blank href='\\2'>\\2</a>",$message);
			$message = preg_replace("/(\[rm\])(\S+?)(\[\/rm\])/is","<img src='$imgpath/$stylepath/file/music.gif' align='absbottom'> <a target=_blank href='\\2'>\\2</a>",$message);
		}
		if ($allow['iframe']) {
			$message = preg_replace("/\[iframe\]\s*(\S+?)\s*\[\/iframe\]/is","<IFRAME SRC=\\1 FRAMEBORDER=0 ALLOWTRANSPARENCY=true SCROLLING=YES WIDTH=97% HEIGHT=340></IFRAME>",$message);
		}else{
			$message = preg_replace("/(\[iframe\])(\S+?)(\[\/iframe\])/is","Iframe �ر�: <a target=_blank href='\\2'>\\2</a>",$message);
		}
		if (strpos($message,"[code]") !== false && strpos($message,"[/code]") !== false)
		{ 
			$message=preg_replace("/\[code\](.+?)\[\/code\]/eis","phpcode('\\1')",$message);
		}//�˴�λ�ò��ɵ���
	}
	$searcharray = array(
		"/\[url=([^\[]*)\](.+?)\[\/url\]/is",
		"/\[url\]www\.([^\[]*)\[\/url\]/is",
		"/\[url\]([^\[]*)\[\/url\]/is"
	);
	$replacearray = array(
		"<a href=\\1 target=_blank>\\2</a>",
		"<a href=\"http://www.\\1\" target=_blank>\\1</a>",
		"<a href=\"\\1\" target=_blank>\\1</a>"
	);
	$message=preg_replace($searcharray,$replacearray,$message);
	if (file_exists("data/wordsfb.php")) {
		global $wordsfb;
		include_once("./data/wordsfb.php");
		if($wordsfb)
		{
			while (list($key,$value)=each($wordsfb))
				$message=str_replace($key,$value,$message);
		}
	}
	return $message;
}
function cvpic($url,$style='')
{
	global $db_bbsurl,$picpath,$attachpath;
	/*һ����һ���жϿ���������������ͼƬ����ɵ���url����*/
	if(strpos($url,$db_bbsurl)!==false)
	{
		$urldb=explode("$db_bbsurl",$url);
		$urlarray=explode("/",$urldb[1]);
		if($urlarray[0]!=''){strpos($urlarray[1],'.')!==false? $urlarray[0]=$attachpath:$urlarray[0]=$picpath;}else{strpos($urlarray[2],'.')!==false? $urlarray[1]=$attachpath:$urlarray[1]=$picpath;}
		$urldb[1]=implode("/",$urlarray);$url=implode("$db_bbsurl",$urldb);
	}
	if(substr($url,0,4)!='http' && !$style) $url='http'.$url;
	$code="<img src='$url' border=0 onload='javascript:if(this.width>screen.width-400)this.width=screen.width-400'>";
	return $code;
}
function phpcode($code)
{
	$code=str_replace("<br>","\n",$code);
	$code=str_replace("<br />","\n",$code);
	$code="<br><font color=red>�����Ǵ���:</font><br><TEXTAREA name=textfield rows=10 style='WIDTH:100%;'>$code</textarea><br><font color=red>[Ctrl+A ȫ��ѡ��]</font>";
	return $code;
}
function sell($moneycost,$code)
{
	global $htfid,$dbpath,$tpc_author,$tpc_buy,$fid,$tid,$tablecolor,$manager,$groupid,$db_moneyname,$tpc_download;
	$sellcheck=0;
	if($moneycost<0) 
		$moneycost=0;
	if ($moneycost && !ereg("^[0-9]{0,}$",$moneycost)) 
		$moneycost=0;
	if(file_exists("$dbpath/$fid/$tid.php"))
	{
		//$filedb=openfile("$dbpath/$fid/$tid.php");
		//$detail=explode("|",$filedb[0]);//�ڻظ�����ۻ����ڴ��޸�
		$userarray=explode(',',$tpc_buy);
		$count=count($userarray);
		empty($userarray[0]) && $count=0;
		for($i=0;$i<$count;$i++)
		{
			$buyers.="<OPTION value=>".$userarray[$i]."</OPTION>";
		}
		if ($tpc_author==$htfid || ($count && in_array($htfid,$userarray)) || $htfid==$manager) 
			$sellcheck=1;//�ں�����Ҳ����ν����������
		else
			$tpc_download='';
		include PrintEot('tpsell');
	}//��Ҫ��Ϊ����post.php��������ж�
	return $printcode.'-->';//����-->��Ҫ�Ǹ���Ϊ��ģ��Ŀɶ��ԺͿ��޸��Ե���ģ��ȡ�ñ���ʱ�����<!--
}
//////////////
function post($code)
{
	global $htfid,$admin_check,$articlearray,$tpc_download;
	$count=count($articlearray);
	for($i=0;$i<$count;$i++)
	{
	
		$authorarray=explode("|",$articlearray[$i]);
		$authortemp[]=$authorarray[2];
	}
	if($admin_check==1 || ($authortemp && in_array($htfid,$authortemp))) 
		$printcode=$code;
	else{
		$tpc_download='';
		$printcode='<br><br><table cellpadding=0 cellspacing=0 border=0 WIDTH=94% bgcolor=#000000 align=center><tr><td><table width=100% cellpadding=5 cellspacing=1 border=0><TR><TD BGCOLOR=#EFF3F9>�����������趨��<font color=red>����</font>,��Ҫ<font color=red>�ظ�</font>����ܿ���</table></table><br><br>';
	}
	return $printcode;
}
function hiden($rvrc,$code)
{
	global $groupid;
	if($groupid!='guest')
	{
		global $admin_check,$userrvrc,$userpath,$tpc_author;
		$rvrc=stripslashes($rvrc);
		$rvrc=intval($rvrc);
		$rvrc=trim($rvrc);
		//$userdetail=explode("|",readover("$userpath/$htfid.php"));
		$authordetail=explode("|",readover("$userpath/$tpc_author.php"));
		$author_require=floor($authordetail[17]/10);
		if($author_require<$rvrc)
			$rvrc=$author_require;
		if($userrvrc<$rvrc && $admin_check!=1)
			$printcode="<table cellpadding=0 cellspacing=0 border=0 WIDTH=100% bgcolor=#000000 align=center><tr><td><table width=100% cellpadding=5 cellspacing=1 border=0><TR><TD BGCOLOR=#EFF3F9>[�����������趨��<font color=red>����</font>,�����ǹ���Ա,��Ҫ{$rvrc}����,������ֻ��{$userrvrc}]</table></table>";
		else 
			$printcode="&nbsp;[�������������������,������<font color=red>����</font>����:]<br><table cellpadding=0 cellspacing=0 border=0 WIDTH=100% bgcolor=#000000 align=center><tr><td><table width=100% cellpadding=5 cellspacing=1 border=0><TR><TD BGCOLOR=#EFF3F9>$code</table></table>";
	}
	else
	{
		$printcode="�Բ���!��û�е�½,����<a href=login.php><font color=red>��¼</font></a>.";
	}
	return $printcode;
}
function showfacedesign($usericon)
{
	global $imgpath;
	$userportait=explode('%',$usericon);
	if (empty($userportait[0]) && empty($userportait[1]))
		return "<br><br>";
	if($userportait[1] && $userportait[2] && $userportait[3])
		return "<table align=center width=$userportait[2] height=$userportait[3]><tr><td background='$userportait[1]'></td></tr></table>";
	else 
		return "<table width=95% style='table-layout: fixed'><tr><td align=center><img src='$imgpath/face/$userportait[0]' border=0></td></tr></table>";
}
?>