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
	//$db_dtreplyrvrc�ظ�����ת��
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
	if (file_exists($attachpath) && !is_writeable($attachpath)) $attdisabled='disabled';/*��ֹĿ¼����д��ʱ.����Ա�����޸�.���´���*/
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
	if ($db_signhtfcode) $signhtfcode_open="checked"; else $signhtfcode_close="checked";//ǩ���� htf ����
	if ($db_replysendmail) $replysendmail_open="checked"; else $replysendmail_close="checked";//�û����±��ظ��Ƿ����ʼ�
	if ($db_footertime) $footertime_open="checked"; else $footertime_close="checked";//�Ƿ���ҳ����ʾ��������ʱ��
	if ($db_threadonline) $threadonline_open="checked"; else $threadonline_close="checked";//�Ƿ��ڰ������ʾ�����û�
	if ($db_showonline) $showonline_open="checked"; else $showonline_close="checked";//�Ƿ���topic.phpҳ����ʾ�����û�	
	if ($db_indexonline) $indexonline_open="checked"; else $indexonline_close="checked";//�Ƿ����Ƿ�����ҳ��ʾ�����û�
 	if ($db_indexshowbirth) $indexshowbirth_open="checked"; else $indexshowbirth_close="checked";//��ҳ��ʾ�������ջ�Ա
	if ($db_regpopup) $regpopup_open="checked";else $regpopup_close="checked";//ע����ʾ��
	if ($db_indexlink) $indexlink_open="checked";else $indexlink_close="checked";//�Ƿ���ʾ��������
	if ($db_indexstar) $indexstar_open="checked";else $indexstar_close="checked";//�Ƿ���ʾ��������,ׯ��,���˶�
	if ($db_indexmqshare) $indexmqshare_open="checked";else $indexmqshare_close="checked";//�Ƿ������ʾ��������
 	if ($db_indexliuyan) $indexliuyan_open="checked";else $indexliuyan_close="checked";//��ҳ��ʾ���Ա�
	if ($db_allowregister) $allowregister_open="checked";else $allowregister_close="checked";//�������û�ע��
	if ($db_regdetail) $regdetail_open="checked";else $regdetail_close="checked";//ע��ʱ��ʾ��ϸע����Ϣ
	if ($db_emailcheck) $emailcheck_open="checked";else $emailcheck_close="checked";//���û�ע����ͨ��email�����û�
	if ($db_regsendmsg) $regsendmsg_open="checked";else $regsendmsg_close="checked";//���û�ע�ᷢ�Ͷ���Ϣ
	if ($db_ifcheck) $regifcheck_open="checked";else $regifcheck_close="checked";//���û�ע���Ƿ���Ҫ��֤
	if ($db_allowsameip==Y) $regallowsameip_open="checked";else $regallowsameip_close="checked";//�Ƿ���ͬһIP24Сʱֻ��ע��һ��
	if ($db_regsendemail) $regsendemail_open="checked";else $regsendemail_close="checked";//���û�ע�ᷢ�͵����ʼ�
	if ($db_ifonlinetime) $ifonlinetime_open="checked";else $ifonlinetime_close="checked";//��¼��Ա����ʱ��
	if ($db_todaypost) $todaypost_open="checked";else $todaypost_close="checked";//��ʾ�������շ�����
	if ($db_reg) $reg_close="checked";else $reg_open="checked";//ע��ʱ��ʾ���Э��
	if ($db_showmenu) $showmenu_open="checked";else $showmenu_close="checked";//��ҳ��ʾ���ֵ�����
	if ($db_showguest) $showguest_open="checked";else $showguest_close="checked";//��ҳ��ʾ�����ο�
	if ($db_indexshowsong) $indexshowsong_open="checked";else $indexshowsong_close="checked";//��ʾ�������� �͵��̨
	if ($db_threademotion) $threademotion_open="checked"; else $threademotion_close="checked";//�ְ�����ӵ�������ʾ�����
	if ($db_threadshowpost) $threadshowpost_open="checked"; else $threadshowpost_close="checked";//��������ʾ���ٷ�������
	if ($db_ifjump) $ifjump_open="checked"; else $ifjump_close="checked";//�Ƿ�ʹ���Զ���ת
	if ($db_indexfmlogo==1) $indexfmlogo_open="checked";elseif($db_indexfmlogo==2)$indexfmlogo_status="checked";else $indexfmlogo_close="checked";//�Զ�����ҳ������ͼƬlogo
	if ($db_obstart) $obstart_open="checked";else $obstart_close="checked";//��gzipѹ��
	if ($db_autoimg) $autoimg_open="checked";else $autoimg_close="checked";//�Ƿ�ʹ���Զ���ͼ
	if ($db_ipfrom)$ipfrom_open="checked";else $ipfrom_close="checked";//�Ƿ���ʾIP��Դ
	if ($db_autochange) $autochange_open="checked"; else $autochange_close="checked";//�Զ�����ͼƬ��
	if ($db_ipcheck) $ipcheck_open="checked"; else $ipcheck_close="checked";//����ip��֤
	if ($db_rglower) $rglower_open="checked"; else $rglower_close="checked";//ע��id���ִ�Сд
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
	if(!ereg("^http",$datebase[2]))//!file_exists("$datebase[2]")��ҪΪ�˼�ǿ����Ա��������
		@rename($picpath,$datebase[2]);
	elseif($picpath<>$datebase[2])
		adminmsg("�Բ���,����Ŀ¼��������,������ʹ�ú���http �ַ���ͼƬĿ¼������Ҫ���ĵ�Ŀ¼�Ѿ�����");
	if ($config[77]>12) adminmsg("ϵͳ�ȶ��Կ���,�벻Ҫ���� 12 �ֽ�");
	if ($attachpath<>$datebase[3]) rename($attachpath,$datebase[3]);
	if ($msgpath<>$datebase[4]) rename($msgpath,$datebase[4]);
	if (!ereg("^[0-9]{1,}",$datebase[5]))
		adminmsg("ͼƬ��������������Ϊ����");
	if (!ereg("^[0-9]{1,}",$config[96]))
		adminmsg("��ע���û���������ʱ�����Ϊ����");
	if (!ereg("^http",$datebase[6]) && $datebase[6]!='N')
		adminmsg("ʹ�ÿ�̨ͼƬ��������http��ͷ");
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
	//$config[24] �ظ�����ת��
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
//$db_linesize=70;//���������г���
//$db_topnum=10;//�ö�����������
//$userpath='$datebase[0]';//�û����ݿ���
//$dbpath='$datebase[1]';//�������ݿ���
//$msgpath='$datebase[4]';//����ϢĿ¼��
//$db_hour=$datebase[5];//ͼƬ����ʱ�����
//$db_http='$datebase[6]';//ʹ�ÿ�̨ͼƬ��
//$db_autochange=$datebase[7];//�����ڸ���ͼƬ��
//$db_bbsifopen=$config[1];//��̳�Ƿ���
//$db_whybbsclose='$config[2]';//��̳Ϊ�ιر�
//$db_bbsname='$config[3]';//��̳����
//$db_bbsurl='$config[4]';//��̳url
//$db_wwwname='$config[5]';//��վ����
//$db_wwwurl='$config[6]';//��վurl
//$db_ceoconnect='$config[7]';//�����ϵ����Ա
//$db_ceoemail='$config[8]';//����Աemail
//$db_newtime='$config[9]';//��������ʱ��綨
//$db_signhtfcode=$config[10];//ǩ��htf����
//$db_htfpic['pic']=$config[11];//ǩ���� htf IMG����
//$db_htfpic['flash']=$config[12];//ǩ���� htf Flash����
//$db_cvtime=$config[13];//ʱ��ת��
//$db_perpage=$config[14];//��̳�����б�ÿҳ��ʾ������
//$db_readperpage=$config[15];//�Ķ�һ����ʱÿҳ��ʾ������
//$db_replysendmail=$config[16];//���±��ظ��Ƿ����ʼ�
//$db_postmax=$config[17];//�����������
//$db_htfpost['pic']=$config[18];//������ htf IMG����
//$db_htfpost['flash']=$config[19];//������ htf Flash����
//$db_showreplynum=$config[21];//�鿴�ظ���ʾ������
//$db_htfreply['pic']=$config[22];//�ظ���ʾ���� htf IMG����
//$db_htfreply['flash']=$config[23];//�ظ���ʾ���� htf Flash����
//$db_dtreplyrvrc=$config[24];//�ظ�10���õ�����
//$db_refreshtime=$config[28];//ˢ��ʱ����
//$db_signnum=$config[29];//ǩ����ʼ����
//$db_onlinetime=$config[30];//����ʱ������
//$db_footertime=$config[31];//�Ƿ���ʾ��������ʱ��
//$db_threadonline=$config[32];//�Ƿ���ʾ�ְ�����߻�Ա
//$db_uploadmaxsize=$config[35];//�ϴ���������С
//$db_uploadfiletype='$config[36]';//�ϴ������ļ�����
//$db_htfpost['mpeg']=$config[37];//���� ��mpeg����
//$db_htfpost['iframe']=$config[38];//���� �� iframe����
//$db_dtpostrvrc=$config[40];//��һ���õ�����
//$db_dtpostmoney=$config[41];//��һ���õ��Ƹ�
//$db_dtjhrvrc=$config[42];//����һ�ε�����
//$db_dtjhmoney=$config[43];//����һ�εòƸ�
//$db_onlinelmt=$config[44];//�����������
//$db_dtdelrvrc=$config[46];//ɾ��һ����������
//$db_dtdelmoney=$config[47];//ɾ��һ�����ٲƸ�
//$db_indexonline=$config[48];//�Ƿ���ҳ��ʾ���߻�Ա
//$db_moneyname='$config[50]';//��̳�Ƹ�����
//$db_ifjump=$config[52];//�Ƿ�ʹ���Զ���ת
//$db_regpopup='$config[53]';//�Ƿ���ʾ���ٵ�½ע�� popup
//$db_ifonlinetime=$config[54];//��¼��Ա����ʱ��
//$db_showguest=$config[55];//�Ƿ���ʾ�ο�
//$db_recycle=$config[56];//����վID
//$db_dtluck=$config[57];//���˶���òƸ���
//$db_indexshowbirth=$config[58];//�Ƿ���ʾ���ջ�Ա
//$db_obstart=$config[60];//�Ƿ�GZIP
//$db_indexlink=$config[61];//�Ƿ���ʾ��������
//$db_indexmqshare=$config[62];//�Ƿ������������
//$db_defaultstyle='$config[63]';//��̳Ĭ�Ϸ��
//$ckpath='$config[64]';//COOKIE��ЧĿ¼
//$ckdomain='$config[65]';//COOKIE��Ч����
//$db_threademotion=$config[70];//���ְ�����ӵ�������ʾ�����
//$db_todaypost=$config[80];//�Ƿ���ʾ�������շ�����
//$db_threadshowpost=$config[81];//��ʾ���ٷ�������
//$db_indexfmlogo=$config[83];//�Զ�����ҳ������ͼƬlogo
//$db_autoimg=$config[84];//�Զ���ʾǰ̨ͼƬ����
//$db_postmin=$config[85];//ÿƪ���µ���С����
//$db_selcount=$config[86];//����ͶƱѡ�����
//$db_searchtime=$config[90];//��������ʱ����
//$db_ipfrom=$config[91];//�Ƿ���ʾIP��Դ
//$db_dtreplymoney=$config[92];//�ظ�һ�����ӵĲƸ�
//$db_ipcheck=$config[93];//����ip��֤$db_signnum
//$db_today=$config[95];//����ͳ�ƽ��յ��û�Ա
//$db_postallowtime=$config[96];//ע���û�����ʱ�����
//$db_showonline=$config[97];//�Ƿ���topic.phpҳ����ʾ�����û�

