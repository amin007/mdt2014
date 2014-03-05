<?php 
include 'tatarajah.php';// buka pangkalan data 
//echo DB_HOST . "," . DB_USER . "," . DB_PASS . ":" . DB_NAME . "<br>";
$s = @mysql_connect(DB_HOST, DB_USER, DB_PASS) or die (mysql_error()); 
$d = @mysql_select_db(DB_NAME, $s) or die (mysql_error());
$Tajuk_Muka_Surat = 'MDT 2014';
date_default_timezone_set("Asia/Kuala_Lumpur");
if(isset($_GET['cari'])) 
{
	$queryString = $_GET['cari'];		
	if(strlen($queryString) > 0) 
	{
		$myTable = 'mdt_rangka14';
		$query = "SELECT newss,nama,ssm,utama,fe,respon FROM $myTable 
		WHERE concat(newss,nama) like '%$queryString%' LIMIT 30";
		$result = mysql_query($query) or die(mysql_error()."<hr>$query<hr>");
		$fields=mysql_num_fields($result); $rows = mysql_num_rows($result);
		
			if($rows==0) {echo '<li onClick="fill(\'-\');">Takde Laa</li>';}
			else
			{
				while($row = mysql_fetch_array($result))
				{
					echo '<li onClick="fill(\''.$row[0].'\');">'.$row[1].'-'.
					$row[0].'-'.$row[2].'-'.$row[3].'-'.$row[4].'-'.$row[5].'</li>';
				}
			}
	}
}