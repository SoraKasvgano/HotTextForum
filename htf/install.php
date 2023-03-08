<?
/*
	[htf Board] install.php - installation of htf Board

	Version: 1.0.0 
	Author: htf
	Copyright: htf Board (www.htf.1m.cn)
	Last Modified: 2005/7/24 13:35
*/
empty($db_debug) && error_reporting(0);
$magic_quotes_gpc = get_magic_quotes_gpc();
$register_globals = @ini_get('register_globals');
if(!$register_globals || !$magic_quotes_gpc)
{
	@extract($HTTP_POST_FILES, EXTR_SKIP); 
	@extract($HTTP_POST_VARS, EXTR_SKIP); 
	@extract($HTTP_GET_VARS, EXTR_SKIP); 
}
$PHP_SELF=$HTTP_SERVER_VARS['PHP_SELF'];
function readover($filename,$method="rb")
{
	$handle=fopen($filename,$method);
	flock($handle,LOCK_SH);
	$filedata=fread($handle,filesize($filename));
	fclose($handle);
	return $filedata;
}
function writeover($filename,$data,$method="wb")
{
	$handle=fopen($filename,$method);
	flock($handle,LOCK_EX);
	fputs($handle,$data);
	fclose($handle);
}
?>
<html><head><title>htf Board ��װ����</title>
<meta http-equiv=Content-Type content="text/html; charset=gb2312">
    <style type="text/css">
    .t {font-family: Verdana, Arial, Sans-serif;font-size  : 12px;padding-left: 10px;font-weight: normal;line-height: 150%;color : #333366;}
    .r {font-family: Arial, Sans-serif;font-size  : 12px;font-weight: normal;line-height: 200%;color : #0000EE;}
    .c {font-family: Arial, Sans-serif;font-size  : 12px;font-weight: normal;line-height: 200%;color : #EE0000;}
    .h {font-family: Arial, Sans-serif;padding-top: 5px;padding-left: 10px;font-size  : 20px;font-weight: bold;color : #000000;}
    .i {font-family: Arial, Sans-serif;padding-top: 5px;padding-left: 10px;font-size  : 14px;font-weight: bold;color : #000000;}
	table {width   : 80%;align         : center;vertical-align: top;background-color: #e8f4ff;}
	    </style>

<body bgcolor=#739ACE leftmargin=0 topmargin=5 marginwidth="0" marginheight="0">
<div align="center">
<table width="95%" cellspacing=0 cellpadding=0 bgcolor=#f5f5f5 border=0 style="background-color: #FFFFFF">
<form action=install.php method=post>
<tr>
<td class=h valign=top align=left colspan=2><span 
style="COLOR: #cc0000">&gt;&gt;</span> <font color="#739ACE">HTF Board
<font face="����">1.x �ı���̳��װ����</font></font><hr noshade align="center" width="100%" size="1">
</td></tr>
<tr>
<td class='t' valign='top' align='left' colspan='2'>
��ӭ���� HTF Board ��װ�򵼣���װǰ����ϸ�Ķ� ��װ˵�����ÿ��ϸ�ں���ܿ�ʼ��װ����װ�ļ�����ͬ���ṩ���й������װ��˵��������ͬ����ϸ�Ķ����Ա�֤��װ���̵�˳�����С�
<hr noshade align="center" width="100%" size="1">
<?
if($step)
{
	?><b>ע��:</b>
	<br>
	<span class='r'>��ɫ��ʾ��װ��ȷ����.</span>
	<br>
	<span class='c'>��ɫ��ʾ��װ��������.</span><hr noshade align="center" width="100%" size="1">
	<?
}?>
</td>
</tr>
<?
if(!$step)
{
	$htf_licence= <<<EOT
��Ȩ���� (c) 2005

    ��л��ѡ�� htf ��̳��ϣ�����ǵ�Ŭ����Ϊ���ṩһ����Ч���ٺ�ǿ��� web ��̳���������

    htf Ӣ��ȫ��Ϊ Hot Text Forum������ȫ��Ϊ htf ��̳�����¼�� htf��
    
    htf �ٷ�����֧����̳Ϊ http://www.htf.1m.cn��

    �ڿ�ʼ��װhtf֮ǰ���������ϸ�Ķ�����Ȩ�ĵ�������ȷ��������ȨЭ���ȫ�������󣬼��ɼ��� htf ��̳�İ�װ��������һ����ʼ��װ htf��������Ϊ��ȫͬ�Ȿ��ȨЭ���ȫ�����ݣ�������־��ף����ǽ�������ط��ɺ�Э������׷�����Ρ�

    1.htf����ԭ������,����ȫ��,������Ҫ�ߡ�

    2.�����Բ鿴 htf ��ȫ��Դ���룬Ҳ���Ը����Լ�����Ҫ��������޸ģ���������Σ���������;��Ρ��Ƿ�Ķ����Ķ��̶���Σ�ֻҪ htf ������κβ��ֱ����������޸ĺ��ϵͳ�У������뱣��ҳ�Ŵ��� htf ���ƺ� http://www.htf.1m.cn �����ӡ����޸ĺ�Ĵ��룬��û�л����ɵ�����£��Ͻ������������ۡ�

    3.�û�������Ը��ʹ�ñ���������ǲ���ŵ���ṩ�κ���ʽ�ļ���֧�֡�ʹ�õ�����Ҳ���е��κ���ʹ�ñ���������������������Ρ�

    4.�����ҽ����ڷ�Ӫ���Ը����û���htf�ǿ���Դ���������������ӭ����ԭ����������ȫ����Ȩ��Ϣ��˵������ǰ���£�������ת�ر�����

    5.��װ htf ��������ȫͬ�Ȿ��ȨЭ��Ļ���֮�ϣ���˶������ľ��ף�Υ����Э���һ�����е�ȫ���������������Ρ� 

EOT;

	$htf_licence = str_replace('  ', '&nbsp; ', nl2br($htf_licence));
	?>
	<tr> 
	<td class='t' align=center><font color="#0000EE"><b>HTF Board �û����Э��</b></font></td>
	</tr>
	<tr>
	<td class='t'><b><font color="#99ccff">&gt;</font><font color="#000000"> ���������ϸ�Ķ���������Э��</font></b></td>
	</tr>
	<tr>
	<td class='t'>
	<?=$htf_licence?>
	</td>
	</tr>
	<tr>
	<td align="center">
	<br>
	<form method="post" action="<?=$PHP_SELF?>">
	<input type="hidden" name="step" value="1">
	<p>
	<input type="submit" name="submit" value="ͬ ��" >&nbsp;
	<input type="button" name="exit" value="�� ͬ ��">
	</form>
	</td>
	</tr>
	<?
}
if($step==1) {
	$check=1;
	//ʵ�ֳ��򴴽�userdir,forumdirĿ¼,�����ٶ�������Ŀ¼���á�777������
	if(file_exists('data')){
		@mkdir('userdir',0777);
		writeover('userdir/test.txt','test');
		if(!file_exists('userdir/test.txt')){
			clearstatcache();
			echo "<tr><td class='t' valign='top' align='left' colspan='2'><font size=3 color=red><b>��װʧ��,�ļ�����д��</b></font>:<br>���ڿռ䰲ȫģʽ��,����ļ����ɶ�д!<br> �������(һ) : �ҿռ���,Ҫ��رհ�ȫģʽ!���� !!<br> �������(��) :<br> &nbsp; &nbsp; &nbsp;1: �ֶ�����userdir, forumdir,forumdir/1 , forumdir/66 Ŀ¼ <font color=red>���������� 777</font>��Ȼ���������� install.php ��ɰ�װ������! <br> &nbsp;  &nbsp; &nbsp;2: ��װ��ɺ�,�ں�̨�½����ʱ,�����������°��ID Ϊ 2 ,��Ҫ��ɾ�������Զ�������forumdir/2 Ŀ¼,Ȼ���Լ���һ��ͬ����Ŀ¼!���������� 777 �Դ�����! ע���ֶ������Ŀ¼�󶼱������̨<font color=red>�ؽ��˰�������!!</font></td></tr>";
			@rmdir('userdir');
			exit;
		}else{
			@unlink('userdir/test.txt');
		}
		clearstatcache();
		$dirarray=array('forumdir','forumdir/1','forumdir/66');
		$dataarray=array(
		'forumdir/1/list.php',
		'forumdir/1/status.php',
		'forumdir/66/list.php',
		'forumdir/66/status.php'
		);
		$db_linesize=70;
		$tpc_top=array();/*�����ʼ��*/
		$topspace=str_pad(' ',$db_linesize)."\n";
		$tpc_top=array_pad($tpc_top,10,$topspace);
		$headfb=str_pad('<?die;?>',$db_linesize)."\n";
		array_unshift($tpc_top,$headfb);
		$list_i=implode("",$tpc_top);
		$status_i='<?die;?>||||||0|0|0|0';
		unset($tpc_top);
		$datawritearray=array($list_i,$status_i,$list_i,$status_i);
		foreach($dirarray as $value){
			@mkdir($value,0777);
		}
		foreach($dataarray as $key =>$value){
			if(!file_exists($value)){
				writeover($value,$datawritearray[$key]);
			}
		}
	}else{
		$echo_nofile="<br><font color=red><b>�ر���ʾ��</b></font>�������ֻ������ install.php ������̳��ʼ��,����ĺ�ɫĿ¼����,��Ӱ������������!";
	}
	echo"<tr><td class='t' valign='top' align='left' colspan='2'><b>��ǰ״̬��</b>�����̳�ļ��Ŀ�д��<hr noshade align=center width=100% size=1></td></tr>";
	$correct='<font class=r>OK</font>';
	$incorrect='<font class=c>777���Լ�ⲻͨ��</font>';
	$uncorrect='<font class=c>�ļ����������ϴ����ļ�</font>';
	//�Ժ���Ҫ����ע����Ǹ��ļ���֤
	$writeablefiletocheck=array(
	'acs','forumdir','img','session','data','userdir',
	'data/admin.php','data/banname.php','data/bbsatc.php','data/bbsnew.php','data/bbsonline.php','data/cache.php','data/config.php','data/dbreg.php','data/dbset.php',
	'data/forumdata.php','data/guest.php','data/ipbans.php','data/level.php','data/manager.php','data/newpost.php','data/bulletin.php','data/olcache.php','data/online.php','data/sharebbs.php','data/top.php',
	'tmp/htf/css.htm',
	'forumdir/66','forumdir/1',
	'data/admin','data/msgbox','style','data/cache','data/digest','style/htf.php');
	echo "<TR><td class='i' colspan='2' align='left'> <span style='color:#CC0000'>&gt;</span>����ҪĿ¼���ļ��Ƿ��д�룬�����������������ļ�/Ŀ¼д������ 777 </td></tr><tr><td colspan=2 align=left class='t'>";
	echo "��������Ŀ¼ (htfĿ¼) ....... <br>
	";
	$count=count($writeablefiletocheck);
	for ($i=0; $i<$count; $i++) {
		echo "$writeablefiletocheck[$i] ....... ";
		if(!file_exists($writeablefiletocheck[$i])) echo $uncorrect;
		elseif(is_writable($writeablefiletocheck[$i])){echo $correct;}
		else{ echo $incorrect; $check=0; }
		echo "<br>";
	}
	echo "</TD></TR>";
	if ($check) {
		echo "<tr> <td class=t valign=top align=left colspan=2><b>��ǰ״̬��</b>�����̳�û�Ŀ¼���ļ�Ŀ¼����ȷ�� $echo_nofile <hr noshade align=center width=100% size=1></td></tr>";
		echo '<INPUT type=hidden value=3 name=step>';
		echo "<tr>
		<td class='i' colspan='2' align='left'><span style='color:#CC0000'>&gt;</span>���ô�ʼ������<br></td>
		</tr><tr>
		<td class='t' align='left' width='40%'>&nbsp;&nbsp;����Ա�û���:</td>
		<td class='t' align='left' width='60%'><input type='text' name='INSTALL_NAME'></td>
		</tr><tr>
		<td class='t' align='left' width='40%'>&nbsp;&nbsp;����Ա Email:</td>
		<td class='t' align='left' width='60%'><input type='text' name='INSTALL_EMAIL'></td>
		</tr><tr>
		<td class='t' align='left' width='40%'>&nbsp;&nbsp;����Ա����:</td>
		<td class='t' align='left' width='60%'>
		<input type='password' name='INSTALL_PASS'></td>
		</tr><tr>
		<td class='t' align='left' width='40%'>&nbsp;&nbsp;�ظ�����:<br><br><br><br><br></td>
		<td class='t' align='left' width='60%'>
		<input type='password' name='INSTALL_PASS_TWO'>&nbsp;&nbsp;<br>&nbsp;<INPUT type=hidden value='$forumdir' name=forumdir><input type='submit' value='��ʼ��װ' s><br><br><br><br><br></td>
		<tr>";
		
	}else {
		echo "</tr><tr><td class='i' colspan='2' align='center'><input onclick='history.go(-1)' type='button' value='��������������' style='font-family:Verdana;width:50%'></td><tr>";
	}
}elseif ($step==3){
	$check=1;
	echo '<TR><td class="i" colspan=2 align=left><span style="color:#CC0000">&gt;</span>��󣺼���������ϲ�д��</td></tr>';
	if ($INSTALL_PASS != $INSTALL_PASS_TWO) {
		echo '<tr><TD align=left class=c align=middle colspan=2>���������2�����벻һ��</TD></TR>';
		$check=0;
	}
	if ($check) {
		$showpwd=$INSTALL_PASS;
		$writepwd=md5($INSTALL_PASS);
		writeover('data/manager.php',"<? \$manager='$INSTALL_NAME'; \$manager_pwd='$writepwd';");
		/*
		*�����install.php���¸��³�������,������ $userpath Ҳû��ϵ �Ծɲ�����!
		*/
		require("data/config.php");
			if(!file_exists("$userpath/$INSTALL_NAME.php")){
			$timestamp=time();
			writeover("$userpath/$INSTALL_NAME.php","<?die;?>|$INSTALL_NAME|$writepwd|$INSTALL_EMAIL|1|manager|0.gif||$timestamp||||||||0|1000|1000|$timestamp|$timestamp||1|||||1|0|||0|||||||||");

			$forumnewdb = "data/forumnew.php";
			if (file_exists($forumnewdb)) $forumnewarray=readover($forumnewdb);
			$forumnewdetail=explode("|",$forumnewarray);
			$forumnewdetail[2]++;
			$forumnewdetail[1]=$INSTALL_NAME;
			$writedb=implode("|",$forumnewdetail);
			writeover($forumnewdb,$writedb);
			writeover('data/userarray.php',$INSTALL_NAME."\n","ab");
		}
		echo '<tr><TD align=left class=r align=middle colSpan=2>OK�������û������Ѿ�д�벢�Ѿ�ע��ɹ�</TD></TR>';
		echo "<tr><td class='i' colspan='2' align='left'><span style='color:#CC0000'>&gt;</span>��ϲ����HTF Board ��̳ ��װ�ɹ���<br></td></tr><tr><td class='t' align='left' width='50%'>&nbsp;&nbsp;����Ա�˺ţ�</td><td class='t' align='left' width='50%'><b>Name</b>: $INSTALL_NAME <b>����Ϊ: $showpwd </b></td></tr><tr><td class='i' colspan='2' align='left'><span style='color:#CC0000'>&gt;</span><a href='admin.php'>�����������̨</a><br><br></td></tr>";
	@rename("install.php","install.txt");
	}
}
?>
</form></table></div>

</body></html>
