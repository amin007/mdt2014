<?php

class Sesat extends Kawal 
{

	function __construct() 
	{
		parent::__construct();
        Kebenaran::kawalKeluar();
	}
	
	function index() 
	{
		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->gambar=gambar_latarbelakang('../../');
		$this->papar->Tajuk_Muka_Surat='MM 2013';
		$this->papar->url = dpt_url();
		// pergi papar kandungan
		$this->papar->mesej = 'Halaman ini tidak wujud';
		$this->papar->baca('sesat/index');
	}

}