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
	$grouptitle='�����������';
	$msg='����������˿�����ֹһЩ����Ĵ����������̳�����С������ѡ����˵Ĵ���͹��˺�Ĵ���������ò��������ڷ�������ʱ�������û��鿴������ʱ�������ᱻ��ʾ������ζ�Ų�����������������Եġ���������һ���µĹ���ʱ�����е����¶��ᱻ���˽����� ';
	$whatdo='������Ҫ���˴��';
	$adinfo="<tr><td bgcolor=$b valign=middle colspan=2>$cptop<span class=bold>ʹ�÷�����</span></td></tr><tr><td bgcolor='$b'>����һ��Ҫ���˵Ĵ���͹��˺�Ĵ��<span class=bold>�����м���� '=' (���ں�)</span>��<br><span class=bold>ע�⣬ÿ��ֻ��дһ����</span><br><span class=bold>���磺fuck=f**k</span><br>$cpbottom<br><br></td></tr>";
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
		$keyarray.="���г��� <span class=bold>$key</span> �ĵط����� <span class=bold>$value</span> �滻��<br>";
	}
	writeover("$wordsfbfile",$wordsfb);
	adminmsg("<span class=bold>���С�������������ˣ�</span> <br>$keyarray<br>");
}
?>