$regcontent="<?php
\$db_regsendemail=$config[71];//���û�ע�ᷢ��email
\$db_allowregister=$config[72];//�������û�ע��
\$db_reg=$config[73];//ע��ʱ��ʾ���Э��
\$db_regdetail=$config[74];//ע��ʱ��ʾ��ϸע����Ϣ
\$db_emailcheck=$config[75];//���û�ע����ͨ��email�����û�
\$db_regminname=$config[76];//�û�����̳���
\$db_regmaxname=$config[77];//�û��������
\$db_regsendmsg=$config[79];//���û�ע�ᷢ�Ͷ���Ϣ
\$db_regrvrc=$config[68];//���û�ע���ʼ����
\$db_regmoney=$config[69];//���û�ע���ʼ�Ƹ�
\$db_ifcheck=$config[59];//���û�ע���Ƿ���Ҫ��֤
\$db_allowsameip='$config[82]';//�Ƿ���ͬһIP24Сʱֻ��ע��һ��
\$db_welcomemsg='$config[88]';//��ӭ����Ϣ����
\$db_rgpermit='$config[89]';//ע���������
\$db_rglower=$config[94];//ע�����ִ�Сд
?/>";
	writeover("data/dbset.php",str_replace("?/>","?>",$dbcontent));
	writeover("data/config.php",str_replace("?/>","?>",$filecontent));
	writeover("data/dbreg.php",str_replace("?/>","?>",$regcontent));
	adminmsg("�ɹ�������̳������Ϣ");
}
?>