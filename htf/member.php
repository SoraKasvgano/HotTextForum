<?php
$star_action='mb';
require("global.php");
require './require/numofpage.php';
list($bbsfb,$bbsnewer,$bbstotleuser)=explode("|",readover("data/bbsnew.php"));


/**
*�û���Ȩ���ж�
*/
if($gp_ifmember==0){

	showmsg("Ȩ�޴���:���������û��鲻�ܲ鿴��Ա�б�");
}

require("header.php");
if (!$page) $page=1;
$total=$page*$db_perpage;
$db=@fopen('data/userarray.php',"rb");
@flock($db,LOCK_SH);
$count=0;
@fgets($db,50);
while (!@feof($db) && $count<$total)
{
	$user_array[]=@fgets($db,50);
	$count++;
}
@fclose($db);
$count=$bbstotleuser;
if ($count%$db_perpage==0) 
	$numofpage=floor($count/$db_perpage);
else 
	$numofpage=floor($count/$db_perpage)+1;
if ($page>$numofpage)	
	$page=$numofpage;
$pagemin=min(($page-1)*$db_perpage , $count-1);  
$pagemax=min($pagemin+$db_perpage-1, $count-1);
$fenye=numofpage($count,$page,$numofpage,"member.php?");
for ($i=$pagemin; $i<=$pagemax; $i++)
{
	if (!trim($user_array[$i])) 
		continue;
	$userfile=$userpath."/".trim($user_array[$i])."."."php";
	if (!file_exists($userfile)) 
		continue;
	$user_info=explode("|",readover($userfile));
	if ($user_info[7]== 1)
		$usergender = "��";
	elseif($user_info[7] == 2)
		$usergender = "Ů";
	else
		$usergender ="δ֪";
	$regdate=date("Y-m-d",$user_info[8]);
	$lastlogin=date($db_tformat,$user_info[20]);//�����������½��ʱ��
	$user_info[17]=floor($user_info[17]/10);
	if($user_info[13]!="") $user_info[13]="<a href=$user_info[13]>�����ҳ</a>";
	$menber_info.= "<tr bgcolor=$forumcolorone height=26><td align=center><a href=usercp.php?action=show&username=".rawurlencode($user_info[1])."><span class=bold>$user_info[1]</span></a></td><td align=center bgcolor=$forumcolortwo>$usergender</td>";
	$menber_info.= "<td align=center><a href=sendemail.php?username=$user_array[$i]>E-mail</a></td>";
	$menber_info.= "<td align=center bgcolor=$forumcolortwo>$user_info[13]</td><td align=center bgcolor=$forumcolortwo>$user_info[14]</td><td align=center >$user_info[11]</td><td align=center bgcolor=$forumcolortwo>$regdate</td><td align=center>$lastlogin</td><td align=center >$user_info[16]</td><td align=center >$user_info[17]</td></tr>";
}
$msg_guide=headguide("��Ա�б�(�»�Ա{$bbsnewer})");
include PrintEot('member');footer();
?>