<?php

class Cprosesan extends Kawal 
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
		$this->papar->baca('cprosesan/index');
		
	}
	
	function cari() 
	{
		//echo '<br>Anda berada di class Cprosesan extends Kawal:cari()<br>';
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
		// prosesan
		$myJadual[]='q01';
		$myJadual[]='q02';
		$myJadual[]='s04';
		$myJadual[]='s05a';
		$myJadual[]='s05b';
		$myJadual[]='s06&s07';
		$myJadual[]='s14';
		$myJadual[]='s15';
		$myJadual[]='qlain15';
		$myJadual[]='qlain16';
		$myJadual[]='qlain20';
		$myJadual[]='qlain21';
		$myJadual[]='qlain35';
		$this->papar->cariNama = array();

		// cari id berasaskan newss/ssm/sidap/nama
		$id['ssm'] = isset($_POST['id']['ssm']) ? $_POST['id']['ssm'] : null;
				
		if (!empty($id['ssm'])) 
		{
			//echo "POST[id][ssm]:" . $_POST['id']['ssm'];
			$cariMedan = 'estab'; // cari dalam medan apa
			$cariID = $id['ssm']; // benda yang dicari
			$this->papar->carian='ssm';
			
			// mula cari $cariID dalam $myJadual
			foreach ($myJadual as $key => $myTable)
			{// mula ulang table
				// senarai nama medan
				$medan = 'estab,thn,batch';
				$this->papar->cariNama[$myTable] = 
				$this->tanya->cariMedan($myTable, $medan, $cariMedan, $cariID);
			}// tamat ulang table
		}
		else
		{
			$this->papar->carian='[id:0]';
		}
		
		// paparkan ke fail cprosesan/cari.php
		$this->papar->baca('cprosesan/cari');
		
	}
	
	function ubah($cari) 
	{
		//echo '<br>Anda berada di class Cprosesan extends Kawal:ubah2($id)<br>';
		
		// senaraikan tatasusunan jadual
		// prosesan
		$myJadual[]='q01';
		$myJadual[]='q02';
		$myJadual[]='s04';
		$myJadual[]='s05a';
		$myJadual[]='s05b';
		$myJadual[]='s06&s07';
		//$myJadual[]='s14';
		//$myJadual[]='s15';
		$myJadual[]='qlain15';
		$myJadual[]='qlain16';
		$myJadual[]='qlain20';
		$myJadual[]='qlain21';
		$myJadual[]='qlain35';
		$this->papar->kesID = array();

		// cari id berasaskan estab
		$id = isset($cari) ? $cari : null;
		
		if (!empty($id)) 
		{
			//echo '$id:' . $id . '<br>';
			$medan = '*'; // senarai nama medan
			$cariMedan = 'estab'; // cari dalam medan apa
			$cariID = $id; // benda yang dicari
			$this->papar->carian=$cariMedan;
			
			// mula cari $cariID dalam $myJadual
			foreach ($myJadual as $key => $myTable)
			{// mula ulang table
				$this->papar->kesID[$myTable] = 
				$this->tanya->cariSemuaMedan($myTable, $medan, $cariMedan, $cariID);
			}// tamat ulang table
		}
		else
		{
			$this->papar->carian='[id:0]';
		}
		
		// paparkan ke fail cprosesan/cari.php
		$this->papar->baca('cprosesan/ubah');
		
	}
	function tahun($cari) 
	{
		// senaraikan tatasusunan jadual
		// prosesan
		$myJadual[]='q01';
		$myJadual[]='q02';
		$myJadual[]='s04';
		$myJadual[]='s05a';
		$myJadual[]='s05b';
		$myJadual[]='s06&s07';
		//$myJadual[]='s14';
		//$myJadual[]='s15';
		$myJadual[]='qlain15';
		$myJadual[]='qlain16';
		$myJadual[]='qlain20';
		$myJadual[]='qlain21';
		$myJadual[]='qlain35';
		$this->papar->kesID = array();

		// cari id berasaskan estab
		$id = isset($cari) ? $cari : null;
		
		if (!empty($id)) 
		{
			//echo '$id:' . $id . '<br>';
			$medan = 'thn'; // senarai nama medan
			$cariMedan = 'estab'; // cari dalam medan apa
			$cariID = $id; // benda yang dicari
			$this->papar->carian=$cariMedan;
			
			// mula cari tahun dalam $myJadual
			foreach ($myJadual as $key => $myTable)
			{// mula ulang table
				$this->papar->tahun[$myTable] = 
				$this->tanya->cariSemuaMedan($myTable, $medan, $cariMedan, $cariID);
			}// tamat ulang table
		
		}
		else
		{
			$this->papar->carian='[id:0]';
		}
		
		// paparkan ke fail cprosesan/cari.php
		$this->papar->baca('cprosesan/tahun');
			
	}

}