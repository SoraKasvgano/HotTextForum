<?php
!function_exists('readover') && exit('Forbidden');

$htfupload='';$attt=$timestamp;
for($i=1;$i<=4;$i++){

	if(is_array(${'atc_attachment'.$i})){
		$atc_attachment=${'atc_attachment'.$i}['tmp_name'];
		$atc_attachment_name=${'atc_attachment'.$i}['name'];
		$atc_attachment_size=${'atc_attachment'.$i}['size'];
	}else{
		$atc_attachment=${'atc_attachment'.$i};
		$atc_attachment_name=${'atc_attachment'.$i.'_name'};
		$atc_attachment_size=${'atc_attachment'.$i.'_size'};
	}
	if(!$atc_attachment)continue;

	$checkuplode=1;
	if (function_exists('is_uploaded_file')){
		if(!is_uploaded_file($atc_attachment))
		{
			$checkuplode=0;
		}
	}
	elseif(!($atc_attachment&& $atc_attachment!= 'none'&&$atc_attachment['error']!=4)) 
	{
		$checkuplode=0;
	}

	if ($reply_check==1 && $checkuplode==1){
		/**
		*���Ȩ���ж� ��� $allowupload �Ƿ�Ϊ���ж�
		*/
		if($allowupload && strpos($allowupload,",$groupid,")===false && $htfid!=$manager){

			showmsg('�Բ��𣬱�����趨ֻ���ض����û��ſ����ϴ��������뷵��');
		}

		/**
		*�û���Ȩ���ж�
		*/
		if($gp_ifupload==0){

			showmsg("���������û���û���ϴ�������Ȩ��");
		}
		$oldattachdb=explode("~",$htfdb[30]);
		if($oldattachdb[0]<$tdtime){
			$oldattachdb[0]=$tdtime;
			$oldattachdb[1]=0;
		}
		else{
			if($oldattachdb[1]>=$gp_allownum){
				showmsg("������ϴ��ĸ����Ѿ��ﵽָ������($gp_allownum ��)");
			}
			else{
				$oldattachdb[0]=$timestamp;
				$oldattachdb[1]++;
			}
		}
		$htfdb[30]=implode("~",$oldattachdb);
		$atc_downrvrc=${'atc_downrvrc'.$i};
		
		if(!is_numeric($atc_downrvrc) || strlen($atc_downrvrc)>8) $atc_downrvrc=0;
		
		//$htfdb define in global.php
		if ($htfdb[18]<$gp_uploadmoney){
			showmsg('���Ľ�ǮС��'.$gp_uploadmoney.',�������ϴ�������');
		}
		if ($atc_attachment_size>$db_uploadmaxsize*1024){
			showmsg('�ϴ��ļ�����ָ����С'.$db_uploadmaxsize.'K');
		}

		$available_type = explode(' ',trim($db_uploadfiletype));
		$attach_ext = substr(strrchr($atc_attachment_name,'.'),1);

		if($attach_ext == 'php' || empty($attach_ext) || !in_array($attach_ext,$available_type)){
			showmsg('���ϴ���ĳһ�ļ����Ͳ�����׼��');
		}

		$fileuplodeurl=$fid.'_'.$tid.'_'.$attt.'.'.$attach_ext;//д�����ӵĸ������� Ϊ��̬������׼��[:htfupload]Ϊ�ж��Ƿ��и��� Ϊ���ظ���������׼��
		$attt++;
		$source=$attachpath.'/'.$fileuplodeurl;//���id_�ļ���_ʱ��.����
		if(function_exists("move_uploaded_file") && is_uploaded_file($atc_attachment)){
			if(@move_uploaded_file($atc_attachment, $source)) 
			{
				$attach_saved = TRUE;
			}
		}elseif(@copy($atc_attachment, $source)){
			$attach_saved = TRUE;
		}
		if(!$attach_saved && is_readable($atc_attachment))
		{
			if($attcontent=readover($atc_attachment)){
				writeover($source,$attcontent);
				$attach_saved = TRUE;
			}
		}
		if($attach_saved){
			if (eregi("\.(gif|jpg|png|bmp|swf)$",$atc_attachment_name) && function_exists('getimagesize') && !getimagesize($source)){
				@unlink($source);
				showmsg("����������Ч,���鸽��!");
			}
			$atc_attachment_name=str_replace('~','-',str_replace(',','��',$atc_attachment_name));/*ȥ��,~*/
			if (eregi("\.(gif|jpg|png|bmp)$",$atc_attachment_name)){
				$htfupload.=$fileuplodeurl.','.$atc_attachment_name.',0,'.$atc_downrvrc.',img';
			}elseif (eregi("\.(zip|rar)$",$atc_attachment_name)){
				$htfupload.=$fileuplodeurl.','.$atc_attachment_name.',0,'.$atc_downrvrc.',zip';
			}elseif (eregi("\.txt$",$atc_attachment_name)){
				$htfupload.=$fileuplodeurl.','.$atc_attachment_name.',0,'.$atc_downrvrc.',txt';
			}else{
				$htfupload.=$fileuplodeurl.','.$atc_attachment_name.',0,'.$atc_downrvrc.',zip';
			}
			$htfupload.='~';
		}else{
			showmsg("�ϴ�����ʧ�ܣ��ռ���safe_mode�£�");
		}
	}
}
?>