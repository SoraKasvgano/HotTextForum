<?php
require'./global.php';
include_once "./require/userglobal.php";
include_once './require/security_integration.php'; // Security: CSRF + Rate Limiting

/**
* �û���Ȩ���ж�
*/
($groupid=='guest' || $gp_ifmessege==0) && showmsg("���������û��鲻��ʹ�ö���Ϣ����");

/**
* �ж��Ƿ��ж���Ϣ
*/
if ($action!="read") $gotnewmsg=getusermsg($htfid);

require'./header.php';
require './require/bbscode.php';

$secondname='����Ϣ';
$secondurl='message.php';
$msg_guide=headguide($secondname);


$msg_time='';
if(!$action) $action='receivebox';

// Security: Rate Limiting for message actions
if($action=='send' || $action=='reply') {
	apply_rate_limit('sendmsg', 20, 300); // 20 messages per 5 minutes
}

if($action=='receivebox'||$action=='sendbox'){

	if (file_exists("data/$msgpath/{$htfid}1.php")){
		$receivearray=openfile("data/$msgpath/{$htfid}1.php");
		$msgcount=count($receivearray);
		if(!$receivearray[$msgcount-1])$msgcount--;
		$contl=number_format(($msgcount/$gp_maxmsg)*100,3);
	}
	else{$msgcount='0';	$contl='0';}
}

/**
* �ռ���
*/
if ($action=="receivebox"){
	$msg_receive='';
	$receivebox='<td align=center  class=head>�Ѷ�</td>';
	if (file_exists("data/$msgpath/{$htfid}1.php")){
		$count=count($receivearray);
		if($count>$gp_maxmsg){
			$msg_receive= "<tr><td bgcolor=$forumcolorone align=center colspan=6>��Ķ���Ϣ�Ѵ���,��ע�ⱸ��</td></tr>";
			$receivearray=array_slice($receivearray,-$gp_maxmsg);
			writeover("data/$msgpath/{$htfid}1.php",implode("",$receivearray));
		}
		if (!$receivearray){
			$msg_receive="<tr><td bgcolor=$forumcolorone align=center colspan=6>�յ��ռ��䣬��û���κζ���Ϣ</td></tr>";	
		}
		else{
			$tyle=1;
			$count=min($gp_maxmsg-1,$count-1);
			for ($i=$count; $i>=0; $i--){
				list($msg_fb,$msg_author,$msg_title,$msg_time,$msg_content,$msg_isread)=explode("|",$receivearray[$i]);
				$msg_time=date($db_tformat,$msg_time);
				if ($msg_isread) $msg_isread="��"; else $msg_isread="<font color=red>��</font>";
				$msg_receive.="<tr align=center bgcolor=$forumcolorone>
				<td align=center><a href='$secondurl?action=read&msg=$i'>$msg_title</a></td><td align=center>
				<a href='$secondurl?action=write&msgid=$msg_author'>$msg_author</a></td><td>$msg_time</td>
				<td align=center>$msg_isread</td>
				<td align=center><input type='checkbox' name='delarray[]' value=$i></td></tr>";
			}
		}
	}
	else{
		$msg_receive= "<tr><td bgcolor=$forumcolorone align=center colspan=6>�յ��ռ��䣬��û���κζ���Ϣ</td></tr>";
	}
	include PrintEot('message');footer();
}

/**
* ������
*/
if ($action=="sendbox"){
	if (file_exists("data/$msgpath/{$htfid}2.php")){

		$receivearray=openfile("data/$msgpath/{$htfid}2.php");
		$count=count($receivearray);
		if($count>$gp_maxmsg){
			$msg_send.= "<tr><td bgcolor=$forumcolorone align=center colspan=6>��Ķ���Ϣ�Ѵ���,��ע�ⱸ��</td></tr>";
			$receivearray=array_slice($receivearray,-$gp_maxmsg);
			writeover("data/$msgpath/{$htfid}2.php",implode("",$receivearray));
		}
		if (!$receivearray){
			$msg_send="<tr><td bgcolor=$forumcolorone align=center colspan=6>�յ��ռ��䣬��û���κζ���Ϣ</td></tr>";
		}
		else{
			$tyle=2;
			$count=min($gp_maxmsg-1,$count-1);
			for ($i=$count; $i>=0; $i--){
				list($msg_fb,$msg_ruser,$msg_title,$msg_time,$msg_content)=explode("|",$receivearray[$i]);
				$msg_time=date($db_tformat,$msg_time);
				$msg_send.= "<tr align=center class=f_one><td align=center><a href='$secondurl?action=readsnd&msg=$i'>{$msg_title}</a></td>
				<td align=center>
				<a href='$secondurl?action=write&msgid=$msg_author'>$msg_ruser</a></td><td>$msg_time</td>
				<td align=center><input type='checkbox' name='delarray[]' value=$i></td></tr>";
			}
		}
	}
	else{
		$msg_send="<tr><td bgcolor=$forumcolorone align=center colspan=6>�յ��ռ��䣬��û���κζ���Ϣ</td></tr>";
	}
	include PrintEot('message');footer();
}

