<?php
require './global.php';
include_once './require/checkpass.php';
$pre_urlarray=explode('/',$_SERVER['HTTP_REFERER']);
//$urlcount=count($pre_urlarray);$pre_url=$nowurl[$urlcount-1];
$pre_url=array_pop($pre_urlarray);
if(!strpos($pre_url, '.php') || strpos($pre_url,'login.php') || strpos($pre_url,'register.php')) {
	$pre_url = 'index.php';
}


if ($groupid!='guest'&& $action!="quit"){
	showmsg("�Բ��𣡣����Ѿ���½�������ظ���½����");
}
if (!$action) $action="login";
if ($action=="login")
{
	if (!$step)
	{
		$jumpurl=$pre_url;
		require("header.php");
		$msg_guide=headguide("��¼��̳");
		include PrintEot('login');footer();	
	}
	elseif($_POST['step']==2)
	{
		unset($hp);
		if($loginuser && $loginpwd)
		{
			$loginpwd=md5($loginpwd);
			list($hp,$L_T,$L_groupid,$loginpwd)=checkpass($loginuser,$loginpwd);
		}
		if($hp==1)
		{
			@include "./data/groupdb/group_$L_groupid.php";
			if(!$gp_ifhide && $hideid==1)
			{
				$hideid=0;$ifhiden=' -- �����ڵ��û��鲻��ʹ�������½';
			}
			$htfid=$loginuser;
			$htfpwd=$loginpwd;
			Loginipwrite($htfid);
			if($cktime!=0)
			{
				$cktime+=$timestamp;
			}
			Cookie('htfid',$htfid,$cktime);
			Cookie('htfpwd',$htfpwd,$cktime);
			Cookie('lastvisit','',0);//��$lastvist����Խ���ע��Ļ�Ա������յ��û�Ա��
			if($hideid==1) Cookie('hideid',$hideid,$cktime);
			if(empty($jumpurl)) $jumpurl="index.php";
			refreshto($jumpurl,'���Ѿ���¼�ɹ�'.$ifhiden);
		}elseif($hp==2){
			$msg="�������,�������Գ��� $L_T ��";
		}elseif($hp==3){
			$msg="�Ѿ����� 6 �������������,������ 10 �������޷�������½,��ʣ�� $L_T ��";
		}else{
			$msg='���û�������,��˶�!';
		}
		showmsg($msg);
	}
}
elseif($action=="quit")
{
	Loginout($htfdb);
	refreshto($pre_url,'״̬�����Ѿ��ɹ��˳�');/*�˳�url ��Ҫʹ��$pre_url ��Ϊ������޸����������һ��ѭ����ת*/
}
function Loginipwrite($htfid){
	global $timestamp,$userpath,$onlineip;
	$filename="$userpath/$htfid.php";
	$userdb=explode("|",readover($filename));
	$userdb[0]='<?die;?>';//��֤�û��İ�ȫ��
	$userdb[1]=$htfid;
	$userdb[19]=$userdb[20];
	$userdb[20]=$timestamp;$userdb[29]=$onlineip;
	$logininfo=Ex_plode("~",$userdb[30],3);
	$e_login=explode(",",$logininfo[3]);$e_login[0]=$onlineip;$e_login[1]=6;
	$logininfo[3]=implode(",",$e_login);
	$userdb[30]=implode("~",$logininfo);
	$userdb=implode("|",$userdb);
	writeover($filename,$userdb);
}
?>