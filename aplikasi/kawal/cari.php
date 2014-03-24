<?php

class Cari extends Kawal 
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
		$this->papar->medan = array(1,2,3);
		// set latarbelakang
		$this->papar->gambar=gambar_latarbelakang('../../');
		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		// pergi papar kandungan
		$this->papar->baca('cari/index');
	}
	
	public function msic() 
	{	
		/* fungsi ini memaparkan borang
		 * untuk carian msic sahaja
		 */
		$this->papar->medan = $this->tanya->paparMedan('msic');
		$url = dpt_url();
		$this->papar->url = $url;

		// set latarbelakang
		$this->papar->gambar=gambar_latarbelakang('../../');
		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		// pergi papar kandungan
		$this->papar->baca('cari/index');
	}

	public function produk() 
	{	
		/* fungsi ini memaparkan borang
		 * untuk carian produk sahaja
		 */
		$this->papar->medan = $this->tanya->paparMedan('kodproduk_mei2011');
		$url = dpt_url();
		$this->papar->url = $url;

		// set latarbelakang
		$this->papar->gambar=gambar_latarbelakang('../../');
		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		// pergi papar kandungan
		$this->papar->baca('cari/index');
	}

	public function syarikat() 
	{	
		/* fungsi ini memaparkan borang
		 * untuk carian syarikat sahaja
		 */
		$this->papar->medan = $this->tanya->paparMedan('mdt_rangka14');
		//echo '<pre>$this->papar->medan:<br>'; print_r($this->papar->medan); 
		$url = dpt_url();
		$this->papar->url = $url;
		
		// set latarbelakang
		$this->papar->gambar=gambar_latarbelakang('../../');
		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MDT 2014';
		// pergi papar kandungan
		$this->papar->baca('cari/index', 0);
	}

	public function data() 
	{	
		// set latarbelakang
		$this->papar->gambar=gambar_latarbelakang('../../');
		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		// pergi papar kandungan
		$this->papar->baca('cari/index');
	}

	function pada($bil) 
	{
		/* fungsi ini memaparkan hasil carian
		 * untuk jadual msic2000 dan msic2008
		 */
	
		$kira = pecah_post($_POST); //echo '<pre>'; print_r($kira); echo '</pre>';
		// setkan pembolehubah dulu
		$namajadual = isset($_POST['namajadual']) ? $_POST['namajadual'] : null;
		$susun = isset($_POST['susun']) ? $_POST['susun'] : 1;
		$carian = isset($_POST['cari']) ? $_POST['cari'] : null;
		$semak = isset($_POST['cari'][1]) ? $_POST['cari'][1] : null;
		$this->papar->cariNama = null;
			
		if (empty($semak)) 
		{
			header('location:' . URL . 'cari/' . $namajadual . '/1');
			exit;	
		}
		elseif (!empty($namajadual) && $namajadual=='msic') 
		{
			$jadual = dpt_senarai('msiclama');
			// mula cari $cariID dalam $jadual
			foreach ($jadual as $key => $myTable)
			{// mula ulang table
				// senarai nama medan
				$medan = ($myTable=='msic2008') ? 
					'seksyen S,bahagian B,kumpulan Kpl,kelas Kls,' .
					'msic2000,msic,keterangan,notakaki' 
					: '*'; 
				$this->papar->cariNama[$myTable] = $this->tanya
				->cariSemuaMedan($myTable, $medan, $kira);
			}// tamat ulang table
			
			$this->papar->carian=$carian;
		}
		elseif (!empty($namajadual) && $namajadual=='produk') 
		{
			$jadual = dpt_senarai('produk');
			// mula cari $cariID dalam $jadual
			foreach ($jadual as $key => $myTable)
			{// mula ulang table
				// senarai nama medan
				$medan = '*'; 
				$this->papar->cariNama[$myTable] = $this->tanya
				->cariSemuaMedan($myTable, $medan, $kira);
			}// tamat ulang table
			
			$this->papar->carian=$carian;
			
			$unit = 'kodproduk_unitkuantiti';
				$this->papar->cariNama[$unit] = $this->tanya
					->paparSemuaJadual($unit, '*');

		}
		elseif (!empty($namajadual) && $namajadual=='syarikat') 
		{
			$jadual = dpt_senarai('syarikat');
			// mula cari $cariID dalam $jadual
			$this->papar->cariNama = $this->tanya
				//->cariCantumJadual($jadual, $medan = '*', $kira);
				->cariKawalan($jadual, $kira);

			//echo '<pre>$cariNama::'; print_r($this->papar->cariNama) . '</pre>';
			
			$this->papar->carian=$carian;
		}
		else
		{
			$this->papar->carian[]='[id:0]';
		}

		// set latarbelakang
		$this->papar->gambar=gambar_latarbelakang('../../');
		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MDT 2013';
		// paparkan ke fail cari/$namajadual.php
		$this->papar->baca('cari/' . $namajadual, 0);		
//*/
	}

}