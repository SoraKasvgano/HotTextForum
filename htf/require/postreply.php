<?php

!function_exists('readover') && exit('Forbidden');
/**
* ���Ȩ���ж�
*/
if($allowreply && strpos($allowreply,",$groupid,")===false && $htfid!=$manager){

	showmsg('����ֻ̳���ض��û�����ܻظ�����');
}
if(!$allowreply && $gp_ifreply==0){
	showmsg("���������û���û�лظ����ӵ�Ȩ��");
}
if (!file_exists("$dbpath/$fid/$tid.php")) {include("./require/url_error.php");}
$oldfiledb=openfile("$dbpath/$fid/$tid.php");
$tpclist=$oldfiledb[0];
$tpcarray=explode("|",$tpclist);
$replytitle=$tpcarray[5];$tpc_author=$tpcarray[2];/*��Ҫ��Ϊconvert������Ҫ$tpc_author����*/
list($topic_hit,$topic_style,$null)=explode(",",$tpcarray[1]);
if ($htfid!=$manager && ($topic_style==1 || $topic_style==3||$topic_style==5)){

	showmsg("�����ѱ����������ɻظ�");
}
if(!$_POST['step'])
{
	require './require/bbscode.php';
	$post_status="ֱ�ӻظ�����";
	$hideemail="disabled";
	if ($action=="quote") 
	{
		$post_status="���ò��ظ�����";
		if ($oldfiledb[$article])
		{
			$oldfilearray=explode("|",$oldfiledb[$article]);
			$old_author=$oldfilearray[2];
			$replytitle=$oldfilearray[5];$wtof_oldfile=$use_date=date($db_tformat,$oldfilearray[4]);$old_content=$oldfilearray[14];
			$old_content=preg_replace("/\[hide=(.+?)\](.+?)\[\/hide\]/is","[color=red]���������Ҫ����[/color]",$old_content);
			$old_content=preg_replace("/\[post\](.+?)\[\/post\]/is","[color=red]�˴��Ǳ����õ�������[/color]",$old_content);
			$old_content=preg_replace("/\[sell=(.+?)\](.+?)\[\/sell\]/is","[color=red]�˴��Ǳ����õĳ�����[/color]",$old_content);
			$old_content=preg_replace("/\[quote\](.*)\[\/quote\]/is","",$old_content);
			$old_content=str_replace("<br>","\n",$old_content);$old_content=str_replace("<br />","\n",$old_content);
			$bit_content = explode("\n",$old_content);
			if (count($bit_content) > 5)
			{
				$old_content = "$bit_content[0]\n$bit_content[1]\n$bit_content[2]\n$bit_content[3]\n$bit_content[4]\n.......";
			}
			$atc_content="[quote][b]����������{$old_author}��{$wtof_oldfile}����� {$replytitle}:[/b]\n{$old_content}[/quote]\n";
		}
	}
	include "./header.php";
	$post_reply="<br><center>����ع�</center>";
	$count=count($oldfiledb)-1;
	$lastreply =max($count-$db_showreplynum,0);

	for ($i=$count; $i>=$lastreply; $i--) 
	{
		$detail=explode("|",$oldfiledb[$i]);
		$post_reply.="<table width=70% align=center cellspacing=1 cellpadding=2 style='TABLE-LAYOUT: fixed;WORD-WRAP: break-word'><tr><td>$detail[2] : $detail[5]</td></tr><tr><td>".convert($detail[14],$db_htfreply)."</td></tr></table><hr size=1 color=$tablecolor width=80%>";
	}
	$replytitle==''?$atc_title='Re:'.$tpcarray[5]:$atc_title='Re:'.$replytitle;//�������ʱΪ�˼��ٿռ�,�ظ����������Ϊ��,����Ĭ��Ϊ�ظ�����!
	$msg_guide=headguide($secondname,$secondurl,"����ظ�");
	include PrintEot('post');footer();
}
elseif ($_POST['step']==2) 
{
	$atc_title=& $_POST['atc_title'];
	$atc_title=safeconvert(stripslashes($atc_title));
	$reply_check=check_data();
	unset($atc_content);
	$atc_content=& $_POST['atc_content'];
	if(!$htfid)$htfid='guest';
	/**
	*�¾���Ҫ��Ϊ�˽�ʡ���ݵ��ظ�,�����������ж�
	*/
	if (!$atc_iconid)
		$atc_iconid="R";
	$_POST['atc_title']=='Re:'."$replytitle" ? $atc_title='':$atc_title=$_POST['atc_title'];
	$atc_content=safeconvert(stripslashes($atc_content));
	include './require/postupload.php';
	if ($reply_check)
	{
		if ($_POST['atc_autourl']=="1"){
			$atc_content=autourl($atc_content);
			$atc_requirervrc=='1' && $atc_content="[hide=".$atc_rvrc."]".$atc_content."[/hide]";
			$atc_hide=='1' && $atc_content="[post]".$atc_content."[/post]";
			$lxcontent=convert($atc_content,$db_htfpost);
			$ifconvert=$lxcontent==$atc_content ? 1 : 2;
			unset($lxcontent);
			//if($atc_requiresell=='1')
			//	$atc_content="[sell=".$atc_money."]".$atc_content."[/sell]";//ȥ��ע�;Ϳ����ڻظ���ӳ�������
		}else{
			$ifconvert=1;
		}
		$ifconvert=$lxcontent==$atc_content ? 1 : 2;
		$atc_title=str_replace("&ensp;$","$",$atc_title);
		$filename="$dbpath/$fid/list.php";
		list($toparray,$topdetail)=searchtop($filename,$db_linesize,$tid);//δ����д��
		if(is_array($topdetail)){
			$topdetail[6]=count($oldfiledb);
			$topdetail[7]=$htfid.",".$timestamp;
			$top_s=trim(implode("|",$topdetail));
			$newtop=str_pad($top_s,$db_linesize)."\n";
			//array_unshift($toparray,$newtop);
			//$topspace=str_pad(' ',$db_linesize)."\n";
			//$toparray=array_pad($toparray,$db_topnum,$topspace);
			$writedb=$newtop.implode("",$toparray);

			writeselect($filename,$writedb,1,$db_linesize);
			unset($toparray,$topdetail);
		}
		else{
			list($fp,$temparray,$post_tpc,$fastwrite)=readsearch($filename,$tid,$db_linesize);
			if($post_tpc[5]==$tid){
				$post_tpc[6]=count($oldfiledb);
				$post_tpc[7]=$htfid.",".$timestamp;
				$replyline=str_pad(trim(implode("|",$post_tpc)),$db_linesize)."\n";
				write_alt($fp,$temparray,$fastwrite,$replyline);
				fclose($fp);
				unset($temparray,$writearray,$post_tpc);
			}
			else{
				fclose($fp);//$fpΪ�ں���readsearch�򿪵�ָ��
				showmsg("��ȡ���ݴ���,ԭ�������ļ����ƻ�,�뵽��̨�޸������ļ�!");
			}
		}
		/*$newposttime=date($db_tformat,$timestamp);
		$writenewpost="<?die;?>|Re:$replytitle|$htfid|$newposttime|$atc_iconid|$tid|$fid|$fid_name||\n"; 
		if ($fid!=$db_recycle)
			writeover("data/newpost.php",$writenewpost,"ab+"); */
		//$file_line=array($atc_title,$htfid,$atc_content,$timestamp,$ip,$atc_iconid);
		if (!$atc_usesign)
			$atc_usesign="0";

		if ($db_replysendmail==1){
			$E_array=explode(",",$tpcarray[13]);
			if ($E_array[0]==1 && $htfid != $tpcarray[2]){
				$receiver = $tpcarray[2];
				$old_title=$tpcarray[5];
				$detail = explode("|",readover("$userpath/$receiver.php"));
				$send_address= $detail[3];
				$textmessage="Hi, $receiver ,\n    ����{$db_bbsname}�ʼ���ʹ��\n    ����{$db_bbsname}���������: $old_title\n    �������˻ظ�.������עһ�°�\n    {$db_bbsurl}/topic.php?fid=$fid&tid=$tid\n    �´������˲�������ʱ,�ҽ�����������\n\n___________________________________\n��ӭ���� {$db_wwwname}\n����̳��htf Studio����,��ӭ����htf��̳ http://www.htf.com/bbs ";
				if ($detail[22]=="1"){
					if(mail("$send_address", "$receiver����{$db_bbsname}�������лظ�", "$textmessage","From: $db_ceoemail\nReply-To: $db_ceoemail\nX-Mailer: {$db_bbsname}�ʼ�ϵͳ")){
						$ifmail="�Ѿ������ʼ�֪ͨ���ⶩ����";
						//$E_array[0]=0;
					}
					else
						$ifmail="����֪ͨ�ʼ�ʧ��";
				}
			}
		}
		$atc_content=trim(str_replace("&ensp;$","$",$atc_content));
		//$atc_email!=1 && $atc_email='';$E_hide!=1 && $E_hide='';
		$writetodb="<?die;?>||$htfid|$atc_iconid|$timestamp|$atc_title|$onlineip|$atc_usesign|$htfupload|||$ipfrom|$ifconvert|$atc_email|$atc_content||\n";
		writeover("$dbpath/$fid/$tid.php",$writetodb,"ab");
		//$page=floor($post_tpc[6]/$db_readperpage)+1;
		bbspostguide($replytitle);//����ԭ����!
		refreshto("topic.php?fid=$fid&tid=$tid&page=lastpost#lastatc","{$ifmail}<a href=forum.php?fid=$fid>[ �ظ����ӳɹ�������������б� ]</a>");
	}
	else
	{
		include "./header.php";
		$msg_guide=headguide($secondname,$secondurl,"��������");
		//�������Ѿ������ $msg_info
		showmsg($msg_info);
	}
}
?>