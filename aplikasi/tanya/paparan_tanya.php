<?php

class Paparan_Tanya extends Tanya 
{

	public function __construct() 
	{
		parent::__construct();
		$this->_susun = ' ORDER BY substring(msic,1,2) ASC, newss';
		$this->_susunUtama = ' ORDER BY substring(c.msic,1,2) ASC, c.newss';
		//$this->_susun = ' ORDER BY newss';
	}

	private function cari($fe, $cari = null, $apa = null)
	{
		$carife = ( !isset($fe) ) ? ' WHERE 1=1 ' : ' WHERE fe = "' . $fe . '"';
		
		return $carife;
	}
	
	private function jika($atau,$medan,$fix,$cariApa,$akhir)
	{
		$dimana = null;
		if($atau==null) $dimana .= null;
		elseif($cariApa==null )
			$dimana .= ($fix=='x!=') ? " $atau`$medan` !='' $akhir\r"
					: " $atau`$medan` is null $akhir\r";
		elseif($fix=='xnull')
			$dimana .= " $atau`$medan` is not null  $akhir\r";
		elseif($fix=='x=')
			$dimana .= " $atau`$medan` = '$cariApa' $akhir\r";
		elseif($fix=='x!=')
			$dimana .= " $atau`$medan` != '$cariApa' $akhir\r";
		elseif($fix=='like')
			$dimana .= " $atau`$medan` like '$cariApa' $akhir\r";
		elseif($fix=='xlike')
			$dimana .= " $atau`$medan` not like '$cariApa' $akhir\r";	
		elseif($fix=='%like%')
			$dimana .= " $atau`$medan` like '%$cariApa%' $akhir\r";	
		elseif($fix=='x%like%')
			$dimana .= " $atau`$medan` not like '%$cariApa%' $akhir\r";	
		elseif($fix=='like%')
			$dimana .= " $atau`$medan` like '$cariApa%' $akhir\r";	
		elseif($fix=='xlike%')
			$dimana .= " $atau`$medan` not like '$cariApa%' $akhir\r";	
		elseif($fix=='%like')
			$dimana .= " $atau`$medan` like '%$cariApa' $akhir\r";	
		elseif($fix=='x%like')
			$dimana .= " $atau`$medan` not like '%$cariApa' $akhir\r";	
		elseif($fix=='in')
			$dimana .= " $atau`$medan` in $cariApa $akhir\r";				
		elseif($fix=='xin')
			$dimana .= " $atau`$medan` not in $cariApa $akhir\r";				
		elseif($fix=='khas2')
			$dimana .= " $atau`$medan` REGEXP CONCAT('(^| )','',$cariApa) $akhir\r";	
		elseif($fix=='xkhas2')
			$dimana .= " $atau`$medan` NOT REGEXP CONCAT('(^| )','',$cariApa) $akhir\r";	
		elseif($fix=='khas3')
			$dimana .= " $atau`$medan` REGEXP CONCAT('[[:<:]]',$cariApa,'[[:>:]]') $akhir\r";	
		elseif($fix=='xkhas4')
			$dimana .= " $atau`$medan` NOT REGEXP CONCAT('[[:<:]]',$cariApa,'[[:>:]]') $akhir\r";	
		elseif($fix=='z1')
			$dimana .= " $atau$medan = $cariApa $akhir\r";
		elseif($fix=='z2')
			$dimana .= " $atau$medan like '$cariApa' $akhir\r";
		elseif($fix=='z2x')
			$dimana .= " $atau$medan not like '$cariApa' $akhir\r";
		elseif($fix=='z3x')
			$dimana .= " $atau$medan IS NOT NULL $akhir\r";
		elseif($fix=='zin')
			$dimana .= " $atau$medan in $cariApa $akhir\r";
		elseif($fix=='zxin')
			$dimana .= " $atau$medan not in $cariApa $akhir\r";	
		return $dimana;
	}
	
