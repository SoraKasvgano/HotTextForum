<?php

!function_exists('readover') && exit('Forbidden');

function numofpage($count,$page,$numofpage,$url)
{
	global $db_readperpage;
	if ($numofpage<=1)
		$fengye= "<span class=bold>����ֻ��һҳ</span>";
	else
	{
		$fengye="<a href=\"{$url}page=1\" title='�� һ ҳ'><< </a>";
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
				if($flag==4) break;/*���ƺ���*/
			}
		}
		$fengye.=" <a href=\"{$url}page=$numofpage\" title='���һҳ'> >></a>&nbsp;&nbsp;Pages: (  $numofpage total )";
	}
	return $fengye;
}
?>