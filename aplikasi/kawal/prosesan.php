<?php

class Prosesan extends Kawal 
{

	public function __construct() 
	{
		parent::__construct();
        Kebenaran::kawalKeluar();		
		
        $this->papar->js = array(
            //'bootstrap.js',
            'bootstrap-transition.js',
            'bootstrap-alert.js',
            'bootstrap-modal.js',
            'bootstrap-dropdown.js',
            'bootstrap-scrollspy.js',
            'bootstrap-tab.js',
            'bootstrap-tooltip.js',
            'bootstrap-popover.js',
            'bootstrap-button.js',
            'bootstrap-collapse.js',
            'bootstrap-carousel.js',
            'bootstrap-typeahead.js',
            'bootstrap-affix.js',
            'bootstrap-datepicker.js',
            'bootstrap-datepicker.ms.js',
            'bootstrap-editable.min.js');
        $this->papar->css = array(
            'bootstrap-datepicker.css',
            'bootstrap-editable.css');

	}
	
	public function index() 
	{	
		$bulanan = bulanan('prosesan','12'); # papar bulan dlm tahun semasa
		$medan = '*'; # senarai nama medan
		$sv='prosesmm_'; # jenis survey

		// mula papar carian dalam $bulanan
		foreach ($bulanan as $key => $myTable)
		{// mula ulang table
			$this->papar->cariApa[$myTable] = 
			$this->tanya->senaraiKes($sv, $myTable, $medan);
		}// tamat ulang table
		
		// papar
		$this->papar->baca('prosesan/index');
	}

	public function semua($tahun) 
	{	
		// tentukan bilangan mukasurat
			$bilSemua=1000; // bilangan jumlah rekod
		$jum = pencamSqlLimit($bilSemua);

		// senaraikan tatasusunan jadual
		$jadual = data_prosesan(); # papar bulan dlm tahun semasa
		// mula cari dalam $jadual['nama']
		foreach ($jadual['nama'] as $key => $myTable)
		{// mula ulang table
			$this->papar->cariApa[$key . '-' . $myTable] = 
			$this->tanya->hadKes($myTable, 
			$jadual['medan'][$key], $tahun=null, $jum);
		}// tamat ulang table

		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		$this->papar->gambar=gambar_latarbelakang('../../');
		$this->papar->carian = 'semua';
		//$this->papar->halaman = halaman($jum);
		$this->papar->url = dpt_url();
		// pergi papar kandungan
		$this->papar->baca('prosesan/index');
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
		$tahunan = tahunan('tahunan','12'); # papar dlm tahun semasa
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
			foreach ($tahunan as $key => $myTable)
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
			foreach ($tahunan as $key => $myTable)
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
		//echo '<br>Anda berada di class Imej extends Kawal:ubah($cari)<br>';
		// cari id berasaskan sidap
		$id = isset($cari) ? $cari : null;
		
		if (!empty($id)) 
		{
			//echo '$id:' . $id . '<br>';
			$cariMedan = 'newss'; // cari dalam medan apa
			$cariID = $id; // benda yang dicari
			$this->papar->carian='newss';
			$this->papar->kesID = array();			
			
			// senaraikan tatasusunan jadual
			$jadual = data_prosesan(); # papar bulan dlm tahun semasa
			// mula cari $cariID dalam $jadual['nama']
			foreach ($jadual['nama'] as $key => $myTable)
			{// mula ulang table
				$this->papar->kesID[$key . '-' . $myTable] = 
				$this->tanya->cariProsesan($myTable, 
				$jadual['medan'][$key], $cariMedan, $cariID);
			}// tamat ulang table
		}
		else
		{
			$this->papar->carian='[tiada id diisi]';
		}
		
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->gambar=gambar_latarbelakang('../../');
		$this->papar->Tajuk_Muka_Surat='MM 2012 - Prosesan Ubah';
		$this->papar->kesID2 = null;
		// paparkan ke fail cimej/cari.php
		$this->papar->baca('prosesan/edit');
		
	}


}