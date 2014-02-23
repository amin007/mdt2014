<?php

class Index extends Kawal 
{

	function __construct() 
	{
		parent::__construct();
        Kebenaran::kawalMasuk();
	}
	
	function index() 
	{
		// set latarbelakang
		$this->papar->gambar=gambar_latarbelakang('../../');
		// Set pemboleubah utama
		$this->papar->Tajuk_Muka_Surat='MM 2012';
		$this->papar->isi='';
		$this->papar->isi2='';
		// pergi papar kandungan
		$this->papar->baca('index/index');
	}
	
	function login($user) 
	{
		$this->papar->nama=$user; # dapatkan nama pengguna
		$this->papar->IP=dpt_ip(); # dapatkan senarai IP yang dibenarkan
		// pergi papar kandungan
		$this->papar->baca('index/login');
	}

	function login_automatik($user) 
	{
		$this->papar->nama=$user; # dapatkan nama pengguna
		$this->papar->IP=dpt_ip(); # dapatkan senarai IP yang dibenarkan
		// pergi papar kandungan
		$this->papar->baca('index/login_automatik');
	}

	function details() 
	{
		$this->papar->baca('index/index');
	}
	
}