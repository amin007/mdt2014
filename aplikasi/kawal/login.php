<?php

class Login extends Kawal 
{

	function __construct() 
	{
		parent::__construct();
        Kebenaran::kawalMasuk();
	}
	
	function index() 
	{	
		$this->papar->gambar=gambar_latarbelakang(null);
		// Set pemboleubah utama
		$this->papar->isi='';
		// pergi papar kandungan
		$this->papar->baca('index/index');
	}
	
	function semakid()
	{
		$this->tanya->semakid();
	}
	
	function salah()
	{
		$this->papar->mesej = 'Ada masalah pada user dan password';

		// Set pemboleubah utama
		$this->papar->sesat='Enjin Carian Ekonomi - Sesat';
		$this->papar->isi='';

		// pergi papar kandungan
		$this->papar->baca('index/salah');
	}

}