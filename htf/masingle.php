<?php
require "./global.php";
require "./require/dbmodify.php";
include './require/forum.php';
list($bbsfb,$bbstpc,$bbsatc,$bbstoday,$bbsyestoday,$bbsmost,$bbspostcontrol,$bbsbirthcontrol,$bbsstar,$bbsrich,$bbslucher,$bbsbirthman)=explode("|",readover("data/bbsatc.php"));
if(empty($forumcount)) list($forumcount,$forumarray)=getforumdb();
htf_forumcheck();

$secondname="$fid_name";
$secondurl="forum.php?fid=$fid";

$groupid=='guest' && showmsg("����ȫ��֤ʧ��,�����Ѿ���¼���IP ��������ʧ,���ǽ�׷������");
empty($action)    && showmsg("���Ϊ��,����������Ϊ��,��û��ѡ�������Ŀ");

//������֤��ʼ!

list($forum_admin,$father_admin,$fidadminarray)=getforumadmin();
if(($forum_admin && in_array($htfid,$forum_admin)) ||($father_admin && in_array($htfid,$father_admin)) ||$groupid=='superadmin' || $htfid==$manager) $ma_check=1;else $ma_check=0;

if ($ma_check==0){

	showmsg("��û��Ȩ�����в���,�����Ժ��ʵ���ݵ�¼(����Ա,����)");
}
/**
* ������ɾ�����ֻ��
*/
if($fid_Pconcle!=''){
	list($P_Prvrc,$P_Rrvrc,$P_Pmoney,$P_Rmoney,$P_Drvrc,$P_Dmoney)=explode("~",$fid_Pconcle);
	is_numeric($P_Drvrc) && $db_dtdelrvrc=$P_Prvrc;
	is_numeric($P_Dmoney) && $db_dtdelmoney=$P_Prvrc;
}

