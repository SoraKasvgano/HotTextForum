var text = "";
function getActiveText(selectedtext) {
  text = (document.all) ? document.selection.createRange().text : document.getSelection();
  if (selectedtext.createTextRange) {	
    selectedtext.caretPos = document.selection.createRange().duplicate();	
  }
	return true;
}
function submitonce(theform)
{
	if (document.all||document.getElementById)
	{
		for (i=0;i<theform.length;i++)
		{
			var tempobj=theform.elements[i];
			if(tempobj.type.toLowerCase()=="submit"||tempobj.type.toLowerCase()=="reset")
				tempobj.disabled=true;
		}
	}
}
function checklength(theform)
{
	alert("�����Ϣ�Ѿ��� "+theform.atc_content.value.length+" �ֽ�.");
}
function AddText(NewCode) 
{
	if (document.FORM.atc_content.createTextRange && document.FORM.atc_content.caretPos) 
	{
		var caretPos = document.FORM.atc_content.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? NewCode + ' ' : NewCode;
	} 
	else 
	{
		document.FORM.atc_content.value+=NewCode
	}
	setfocus();
}
function setfocus()
{
  document.FORM.atc_content.focus();
}
defaultmode = "divmode";

if (defaultmode == "nomode") {
        helpmode = false;
        divmode = false;
        nomode = true;
} else if (defaultmode == "helpmode") {
        helpmode = true;
        divmode = false;
        nomode = false;
} else {
        helpmode = false;
        divmode = true;
        nomode = false;
}
function checkmode(swtch){
        if (swtch == 1){
                nomode = false;
                divmode = false;
                helpmode = true;
                alert("htf ���� - ������Ϣ\n\n�����Ӧ�Ĵ��밴ť���ɻ����Ӧ��˵������ʾ");
        } else if (swtch == 0) {
                helpmode = false;
                divmode = false;
                nomode = true;
                alert("htf ���� - ֱ�Ӳ���\n\n������밴ť�󲻳�����ʾ��ֱ�Ӳ�����Ӧ����");
        } else if (swtch == 2) {
                helpmode = false;
                nomode = false;
                divmode = true;
                alert("htf ���� - ��ʾ����\n\n������밴ť������򵼴��ڰ�������ɴ������");
        }
}
function showsize(size) {
	if (helpmode) {
		alert("���ִ�С���\n�������ִ�С.\n�ɱ䷶Χ 1 - 6.\n 1 Ϊ��С 6 Ϊ���.\n�÷�: [size="+size+"]���� "+size+" ����[/size]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[size="+size+"]"+text+"[/size]";
		AddText(AddTxt);
	} else {
		txt=prompt("��С "+size,"����");
		if (txt!=null) {
			AddTxt="[size="+size+"]"+txt;
			AddText(AddTxt);
			AddTxt="[/size]";
			AddText(AddTxt);
		}
	}
}

function showfont(font) {
 	if (helpmode){
		alert("������\n��������������.\n�÷�: [font="+font+"]�ı���������Ϊ"+font+"[/font]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[font="+font+"]"+text+"[/font]";
		AddText(AddTxt);
	} else {
		txt=prompt("Ҫ�������������"+font,"����");
		if (txt!=null) {
			AddTxt="[font="+font+"]"+txt;
			AddText(AddTxt);
			AddTxt="[/font]";
			AddText(AddTxt);
		}
	}
}
function showcolor(color) {
	if (helpmode) {
		alert("��ɫ���\n�����ı���ɫ.  �κ���ɫ�������Ա�ʹ��.\n�÷�: [color="+color+"]��ɫҪ�ı�Ϊ"+color+"������[/color]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[color="+color+"]"+text+"[/color]";
		AddText(AddTxt);
	} else {  
     	txt=prompt("ѡ�����ɫ��: "+color,"����");
		if(txt!=null) {
			AddTxt="[color="+color+"]"+txt;
			AddText(AddTxt);
			AddTxt="[/color]";
			AddText(AddTxt);
		}
	}
}

function bold() {
	if (helpmode) {
		alert("�Ӵֱ��\nʹ�ı��Ӵ�.\n�÷�: [b]���ǼӴֵ�����[/b]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[b]"+text+"[/b]";
		AddText(AddTxt);
	} else {
		txt=prompt("���ֽ������.","����");
		if (txt!=null) {
			AddTxt="[b]"+txt;
			AddText(AddTxt);
			AddTxt="[/b]";
			AddText(AddTxt);
		}
	}
}

function italicize() {
	if (helpmode) {
		alert("б����\nʹ�ı������Ϊб��.\n�÷�: [i]����б����[/i]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[i]"+text+"[/i]";
		AddText(AddTxt);
	} else {
		txt=prompt("���ֽ���б��","����");
		if (txt!=null) {
			AddTxt="[i]"+txt;
			AddText(AddTxt);
			AddTxt="[/i]";
			AddText(AddTxt);
		}
	}
}

function quoteme() {
	if (helpmode){
		alert("���ñ��\n����һЩ����.\n�÷�: [quote]��������[/quote]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[quote]"+text+"[/quote]";
		AddText(AddTxt);
	} else {
		txt=prompt("�����õ�����","����");
		if(txt!=null) {
			AddTxt="[quote]"+txt;
			AddText(AddTxt);
			AddTxt="[/quote]";
			AddText(AddTxt);
		}
	}
}
function setfly() {
 	if (helpmode){
		alert("���б��\nʹ���ַ���.\n�÷�: [fly]����Ϊ��������[/fly]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[fly]"+text+"[/fly]";
		AddText(AddTxt);
	} else {
		txt=prompt("��������","����");
		if (txt!=null) {
			AddTxt="[fly]"+txt;
			AddText(AddTxt);
			AddTxt="[/fly]";
			AddText(AddTxt);
		}
	}
}

function movesign() {
	if (helpmode) {
		alert("�ƶ����\nʹ���ֲ����ƶ�Ч��.\n�÷�: [move]Ҫ�����ƶ�Ч��������[/move]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[move]"+text+"[/move]";
		AddText(AddTxt);
	} else {
		txt=prompt("Ҫ�����ƶ�Ч��������","����");
		if (txt!=null) {
			AddTxt="[move]"+txt;
			AddText(AddTxt);
			AddTxt="[/move]";
			AddText(AddTxt);
		}
	}
}

function shadow() {
	if (helpmode) {
alert("��Ӱ���\nʹ���ֲ�����ӰЧ��.\n�÷�: [SHADOW=���, ��ɫ, �߽�]Ҫ������ӰЧ��������[/SHADOW]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[SHADOW=255,blue,1]"+text+"[/SHADOW]";
		AddText(AddTxt);
	} else {
		txt2=prompt("���ֵĳ��ȡ���ɫ�ͱ߽��С","255,blue,1");
		if (txt2!=null) {
			txt=prompt("Ҫ������ӰЧ��������","����");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[shadow=255, blue, 1]"+txt;
					AddText(AddTxt);
					AddTxt="[/shadow]";
					AddText(AddTxt);
				} else {
					AddTxt="[shadow="+txt2+"]"+txt;
					AddText(AddTxt);
					AddTxt="[/shadow]";
					AddText(AddTxt);
				}
			}
		}
	}
}

function glow() {
	if (helpmode) {
		alert("���α��\nʹ���ֲ�������Ч��.\n�÷�: [GLOW=���, ��ɫ, �߽�]Ҫ��������Ч��������[/GLOW]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[glow=255,red,2]"+text+"[/glow]";
		AddText(AddTxt);
	} else {
		txt2=prompt("���ֵĳ��ȡ���ɫ�ͱ߽��С","255,red,2");
		if (txt2!=null) {
			txt=prompt("Ҫ��������Ч��������.","����");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[glow=255,red,2]"+txt;
					AddText(AddTxt);
					AddTxt="[/glow]";
					AddText(AddTxt);
				} else {
					AddTxt="[glow="+txt2+"]"+txt;
					AddText(AddTxt);
					AddTxt="[/glow]";
					AddText(AddTxt);
				}
			}
		}
	}
}

function center() {
 	if (helpmode) {
		alert("������\nʹ��������, ����ʹ�ı�����롢���С��Ҷ���.\n�÷�: [align=center|left|right]Ҫ������ı�[/align]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[align=center]"+text+"[/align]";
		AddText(AddTxt);
	} else {
		txt2=prompt("������ʽ\n���� 'center' ��ʾ����, 'left' ��ʾ�����, 'right' ��ʾ�Ҷ���.","center");
		while ((txt2!="") && (txt2!="center") && (txt2!="left") && (txt2!="right") && (txt2!=null)) {
			txt2=prompt("����!\n����ֻ������ 'center' �� 'left' ���� 'right'.","");
		}
		txt=prompt("Ҫ������ı�","�ı�");
		if (txt!=null) {
			AddTxt="\r[align="+txt2+"]"+txt;
			AddText(AddTxt);
			AddTxt="[/align]";
			AddText(AddTxt);
		}
	}
}

function rming() {
	if (helpmode) {
		alert("RM���ֱ��\n����һ��RM���ӱ��\nʹ�÷���: [rm]http:\/\/www.123.com\/rm\/php.rm[/rm]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[rm]"+text+"[/rm]";
		AddText(AddTxt);
	} else {
		txt=prompt("rm��Ӱ�� URL","http://");
		if(txt!=null) {
			AddTxt="\r[rm]"+txt;
			AddText(AddTxt);
			AddTxt="[/rm]";
			AddText(AddTxt);
		}
	}
}

function image() {
	if (helpmode){
		alert("ͼƬ���\n����ͼƬ\n�÷�: [img]http://www.hao123.com/logo.gif[/img]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[img]"+text+"[/img]";
		AddText(AddTxt);
	} else {
		txt=prompt("ͼƬ�� URL","http://");
		if(txt!=null) {
			AddTxt="\r[img]"+txt;
			AddText(AddTxt);
			AddTxt="[/img]";
			AddText(AddTxt);
		}
	}
}

function wmv() {
	if (helpmode){
		alert("wmv���\n����wmv\n�÷�: [wmv]http:\/\/www.123.com\/wmv\/php.wmv[/wmv]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[wmv]"+text+"[/wmv]";
		AddText(AddTxt);
	} else {
		txt=prompt("��Ӱ�� URL","http://");
		if(txt!=null) {
			AddTxt="\r[wmv]"+txt;
			AddText(AddTxt);
			AddTxt="[/wmv]";
			AddText(AddTxt);
		}
	}
}

function showcode() {
	if (helpmode) {
		alert("������\nʹ�ô�����,����ʹ��ĳ����������� html �ȱ�־���ᱻ�ƻ�.\nʹ�÷���:\n [code]�����Ǵ�������[/code]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="\r\n[code]"+text+"[/code]";
		AddText(AddTxt);
	} else {
		txt=prompt("�������","");
		if (txt!=null) { 
			AddTxt="\r[code]"+txt;
			AddText(AddTxt);
			AddTxt="[/code]";
			AddText(AddTxt);
		}
	}
}

function list() {
	if (helpmode) {
		alert("�б���\n����һ�����ֻ��������б�.\nUSE: [list]\n[*]item1\n[*]item2\n[*]item3\n[/list]");
	} else if (nomode) {
		AddTxt="\r[list]\r[*]\r[*]\r[*]\r[/list]";
		AddText(AddTxt);
	} else {
		txt=prompt("�б�����\n���� 'A' ��ʾ�����б�, '1' ��ʾ�����б�, ���ձ�ʾ�����б�.","");
		while ((txt!="") && (txt!="A") && (txt!="a") && (txt!="1") && (txt!=null)) {
			txt=prompt("����!\n����ֻ������ 'A' �� '1' ��������.","");
		}
		if (txt!=null) {
			if ((txt=="") || (txt=="1")) {
				AddTxt="\r[list]\r\n";
			} else {
				AddTxt="\r[olist]\r\n";
			}
			ltxt="1";
			while ((ltxt!="") && (ltxt!=null)) {
				ltxt=prompt("�б���\n�հױ�ʾ�����б�","");
				if (ltxt!="") {
					AddTxt+="[*]"+ltxt+"\r";
				}
			}
			if ((txt=="") || (txt=="1")) {
				AddTxt+="[/list]\r\n";
			} else {
				AddTxt+="[/olist]\r\n";
			} 
			AddText(AddTxt);
		}
	}
}
function underline() {
  	if (helpmode) {
		alert("�»��߱��\n�����ּ��»���.\n�÷�: [u]Ҫ���»��ߵ�����[/u]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="[u]"+text+"[/u]";
		AddText(AddTxt);
	} else {
		txt=prompt("�»�������.","����");
		if (txt!=null) {
			AddTxt="[u]"+txt;
			AddText(AddTxt);
			AddTxt="[/u]";
			AddText(AddTxt);
		}
	}
}

function setswf() {
 	if (helpmode){
		alert("Flash ����\n���� Flash ����.\n�÷�: [flash=���,�߶�]Flash �ļ��ĵ�ַ[/flash]");
	} else if (nomode || document.selection && document.selection.type == "Text") {
		AddTxt="\r[flash=400,300]"+text+"[/flash]";
		AddText(AddTxt);
	} else {
			txt2=prompt("���,�߶�","400,300");
		if (txt2!=null) {
			txt=prompt("Flash �ļ��ĵ�ַ","http://");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[flash=400,300]"+txt;
					AddText(AddTxt);
					AddTxt="[/flash]";
					AddText(AddTxt);
				} else {
					AddTxt="\r[flash="+txt2+"]"+txt;
					AddText(AddTxt);
					AddTxt="[/flash]";
					AddText(AddTxt);
				}
			}
		}
	}
}
//////////////////////////////////
	function add_title(addTitle) 
	{ 
		var revisedTitle; 
		var currentTitle = document.FORM.atc_title.value; 
		revisedTitle = currentTitle+addTitle; 
		document.FORM.atc_title.value=revisedTitle; 
		document.FORM.atc_title.focus(); 
		return;
	}
	function Addaction(addTitle)
	{ 
		var revisedTitle; 
		var currentTitle = document.FORM.atc_content.value; revisedTitle = currentTitle+addTitle; document.FORM.atc_content.value=revisedTitle; document.FORM.atc_content.focus(); 
		return; 
	}
	function copytext(theField) 
	{
		var tempval=eval("document."+theField);
		tempval.focus();
		tempval.select();
		therange=tempval.createTextRange();
		therange.execCommand("Copy");
	}
	function replac()
	{
		if (helpmode)
		{
			alert("�滻�ؼ���");
		}
		else
		{
			txt2=prompt("��������ѰĿ��ؼ���","");
			if (txt2 != null)
			{
				if (txt2 != "") 
				{
					txt=prompt("�ؼ����滻Ϊ:",txt2);
				}
				else
				{
					replac();
				}
				var Rtext = txt2; var Itext = txt; document.FORM.atc_content.value = eval('FORM.atc_content.value.replace("'+Rtext+'","'+Itext+'")');
			}
		}
	}
function addsmile(NewCode) {
  document.FORM.atc_content.value += ' '+NewCode+' '; 
}