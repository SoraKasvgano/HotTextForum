<?php
$star_action='nt';
require "./global.php";
require "./header.php";
$secondname="бшлЁ╧╚╦Ф";
$filename="data/bulletin.php";

if(!$action) $action=1;
if($action==1)
{
	if (file_exists($filename))
	{
		$msgarray=openfile($filename);
		$count=count($msgarray);		
		for($i=0;$i<$count;$i++)
		{
			list($fb,$notice[author],$notice[title],$notice[msg],$notice[stime],$notice[etime],$end)=explode("|",$msgarray[$i]);
			$notice[i]=$i;
			$notice[rawurl]=rawurlencode($notice[author]);
			$noticedb[]=$notice;
		}
	}	
	$msg_guide=headguide("$secondname");
	include PrintEot('bulletin');footer();
}
?>