<?php

class Suku4 extends Kawal 
{

	public function __construct() 
	{
		parent::__construct();
		Kebenaran::kawalKeluar();	
		//$this->papar->js = array('ruangtamu/js/default.js');
		$this->papar->js = array(
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
			'bootstrap-datepicker.ms.js');
		$this->papar->css = array(
			'bootstrap-datepicker.css');
		$this->_folder = 'suku4/';
		$this->_jadual = 'qss12_s4';
		$this->_medan  = '*';
	}
	
	public function index() 
	{	//echo 'class qss4 fungsi index()<br>';		
		
		// papar semua data
		$this->papar->senaraiData[$this->_jadual] = 
			$this->tanya->paparIkutSurvey($this->_jadual);
		$this->papar->medanID ='subsektor';
		//echo '<pre>$senaraiData->', print_r($this->papar->senaraiData, 1) . '</pre>';# papar $senaraiData
		
		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		$this->papar->gambar=gambar_latarbelakang('../../');		

		// pergi papar kandungan fungsi papar($this->_folder) dalam KAWAL
		$fail = Kebenaran::papar($this->_folder);
		$this->papar->baca($fail);
	}

	public function asing($medanID, $cariID) 
	{	//echo 'class qss4 fungsi index()<br>';		
		$cari = array('medan' => $medanID,
			'operator' => '=','id' => $cariID);

		// papar semua data
		$this->papar->senaraiData[$this->_jadual] = 
			$this->tanya->asingSv($this->_jadual, 'newss,nama,fe'
			. ',concat_ws(\'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\',newss,\'QSS2012\',msic2000'
			. ') as `kod`,sidap'
			. ',concat_ws(\'<br>\',\'PENGURUS\',nama,operator) as `kod2`'
			. ',concat_ws(' . "'<br>\n'" . ',alamat1,alamat2,poskod,bandar,ng) as `alamat penuh`' 
			. ',concat_ws(' . "'<br>\n'" . ',utama,msic2008,msic2000,ngdbbp) as kod3'
			. ',subsektor',$cari);
		$this->papar->medanID ='newss';
		//echo '<pre>$senaraiData->', print_r($this->papar->senaraiData, 1) . '</pre>';# papar $senaraiData
		
		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		$this->papar->gambar=gambar_latarbelakang('../../');		

		// pergi papar kandungan fungsi papar($this->_folder) dalam KAWAL
		$fail = Kebenaran::papar($this->_folder);
		$this->papar->baca($fail);
	}
	
	public function papar($newss) 
	{	
		if ( is_numeric($newss) )
			$cari = array('medan' => 'newss',
			'operator' => '=','id' => $newss);
		else
			$cari = array('medan' => 'fe',
			'operator' => '<>','id' => 'semak');
		
		// papar semua data
		$this->papar->senaraiData[$this->_jadual] = 
			$this->tanya->paparPOM($this->_jadual, 'newss,nama,fe'
			. ',concat_ws(\'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\',newss,\'QSS2012\',msic2000'
			. ') as `kod`,sidap'
			. ',concat_ws(\'<br>\',\'PENGURUS\',nama,operator) as `kod2`'
			. ',concat_ws(' . "'<br>\n'" . ',alamat1,alamat2,poskod,bandar,ng) as `alamat penuh`' 
			. ',concat_ws(' . "'<br>\n'" . ',utama,msic2008,msic2000,ngdbbp) as kod3'
			. ',subsektor',$cari);
		$this->papar->medanID ='newss';
		//echo '<pre>$senaraiData->', print_r($this->papar->senaraiData, 1) . '</pre>';# papar $senaraiData

		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		$this->papar->gambar=gambar_latarbelakang('../../');

		// pergi papar kandungan fungsi papar($this->_folder) dalam KAWAL
		$fail = Kebenaran::papar($this->_folder);
		$this->papar->baca($fail);
	}
	
