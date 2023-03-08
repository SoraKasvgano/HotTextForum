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
		*版块权限判断 需加 $allowupload 是否为空判断
		*/
		if($allowupload && strpos($allowupload,",$groupid,")===false && $htfid!=$manager){

			showmsg('对不起，本版块设定只有特定的用户才可以上传附件，请返回');
		}

		/**
		*用户组权限判断
		*/
		if($gp_ifupload==0){

			showmsg("你所属的用户组没有上传附件的权限");
		}
		$oldattachdb=explode("~",$htfdb[30]);
		if($oldattachdb[0]<$tdtime){
			$oldattachdb[0]=$tdtime;
			$oldattachdb[1]=0;
		}
		else{
			if($oldattachdb[1]>=$gp_allownum){
				showmsg("你今天上传的附件已经达到指定个数($gp_allownum 个)");
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
			showmsg('您的金钱小于'.$gp_uploadmoney.',不可以上传附件。');
		}
		if ($atc_attachment_size>$db_uploadmaxsize*1024){
			showmsg('上传文件超过指定大小'.$db_uploadmaxsize.'K');
		}

		$available_type = explode(' ',trim($db_uploadfiletype));
		$attach_ext = substr(strrchr($atc_attachment_name,'.'),1);

		if($attach_ext == 'php' || empty($attach_ext) || !in_array($attach_ext,$available_type)){
			showmsg('您上传的某一文件类型不符合准则');
		}

		$fileuplodeurl=$fid.'_'.$tid.'_'.$attt.'.'.$attach_ext;//写入帖子的附件名称 为动态附件做准备[:htfupload]为判断是否有附件 为下载附件次数做准备
		$attt++;
		$source=$attachpath.'/'.$fileuplodeurl;//版块id_文件名_时间.类型
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
				showmsg("附件内容无效,请检查附件!");
			}
			$atc_attachment_name=str_replace('~','-',str_replace(',','，',$atc_attachment_name));/*去除,~*/
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
			showmsg("上传附件失败，空间在safe_mode下！");
		}
	}
}
?>