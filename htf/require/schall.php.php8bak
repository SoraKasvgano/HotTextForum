<?php
!function_exists('readover') && exit('Forbidden');
for($i=$start; $i<$fcount; $i++){
	$num1=0;
	if($db=fopen("$dbpath/$searchedforum[$i]/list.php","rb")){
		flock($db,LOCK_SH);
		if($startnum!=0){
			$num1=$startnum;
			fseek($db,$startnum*($db_linesize+1),SEEK_SET);
		}
		while (!feof($db)){
			$schedatc=fgets($db,100);
			$schedatc=explode("|",$schedatc);
			$num1++;//控制已读帖子帖子，避免帖子被二次搜索.
			if(is_numeric($schedatc[5])){
				$getfile="$schedatc[5].php";
				$schednum++;
				if($sch_time!='ALL'){
					$lasttime=filemtime("$dbpath/$searchedforum[$i]/$getfile");
					if ($timestamp-$lasttime>$sch_time*86400)continue;
				}
				if($method=="or"){
					if($fp=fopen('./'.$dbpath.'/'.$searchedforum[$i].'/'.$getfile,"rb")){//定位指针技术搜索帖子。
						flock($fp,LOCK_SH);
						$inarray=0;
						while (!feof($fp)&&$inarray==0){
							$seaf=fread($fp,8192);
							for ( $k = 0; $k < $keycount; $k++){
								if(strpos($seaf, $keywordarray[$k] )!==false){
									$fidtemp=$searchedforum[$i];
									$treadinfo.="$forumname[$fidtemp]|$fidtemp|$schedatc[5]|<--->\n";//缓冲保存的内容.
									$rstcount++;
									$inarray=1;
									break;
								}
							}
						}
						fclose($fp);
					}
				}
				elseif($method=="and"){
					if($fp=fopen("$dbpath/$searchedforum[$i]/$getfile","rb")){//定位指针技术搜索帖子。
						flock($fp,LOCK_SH);
						$keyinatc=array();
						while (!feof($fp)){
							$seaf=fread($fp,8192);
							for ( $k = 0; $k < $keycount; $k++){
								if(!in_array($keywordarray[$k],$keyinatc))
									if ( strpos($seaf, $keywordarray[$k] ) !== false )$keyinatc[]=$keywordarray[$k];
							}
							if(count($keyinatc)==$keycount){
								$fidtemp=$searchedforum[$i];
								$treadinfo.="$forumname[$fidtemp]|$fidtemp|$schedatc[5]|<--->\n";//缓冲保存的内容.
								$rstcount++;
								break;
							}
						}
						fclose($fp);
					}
				}
				if($rstcount>=$maxresult){
					$more=1;break;
				}
				if($schednum>=$db_schpernum){
					fclose($db);
					$step++;
					if($treadinfo)
						writeover("./data/cache/$cachefile.txt",$treadinfo,"ab");//采用缓冲技术保存搜索的结果.
					$keyword=rawurlencode($keyword);
					$cachefile=rawurlencode($cachefile);
					$url="search.php?keyword=$keyword&more=$more&page=$page&cache=$cachefile&startnum=$num1&start=$start&rstcount=$rstcount&step=$step&sch_time=$sch_time&method=$method&sch_area=$sch_area&seekfid=$seekfid";
					$already=$step*$db_schpernum;
					showmsg("已搜索{$already}个帖子,正在搜索余下帖子......",$url);
				}
			}
		}
		fclose($db);
	}
	$startnum=0;
	if($rstcount>=$maxresult){break;}
	$start++;
}

if($treadinfo)
	writeover("./data/cache/$cachefile.txt",$treadinfo,"ab");
?>