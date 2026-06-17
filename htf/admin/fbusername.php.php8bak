<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=fbusername";
$bannamefile="data/banname.php";
if ($action!="unsubmit")
{
	if (file_exists($bannamefile)) 
	{
		require "./$bannamefile";
		$groupadmin=implode("\n",$banname);
	}
	else $groupadmin="";
	$grouptitle='禁止注册的会员名';
	$msg='过滤不良词汇你可以到不良词语过滤那设置，这里主要功能是保留一些有意义的用户名不被注册!!!!!!';
	$whatdo='请输入您要禁止的注册名：';
	eval("dooutput(\"".gettmp('admin_group')."\");");
}
elseif ($action=="unsubmit") 
{
	$banname="<?php\n";
	$grouparray=str_replace("\n","",$grouparray);
	$grouparray=explode("\r",$grouparray);
	$count=count($grouparray);
	for ($i=0; $i<$count; $i++) 
	{
		if($grouparray[$i]<>"")
			$banname.="\$banname[$i]='$grouparray[$i]';\n";
	}
	writeover($bannamefile,$banname);
	$grouparray=implode("<br>",$grouparray);
	adminmsg("<span class=bold>你已经开放下列注册会员为禁止的注册名</span><br><span class=bold>$grouparray</span>");
}
?>