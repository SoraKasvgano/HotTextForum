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
		$get_style="<select name='stylefile'>";//�˴���stylefile�������з���ҳ�洫��
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
		$style_css=str_replace('$',"\$",$style_css[1]); //��ʾ $ 
		eval("dooutput(\"".gettmp('setstyles')."\");");
	}
	else
	{
		if ($job=="submit")
		{
			if($stylefile==$skin)
			{
				adminmsg("����ɾ��Ĭ�Ϸ��,���ȸ���Ĭ�Ϸ��");
			}
			if (file_exists("style/$stylefile.php"))
			{
				if(unlink("style/$stylefile.php"))
				{
					$msg="�ɹ�ɾ�����{$stylefile}";
					adminmsg($msg);
				}
				else
					adminmsg("ɾ�����ʧ��");
			}
			else 
			{
				adminmsg("�˷�񲻴���");
			}
		}
	}
}
if($action==editcss)
{
	$cssadd=readover("tmp/$skin/css.htm");
	$cssadd=explode('<!--css-->',$cssadd);
	$style_css=str_replace("$","\$",$cssadd[0].'<!--css-->'.$style_css.'<!--css-->'.$cssadd[2]);//��html��õ�$�ַ���
	$style_css = stripslashes($style_css);
	writeover("tmp/$skin/css.htm",$style_css);
	adminmsg("�ɹ��༭��̳���");
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
adminmsg("���з���ѳɹ�ת������ѡ������ȷֱ��ʡ�");
}
if($action==edit)
{
	if ($job!="submit")
	{
		include("./style/$stylefile.php");
		//$bgcolor=str_replace($imgpath,"\$imgpath",$bgcolor); ��ʾ $ ���Ǹ�����
		eval("dooutput(\"".gettmp('setstylesedit')."\");");
	}
	elseif ($job=="submit") {
//$setting[5]=str_replace("$","\$",$setting[5]);��html��õ�$�ַ���
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
	adminmsg("���ɹ��༭");
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
			adminmsg(" ���Ʋ���Ϊ�� ");
		}
		if (file_exists("style/$setting[0].php")) {adminmsg("�������Ѵ��ڣ�����ѡ����");}
		writeover("style/$setting[0].php",$stylecontent);
		$msg="�����ӷ��,���ٵ�imagesĿ¼�½���{$setting[0]}��������Ӧ��ͼƬ";
		adminmsg($msg);
	}
}
?>