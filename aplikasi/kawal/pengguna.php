<?php

class Pengguna extends Kawal 
{

	public function __construct() 
	{
		parent::__construct();
        Kebenaran::kawalKeluar();
	}
	
	public function index() 
	{	
		$this->papar->senaraiPengguna = $this->tanya->senaraiPengguna();
		$this->papar->baca('pengguna/index');
	}
	
	public function create() 
	{
		$data = array();
		$data['login'] = $_POST['login'];
		$data['password'] = $_POST['password'];
		$data['role'] = $_POST['role'];
		
		// @TODO: Do your error checking!
		
		$this->tanya->create($data);
		header('location: ' . URL . 'pengguna');
	}
	
	public function edit($id) 
	{
		$this->papar->user = $this->tanya->userSingleList($id);
		$this->papar->baca('pengguna/edit');
	}
	
	public function editSave($id)
	{
		$data = array();
		$data['id'] = $id;
		$data['login'] = $_POST['login'];
		$data['password'] = $_POST['password'];
		$data['role'] = $_POST['role'];
		
		// @TODO: Do your error checking!
		
		$this->tanya->editSave($data);
		header('location: ' . URL . 'pengguna');
	}
	
	public function delete($id)
	{
		$this->tanya->delete($id);
		header('location: ' . URL . 'pengguna');
	}
	
	function smskes($fe)
	{
		// cari fe ada tak 
		if (isset($fe))
		{
			// senaraikan nama jadual dalam tatsusunan
			$myJadual = 'nama_pegawai';
			$cari['medan'] = 'namaPegawai'; // cari dalam medan apa
			$cari['id'] = $fe; // cari fe
			$this->papar->cariNama=array();		
			$this->papar->carian = 'namaPegawai';
			
			// mula cari $cari dalam $myJadual
			$this->papar->cariNama = 
				$this->tanya->cariSatuSahaja($myJadual, 
				$medan = 'namaPegawai,nohp', $cari);
		}
		else
		{
			$this->papar->carian='[tiada fe diisi]';
		}

		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->Tajuk_Muka_Surat='MM 2012 - SMS UNTUK KES FE';
		$this->papar->gambar=gambar_latarbelakang('../../');

		// set pembolehubah untuk mesej kawan-kawan
		$url = dpt_url_xfilter();
		//echo '<pre>$url->' . print_r($url , 1)  . '</pre>';
		/*$url->Array
			[0] => pengguna
			[1] => smskes
			[2] => shukor
			[3] => CARETEX APPARELS SDN. BHD
		*/
		$this->papar->cariNama['mesej'] = !isset($url[3]) ? 'Kosong' : 
			( empty($url[3]) ? 'Tiada mesej' : $url[3]);

		// paparkan ke fail pengguna/smskes.php
		$this->papar->baca('pengguna/smskes');
	}

	function sms()
	{
		/*$_POST->Array
		(	[sms] => Array
				(
					[kawan] => amin
					[mobile_no] => 60123456789
					[message] => hai2bye2
				)
			[hantar] => baki
		)
		*/
		$pn = array('suhaida','sujana','norita', 'azizah');
		foreach ($_POST as $key => $value)
		{	
			if ( $key=='sms')
			{
				foreach ($value as $kekunci => $papar)
				{
					$data[$kekunci] = ($kekunci=='kawan') ?
					( ( in_array($papar,$pn)  ) ?
						'puan ' . bersih($papar)
						: 'tuan ' . bersih($papar)
					) : bersih($papar);
				}				
			}
		}
				
		$siapa = $data['siapa']; // masukkan nama pengguna
		$kawan = $data['kawan']; // masukkan nama kawan
		$hantar = bersih($_POST['hantar']);
		
		// semak sms untuk semak baki atau dihantar
		if ($hantar == 'proses')
			$papar = "SMS berjaya dihantar kepada $kawan.\r"
				   . SmsMisbah::sms_kawan($data);
		elseif ($hantar == 'baki')
			$papar = SmsMisbah::sms_baki($data);
		else	
			$papar = 'Ada masalah teknikal';

		// set URL untuk balik ke lokasi asal
		$url = URL . 'pengguna/smskes/' . $siapa . '/' . $papar;
			
		//echo '<pre>$_POST->' . print_r($_POST , 1)  . '</pre>';
		//echo '<pre>$data->' . print_r($data , 1)  . '</pre>';
		//echo '$url->' . $url;

		// hantar lokasi asal
		header('Location:' . $url);
		
	}

	function sms_get_data($data)
	{
		$dataGet = '';
		foreach($data as $key=>$val)
		{
			if (!empty($dataGet)) $dataGet .= '&';
			$dataGet .= $key . '=' . urlencode($val);
		}

		return $dataGet;
	}
}