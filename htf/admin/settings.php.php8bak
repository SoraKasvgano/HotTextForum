<?php

!function_exists('adminmsg') && exit('Forbidden');

$basename='admin.php?adminjob=settings';
require './data/dbreg.php';
if ($action!='unsubmit') 
{
	!$db_tformat && $db_tformat='Y-m-j g:i A';
	$db_onlinetime/=60;
	$db_cvtime/=60;
	$db_dtpostrvrc/=10;
	$db_dtjhrvrc/=10;
	$db_dtdelrvrc/=10;
	$db_regrvrc/=10;
	$db_whybbsclose=str_replace('<br />',"\n",$db_whybbsclose);
	$db_welcomemsg=str_replace('<br />',"\n",$db_welcomemsg);
	$db_rgpermit=str_replace('<br />',"\n",$db_rgpermit);
	//$db_dtreplyrvrc回复不用转换
	$fg=opendir('style/');
	$choseskin="<option value=$db_defaultstyle>$db_defaultstyle</option>";
	while (false!==($userskin=readdir($fg))) 
	{
		$userskin=str_replace(".php","",$userskin);
		if (($userskin!=".") && ($userskin!="..") && ($userskin!=$db_defaultstyle)) 
		{
			$choseskin.="<option value=$userskin>$userskin</option>";
		}
	}
	closedir($fg);
	if (file_exists($picpath) && !is_writeable($picpath)) $imgdisabled='disabled';
	if (file_exists($attachpath) && !is_writeable($attachpath)) $attdisabled='disabled';/*防止目录不可写入时.管理员进行修改.导致错误*/
	if ($db_hour) $ifselected[$db_hour]='selected';
	if ($db_postallowtime) $regcheck[$db_postallowtime]='selected';
	if ($db_bbsifopen) $bbsifopen_open="CHECKED"; else $bbsifopen_close="CHECKED";
	if ($db_htfpic['pic']) $signpic_open="CHECKED"; else $signpic_close="CHECKED";
	if ($db_htfpic['flash']) $signflash_open="CHECKED"; else $signflash_close="CHECKED";
	if ($db_htfpost['pic']) $postpic_open="CHECKED"; else $postpic_close="CHECKED";
	if ($db_htfpost['flash']) $postflash_open="CHECKED"; else $postflash_close="CHECKED";
	if ($db_htfpost['mpeg']) $postmpeg_open="CHECKED"; else $postmpeg_close="CHECKED";
	if ($db_htfpost['iframe']) $postiframe_open="CHECKED"; else $postiframe_close="CHECKED";
	if ($db_htfreply['pic']) $replypic_open="CHECKED"; else $replypic_close="CHECKED";
	if ($db_htfreply['flash']) $replyflash_open="CHECKED"; else $replyflash_close="CHECKED";
	if ($db_signhtfcode) $signhtfcode_open="checked"; else $signhtfcode_close="checked";//签名中 htf 代码
	if ($db_replysendmail) $replysendmail_open="checked"; else $replysendmail_close="checked";//用户文章被回复是否发送邮件
	if ($db_footertime) $footertime_open="checked"; else $footertime_close="checked";//是否在页脚显示程序运行时间
	if ($db_threadonline) $threadonline_open="checked"; else $threadonline_close="checked";//是否在版块内显示在线用户
	if ($db_showonline) $showonline_open="checked"; else $showonline_close="checked";//是否在topic.php页面显示在线用户	
	if ($db_indexonline) $indexonline_open="checked"; else $indexonline_close="checked";//是否在是否在首页显示在线用户
 	if ($db_indexshowbirth) $indexshowbirth_open="checked"; else $indexshowbirth_close="checked";//首页显示今天生日会员
	if ($db_regpopup) $regpopup_open="checked";else $regpopup_close="checked";//注册提示窗
	if ($db_indexlink) $indexlink_open="checked";else $indexlink_close="checked";//是否显示友情链接
	if ($db_indexstar) $indexstar_open="checked";else $indexstar_close="checked";//是否显示今日明星,庄主,幸运儿
	if ($db_indexmqshare) $indexmqshare_open="checked";else $indexmqshare_close="checked";//是否滚动显示友情链接
 	if ($db_indexliuyan) $indexliuyan_open="checked";else $indexliuyan_close="checked";//首页显示留言本
	if ($db_allowregister) $allowregister_open="checked";else $allowregister_close="checked";//允许新用户注册
	if ($db_regdetail) $regdetail_open="checked";else $regdetail_close="checked";//注册时显示详细注册信息
	if ($db_emailcheck) $emailcheck_open="checked";else $emailcheck_close="checked";//新用户注册需通过email激活用户
	if ($db_regsendmsg) $regsendmsg_open="checked";else $regsendmsg_close="checked";//新用户注册发送短消息
	if ($db_ifcheck) $regifcheck_open="checked";else $regifcheck_close="checked";//新用户注册是否需要验证
	if ($db_allowsameip==Y) $regallowsameip_open="checked";else $regallowsameip_close="checked";//是否开启同一IP24小时只能注册一次
	if ($db_regsendemail) $regsendemail_open="checked";else $regsendemail_close="checked";//新用户注册发送电子邮件
	if ($db_ifonlinetime) $ifonlinetime_open="checked";else $ifonlinetime_close="checked";//记录会员在线时间
	if ($db_todaypost) $todaypost_open="checked";else $todaypost_close="checked";//显示今日昨日发贴数
	if ($db_reg) $reg_close="checked";else $reg_open="checked";//注册时显示许可协议
	if ($db_showmenu) $showmenu_open="checked";else $showmenu_close="checked";//首页显示娱乐导航栏
	if ($db_showguest) $showguest_open="checked";else $showguest_close="checked";//首页显示在线游客
	if ($db_indexshowsong) $indexshowsong_open="checked";else $indexshowsong_close="checked";//显示最新帖子 和点歌台
	if ($db_threademotion) $threademotion_open="checked"; else $threademotion_close="checked";//分版块帖子导航里显示表情符
	if ($db_threadshowpost) $threadshowpost_open="checked"; else $threadshowpost_close="checked";//帖子里显示快速发表主题
	if ($db_ifjump) $ifjump_open="checked"; else $ifjump_close="checked";//是否使用自动跳转
	if ($db_indexfmlogo==1) $indexfmlogo_open="checked";elseif($db_indexfmlogo==2)$indexfmlogo_status="checked";else $indexfmlogo_close="checked";//自定义首页各版块的图片logo
	if ($db_obstart) $obstart_open="checked";else $obstart_close="checked";//打开gzip压缩
	if ($db_autoimg) $autoimg_open="checked";else $autoimg_close="checked";//是否使用自动贴图
	if ($db_ipfrom)$ipfrom_open="checked";else $ipfrom_close="checked";//是否显示IP来源
	if ($db_autochange) $autochange_open="checked"; else $autochange_close="checked";//自动更改图片链
	if ($db_ipcheck) $ipcheck_open="checked"; else $ipcheck_close="checked";//开启ip验证
	if ($db_rglower) $rglower_open="checked"; else $rglower_close="checked";//注册id区分大小写
	if ($db_today) $today_open="checked"; else $today_close="checked";
	eval("dooutput(\"".gettmp('setting')."\");");
}
elseif ($action=="unsubmit")
{
	$config[88]=ieconvert($config[88]);
	$config[89]=ieconvert($config[89]);
	$config[2]=ieconvert($config[2]);
	if ($userpath<>$datebase[0]) rename("$userpath","$datebase[0]");
	if ($dbpath<>$datebase[1])   rename($dbpath,$datebase[1]);
	if ($config[56] && !file_exists("{$dbpath}/$config[56]")) mkdir("{$dbpath}/$config[56]",0777);
	if(!ereg("^http",$datebase[2]))//!file_exists("$datebase[2]")主要为了加强管理员纠错能力
		@rename($picpath,$datebase[2]);
	elseif($picpath<>$datebase[2])
		adminmsg("对不起,更改目录发生错误,您不能使用含有http 字符的图片目录或你所要更改的目录已经存在");
	if ($config[77]>12) adminmsg("系统稳定性考虑,请不要超过 12 字节");
	if ($attachpath<>$datebase[3]) rename($attachpath,$datebase[3]);
	if ($msgpath<>$datebase[4]) rename($msgpath,$datebase[4]);
	if (!ereg("^[0-9]{1,}",$datebase[5]))
		adminmsg("图片链防盗参数必须为数字");
	if (!ereg("^[0-9]{1,}",$config[96]))
		adminmsg("新注册用户发帖控制时间必须为数字");
	if (!ereg("^http",$datebase[6]) && $datebase[6]!='N')
		adminmsg("使用跨台图片链必须以http开头");
	//if (!$config[9]) $config[9]=0;
	if (!$config[14]) $config[14]=0;
	if (!$config[15]) $config[15]=0;
	if (!$config[17]) $config[17]=50000;
	if (!$config[21]) $config[21]=0;
	if (!$config[24]) $config[24]=0;
	if (!$config[25]) $config[25]=2000;
	if (!$config[28]) $config[28]=0;
	if (!$config[29]) $config[29]=35;
	if (!$config[30]) $config[30]=0;
	if (!$config[58]) $config[58]=0;
	if (!$config[31]) $config[31]=0;
	if (!$config[35]) $config[35]=0;
	if (!$config[40]) $config[40]=0;
	if (!$config[41]) $config[41]=0;
	if (!$config[42]) $config[42]=0;
	if (!$config[43]) $config[43]=0;
	if (!$config[44]) $config[44]=5000;
	if (!$config[46]) $config[46]=0;
	if (!$config[47]) $config[47]=0;
	if (!$config[48]) $config[48]=0;
	if (!$config[56]&&$config[56]!=0) $config[56]=66;
	if (!$config[57]) $config[57]=50;
	//if (!$config[59]) $config[59]=1;
	if (!$config[67]) $config[67]=10000;
	if (!$config[68]) $config[68]=1;
	if (!$config[69]) $config[69]=0;
	if (!$config[76]) $config[76]=3;
	if (!$config[77]) $config[77]=12;
	if (!$config[84]) $config[84]=0;
	if (!$config[85]) $config[85]=0;
	if (!$config[86]) $config[86]=0;
	if (!$config[90]&&$config[90]!=0) $config[90]=15;
	if (!$config[91]) $config[91]=0;
	if (!$config[92]) $config[92]=0;
	if (!$config[93]) $config[93]=0;
	if (!$config[94]) $config[94]=0;
	$config[13]*=60;
	$config[30]*=60;
	//$config[24] 回复不用转换
	$config[40]*=10;
	$config[42]*=10;
	$config[46]*=10;
	$config[68]*=10;
	$dbcontent="<?php
\$picpath='$datebase[2]';
\$attachpath='$datebase[3]';
?/>";
	$filecontent="<?php
include './data/dbset.php';
\$db_linesize=70;
\$db_topnum=10;
\$userpath='$datebase[0]';
\$dbpath='$datebase[1]';
\$msgpath='$datebase[4]';
\$db_hour=$datebase[5];
\$db_http='$datebase[6]';
\$db_autochange=$datebase[7];
\$db_bbsifopen=$config[1];
\$db_whybbsclose='$config[2]';
\$db_bbsname='$config[3]';
\$db_bbsurl='$config[4]';
\$db_wwwname='$config[5]';
\$db_wwwurl='$config[6]';
\$db_ceoconnect='$config[7]';
\$db_ceoemail='$config[8]';
\$db_newtime='$config[9]';
\$db_signhtfcode=$config[10];
\$db_htfpic['pic']=$config[11];
\$db_htfpic['flash']=$config[12];
\$db_cvtime=$config[13];
\$db_perpage=$config[14];
\$db_readperpage=$config[15];
\$db_replysendmail=$config[16];
\$db_postmax=$config[17];
\$db_htfpost['pic']=$config[18];
\$db_htfpost['flash']=$config[19];
\$db_showreplynum=$config[21];
\$db_htfreply['pic']=$config[22];
\$db_htfreply['flash']=$config[23];
\$db_dtreplyrvrc=$config[24];
\$db_schpernum=$config[25];
\$db_tformat='$config[26]';
\$db_refreshtime=$config[28];
\$db_signnum=$config[29];
\$db_onlinetime=$config[30];
\$db_footertime=$config[31];
\$db_threadonline=$config[32];
\$db_uploadmaxsize=$config[35];
\$db_uploadfiletype='$config[36]';
\$db_htfpost['mpeg']=$config[37];
\$db_htfpost['iframe']=$config[38];
\$db_dtpostrvrc=$config[40];
\$db_dtpostmoney=$config[41];
\$db_dtjhrvrc=$config[42];
\$db_dtjhmoney=$config[43];
\$db_onlinelmt=$config[44];
\$db_dtdelrvrc=$config[46];
\$db_dtdelmoney=$config[47];
\$db_indexonline=$config[48];
\$db_moneyname='$config[50]';
\$db_ifjump=$config[52];
\$db_regpopup='$config[53]';
\$db_ifonlinetime=$config[54];
\$db_showguest=$config[55];
\$db_recycle=$config[56];
\$db_indexshowbirth=$config[58];
\$db_obstart=$config[60];
\$db_indexlink=$config[61];
\$db_indexmqshare=$config[62];
\$db_defaultstyle='$config[63]';
\$ckpath='$config[64]';
\$ckdomain='$config[65]';
\$db_threademotion=$config[70];
\$db_todaypost=$config[80];
\$db_threadshowpost=$config[81];
\$db_indexfmlogo=$config[83];
\$db_autoimg=$config[84];
\$db_postmin=$config[85];
\$db_selcount=$config[86];
\$db_ipfrom=$config[91];
\$db_dtreplymoney=$config[92];
\$db_ipcheck=$config[93];
\$db_today=$config[95];
\$db_postallowtime=$config[96];
\$db_showonline=$config[97];
?/>";
//87//66//51//49//34//45//39//33//27//90//67//57
//$db_linesize=70;//索引定长行长度
//$db_topnum=10;//置顶帖个数控制
//$userpath='$datebase[0]';//用户数据库名
//$dbpath='$datebase[1]';//帖子数据库名
//$msgpath='$datebase[4]';//短消息目录名
//$db_hour=$datebase[5];//图片更改时间控制
//$db_http='$datebase[6]';//使用跨台图片链
//$db_autochange=$datebase[7];//不定期更改图片链
//$db_bbsifopen=$config[1];//论坛是否开启
//$db_whybbsclose='$config[2]';//论坛为何关闭
//$db_bbsname='$config[3]';//论坛名称
//$db_bbsurl='$config[4]';//论坛url
//$db_wwwname='$config[5]';//网站名称
//$db_wwwurl='$config[6]';//网站url
//$db_ceoconnect='$config[7]';//如何联系管理员
//$db_ceoemail='$config[8]';//管理员email
//$db_newtime='$config[9]';//新贴保持时间界定
//$db_signhtfcode=$config[10];//签名htf代码
//$db_htfpic['pic']=$config[11];//签名中 htf IMG代码
//$db_htfpic['flash']=$config[12];//签名中 htf Flash代码
//$db_cvtime=$config[13];//时差转换
//$db_perpage=$config[14];//论坛文章列表每页显示主题数
//$db_readperpage=$config[15];//阅读一主题时每页显示帖子数
//$db_replysendmail=$config[16];//文章被回复是否发送邮件
//$db_postmax=$config[17];//帖子最大文字
//$db_htfpost['pic']=$config[18];//帖子中 htf IMG代码
//$db_htfpost['flash']=$config[19];//帖子中 htf Flash代码
//$db_showreplynum=$config[21];//查看回复显示帖子数
//$db_htfreply['pic']=$config[22];//回复提示帖中 htf IMG代码
//$db_htfreply['flash']=$config[23];//回复提示帖中 htf Flash代码
//$db_dtreplyrvrc=$config[24];//回复10贴得到威望
//$db_refreshtime=$config[28];//刷新时间间隔
//$db_signnum=$config[29];//签名初始字数
//$db_onlinetime=$config[30];//在线时间限制
//$db_footertime=$config[31];//是否显示程序运行时间
//$db_threadonline=$config[32];//是否显示分版块在线会员
//$db_uploadmaxsize=$config[35];//上传附件最大大小
//$db_uploadfiletype='$config[36]';//上传附件文件类型
//$db_htfpost['mpeg']=$config[37];//发贴 的mpeg代码
//$db_htfpost['iframe']=$config[38];//发贴 的 iframe代码
//$db_dtpostrvrc=$config[40];//发一贴得到威望
//$db_dtpostmoney=$config[41];//发一贴得到财富
//$db_dtjhrvrc=$config[42];//精华一次得威望
//$db_dtjhmoney=$config[43];//精华一次得财富
//$db_onlinelmt=$config[44];//最大在线人数
//$db_dtdelrvrc=$config[46];//删除一贴减少威望
//$db_dtdelmoney=$config[47];//删除一贴减少财富
//$db_indexonline=$config[48];//是否首页显示在线会员
//$db_moneyname='$config[50]';//论坛财富名称
//$db_ifjump=$config[52];//是否使用自动跳转
//$db_regpopup='$config[53]';//是否显示快速登陆注册 popup
//$db_ifonlinetime=$config[54];//记录会员在线时间
//$db_showguest=$config[55];//是否显示游客
//$db_recycle=$config[56];//回收站ID
//$db_dtluck=$config[57];//幸运儿获得财富数
//$db_indexshowbirth=$config[58];//是否显示生日会员
//$db_obstart=$config[60];//是否GZIP
//$db_indexlink=$config[61];//是否显示友情链接
//$db_indexmqshare=$config[62];//是否滚动友情链接
//$db_defaultstyle='$config[63]';//论坛默认风格
//$ckpath='$config[64]';//COOKIE有效目录
//$ckdomain='$config[65]';//COOKIE有效域名
//$db_threademotion=$config[70];//各分版块帖子导航里显示表情符
//$db_todaypost=$config[80];//是否显示今日昨日发贴数
//$db_threadshowpost=$config[81];//显示快速发表主题
//$db_indexfmlogo=$config[83];//自定义首页各版块的图片logo
//$db_autoimg=$config[84];//自动显示前台图片链界
//$db_postmin=$config[85];//每篇文章的最小长度
//$db_selcount=$config[86];//控制投票选项个数
//$db_searchtime=$config[90];//两次搜索时间间隔
//$db_ipfrom=$config[91];//是否显示IP来源
//$db_dtreplymoney=$config[92];//回复一贴增加的财富
//$db_ipcheck=$config[93];//开启ip验证$db_signnum
//$db_today=$config[95];//启用统计今日到访会员
//$db_postallowtime=$config[96];//注册用户发帖时间控制
//$db_showonline=$config[97];//是否在topic.php页面显示最新用户

$regcontent="<?php
\$db_regsendemail=$config[71];//新用户注册发送email
\$db_allowregister=$config[72];//允许新用户注册
\$db_reg=$config[73];//注册时显示许可协议
\$db_regdetail=$config[74];//注册时显示详细注册信息
\$db_emailcheck=$config[75];//新用户注册需通过email激活用户
\$db_regminname=$config[76];//用户名最短长度
\$db_regmaxname=$config[77];//用户名最长长度
\$db_regsendmsg=$config[79];//新用户注册发送短消息
\$db_regrvrc=$config[68];//新用户注册初始威望
\$db_regmoney=$config[69];//新用户注册初始财富
\$db_ifcheck=$config[59];//新用户注册是否需要验证
\$db_allowsameip='$config[82]';//是否开启同一IP24小时只能注册一次
\$db_welcomemsg='$config[88]';//欢迎短消息内容
\$db_rgpermit='$config[89]';//注册许可内容
\$db_rglower=$config[94];//注册区分大小写
?/>";
	writeover("data/dbset.php",str_replace("?/>","?>",$dbcontent));
	writeover("data/config.php",str_replace("?/>","?>",$filecontent));
	writeover("data/dbreg.php",str_replace("?/>","?>",$regcontent));
	adminmsg("成功设置论坛核心信息");
}
?>