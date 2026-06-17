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
<html><head><title>htf Board 安装程序</title>
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
<font face="黑体">1.x 文本论坛安装程序</font></font><hr noshade align="center" width="100%" size="1">
</td></tr>
<tr>
<td class='t' valign='top' align='left' colspan='2'>
欢迎来到 HTF Board 安装向导，安装前请仔细阅读 安装说明里的每处细节后才能开始安装。安装文件夹里同样提供了有关软件安装的说明，请您同样仔细阅读，以保证安装进程的顺利进行。
<hr noshade align="center" width="100%" size="1">
<?
if($step)
{
	?><b>注意:</b>
	<br>
	<span class='r'>蓝色表示安装正确进行.</span>
	<br>
	<span class='c'>红色表示安装发生错误.</span><hr noshade align="center" width="100%" size="1">
	<?
}?>
</td>
</tr>
<?
if(!$step)
{
	$htf_licence= <<<EOT
版权所有 (c) 2005

    感谢您选择 htf 论坛。希望我们的努力能为您提供一个高效快速和强大的 web 论坛解决方案。

    htf 英文全称为 Hot Text Forum，中文全称为 htf 论坛，以下简称 htf。
    
    htf 官方技术支持论坛为 http://www.htf.1m.cn。

    在开始安装htf之前，请务必仔细阅读本授权文档，在您确定符合授权协议的全部条件后，即可继续 htf 论坛的安装。即：您一旦开始安装 htf，即被视为完全同意本授权协议的全部内容，如果出现纠纷，我们将根据相关法律和协议条款追究责任。

    1.htf并非原创程序,但安全性,易用性要高。

    2.您可以查看 htf 的全部源代码，也可以根据自己的需要对其进行修改，但无论如何，即无论用途如何、是否改动、改动程度如何，只要 htf 程序的任何部分被包含在您修改后的系统中，都必须保留页脚处的 htf 名称和 http://www.htf.1m.cn 的链接。您修改后的代码，在没有获得许可的情况下，严禁公开发布或发售。

    3.用户出于自愿而使用本软件，我们不承诺对提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。

    4.对于且仅对于非营利性个人用户，htf是开放源代码的免费软件，欢迎您在原样完整保留全部版权信息和说明档的前提下，传播和转载本程序。

    5.安装 htf 建立在完全同意本授权协议的基础之上，因此而产生的纠纷，违反本协议的一方将承担全部民事与刑事责任。 

EOT;

	$htf_licence = str_replace('  ', '&nbsp; ', nl2br($htf_licence));
	?>
	<tr> 
	<td class='t' align=center><font color="#0000EE"><b>HTF Board 用户许可协议</b></font></td>
	</tr>
	<tr>
	<td class='t'><b><font color="#99ccff">&gt;</font><font color="#000000"> 请您务必仔细阅读下面的许可协议</font></b></td>
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
	<input type="submit" name="submit" value="同 意" >&nbsp;
	<input type="button" name="exit" value="不 同 意">
	</form>
	</td>
	</tr>
	<?
}
if($step==1) {
	$check=1;
	//实现程序创建userdir,forumdir目录,无须再对这两个目录设置‘777’属性
	if(file_exists('data')){
		@mkdir('userdir',0777);
		writeover('userdir/test.txt','test');
		if(!file_exists('userdir/test.txt')){
			clearstatcache();
			echo "<tr><td class='t' valign='top' align='left' colspan='2'><font size=3 color=red><b>安装失败,文件不可写入</b></font>:<br>由于空间安全模式打开,造成文件不可读写!<br> 解决方法(一) : 找空间商,要求关闭安全模式!即可 !!<br> 解决方法(二) :<br> &nbsp; &nbsp; &nbsp;1: 手动建立userdir, forumdir,forumdir/1 , forumdir/66 目录 <font color=red>并设置属性 777</font>。然后重新运行 install.php 完成安装各步骤! <br> &nbsp;  &nbsp; &nbsp;2: 安装完成后,在后台新建版块时,比如您建立新版块ID 为 2 ,需要先删除程序自动建立的forumdir/2 目录,然后自己建一个同名的目录!并设置属性 777 以此类推! 注意手动建版块目录后都必须进后台<font color=red>重建此版块的索引!!</font></td></tr>";
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
		$tpc_top=array();/*数组初始化*/
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
		$echo_nofile="<br><font color=red><b>特别提示：</b></font>如果您是只是利用 install.php 重置论坛创始人,上面的红色目录警告,不影响程序继续进行!";
	}
	echo"<tr><td class='t' valign='top' align='left' colspan='2'><b>当前状态：</b>检查论坛文件的可写性<hr noshade align=center width=100% size=1></td></tr>";
	$correct='<font class=r>OK</font>';
	$incorrect='<font class=c>777属性检测不通过</font>';
	$uncorrect='<font class=c>文件不存在请上传此文件</font>';
	//以后需要加入注册的那个文件验证
	$writeablefiletocheck=array(
	'acs','forumdir','img','session','data','userdir',
	'data/admin.php','data/banname.php','data/bbsatc.php','data/bbsnew.php','data/bbsonline.php','data/cache.php','data/config.php','data/dbreg.php','data/dbset.php',
	'data/forumdata.php','data/guest.php','data/ipbans.php','data/level.php','data/manager.php','data/newpost.php','data/bulletin.php','data/olcache.php','data/online.php','data/sharebbs.php','data/top.php',
	'tmp/htf/css.htm',
	'forumdir/66','forumdir/1',
	'data/admin','data/msgbox','style','data/cache','data/digest','style/htf.php');
	echo "<TR><td class='i' colspan='2' align='left'> <span style='color:#CC0000'>&gt;</span>检查必要目录和文件是否可写入，如果发生错误，请更改文件/目录写入属性 777 </td></tr><tr><td colspan=2 align=left class='t'>";
	echo "讨论区根目录 (htf目录) ....... <br>
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
		echo "<tr> <td class=t valign=top align=left colspan=2><b>当前状态：</b>检查论坛用户目录与文件目录的正确性 $echo_nofile <hr noshade align=center width=100% size=1></td></tr>";
		echo '<INPUT type=hidden value=3 name=step>';
		echo "<tr>
		<td class='i' colspan='2' align='left'><span style='color:#CC0000'>&gt;</span>设置创始人资料<br></td>
		</tr><tr>
		<td class='t' align='left' width='40%'>&nbsp;&nbsp;管理员用户名:</td>
		<td class='t' align='left' width='60%'><input type='text' name='INSTALL_NAME'></td>
		</tr><tr>
		<td class='t' align='left' width='40%'>&nbsp;&nbsp;管理员 Email:</td>
		<td class='t' align='left' width='60%'><input type='text' name='INSTALL_EMAIL'></td>
		</tr><tr>
		<td class='t' align='left' width='40%'>&nbsp;&nbsp;管理员密码:</td>
		<td class='t' align='left' width='60%'>
		<input type='password' name='INSTALL_PASS'></td>
		</tr><tr>
		<td class='t' align='left' width='40%'>&nbsp;&nbsp;重复密码:<br><br><br><br><br></td>
		<td class='t' align='left' width='60%'>
		<input type='password' name='INSTALL_PASS_TWO'>&nbsp;&nbsp;<br>&nbsp;<INPUT type=hidden value='$forumdir' name=forumdir><input type='submit' value='开始安装' s><br><br><br><br><br></td>
		<tr>";
		
	}else {
		echo "</tr><tr><td class='i' colspan='2' align='center'><input onclick='history.go(-1)' type='button' value='发生错误点击返回' style='font-family:Verdana;width:50%'></td><tr>";
	}
}elseif ($step==3){
	$check=1;
	echo '<TR><td class="i" colspan=2 align=left><span style="color:#CC0000">&gt;</span>最后：检查输入资料并写入</td></tr>';
	if ($INSTALL_PASS != $INSTALL_PASS_TWO) {
		echo '<tr><TD align=left class=c align=middle colspan=2>您所输入的2个密码不一致</TD></TR>';
		$check=0;
	}
	if ($check) {
		$showpwd=$INSTALL_PASS;
		$writepwd=md5($INSTALL_PASS);
		writeover('data/manager.php',"<? \$manager='$INSTALL_NAME'; \$manager_pwd='$writepwd';");
		/*
		*如果用install.php重新更新超管密码,而改了 $userpath 也没关系 仍旧不覆盖!
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
		echo '<tr><TD align=left class=r align=middle colSpan=2>OK，超级用户资料已经写入并已经注册成功</TD></TR>';
		echo "<tr><td class='i' colspan='2' align='left'><span style='color:#CC0000'>&gt;</span>恭喜您，HTF Board 论坛 安装成功！<br></td></tr><tr><td class='t' align='left' width='50%'>&nbsp;&nbsp;管理员账号：</td><td class='t' align='left' width='50%'><b>Name</b>: $INSTALL_NAME <b>密码为: $showpwd </b></td></tr><tr><td class='i' colspan='2' align='left'><span style='color:#CC0000'>&gt;</span><a href='admin.php'>点击这里进入后台</a><br><br></td></tr>";
	@rename("install.php","install.txt");
	}
}
?>
</form></table></div>

</body></html>
