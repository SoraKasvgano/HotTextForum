<?php

!function_exists('readover') && exit('Forbidden');

//����Ա��֤
function htf_forumcheck($G_C=0)
{
	global $allowvisit,$fid_type,$htfid,$action,$groupid,$tid,$fid,$fid_pwd,$pwdcheck,$cookietime,$manager,$fid_skin,$fid_type2,$skin;
    showforuminfo($G_C);//ֱ���ڴ˼���˺���������Ч������Դ,�ӿ��ٶ�

	/**
	* ȡ�ð������
	*/
	$fid_type=trim($fid_type);
	
	/**
	* ������ֻ��ע���Ա���ܽ���
	*/
	if($fid_type=='former' && $groupid=='guest')
		showmsg('�����Ϊ������,ֻ��ע���Ա���ܽ���');

	/**
	* ���ذ��ֻ�й���Ա���ܽ���
	*/
	if($fid_type=='hidden' && $htfid!=$manager)
		showmsg('�����Ϊ���ذ��,ֻ�й���Ա���ܽ���');

	/**
	* ���������
	*/
	if(!empty($fid_skin)&&file_exists("style/$fid_skin.php")){
		$skin=$fid_skin;
	}

	/**
	* ���ܰ����
	*/
	if($fid_type2=='jiami' && $pwdcheck!=$fid && $htfid!=$manager)
	{
		global $htf_action,$htf_password;
		if(!$htf_action){
			//global $printmsgpwd,$userpath,$starttime,$tablewidth,$mtablewidth,$tablecolor,$imgpath,$stylepath,$tplpath;
			global $printmsgpwd;
			$shouldwritepwd="����������&nbsp;
			<input type=password size=20 maxlength=75 name=htf_password>
			<input type=hidden name=fid value=$fid>
			<input type=hidden name=htf_action value=htf_fpwd>
			<input type=submit value='ȷ ��'>";
			$printmsgpwd="
			<table width=100% border=0 cellspacing=1 cellpadding=3>
			<tr><td><form methor=post action='forum.php'>
			<center><br>{$error}<br>{$shouldwritepwd}</center>
			</form></td></tr></table>";

			showmsg("�����Ϊ���ܰ��,��������֤( �ο���Ȩ��½�˰��)");
		}
		else{
			if($fid_pwd==md5($htf_password) && $groupid!='guest'){
				
				/**
				* ��ͬ��鲻ͬ����
				*/
				$pwdcheck=$fid;
				Cookie('pwdcheck',$pwdcheck);
			}
			elseif($groupid=='guest'){
				showmsg("�ο���Ȩ��½���ܰ��");
			}
			else{
				showmsg("����������,��������������");
			}
		}
	}
	if($allowvisit && @strpos($allowvisit,','.$groupid.',')===false && $htfid!=$manager)
		showmsg("�Բ���,�����Ϊ��֤���,��û��Ȩ�޽�ȥ");
}

//���״̬����
function showforuminfo($G_C)
{
	global $ifchildrenforum,$allowvisit,$allowpost,$allowdownload,$allowupload,$allowreply,$fid_name,$fid_type,$fid_father,$fid_pwd,$fid_skin,$fid_type2,$fid_Cconcle,$fid_perpage,$fid_Pconcle,$fid_ifchildren,$forumcount,$fid,$forumarray;
	/**
	* ������������Ѿ����ⲿ������� ͳһΪ$forumarray
	*/
	for ($i=0; $i<$forumcount; $i++) 
	{
		$detail=explode("|", $forumarray[$i]);
		if ($detail[1]=='category') continue;
		if ($detail[4]==$fid)
		{
			if($G_C!=0){
				
				$C_detail=explode("|", $forumarray[$i+1]);
				if($C_detail[5]==$fid){
					$ifchildrenforum=1;
				}
			}
			$fid_type=$detail[1];
			$fid_name=$detail[2];
			$fid_father=$detail[5];
			$fid_pwd=$detail[6]; 
			$fid_skin=$detail[7];
			$fid_Cconcle=$detail[8];
			$fid_Pconcle=$detail[11];
			$fid_perpage=$detail[12];
			$fid_ifchildren=$detail[14];//�ж��Ƿ�ȫ����ʾ�Ӱ��
			list($allowvisit,$allowpost,$allowdownload,$allowupload,$allowreply)=explode("~",$detail[16]);
			if($fid_pwd!='') 
				$fid_type2='jiami';
			break;
		}
	}
}
function getforumadmin($forumall='N')
{
	global $fid,$fid_father;
	$forum_admin=$father_admin=$fidadminarray=array();
	$adminarray=openfile("data/admin.php");
	$count=count($adminarray);
	for ($i=0; $i<$count; $i++) 
	{
		$temp=explode("|", trim($adminarray[$i]));
		if($forumall=='Y') $fidadminarray[$temp[1]][]=$temp[2];/*��ȡ��������Ա*/
		if ($temp[1]==$fid) 
			$forum_admin[]=$temp[2];
		if($fid_father)
		{
			if($temp[1]==$fid_father)
				$father_admin[]=$temp[2];
		}
	}
	return array($forum_admin,$father_admin,$fidadminarray);
}
?>