	private function dimana($carian)
	{
		//' WHERE ' . $medan . ' like %:cariID% ', array(':cariID' => $cariID));
		$where = null;
		if($carian==null || $carian=='' || empty($carian) ):
			$where .= null;
		else:
			foreach ($carian as $key=>$value)
			{
				   $atau = isset($carian[$key]['atau'])  ? $carian[$key]['atau'] . ' ' : null;
				  $medan = isset($carian[$key]['medan']) ? $carian[$key]['medan']      : null;
				    $fix = isset($carian[$key]['fix'])   ? $carian[$key]['fix']        : null;			
				$cariApa = isset($carian[$key]['apa'])   ? $carian[$key]['apa']        : null;
				  $akhir = isset($carian[$key]['akhir']) ? $carian[$key]['akhir']      : null;
				//echo "\r$key => ($fix) $atau $medan = '$apa'  ";
				$where .= $this->jika($atau, $medan, $fix, $cariApa, $akhir);
			}
		endif;
	
		return $where;
	
	}
	
	private function dibawah($carian)
	{
		$susunan = null;
		if($carian==null || empty($carian) ):
			$susunan .= null;
		else:
			foreach ($carian as $key=>$cari)
			{
				$kumpul = isset($carian[$key]['kumpul'])? $carian[$key]['kumpul'] : null;
				 $order = isset($carian[$key]['susun']) ? $carian[$key]['susun']  : null;
				  $dari = isset($carian[$key]['dari'])  ? $carian[$key]['dari']   : null;			
				   $max = isset($carian[$key]['max'])   ? $carian[$key]['max']    : null;
				
				//echo "\$cari = $cari, \$key=$key <br>";
			}
				if ($kumpul!=null)$susunan .= " GROUP BY $kumpul\r";
				if ($order!=null) $susunan .= " ORDER BY $order\r";
				if ($max!=null)   $susunan .= ($dari==0) ? 
					" LIMIT $max\r" : " LIMIT $dari,$max\r";
		endif; 
		
		//echo "<hr>\$kumpul:$kumpul \$order:$order \$dari:$dari \$max:$max hahaha<hr>";
		return $susunan;
		
	}
	
