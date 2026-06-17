<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=ipban";
$ipbanfile="data/ipbans.php";
if ($action!="unsubmit")
{
	if (file_exists($ipbanfile)) 
	{
		require "./$ipbanfile";
		$groupadmin=implode("\n",$banip);
	}
	else $groupadmin="";
	$grouptitle='IP 禁止';
	$msg='如果你禁止了一个 IP 的话，那么这个 IP 将无法进入论坛的任何程式！';
	$whatdo='请输入您要禁止的IP：';
	$adinfo="<tr><td bgcolor=$b valign=middle colspan=2>$cptop<span class=bold>使用方法：</span></td></tr><tr><td bgcolor='$b'>你如果要禁止一个 IP，可以直接输入 IP 地址在这里，比如： 218.16.255.255<BR>如果你要禁止一个 C 类网，那么你可以不输入 IP 的最后一位，比如：218.16.255 <BR>如果你要禁止一个 B 类网，那么你可以不输入 IP 的最后两位，比如：218.16 <BR>(你不必输入最后的句号，程式自动过滤以你所输入的为开头的IP)<br>如果禁止的是一个 1 或者 2 位的IP，不要补全，如 61.xxx 不用写成 061.xxx$cpbottom<br></td></tr>";
	eval("dooutput(\"".gettmp('admin_group')."\");");
}
elseif ($action=="unsubmit") 
{
	$banip="<?php\n";
	$grouparray=str_replace("\n","",$grouparray);
	$grouparray=explode("\r",$grouparray);
	$count=count($grouparray);
	for ($i=0; $i<$count; $i++) 
	{
		//if($grouparray[$i]<>"")
			$banip.="\$banip[$i]='$grouparray[$i]';\n";
	}
	writeover("$ipbanfile",$banip);
	$grouparray=implode("<br>",$grouparray);
	adminmsg("<span class=bold>你已经禁止了下列 IP<br><br>$grouparray</span><br>");
}
?>