$atcname="$dbpath/$fid/$tid.php";
if ($action=="showping")
{
	if (!file_exists($atcname)) {include("require/url_error.php");}
	$articlearray=openfile($atcname);
	$count=count($articlearray);
	if (!isset($article) || !$articlearray[$article]){include("require/url_error.php");}
	$pingdetail=explode("|",$articlearray[$article]);
	$tpc_author=$pingdetail[2];
	if($article!=0){$ping_db=explode("|",$articlearray[0]);$tpc_title='Re:'.$ping_db[5];}else{$tpc_title=$pingdetail[5];}
	list($rvrc,$adminrvrc)=explode(",",$pingdetail[9]);
	if (ereg("[0-9]{1,3}$",$rvrc)){

		showmsg("�����Ѿ�������,�벻Ҫ�ظ�,������������ϵ�ٷ���̳");
	}
	if ($htfid==$tpc_author && $htfid != $manager){

		showmsg("�Բ���,�����ܸ��Լ��������������,�뷵��");
    }
	if ($_POST['step'] != 1)
	{
		require "./header.php";
		$msg_guide=headguide($secondname,$secondurl,'��������');
		$posttime=date($db_tformat,$pingdetail[4]);
		if (strlen($pingdetail[14])>300) 
			$pingdetail[14]=substrs($pingdetail[14],294);
		$raw_author=rawurlencode($tpc_author);
		include PrintEot('masingle');footer();
	}
	$pingrvrc=$postrvrc*10;
	$pingdetail[9]="$postrvrc,$adminping";
	$articlearray[$article]=implode("|",$pingdetail);
	$articledb=implode("",$articlearray);
	writeover($atcname,$articledb);
	if($tpc_author!='guest'){
		dtchange($tpc_author,$pingrvrc,0,0);
		$page=floor($article/$db_readperpage)+1;
		$new="<?die;?>|ϵͳ��Ϣ|������±�����!!|$timestamp|�������<span class=bold>[<a href=\"topic.php?fid=$fid&tid=$tid&page=$page\" target=blank><font color=blue>$tpc_title</font></a>]</span>  �õ�<font color=blue>$adminping</font>��<font color=red>����</font>,�ɴ˶�����ֵ�Ӱ����<font color=red>$postrvrc</font>������|0|\n";
		writeover("data/$msgpath/{$tpc_author}1.php",$new,"ab");//���������󷢶���Ϣ֪ͨ����
	}
	refreshto("topic.php?fid=$fid&tid=$tid","���ֳɹ�");
}
if (empty($_POST['step'])) 
{
	require "./header.php";
	$msg_guide=headguide($secondname,$secondurl);
	if($action=="del")
		$ma_whatdo="ɾ������";
	elseif($action=="move") 
		$ma_whatdo="ת�����ӣ���ѡ��Ҫת�Ƶ��İ��";
	elseif($action=="copy") 
		$ma_whatdo="�������ӣ���ѡ��Ҫ���Ƶ��İ��";
	elseif($action=="lock")
		$ma_whatdo="��ס����";
	elseif($action=="unlock")
		$ma_whatdo="��������";
	elseif($action=="digest")
		$ma_whatdo="�����Ӽ��뾫��";
	elseif($action=="undigest")
		$ma_whatdo="������ȡ������";
	elseif($action=="pushtopic")
		$ma_whatdo="ǰ������";
	elseif($action=="headtopic") 
		$ma_whatdo="�ö�����";
	elseif($action=="unheadtopic")
		$ma_whatdo="ȡ���ö�";
	elseif($action=="edit"){
		$ma_whatdo="�༭����";
		$articledb=openfile($atcname);
		$detail=explode("|",$articledb[0]);
		$T_P_C=explode(",",$detail[1]);
		$titledetail=explode("~",$T_P_C[2]);
		$titlecolor=$titledetail[0];
		$ifchecked[$titlecolor]='checked';
		if($titledetail[1]=='1')$ifchecked[1]='checked';
		if($titledetail[2]=='1')$ifchecked[2]='checked';
		if($titledetail[3]=='1')$ifchecked[3]='checked';
	}
	if($action=="move" || $action=="copy") 
	{
		for ($i=0; $i<$forumcount; $i++) 
		{
			$forumdetail=explode("|",$forumarray[$i]);
			if ($forumdetail[1]!="category" && $forumdetail[4]!=$fid)
			{
				if ($forumdetail[5]==0) 
					$mg_jumpforum.="<OPTION value=\"$forumdetail[4]\">$forumdetail[2]";
				else $mg_jumpforum.="<OPTION value=\"$forumdetail[4]\">&nbsp;|- $forumdetail[2]";
			}
		}
	}
	include PrintEot('masingle');footer();
}
$filename="$dbpath/$fid/list.php";
if ($_POST['action']=="move" || $_POST['action']=="copy")
{
	if (file_exists($filename))
	{
		list($toparray,$detaillst)=searchtop($filename,$db_linesize,$tid);
		if(is_array($detaillst)){
			if($_POST['action']=="move"){
				$writedb=implode("",$toparray).str_pad(' ',$db_linesize)."\n";
				writeselect($filename,$writedb,1,$db_linesize);
			}
			unset($toparray);
		}
		else{
			list($fp,$writearray,$detaillst,$fastwrite)=readsearch($filename,$tid,$db_linesize);
			if($_POST['action']=="move"){
				write_del($fp,$writearray,$fastwrite);
			}
			fclose($fp);
		}
		$nextname=shownextname($gotoboard);
		@copy("$dbpath/$fid/{$detaillst[5]}vote.php","$dbpath/$gotoboard/{$nextname}vote.php");
		if($_POST['action']=="copy"){
			@copy("$dbpath/$fid/$detaillst[5].php", "$dbpath/$gotoboard/$nextname.php");
		}elseif($_POST['action']=="move"){

			Move_topic($fid,$detaillst[5],$gotoboard,$nextname);

			@unlink("$dbpath/$fid/$detaillst[5].php");
			@unlink("$dbpath/$fid/{$detaillst[5]}vote.php");
		}
		$tpcdb=gets("$dbpath/$gotoboard/$nextname.php",200);
		$detaillst[1]=$fid;//�˹������� forum.phpҳ���ж���������Դ!
		$detaillst[5]=$nextname;
		$detaillst[8]=0;
		$getnewitem=str_pad(trim(implode("|",$detaillst)),$db_linesize)."\n";
		writeover("$dbpath/$gotoboard/list.php",$getnewitem,"ab");
		$tpcdetail=explode("|",$tpcdb);
		$statusdetail=explode("|",readover("$dbpath/$gotoboard/status.php"));
		$statusdetail[0]='<?die;?>';$statusdetail[1]=$tpcdetail[5];$statusdetail[2]=$tpcdetail[2];
		$statusdetail[3]=date($db_tformat,$timestamp);$statusdetail[4]="$gotoboard&tid=$nextname";
		$statusdetail[5]=$timestamp;
		$statusdetail[6]<0?$statusdetail[6]=$detaillst[6]+1:$statusdetail[6]+=$detaillst[6]+1;
		$statusdetail[7]<0?$statusdetail[7]=1:$statusdetail[7]++;
		$writestatus=implode("|",$statusdetail);
		writeover("$dbpath/$gotoboard/status.php",$writestatus);
		if ($_POST['action']=="move")
		{
			list($lasttid,$lasttitle,$lastauthor,$laststamp,$lasttime)=getnewstatus($fid);
			$statusdetail=explode("|",readover("$dbpath/$fid/status.php"));
			$statusdetail[1]=$lasttitle;$statusdetail[2]=$lastauthor;$statusdetail[3]=$lasttime;
			$statusdetail[4]=$lasttid;$statusdetail[5]=$laststamp;
			$statusdetail[6]<$detaillst[6]+1 ? $statusdetail[6]=0 : $statusdetail[6]-=$detaillst[6]+1;
			$statusdetail[7]<0 ? $statusdetail[7]=0 : $statusdetail[7]--;
			$writestatus=implode("|",$statusdetail);
			writeover("$dbpath/$fid/status.php",$writestatus);
		}
		else
		{
			$bbstpc++;
			$bbsatc+=$detaillst[6]+1;
			$writebbsatcdb="<?die;?>|$bbstpc|$bbsatc|$bbstoday|$bbsyestoday|$bbsmost|$bbspostcontrol|$bbsbirthcontrol|$bbsstar|$bbsrich|$bbslucher|$bbsbirthman|";
			writeover("data/bbsatc.php",$writebbsatcdb);
		}
		refreshto("forum.php?fid=$fid","�����ʽ�Ѿ��ɹ�ִ���˲���");
	}
}
//////////////////////////////////////////////////////////////////////
if(file_exists($atcname) && ($_POST['action']=="lock" || $_POST['action']=="unlock" || $_POST['action']=="digest" || $_POST['action']=="undigest"||$_POST['action']=="edit"))
{
	$articledb=openfile($atcname);
	$detail=explode("|",$articledb[0]);
	$T_P_C=explode(",",$detail[1]);
	if ($_POST['action']=="lock")
	{
		if ($T_P_C[1]==0 || $T_P_C[1]==2 || $T_P_C[1]==4)
			$T_P_C[1]=$T_P_C[1]+1;
	}
	elseif ($_POST['action']=="unlock")
	{
		if ($T_P_C[1]==1 || $T_P_C[1]==3 || $T_P_C[1]==5)
			$T_P_C[1]=$T_P_C[1]-1;
	}
	elseif ($_POST['action']=="digest") 
	{
		/*if($htfid==$detail[2])
		{
			showmsg("�������ǹ���ԱҲ���ɾ����Լ�������");
		}*/
		if($T_P_C[1]==0||$T_P_C[1]==1){
			$T_P_C[1]=$T_P_C[1]+2;
			$msgshowrvrc=floor($db_dtjhrvrc/10);
			dtchange($detail[2],$db_dtjhrvrc,"0",$db_dtjhmoney);
			$new="<?die;?>|������Ϣ|��ϲ��ϲ��|$timestamp|��ϲ $htfid ��������������Ϊ������ �������ӵ���߽���,�����������������{$msgshowrvrc},��Ǯ����{$db_dtjhmoney}.ϣ�����ٽ�������<a href=topic.php?fid=$fid&tid=$tid target=_blank>����鿴����</a>|0|\n";
			writeover("data/$msgpath/{$detail[2]}1.php",$new,"ab");
			!file_exists("data/digest/digest{$fid}.php") && $digsetfb="<?die?>\n$fid\n";
			writeover("data/digest/digest{$fid}.php","$digsetfb$tid|","ab");
		}
	} 
	elseif ($_POST['action']=="undigest")
	{ 
		if ($T_P_C[1]==2 || $T_P_C[1]==3){
			$T_P_C[1]=$T_P_C[1]-2;
			dtchange($detail[2],-$db_dtjhrvrc,"0",-$db_dtjhmoney);
			if(file_exists("data/digest/digest{$fid}.php")){
				$digestarray=openfile("data/digest/digest{$fid}.php");
				$digestdetail=explode("|",$digestarray[2]);
				foreach($digestdetail as $key =>$value){
					if($tid==$value)
						unset($digestdetail[$key]);
				}
				$digestarray[2]=implode("|",$digestdetail);
				$digestdb=implode("",$digestarray);
				writeover("data/digest/digest{$fid}.php",$digestdb);
			}
		}
	}
	elseif ($_POST['action']=="edit"){
		$T_P_C[2]="$title1~$title2~$title3~$title4~$title5~$title6~";
	}
	$detail[1]=implode(",",$T_P_C);
	$articledb[0]=implode("|",$detail);
	$articledb=implode("",$articledb);
	writeover($atcname,$articledb);
}
//////////////////////////////////////////////////////////////////
if (file_exists($filename) && $_POST['action']=="del")
{
	list($toparray,$detail)=searchtop($filename,$db_linesize,$tid);
	if(is_array($detail)){
		$writedb=implode("",$toparray).str_pad(' ',$db_linesize)."\n";
		writeselect($filename,$writedb,1,$db_linesize);
		unset($toparray);
	}
	else{
		list($fp,$writearray,$detail,$fastwrite)=readsearch($filename,$tid,$db_linesize);
		write_del($fp,$writearray,$fastwrite);
		fclose($fp);
	}
	if (file_exists("$dbpath/$db_recycle/list.php")){
		$nextname=shownextname($db_recycle);
	}
	else
	{
		$headfb=str_pad('<?die;?>',$db_linesize)."\n";
		$nextname="1";
	}
	if (file_exists($atcname) && $fid != $db_recycle && $db_recycle!=0)
	{
		$topic_dbname=$detail[5];
		$detail[1]=$fid;
		$detail[5]=$nextname;
		$newlistdb=str_pad(trim(implode("|",$detail)),$db_linesize)."\n";
		writeover("$dbpath/$db_recycle/list.php",$headfb.$newlistdb,'ab');
		Move_topic($fid,$tid,$db_recycle,$nextname);
		//@copy($atcname, "$dbpath/$db_recycle/$nextname.php");
		@copy("$dbpath/$fid/{$tid}vote.php","$dbpath/$db_recycle/{$nextname}vote.php");//ͶƱ
		$tpcdb=gets($atcname,200);
		$tpcdetail=explode("|",$tpcdb);
		$topic_author=$tpcdetail[2];
		$topic_name=$tpcdetail[5];
		//�޸�status.php�����Ϣ
		$statusdetail=Ex_plode("|",readover("$dbpath/$db_recycle/status.php"),9);
		$statustime=date($db_tformat,$timestamp);
		$statusdetail[6]<0 ? $statusdetail[6]=$detail[6]+1 : $statusdetail[6]+=$detail[6]+1;
		$statusdetail[7]<0 ? $statusdetail[7]=1 : $statusdetail[7]++;
		$writestatus="<?die;?>|$tpcdetail[5]|$tpcdetail[2]|$statustime|$db_recycle&tid=$nextname|$timestamp|$statusdetail[6]|$statusdetail[7]||";
		writeover("$dbpath/$db_recycle/status.php",$writestatus);
	}
	$msg_delrvrc=floor($db_dtdelrvrc/10);
	dtchange($topic_author,-$db_dtdelrvrc,"-1",-$db_dtdelmoney);
	//$replycount=$detail[6]+1;
	@unlink("$dbpath/$fid/$topic_dbname.php");
	if (file_exists("$dbpath/$fid/{$topic_dbname}vote.php"))
		@unlink("$dbpath/$fid/{$topic_dbname}vote.php");
	//�޸�status.php�����Ϣ
	list($lasttid,$lasttitle,$lastauthor,$laststamp,$lasttime)=getnewstatus($fid);
	$statusdetail=explode("|",readover("$dbpath/$fid/status.php"));
	$statusdetail[0]="<?die;?>";
	$statusdetail[1]=$lasttitle;$statusdetail[2]=$lastauthor;$statusdetail[3]=$lasttime;
	$statusdetail[4]=$lasttid;$statusdetail[5]=$laststamp;
	$statusdetail[6]<=$detail[6]+1 ? $statusdetail[6]=0 : $statusdetail[6]-=$detail[6]+1;
	$statusdetail[7]<=0 ? $statusdetail[7]=0 : $statusdetail[7]--;
	$writestatus=implode("|",$statusdetail);
	writeover("$dbpath/$fid/status.php",$writestatus);
	//�޸���̳��Ϣ
	$bbstpc--;
	$bbsatc-=$detail[6]+1;
	$writebbsatcdb="<?die;?>|$bbstpc|$bbsatc|$bbstoday|$bbsyestoday|$bbsmost|$bbspostcontrol|$bbsbirthcontrol|$bbsstar|$bbsrich|$bbslucher|$bbsbirthman|";
	writeover("data/bbsatc.php",$writebbsatcdb);

	
	$newlog_forum="<?die;?>|ɾ������|$fid||$topic_name|$topic_author|���ĵ�����|-$msg_delrvrc|-$db_dtdelmoney|$timestamp|$htfid|$onlineip|\n";
	writeover("data/log_forum.php",$newlog_forum,"ab");/*���µ���������.�޸�����*/

	refreshto("forum.php?fid=$fid","�����ʽ�Ѿ��ɹ�ִ���˲���");
}
if ($_POST['step'] && file_exists($filename))
{
	list($writetop,$edittop)=searchtop($filename,$db_linesize,$tid);//δ����д��
	if(is_array($edittop)){
		if($_POST['action']=="pushtopic")
		{
			$getnewitem=implode("|",$edittop);
			array_unshift($writetop,$getnewitem);
			$writedb=implode("",$writetop);
			writeselect($filename,$writedb,1,$db_linesize);
		}
		elseif($_POST['action']=="unheadtopic")
		{
			$writedb=implode("",$writetop).str_pad(' ',$db_linesize)."\n";
			writeselect($filename,$writedb,1,$db_linesize);
			$edittop[8]=0;
			$getnewitem=implode("|",$edittop);
			writeover($filename,$getnewitem,'ab');
		}
	}
	else{
		list($fp,$writearray,$detail,$fastwrite)=readsearch($filename,$tid,$db_linesize);
		if ($detail[5]==$tid)
		{
			if($_POST['action']=="pushtopic")
			{
				$getnewitem=implode("|",$detail);
				array_push($writearray,$getnewitem);
				$writedb=implode("",$writearray);
				fputs($fp,$writedb);
				fclose($fp);
			}
			elseif($_POST['action']=="headtopic")
			{
				
				$detail[8]=3;
				$getnewitem=implode("|",$detail);
				if(count($writetop)>=$db_topnum){

					showmsg('�ö��������Ѵﵽָ������������������');
				}
				array_unshift($writetop,$getnewitem);
				$writetopdb=implode("",$writetop);
				//echo$writetopdb;exit;

				write_del($fp,$writearray,$fastwrite);

				fseek($fp,$db_linesize+1,SEEK_SET);
				fputs($fp,$writetopdb);
				fclose($fp);
			}
			else
				fclose($fp);
		}
	}
}
refreshto("forum.php?fid=$fid","�������ӳɹ�");

?>