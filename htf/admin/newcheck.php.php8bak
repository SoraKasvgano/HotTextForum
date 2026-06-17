<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename='admin.php?adminjob=newcheck';
$newfile='data/newuser_cache.php';
if(!$action)
{
	if(file_exists($newfile)){
		$newuserarray=openfile($newfile);
		$count=count($newuserarray);
		if(!$newuserarray[$count-1])$count--;
		for($i=0;$i<$count;$i++){
			$detail=explode("|",$newuserarray[$i]);
			$newuser.="<tr>
					 <input type=hidden name='newarray[$i]'value=2>
					 <td bgcolor=$b><a href='admin.php?adminjob=setuser&action=view&setusername=$detail[1]'>$detail[1]</a></td>
					 <td bgcolor=$b>$detail[2]</td>
					 <td bgcolor=$b>$detail[3]</td>
					 <td bgcolor=$b><input type='checkbox' name='checkarray[]' value=$i checked></td>
					 <td bgcolor=$b><a href='$basename&action=admin&del=$i'>删</a></td>
					 </tr>";
		}
	}
	eval("dooutput(\"".gettmp('newcheck')."\");");
}
elseif($action=='admin')
{
	$newuserarray=openfile($newfile);
	$count=count($newuserarray);
	if(isset($del)){
		$detail=explode("|",$newuserarray[$del]);
		@unlink("$userpath/$detail[1].php");
		unset($newuserarray[$del]);
	}else{
		if(empty($checkarray)) adminmsg('没有指定会员');
		for($i=0;$i<$count;$i++)
		{
			if($newarray[$i]==2)
			{
				$detail=explode("|",$newuserarray[$i]);
				if(in_array($i,$checkarray)){
					changegroup($detail[1],0);
					unset($newuserarray[$i]);
				}
			}
		}
	}
	$newuserdb=implode("",$newuserarray);
	writeover($newfile,$newuserdb);
	adminmsg('操作成功');
}
elseif($action=='updatauser')
{
	if(file_exists("$userpath/$username.php"))
	{
		$userinfo=readover("$userpath/$username.php");
		$detail=explode("|",$userinfo);
		if($detail[5]=='newrg'){
			$newuser=readover($newfile);
			if(strpos($newuser,$username)===false){
				$regtime=date("Y-m-d H:i",$detail[8]);
				writeover($newfile,"<?die;?>|$username|$regtime||","ab");//丢失IP无法取回
			}
		}
		adminmsg('更新成功');
	}
	else
		adminmsg('无此用户');
}