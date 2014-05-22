<?php

class Papar extends Kawal 
{
    public function __construct() 
    {
        parent::__construct();
        Kebenaran::kawalKeluar();
        
        $this->papar->js = array(
            /*'bootstrap.js',
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
            'bootstrap-affix.js',*/
            'bootstrap-datepicker.js',
            'bootstrap-datepicker.ms.js',
            'bootstrap-editable.min.js');
        $this->papar->css = array(
            'bootstrap-datepicker.css',
            'bootstrap-editable.css');
/*			
        $this->medanRangka = 'newss,ssm,concat_ws("<br>",nama,operator) as nama,'
			. 'fe,msic,sv,utama,respon R,' . "\r"
			. 'concat_ws("<br>",alamat1,alamat2,poskod,ngdbbp) as alamat,' . "\r"
			. 'thn,concat_ws("<br>",semak_rangka12,data12) as data12,label,' . "\r"
			. 'tel,fax,concat_ws("<br>","PENGURUS|PEMILIK|KERANI",responden) as Orang,'
			. 'email,nota,msic08';
			//. 'newss,msic,nama,utama,fe,terima,'
			//. 'hasil,dptLain,web,stok,staf,gaji,outlet,sebab';
		$this->medanData = 'newss,msic,nama,utama,fe,terima,'
			. ' format( (hasil + IFNULL(dptLain,0) ), 0 ) as dapat,'
			//. '(hasil+COALESCE(dptLain,0)) as dapat2,'
			. 'format(hasil,0) as hasil,format(dptlain,0) as dptlain,' . "\r"
			. 'format(stok,0) as stok,staf,format(gaji,0) as gaji,' . "\r"
			. 'outlet,sebab';
		$this->sv = 'mdt_';
		$this->_folder = 'kawalan';
		$this->pengguna = Sesi::get('namaPegawai');
		$this->level = Sesi::get('levelPegawai');
//*/		
    }
    
    public function index() 
	{
		$respon = 'semua'; $item = 30; $ms = 1; $fe = null; $cetak = null;
		echo "$respon | $item | $ms | $fe | $cetak <br>";
	}

}