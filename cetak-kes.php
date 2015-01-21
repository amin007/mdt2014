<?php 
###########################################################################
include 'tatarajah.php';// buka pangkalan data 
//echo DB_HOST . "," . DB_USER . "," . DB_PASS . ":" . DB_NAME . "<br>";
$s = @mysql_connect(DB_HOST, DB_USER, DB_PASS) or die (mysql_error()); 
$d = @mysql_select_db(DB_NAME, $s) or die (mysql_error());
$Tajuk_Muka_Surat='MDT 2014';
date_default_timezone_set("Asia/Kuala_Lumpur");
############################################################################
# untuk fungsi
//function kira($kiraan) {return number_format($kiraan, 0, '.', ',');} 
//function kira2($dulu,$sekarang)	{@$kiraan=(($sekarang-$dulu)/$dulu)*100;
//return number_format($kiraan,0,'.',',');}
$carian=$_GET['cari'];
//echo '<span style="background-color: black; color:yellow">(';
//echo 'cari='.$_GET['cari'].")</span>\r";
$s1='<span style="background-color: black; color:yellow">';$hr=')<hr>';$s2='</span>';
###########################################################
## tentukan nilai pencam yang terlibat dalam sql
// ambil halaman semasa, jika tiada, cipta satu! 
$page =( !isset($_REQUEST['page']) )? 1: $_REQUEST['page'];
$baris_max = 10; // berapa item dalam satu halaman
// Tentukan had query berasaskan nombor halaman semasa.
$dari_baris = (($page * $baris_max) - $baris_max); 
// nak tentukan berapa bil baris dlm satu muka surat
$bil = $dari_baris+1; 
###########################################################

$myJoin='nama_pegawai';$myJadual=array('mdt_rangka14');
$medanSemak[]='newss,nama,R.utama as utama,msic,msic08,'.
'msic,msic08,alamat1,alamat2,poskod,fe,nohp as nohpfe';
//------------------------------------------------------------------------------
foreach ($myJadual as $key => $myTable)
{// mula ulang table
$sql="SELECT ".$medanSemak[$key]." FROM ".$myTable." R LEFT JOIN ".$myJoin." J
ON R.fe = J.namaPegawai 
WHERE concat(newss,nama) like '%".$_GET['cari']."%' ";

$result = mysql_query($sql) or die(mysql_error()."<hr>$sql<hr>"); 
$fields = mysql_num_fields ($result);
$rows  = mysql_num_rows ($result);

// nak papar bil. brg
if ($rows=='0' or $_GET['cari']==null): echo "<br><font color=red>
Maaflah, ".$_GET[cari]." tak jumpalah pada jadual :".$myTable."
<font face=Wingdings size=5>L</font></font>";

else: // kalau jumpa
	while($row = mysql_fetch_array($result,MYSQL_NUM))
	{	for ( $f = 0; $f < $fields ; $f++ )
		{// masuk - mula
		$name=mysql_field_name($result,$f);
		$semak[$name]=$row[$f];
		$cari[0]="like '".$row[0]."' ";
		$cari[1]="in ('".$row[3]."','".$row[4]."')";
		$cari[2]="in ('".$row[3]."','".$row[4]."')";
		}// masuk - tamat
	}
endif; //tamat jika jumpa
}// tamat ulang table
//--------------------------------------------------------------------------------------

$rangkaMDT = array(null,'msic_bandingan','msic2008');
$tajuk[1]='msic';$medan[1]='sv_newss,msic as msicL,keterangan as keteranganL';
$tajuk[2]='msic';$medan[2]='msic as msicB,msic2000,keterangan as keteranganB';
//------------------------------------------------------------------------------
unset($rangkaMDT[0]);foreach ($rangkaMDT as $key => $ubah)
{// mula ulang table
$query='SELECT '.$medan[$key].' FROM `'.$ubah.'` WHERE 
'.$tajuk[$key].' '.$cari[$key].' ';

$result = mysql_query($query) or die(mysql_error()."<hr>hai - $query<hr>"); 
$semak = mysql_query($query) or die(mysql_error()."<hr>$query<hr>"); 
$fields = mysql_num_fields($result); $rows  = mysql_num_rows($result);

// nak papar bil. brg
if ($rows=='0'): echo "<br><font color=red>
Maaflah, ".$carian." tak jumpalah pada jadual :".$key."
<font face=Wingdings size=5>L</font></font>";

else: // kalau jumpa
	$bil=1;
	//echo "<hr>$bil|$query<hr>";
	while($row = mysql_fetch_array($result,MYSQL_NUM))
	{	for ( $f = 0; $f < $fields ; $f++ )
		{// masuk - mula
		$name=mysql_field_name($result,$f);
		$msic[]=$row[$f];
		}// masuk - tamat
	}
endif; //tamat jika jumpa
}// tamat ulang table
//------------------------------------------------------------------------------
$tajuk[0]='newss';$medan[0]='nota,respon,fe,tel,fax,responden,email,msic,msic08,'.
'newss,nama,ssm,utama,sv';
//------------------------------------------------------------------------------
# mula ulang 
$query="SELECT ".$medan[0]." FROM `mdt_rangka14` WHERE 
".$tajuk[0]." ".$cari[0]." ";
//echo "<hr>$query<hr>";

$result = mysql_query($query) or die(mysql_error()."<hr>$query<hr>"); 
$semak = mysql_query($query) or die(mysql_error()."<hr>$query<hr>"); 
$fields = mysql_num_fields($result); $rows  = mysql_num_rows($result);

// nak papar bil. brg
if ($rows=='0'): echo "<br><font color=red>
Maaflah, ".$carian." tak jumpalah pada jadual :".$key."
<font face=Wingdings size=5>L</font></font>";

else: // kalau jumpa
	$bil=1;
	while($row = mysql_fetch_array($result,MYSQL_NUM))
	{	for ( $f = 0; $f < $fields ; $f++ )
		{// masuk - mula
		$name=mysql_field_name($result,$f);
		$data['rangka'][$name]=$row[$f];
		}// masuk - tamat
	}
endif; //tamat jika jumpa
# tamat ulang 
//------------------------------------------------------------------------------
$bulanan = array('jan', 'feb', 'mac', 'apr', 
    'mei', 'jun', 'jul', 'ogo', 
    'sep', 'okt', 'nov', 'dis');
