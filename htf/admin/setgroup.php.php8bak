<?php

!function_exists('adminmsg') && exit('Forbidden');
if(empty($status)){ $key='default';$status="默认组设置";}
$basename="admin.php?adminjob=setgroup&status=".rawurlencode($status)."&key=$key";

if (empty($action))
{
	$ltitle['default']="默认组设置";
	unset($ltitle['banned']);
	foreach($ltitle as $keys=>$value)
	{
		$rawvalue=rawurlencode($value);
		$level_jump.="<option value=$rawvalue&key=$keys>&nbsp;$value</option>";
	}
	if(@include("./data/groupdb/group_$key.php")){
		if($key!='default') $is_default="<font color=red>独立用户组权限</font>-- <a href=admin.php?adminjob=setgroup&action=default&to=$key>点击采用默认用户组权限</a>";
	}else{
		include "./data/groupdb/group_default.php";
		$is_default="<font color=blue>采用默认用户组权限</font>";
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
//$gp_uploadmaxsize='$group[12]';//上传附件大小上限(字节:1024字节=1 k)超管不受限制


//$gp_ifread='$group[1]';//是否允许浏览贴子
//$gp_ifsearch='$group[2]';//是否允许使用搜索
//$gp_ifmember='$group[3]';//是否允许查看会员列表
//$gp_ifmessege='$group[4]';//是否允许使用短消息功能
//$gp_maxmsg='$group[5]';//最大短消息数目
//$gp_ifpost='$group[6]';//是否允许发贴
//$gp_ifpostvote='$group[7]';//是否允许发起投票
//$gp_ifvote='$group[8]';//是否允许参与投票
//$gp_ifupload='$group[12]';//是否允许上传附件
//$gp_ifdownload='$group[13]';//是否允许下载附件
//$gp_ifuploadrvrc='$group[14]';//是否使用下载附件需要指定威望
//$gp_allownum='$group[15]';//一天最多上传附件个数
writeover("data/groupdb/group_{$key}.php",str_replace("?/>","?>",$groupdb));
adminmsg('用户组权限编辑成功');
}elseif($action=="default"){
	if($to=="default"){
		adminmsg('不可取消默认用户组设置');
	}elseif(isset($to)){
		unlink("./data/groupdb/group_$to.php");
		adminmsg('成功设置该用户组为默认权限');
	}
}
?>