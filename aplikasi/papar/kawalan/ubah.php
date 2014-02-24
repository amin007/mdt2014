<?php 
function cariInput($kira,$key,$data)
{
    /*
    0-nota,1-respon,2-fe
    3-tel,4-fax,5-responden,6-email
    7-msic,8-msic08,9-id U M
    10-nama,11-ssm,12-utama
    */
	// istihar pembolehubah 
	$bulan = 'rangka14';
	$name = 'name="' . $bulan . '[' . $key . ']"';
	//if ($key=='noahli') $id = $data; 
	//if ( in_array($key,$textbox) )
	if ($key=='nota')
	{//sebab
	    $input =
	    '<textarea ' . $name . ' rows="1" cols="20">' . $data . '</textarea>';
	}	
	elseif($key=='respon')
	{//msic
	    $input = '<input type="text" ' . $name . ' value="' 
	    . $data . '" class="input-mini" >';
	}
	elseif(in_array($key,array('fe','tel','fax','responden','email')))
	{//msic
	    $input = '<input type="text" ' . $name . ' value="' 
	    . $data . '" class="input-medium" >';
	}
	elseif($key=='msic08')
	{//msic
	    $input = '<input type="text" ' . $name . ' value="' 
	    . $data . '" class="input-mini" >';
	}
	else
	{
	    $papar_data = ($data==null) ? '' : '<span class="label">' . $data . '</span>';
	    $input=$papar_data.'&nbsp;';
	    //$input = '<input type="text" ' . $name . ' value="' 
	    //. $data . '" class="input-small" >';
	}
	
	// medan yang tak perlu dipaparkan
	$lepas = array('ssm','utama');
	$papar_data = ($data==null) ? '' : '<span class="label">' . $data . '</span>';
	echo (in_array($key,$lepas)) ? '' : 
	(    ($key == 'newss') ?
	    $input : // kalau bukan $key==newss	    
	    '<span class="add-on"><i class="icon icon-remove"></i></span>' .
	    $input
	);
}