$medan='newss,nama,msic,terima,hasil,dptLain,web,stok,staf,gaji,sebab,outlet';
$muladari=1;
//------------------------------------------------------------------------------
foreach ($bulanan as $key => $bulan)
{# mula ulang table
$query="SELECT ".$medan."\rFROM `mdt_".$bulan."14` WHERE newss ".$cari[0]." ";

$result=mysql_query($query) or die(mysql_error()."<hr>$query<hr>"); 
$fields=mysql_num_fields($result); $rows=mysql_num_rows($result);

// nak papar bil. brg
if ($rows=='0' or $_GET['cari']==null or $cari[0]==null): echo "<br><font color=red>
Maaflah, ".$carian." tak jumpalah pada jadual :".$bulan."
<font face=Wingdings size=5>L</font></font>";

else: // kalau jumpa
while($row = mysql_fetch_array($result,MYSQL_NUM)) 
    {// mula papar 
	$data['msic08'][$key+1]=$row[2];$data['tarikh'][$key+1]=$row[3];
	$data['jual'][$key+1]=$row[4];$data['lain'][$key+1]=$row[5];
	$data['web'][$key+1]=$row[6];$data['stok'][$key+1]=$row[7];
	$data['staf'][$key+1]=$row[8];$data['gaji'][$key+1]=$row[9];
	$data['sebab'][$key+1]=$row[10];$data['outlet'][$key+1]=$row[11];
	}// tutup papar 
endif; //tamat jika jumpa
}# tamat ulang table
//------------------------------------------------------------------------------
?>
<html>
<head><title>Cetak Kes:<?=$_GET['cari']?>|Tarikh:<?=$data['tarikh'][$_GET['bln']]?></title>
<script type="text/javascript" src="../../../js/datepick/jquery.js"></script>
<?php 
//include '../css.txt'; 
//include '../gambar_head.txt';
include './cetak.txt';
?>
</head>
<body>
<div id="content">
<?php
data_cetak($semak,$msic,$data);
function data_cetak($semak,$msic,$data)
{/*
echo '<pre>$msic->', print_r($msic).'</pre>';
echo '<pre>$semak->', print_r($semak).'</pre>';
echo '<pre>$data->', print_r($data).'</pre>';*/
$bln=$_GET['bln'];
?>
<!--[if !excel]>&nbsp;&nbsp;<![endif]-->
<!--The following information was generated by Microsoft Office Excel's Publish
as Web Page wizard.-->
<!--If the same item is republished from Excel, all information between the DIV
tags will be replaced.-->
<!----------------------------->
<!--START OF OUTPUT FROM EXCEL PUBLISH AS WEB PAGE WIZARD -->
<!----------------------------->

<div id="borang mdt 2011_31858" align=left x:publishsource="Excel">
<table x:str border=0 cellpadding=0 cellspacing=0 width=813 style='border-collapse:
 collapse;table-layout:fixed;width:610pt'>
 <col width=12 span=2 style='mso-width-source:userset;mso-width-alt:438;
 width:9pt'>
 <col width=24 style='mso-width-source:userset;mso-width-alt:877;width:18pt'>
 <col width=12 span=54 style='mso-width-source:userset;mso-width-alt:438;
 width:9pt'>
 <col width=13 style='mso-width-source:userset;mso-width-alt:475;width:10pt'>
 <col width=12 span=8 style='mso-width-source:userset;mso-width-alt:438;
 width:9pt'>
 <col width=8 style='mso-width-source:userset;mso-width-alt:292;width:6pt'>
 <!--
 <tr height=22>
 <td class=xl1531858 width=12 colspan=10>&nbsp;</td>
 <td class=xl1531858 width=12 colspan=10>&nbsp;</td>
 <td class=xl1531858 width=12 colspan=9 style='border: solid' align=right>ANGGARAN</td>
 <td class=xl1531858 width=12 colspan=2 style='border: solid'>&nbsp;</td>
 <td class=xl1531858 width=12 colspan=2 style='border: solid'>&nbsp;</td>
 <td class=xl1531858 width=12 colspan=14 style='border: solid'>BETUL-BETUL-BETUL</td>
 <td class=xl1531858 width=12 colspan=10>&nbsp;</td>
 <td class=xl1531858 width=12 colspan=10>&nbsp;</td>
 </tr>
 -->
 <tr height=22 style='mso-height-source:userset;height:16.5pt'>
  <td height=22 class=xl1531858 width=12 style='height:16.5pt;width:9pt'></td>
  <td class=xl13531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 colspan=11 width=144 style='width:108pt'
  x:str="UNTUK PERHATIAN : ">UNTUK PERHATIAN :
  <span style='mso-spacerun:yes'>
  <?=$data['rangka']['fe']?>
  (Kes <?=$data['rangka']['utama']?>)
  (msic <?=$data['msic08'][$bln]?>) <?php 
//$sv=;
$sv=substr($data['msic08'][$bln], 0, 2);//echo $sv;
	if ($sv=='46') {$lihat['326']='X'; $lihat['327']=''; $lihat['329']='';}
elseif ($sv=='47') {$lihat['327']='X'; $lihat['326']=''; $lihat['329']='';}
elseif ($sv=='45') {$lihat['329']='X'; $lihat['327']=''; $lihat['326']='';}
else			  {$lihat['X']='';}

//echo $sv.'=>'.$lihat2;
?>
  </span></td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=13 style='width:10pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl13731858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl1531858 width=8 style='width:6pt'></td>
 </tr>
 <tr height=30 style='mso-height-source:userset;height:23.1pt'>
  <td height=30 class=xl1531858 style='height:23.1pt'></td>
  <td class=xl13831858>&nbsp;</td>
  <td class=xl8931858></td>
  <?php
unset($letak);
$pecah = str_split($data['rangka']['newss']);
$j=count($pecah)-1;
//echo '<pre>jum $newss('.$data['rangka']['newss'].')='.count($pecah).'-', print_r($pecah).'</pre>';
//echo '<b>beza $i-$j='.(5-$j).'</b><br>';
for ($i=12;$i > 0; $i--)
{	//echo '$i='.$i.'-$j='.$j--.'<br>';
	$letak[$i]=$pecah[$j--];
}//echo '<pre>jum $letak='.count($letak).'-', print_r($letak).'</pre>';
?>
  <td colspan=4 class=xl20031858><?=$letak[1].$letak[2].$letak[3]?></td>
  <td colspan=4 class=xl20131858 style="border-left:none"><?=$letak[6].$letak[5].$letak[6]?></td>
  <td colspan=4 class=xl20131858 style="border-left:none"><?=$letak[7].$letak[8].$letak[9]?></td>
  <td colspan=4 class=xl20131858 style="border-left:none"><?=$letak[10].$letak[11].$letak[12]?></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
  <td class=xl8931858></td>
 
  <td class=xl13931858></td>
  <td class=xl13931858></td>
  <td class=xl13931858></td>
  <td class=xl13931858></td>
  <td class=xl8931858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td><!-- tambah 2 td -->
  <td class=xl1531858></td><!-- tambah 2 td -->
  <td class=xl1531858></td><!-- tambah 2 td -->
  <td colspan=5 class=xl19131858 width=85 style='border-right:.5pt solid black;
  width:64pt'>BULAN <font class="font631858">MONTH</font></td>
  <td colspan=5 class=xl19431858 width=60 style='border-right:.5pt solid black;
  border-left:none;width:45pt'>TAHUN <font class="font631858">YEAR</font></td>
 </tr>
 <tr height=29 style='mso-height-source:userset;height:21.75pt'>
  <td height=29 class=xl1531858 style='height:21.75pt'></td>
  <td class=xl13831858>&nbsp;</td>
  <td class=xl21731858 colspan=10>Nama Syarikat :<?=$data['rangka']['nama']?></td>
  <td class=xl14131858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl14131858></td>
  <td class=xl14131858></td>
  <td class=xl14131858></td>
  <td class=xl14131858></td>
  <td class=xl14131858></td>
  <td class=xl14131858></td>
  <td class=xl14131858></td>
  <td class=xl14131858></td>
  <td class=xl14131858></td>
  <td class=xl14131858></td>
  <td class=xl14131858></td>
  <td class=xl14131858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td><!-- tambah 2 td -->
  <td class=xl1531858></td><!-- tambah 2 td -->
  <td class=xl1531858></td><!-- tambah 2 td -->
  <td class=xl1531858></td><!-- tambah 2 td -->
  <td colspan=3 class=xl16731858></td>
  <td class=xl1531858></td>
  <td class=xl6731858></td>
  <td class=xl14231858></td>
  <!-- mula bulan 
  <td class=xl6731858>7</td>
  <td class=xl14331858 style='border-top:none'>&nbsp;</td>
  <td colspan="4" class=xl19731858 style='border-left:none' x:num><?=$bln?>&nbsp;</td>
  <td class=xl14431858 style='border-top:none'>&nbsp;</td>
  <td colspan=5 class=xl19731858 style='border-right:.5pt solid black;
  border-left:none' x:num>2012</td>
  <td class=xl14031858>&nbsp;</td>
  <td class=xl1531858></td>
  
  <td class=xl14031858>&nbsp;</td>
  <td class=xl1531858></td>
  tamat bulan -->
  <td colspan=5 class="xl19431858" width="60" style="border-right:.5pt solid black;
  width:64pt" valign="center"><?=$bln?></td>
  <td colspan=5 class="xl19431858" width="60" style="border-right:.5pt solid black;
  border-left:none;width:45pt"><?php 
	//$time = new DateTime('now');
	//$newtime = $time->modify('-1 year')->format('Y');
	//echo ($bln==12) ? $newtime : date("Y");
	//$year = mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+1)
	echo date("Y", strtotime("-1 year")), "\n";
?></td>
 </tr>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td height=20 class=xl6731858 style='height:15.0pt'></td>
  <td colspan=65 class=xl20431858 style='border-right:1.0pt solid black'>1:
  AKTIVITI UTAMA &amp; JENIS PERNIAGAAN / <font class="font731858">MAIN
  ACTIVITY &amp; KIND OF BUSINESS</font></td>
  <td class=xl1531858></td>
 </tr>
 <tr height=18 style='mso-height-source:userset;height:14.1pt'>
  <td height=18 class=xl1531858 style='height:14.1pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl11331858 x:num="1.1">1.1</td>
  <td class=xl6831858 colspan=38>Sila pangkah (X) pada kotak bersesuaian<font
  class="font031858"> / </font><font class="font931858">Please cross (X) in the
  appropriate box .</font></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=32 style='mso-height-source:userset;height:24.0pt'>
  <td height=32 class=xl1531858 style='height:24.0pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td colspan=11 class=xl18431858 width=132 style='width:99pt'>Perdagangan
  Borong<font class="font031858"> / </font><font class="font1231858">Wholesale
  Trade</font></td>
  <td class=xl11631858 width=12 style='width:9pt'></td>
  <td class=xl12931858 width=12 style='width:9pt' x:num>3</td>
  <td class=xl13031858 width=12 style='width:9pt' x:num>2</td>
  <td class=xl13131858 width=12 style='width:9pt' x:num>6</td>
  <td colspan=3 class=xl18831858 width=36 style='border-right:.5pt solid black;
  border-left:none;width:27pt'>
  <?php echo $lihat['326']?>&nbsp;</td>
  <td class=xl11631858 width=12 style='width:9pt'></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td colspan=11 class=xl18431858 width=132 style='width:99pt'>Perdagangan
  Runcit<font class="font031858"> / </font><font class="font931858">Retail
  Trade</font></td>
  <td class=xl1531858></td>
  <td class=xl12931858 width=12 style='width:9pt' x:num>3</td>
  <td class=xl13031858 width=12 style='width:9pt' x:num>2</td>
  <td class=xl13131858 width=12 style='width:9pt' x:num>7</td>
  <td colspan=3 class=xl18831858 width=36 style='border-right:.5pt solid black;
  border-left:none;width:27pt'>
  <?php echo $lihat['327']?>&nbsp;</td>
  <td class=xl11631858 width=12 style='width:9pt'></td>
  <td class=xl1531858></td>
  <td colspan=11 class=xl18431858 width=132 style='width:99pt'>Kenderaan
  Bermotor<font class="font031858"> / </font><font class="font931858">Motor
  Vehicle</font></td>
  <td class=xl1531858></td>
  <td class=xl12931858 width=12 style='width:9pt' x:num>3</td>
  <td class=xl13031858 width=13 style='width:10pt' x:num>2</td>
  <td class=xl13131858 width=12 style='width:9pt' x:num>9</td>
  <td colspan=3 class=xl18831858 width=36 style='border-right:.5pt solid black;
  border-left:none;width:27pt'>
  <?php echo $lihat['329']?>&nbsp;</td>
  <td class=xl6731858 colspan=3>F1015</td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=18 style='mso-height-source:userset;height:14.1pt'>
  <td height=18 class=xl1531858 style='height:14.1pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl6831858 colspan=55>Sila jelaskan jenis perniagaan utama
  pertubuhan tuan / puan dalam tempoh laporan (seperti kedai runcit, pasaraya
  dsb.)</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl1531858 style='height:9.95pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl7931858 colspan=37>Please specify your main kind of business
  during the reporting period (e.g. provision store, supermarket, etc)</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr class=xl8031858 height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl8031858 style='height:9.0pt'></td>
  <td class=xl8131858>&nbsp;</td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td class=xl8031858></td>
  <td colspan=17 class=xl21031858 width='205' style='border-right:1.0pt solid black;
  width:154pt' x:str="Untuk kegunaan pejabat">Untuk kegunaan pejabat
  <span style='mso-spacerun:yes'></span></td>
  <td class=xl8031858></td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl1531858 style='height:9.0pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td colspan=17 class=xl21331858 width=205 style='border-right:1.0pt solid black;
  width:154pt'>For office use</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=11 style='mso-height-source:userset;height:8.25pt'>
  <td height=11 class=xl1531858 style='height:8.25pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl7631858><?=$data['rangka']['nota']?>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>&nbsp;</td>
  <td class=xl7631858>.&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8231858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8331858 style='border-top:none'>&nbsp;</td>
  <td class=xl8431858 style='border-top:none'>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=30 style='mso-height-source:userset;height:23.1pt'>
  <td height=30 class=xl1531858 style='height:23.1pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl14531858 style='border-top:none'>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6531858>&nbsp;
  <?php
unset($letak);
$pecah = str_split($data['msic08'][$bln]);
$pecah2= str_split('01234');
#msic ada 5 nombor. jadi array dari (0-4)
$j=count($pecah2)-1;
/*echo '<pre>jum $msic08('.$data['msic08'][$bln].')='.
count($pecah).'-', print_r($pecah).'</pre>';
echo '<b>beza $i-$j='.(5-$j).'</b><br>';*/
for ($i=5;$i > 0; $i--)
{//echo '$i='.$i.'-$j='.$j--.'<br>';
	$letak[$i]=$pecah[$j--];
}//echo '<pre>jum $letak='.count($letak).'-',print_r($letak).'</pre>';
$style='color:#000000';//powerblue
?>
  </td>
  <td class=xl14831858>&nbsp;</td>
  <td class=xl14931858 style="<?=$style?>"><?=$letak[1]?>&nbsp;</td>
  <td class=xl15031858>&nbsp;</td>
  <td class=xl14831858 style='border-left:none'>&nbsp;</td>
  <td class=xl14931858 style="<?=$style?>"><?=$letak[2]?>&nbsp;</td>
  <td class=xl15031858>&nbsp;</td>
  <td class=xl14831858 style='border-left:none'>&nbsp;</td>
  <td class=xl14931858 style="<?=$style?>"><?=$letak[3]?>&nbsp;</td>
  <td class=xl15031858>&nbsp;</td>
  <td class=xl14831858 style='border-left:none'>&nbsp;</td>
  <td class=xl14931858 style="<?=$style?>"><?=$letak[4]?>&nbsp;</td>
  <td class=xl15031858>&nbsp;</td>
  <td class=xl14831858 style='border-left:none'>&nbsp;</td>
  <td class=xl14931858 style="<?=$style?>"><?=$letak[5]?>&nbsp;</td>
  <td class=xl15031858>&nbsp;</td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=16 style='mso-height-source:userset;height:12.0pt'>
  <td height=16 class=xl1531858 style='height:12.0pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8531858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl14631858 colspan=3>F1016</td>
  <td class=xl7831858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td height=20 class=xl1531858 style='height:15.0pt'></td>
  <td colspan=65 class=xl18531858 style='border-right:1.0pt solid black'>2:
  JUMLAH PENDAPATAN KASAR / <font class="font731858">TOTAL GROSS INCOME</font></td>
  <td class=xl1531858></td>
 </tr>
 <tr height=4 style='mso-height-source:userset;height:3.0pt'>
  <td height=4 class=xl1531858 style='height:3.0pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8631858></td>
  <td class=xl8631858></td>
  <td class=xl8631858></td>
  <td class=xl8631858></td>
  <td class=xl8631858></td>
  <td class=xl8631858></td>
  <td class=xl8631858></td>
  <td class=xl8631858></td>
  <td class=xl8631858></td>
  <td class=xl8631858></td>
  <td class=xl8631858></td>
  <td class=xl8631858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=30 style='mso-height-source:userset;height:23.1pt'>
  <td height=30 class=xl1531858 style='height:23.1pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl11331858 x:num>2.1</td>
  <td class=xl6831858 colspan=7>Jualan / <font class="font931858">Sales<span
  style='mso-spacerun:yes'> </span></font></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td colspan=2 class=xl16431858 style='border-right:1.0pt dashed black; width:18pt'
  width=24>RM</td>
   <!-- mula $letak[] -->
  <?php
unset($letak);
$pecah = str_split($data['jual'][$bln]);
$j=count($pecah)-1;
//echo '<pre>jum $jual('.$data['jual'][$bln].')='.count($pecah).'-', print_r($pecah).'</pre>';
//echo '<b>beza $i-$j='.(5-$j).'</b><br>';
for ($i=12;$i > 0; $i--)
{	//echo '$i='.$i.'-$j='.$j--.'<br>';
	@$letak[$i]=$pecah[$j--];
}//echo '<pre>jum $letak='.count($letak).'-', print_r($letak).'</pre>';
?>
  <td colspan=3 class=xl16631858 style='border-right:.5pt solid black'>
  <?=$letak[1]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black'>
  <?=$letak[2]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-left:none'>
  <?=$letak[3]?>&nbsp;</td>
  <td colspan=3 class=xl16631858 style='border-right:.5pt solid black'>
  <?=$letak[4]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[5]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[6]?>&nbsp;</td>
  <td colspan=3 class=xl16631858 style='border-left:none'>
  <?=$letak[7]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black'>
  <?=$letak[8]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[9]?>&nbsp;</td>
  <td colspan=3 class=xl16631858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[10]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[11]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[12]?>&nbsp;</td>
  <!-- tamat $letak[] -->
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=15 style='mso-height-source:userset;height:11.25pt'>
  <td height=15 class=xl1531858 style='height:11.25pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl11431858 colspan=10>Termasuk<font class="font831858"> / </font><font
  class="font931858">Include</font><font class="font831858">:</font></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8731858 width=12 style='width:9pt'></td>
  <td class=xl8731858 width=12 style='width:9pt'></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8831858></td>
  <td class=xl8831858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8831858></td>
  <td class=xl8831858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8831858></td>
  <td class=xl8831858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td colspan=3 class=xl17831858>F1019</td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=15 style='mso-height-source:userset;height:11.25pt'>
  <td height=15 class=xl1531858 style='height:11.25pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8931858 align=right x:num>0</td>
  <td class=xl11831858 colspan=16>jualan barangan <font class="font031858">/ </font><font
  class="font931858">sales of goods.</font></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=30 style='mso-height-source:userset;height:23.1pt'>
  <td height=30 class=xl1531858 style='height:23.1pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl11331858 x:num="2.2">2.2</td>
  <td class=xl6831858 colspan=24>Pendapatan Operasi Lain / <font
  class="font931858">Other Operating Income</font></td>
  <!-- mula $letak[] -->
  <?php
unset($letak);
$pecah = str_split($data['lain'][$bln]);
$j=count($pecah)-1;
//echo '<pre>jum $lain('.$data['lain'][$bln].')='.count($pecah).'-', print_r($pecah).'</pre>';
//echo '<b>beza $i-$j='.(5-$j).'</b><br>';
for ($i=12;$i > 0; $i--)
{	//echo '$i='.$i.'-$j='.$j--.'<br>';
	@$letak[$i]=$pecah[$j--];
}//echo '<pre>jum $letak='.count($letak).'-', print_r($letak).'</pre>';
?>
  <td colspan=2 class=xl16431858 style='border-right:1.0pt dashed black; width:18pt'
  width=24>RM</td>
  <td colspan=3 class=xl16631858 style='border-right:.5pt solid black'>
  <?=$letak[1]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black'>
  <?=$letak[2]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-left:none'>
  <?=$letak[3]?>&nbsp;</td>
  <td colspan=3 class=xl16631858 style='border-right:.5pt solid black'>
  <?=$letak[4]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[5]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[6]?>&nbsp;</td>
  <td colspan=3 class=xl16631858 style='border-left:none'>
  <?=$letak[7]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black'>
  <?=$letak[8]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[9]?>&nbsp;</td>
  <td colspan=3 class=xl16631858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[10]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[11]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[12]?>&nbsp;</td>
  <!-- tamat $letak[] -->
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=16 style='mso-height-source:userset;height:12.0pt'>
  <td height=16 class=xl1531858 style='height:12.0pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl9031858 colspan=10>Termasuk<font class="font831858"> /</font><font
  class="font1331858"> </font><font class="font931858">Include</font><font
  class="font831858">:</font></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8731858 width=12 style='width:9pt'></td>
  <td class=xl8731858 width=12 style='width:9pt'></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8831858></td>
  <td class=xl8831858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8831858></td>
  <td class=xl8831858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8831858></td>
  <td class=xl8831858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td colspan=3 class=xl16731858>F1023</td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=15 style='mso-height-source:userset;height:11.25pt'>
  <td height=15 class=xl1531858 style='height:11.25pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl6831858></td>
  <td class=xl9131858></td>
  <td class=xl1531858 align=right x:num>0</td>
  <td class=xl1531858></td>
  <td class=xl9031858 colspan=57>yuran prosesan, yuran pengurusan, royalti dan
  yuran paten<font class="font031858"> / </font><font class="font931858">processing
  fees, management fees, royalties and patent fees</font></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=15 style='mso-height-source:userset;height:11.25pt'>
  <td height=15 class=xl1531858 style='height:11.25pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858 align=right x:num>0</td>
  <td class=xl1531858></td>
  <td class=xl9031858 colspan=34>penyewaan bangunan, peralatan, dll / <font
  class="font931858">rental of buildings, equipment, etc</font></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl9331858></td>
  <td class=xl9331858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl9331858></td>
  <td class=xl9331858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td height=20 class=xl1531858 style='height:15.0pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl9031858 colspan=13>Tidak termasuk<font class="font831858"> /</font><font
  class="font731858"> </font><font class="font931858">Exclude :<span
  style='mso-spacerun:yes'> </span></font></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8731858 width=12 style='width:9pt'></td>
  <td class=xl8731858 width=12 style='width:9pt'></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8831858></td>
  <td class=xl8831858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8831858></td>
  <td class=xl8831858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8831858></td>
  <td class=xl8831858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td colspan=3 class=xl16731858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.75pt'>
  <td height=13 class=xl1531858 style='height:9.75pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858 align=right x:num>0</td>
  <td class=xl1531858></td>
  <td class=xl6831858 colspan=17>pajakan kewangan /<font class="font931858">
  financial leasing</font></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=15 style='mso-height-source:userset;height:11.25pt'>
  <td height=15 class=xl1531858 style='height:11.25pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858 align=right x:num>0</td>
  <td class=xl1531858></td>
  <td class=xl6831858 colspan=59 style='border-right:1.0pt solid black'>faedah/dividen
  yang diterima, subsidi, geran kerajaan atau derma<font class="font031858"> /</font><font
  class="font531858"> </font><font class="font931858">interest/dividends
  received, subsidies, government grants or</font><span style='display:none'><font
  class="font931858"> donations</font></span></td>
  <td class=xl1531858></td>
 </tr>
 <tr height=15 style='mso-height-source:userset;height:11.25pt'>
  <td height=15 class=xl1531858 style='height:11.25pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858 align=right x:num>0</td>
  <td class=xl1531858></td>
  <td class=xl6831858 colspan=58>keuntungan dari jualan harta atau transaksi
  pertukaran wang asing <font class="font031858">/ </font><font
  class="font931858">gains on sale of assets or foreign exchange transactions</font></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=21 style='mso-height-source:userset;height:15.75pt'>
  <td height=21 class=xl1531858 style='height:15.75pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl22231858 align=right x:num>0</td>
  <td class=xl1531858></td>
  <td class=xl11431858 colspan=42>pemulihan hutang lapuk atau tuntutan insurans
  <font class="font031858">/ </font><font class="font931858">bad debts
  recovered or insurance claim</font></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl9431858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=15 style='mso-height-source:userset;height:11.45pt'>
  <td height=15 class=xl1531858 style='height:11.45pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl11331858 x:num="2.3">2.3</td>
  <td class=xl11331858 colspan=29>Sila nyatakan peratusan jualan melalui
  website atau internet / <font class="font931858"><span
  style='mso-spacerun:yes'> </span></font></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
   <?php
unset($letak);
$pecah = str_split($data['web'][$bln]);
$j=count($pecah)-1;
//echo '<pre>jum $web('.$data['web'][$bln].')='.count($pecah).'-', print_r($pecah).'</pre>';
//echo '<b>beza $i-$j='.(5-$j).'</b><br>';
for ($i=3;$i > 0; $i--)
{	//echo '$i='.$i.'-$j='.$j--.'<br>';
	@$letak[$i]=$pecah[$j--];
}//echo '<pre>jum $letak='.count($letak).'-', print_r($letak).'</pre>';
?>
  <td colspan=3 rowspan=2 class=xl17031858 style='border-right:.5pt solid black;
  border-bottom:.5pt solid black'><?=$letak[1]?>&nbsp;</td>
  <td colspan=3 rowspan=2 class=xl17031858 style='border-right:.5pt solid black;
  border-bottom:.5pt solid black'><?=$letak[2]?>&nbsp;</td>
  <td colspan=3 rowspan=2 class=xl17031858 style='border-right:.5pt solid black;
  border-bottom:.5pt solid black'><?=$letak[3]?>&nbsp;</td>
  <td colspan=3 rowspan=2 class=xl17631858>%</td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=15 style='mso-height-source:userset;height:11.45pt'>
  <td height=15 class=xl1531858 style='height:11.45pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl12031858 colspan=23>Please state the percentage of sales through
  website or internet</td>
  <td class=xl11931858 width=12 style='width:9pt'></td>
  <td class=xl11931858 width=12 style='width:9pt'></td>
  <td class=xl11931858 width=12 style='width:9pt'></td>
  <td class=xl11931858 width=12 style='width:9pt'></td>
  <td class=xl11931858 width=12 style='width:9pt'></td>
  <td class=xl11931858 width=12 style='width:9pt'></td>
  <td class=xl11931858 width=12 style='width:9pt'></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8831858></td>
  <td class=xl8831858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8831858></td>
  <td class=xl8831858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=19 style='mso-height-source:userset;height:14.25pt'>
  <td height=19 class=xl1531858 style='height:14.25pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl11531858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8831858></td>
  <td class=xl8831858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td colspan=3 class=xl16931858>F1020</td>
  <td class=xl6731858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td height=20 class=xl1531858 style='height:15.0pt'></td>
  <td colspan=65 class=xl18531858 style='border-right:1.0pt solid black'>3:
  NILAI STOK BARANGAN / <font class="font731858">VALUE OF STOCK OF GOODS</font></td>
  <td class=xl1531858></td>
 </tr>
 <tr height=18 style='mso-height-source:userset;height:14.1pt'>
  <td height=18 class=xl6831858 style='height:14.1pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl6831858 colspan=50>SILA JAWAB SOALAN INI PADA SETIAP PENGHUJUNG
  SUKU TAHUN SAHAJA (MAC, JUN, SEPTEMBER &amp;<span style='mso-spacerun:yes'> 
  </span>DISEMBER)</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=17 style='mso-height-source:userset;height:12.75pt'>
  <td height=17 class=xl1531858 style='height:12.75pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl7931858 colspan="45"
  x:str="PLEASE ANSWER THIS QUESTION  AT THE END OF EACH QUARTER ONLY ( MARCH, JUNE, SEPTEMBER &amp; DECEMBER) ">
  PLEASE ANSWER THIS QUESTION<span style='mso-spacerun:yes'>  </span>AT THE END OF
  EACH QUARTER ONLY ( MARCH, JUNE, SEPTEMBER &amp; DECEMBER)<span
  style='mso-spacerun:yes'> </span></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=30 style='mso-height-source:userset;height:23.1pt'>
  <td height=30 class=xl1531858 style='height:23.1pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td colspan=24 class=xl18231858 width=252 style='width:200pt'>
  Nilai Stok Barangan (pada akhir suku tahun)<br>
   <font class="font931858">
  Value of Stock of Goods (as at the end of the quarter)
  <span style='mso-spacerun:yes'></span></font></td>
  <!-- 20-24
  <td class=xl6831858></td>
  <td class=xl6831858></td>
  <td class=xl6831858></td>
  <td class=xl6831858></td>
  -->
  <td class=xl12131858>&nbsp;</td>
 <!-- mula $letak[] -->
  <?php
unset($letak);
$pecah = str_split($data['stok'][$bln]);
$j=count($pecah)-1;
//echo '<pre>jum $stok('.$data['stok'][$bln].')='.count($pecah).'-', print_r($pecah).'</pre>';
//echo '<b>beza $i-$j='.(5-$j).'</b><br>';
for ($i=12;$i > 0; $i--)
{	//echo '$i='.$i.'-$j='.$j--.'<br>';
	@$letak[$i]=$pecah[$j--];
}//echo '<pre>jum $letak='.count($letak).'-', print_r($letak).'</pre>';
?>
  <td colspan=2 class=xl16431858 style='border-right:1.0pt dashed black; width:18pt'
  width=24>RM</td>
  <td colspan=3 class=xl16631858 style='border-right:.5pt solid black'>
  <?=$letak[1]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black'>
  <?=$letak[2]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-left:none'>
  <?=$letak[3]?>&nbsp;</td>
  <td colspan=3 class=xl16631858 style='border-right:.5pt solid black'>
  <?=$letak[4]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[5]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[6]?>&nbsp;</td>
  <td colspan=3 class=xl16631858 style='border-left:none'>
  <?=$letak[7]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black'>
  <?=$letak[8]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[9]?>&nbsp;</td>
  <td colspan=3 class=xl16631858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[10]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[11]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[12]?>&nbsp;</td>
  <!-- tamat $letak[] -->
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=15 style='mso-height-source:userset;height:11.25pt'>
  <td height=15 class=xl1531858 style='height:11.25pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl9531858 width=24 style='width:18pt'></td>
  <td class=xl1531858></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl9531858 width=12 style='width:9pt'></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl8731858 width=12 style='width:9pt'></td>
  <td class=xl8731858 width=12 style='width:9pt'></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6731858></td>
  <td colspan=3 class=xl16931858>F1022</td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=29 style='mso-height-source:userset;height:21.75pt'>
  <td height=29 class=xl1531858 style='height:21.75pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td colspan=60 class=xl9731858 width=733 style='width:550pt'>Sila laporkan
  nilai stok barangan untuk dijual, inventori, bahan bakar, bekas yang tidak
  tahan lama, pembungkus, bekalan pejabat dan lain-lain. Jika angka yang tepat
  tidak dapat diberikan, sila berikan anggaran yang munasabah.</td>
  <td class=xl9731858 width=12 style='width:9pt'></td>
  <td class=xl9731858 width=12 style='width:9pt'></td>
  <td class=xl9731858 width=12 style='width:9pt'></td>
  <td class=xl9631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl9731858 width=8 style='width:6pt'></td>
 </tr>
 <tr height=29 style='mso-height-source:userset;height:21.75pt'>
  <td height=29 class=xl1531858 style='height:21.75pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td colspan=60 class=xl12231858 width=733 style='width:550pt'>Please fill in
  the value of stock of goods intended for sale, inventories, fuels,
  non-durable containers, packaging, office and other supplies etc. If exact
  figures are not available, please provide a reasonable estimate.</td>
  <td class=xl12231858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl12231858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl9631858 width=12 style='width:9pt'>&nbsp;</td>
  <td class=xl9731858 width=8 style='width:6pt'></td>
 </tr>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td height=20 class=xl1531858 style='height:15.0pt'></td>
  <td colspan=65 class=xl18531858 style='border-right:1.0pt solid black'>4:
  BILANGAN PEKERJA<span style='mso-spacerun:yes'>  </span>DAN GAJI &amp; UPAH /
  <font class="font731858">NUMBER OF WORKERS AND SALARIES &amp; WAGES</font></td>
  <td class=xl1531858></td>
 </tr>
 <tr height=19 style='mso-height-source:userset;height:14.25pt'>
  <td height=19 class=xl1531858 style='height:14.25pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl11331858 x:num="4.1">4.1</td>
  <td class=xl6831858 colspan=20>Jumlah Pekerja <font class="font031858">/</font><font
  class="font931858"> Total Number of Workers</font></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6831858 colspan=21>Jumlah Gaji &amp; Upah <font class="font031858">/
  </font><font class="font931858">Total Salaries &amp; Wages</font></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=30 style='mso-height-source:userset;height:23.1pt'>
  <td height=30 class=xl1531858 style='height:23.1pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <!-- mula $letak[] -->
  <?php
unset($letak);
$pecah = str_split($data['staf'][$bln]);
$j=count($pecah)-1;
//echo '<pre>jum $staf('.$data['staf'][$bln].')='.count($pecah).'-', print_r($pecah).'</pre>';
//echo '<b>beza $i-$j='.(5-$j).'</b><br>';
for ($i=6;$i > 0; $i--)
{	//echo '$i='.$i.'-$j='.$j--.'<br>';
	@$letak[$i]=$pecah[$j--];
}//echo '<pre>jum $letak='.count($letak).'-', print_r($letak).'</pre>';
?>
  <td colspan=3 class=xl16631858>
  <?=$letak[1]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black'>
  <?=$letak[2]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[3]?>&nbsp;</td>
  <td colspan=3 class=xl16631858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[4]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[5]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[6]?>&nbsp;</td>
  <!-- tamat $letak[] -->
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
 <!-- mula $letak[] -->
  <?php
//for ($i=5;$i > 0; $i--){unset($letak[$i]);}
$pecah = str_split($data['gaji'][$bln]);
$j=count($pecah)-1;
//echo '<pre>jum $gaji('.$data['gaji'][$bln].')='.count($pecah).'-', print_r($pecah).'</pre>';
//echo '<b>beza $i-$j='.(5-$j).'</b><br>';
for ($i=12;$i > 0; $i--)
{	//echo '$i='.$i.'-$j='.$j--.'<br>';
	@$letak[$i]=$pecah[$j--];
}//echo '<pre>jum $letak='.count($letak).'-', print_r($letak).'</pre>';
?>
  <td colspan=2 class=xl16431858 style='border-right:1.0pt dashed black; width:18pt'
  width=24>RM</td>
  <td colspan=3 class=xl16631858 style='border-right:.5pt solid black'>
  <?=$letak[1]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black'>
  <?=$letak[2]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-left:none'>
  <?=$letak[3]?>&nbsp;</td>
  <td colspan=3 class=xl16631858 style='border-right:.5pt solid black'>
  <?=$letak[4]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[5]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[6]?>&nbsp;</td>
  <td colspan=3 class=xl16631858 style='border-left:none'>
  <?=$letak[7]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black'>
  <?=$letak[8]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[9]?>&nbsp;</td>
  <td colspan=3 class=xl16631858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[10]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:.5pt solid black; border-left:none'>
  <?=$letak[11]?>&nbsp;</td>
  <td colspan=3 class=xl16031858 style='border-right:1.0pt dashed black; border-left:none'>
  <?=$letak[12]?>&nbsp;</td>
  <!-- tamat $letak[] -->
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=24 style='mso-height-source:userset;height:18.0pt'>
  <td height=24 class=xl1531858 style='height:18.0pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6831858 colspan=10>Termasuk<font class="font831858"> </font><font
  class="font631858">/</font><font class="font831858"> </font><font
  class="font931858">lnclude :</font><font class="font1331858"><span
  style='mso-spacerun:yes'> </span></font></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td colspan=3 class=xl21831858>F1017</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6731858></td>
  <td colspan=3 class=xl21831858>F1018</td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr class=xl1531858 height=15 style='mso-height-source:userset;height:11.25pt'>
  <td height=15 class=xl1531858 style='height:11.25pt'></td>
  <td class=xl6531858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858 align=right x:num>0</td>
  <td class=xl6831858 colspan=35>pemilik dan rakan niaga yang aktif <font
  class="font031858">/</font><font class="font931858">working proprietors and
  active business</font><span style='display:none'><font class="font931858">
  partners</font></span></td>
  <td class=xl1531858 align=right x:num>0</td>
  <td class=xl6831858 colspan=21>pekerja sepenuh masa <font class="font031858">/</font><font
  class="font931858"> full-time employees</font></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr class=xl8931858 height=19 style='mso-height-source:userset;height:14.25pt'>
  <td height=19 class=xl8931858 style='height:14.25pt'></td>
  <td class=xl9831858>&nbsp;</td>
  <td class=xl9931858>&nbsp;</td>
  <td class=xl9931858>&nbsp;</td>
  <td class=xl15231858 align=right x:num>0</td>
  <td class=xl15331858 colspan=26>pekerja keluarga tidak bergaji /<font
  class="font931858"> unpaid family workers</font></td>
  <td class=xl15231858>&nbsp;</td>
  <td class=xl15231858>&nbsp;</td>
  <td class=xl15231858>&nbsp;</td>
  <td class=xl15231858>&nbsp;</td>
  <td class=xl15231858>&nbsp;</td>
  <td class=xl15231858>&nbsp;</td>
  <td class=xl15231858>&nbsp;</td>
  <td class=xl15231858>&nbsp;</td>
  <td class=xl15231858>&nbsp;</td>
  <td class=xl15231858 align=right x:num>0</td>
  <td class=xl15331858 colspan=19>pekerja sambilan /<font class="font1331858"> </font><font
  class="font931858">part-time employees</font></td>
  <td class=xl9931858>&nbsp;</td>
  <td class=xl9931858>&nbsp;</td>
  <td class=xl9931858>&nbsp;</td>
  <td class=xl9931858>&nbsp;</td>
  <td class=xl9931858>&nbsp;</td>
  <td class=xl10031858>&nbsp;</td>
  <td class=xl8931858></td>
 </tr>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td height=20 class=xl1531858 style='height:15.0pt'></td>
  <td colspan=65 class=xl18531858 style='border-right:1.0pt solid black'>5.
  MAKLUMAT TAMBAHAN / <font class="font731858">ADDITIONAL INFOMATION</font></td>
  <td class=xl1531858></td>
 </tr>
 <tr class=xl1531858 height=18 style='mso-height-source:userset;height:14.1pt'>
  <td height=18 class=xl1531858 style='height:14.1pt'></td>
  <td class=xl7331858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td colspan=2 class=xl20331858 x:num>5.2</td>
  <td class=xl6831858 colspan=45 style='border-right:1.0pt solid black'
  x:str="Jika jumlah jualan yang dilaporan untuk bulan ini meningkat atau menurun sekurang-kurangnya ">Jika
  jumlah jualan yang dilaporan untuk bulan ini meningkat atau menurun
  sekurang-kurangnya<span style='mso-spacerun:yes'> </span></td>
  <td class=xl1531858></td>
 </tr>
 <tr class=xl1531858 height=18 style='mso-height-source:userset;height:13.5pt'>
  <td height=18 class=xl1531858 style='height:13.5pt'></td>
  <td class=xl7531858>&nbsp;</td>
  <td class=xl11331858 x:num="5.1">5.1</td>
  <td class=xl11331858 colspan=17>Jumlah outlet lain yang beroperasi</td>
  <td class=xl1531858></td>
  <td class=xl6831858 colspan=41>30% berbanding bulan sebelumnya, sila nyatakan
  sebab berlakunya perbezaan tersebut</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr class=xl1531858 height=19 style='mso-height-source:userset;height:14.25pt'>
  <td height=19 class=xl1531858 style='height:14.25pt'></td>
  <td class=xl7531858>&nbsp;</td>
  <td class=xl7431858></td>
  <td class=xl15131858 colspan=18>Total number of other outlets in operation</td>
  <td class=xl12331858 colspan=37>if total sales for this month increased or
  decreased at least 30%, please provide reason</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6731858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr class=xl1531858 height=26 style='mso-height-source:userset;height:20.1pt'>
  <td height=26 class=xl1531858 style='height:20.1pt'></td>
  <td class=xl7531858>&nbsp;</td>
  <td class=xl7431858></td>
  <td class=xl21931858 colspan=14 align=center>
  <font size=4><?php echo $data['outlet'][$bln] ?>&nbsp;</font></td>
  <!-- mula colspan 14
  <td class=xl22031858>&nbsp;</td>
  <td class=xl22031858>&nbsp;</td>
  <td class=xl22031858>&nbsp;</td>
  <td class=xl22031858>&nbsp;</td>
  <td class=xl22031858>&nbsp;</td>
  <td class=xl22031858>&nbsp;</td>
  <td class=xl22031858>&nbsp;</td>
  <td class=xl22031858>&nbsp;</td>
  <td class=xl22031858>&nbsp;</td>
  <td class=xl22031858>&nbsp;</td>
  <td class=xl22031858>&nbsp;</td>
  <td class=xl22031858>&nbsp;</td>
  <td class=xl22031858>&nbsp;</td>
  tamat colspan 14 -->
  <td class=xl22131858>&nbsp;</td>
  <td class=xl7031858></td>
  <td class=xl7031858></td>
  <td class=xl7031858></td>
   <!-- mula $letak[] -->
 <?php
$dulu = @$data['jual'][$bln-1]+@$data['lain'][$bln-1];
$sekarang = $data['jual'][$bln]+$data['lain'][$bln];
$letak['beza'] = kira2($dulu,$sekarang);
?>
  <!-- tamat $letak[] -->
  <td class=xl12431858 colspan=43>
  <font size=4><?php echo $letak['beza']?>%</font> => <?=$data['sebab'][$bln]?>
  </td>
  <!-- mula colspan -43 --
  <td class=xl12531858>&nbsp;</td>
  <td class=xl12531858>&nbsp;</td>
  <td class=xl12531858>&nbsp;</td>
  <td class=xl12531858>&nbsp;</td>
  <td class=xl12631858>&nbsp;</td>
  <td class=xl12631858>&nbsp;</td>
  <td class=xl12631858>&nbsp;</td>
  <td class=xl12631858>&nbsp;</td>
  <td class=xl12631858>&nbsp;</td>
  <td class=xl12631858>&nbsp;</td>
  <td class=xl12631858>&nbsp;</td>
  <td class=xl12631858>&nbsp;</td>
  <td class=xl12631858>&nbsp;</td>
  <td class=xl12631858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl12731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl12631858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
  <td class=xl11731858>&nbsp;</td>
    -- mula colspan -43 -->
  <td class=xl12831858>&nbsp;</td>
  <td class=xl10631858>&nbsp;</td>
  <td class=xl10131858></td>
 </tr>
 <tr class=xl1531858 height=40 style='mso-height-source:userset;height:30.0pt'>
  <td height=40 class=xl1531858 style='height:30.0pt'></td>
  <td class=xl7531858>&nbsp;</td>
  <td class=xl7431858></td>
  <td class=xl7231858></td>
  <td class=xl7231858></td>
  <td class=xl7231858></td>
  <td class=xl7231858></td>
  <td class=xl7231858></td>
  <td class=xl7231858></td>
  <td class=xl7231858></td>
  <td class=xl7231858></td>
  <td class=xl7231858></td>
  <td class=xl7231858></td>
  <td class=xl7231858></td>
  <td class=xl1531858></td>
  <td class=xl7131858 colspan=3>F1026</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td colspan=44 class=xl20731858 style='border-right:.5pt solid black'>&nbsp;</td>
  <td class=xl10631858>&nbsp;</td>
  <td class=xl10131858></td>
 </tr>
 <!-- buang 
 <tr class=xl1531858 height=1 style='mso-height-source:userset;height:0.75pt'>
  <td height=1 class=xl1531858 style='height:0.75pt'></td>
  <td class=xl10731858>&nbsp;</td>
  <td class=xl10831858>&nbsp;</td>
  <td class=xl10931858>&nbsp;</td>
  <td class=xl10831858>&nbsp;</td>
  <td class=xl10831858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl11031858><u style='visibility:hidden;mso-ignore:visibility'>&nbsp;</u></td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl11131858>&nbsp;</td>
  <td class=xl11231858>&nbsp;</td>
  <td class=xl11231858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7831858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 tamat buang -->
 <tr height=10 style='height:12.75pt'>
  <td class=xl7331858 style='border-left:0.0pt solid black'></td>
  <td class=xl1531858 style='border-top:5.0pt groove black;border-left:1.0pt solid black'></td>
  <td class=xl1531858 colspan="63" style="border-top:5.0pt groove black; border-left:0.0pt">
  Orang yang boleh dihubungi bagi sebarang
  pertanyaan / <font class="font1131858">Person to be contacted regarding any
  queries:<span style='mso-spacerun:yes'> </span></font></td>
  <!-- mula hiang -- 47 hingga 65
  <td class=xl1531858></td><td class=xl1531858></td><td class=xl1531858></td>
  <td class=xl1531858></td><td class=xl1531858></td><td class=xl1531858></td>
  <td class=xl1531858></td><td class=xl1531858></td><td class=xl1531858></td>
  <td class=xl1531858></td><td class=xl1531858></td><td class=xl1531858></td>
  <td class=xl6731858></td><td class=xl1531858></td><td class=xl1531858></td>
  <td class=xl1531858></td><td class=xl1531858></td><td class=xl6631858>&nbsp;</td>
  tamat 65 -->
  <td class=xl1531858 style='border-top:5.0pt groove black;
  border-right:1.0pt solid black' ></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
 <!-- mula $letak[] -->
 <?php
$letak['nama']=$data['rangka']['responden'];
$letak['tel']=$data['rangka']['tel'];
$letak['fax']=$data['rangka']['fax'];
$letak['email']=$data['rangka']['email'];
?>
<!-- tamat $letak[] -->
  <td height=17 class=xl1531858 style='height:12.75pt'></td>
  <td class=xl7531858>&nbsp;</td>
  <td class=xl7431858></td>
  <td colspan=12 class=xl17931858>Nama<font class="font1031858"> / </font><font
  class="font1131858">Name :</font></td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858><?=$letak['nama']?>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl10231858>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl11431858 colspan=9>COP PERNIAGAAN</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6731858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl1531858 style='height:15.0pt'></td>
  <td class=xl7531858>&nbsp;</td>
  <td class=xl7431858></td>
  <td colspan=12 class=xl17931858>No.Telefon<font class="font1031858">/</font><font
  class="font1131858">Telephone No. :</font></td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl1531858><?=$letak['tel']?></td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10531858 style='border-top:none'>&nbsp;</td>
  <td class=xl10531858 style='border-top:none'>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6931858></td>
  <td class=xl6931858></td>
  <td class=xl6931858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6731858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl1531858 style='height:15.0pt'></td>
  <td class=xl7531858>&nbsp;</td>
  <td class=xl7431858></td>
  <td colspan=12 class=xl17931858>No. Faks / <font class="font1131858">Fax No :</font></td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858><?=$letak['fax']?>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10331858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10431858 style='border-top:none'>&nbsp;</td>
  <td class=xl10531858 style='border-top:none'>&nbsp;</td>
  <td class=xl10531858 style='border-top:none'>&nbsp;</td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6931858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl6731858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl1531858></td>
  <td class=xl10131858></td>
  <td class=xl10631858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
 <tr height=21 style='height:15.75pt'>
  <td height=21 class=xl1531858 style='height:15.75pt'></td>
  <td class=xl10731858>&nbsp;</td>
  <td class=xl10831858>&nbsp;</td>
  <td colspan=12 class=xl18031858>E-Mel /<font class="font1131858">E-Mail :</font></td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'><?=$letak['email']?>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl15731858 style='border-top:none'>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl11131858>&nbsp;</td>
  <td class=xl11231858>&nbsp;</td>
  <td class=xl11231858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl7731858>&nbsp;</td>
  <td class=xl15831858>&nbsp;</td>
  <td class=xl15931858>&nbsp;</td>
  <td class=xl1531858></td>
 </tr>
</table>
</div>
<?php } ?>
<!-- ---------------------------------------------------------------------------------------------------------- -->
<//div>
</body></html>
<?php
/* <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=24 style='width:18pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=13 style='width:10pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=12 style='width:9pt'></td>
  <td width=8 style='width:6pt'></td>
 </tr>
 <![endif]>
*/
?>