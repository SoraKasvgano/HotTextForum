<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=setbwd";
$wordsfbfile="data/wordsfb.php";
if ($action!="unsubmit")
{
	if (file_exists($wordsfbfile)) 
	{
		require "./$wordsfbfile";
		while (list($key, $value) = each ($wordsfb)) 
		{
			$groupadmin.="$key=$value\n";
		}
	}
	else $groupadmin="";
	$grouptitle='不良词语过滤';
	$msg='不良词语过滤可以阻止一些恶意的词语出现在论坛帖子中。你可以选择过滤的词语，和过滤后的词语。这样，用不良词语在发表文章时，或在用户查看、引用时，都不会被显示。这意味着不良词语过滤是永久性的。当你增加一个新的过滤时，所有的文章都会被过滤交换。 ';
	$whatdo='请输入要过滤词语：';
	$adinfo="<tr><td bgcolor=$b valign=middle colspan=2>$cptop<span class=bold>使用方法：</span></td></tr><tr><td bgcolor='$b'>输入一个要过滤的词语和过滤后的词语，<span class=bold>并在中间加上 '=' (等于号)</span>。<br><span class=bold>注意，每行只能写一个！</span><br><span class=bold>例如：fuck=f**k</span><br>$cpbottom<br><br></td></tr>";
	eval("dooutput(\"".gettmp('admin_group')."\");");
}
elseif ($action=="unsubmit") 
{
	$wordsfb="<?php\n";
	$grouparray=str_replace("\n","",$grouparray);
	$grouparray=explode("\r",$grouparray);
	$count=count($grouparray);
	for ($i=0; $i<$count; $i++)
	{
		list($key,$value)=explode("=",$grouparray[$i]);
		if (empty($key)) continue;
		$wordsfb.="\$wordsfb['$key']='$value';\n";
		$keyarray.="所有出现 <span class=bold>$key</span> 的地方将被 <span class=bold>$value</span> 替换。<br>";
	}
	writeover("$wordsfbfile",$wordsfb);
	adminmsg("<span class=bold>下列“不良词语”被过滤！</span> <br>$keyarray<br>");
}
?>