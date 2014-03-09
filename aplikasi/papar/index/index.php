<?php
//echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
//echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';

$kotak[0]='login_fancybox.txt'; 
$kotak[1]='login_colorbox.txt'; 
$login=$kotak[rand(0,1)];

$isi=$this->isi;
?>
<html><head><title><?php echo Tajuk_Muka_Surat ?></title>
<link rel="stylesheet" href="<?php echo JS ?>public/css/gambar_head.css" />
<?php
include '../../js/public/css/' . $login;
?>
</head>
<body background="<?php echo GAMBAR ?>">
<div id="content">
<table border="0" height="90%" width="90%">
<tr><td align="center" valign="middle"> 
<!-- ----------------------------------------------------------------------------------- -->
<table border="0" align="center">
<tr><td align="center" valign="top"  colspan=2>
	<a style="font-size: 20pt; text-decoration: overline underline; 
	background-color: #000000; color:#ffffff">Untuk <?php echo Tajuk_Muka_Surat ?></a>
	</td></tr>
<tr><td width="1000" align="center" valign="top">
	<a style="font-size: 20pt; text-decoration: overline underline; 
	background-color: #000000; color:#ffffff" class="zoom" 
	title="Apa Kabar Pentadbir" href="index/login/admin">Pentadbir</a>
	<a style="font-size: 15pt; text-decoration: overline underline; 
	background-color: #000000; color:#ffffff">Kawalan</a>
	<a class="zoom" title="Apa Kabar Amin" href="index/login/amin007"> 
	<img src="../../bg/kakitangan/amin.jpg"></a>
	<a class="zoom" title="Apa Kabar Suhaida" href="index/login/suhaida"> 
	<img src="../../bg/kakitangan/suhaida.jpg"></a> 
	<br><a style="font-size: 15pt; text-decoration: overline underline; 
	background-color: #000000; color:#ffffff">Prosesan</a><?php
$prosesan=array('azizah','rahima','zainap');
$isi=null;
foreach ($prosesan as $key => $nama2)
{	
	$isi.="\n\t".'<a class="zoom" title="Assalamualaikum ' . 
		ucwords($nama2) . '" ' . 'href="index/login/' . $nama2 . '">' . 
		"\n\t" . '<img src="../../bg/kakitangan/' . $nama2 . '.jpg" ' . 
		//'width="12%" height="12%"></a>';
		'></a>';
		
	$isi.=($key==10)? "<br>\n":"\n";
}
echo $isi;
?>
	</td></tr>
<tr><td align="center" valign="top">
	<a style="font-size: 15pt; text-decoration: overline underline; 
	background-color: #000000; color:#ffffff">FE</a><?php 
unset($pegawai);
$isi=null;
$pegawai=array('adam','ariff','azim','fendi','irwan','khairi',
'norita','musa','mustaffa','shukor');

foreach ($pegawai as $key => $nama)
{	
	$isi.="\n\t" . '<a class="zoom" title="Assalamualaikum ' .
	ucwords($nama) . '" ' .	'href="index/login_automatik/' . $nama . '">' .
	"\n\t" . '<img src="../../bg/kakitangan/' . $nama . '.jpg"></a>';
	
	$isi.=($key==4)? "<br>\n":"\n";
}
	echo $isi;
?>	
	</td></tr>
<tr><td align="center" valign="top"  colspan=2>
	<a style="font-size: 15pt; text-decoration: overline underline; 
	background-color: #000000; color:#ffffff">KUP</a><?php 
unset($kup);
$isi=null;
$kup=array('murad','sujana');
foreach ($kup as $key => $nama)
{	
	$isi.="\n\t" . '<a class="zoom" title="Assalamualaikum ' .
	ucwords($nama) . '" ' .	'href="index/login/' . $nama . '">' .
	"\n\t" . '<img src="../../bg/kakitangan/' . $nama . '.jpg"></a>';
	
	$isi.=($key==5)? "<br>\n":"\n";
}
	echo $isi;?><br>
	<a style="font-size: 15pt; text-decoration: overline underline; 
	background-color: #000000; color:#ffffff">PEGAWAI</a>
	<a class="zoom" title="Apa Kabar En Abdul Razak" href="index/login_automatik/razak"
	style="font-size: 20pt; background-color: #ffff00; color:#ff0000">Razak</a>
	<a class="zoom" title="Apa Kabar En Fareza" href="index/login_automatik/fareza"
	style="font-size: 20pt; background-color: #ffff00; color:#ff0000">Fareza</a>
	</td></tr>
</table>
<!-- ----------------------------------------------------------------------------------- -->
</td></tr></table>
</div>
</body>
</html>
