<?php
$ifecho = array
(
	'mah_jup1' =>'<!--',
	'mah_jup2' =>'<-->'
);
require "./global.php";
require "./require/dbmodify.php";
require './require/forum.php';
list($bbsfb,$bbstpc,$bbsatc,$bbstoday,$bbsyestoday,$bbsmost,$bbspostcontrol,$bbsbirthcontrol,$bbsstar,$bbsrich,$bbslucher,$bbsbirthman)=explode("|",readover("data/bbsatc.php"));
if(empty($forumcount)) list($forumcount,$forumarray)=getforumdb();
htf_forumcheck();

$secondname="$fid_name";
$secondurl="forum.php?fid=$fid";
if($groupid=='guest'){

	showmsg("����ȫ��֤ʧ��,�����Ѿ���¼���IP ��������ʧ,���ǽ�׷������");
}
if (empty($fid) || empty($tidarray)){

	showmsg("���Ϊ��,����������Ϊ��,�������ɾ�����������,��ʹ��<font color=red>���ӹ���</font>���е���ɾ��");
}
/*������֤*/
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

if (empty($_POST['step']))
{
	if($action=="digest"){
		!file_exists("data/digest/digest{$fid}.php") && $digsetfb="<?die?>\n$fid\n";
		$tiddb=implode("|",$tidarray);
		foreach($tidarray as $tid){
			$detail=explode("|",gets("$dbpath/$fid/$tid.php",100));
			$T_P_C=explode(",",$detail[1]);

			if($T_P_C[1]==0||$T_P_C[1]==1){
				$C=$T_P_C[1]+2;
				$msgshowrvrc=floor($db_dtjhrvrc/10);
				dtchange($detail[2],$db_dtjhrvrc,"0",$db_dtjhmoney);
				$new="<?die;?>|������Ϣ|��ϲ��ϲ��|$timestamp|��ϲ $htfid ��������������Ϊ������ �������ӵ���߽���,�����������������{$msgshowrvrc},��Ǯ����{$db_dtjhmoney}.ϣ�����ٽ�������<a href=topic.php?fid=$fid&tid=$tid target=_blank>����鿴����</a>|0|\n";
				writeover("data/$msgpath/{$detail[2]}1.php",$new,"ab");
				seekwrite("$dbpath/$fid/$tid.php",18,$C);
			}
		}
		writeover("data/digest/digest{$fid}.php","$digsetfb$tiddb|","ab");
		refreshto("forum.php?fid=$fid","���������������");
	}elseif($action=="undigest"){
		$digestarray=openfile("data/digest/digest{$fid}.php");
		$digestdetail=explode("|",$digestarray[2]);
		foreach($tidarray as $tid){

			$detail=explode("|",gets("$dbpath/$fid/$tid.php",100));
			$T_P_C=explode(",",$detail[1]);

			if (($T_P_C[1]==2 || $T_P_C[1]==3) && file_exists("data/digest/digest{$fid}.php")){
				$C=$T_P_C[1]-2;

				dtchange($detail[2],-$db_dtjhrvrc,"0",-$db_dtjhmoney);

				foreach($digestdetail as $key =>$value){
					if($tid==$value)
						unset($digestdetail[$key]);
				}

				seekwrite("$dbpath/$fid/$tid.php",18,$C);
			}
		}
		$digestarray[2]=implode("|",$digestdetail);
		$digestdb=implode("",$digestarray);
		writeover("data/digest/digest{$fid}.php",$digestdb);
		refreshto("forum.php?fid=$fid","ȡ�������������");
	}

	if($action=="del")
		$ma_whatdo="ɾ��";
	elseif ($action=="move") 
		$ma_whatdo="�ƶ�";
	elseif ($action=="copy") 
		$ma_whatdo="����";
	require "./header.php";
	$msg_guide=headguide($secondname,$secondurl);
	$count=count($tidarray);
	$tiddb=implode("_",$tidarray);
	if ($action=="move" || $action=="copy")
	{
		$mg_jumpforum="";
		$fcount=$forumcount;
		for($i=0; $i<$fcount; $i++) 
		{
			$forumdetail=explode("|",$forumarray[$i]);
			if ($forumdetail[1]!="category" && $forumdetail[4]!=$fid)
			{
				if ($forumdetail[5]==0) 
					$mg_jumpforum.="<OPTION value=$forumdetail[4]>$forumdetail[2]";
				else $mg_jumpforum.="<OPTION value=$forumdetail[4]>&nbsp;|- $forumdetail[2]";
			}
		}
	} 
	for ($i=0; $i<$count;$i++)
	{
		$tid=$tidarray[$i];
		$articlearray=gets("$dbpath/$fid/$tid.php",1024);
		list($tpc_fb,$tpc_covert,$tpc_author,$tpc_icon,$tpc_date,$tpc_title,$tpc_ip,$tpc_sign,$tpc_download,$tpc_rvrc,$tpc_buy,$tpc_from,$tpc_ifconvert,$tpc_email,$tpc_content,$tpc_null1)=explode("|",$articlearray);
		$tpc_date=date($db_tformat,$tpc_date);
		$tpc_title="<a href='topic.php?fid=$fid&tid=$tid'>$tpc_title</a>";
		$tid_item=$i+1;
		$ma_tidarray.="<tr align=center height=25 bgcolor=$threadcolorone><td width=30>$tid_item</td><td width=* bgcolor=$threadcolortwo align=left>$tpc_title</td><td width=85 ><a href=\"usercp.php?action=show&username=$tpc_author\">$tpc_author</a></td><td width=160>$tpc_date</td></tr>";
	}
	if ($action=="move" || $action=="copy")
	{
		$ifecho[mah_jup1]="";$ifecho[mah_jup2]="";
	}
	include PrintEot('mawhole');footer();
}
//ɾ�����ӿ�ʼ
$filename="$dbpath/$fid/list.php";
if ($_POST['step']  && $_POST['action']=="del" && file_exists($filename)) 
{
	$tidarray=explode("_",$tidarray);
	$tcount=count($tidarray);
	if ($tcount > 200){

		showmsg("��һ��ɾ����Ҫ����200��");
	}
	$replycount=0;
	$msg_delrvrc=floor($db_dtdelrvrc/10);
	//$upload_path=opendir("$attachpath/");
	$newlog_forum="";$tpcstatuscount=0;$atcstatuscount=0;
	if ($fid != $db_recycle && $db_recycle!=0 && file_exists("$dbpath/$db_recycle/list.php"))
	{
		$nextname=shownextname($db_recycle);
	} 
	else
		$nextname="1"; 
	$step=0;$topicnum=0;$oldlist_db=array();
	$fp=fopen($filename,"rb");
	flock($fp,LOCK_SH);
	while(!feof($fp)&&!empty($tidarray)){
		$step++;$iftop=0;
		$offset=-($db_linesize+1)*$step;
		fseek($fp,$offset,SEEK_END);
		$line=fgets($fp,100);
		$detail=explode("|",$line);
		if(in_array($detail[5],$tidarray))
		{
			$tid=$detail[5];
			$iftop=$detail[8];
			$key=array_search($detail[5],$tidarray);
			unset($tidarray[$key]);
			if ($fid != $db_recycle && $db_recycle!=0)
			{
				Move_topic($fid,$tid,$db_recycle,$nextname);
				//@copy("$dbpath/$fid/$tid.php", "$dbpath/$db_recycle/$nextname.php");
				@copy("$dbpath/$fid/{$tid}vote.php","$dbpath/$db_recycle/{$nextname}vote.php");//ͶƱ
				$tpcdb=gets("$dbpath/$fid/$tid.php",200);
				$tpcdetail=explode("|",$tpcdb);
				$topic_author=$tpcdetail[2];
				$topic_name=$tpcdetail[5];
				$topic_dbname=$detail[5];
				$detail[1]=$fid;
				$detail[5]=$nextname;
				$detail[8]=0;
				$newrecycledb.=str_pad(trim(implode("|",$detail)),$db_linesize)."\n";
				//�޸�status.php�����Ϣ
				$statuscount=$tcount-1;
				$tpcstatuscount++;
				$atcstatuscount+=$detail[6]+1;
				do{
					$nextname++;
				}while (file_exists("$dbpath/$db_recycle/$nextname.php"));
			}
			$topicnum++;
			$replycount=$replycount+$detail[6]+1;
			dtchange($topic_author,-$db_dtdelrvrc,"-1",-$db_dtdelmoney);
			@unlink("$dbpath/$fid/$tid.php");
			@unlink("$dbpath/$fid/{$tid}vote.php");

			//�����ռ�
			$newlog_forum.="<?die;?>|ɾ������|$fid||$topic_name|$topic_author|���ĵ�����|-$msg_delrvrc|-$db_dtdelmoney|$timestamp|$htfid|$onlineip|\n";
		}
		elseif($detail[8]==3){
			$oldlist_top[]=$line;
		}
		else{
			$oldlist_db[]=$line;
		}
		if($iftop==3)
			$oldlist_db[]=str_pad('',$db_linesize)."\n";
	}
	fclose($fp);
	$oldlist_db=array_merge($oldlist_db,$oldlist_top);
	writeoldlist($filename,$offset,$oldlist_db);
	//closedir($upload_path);
	if ($fid != $db_recycle && $db_recycle!=0)
	{
		writeover("$dbpath/$db_recycle/list.php",$newrecycledb,'ab');
		$statusdetail=Ex_plode("|",readover("$dbpath/$db_recycle/status.php"),9);
		$statusdetail[0]="<?die;?>";
		$statusdetail[1]=$tpcdetail[5];
		$statusdetail[2]=$tpcdetail[2];
		$statusdetail[3]=date($db_tformat,$timestamp);
		$nextname--;
		$statusdetail[4]="$db_recycle&tid=$nextname";
		$statusdetail[5]=$timestamp;
		$statusdetail[6]<=0 ? $statusdetail[6]=$atcstatuscount : $statusdetail[6]+=$atcstatuscount;
		$statusdetail[7]<=0 ? $statusdetail[7]=$tpcstatuscount : $statusdetail[7]+=$tpcstatuscount;

		$writestatus=implode("|",$statusdetail);
		writeover("$dbpath/$db_recycle/status.php",$writestatus);
	}
	writeover("data/log_forum.php",$newlog_forum,"ab");
	//�޸�status.php�����Ϣ
	list($lasttid,$lasttitle,$lastauthor,$laststamp,$lasttime)=getnewstatus($fid);
	$statusdetail=explode("|",readover("$dbpath/$fid/status.php"));
	$statusdetail[0]="<?die;?>";
	$statusdetail[1]=$lasttitle;$statusdetail[2]=$lastauthor;$statusdetail[3]=$lasttime;
	$statusdetail[4]=$lasttid;$statusdetail[5]=$laststamp;
	$statusdetail[6]<$replycount ?  $statusdetail[6]=0 : $statusdetail[6]-=$replycount;
	$statusdetail[7]<=$topicnum  ?  $statusdetail[7]=0 : $statusdetail[7]-=$topicnum;
	$writestatus=implode("|",$statusdetail);
	writeover("$dbpath/$fid/status.php",$writestatus);
	//�޸���̳��Ϣ
	$bbstpc-=$topicnum;
	$bbsatc-=$replycount;
	$writebbsatcdb="<?die;?>|$bbstpc|$bbsatc|$bbstoday|$bbsyestoday|$bbsmost|$bbspostcontrol|$bbsbirthcontrol|$bbsstar|$bbsrich|$bbslucher|$bbsbirthman|";
	writeover("data/bbsatc.php",$writebbsatcdb);
	refreshto("forum.php?fid=$fid","�����ʽ�Ѿ��ɹ�ִ���˲���");
}
//�ƶ�����
if ($_POST['step']  && ( $_POST['action']=="move" || $_POST['action']=="copy" )&& file_exists($filename))
{
 	$tidarray=explode("_",$tidarray);
 	$tcount=count($tidarray);
	if ($tcount > 200){
		showmsg("��һ�θ��ƻ��ƶ�����������Ҫ����200");
	}
	$mawhole_newlist="";
	$tpcstatuscount=0;$atcstatuscount=0;
	if (file_exists("$dbpath/$gotoboard/list.php"))
	{
		$nextname=shownextname($gotoboard);
	}
	else
		$nextname="1";
	$step=0;$end=0;$oldlist_db=array();
	$fp=fopen($filename,"rb");
	flock($fp,LOCK_SH);
	while(!feof($fp)&&!empty($tidarray)){
		$step++;$iftop=0;
		$offset=-($db_linesize+1)*$step;
		fseek($fp,$offset,SEEK_END);
		$line=fgets($fp,100);
		$detail=explode("|",$line);
		if($detail[5]!='' && in_array($detail[5],$tidarray))
		{
			//echo$detail[5].'<br>';
			$tid=$detail[5];
			$iftop=$detail[8];
			$key=array_search($detail[5],$tidarray);
			unset($tidarray[$key]);
			$detail[1]=$fid;//�˹������� forum.phpҳ���ж���������Դ!
			$detail[5]=$nextname;
			$detail[8]=0;/*���ƶ����ö�����Ϊ��ͨ��*/
			$getnewitem.=str_pad(trim(implode("|",$detail)),$db_linesize)."\n";			


			@copy("$dbpath/$fid/{$tid}vote.php","$dbpath/$gotoboard/{$nextname}vote.php");
			if($_POST['action']=="copy"){
				@copy("$dbpath/$fid/$tid.php", "$dbpath/$gotoboard/$nextname.php");
			}elseif($_POST['action']=="move"){

				Move_topic($fid,$tid,$gotoboard,$nextname);

				@unlink("$dbpath/$fid/$tid.php");
				@unlink("$dbpath/$fid/{$tid}vote.php");
			}

			$tpcdb=gets("$dbpath/$gotoboard/$nextname.php",200);
			$tpcdetail=explode("|",$tpcdb);
			$tpcstatuscount++;
			$atcstatuscount+=$detail[6]+1;
			do{
				$nextname++;
			}while (file_exists("$dbpath/$gotoboard/$nextname.php"));
		}
		elseif($detail[8]==3){
			$oldlist_top[]=$line;
		}
		else{
			$oldlist_db[]=$line;
		}
		if($iftop==3){
			$oldlist_db[]=str_pad('',$db_linesize)."\n";
		}
	}
	fclose($fp);
	$oldlist_db=array_merge($oldlist_db,$oldlist_top);
	if ($_POST['action']=="move"){
		writeoldlist($filename,$offset,$oldlist_db);
	}
	writeover("$dbpath/$gotoboard/list.php",$getnewitem,'ab');
	$statusdetail=explode("|",readover("$dbpath/$gotoboard/status.php"));
	$statusdetail[0]="<?die;?>";
	$statusdetail[1]=$tpcdetail[5];
	$statusdetail[2]=$tpcdetail[2];
	$statusdetail[3]=date($db_tformat,$timestamp);
	$nextname--;
	$statusdetail[4]="$gotoboard&tid=$nextname";
	$statusdetail[5]=$timestamp;
	$statusdetail[6]<=0 ? $statusdetail[6]=$atcstatuscount : $statusdetail[6]+=$atcstatuscount;
	$statusdetail[7]<=0 ? $statusdetail[7]=$tpcstatuscount : $statusdetail[7]+=$tpcstatuscount;
	$writestatus=implode("|",$statusdetail);
	writeover("$dbpath/$gotoboard/status.php",$writestatus);
	if ($_POST['action']=="move")
	{
		list($lasttid,$lasttitle,$lastauthor,$laststamp,$lasttime)=getnewstatus($fid);
		$statusdetail=explode("|",readover("$dbpath/$fid/status.php"));
		$statusdetail[1]=$lasttitle;$statusdetail[2]=$lastauthor;$statusdetail[3]=$lasttime;
		$statusdetail[4]=$lasttid;$statusdetail[5]=$laststamp;
		$statusdetail[6]<=$atcstatuscount?$statusdetail[6]=0:$statusdetail[6]-=$atcstatuscount;
		$statusdetail[7]<=$tpcstatuscount?$statusdetail[7]=0:$statusdetail[7]-=$tpcstatuscount;
		$writestatus=implode("|",$statusdetail);
		writeover("$dbpath/$fid/status.php",$writestatus);
	}
	if ($_POST['action']=="copy")
	{
		$bbstpc+=$tpcstatuscount;
		$bbsatc+=$atcstatuscount;
		$writebbsatcdb="<?die;?>|$bbstpc|$bbsatc|$bbstoday|$bbsyestoday|$bbsmost|$bbspostcontrol|$bbsbirthcontrol|$bbsstar|$bbsrich|$bbslucher|$bbsbirthman|";
		writeover("data/bbsatc.php",$writebbsatcdb);
	}
	refreshto("forum.php?fid=$fid","�����ʽ�Ѿ��ɹ�ִ���˲���");
}
function writeoldlist($filename,$offset,$oldlist_db){
	$fp=fopen($filename,"rb+");
	flock($fp,LOCK_EX);
	fseek($fp,$offset,SEEK_END);
	$oldlist_db=array_reverse($oldlist_db);
	$size=5000;//����ÿ�����д������ĳ���
	$count=floor(count($oldlist_db)/$size)+1;
	/*���������*/
	for($i=0;$i<$count;$i++){
		$array1=array_slice ($oldlist_db,$i*$size,$size);
		fputs($fp,implode("",$array1));
	}
	ftruncate($fp,ftell($fp));
	fclose($fp);
}
function seekwrite($filename,$offset,$C){
	if($fp=fopen($filename,"rb+")){
		flock($fp,LOCK_EX);
		fseek($fp,$offset);
		fputs($fp,$C);
		fclose($fp);
	}
}
?>