	public function cari() 
	{	
		$jadual['nama'] = $this->_jadual;
		$senarai = $this->tanya->paparMedan($jadual['nama']);
		
		# Memilih nama medan dalam jadual berkenaan
		foreach ($senarai as $key => $medan): #mula ulang $kunci
			$jadual['medan'][$key] = $medan['Field'];
		endforeach; #tamat ulang $kunci

		//echo '<pre>$jadual->', print_r($jadual, 1) . '</pre>';# papar $jadual

		$this->papar->paparMedan = $jadual;
		$this->papar->url = dpt_url();

		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		$this->papar->gambar=gambar_latarbelakang('../../');

		// pergi papar kandungan
		$this->papar->baca($this->_folder . 'cari');
	}

	function pada($bil) 
	{
		/*
		 * fungsi ini memaparkan hasil carian
		 */
		 
		$url = dpt_url();
		$had = '0, ' . $url[2];
		//echo '<pre>$url->', print_r($url, 1) . '</pre>';
	
		$kira = pecah_post(); # echo '<pre>$kira->'; print_r($kira); echo '</pre>';
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
		elseif (!empty($namajadual) && $namajadual==$this->_jadual) 
		{
			$myTable = $namajadual;
			// mula cari $cariID dalam $jadual
			$this->papar->cariApa[$myTable] 
					= $this->tanya->cariBanyakMedan($myTable, 
						$medan = '*', $kira, $had);
			$this->papar->carian=$carian;
		}

		//echo '<pre>$this->papar->cariApa:', print_r($this->papar->cariApa, 1) . '</pre>';

		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		$this->papar->gambar=gambar_latarbelakang('../../');

		// pergi papar kandungan
		$this->papar->baca($this->_folder . 'jumpa');

	}
	
	function tambah() 
	{				
		$myTable = $this->_jadual;
		
		// set dalam KAWAL sahaja
		$paparMedan[$myTable] = $this->tanya->paparMedan($myTable);
		// dapatkan nama_medan,jenis_medan,input_medan 
		// dlm class Borang::tambah()
		Borang::tambah($paparMedan);
		
		// set dalam LIHAT sahaja
		$this->papar->paparMedan[$myTable] = $paparMedan[$myTable];
	
		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		$this->papar->gambar=gambar_latarbelakang('../../');

		// pergi papar kandungan
		$this->papar->baca($this->_folder . 'tambah');
	}
	
	public function tambahSimpan() 
	{	
		// semak $_POST dalam class Borang
		$data = Borang::tambahSimpan($this->_jadual); //echo '<pre>$data:'; print_r($data) . '</pre>';
		$jadual = $data['namaJadual'];
		$this->tanya->tambahSimpan($data, $jadual);
		
		// pergi papar kandungan tambahSimpan() dalam KAWAL
		Kebenaran::tambahSimpan($this->_folder);
		//$this->papar->baca($fail);
	}
	
	public function ubah($medanID, $cariID, $mesej = null) 
	{	//echo '$this->tanya->noAhli('.$medanID.'='.$cariID .')<br>';

		$myTable = $this->_jadual;
		
		// set dalam KAWAL sahaja
		$cari['medan'] = $medanID;
		$cari['id'] = $cariID;
		$noAhli = $this->tanya->noAhli($myTable, '*', $cari);
		$paparMedan[$myTable] = $this->tanya->paparMedan($myTable);

		// dapatkan nama_medan,jenis_medan,input_medan dlm class Borang::ubah()
		$this->papar->inputBorang = Borang::ubah($noAhli, $paparMedan, $this->_medan);
				
		// set dalam LIHAT sahaja
		$this->papar->paparMedan[$myTable] = $paparMedan[$myTable];
		$this->papar->noAhli[$myTable][] = $noAhli;
		$this->papar->medan  = $medanID;
		$this->papar->cariID = $cariID;
		$this->papar->mesej = (isset($mesej)) ? $mesej : null;
				
		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		$this->papar->gambar=gambar_latarbelakang('../../');

		// pergi papar kandungan
		$this->papar->baca($this->_folder . 'ubah');	
	}
	
