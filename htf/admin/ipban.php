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
	$grouptitle='IP ��ֹ';
	$msg='������ֹ��һ�� IP �Ļ�����ô��� IP ���޷�������̳���κγ�ʽ��';
	$whatdo='��������Ҫ��ֹ��IP��';
	$adinfo="<tr><td bgcolor=$b valign=middle colspan=2>$cptop<span class=bold>ʹ�÷�����</span></td></tr><tr><td bgcolor='$b'>�����Ҫ��ֹһ�� IP������ֱ������ IP ��ַ��������磺 218.16.255.255<BR>�����Ҫ��ֹһ�� C ��������ô����Բ����� IP �����һλ�����磺218.16.255 <BR>�����Ҫ��ֹһ�� B ��������ô����Բ����� IP �������λ�����磺218.16 <BR>(�㲻���������ľ�ţ���ʽ�Զ����������������Ϊ��ͷ��IP)<br>�����ֹ����һ�� 1 ���� 2 λ��IP����Ҫ��ȫ���� 61.xxx ����д�� 061.xxx$cpbottom<br></td></tr>";
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
	adminmsg("<span class=bold>���Ѿ���ֹ������ IP<br><br>$grouparray</span><br>");
}
?>