function paparInputBulanan($bulan,$row,$kira,$key,$data)
{
	$s1 = '<span class="label">';
	$s2 = '</span>';
	$name = 'name="' . $bulan . '[' . $key . ']"';
	$tandaX = 'name="' . $bulan . '[' . $key . 'x]"';
	//if ($key=='noahli') $id = $data; 
	//if ( in_array($key,$textbox) )
	if ($key=='newss')
	{
		$input = $s1 . $row[$kira]['newss'] 
			   . '|' . $row[$kira]['utama'] . '<br>'
			   . $row[$kira]['nama'] . $s2;
	}
	elseif ( in_array($key,array('hasil','dptLain','stok','gaji')) )
	{
		$input = '<input type="text" ' . $name . ' value="' 
			   . $data . '" class="input-small" >';
		$input .= ($data==null) ? '' : $s1 . kira($data) . $s2;
	}
	elseif ( in_array($key,array('msic','web','staf','outlet')) )
	{//msic
	    $input = '<input type="text" ' . $name . ' value="' 
	           . $data . '" class="input-micro" >';
		$input .= ($data==null) ? '' : $s1 . $data . $s2;
	}
	elseif($key=='terima')
	{//terima - style="font-family:sans-serif;font-size:10px;"
	    $input = '<input type="text" ' . $name . ' value="' . $data . '" '
	           . 'class="input-date tarikh" readonly>'
			   . '<input type="checkbox" ' . $tandaX . ' value="x">';
		$input .= ($data==null) ? '' : $s1 . $data . $s2;
	}
	elseif ($key=='sebab')
	{//sebab
	    $input = '<textarea ' . $name . ' rows="1" cols="10">' 
		       . $data . '</textarea>';
		$input .= ($data==null) ? '' : $s1 . $data . $s2;
	}
	else
	{	//$input=$bulan.'-'.$data.'&nbsp;';    $input=$data;
	    $input = '<input type="text" ' . $name . ' value="' 
	           . $data . '" class="input-small" >';
		$input .= ($data==null) ? '' : $s1 . $data . $s2;
	}

	return $input;
}
// mula untuk kod php+html
function papar_jadual($row, $myTable, $pilih)
{
    if ($pilih == 1)
    {
        ?><!-- Jadual <?php echo $myTable ?> ########################################### -->
        <table  border="1" class="excel" id="example">
        <?php
        // mula bina jadual
        $printed_headers = false;
        #-----------------------------------------------------------------
        for ($kira=0; $kira < count($row); $kira++)
        {
            //print the headers once:  
            if ( !$printed_headers )
            {
                ?><thead><tr>
        <th>#</th>
        <?php
                foreach ( array_keys($row[$kira]) as $tajuk )
                {
                    // anda mempunyai kunci integer serta kunci rentetan
                    // kerana cara PHP mengendalikan tatasusunan.
                    ?><th><?php echo $tajuk ?></th>
        <?php    
                }
        ?></tr></thead>
        <?php
                $printed_headers = true;
            }
        #-----------------------------------------------------------------      
            //print the data row
            ?><tbody><tr>
            <td><?php echo $kira+1 ?></td>
            <?php foreach ( $row[$kira] as $key=>$data ) : ?>
            <td><?php echo $data ?></td>
            <?php endforeach; ?>
        </tr></tbody>
        <?php
        }
        #-----------------------------------------------------------------
        ?>
        </table>
        <!-- Jadual <?php echo $myTable ?> ########################################### --><?php
    }
    elseif ($pilih == 2)
    {
        ?><!-- Jadual <?php echo $myTable ?> ########################################### -->
        <table class="table table-striped">
        <?php
        // mula bina jadual
        $printed_headers = false;
        #-----------------------------------------------------------------
        for ($kira=0; $kira < count($row); $kira++)
        {
            //print the headers once:  
            if ( !$printed_headers )
            {
                ?><thead><tr>
        <th>#</th>
        <?php
                foreach ( array_keys($row[$kira]) AS $tajuk )
                {
                // anda mempunyai kunci integer serta kunci rentetan
                // kerana cara PHP mengendalikan tatasusunan.
                    $paparTajuk = ($tajuk=='keterangan') ?
                    $tajuk . ' (jadual:' . $myTable . ')'
                    : $tajuk;
                    ?><th><?php echo $paparTajuk ?></th>
        <?php   
                }
        ?></tr></thead>
        <?php
                $printed_headers = true;
            }
        #-----------------------------------------------------------------      
            //print the data row ?>
            <tbody><tr>
            <td><?php echo $kira+1 ?></td>
            <?php foreach ( $row[$kira] as $key=>$data ) : ?>
            <td><?php echo $data ?></td>
            <?php endforeach; ?>
        </tr></tbody>
        <?php
        }
        #-----------------------------------------------------------------
        ?>
        </table>
        <!-- Jadual <?php echo $myTable ?> ########################################### --><?php
    }
    elseif ($pilih == 3)
    {	?><!-- Jadual <?php echo $myTable ?> ########################################### --><?php
            for ($kira=0; $kira < count($row); $kira++)
            {//print the data row
            #-----------------------------------------------------------------?>
			<table border="1" class="excel" id="example">
			<caption><?php echo $myTable ?></caption>
			<tbody><?php foreach ( $row[$kira] as $key=>$data ) :?>
			<tr>
			<td><span class="label"><?php echo $key ?></span></td>
			<td><?php echo $data ?></td>
			</tr><?php endforeach ?>
			</tbody>
			</table>
        <?php
            }// final print the data row
            #-----------------------------------------------------------------
        ?><!-- Jadual <?php echo $myTable ?> ########################################### --><?php
    } // tamat if (jadual ==3
 
}
// tamat untuk kod php+html 

function paparData($cariIndustri, $kira, $key, $data)
{
	if($key != 'msic'): echo $data;
	else:
		//echo 'papar jadual untuk msic=' . $data . ' |<br>';
		foreach ($cariIndustri as $myTable => $bilang)
		{// mula ulang $bilang
			papar_jadual($bilang, $myTable, $pilih=2);
		}// tamat ulang $bilang
	endif;
}
/*
echo '<pre>';
echo '<br>$this->kesID:<br>'; print_r($this->kesID);
//echo '<br>$this->rangka:<br>'; print_r($this->rangka);
//echo '$this->cariIndustri:<br>'; print_r($this->cariIndustri); 
//echo '<br>$this->carian:<br>'; print_r($this->carian); 
echo '</pre>';
//*/

