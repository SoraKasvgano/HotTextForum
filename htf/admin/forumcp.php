<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=forumcp";
$forumfile="data/forumdata.php";
$forumarray=openfile($forumfile);
$forumcount=count($forumarray);
$nowtime=time();
@set_time_limit(0);
if(empty($action))
{	
	$onlyforum="";
	for($i=0; $i<$forumcount; $i++)
	{
		$detail=explode("|",$forumarray[$i]);
		
		if ($detail[1]!="category") {
			$onlyforum.="<option value=\"$detail[4]\">$detail[2]</option>";
		}
	}
	$onlyforum.="</select>";		
	eval("dooutput(\"".gettmp('adminforumcp')."\");");
}
elseif ($_POST['action']=="cleanup")
{		
	if (($method=="byamount" && empty($limitnum)) || ($method=="bydate" && empty($limitdate)) || ($method=="byauthor" && empty($author)))
	{
		adminmsg("����д���������Ա��ʽִ�ж���");				
	}
	if ($target=="all") 
	{
		for ($i=0; $i<$forumcount; $i++)
		{
			$detail=explode("|",$forumarray[$i]);
			if ($detail[1]!="category") art_delete($detail[4]);
		}
	}
	else 
	{
		art_delete($target);
	}
	update_indexsum();
	adminmsg("<span class=bold>����ִ�����</span>,�����Ѿ��ɹ�ִ��!!!");
}
elseif ($_POST['action']=="updatecount")
{
	//�Ӱ�� ���� ��������ҳһ�����ȫȨ���ƿ�ʼ

	for($i=0; $i<$forumcount; $i++) {
		$detail=explode("|",$forumarray[$i]);
		if ($detail[1]=="category")
			continue;
		if($detail[5]!=0)
		{
			$havechild[$detail[5]][]=$detail[4];
			if (file_exists("$dbpath/$detail[5]/status.php")) 
			{
				$statusarray=explode("|",readover("$dbpath/$detail[5]/status.php"));
				$statusarray[6]=0;$statusarray[8]=0;//��ʼ�����к����Ӱ����status.php����¼���Ӱ��������
				$writestatusfile=implode("|",$statusarray);
				writeover("$dbpath/$detail[5]/status.php",$writestatusfile);
			}
		}else{

		}
	}
	for($j=0; $j<$forumcount; $j++)
	{
		$detailwrite=explode("|",$forumarray[$j]);
		if ($detailwrite[1]==category)
			continue;
		if(!empty($havechild[$detailwrite[4]]))
			$detailwrite[13]=implode(",",$havechild[$detailwrite[4]]);
		else
			$detailwrite[13]="";
		$forumarray[$j]=implode("|",$detailwrite);
	}
	$writeforumdata=implode("",$forumarray);
	writeover("data/forumdata.php",$writeforumdata);
	//����
	if ($target=="all")
	{
		for ($i=0; $i<$forumcount; $i++)
		{
			$detail=explode("|",$forumarray[$i]);
			if ($detail[1]!="category") update_forumcount($detail[4],$detail[13]);
		}
	}
	else 
	{
		update_forumcount($target,"N");
	}
	update_indexsum();
	adminmsg("<span class=bold>����ִ�����</span>,�����Ѿ��ɹ�ִ��!!!");
}
elseif($action=='fixindex')
{
	if($recycle=='Y'){
		listrecreat($db_recycle,Y);
		update_forumcount($db_recycle,"N");
	}
	if($target=='all')
	{
		if(empty($start)){/*ֻ����һ�γ�ʼ��*/
			$start=0;
			for($i=0; $i<$forumcount; $i++) {
				$detail=explode("|",$forumarray[$i]);
				if ($detail[1]=="category")
					continue;
				if($detail[5]!=0)
				{
					if (file_exists("$dbpath/$detail[5]/status.php")) 
					{
						$statusarray=explode("|",readover("$dbpath/$detail[5]/status.php"));
						$statusarray[6]=0;$statusarray[8]=0;//��ʼ�����к����Ӱ����status.php����¼���Ӱ��������
						$writestatusfile=implode("|",$statusarray);
						writeover("$dbpath/$detail[5]/status.php",$writestatusfile);
					}
				}
			}
		}
		for($i=$start; $i<$forumcount; $i++)
		{
			$detail=explode("|",$forumarray[$i]);
			if($detail[1]!='category')
			{
				$start++;
				listrecreat($detail[4]);
				update_forumcount($detail[4],$detail[13]);
				$url="$basename&action=fixindex&target=$target&start=$start";
				adminmsg("�����<font color=red>{$detail[2]}</font>�������,�����Զ�������°��...",1);
			}
			else
				$start++;
		}
	}
	else{
		listrecreat($target);
		update_forumcount($target,"N");
	}
	update_indexsum();
	adminmsg('����ִ�����,�����Ѿ��ɹ�ִ��!!!');
}
function listrecreat($fid)
{
	global $dbpath,$db_linesize,$db_topnum;
	if (!is_dir("$dbpath/$fid")) return;
	$tpc_top=array();
	if($fp=@fopen("$dbpath/$fid/list.php","rb")){
		flock($fp,LOCK_SH);
		fseek($fp,$db_linesize+1,SEEK_SET);
		$num=0;
		while(!feof($fp)&&$num<10){
			$num++;
			$topdb=fgets($fp,100);
			$topdetail=explode("|",$topdb);
			if(strpos($topdb,"|")!==false &&$topdetail[8]>0&&$topdetail[4]==''){
				if(strlen($topdb)!=$db_linesize+1)
					$topdb=str_pad(trim($topdb),$db_linesize)."\n";
				$tpc_top[]=	$topdb;
				$filedb[]=$topdetail[5];
			}
		}
		fclose($fp);
	}
	$topspace=str_pad(' ',$db_linesize)."\n";
	$tpc_top=array_pad($tpc_top,$db_topnum,$topspace);
	$db=opendir("$dbpath/$fid/");
	while (false!==($getfile=readdir($db)))
	{
		if(ereg("^[0-9]{1,}\.php$",$getfile))
		{
			$filearray=explode(".",$getfile);
			$tid=$filearray[0];
			$getfiledb=openfile("$dbpath/$fid/$getfile");
			$detail=explode("|",$getfiledb[0]);
			list($rd_hit,$rd_islock,$null)=explode(",",$detail[1]);
			if(!file_exists("data/digest")) @mkdir("data/digest",0777);
			if($rd_islock==2||$rd_islock==3)
				$digestdb.="{$tid}|";
			if (empty($filedb) || !in_array($tid,$filedb))
			{
				$count=count($getfiledb);
				$replycount=$count-1;//�ظ�������
				$last_line  = $getfiledb[$count-1];
				$lstarray   =explode("|",$last_line);
				$lsp= "$lstarray[2],$lstarray[4]";
				if($lstarray[4]==''||strlen($lstarray[4])!=10 || !is_numeric($lstarray[4])){

					if(filesize("$dbpath/$fid/$getfile")<25) @unlink("$dbpath/$fid/$getfile");
					//@unlink("$dbpath/$fid/$getfile");
				}else{
					$lst_reply  = $count-1;
					if(strlen($lstarray[4])!=10)
					{
						$detail[4]='';
					}
					$key_art=str_pad("|||$detail[2]||$tid|$replycount|$lsp|0||",$db_linesize)."\n";
					$array[$key_art]=$lstarray[4];
				}
			}
		}
	}
	closedir($db);
	if($digestdb)writeover("data/digest/digest{$fid}.php","<?die;?>\n$fid\n$digestdb");
	@asort($array);//����������������
	$lst_array=@array_keys($array);
	$headfb=str_pad('<?die;?>',$db_linesize)."\n";
	array_unshift($tpc_top,$headfb);/*�����鿪ͷ����һ��������Ԫ*/
	if(!is_array($lst_array))$lst_array=array();
	$lst_array=array_merge($tpc_top,$lst_array);/*�ϲ�������������*/
	writelist("$dbpath/$fid/list.php",$lst_array);
}
function art_delete($id) 
{
	global $method,$limitnum,$limitdate,$author,$nowtime,$dbpath,$db_linesize;
	if (!file_exists("$dbpath/$id/status.php")) return;
	$listfile = "$dbpath/$id/list.php";
	$statusfile = "$dbpath/$id/status.php";
	$readsize=$db_linesize+1;$offset=$readsize*11;
	$topictominus=0;
	if ($method=="byamount")
	{
		$delamount=filesize($listfile)/$readsize-11-$limitnum;
		if($delamount<0)return;
		$delcount=0;
		$fp=fopen($listfile,"rb");
		flock($fp,LOCK_SH);
		$topdb=fread($fp,$offset);
		while(!feof($fp)){
			$delcount++;
			$listdb=fread($fp,$readsize);
			if($delcount<=$delamount){
				$detail = explode("|",$listdb);
				if(@unlink("$dbpath/$id/$detail[5].php")){
					@unlink("$dbpath/$id/{$detail[5]}vote.php");
					$topictominus=$topictominus+$detail[6]+1;
					$deltpcnum++;
				}
			}
			else{
				$listarray[]=$listdb;
			}
		}
		/*$writedb=$topdb.implode("",$listarray);
		rewind($fp);
		fputs($fp,$writedb);
		ftruncate($fp,strlen($writedb));*/
		fclose($fp);
		$count=count($listarray);
		$lastlist=$listarray[$count-1];
		array_unshift($listarray,$topdb);/*$topdb����11������.����һ�����д���*/
		writelist("$dbpath/$id/list.php",$listarray);
	}
	elseif ($method=="bydate") 
	{
		$limitdate=$limitdate*86400;
		$fp=fopen($listfile,"rb");
		flock($fp,LOCK_SH);
		$topdb=fread($fp,$offset);
		while(!feof($fp)){
			$listdb=fread($fp,$readsize);
			$detail = explode("|",$listdb);
			$lastdb=explode(",",$detail[7]);
			$lasttime=$lastdb[1];
			if ($nowtime-$lasttime>$limitdate)
			{
				if(@unlink("$dbpath/$id/$detail[5].php")){
					@unlink("$dbpath/$id/{$detail[5]}vote.php");
					$topictominus=$topictominus+$detail[6]+1;
					$deltpcnum++;
				}
			}
			else{
				$listarray[]=$listdb;
			}
		}
		fclose($fp);
		$count=count($listarray);
		$lastlist=$listarray[$count-1];
		array_unshift($listarray,$topdb);
		writelist("$dbpath/$id/list.php",$listarray);
	}
	elseif ($method=="byauthor"){
		$fp=fopen($listfile,"rb");
		flock($fp,LOCK_SH);
		$topdb=fread($fp,$offset);
		while(!feof($fp)){
			$listdb=fread($fp,$readsize);
			$detail = explode("|",$listdb);
			if($detail[3]==$author){
				if(@unlink("$dbpath/$id/$detail[5].php")){
					@unlink("$dbpath/$id/{$detail[5]}vote.php");
					$topictominus=$topictominus+$detail[6]+1;
					$deltpcnum++;
				}
			}
			else{
				$listarray[]=$listdb;
			}
		}
		fclose($fp);
		$count=count($listarray);
		$lastlist=$listarray[$count-2];/*ʹ��feofѭ�����鵹���ڶ���Ϊ������ֵ,��Ȼ���ǰ�������жϾ�û�������.��������������ٶ�*/
		array_unshift($listarray,$topdb);
		writelist("$dbpath/$id/list.php",$listarray);
	}
	$lastlistdb=explode("|",$lastlist);
	$newtpcdb=explode(",",$lastlistdb[7]);
	$lasttpc=explode("|",gets("$dbpath/$id/$lastlistdb[5].php",150));
	$statusarray=explode("|",readover($statusfile));
	$statusarray[1]=$lasttpc[5];$statusarray[2]=$newtpcdb[0];
	$statusarray[3]=date("Y-m-j g:i A",$newtpcdb[1]);
	$statusarray[4]="$id&tid=$lastlistdb[5]";$statusarray[5]=$laststamp;
	$statusarray[6]<$topictominus?$statusarray[6]=0:$statusarray[6]-=$topictominus;
	$statusarray[7]-=$deltpcnum;
	writeover($statusfile,"$statusarray[0]|$statusarray[1]|$statusarray[2]|$statusarray[3]|$statusarray[4]|$statusarray[5]|$statusarray[6]|$statusarray[7]|$statusarray[8]|$statusarray[9]");
}
function update_forumcount($id,$ch_id) 
{
	/*
	*���˼��:�Ӱ���������� ��8�����ݶ�,�Ӱ�������������ڵ�6�����ݶι���!!
	*/
	global $dbpath,$forumarray,$forumcount;
	//����ͳ�ư��������Ŀ
	$listfile = "$dbpath/$id/list.php";
	$statusfile = "$dbpath/$id/status.php";
	$listdb = openfile($listfile);
	$count = count($listdb);
	$atcnum=0;$topics=$count;
	for ($i=0; $i<$count; $i++) {
		if (strpos($listdb[$i],"|")!==false){
			$lastinfo=explode("|",$listdb[$i]);
			$atcnum+=$lastinfo[6]+1;/*+1Ϊ��������Ҳ��Ϊ������������������,$lastinfo[6]Ϊlist�еĵ�6�����ݶ�:�ظ���!*/
		}
		else
			$topics--;
	}
	if(file_exists($statusfile)) $statusarray=explode("|",readover($statusfile));
	list($lastauthor,$lasttime)=explode(",",$lastinfo[7]);
	if(is_numeric($lastinfo[5])){
		$use_date=date("Y-m-j g:i A",$lasttime);
		$lstfiledb=gets("$dbpath/$id/$lastinfo[5].php",120);
		$filearray=explode("|",$lstfiledb);
		if($ch_id==0||$ch_id=="") $statusarray[8]=0;
		writeover($statusfile,"<?die;?>|$filearray[5]|$lastauthor|$use_date|{$id}&tid={$lastinfo[5]}|$lasttime|$atcnum|$topics|$statusarray[8]|$statusarray[9]");
	}
	/*elseif(isset($lastinfo)){
		writeover($statusfile,"<?die;?>||||||$atcnum|$topics|$statusarray[8]|$statusarray[9]");
	}*/
	for($i=0; $i<$forumcount; $i++) {
		$detail=explode("|",$forumarray[$i]);
		if ($detail[1]=="category")
			continue;
		/*����û���Ӱ�������ж��˳�,������Ӱ������жԸ����������Ϣ*/
		if($detail[4]==$id && $detail[5]!=0 && $detail[5]!="")
		{
			//echo $detail[5];
			if(file_exists("$dbpath/$detail[5]/status.php"))
				$fathertemp=explode("|",readover("$dbpath/$detail[5]/status.php"));
			$fathertemp[6]+=$atcnum;/*���Ӱ����������븸��������������*/
			$fathertemp[8]+=$topics;/*���Ӱ������������븸���������еĵ�һ���ݶ���,�������Ϊ�����õ�һ���ݶ�,��Ϊthreadҳ�����������������Ҫ����7���ݶ���ʵ��,���Ա�����ʵ*/
			//echo "$detail[5] $fathertemp[8] <br>";
			writeover("$dbpath/$detail[5]/status.php","<?die;?>|$fathertemp[1]|$fathertemp[2]|$fathertemp[3]|$fathertemp[4]|$fathertemp[5]|$fathertemp[6]|$fathertemp[7]|$fathertemp[8]|$fathertemp[9]");
			break;
		}
	}
}
function update_indexsum()
{
	//�����������Ŀ��Ͳ�д�� bbsnew ���������ļ�
	global $forumarray,$forumcount,$dbpath;
	$thread=0;
	$htftopic=0;//��������
	for ($i=0; $i<$forumcount; $i++) 
	{
		$temp=explode("|",$forumarray[$i]);
		if ($temp[1]!="category" && file_exists("$dbpath/$temp[4]/status.php")) 
		{
			$statusarray=explode("|",readover("$dbpath/$temp[4]/status.php"));
			if($temp[5]==0)$thread+=$statusarray[6];
			$htftopic+=$statusarray[7];
		}
	}
	list($bbsfb,$bbstpc,$bbsatc,$bbstoday,$bbsyestoday,$bbsmost,$bbspostcontrol,$bbsbirthcontrol,$bbsstar,$bbsrich,$bbslucher,$bbsbirthman)=explode("|",readover("data/bbsatc.php"));
	if($bbsmost>5000000) $bbsmost=0;//����շ��������ж� 5000000����������
	if($bbstoday>5000000) $bbstoday=0;if($bbsyestoday>5000000) $bbsyestoday=0;
	$bbstpc=$htftopic;
	$bbsatc=$thread;
	$writebbsatcdb="<?die;?>|$bbstpc|$bbsatc|$bbstoday|$bbsyestoday|$bbsmost|$bbspostcontrol|$bbsbirthcontrol|$bbsstar|$bbsrich|$bbslucher|$bbsbirthman|";
	writeover("data/bbsatc.php",$writebbsatcdb);
}
?>