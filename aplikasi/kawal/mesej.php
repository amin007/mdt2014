<?php

class Mesej extends Kawal 
{

    public function __construct() 
    {
        parent::__construct();
        Kebenaran::kawalKeluar();
        
        $this->lihat->js = array(
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
        $this->lihat->css = array(
            'bootstrap-datepicker.css',
            'bootstrap-editable.css');
			
		$this->_folder = 'mesej';
		$this->pengguna = Sesi::get('namaPegawai');
		$this->level = Sesi::get('levelPegawai');

    }
    
    public function index() 
    {    
		// tarik data dari jadual nama_pegawai
		$this->lihat->senaraiPegawai['biodata'] = $this->tanya->cariMedan('nama_pegawai', 
			'namaPegawai,Nama_Penuh,email,nohp,unit', 
			array('medan'=>'unit', 'id'=>'ekonomi'));
	
		//
		$this->lihat->ralat = null;
		
		$this->lihat->pegawai = senarai_kakitangan();
        $this->lihat->baca($this->_folder . '/index', 0);
    }   
	
	 public function utama() 
    {    
		$this->lihat->ralat = null;
		
		$this->lihat->pegawai = senarai_kakitangan();
        $this->lihat->baca($this->_folder . '/utama', 0);
    }   

    
	public function ubahCari()
	{
		// echo '<pre>$_POST->', print_r($_POST, 1) . '</pre>';
		// bersihkan data $_POST
		$dataID = bersih($_POST['cari']);
		
		// Set pemboleubah utama
        $this->lihat->pegawai = senarai_kakitangan();
        $this->lihat->lokasi = 'MDT 2012 - Ubah';
		
		// paparkan ke fail kawalan/ubah.php
		header('location: ' . URL . 'kawalan/ubah/' . $dataID);

	}

    public function ubahSimpan($dataID)
    {
        $bulanan = bulanan('kawalan','13'); # papar bulan dlm tahun semasa
        $posmen = array();
        $id = 'newss';
    
        foreach ($_POST as $key => $value)
        {
            if ( in_array($key,$bulanan) )
            {
                $myTable = 'mdt_' . $key;
                foreach ($value as $kekunci => $papar)
                {
                    $posmen[$myTable][$kekunci] = bersih($papar);
                }
                $posmen[$myTable][$id] = $dataID;
            }
        }
        
		// ubahsuai $posmen
			# buat peristiharan
			$rangka = 'mdt_rangka13'; // rangka kawalan kes
			$posmen[$rangka]['respon']=strtoupper($posmen[$rangka]['respon']);
			$posmen[$rangka]['fe']=strtolower($posmen[$rangka]['fe']);
			$posmen[$rangka]['email']=strtolower($posmen[$rangka]['email']);
			$posmen[$rangka]['responden']=mb_convert_case($posmen[$rangka]['responden'], MB_CASE_TITLE);
        //echo '<br>$dataID=' . $dataID . '<br>';
        //echo '<pre>$_POST='; print_r($_POST) . '</pre>';
        //echo '<pre>$posmen='; print_r($posmen) . '</pre>';
        
        // mula ulang $bulanan
        
        foreach ($bulanan as $kunci => $jadual)
        {// mula ulang table
            $myTable = 'mdt_' . $jadual;
			$posmen[$myTable]['fe'] = $posmen[$rangka]['fe'];
            $data = $posmen[$myTable];
            $this->tanya->ubahSimpan($data, $myTable);
        }// tamat ulang table
        
        //$this->lihat->baca('kawalan/ubah/' . $dataID);
        header('location: ' . URL . 'kawalan/ubah/' . $dataID);
        
    }

}
