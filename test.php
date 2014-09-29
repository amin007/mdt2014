<?php if (!isset($_POST['masuk'])) { ?>
<html>
<head><title>Test</title>
</head>
<body>
<div id="content" style="position:relative;height:100%;overflow:hidden" align="center">
<table border="0" align="center" bgcolor="#ffff00" width="100%" height="100%">
<tr><td align=center><?php 
$ip  =$_SERVER['REMOTE_ADDR'];
$ip2 = substr($ip,0,10);
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$server = $_SERVER['SERVER_NAME'];

echo "<br>Alamat IP : <font color='red'>" . $ip . "</font> |" .
//"<br>Alamat IP2 : <font color='red'>" . substr($ip,0,10) . "</font> |" .
"\r<br>Nama PC : <font color='red'>" . $hostname . "</font> |" .
//"\r<br>Server : <font color='red'>" . $server . "</font>" .
"<br>\r";

$senaraiIP=array('192.168.1.', '10.69.112.', '127.0.0.1');
if ( in_array($ip2,$senaraiIP) )
{?>
	<form method="post" action="test.php">
	<input name="username" type="text" tabindex="1">
	<label align="center" style="font-size: 20pt; background-color: #000000; 
	color:#ffffff">	Masukkan kata laluan</label>
	<input name="password" type="password" size="20" tabindex="2">
	<input type="submit" name="masuk" value="Masuk" class="masuk">
	</form>
<?php
}
else{echo 'ip anda ' . $ip . ', anda tiada kebenaran masuk sistem';}

?></td></tr>
</table>
</div>

</body></html><?php 
} else {// data baru mula proses

// Mula Proses Kawalan ****************************************************************************** -->
	$_POST['password']=md5($_POST['password']);
	echo '<pre>', print_r($_POST) . '</pre>';
}// data baru tamat proses 
/*
data anggaran
SET @bil=0.00; SELECT @bil:=@bil+0.00 as `#`,b8.newss,b8.nama,b8.utama,b8.hasil,b7.hasil,
format(FLOOR(@bil+rand()*b7.hasil)+b7.hasil, 0) as anggar
FROM mdt_jul14 b7, mdt_ogo14 b8
WHERE b8.newss = b7.newss
and b8.fe like 'amin%' 
and b8.utama = 'sbu'
AND b8.hasil is null
update
SET @bil=0.00; 
UPDATE mdt_jul14 b7, mdt_ogo14 b8 SET
b8.hasil = FLOOR(@bil+rand()*b7.hasil)+b7.hasil
WHERE b8.newss = b7.newss
and b8.fe like 'amin%' 
and b8.utama = 'sbu'
AND b8.hasil is null
*/

?>