/**
* �Ķ��ռ���
*/
if ($action=="read"){
	if (@filesize("data/$msgpath/{$htfid}1.php")!=0){
		$receivearray=openfile("data/$msgpath/{$htfid}1.php");
		if(!empty($receivearray[$msg])){

			list($msg_fb,$msg_author,$msg_title,$msg_time,$msg_content,$msg_isread)=explode("|",$receivearray[$msg]);
			$sfcv_content=convert($msg_content,$db_htfpost);
			if($msg_isread==0){
				$count=count($receivearray);
				for ($i=$count-1; $i>=0; $i--){
					if($i==$msg){
						$receivearray[$i]="$msg_fb|$msg_author|$msg_title|$msg_time|$msg_content|1|\n";
						$newmsgdb=implode("",$receivearray);
						writeover("data/$msgpath/{$htfid}1.php",$newmsgdb);
					}
				}
			}
			$raw_title=rawurlencode($msg_title);
			$raw_author=rawurlencode($msg_author);
			$filename=$htfid.'1.php';
			$msg_time=date($db_tformat,$msg_time);
			$read='�ظ�';
		}
		else{
			
			$mes_info='����Ϣ�ѱ�ɾ��';
		}
	}
	else{
		
		$mes_info='û���ж���Ϣ';
	}
	$gotnewmsg=getusermsg($htfid);
	include PrintEot('message');footer();
}

/**
* �Ķ�������
*/
if ($action=="readsnd"){
	if (@filesize("data/$msgpath/{$htfid}2.php")!=0){
		$sendarray=openfile("data/$msgpath/{$htfid}2.php");
		if(!empty($sendarray[$msg])){
			list($msg_fb,$msg_author,$msg_title,$msg_time,$msg_content)=explode("|",$sendarray[$msg]);
			$msg_time=date($db_tformat,$msg_time);
			$sfcv_content=convert($msg_content,$db_htfpost);
			$read='����';
			$raw_title=rawurlencode($msg_title);
			$raw_author=rawurlencode($msg_author);
			$filename=$htfid.'2.php';
		}
		else{
			$mes_info='����Ϣ�ѱ�ɾ��';
		}
	}
	else{
		$mes_info='û���ж���Ϣ';
	}
	include PrintEot('message');footer();
}

/**
* д����
*/
if($action=="write"){
	if (empty($_POST['step'])){

		isset($msgid) && $msgid= "value=$msgid";
		if (isset($retitle)){
			strpos($retitle,'Re:')!==false ?$retitle= "$retitle": $retitle= "Re:$retitle";
		}
		$retitle=stripslashes(safeconvert($retitle));

		include PrintEot('message');footer();
	}
	elseif($_POST['step']==2){

		if (empty($msg_ruser))
			$mes_info="�û���Ϊ��.";
		elseif(empty($msg_content) ||empty($msg_title))
			$mes_info='���������Ϊ��';
		elseif(!file_exists("$userpath/$msg_ruser.php")){
			$mes_info="�û�������.";
		}
		elseif(strlen($msg_title)>75||strlen($msg_content)>1500)
			$mes_info='���ⲻ�ô���75�ֽ�,���ݲ��ô���1500�ֽ�';
		if($mes_info){
			$writefail="<a href='javascript:history.go(-1)'>������д</a>";
			include PrintEot('message');footer();
		}
		if ($onbbstime<=$gp_postpertime){
			/**
			* ������ˢ�»�õ�ʱ���
			*/
			Cookie('lastvisit','',0);
			$mes_info="<font color=red>����ʧ��</font>:�벻Ҫ�� $gp_postpertime ���������Եķ��Ͷ���Ϣ.";
			include PrintEot('message');footer();
		}
		else{
			$mes_info="�� �� �� ��";
			$msg_content=stripslashes(safeconvert($msg_content));
			$msg_title=stripslashes(safeconvert($msg_title));
			$msg_content=autourl($msg_content);
			$new="<?die;?>|$htfid|$msg_title|$timestamp|$msg_content|0|\n";
			writeover("data/$msgpath/{$msg_ruser}1.php",$new,'ab');
			if($ifsave==Y){
				$new="<?die;?>|$msg_ruser|$msg_title|$timestamp|$msg_content|\n";
				writeover("data/$msgpath/{$htfid}2.php",$new,'ab');
			}
		}
	}
	include PrintEot('message');footer(); 
}

/**
* ����ռ���ͷ�����
*/
if ($action=="clear"){

	if (file_exists("data/$msgpath/{$htfid}1.php"))
		unlink("data/$msgpath/{$htfid}1.php");
	if (file_exists("data/$msgpath/{$htfid}2.php"))
		unlink("data/$msgpath/{$htfid}2.php");

	$mes_info='�������ж���Ϣ�ѱ��ɹ����';
	include PrintEot('message');footer();
}

/**
* ɾ������
*/
if ($action=="del"){
	$d_count=count($delarray);
	if(strpos($tyle,'..')===false){
		$filename="data/$msgpath/$htfid$tyle.php";
		
		$receivearray=openfile($filename);
		for($j=0;$j<$d_count;$j++){

			$count=count($receivearray);
			for ($i=0; $i<$count; $i++){
				if ($i==$delarray[$j])unset($receivearray[$i]);
			}
		}
		$newmsgdb=implode("",$receivearray);
		if(empty($newmsgdb)){
			@unlink($filename);
		}else{
			writeover($filename,$newmsgdb);
		}
		$mes_info='�ɹ�ɾ����ѡ����Ϣ';
		include PrintEot('message');footer();
	}else{
		showmsg('�ļ����Ͳ��ͷ�');
	}
}

function autourl($message){
	global $db_autoimg;
	if($db_autoimg==1){
		$message= preg_replace(array(
					"/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\��]+\.gif)/i",
					"/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\��]+\.jpg)/i"
				), array(
					"[img]\\1\\3[/img]",
					"[img]\\1\\3[/img]"
				), ' '.$message);
	}
	$message= preg_replace(	array(
					"/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp|gopher|news|telnet|mms|rtsp):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\��]+)/i",
					"/(?<=[^\]a-z0-9\/\-_.~?=:.])([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4}))/i"
				), array(
					"[url]\\1\\3[/url]",
					"[email]\\0[/email]"
				), ' '.$message);
	return $message;
}
?>