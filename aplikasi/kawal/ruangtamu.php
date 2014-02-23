<?php

class Ruangtamu extends Kawal 
{

	function __construct() 
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
		$this->papar->kini = 'jan12';
	}
	
	function index() 
	{	
		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		// pergi papar kandungan
		$this->papar->baca('ruangtamu/index');
	}
	
	function logout()
	{
		Sesi::destroy();
		header('location: ' . URL);
		exit;
	}
	
	function xhrInsert()
	{
		$this->tanya->xhrInsert();
	}
	
	function xhrGetListings()
	{
		$this->tanya->xhrGetListings();
	}
	
	function xhrDeleteListing()
	{
		$this->tanya->xhrDeleteListing();
	}

}