<?php

!function_exists('readover') && exit('Forbidden');

function vote($voteopts)
{
	global $dbpath,$ifview,$votedb,$action,$votesum,$state,$viewvoter,$fid,$tid,$htfid,$groupid,$admin_check;
 	$voteopts=explode("|",readover("$dbpath/$fid/{$tid}vote.php"));
	$votearray = unserialize($voteopts[1]);
	$votetype = $votearray['multiple'][0] ? 'checkbox' : 'radio';

	$print=	$state=""; $votesum=0;
	$vt_name=$vt_num=$voteid=$voter=array();
	if(!$ifview)$ifview='yes';
	$ifview=($viewvoter=='yes' ? 'no':'yes');
	
	foreach($votearray['options'] as $option){
		$voterr='';
		foreach($option[2] as $key =>$value){
			$viewvoter==yes && $voterr.="<span class=bold>$value</span>".' ';
			$allvoter[]=$value;
		}
		if($viewvoter==yes &&!$admin_check)	{
			showmsg("�Բ�����û�в鿴ͶƱ��Ա��Ȩ��");
		}
		$voter[]=$voterr;
		$vt_name[]=$option[0];
		$vt_num[]=$option[1];
		$votesum+=$option[1];
		if(in_array($htfid,$option[2]))
			$state='havevote';
	}
	
	foreach($vt_name as $key=>$value)
	{
		$vote['width']=floor(500*$vt_num[$key]/($votesum+1));
		$vote['name']=$value;
		$vote['num']=$vt_num[$key];
		$vote['voter']=$voter[$key];

		$votedb[]=$vote;
	}
	$votesum=count(array_unique($allvoter));
	if ($groupid!='guest')
	{
		if ($state=='havevote' && $action!='modify') 
		{
			$state="&nbsp;* <b>���Ѿ����������ͶƱ,<a href='topic.php?fid=$fid&tid=$tid&action=modify'>�޸�ͶƱ</a></b>";
		}
		else
		{
			if($action=='modify')
				$modifyadd="<input type=hidden value='modify' name=voteaction>";

			$votearray['multiple'][0]&&$state="��ѡ������".$votearray['multiple'][1]."&nbsp;&nbsp;";
			foreach($votearray['options'] as $key=>$option)
			{
				list($sel_name) =explode(",",$vt_sel[$i]);
				$state.=(" �� <input type=$votetype value=\"$key\" name=voteid[]>$option[0]");
			}
			$state.="$modifyadd<input type=hidden value=\"$fid\" name=fid><input type=hidden value=\"$tid\" name=tid>";
			$state.="&nbsp;&nbsp;<input type=\"submit\" value=\"��ҲͶ\">";
		}
	}
	else
		$state="&nbsp;* <b>�Բ�������δ��¼���޷�����ͶƱ</b>";
	
}
?>