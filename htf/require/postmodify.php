<?php

!function_exists('readover') && exit('Forbidden');

$atcname="$dbpath/$fid/$tid.php";
if (!file_exists($atcname)||!isset($article)){include("./require/url_error.php");}

$articlearray=openfile($atcname);//$articlearray
$count=count($articlearray);
$htf=$count-1;
if (!$articlearray[$article]) {include("./require/url_error.php");}

list($tpc_fb,$tpc_covert,$tpc_author,$rd_icon,$tpc_date,$tpc_title,$tpc_ip,$tpc_sign,$tpc_download,$tpc_rvrc,$tpc_buy,$tpc_ipfrom,$tpc_ifconvert,$tpc_concle,$tpc_content,$tpc_null1)=explode("|",$articlearray[$article]);

if($article!=0){$hideemail="disabled";/**/}
$page=floor($article/$db_readperpage)+1;//�޸ĵ��������ڵ�ҳ��

/**
* ��ȡ����ԱȨ��֮��
*/
list($forum_admin,$father_admin,$fidadminarray)=getforumadmin('Y');
/**
* �ܰ���ӵ�и����Ĺ���Ȩ��
*/
if ($groupid=='superadmin'){
	$forum_admin[]=$htfid;
}

/**
* ����Ա����������������ɾ�����ӵ�Ȩ��
*/
$post_admincheck=0;$admincheck=0;
if($htfid==$manager ||($father_admin && in_array($htfid,$father_admin)) || ($forum_admin && in_array($htfid,$forum_admin))){
	$admincheck=1;
	$E_array=explode(",",$tpc_concle);
	$E_array[1]==1 && $E_checked='checked';
}
/**
* û�з�������Ȩ�޵İ��,������ⷢ���߲�����������������û�����,���޷��༭
*/
if (($tpc_author==$htfid && ($article!=0 || !$allowpost || strpos($allowpost,",$groupid,")!==false)) || $admincheck)
	$post_admincheck=1;

/**
* ����Ա�����������������������������޸�����Ȩ��
*/
if ($post_admincheck==0){

	showmsg("��û��Ȩ���޸ĸ���,�����Ժ��ʵ���ݵ�¼(����ԭ���� ���� ����Ա)");
}
/**
* �༭����ʱ������
*/
if ($gp_edittime && ($timestamp-$tpc_date)>$gp_edittime){
	$gp_edittime/=60;

	showmsg("�ܾ��û��༭:���ѳ����༭ʱ������ $gp_edittime ����");
}

if($article==0){
	$voteopts=explode("|",readover("$dbpath/$fid/{$tid}vote.php"));
	$votearray = unserialize($voteopts[1]);
}

$tpc_download && $downloaddb=explode("~",$tpc_download);

