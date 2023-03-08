<?php
$star_action='hp';
require("global.php");
$secondname="бшлЁ╟ОжЗ";
if(!$faqjob) $faqjob=1;
require("header.php");
$msg_guide=headguide("$secondname");
include PrintEot('faq');footer();
?>