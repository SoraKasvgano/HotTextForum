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
	$grouptitle='��ֹע��Ļ�Ա��';
	$msg='���˲����ʻ�����Ե�����������������ã�������Ҫ�����Ǳ���һЩ��������û�������ע��!!!!!!';
	$whatdo='��������Ҫ��ֹ��ע������';
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
	adminmsg("<span class=bold>���Ѿ���������ע���ԱΪ��ֹ��ע����</span><br><span class=bold>$grouparray</span>");
}
?>