if (!$_POST['step'])
{
	if($downloaddb){
		$attach='<table><tr><td colspan=6>��������(���뱣��ȥ����)</td></tr>';$num=1;
		foreach($downloaddb as $value){
			if($value){
				list($dfurl,$dfname,$dfhit,$dfrvrc,$dfinfo)=explode(",",$value);
				if(file_exists("$attachpath/$dfurl")){
					$attach.="<td><input type='checkbox' name='keep[]' value='$value' checked><font color='red'><b>$dfname</b></font></td>";
					if($num++%6==0)$attach.='</tr><tr>';
				}
			}
		}
		$num==1?$attach='':	$attach.='</tr></table>';
	}
	if($article==0){
		if(is_array($votearray)){
			if($votearray['multiple'][0])$multi='checked';
			$mostnum=$votearray['multiple'][1];
		}
	}
	$tpc_title==''?$atc_title=' ':$atc_title=$tpc_title;
	$atc_content=str_replace("<br />","\n",$tpc_content);
	$atc_content=str_replace("<br>","\n",$atc_content);
	$post_status="�޸�����";
	include "./header.php";
	$msg_guide=headguide($secondname,$secondurl,"�޸�����");
	include PrintEot('post');
	footer();
}
elseif($_POST['step']==1){
	/**
	* �Ѿ����ظ�������ֻ�й���Ա��������������������ɾ��
	*/
	if($gp_ifdelatc==0){

		showmsg("�����ڵ��û���,û��Ȩ��ɾ���Լ�������");
	}
	if($article==0 && $admincheck!=1 && $count>1){

		showmsg("�����ѱ��ظ�,����ɾ��");
	}

	$filename="$dbpath/$fid/list.php";
	list($writetop,$detail)=searchtop($filename,$db_linesize,$tid);

	if(is_array($detail)){
		if($article>0){
			$detail[6]--;
			if($article==$count-1){
				$lastarray=explode("|",$articlearray[$article-1]);
				$detail[7]="$lastarray[2],$lastarray[4]";
			}
			$getnewitem=str_pad(trim(implode("|",$detail)),$db_linesize)."\n";
			array_unshift($writetop,$getnewitem);
		}else{
			array_push($writetop,str_pad(' ',$db_linesize)."\n");
		}
		$writedb=implode("",$writetop);
		writeselect($filename,$writedb,1,$db_linesize);
	}
	else{
		list($fp,$writearray,$detail,$fastwrite)=readsearch($filename,$tid,$db_linesize);
		if ($detail[5]==$tid){
			if($article>0){
				$detail[6]--;
				if($article==$count-1){
					$lastarray=explode("|",$articlearray[$article-1]);
					$detail[7]="$lastarray[2],$lastarray[4]";
				}
				$getnewitem=str_pad(trim(implode("|",$detail)),$db_linesize)."\n";
				fputs($fp,$getnewitem);/*�޸��޸�����*/
			}
			else{
				write_del($fp,$writearray,$fastwrite);
			}
		}
		fclose($fp);
	}
	if($article==0){
		$nextname=shownextname($db_recycle);
		Move_topic($fid,$tid,$db_recycle,$nextname);
		@copy("$dbpath/$fid/{$tid}vote.php","$dbpath/$db_recycle/{$nextname}vote.php");
		$tpcdb=gets("$dbpath/$db_recycle/$nextname.php",200);
		$M_detail=$detail;
		$M_detail[1]=$fid;//�˹������� forum.phpҳ���ж���������Դ!
		$M_detail[5]=$nextname;
		$M_detail[8]=0;
		$getnewitem=str_pad(trim(implode("|",$M_detail)),$db_linesize)."\n";
		writeover("$dbpath/$db_recycle/list.php",$getnewitem,"ab");
		$tpcdetail=explode("|",$tpcdb);
		$M_status=explode("|",readover("$dbpath/$db_recycle/status.php"));
		$M_status[0]='<?die;?>';$M_status[1]=$tpcdetail[5];$M_status[2]=$tpcdetail[2];
		$M_status[3]=date($db_tformat,$timestamp);$M_status[4]="$db_recycle&tid=$nextname";
		$M_status[5]=$timestamp;
		$M_status[6]<0?$M_status[6]=$M_detail[6]+1:$M_status[6]+=$M_detail[6]+1;
		$M_status[7]<0?$M_status[7]=1:$M_status[7]++;
		$M_status[8]='';
		$M_status[9]='';
		$writestatus=implode("|",$M_status);
		writeover("$dbpath/$db_recycle/status.php",$writestatus);
		unlink($atcname);
	}else{
		unset($articlearray[$article]);
		$articledb=implode("",$articlearray);
		writeover($atcname,$articledb);
	}
	//�޸�status.php�����Ϣ
	list($bbsfb,$bbstpc,$bbsatc,$bbstoday,$bbsyestoday,$bbsmost,$bbspostcontrol,$bbsbirthcontrol,$bbsstar,$bbsrich,$bbslucher,$bbsbirthman)=explode("|",readover("data/bbsatc.php"));
	$statusdetail=explode("|",readover("$dbpath/$fid/status.php"));
	$statusdetail[0]="<?die;?>";
	if($article==0)
	{
		$bbstpc--;$bbsatc-=$detail[6]+1;
		$statusdetail[7]--;$statusdetail[6]-=$detail[6]+1;
	}
	else{
		$bbsatc--;
		$statusdetail[6]--;
	}
	if($tpc_date==$statusdetail[5]){
		list($lasttid,$lasttitle,$lastauthor,$laststamp,$lasttime)=getnewstatus($fid);
		$statusdetail[1]=$lasttitle;$statusdetail[2]=$lastauthor;$statusdetail[3]=$lasttime;
		$statusdetail[4]=$lasttid;$statusdetail[5]=$laststamp;
	}
	writeover("$dbpath/$fid/status.php",implode("|",$statusdetail));
	$writebbsatcdb="<?die;?>|$bbstpc|$bbsatc|$bbstoday|$bbsyestoday|$bbsmost|$bbspostcontrol|$bbsbirthcontrol|$bbsstar|$bbsrich|$bbslucher|$bbsbirthman|";
	writeover("data/bbsatc.php",$writebbsatcdb);
	if($fid_Pconcle!=''){
		list($P_Prvrc,$P_Rrvrc,$P_Pmoney,$P_Rmoney,$P_Drvrc,$P_Dmoney)=explode("~",$fid_Pconcle);
		is_numeric($P_Rrvrc) && $db_dtreplyrvrc=$P_Rrvrc;
		is_numeric($P_Dmoney) && $db_dtdelmoney=$P_Prvrc;
	}
	$article==0?$msg_delrvrc=$db_dtdelrvrc:$msg_delrvrc=$db_dtreplyrvrc;
	dtchange($tpc_author,-$msg_delrvrc,-1,-$db_dtdelmoney); //ɾ�ظ�����ۻ��ַ�����
	$msg_delrvrc=number_format(($msg_delrvrc/10),1);
	$article==0?$deltype='ɾ������':$deltype='ɾ���ظ�';
	$newlog_forum="<?die;?>|$deltype|$fid|$tid|$tpc_title|$tpc_author|������Ļظ���|-$msg_delrvrc|-$db_dtdelmoney|$timestamp|$htfid|$onlineip|\n";

	writeover("data/log_forum.php",$newlog_forum,"ab");
	refreshto("forum.php?fid=$fid","<a href=forum.php?fid=$fid>[ �޸����ӳɹ�������������б� ]</a>");
}
elseif($_POST['step']==2)
{
	if ($reply_check=check_data())
	{
		/**
		* �����޸�
		*/
		include './require/postupload.php';
		$olddownloaddb=implode("~",$keep);
		
		foreach($downloaddb as $value){
			if($value){
				if( strpos($olddownloaddb,$value)===false ){
					list($dfname,$dfhit,$dfrvrc,$dfinfo)=explode(",",$value);
					@unlink("$attachpath/$dfurl");
				}
			}
		}
		
		/**
		* ͶƱ�޸�
		*/
		if($artile==0){
			if(is_array($votearray)){
				foreach($vt_selarray as $key=>$voteoption){
					$voteoption = trim($voteoption);
					if($voteoption){
						$votearray['options'][$key] = array($voteoption, $votearray['options'][$key][1],$votearray['options'][$key][2]);
					}
				}
				if($mostvotes)
					$mostvotes>count($vt_selarray)?$mostvotes=count($vt_selarray):'';
				else
					$mostvotes=count($vt_selarray);
				$votearray['multiple'] = array($multiplevote,$mostvotes);
				$voteopts = serialize($votearray);
				writeover("$dbpath/$fid/{$tid}vote.php","<?die?>|$voteopts");
			}
		}

		$tpc_download='';
		if($htfupload){
			$tpc_download='~'.$htfupload;
			writeover("$userpath/$htfid.php",implode("|",$htfdb));
		}
		$tpc_download=$olddownloaddb.$tpc_download;
		if (!$atc_iconid) $atc_iconid=$rd_icon;
		unset($atc_content);
		$atc_content=& $_POST['atc_content'];
		$_POST['atc_title']==' ' ? $atc_title='':$atc_title=& $_POST['atc_title'];
		$atc_content=safeconvert(stripslashes($atc_content));
		$atc_title=str_replace("&ensp;$","$",$atc_title);
		$atc_title=safeconvert(stripslashes($atc_title));
		$timeofedit=date($db_tformat,$timestamp);
		if($htfid!=$manager && $tpc_date+300<$timestamp)
			$atc_content=$atc_content."<br>[color=gray][������".$htfid."��".$timeofedit."���±༭][/color]";
		if($htfid!=$tpc_author || $htfid==$manager){
			/*����Ա�༭���ӵİ�ȫ�ռ�*/
			$newlog_forum="<?die;?>|�༭����|$fid|$tid|$tpc_title|$tpc_author|�����߲���|0|0|$timestamp|$htfid|$onlineip|\n";
			writeover("data/log_forum.php",$newlog_forum,"ab");
		}

		if ($_POST['atc_autourl']=="1"){
			$atc_content=autourl($atc_content);
			if($atc_requirervrc=='1') 
				$atc_content="[hide=".$atc_rvrc."]".$atc_content."[/hide]";
			if($atc_hide=='1')
				$atc_content="[post]".$atc_content."[/post]";
			if($atc_requiresell=='1' && $article==0)//ȥ�� && $article==0 ��ʹ�ظ���Ҳ���޸ĳɳ�����
				$atc_content="[sell=".$atc_money."]".$atc_content."[/sell]";
			$lxcontent=convert($atc_content,$db_htfpost);
			$ifconvert=$lxcontent==$atc_content ? 1 : 2;
			unset($lxcontent);
		}else{
			$ifconvert=1;
		}
		$atc_content=trim(str_replace("&ensp;$","$",$atc_content));
		$E_array=Ex_plode(",",$tpc_concle,1);
		if($admincheck==1){
			$E_hide!=1 && $E_hide='';
			$E_array[1]=$E_hide;
		}
		$atc_email!=1 && $atc_email='';
		$E_array[0]=$atc_email;
		$tpc_concle=implode(",",$E_array);
		$articlearray[$article]="<?die;?>|$tpc_covert|$tpc_author|$atc_iconid|$tpc_date|$atc_title|$onlineip|$atc_usesign|$tpc_download|$tpc_rvrc|$tpc_buy|$ipfrom|$ifconvert|$tpc_concle|$atc_content|$tpc_null1|\n";
		$articlearray=implode("",$articlearray);
		writeover($atcname,$articlearray);
		refreshto("topic.php?fid=$fid&tid=$tid&page=$page#lastatc","<a href=forum.php?fid=$fid>[ �޸����ӳɹ�������������б� ]</a>");
	}
	else
	{
		include "./header.php";
		$msg_guide=headguide($secondname,$secondurl,"�޸����� ERROR");
		//�������Ѿ������ $msg_info
		showmsg($msg_info);
	}
}
?>