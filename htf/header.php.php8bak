<?php
$headif = array('hd_nlg1' =>'<!--','hd_nlg2' =>'-->','hd_ma1' =>'<!--','hd_ma2' =>'-->','hd_lg1' =>'<!--','hd_lg2' =>'-->');

if (empty($skin)) $skin=$db_defaultstyle;
if(file_exists("style/$skin.php") && strpos($skin,'..')===false){
	@include ("style/$skin.php");
}else{
	@include ("style/htf.php");
}
$yeyestyle=='no' ? $i_table="bgcolor=$tablecolor" : $i_table='class=i_table';

if($groupid=='guest' || !isset($groupid)){
	$headif['hd_nlg1']=$headif['hd_nlg2']='';
	if($db_regpopup=='1' && !strpos($REQUEST_URI,'register') && !strpos($REQUEST_URI,'login')){	
		$head_pop='head_pop';
	}
}
else{
	$gotnewmsg>0 ? $head_gotmsg='<font color=red>您有新消息</font>':$head_gotmsg='短消息';
	$headif['hd_lg1']=$headif['hd_lg2']='';
	if($htfid==$manager){$headif['hd_ma1']=$headif['hd_ma2']='';}
}
include_once PrintEot('css');
include_once PrintEot('header');
?>