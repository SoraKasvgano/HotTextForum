<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename="admin.php?adminjob=setstyles";
if(!$action)
{
	if ($job!="submit") 
	{
		if (empty($skin)) $skin=$db_defaultstyle;
		if (empty($db_defaultstyle)) $skin=htf;
		if (file_exists("style/$skin.php")) include("./style/$skin.php");
		else include("./style/htf.php");
		if(strpos($tablewidth,"%")!=false)
		$open_seecheck="checked";else $close_seecheck="checked";
		$get_style="<select name='stylefile'>";//此处的stylefile控制所有风格的页面传递
		$db=opendir("style/");
		while (false!==($skinfile=readdir($db))) 
		{
			if (($skinfile!=".") && ($skinfile!="..")  ) 
			{
				$skinfile=str_replace(".php","",$skinfile);
				$get_style.="<option value=$skinfile>$skinfile</option>";
			}
		}
		closedir($db);
		$get_style.="</select>";
		$style_css=readover("tmp/$skin/css.htm");
		$style_css=explode('<!--css-->',$style_css);
		$style_css=str_replace('$',"\$",$style_css[1]); //显示 $ 
		eval("dooutput(\"".gettmp('setstyles')."\");");
	}
	else
	{
		if ($job=="submit")
		{
			if($stylefile==$skin)
			{
				adminmsg("不能删除默认风格,请先更换默认风格");
			}
			if (file_exists("style/$stylefile.php"))
			{
				if(unlink("style/$stylefile.php"))
				{
					$msg="成功删除风格{$stylefile}";
					adminmsg($msg);
				}
				else
					adminmsg("删除风格失败");
			}
			else 
			{
				adminmsg("此风格不存在");
			}
		}
	}
}
if($action==editcss)
{
	$cssadd=readover("tmp/$skin/css.htm");
	$cssadd=explode('<!--css-->',$cssadd);
	$style_css=str_replace("$","\$",$cssadd[0].'<!--css-->'.$style_css.'<!--css-->'.$cssadd[2]);//从html里得到$字符串
	$style_css = stripslashes($style_css);
	writeover("tmp/$skin/css.htm",$style_css);
	adminmsg("成功编辑论坛风格");
}
if($action==see)
{
	$db=opendir("style/");
	if($setting_seecheck==1)
	{
		$setsee='90%';
		$msetsee='90%';
	}
	else
	{
		$setsee=900;
		$msetsee=900;
	}
while (false!==($stylefile=readdir($db))) { 
if ($stylefile!="." && $stylefile!=".." && $stylefile!="") { 
include("./style/$stylefile");
if($setting_seecheck==1) $setsee='95%';
if($setting_seecheck==0) $msetsee=925;
$stylecontent="<?
\$stylepath  =     '$stylepath';
\$tplpath  =     '$tplpath';
\$yeyestyle = '$yeyestyle';
\$tablecolor	=	'$tablecolor';//table
\$tablewidth	=	'$setsee';
\$mtablewidth=		'$msetsee';
\$forumcolorone	=	'$forumcolorone';
\$forumcolortwo	=	'$forumcolortwo';
\$threadcolorone	=	'$threadcolorone';
\$threadcolortwo	=	'$threadcolortwo';
\$readcolorone=	'$readcolorone';
\$readcolortwo=	'$readcolortwo';
\$maincolor =     '$maincolor';
";
	writeover("style/$stylefile",$stylecontent);
}
}
closedir($db);
adminmsg("所有风格已成功转换成你选择的优先分辨率。");
}
if($action==edit)
{
	if ($job!="submit")
	{
		include("./style/$stylefile.php");
		//$bgcolor=str_replace($imgpath,"\$imgpath",$bgcolor); 显示 $ 这是个例子
		eval("dooutput(\"".gettmp('setstylesedit')."\");");
	}
	elseif ($job=="submit") {
//$setting[5]=str_replace("$","\$",$setting[5]);从html里得到$字符串
$stylecontent="<?
\$stylepath  =     '$setting[0]';
\$tplpath  =     '$setting[1]';
\$yeyestyle = '$setting[3]';
\$tablecolor	=	'$setting[10]';//table
\$tablewidth	=	'$setting[11]';
\$mtablewidth=		'$setting[12]';
\$forumcolorone	=	'$setting[16]';
\$forumcolortwo	=	'$setting[17]';
\$threadcolorone	=	'$setting[18]';
\$threadcolortwo	=	'$setting[19]';
\$readcolorone=	'$setting[20]';
\$readcolortwo=	'$setting[21]';
\$maincolor =     '$setting[22]';
";
	writeover("style/$stylefile.php",$stylecontent);
	adminmsg("风格成功编辑");
}
}

if($action=='add')
{
	if ($job!="submit") {
	eval("dooutput(\"".gettmp('setstylesadd')."\");");
	}
	elseif ($job=="submit") {
	$stylecontent="<?
	\$stylepath  =     '$setting[0]';
	\$tplpath  =     '$setting[1]';
	\$yeyestyle = '$setting[3]';
	\$tablecolor	=	'$setting[10]';//table
	\$tablewidth	=	'$setting[11]';
	\$mtablewidth=		'$setting[12]';
	\$forumcolorone	=	'$setting[16]';
	\$forumcolortwo	=	'$setting[17]';
	\$threadcolorone	=	'$setting[18]';
	\$threadcolortwo	=	'$setting[19]';
	\$readcolorone=	'$setting[20]';
	\$readcolortwo=	'$setting[21]';
	\$maincolor =     '$setting[22]';
	";
		if (empty($setting[0])) 
		{
			adminmsg(" 名称不能为空 ");
		}
		if (file_exists("style/$setting[0].php")) {adminmsg("此名称已存在，请另选名称");}
		writeover("style/$setting[0].php",$stylecontent);
		$msg="风格添加风格,请速到images目录下建立{$setting[0]}并放上相应的图片";
		adminmsg($msg);
	}
}
?>