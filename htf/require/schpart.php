<?php

!function_exists('readover') && exit('Forbidden');

for($i=$start; $i<$fcount; $i++)
{
	
	$num1=0;
	if($db=fopen("$dbpath/$searchedforum[$i]/list.php","rb")){
		flock($db,LOCK_SH);
		if($startnum!=0){
			$num1=$startnum;
			fseek($db,$startnum*($db_linesize+1),SEEK_SET);
		}
		while (!feof($db))
		{
			$schedatc=fgets($db,100);
			$temp=explode("|",$schedatc);
			$num1++;//�����Ѷ��������ӣ��������ӱ���������.
			$schednum++;
			if($sch_area=="T"){
				$atc_db=gets("$dbpath/$searchedforum[$i]/$temp[5].php",200);
				$atc_array=explode("|",$atc_db);
				$seaf=$atc_array[5];//$sch_area=="T"ֻ�������⣬$sch_area=="A"ֻ������������
			}
			else
				$seaf=$temp[3].'|';/*��������׼ȷƥ��*/
			if($sch_time!='ALL'){
				$lasttime=filemtime("$dbpath/$searchedforum[$i]/$getfile");
				if ($timestamp-$lasttime>$sch_time*86400)continue;
			}
			if($method=="or"){
				for ( $k = 0; $k < $keycount; $k++)
				{
					if ( strpos($seaf, $keywordarray[$k] ) !== false )
					{
						$fidtemp=$searchedforum[$i];
						$treadinfo.="$forumname[$fidtemp]|$fidtemp|$temp[5]|<--->\n";//���屣�������.
						$rstcount++;
						break;
					}
				}
			}
			elseif($method=="and"){
				$keyinatc=array();
				foreach ($keywordarray as $value){
					if(!in_array($value,$keyinatc))
						if ( strpos($seaf, $keywordarray[$k] ) !== false )$keyinatc[]=$keywordarray[$k];
				}
				if(count($keyinatc)==$keycount){
					$fidtemp=$searchedforum[$i];
					$treadinfo.="$forumname[$fidtemp]|$fidtemp|$temp[5]|<--->\n";//���屣�������.
					$rstcount++;
					$inarray=1;
					break;
				}
			}
			if($rstcount>=$maxresult)
			{$more=1;break;}
			if($schednum>=$db_schpernum)
			{
				$step++;
				if($treadinfo)
					writeover("./data/cache/$cachefile.txt",$treadinfo,"ab");//���û��弼�����������Ľ��.
				$keyword=rawurlencode($keyword);
				$cachefile=rawurlencode($cachefile);
				$url="search.php?keyword=$keyword&more=$more&page=$page&cache=$cachefile&startnum=$num1&start=$start&rstcount=$rstcount&step=$step&sch_time=$sch_time&method=$method&sch_area=$sch_area&seekfid=$seekfid";
				$already=$step*$db_schpernum;
				showmsg("������{$already}������,����������������......",$url);
			}
		}
		fclose($db);
	}
	$startnum=0;
	if($rstcount>=$maxresult)
	{break;}
	$start++;
}
if($treadinfo)
	writeover("./data/cache/$cachefile.txt",$treadinfo,"ab");
//*/