	public function ubahSimpan($medanID, $cariID)
	{
		// semak $_POST dalam class Borang::ubahSimpan($thid->_jadual)
		$data = Borang::ubahSimpan($this->_jadual);
		//echo '<pre>$data:'; print_r($data) . '</pre>';
		$jadual = $data['namaJadual'];
		$jadual['medanID'] = $medanID;
		$jadual['cariID'] = $cariID;
		
		#Do your error checking!
		$semakID = $this->tanya->ubahSimpan($data, $jadual);
		$pilihID = ($cariID==$semakID) ? $cariID : $semakID;
		$ID = 'ubah/' . $medanID . '/' . $pilihID . '/berjaya';
		
		// pergi papar kandungan ubahSimpan($medanID, $cariID) dalam KAWAL
		Kebenaran::ubahSimpan($this->_folder, $ID);
		$this->papar->baca($fail);
	}
	
	public function buang($medanID, $cariID)
	{
		//echo '$this->tanya->buang('.$medanID.'='.$cariID .')<br>';
		$jadual = $this->_jadual;
		$cari['medan'] = $medanID;
		$cari['id'] = $cariID;

		$this->tanya->buang($jadual, null, $cari);
		header('location: ' . URL . $this->_folder);
	}

	public function rangka($medanID, $cariID, $mesej = null) 
	{	//echo '$this->tanya->noAhli('.$medanID.'='.$cariID .')<br>';

		$myTable = $this->_jadual;
		
		// set dalam KAWAL sahaja
		$cari['medan'] = $medanID;
		$cari['id'] = $cariID;
		$medan2 = 'newss,sidap,nama,fe'
			   . ',alamat1,alamat2,poskod,bandar,ng,dp' 
			   . ',utama,msic2008,msic2000,ngdbbp,subsektor';
		$medan = '*';
		$noAhli = $this->tanya->noAhli($myTable, $medan, $cari);
		$paparMedan[$myTable] = $this->tanya->paparMedan($myTable, $medan);

		// semak pembolehubah
		//echo '<pre>$noAhli:'; print_r($noAhli) . '</pre>';
		//echo '<pre>$paparMedan:'; print_r($paparMedan) . '</pre>';
		// dapatkan nama_medan,jenis_medan,input_medan dlm class Borang::ubah()
		$this->papar->inputBorang = Borang::ubah($noAhli, $paparMedan, $this->_medan);
				
		// set dalam LIHAT sahaja
		$this->papar->paparMedan[$myTable] = $paparMedan[$myTable];
		$this->papar->noAhli[$myTable][] = $noAhli;
		$this->papar->medan  = $medanID;
		$this->papar->cariID = $cariID;
		$this->papar->mesej = (isset($mesej)) ? $mesej : null;
				
		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		$this->papar->gambar=gambar_latarbelakang('../../');

		// pergi papar kandungan
		$this->papar->baca($this->_folder . 'export');
	}

	public function exportExcel($medanID, $cariID)
	{
		// semak pembolehubah
		//echo '<pre>$_POST:'; print_r($_POST) . '</pre>';
		//echo 'url=' . URL;
		###############################################################################
		$namasyarikat=bersih($_POST['qss12_s4']['nama']);
		$sv = Excel::pilihSurvey();
		//echo '<pre>$sv:'; print_r($sv) . '</pre>';
		//$data = Excel::$sv['template']();
		$data = Excel::simpanData(Excel::$sv['template']());
		//echo '<pre>$data:'; print_r($data) . '</pre>';
		################################################################################
		$template = $sv['template'] . '.xls'; // contoh template borang dalam excel xls
		$templateDir = '../../bg/template/borang_qss/'; // setkan folder yang ada contoh template borang

		//set tatarajah untuk class PHPReport
		$config=array(
				'template'=>$template,
				'templateDir'=>$templateDir
			);
		//echo '<pre>$config:'; print_r($config) . '</pre>';
		################################################################################

		// export to excel again
		$R=new PHPReport($config);
		$R->load($data); // $data adalah tatasusunan dari pangkalan data
		//print_r($R);
		################################################################################
		// kita boleh setkan fail untuk dimuatturun
		// seperti html, excel, excel2003 sebagai $type
		// dan $filename sebagai nama fail
		// public function render($type='html',$filename='')
		$type = array ('html','excel','excel2003');
		$filename = $namasyarikat;
		echo $R->render($type[2], $filename);
		exit();
		
	}

}
