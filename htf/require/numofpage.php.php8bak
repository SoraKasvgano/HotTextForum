<?php

!function_exists('readover') && exit('Forbidden');

function numofpage($count,$page,$numofpage,$url)
{
	global $db_readperpage;
	if ($numofpage<=1)
		$fengye= "<span class=bold>本版只有一页</span>";
	else
	{
		$fengye="<a href=\"{$url}page=1\" title='第 一 页'><< </a>";
		$flag=0;
		for($i=$page-3;$i<=$page-1;$i++)
		{
			if($i<1) continue;
			$fengye.=" <a href={$url}page=$i>&nbsp;$i&nbsp;</a>";
		}
		$fengye.="&nbsp;&nbsp;<b>$page</b>&nbsp;";
		if($page<$numofpage)
		{
			for($i=$page+1;$i<=$numofpage;$i++)
			{
				$fengye.=" <a href={$url}page=$i>&nbsp;$i&nbsp;</a>";
				$flag++;
				if($flag==4) break;/*控制后面*/
			}
		}
		$fengye.=" <a href=\"{$url}page=$numofpage\" title='最后一页'> >></a>&nbsp;&nbsp;Pages: (  $numofpage total )";
	}
	return $fengye;
}
?>