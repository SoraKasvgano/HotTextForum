<?php

!function_exists('readover') && exit('Forbidden');

global $db_autochange,$picpath,$attachpath;
if($db_autochange==1)
{
	$imgdt=$timestamp+$db_hour;
	$attachdt=$imgdt+$db_hour*100;
	if(@rename($picpath,$imgdt) && @rename($attachpath,$attachdt))
	{
		$dbcontent="<? \n\$picpath='$imgdt';//图片目录名\n\$attachpath='$attachdt';//附件目录名\n";
		writeover("data/dbset.php",$dbcontent);
	}
	$nowtime=($timestamp-$tdtime)/3600;	
	$set_control=floor($nowtime/$db_hour)+1;
	if($set_control>24/$db_hour)$set_control=1;
	$setdb="<?die;?>|$set_control|$tdtime|$set_null";
	writeover("data/set_cache.php",$setdb);
}
?>