	public function cariSemuaData($myTable, $medan, $carian, $susun)
	{
		$sql = 'SELECT ' . $medan . ' FROM ' . $myTable 
			 . $this->dimana($carian)
			 . $this->dibawah($susun);
		
		//echo '<pre>susun:'; print_r($susun) . '</pre><br>';
		//echo htmlentities($sql) . '<br>';
		//echo '<pre>';print_r($sql) . '</pre><br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}
	
	public function paparMedan($myTable, $papar = null)
	{
		$cari = ( !isset($papar) ) ? '' : ' WHERE  ' . $papar . ' ';
		//return $this->db->select('SHOW COLUMNS FROM ' . $myTable);
		$sql = 'SHOW COLUMNS FROM ' . $myTable . $cari;
		
		//echo htmlentities($sql) . '<br>';
		return $this->db->selectAll($sql);
	}

	public function kiraKes($myTable, $medan, $carian)
	{

		$sql = 'SELECT * FROM ' . $myTable 
			 . $this->dimana($carian);
		
		//echo htmlentities($sql) . '<br>';
		$result = $this->db->rowCount($sql);
		//echo '<br>Bil hasil = ' . $result . '<br>';
		//echo json_encode($result);
		
		return $result;
	}

	public function paparSemua($myTable, $medan, $fe, $jum)
	{
		$sql = 'SELECT ' . $medan . ' FROM ' . $myTable . ' b ' 
			 . $this->cari($fe) . $this->_susun;
		
		//echo htmlentities($sql) . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function kesSelesai($myTable, $medan, $fe, $jum)
	{
		$a1 = ($myTable == 'rangka14') ?
			' respon = "A1"' : ' terima is not null';

		$sql = 'SELECT ' . $medan . ' FROM ' . $myTable . ' b ' 
			 . $this->cari($fe)	. $a1 . $this->_susun 
			 . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];

		//echo htmlentities($sql) . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}
	
	public function kesJanji($myTable, $medan, $fe, $jum)
	{
		$sql = 'SELECT ' . $medan . ' FROM ' . $myTable . ' b, ' 
			 . '`mdt_rangka14` as c '
			 . $this->cari($fe)	
			 . ' and b.newss = c.newss '
			 . ' and (b.terima is null and c.respon != "A1") ' 
			 . $this->_susun 
			 . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];

		//echo htmlentities($sql) . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function kesBelum($myTable, $medan, $fe, $jum)
	{
		$sql = 'SELECT ' . $medan . ' FROM ' . $myTable . ' as b '
			 . $this->cari($fe)	
			 . ' and (terima is null' 
			 . ' or terima like "0000%") '
			 . $this->_susun 
			 . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];

		//echo htmlentities($sql) . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function kesTegar($myTable, $medan, $fe, $jum)
	{
		$sql = 'SELECT ' . $medan . ' FROM ' . $myTable 
			 . $this->cari($fe)
			 . ' and (`respon` not like "A1"' 
			 . ' and `respon` not like "B%") '
			 . $this->_susun 
			 . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];

		//echo htmlentities($sql) . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function kiraKesUtama($myTable, $medan, $cari)
	{
		$cariUtama = ( !isset($cari['utama']) ) ? 
		'' : ' WHERE b.newss=c.newss and b.utama = "' . $cari['utama'] . '"';
		$cariFe = ( !isset($fe) ) ? '' : ' and b.fe = "' . $fe . '"';
		$respon = ( !isset($cari['respon']) ) ? null : $cari['respon'] ;
		$AN=array('A2','A3','A4','A5','A6','A7','A8','A9','A10','A11','A12','A13');
		
		if  ($respon=='a1')
			$cariRespon = " AND c.respon='A1' and b.terima like '20%' \r";
		elseif ($respon=='xa1')
			$cariRespon = " AND b.terima is null \r";
		elseif ($respon=='tegar')
			$cariRespon = " AND(`respon` IN ('" . implode("','",$AN) . "')) \r";
		else $cariRespon = '';

		$sql = 'SELECT ' . $medan . ' FROM ' . 	$myTable 
			 . ' b, `mdt_rangka14` as c '
			 . $cariUtama . $cariRespon . $cariFe;

		//echo htmlentities($sql) . '<br>';
		$result = $this->db->rowcount($sql);
		//echo json_encode($result);
		
		return $result;
	}
	
	public function kesUtama($myTable, $medan, $cari, $jum)
	{
		//$jum['dari'] . ', ' . $jum['max']
		$cariUtama = ( !isset($cari['utama']) ) ? 
		'' : ' WHERE b.newss=c.newss and b.utama = "' . $cari['utama'] . '"';
		$respon = ( !isset($cari['respon']) ) ? null : $cari['respon'] ;
		$cariFe = ( !isset($cari['fe']) ) ? '' : ' and b.fe = "' . $cari['fe'] . '"';
		//echo ' cariFe = ' . $cariFe . '<br>';
		$AN=array('A2','A3','A4','A5','A6','A7','A8','A9','A10','A11','A12','A13');
		
		if  ($respon=='a1')
			$cariRespon = " AND c.respon='A1' and b.terima like '20%' \r";
		elseif ($respon=='xa1')
			$cariRespon = " AND b.terima is null \r";
		elseif ($respon=='tegar')
			$cariRespon = " AND(`c.respon` IN ('" . implode("','",$AN) . "')) \r";
		else $cariRespon = '';

		$sql = '/*' . $myTable . '*/SELECT ' . $medan . ' FROM ' . $myTable 
			 . ' b, `mdt_rangka14` as c '
			 . $cariUtama . $cariRespon . $cariFe
			 . $this->_susunUtama
			 . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];

		//echo htmlentities($sql) . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function kesSemak($myTable, $myJoin, $medan, $jum)
	{
		//$jum['dari'] . ', ' . $jum['max']
		$sql = 'SELECT ' . $medan . ' FROM ' 
			 . $myTable . ' a, '.$myJoin.' b ' 
			 . ' WHERE a.newss=b.newss ' 
			 . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];
			
		$result = $this->db->selectAll($sql);
		//echo '<pre>' . $sql . '</pre><br>';
		//echo json_encode($result);
		
		return $result;
	}
	
	public function cariMedan($myTable, $medan, $cari)
	{
		$cariMedan = ( !isset($cari['medan']) ) ? '' : $cari['medan'];
		$cariID = ( !isset($cari['id']) ) ? '' : $cari['id'];
		
		$sql = 'SELECT ' . $medan . ' FROM ' . $myTable 
			 . ' WHERE ' . $cariMedan . ' like "%' . $cariID . '%" ';
		//' WHERE ' . $medan . ' like %:cariID% ', array(':cariID' => $cariID));

		//echo htmlentities($sql) . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}
	
	public function cariSemuaMedan($myTable, $medan, $cari)
	{
		$cariMedan = ( !isset($cari['medan']) ) ? '' : $cari['medan'];
		$cariID = ( !isset($cari['id']) ) ? '' : $cari['id'];
		
		$sql = 'SELECT ' . $medan . ' FROM ' . 	$myTable 
			 . ' WHERE ' . $cariMedan . ' = "' . $cariID . '" ';
		
		//echo htmlentities($sql) . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function cariSatuSahaja($myTable, $medan, $cari)
	{
		$cariMedan = ( !isset($cari['medan']) ) ? '' : $cari['medan'];
		$cariID = ( !isset($cari['id']) ) ? '' : $cari['id'];
		
		$sql = 'SELECT ' . $medan . ' FROM ' . 	$myTable 
			 . ' WHERE ' . $cariMedan . ' = "' . $cariID . '" ';
		
		//echo htmlentities($sql) . '<br>';
		$result = $this->db->select($sql);
		//echo json_encode($result);
		
		return $result;
	}
	
	public function cariIndustri($myTable, $medan, $cari)
	{
		$cariMedan = ( !isset($cari['medan']) ) ? '' : $cari['medan'];
		$cariID = ( !isset($cari['id']) ) ? '' : $cari['id'];
		
		$sql = 'SELECT ' . $medan . ' FROM ' . $myTable 
			 . ' WHERE ' . $cariMedan . ' = "' . $cariID . '" ';
		
		//echo htmlentities($sql) . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}
	
	public function kiraProsesan($myTable)
	{
		$sql = 'SELECT * FROM ' . $myTable 
			 . ' WHERE data12 <> "Batch 1" ';
		
		//echo htmlentities($sql) . '<br>';
		$result = $this->db->rowcount($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function semakProsesan($myTable)
	{
		$sql = 'SELECT * FROM ' . 	$myTable 
			 . ' WHERE data12 <> "Batch 1" ';
		
		//echo htmlentities($sql) . '<br>';
		$result = $this->db->select($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function semakRangkaProsesan($myTable, $medan, $cari, $jum)
	{
		$cariMedan = ( !isset($cari['medan']) ) ? '' : $cari['medan'];
		$cariID = ( !isset($cari['id']) ) ? '' : $cari['id'];
		$cari = ( !isset($cari['medan']) ) ? '' 
			: ' and ' . $cariMedan . ' = "' . $cariID . '" ';
		
		$sql = 'SELECT ' . $medan . ' FROM ' . 	$myTable 
			 . ' WHERE data12 <> "Batch 1" ' 
			 . $cari . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];
		
		//echo htmlentities($sql) . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function ubahSimpan($data, $myTable)
	{
		//echo '<pre>$sql->', print_r($data, 1) . '</pre>';
		$senarai = null;
		$medanID = 'newss';
		
		foreach ($data as $medan => $nilai)
		{
			//$postData[$medan] = $nilai;
			if ($medan == $medanID)
				$cariID = $medan;
			elseif ($medan != $medanID)
				$senarai[] = ($nilai==null) ? " `$medan`=null" : " `$medan`='$nilai'"; 
			if(($medan == 'fe'))
				$fe = ($nilai==null) ? " `$medan`=null" : " `$medan`='$nilai'"; 
		}
		
		$senaraiData = implode(",\r",$senarai);
		$where = "`$cariID` = '{$data[$cariID]}' ";
		
		// set sql
		$sql = " UPDATE `$myTable` SET \r$senaraiData\r WHERE $where";
		//echo '<pre>$sql->', print_r($sql, 1) . '</pre>';
		$this->db->update($sql);
	}

	public function tambahSimpan($data, $myTable)
	{
		//echo '<pre>$sql->', print_r($data, 1) . '</pre>';
		//$fieldNames = implode('`, `', array_keys($data));
		//$fieldValues = ':' . implode(', :', array_keys($data));

		$senarai = null;
		
		foreach ($data as $medan => $nilai)
		{
			$senarai[] = ($nilai==null) ? " `$medan`=null" : " `$medan`='$nilai'"; 
		}
		
		$senaraiData = implode(",\r",$senarai);
		
		// set sql
		$sql = " INSERT `$myTable` SET $senaraiData";
		//echo '<pre>$sql->', print_r($sql, 1) . '</pre>';
		$this->db->insert($sql);
	}
	
	public function cantumsql($sql) 
	{
		//echo htmlentities($sql) . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function cariKawalan($bulan, $cari, $apa)
	{
		foreach ($bulan as $key => $myTable)
		{// mula ulang table
			if (!in_array($key,array(0,1)))
			{
				////////////////////////////////////////////////////////
				//if (isset($myTable)){$sebelum = (array_search($myTable,$bulan))-1;}
				$sebelum = ($key - 1);
				$msic='if(semasa.msic is null,semasa.msic,semasa.msic)';
				$k1 = '<p align="right">';
				$k2 = '</p>';
				//echo '<hr>'.$key.')Bandingan Antara Bulan ' . $myTable . ' Dan ' . $bulan[$sebelum];
				// hasil+lain
				$hasil="concat( '$k1', format(lepas.hasil,0),'<br>',format(semasa.hasil,0),'$k2' ) as `hasil`";
				$dptLain="concat( format(lepas.dptLain,0),'<br>',format(semasa.dptLain,0) ) as `dptLain`";
				$peratus="format((((semasa.hasil-lepas.hasil)/lepas.hasil)*100),2)";
				$jumSemasa = 'format(semasa.hasil+semasa.dptLain, 0)';
				$jumLepas = 'format(lepas.hasil+lepas.dptLain, 0)';
				$jumlah="format((( ($jumSemasa - $jumLepas) / $jumLepas)*100),2)";
				// gaji
				$gajilepas="format(lepas.gaji,0)";
				$gajisemasa="format(semasa.gaji,0)";
				$gajiperatus="format((((semasa.gaji-lepas.gaji)/lepas.gaji)*100),2)";
				// staf
				$staflepas="format(lepas.staf,0)";
				$stafsemasa="format(semasa.staf,0)";
				$stafperatus="format((((semasa.staf-lepas.staf)/lepas.staf)*100),2)";
				//sql
				$sql = "SELECT semasa.newss,semasa.nama,$msic msic,semasa.utama,semasa.fe,\r"
					 . "$hasil,\r$dptLain,\r$peratus as `peratus`,\r"
					 . "concat($jumLepas,'<br>',$jumSemasa) as `Hasil Semua`,\r$jumlah as peratus2,"
					 . "concat($gajilepas,'<br>',$gajisemasa) as gaji,\r$gajiperatus as `gaji%`,\r"
					 . "concat($staflepas,'<br>',$stafsemasa) as staf,\r$stafperatus as `staf%`,\r"
					 . "semasa.sebab, substring('$myTable', 5, 5) as bulan\r"
					 . "FROM " . $bulan[$sebelum] . " lepas, $myTable semasa\r"
					 . "WHERE lepas.newss=semasa.newss "
					 . "AND semasa.$cari='$apa'\r";
				////////////////////////////////////////////////////////	
					//echo '<pre>$sql:'; print_r($sql) . '</pre><hr>';
					$data['Kawal'][] = $this->db->select($sql);
			}
			elseif (in_array($key,array(0)))
			{
				$sql = "\rSELECT * FROM `$myTable` WHERE $cari='$apa'\r";
								
				//echo '<pre>$sql:'; print_r($sql) . '</pre><hr>';
				$data['Rangka'] = $this->db->selectAll($sql);
			}// tamat if ($key != 0 || $key != 1)
		}// tamat ulang table
		//echo '<pre>$data:'; print_r($data) . '</pre>';
		//return $this->db->selectAll($sql);
		return $data;
	}	
	
	public function xhrInsert() 
	{
		$text = $_POST['text'];
		$this->db->insert('data', array('text' => $text));
		$data = array('text' => $text, 'id' => $this->db->lastInsertId());
		echo json_encode($data);
	}
	
	public function xhrGetListings()
	{
		$result = $this->db->select("SELECT * FROM data");
		//echo $result;
		echo json_encode($result);
	}
	
	public function xhrDeleteListing()
	{
		$id = (int) $_POST['id'];
		$this->db->delete('data', "id = '$id'");
	}

}