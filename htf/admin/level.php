<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=level";
if (empty($action))
{
	foreach($ltitle as $key=>$value)
	{
		$raw_title=rawurlencode($ltitle[$key]);
		if(is_numeric($key)){
			$memthread.="<tr><td bgcolor='$b' valign=middle align=center colspan=2>
				<span class=bold>�ȼ���{$key}</span ></td></tr>
				<td bgcolor='$b' valign=middle align=left width=40%>
				<span class=bold>�û�������</span ></td>
				<td bgcolor='$b' valign=middle align=left>
				<input type=text size=40 name='memtitle[$key]' value='$ltitle[$key]'>";
				$memthread.="<input type=button value='Ȩ�޿���' onclick=location.href='admin.php?adminjob=setgroup&status=$raw_title&key=$key'>";
				if($key!='0')$memthread.="&nbsp;&nbsp;&nbsp;&nbsp;<input type=button value='ɾ  ��'  onclick=\"return checkset('$basename&action=delgroup&delid=$key')\"></td></tr>";
			if(isset($lpic[$key]))
				$memthread.="
				<tr><td bgcolor='$b' valign=middle align=left width=40%>
				<span class=bold>�û���ͼ��</span ></td>
				<td bgcolor='$b' valign=middle align=left>
				<input type=text size=40 name='mempic[$key]' value='$lpic[$key]'></td>
				</tr>";
			if(isset($lpost[$key]))
				$memthread.="
				<tr><td bgcolor='$b' valign=middle align=left width=40%>
				<span class=bold>�������</span ></td><td bgcolor='F5F5FF' valign=middle align=left>
				<input type=text size=40 name='mempost[$key]' value='$lpost[$key]'></td></tr>";
		}elseif(strpos($key,'vip_')===false){
			$systhread.="<tr><td bgcolor='$b' valign=middle align=center colspan=2>
				<span class=bold>ϵͳͷ�Σ�{$key}</span ></td></tr>		
				<td bgcolor='$b' valign=middle align=left width=40%>
				<span class=bold>�û�������</span ></td>
				<td bgcolor='$b' valign=middle align=left>
				<input type=text size=40 name='systitle[$key]' value='$ltitle[$key]'><input type=button value='Ȩ�޿���' onclick=location.href='admin.php?adminjob=setgroup&status=$raw_title&key=$key'>";
			if(isset($lpic[$key]))
				$systhread.="
				<tr><td bgcolor='$b' valign=middle align=left width=40%>
				<span class=bold>�û���ͼ��</span ></td>
				<td bgcolor='$b' valign=middle align=left>
				<input type=text size=40 name='syspic[$key]' value='$lpic[$key]'></td>
				</tr>";
		}else{
			if(file_exists("data/vip/$key.php"))
				$vipinfo=explode("|",readover("data/vip/$key.php"));
			$vipthread.="<tr><td bgcolor='$b' valign=middle align=center colspan=2>
				<span class=bold>������ͷ�Σ�{$key}</span ></td></tr>		
				<td bgcolor='$b' valign=middle align=left width=40%>
				<span class=bold>�û�������</span ></td>
				<td bgcolor='$b' valign=middle align=left>
				<input type=text size=40 name='viptitle[$key]' value='$ltitle[$key]'><input type=button value='Ȩ�޿���' onclick=location.href='admin.php?adminjob=setgroup&status=$raw_title&key=$key'>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type=button value='ɾ  ��'  onclick=\"return checkset('$basename&action=delvip&delid=$key')\">";
			if(isset($lpic[$key]))
				$vipthread.="
					<tr><td bgcolor='$b' valign=middle align=left width=40%>
					<span class=bold>�û���ͼ��</span ></td>
					<td bgcolor='$b' valign=middle align=left>
					<input type=text size=40 name='vippic[$key]' value='$lpic[$key]'></td>
					</tr>";
			$vipthread.="<tr><td bgcolor='$b' valign=middle align=left width=40%>
					<span class=bold>�����û�(�û���֮����','�ָ�)</span ></td>
					<td bgcolor='$b' valign=middle align=left>
					<textarea  name='vipuser[$key]' rows=10 cols=60>$vipinfo[1]</textarea>
					</td></tr>";
		}
	}
	$vipthread && $vipthread="</table></td></tr></table><br><table width=95% align=center cellspacing=0 cellpadding=0 bgcolor=333333><tr><td><table width=100% cellspacing=1 cellpadding=3><form action='$basename' method='post'><input type=hidden name='action' value='vipedit'><tr><td class=head colspan=2><span class=bold>������༭</span></td></tr>"
	.$vipthread.
	"<tr bgcolor='$c'><td colspan=2 align=center ><input type=submit value='�� ��'><input type=reset value='�� ��'></tr></form>";
	eval("dooutput(\"".gettmp('admin_level')."\");");
}
elseif($action=="menedit") 
{
	foreach($mempost as $key=>$value){
		if(!is_numeric($value)){
			$value=20*pow(2,$key);
			$mempost[$key]=$value;
		}
	}
	$sysdb="<?php\n/**\n* ϵͳ��\n*/\n";
	$vipdb="\n/**\n* ������\n*/\n";
	$memdb="\n/**\n* ��Ա��\n*/\n";
	foreach($memtitle as $key=>$value)
	{
		$ltitledb.="\$ltitle[$key]    ='$value';\n";
		if(isset($mempic[$key]))
			$lpicdb.="\$lpic[$key]    ='$mempic[$key]';\n";
		if(isset($mempost[$key]))
			$lpostdb.="\$lpost[$key]    ='$mempost[$key]';\n";
	}
	foreach($ltitle as $key=>$value)
	{
		if(!is_numeric($key)){
			if(strpos($key,'vip_')===false){
				$systitledb.="\$ltitle['$key']    ='$value';\n";
				if(isset($lpic[$key]))
					$syspicdb.="\$lpic['$key']    ='$lpic[$key]';\n";
			}
			elseif(strpos($key,'vip_')!==false){
				$viptitledb.="\$ltitle['$key']    ='$value';\n";
				if(isset($lpic[$key]))
					$vippicdb.="\$lpic['$key']    ='$lpic[$key]';\n";
			}
		}
	}
	$sysdb.=$systitledb.$syspicdb;
	$vipdb.=$viptitledb.$vippicdb;
	$memdb.=$ltitledb.$lpicdb.$lpostdb;
	writeover("data/level.php",$sysdb.$vipdb.$memdb);
	adminmsg("��Ա�����óɹ�");
}
elseif($action=="sysedit")
{
	$sysdb="<?php\n/**\n* ϵͳ��\n*/\n";
	$vipdb="\n/**\n* ������\n*/\n";
	$memdb="\n/**\n* ��Ա��\n*/\n";
	foreach($systitle as $key=>$value)
		$sysdb.="\$ltitle['$key']    ='$value';\n";
	foreach($syspic as $key=>$value)
		$sysdb.="\$lpic['$key']    ='$value';\n";
	
	foreach($ltitle as $key=>$value)
	{
		if(is_numeric($key)){
			$memtitledb.="\$ltitle[$key]    ='$value';\n";
			if(isset($lpic[$key]))
				$mempicdb.="\$lpic[$key]    ='$lpic[$key]';\n";
			if(isset($lpost[$key]))
				$mempostdb.="\$lpost[$key]    ='$lpost[$key]';\n";
		}elseif(strpos($key,'vip_')!==false){
			$viptitledb.="\$ltitle['$key']    ='$value';\n";
			if(isset($lpic[$key]))
				$vippicdb.="\$lpic['$key']    ='$lpic[$key]';\n";
		}
	}
	$vipdb.=$viptitledb.$vippicdb;
	$memdb.=$memtitledb.$mempicdb.$mempostdb;
	writeover("data/level.php",$sysdb.$vipdb.$memdb);
	adminmsg("ϵͳ�����óɹ�");
}
elseif($action=="vipedit")
{
	$sysdb="<?php\n/**\n* ϵͳ��\n*/\n";
	$vipdb="\n/**\n* ������\n*/\n";
	$memdb="\n/**\n* ��Ա��\n*/\n";
	foreach($viptitle as $key=>$value)
		$vipdb.="\$ltitle['$key']    ='$value';\n";
	foreach($vippic as $key=>$value)
		$vipdb.="\$lpic['$key']    ='$value';\n";
	foreach($vipuser as $key=>$value){
		$value=trim($value);
		$vipinfo=explode("|",readover("data/vip/$key.php"));
		if($value){
			$vip_add=explode(",",$value);
			$vip_add=array_unique($vip_add);
			$value=implode(",",$vip_add);
			foreach($vip_add as $vip){
				if($vip){
					if(file_exists("$userpath/$vip.php")){
						$newvipinfo=readover("$userpath/$vip.php");
						$detail_u=explode("|",$newvipinfo);
						if(is_numeric($detail_u[5])||$detail_u[5]==$key){
							$detail_u[5]=$key;
							writeover("$userpath/$vip.php",implode("|",$detail_u));
						}
						else
							adminmsg("������ӷ���ͨ��ԱΪ�������Ա");
					}
					else{
						$value=str_replace($vip,'',$value);
						$error.="<br>�û�{$vip}������";
					}
				}
			}
		}
		writeover("data/vip/$key.php","<?die;?>|$value");
		if($vipinfo[1]){
			$viparray=explode(",",$vipinfo[1]);
			foreach($viparray as $vip_user){
				if($vip_user){
					if(strpos($value,$vip_user)===false){
						$delvip[]=$vip_user;
					}
				}
			}
			if($delvip){
				foreach($delvip as $vip){
					if(file_exists("$userpath/$vip.php")){
						$vipinfo=readover("$userpath/$vip.php");
						$detail_u=explode("|",$vipinfo);
						$detail_u[5]=getusergroup($vip,'Y');
						writeover("$userpath/$vip.php",implode("|",$detail_u));
					}
				}
			}
		}
	}
	foreach($ltitle as $key=>$value)
	{
		if(is_numeric($key)){
			$memtitledb.="\$ltitle[$key]    ='$value';\n";
			if(isset($lpic[$key]))
				$mempicdb.="\$lpic[$key]    ='$lpic[$key]';\n";
			if(isset($lpost[$key]))
				$mempostdb.="\$lpost[$key]    ='$lpost[$key]';\n";
		}elseif(strpos($key,'vip_')===false){
			$systitledb.="\$ltitle['$key']    ='$value';\n";
			if(isset($lpic[$key]))
				$syspicdb.="\$lpic['$key']    ='$lpic[$key]';\n";
		}
	}
	$memdb.=$memtitledb.$mempicdb.$mempostdb;
	$sysdb.=$systitledb.$syspicdb;
	writeover("data/level.php",$sysdb.$vipdb.$memdb);
	adminmsg("���������óɹ�$error");
}
elseif($action=="addmengroup")
{
	$sysdb="<?php\n/**\n* ϵͳ��\n*/\n";
	$vipdb="\n/**\n* ������\n*/\n";
	$memdb="\n/**\n* ��Ա��\n*/\n";
	foreach($ltitle as $key=>$value)
	{
		if(!is_numeric($key)){
			if(strpos($key,'vip_')===false){
				$systitledb.="\$ltitle['$key']    ='$value';\n";
				if(isset($lpic[$key]))
					$syspicdb.="\$lpic['$key']    ='$lpic[$key]';\n";
			}
			elseif(strpos($key,'vip_')!==false){
				$viptitledb.="\$ltitle['$key']    ='$value';\n";
				if(isset($lpic[$key]))
					$vippicdb.="\$lpic['$key']    ='$lpic[$key]';\n";
			}
		}
	}
	$vipdb.=$viptitledb.$vippicdb;
	$sysdb.=$systitledb.$syspicdb;
	foreach($ltitle as $key=>$value)
	{
		if(is_numeric($key)){
			$memtitledb.="\$ltitle[$key]    ='$value';\n";
			if(isset($lpic[$key]))
				$mempicdb.="\$lpic[$key]    ='$lpic[$key]';\n";
			if(isset($lpost[$key]))
				$mempostdb.="\$lpost[$key]    ='$lpost[$key]';\n";
		}
		$newkey=$key;
	}
	$newkey++;
	$memtitledb.="\$ltitle[$newkey]    ='$newmemtitle';\n";
	$mempicdb.="\$lpic[$newkey]    ='$newmempic';\n";
	$mempostdb.="\$lpost[$newkey]    ='$newmempost';\n";
	$memdb.=$memtitledb.$mempicdb.$mempostdb;
	writeover("data/level.php",$sysdb.$vipdb.$memdb);
	adminmsg("��Ա����ӳɹ�");
}
elseif($action=="addvipgroup"){
	if (!ereg ("[a-zA-Z]",$newkey)||strlen($newkey)>10)
		adminmsg("������ID������Ҫ��.");
	$viparray=explode(",",$vipuser);
	foreach($viparray as $vip){
		if(file_exists("$userpath/$vip.php")){
			$vipinfo=readover("$userpath/$vip.php");
			$detail_u=explode("|",$vipinfo);
			if(is_numeric($detail_u[5])){
				$detail_u[5]="vip_$newkey";
				writeover("$userpath/$vip.php",implode("|",$detail_u));
			}else
				adminmsg("������ӷ���ͨ��ԱΪ�������Ա");

		}
		else{
			$vipuser=str_replace($vip,'',$vipuser);
			$error.="<br>�û�{$vip}������";
		}
	}
	writeover("data/vip/vip_$newkey.php","<?die;?>|$vipuser");
	$sysdb="<?php\n/**\n* ϵͳ��\n*/\n";
	$vipdb="\n/**\n* ������\n*/\n";
	$memdb="\n/**\n* ��Ա��\n*/\n";
	foreach($ltitle as $key=>$value)
	{
		if(is_numeric($key)){
			$memtitledb.="\$ltitle[$key]    ='$value';\n";
			if(isset($lpic[$key]))
				$mempicdb.="\$lpic[$key]    ='$lpic[$key]';\n";
			if(isset($lpost[$key]))
				$mempostdb.="\$lpost[$key]    ='$lpost[$key]';\n";
		}
	}
	$memdb.=$memtitledb.$mempicdb.$mempostdb;
	foreach($ltitle as $key=>$value)
	{
		if(!is_numeric($key)){
			if(strpos($key,'vip_')===false){
				$systitledb.="\$ltitle['$key']    ='$value';\n";
				if(isset($lpic[$key]))
					$syspicdb.="\$lpic['$key']    ='$lpic[$key]';\n";
			}
			elseif(strpos($key,'vip_')!==false){
				$viptitledb.="\$ltitle['$key']    ='$value';\n";
				if(isset($lpic[$key]))
					$vippicdb.="\$lpic['$key']    ='$lpic[$key]';\n";
			}
		}
	}
	$viptitledb.="\$ltitle['vip_$newkey']    ='$newsystitle';\n";
	$vippicdb.="\$lpic['vip_$newkey']    ='$newsyspic';\n";
	$vipdb.=$viptitledb.$vippicdb;
	$sysdb.=$systitledb.$syspicdb;
	writeover("data/level.php",$sysdb.$vipdb.$memdb);
	adminmsg("��������ӳɹ�$error");
}
elseif($action=='delvip'){
	if(strpos($delid,'vip_')!==false){
		unset($ltitle[$delid]);
		unset($lpic[$delid]);
		@unlink("data/groupdb/group_$delid.php");
		$vipinfo=explode("|",readover("data/vip/$delid.php"));
		$viparray=explode(",",$vipinfo[1]);
		foreach($viparray as $vip){
			if(file_exists("$userpath/$vip.php")){
				$vipinfo=readover("$userpath/$vip.php");
				$detail_u=explode("|",$vipinfo);
				$detail_u[5]=getusergroup($vip,'Y');
				writeover("$userpath/$vip.php",implode("|",$detail_u));
			}
		}
		@unlink("data/vip/$delid.php");
		
		$sysdb="<?php\n/**\n* ϵͳ��\n*/\n";
		$vipdb="\n/**\n* ������\n*/\n";
		$memdb="\n/**\n* ��Ա��\n*/\n";
		foreach($ltitle as $key=>$value)
		{
			if(is_numeric($key)){
				$ltitledb.="\$ltitle[$key]    ='$value';\n";
				if(isset($lpic[$key]))
					$lpicdb.="\$lpic[$key]    ='$lpic[$key]';\n";
				if(isset($lpost[$key]))
					$lpostdb.="\$lpost[$key]    ='$lpost[$key]';\n";
			}
			else{
				if(strpos($key,'vip_')===false){
				$systitledb.="\$ltitle['$key']    ='$value';\n";
				if(isset($lpic[$key]))
					$syspicdb.="\$lpic['$key']    ='$lpic[$key]';\n";
				}
				else{
					$viptitledb.="\$ltitle['$key']    ='$value';\n";
					if(isset($lpic[$key]))
						$vippicdb.="\$lpic['$key']    ='$lpic[$key]';\n";
				}
			}
		}
		$sysdb.=$systitledb.$syspicdb;
		$vipdb.=$viptitledb.$vippicdb;
		$memdb.=$ltitledb.$lpicdb.$lpostdb;
		writeover("data/level.php",$sysdb.$vipdb.$memdb);
		adminmsg("{$delid} �û�����ɾ��");
	}
	else
		adminmsg('ɾ�����󣬸��û��鲻����');
}
elseif($action=="delgroup")
{
	if($delid==0)
		adminmsg('����ɾ�� 0 �û���');
	unset($ltitle[$delid]);
	unset($lpic[$delid]);
	unset($lpost[$delid]);
	@unlink("data/groupdb/group_$delid.php");
	$sysdb="<?php\n/**\n* ϵͳ��\n*/\n";
	$vipdb="\n/**\n* ������\n*/\n";
	$memdb="\n/**\n* ��Ա��\n*/\n";
	foreach($ltitle as $key=>$value)
	{
		if(is_numeric($key)){
			$ltitledb.="\$ltitle[$key]    ='$value';\n";
			if(isset($lpic[$key]))
				$lpicdb.="\$lpic[$key]    ='$lpic[$key]';\n";
			if(isset($lpost[$key]))
				$lpostdb.="\$lpost[$key]    ='$lpost[$key]';\n";
		}
		else{
			if(strpos($key,'vip_')===false){
				$systitledb.="\$ltitle['$key']    ='$value';\n";
				if(isset($lpic[$key]))
					$syspicdb.="\$lpic['$key']    ='$lpic[$key]';\n";
			}
			else{
				$viptitledb.="\$ltitle['$key']    ='$value';\n";
				if(isset($lpic[$key]))
					$vippicdb.="\$lpic['$key']    ='$lpic[$key]';\n";
			}
		}
	}
	$sysdb.=$systitledb.$syspicdb;
	$vipdb.=$viptitledb.$vippicdb;
	$memdb.=$ltitledb.$lpicdb.$lpostdb;
	writeover("data/level.php",$sysdb.$vipdb.$memdb);
	adminmsg("{$delid} �û�����ɾ��");
}
?>