// set pembolehubah
$mencari = URL . 'kawalan/ubahCari/';
$carian = $this->cari;
$cetak = URL . 'kawalan/cetak/' . $carian
?>
<h1>Ubah Data Bulanan<a href="<?php echo $cetak ?>"><span class="badge"><i class="icon-print icon-white"></i>Cetak</span></a></h1>
<div align="center"><form method="POST" action="<?=$mencari;?>" autocomplete="off">
<font size="5" color="red">&rarr;</font><br>
<input type="text" name="cari" size="40" value="<?=$carian;?>" 
id="inputString" onkeyup="lookup(this.value);" onblur="fill();">
<input type="submit" value="mencari">
<div class="suggestionsBox" id="suggestions" style="display: none; " >
	<div class="suggestionList" id="autoSuggestionsList">&nbsp;</div>
</div>
</form></div>
<?php 
if ($this->carian=='[tiada id diisi]')
{
    echo 'data kosong<br>';
}
else
{ // $this->carian=='sidap' - mula
    $cari = $this->carian;
    $s1 = '<span class="label">';
    $s2 = '</span>';
    
    // isytihar pembolehubah untuk sistem sms
    $newss = $this->kesID['jan14'][0]['newss'];
    $syarikat  = $this->rangka['kes'][0]['nama'];
	$sykt  = urlencode($this->rangka['kes'][0]['nama']);
    $kawan = urlencode($this->rangka['kes'][0]['fe']);
    $hantar_sms = URL . 'pengguna/smskes/' . $kawan . '/' . $sykt;
	
?>
<form method="post" action="<?php echo URL;?>kawalan/ubahSimpan/<?php echo $newss; ?>"
class="form-horizontal">
<!-- test textbox edit by a href -->
<!-- jadual rangka ########################################### -->
<?php
foreach ($this->rangka as $myTable => $row)
{// mula ulang $row
    for ($kira=0; $kira < count($row); $kira++)
    {//print the data row
    #-----------------------------------------------------------------
    foreach ($row[$kira] as $key=>$data): ?>
	<div class="control-group" for="fileInput">
	<label class="control-label"><?php echo $key ?></label>
		<div class="controls">
		<?php cariInput($kira, $key, $data) ?>
		<span class="help-inline"><?php paparData($this->cariIndustri, 
		$kira, $key, $data) ?></span>
		</div>
	</div>
    <?php 
	endforeach;
    }// final print the data row
    #-----------------------------------------------------------------
}// tamat ulang $row
?>

<hr>
<!-- jadual data ########################################### -->
<a target='_blank' href="<?php echo $hantar_sms ?>" class="btn btn-primary btn-large">Hantar sms</a>
<table border="1" class="table table-bordered table-striped">
<tr><?php echo $this->paparTajuk; ?></tr>
<?php
$nama_bulan = array('jan14' => 1, 'feb14' => 2, 'mac14' => 3, 'apr14' => 4, 
    'mei14' => 5, 'jun14' => 6, 'jul14' => 7, 'ogo14' => 8, 
    'sep14' => 9, 'okt14' => 10, 'nov14' => 11, 'dis14' => 12);

foreach ($this->kesID as $myTable => $row)
{// mula ulang $row
#-----------------------------------------------------------------
    // mula bina jadual
    for ($kira=0; $kira < count($row); $kira++)
    {//print the data row 
    // bgcolor='#ffffff'
		$fe = $row[$kira]['fe'];
		$noID = $row[$kira]['newss'];
		$link = 'target="_blank" href="' . URL 
			  . 'cetak-kes.php?cari=' . $noID
			  . '&bln=' . $nama_bulan[$myTable] . '"';
		$cetakID = '<a ' . $link . '>' . $myTable . '</a>';
		$dataID =  $cetakID . '<br>' . $fe;
    ?><tr>
<td><?php echo $s1 . $dataID . $s2 ?></td>
<?php
        $bulan = $myTable;
		// medan yang tak perlu dipaparkan
		$lepas = array('respon','nama','utama','fe',);
		//$textbox = array('nota');
        foreach ( $row[$kira] as $key=>$data ) 
        {        
            $input = paparInputBulanan($bulan,$row,$kira,$key,$data);
            echo (in_array($key,$lepas)) ? '' : 
            (    ($key == 'newss') ?
                '<td>' . $input . '</td>'
                : // kalau bukan $key==newss                
                "\n" . '<td>' // . '<div class="input-prepend">' . $input 
                //. '<span class="add-on"><i class="icon- icon-remove"></i></span></div>'
                . $input . '</td>'
            );
        } 
?>
</tr><!-- bandingan data bulan ke bulan -->
<?php
		$link2 = 'target="_blank" href="' . URL 
		       . 'laporan_kes.php?cari='.$noID.'"';

		echo "\r" . '<tr bgcolor="bisque">';
		echo "\n" . '<td align="center" colspan="1">' . $myTable 
		          . '<br><a ' . $link2 . '>lepas</a></td>';	

			foreach ( $this->beza[$myTable][0] as $kunci=>$perbezaan ) 
			{
				$gaji = $this->kesID[$myTable][0]['gaji'];
				$staf = $this->kesID[$myTable][0]['staf'];
				@$purata_gaji = $gaji / $staf;
				$jual = $this->kesID[$myTable][0]['hasil'];
				$lain = $this->kesID[$myTable][0]['dptLain'];
				@$sumbang = kira(($jual+$lain)/$staf); // kira sumbang
				@$hari = kira(($jual+$lain)/30); // kira sehari
				$pekerja = ($purata_gaji==null) ? null : '1gaji=' . $purata_gaji . '. sumbangan=' . $sumbang;
				$jualan = ($jual==null) ? null : ' bulan ' . $myTable . '. Jual sehari=' . $hari;

				// semak perbezaan
				@$beza = ($kunci=='dpt') ? $perbezaan : 0;
				$sebab = ($beza <= 30 && $beza >= -30)?
					$syarikat . $jualan . '|' . $beza . '%': 
					$syarikat . (
						($beza > 0) ? ' naik ' :' turun '
					) . $beza . '%' . $jualan;
		
				echo ($kunci=='dpt') ? 
					( $myTable=='jan13' ?
						"\n" . '<td align="center" colspan="3">&nbsp;</td>'
						:
						"\n" . '<td align="center" colspan="3">' 
						. $sebab . '</td>'
					)
					: "\n" . '<td align="center">' 
					. ($perbezaan==null? null : $perbezaan . '%' ) 
					. '</td>';	
			}
		echo "\n" . '<td align="center">' . $pekerja . '</td>';	
		echo "\n" . '<td align="center">&nbsp;</td>';
		echo "\r" . '</tr>';
?>
<?php
    } 
#-----------------------------------------------------------------
}// tamat ulang $row
?>
</table>
<input type="submit" name="Simpan" value="Simpan" class="btn btn-primary btn-large">
</form>
<hr>
<?php } // $this->carian=='sidap' - tamat 
/*
*/
// fungsi papar data
function papar_data($f, $data)
{
		// semak dah kira, baru papar
			$Dahulu=($jual[$key-1]+$lain[$key-1]);
			$Kemudian=($row[4]+$row[5]);
			$beza=kira2($Dahulu,$Kemudian); // ada koma
			$beza3=kira3($Dahulu,$Kemudian);// takde koma
			$sebab=($beza3 <= 30 && $beza3 >= -30)?
			$syarikat: $syarikat . (
				($beza3 > 0) ? ' naik ' :' turun '
			) . $beza3 . '%';
			$link3='target="_blank" href="../forum/sms.php?kawan=' . $kawan .
			//'&cari=' . urlencode($sebab) . '"';
			'&cari=' . ($sebab) . '"';
			$banding='<a ' . $link3 . '>' .
			(
				($beza <= 30 && $beza >= -30)? $beza
				:'<font size=4>' .$beza . '</font>'
			) . '%</a>';
			
			//kira purata
			@$purata=kira(($row[9]/$row[8]));// gaji/staf

		return ($data==null) ? '' : '<span class="label">' 
			. $papar . '</span>';
}

/*
<!-- jadual rangka ########################################### -->
<?php
foreach ($this->rangka as $myTable => $row)
{// mula ulang $row
    for ($kira=0; $kira < count($row); $kira++)
    {//print the data row
    #-----------------------------------------------------------------
    ?><table class="table table-striped">
    <tbody><?php foreach ($row[$kira] as $key=>$data): ?>
    <tr>
    <td><span class="label"><?php echo $key ?></span></td>
    <td><?php cariInput($kira, $key, $data) ?>&nbsp;</td>
    <td><?php paparData($this->cariIndustri, $kira, $key, $data) ?>&nbsp;</td>
    </tr><?php endforeach ?>
    </tbody>
    </table>
    <?php
    }// final print the data row
    #-----------------------------------------------------------------
}// tamat ulang $row
?>
*/