<?php

!function_exists('adminmsg') && exit('Forbidden');
if(empty($status)){ $key='default';$status="Ĭ��������";}
$basename="admin.php?adminjob=setgroup&status=".rawurlencode($status)."&key=$key";

if (empty($action))
{
	$ltitle['default']="Ĭ��������";
	unset($ltitle['banned']);
	foreach($ltitle as $keys=>$value)
	{
		$rawvalue=rawurlencode($value);
		$level_jump.="<option value=$rawvalue&key=$keys>&nbsp;$value</option>";
	}
	if(@include("./data/groupdb/group_$key.php")){
		if($key!='default') $is_default="<font color=red>�����û���Ȩ��</font>-- <a href=admin.php?adminjob=setgroup&action=default&to=$key>�������Ĭ���û���Ȩ��</a>";
	}else{
		include "./data/groupdb/group_default.php";
		$is_default="<font color=blue>����Ĭ���û���Ȩ��</font>";
	}
	$gp_edittime/=60;
	ifcheck($gp_ifread,'read');
	ifcheck($gp_ifmember,'member');
	ifcheck($gp_ifsearch,'search');
	ifcheck($gp_ifmessege,'messege');
	ifcheck($gp_ifpost,'post');
	ifcheck($gp_ifpostvote,'postvote');
	ifcheck($gp_ifvote,'vote');
	ifcheck($gp_ifreply,'reply');
	ifcheck($gp_ifupload,'upload');
	ifcheck($gp_ifdownload,'download');
	ifcheck($gp_ifuploadrvrc,'uploadrvrc');
	ifcheck($gp_ifhide,'hide');
	ifcheck($gp_ifportait,'portait');
	ifcheck($gp_ifhonor,'honor');
	ifcheck($gp_ifdelatc,'delatc');
	eval("dooutput(\"".gettmp('group')."\");");
}
elseif($action=="unsubmit")
{
	!$group[maxmsg] && $group[maxmsg]=10;
	!$group[allownum] && $group[allownum]=10;
	!$group[uploadmoney] && $group[uploadmoney]=0;
	!$group[edittime] && $group[edittime]=0;
	!$group[postpertime] && $group[postpertime]=0;
	!$group[searchtime] && $group[searchtime]=0;
	!$group[signnum] && $group[signnum]=0;
	$group[edittime]*=60;
	$groupdb="<?php
\$gp_id='$key';
\$gp_ifread=$group[ifread];
\$gp_ifsearch=$group[ifsearch];
\$gp_ifmember=$group[ifmember];
\$gp_ifmessege=$group[ifmessege];
\$gp_maxmsg=$group[maxmsg];
\$gp_ifpost=$group[ifpost];
\$gp_ifreply=$group[ifreply];
\$gp_ifpostvote=$group[ifpostvote];
\$gp_ifvote=$group[ifvote];
\$gp_ifupload=$group[ifupload];
\$gp_ifdownload=$group[ifdownload];
\$gp_ifuploadrvrc=$group[ifuploadrvrc];
\$gp_allownum=$group[allownum];
\$gp_ifhide=$group[ifhide];
\$gp_uploadmoney=$group[uploadmoney];
\$gp_ifportait=$group[ifportait];
\$gp_ifhonor=$group[ifhonor];
\$gp_edittime=$group[edittime];
\$gp_ifdelatc=$group[ifdelatc];
\$gp_postpertime=$group[postpertime];
\$gp_searchtime=$group[searchtime];
\$gp_signnum=$group[signnum];
?/>";
//$gp_uploadmaxsize='$group[12]';//�ϴ�������С����(�ֽ�:1024�ֽ�=1 k)���ܲ�������


//$gp_ifread='$group[1]';//�Ƿ������������
//$gp_ifsearch='$group[2]';//�Ƿ�����ʹ������
//$gp_ifmember='$group[3]';//�Ƿ�����鿴��Ա�б�
//$gp_ifmessege='$group[4]';//�Ƿ�����ʹ�ö���Ϣ����
//$gp_maxmsg='$group[5]';//������Ϣ��Ŀ
//$gp_ifpost='$group[6]';//�Ƿ�������
//$gp_ifpostvote='$group[7]';//�Ƿ�������ͶƱ
//$gp_ifvote='$group[8]';//�Ƿ��������ͶƱ
//$gp_ifupload='$group[12]';//�Ƿ������ϴ�����
//$gp_ifdownload='$group[13]';//�Ƿ��������ظ���
//$gp_ifuploadrvrc='$group[14]';//�Ƿ�ʹ�����ظ�����Ҫָ������
//$gp_allownum='$group[15]';//һ������ϴ���������
writeover("data/groupdb/group_{$key}.php",str_replace("?/>","?>",$groupdb));
adminmsg('�û���Ȩ�ޱ༭�ɹ�');
}elseif($action=="default"){
	if($to=="default"){
		adminmsg('����ȡ��Ĭ���û�������');
	}elseif(isset($to)){
		unlink("./data/groupdb/group_$to.php");
		adminmsg('�ɹ����ø��û���ΪĬ��Ȩ��');
	}
}
?>