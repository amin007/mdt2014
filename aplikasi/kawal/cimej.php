<?php

class Cimej extends Kawal 
{

	public function __construct() 
	{
		parent::__construct();
        Kebenaran::kawalKeluar();
	}
	
	public function index() 
	{	
		//$myTable = 'sse10_kawal';
		//$this->papar->senaraiKes = $this->tanya->senaraiKes($myTable);
		$this->papar->baca('cimej/index');
		
		//$this->papar->senaraiKes = $myTable;
		//$this->papar->baca('cimej/test_index');
	}
	
	function cari() 
	{
		//echo '<br>Anda berada di class Imej extends Kawal:cari()<br>';
		//echo '<pre>'; print_r($_POST) . '</pre>';
		/*
		$_POST[id] => Array
		(
			[ssm] => 188561 
			atau
			[nama] => sharp manu
		)
		*/
		
		// senaraikan tatasusunan jadual
		$myJadual[]='kawal_ppmas09';
		$myJadual[]='kawal_rpe09';
		$myJadual[]='kawal_tani09';
		$myJadual[]='sse08_rangka';
		$myJadual[]='sse09_buat';
		$myJadual[]='sse09_ppt';
		$myJadual[]='sse10_kawal';
		$this->papar->cariNama = array();

		// cari id berasaskan newss/ssm/sidap/nama
		$id['ssm'] = isset($_POST['id']['ssm']) ? $_POST['id']['ssm'] : null;
		$id['nama'] = isset($_POST['id']['nama']) ? $_POST['id']['nama'] : null;
			
		
		if (!empty($id['ssm'])) 
		{
			//echo "POST[id][ssm]:" . $_POST['id']['ssm'];
			$cariMedan = 'sidap'; // cari dalam medan apa
			$cariID = $id['ssm']; // benda yang dicari
			$this->papar->carian='ssm';
			
			// mula cari $cariID dalam $myJadual
			foreach ($myJadual as $key => $myTable)
			{// mula ulang table
				// senarai nama medan
				$medan = ($myTable=='sse10_kawal') ? 
					'sidap,newss,nama' : 'sidap,nama'; 
				$this->papar->cariNama[$myTable] = 
				$this->tanya->cariMedan($myTable, $medan, $cariMedan, $cariID);
			}// tamat ulang table
		}
		elseif (!empty($id['nama']))
		{
			//echo "POST[id][nama]:" . $_POST['id']['nama'];
			$cariMedan = 'nama'; // cari dalam medan apa
			$cariID = $id['nama'];
			$this->papar->carian='nama';
			
			// mula cari $cariID dalam $myJadual
			foreach ($myJadual as $key => $myTable)
			{// mula ulang table
				// senarai nama medan
				$medan = ($myTable=='sse10_kawal') ? 
					'sidap,newss,nama' : 'sidap,nama'; 
				$this->papar->cariNama[$myTable] = 
				$this->tanya->cariMedan($myTable, $medan, $cariMedan, $cariID);
			}// tamat ulang table

		}
		else
		{
			$this->papar->carian='[id:0]';
		}
		
		// paparkan ke fail cimej/cari.php
		$this->papar->baca('cimej/cari');
		
	}
	
	function ubah($cari) 
	{
		//echo '<br>Anda berada di class Imej extends Kawal:ubah2($id)<br>';
		//$url = dpt_url();
		//echo '<pre>$url->'; print_r($url) . '</pre>';
		//echo '<pre>'; print_r($cari) . '</pre>';
		
		// senaraikan tatasusunan jadual
		$myJadual[]='kawal_ppmas09';
		$myJadual[]='kawal_rpe09';
		$myJadual[]='kawal_tani09';
		$myJadual[]='sse08_rangka';
		$myJadual[]='sse09_buat';
		$myJadual[]='sse09_ppt';
		$myJadual[]='sse10_kawal';
		$this->papar->kesID = array();

		// cari id berasaskan sidap
		$id = isset($cari) ? $cari : null;
		
		if (!empty($id)) 
		{
			//echo '$id:' . $id . '<br>';
			$medan = '*'; // senarai nama medan
			$cariMedan = 'sidap'; // cari dalam medan apa
			$cariID = $id; // benda yang dicari
			$this->papar->carian='sidap';
			
			// mula cari $cariID dalam $myJadual
			foreach ($myJadual as $key => $myTable)
			{// mula ulang table
				$this->papar->kesID[$myTable] = 
				$this->tanya->cariSemuaMedan($myTable, $medan, $cariMedan, $cariID);
			}// tamat ulang table
		}
		else
		{
			$this->papar->carian='[tiada id diisi]';
		}
		
		// paparkan ke fail cimej/cari.php
		$this->papar->baca('cimej/ubah');
		
		
	}


}