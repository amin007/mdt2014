<?php

class Bbu extends Kawal 
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
    }
    
    public function semua($item = 30, $ms = 1, $fe = null, $bulan = null) 
    {    
        /*
         * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
         * $fe = null // set $fe = pegawai kerja luar tiada
         */
        // setkan pembolehubah untuk $this->tanya
			$medanRangka = $this->medanRangka;
			$medanData = $this->medanData;
			$sv = $this->sv;
        // mula papar semua dalam $myTable
        $bulanan = bulanan('kawalan','14'); # papar bulan dlm tahun semasa
        # semak pembolehubah $bulanan
        //echo '<pre>', print_r($bulanan, 1) . '</pre><br>';
        foreach ($bulanan as $key => $myTable)
        {// mula ulang table
			// setkan $medan
			$medan = ($myTable=='rangka13') ? $medanRangka : $medanData;
            // dapatkan bilangan jumlah rekod
            $bilSemua = $this->tanya->kiraKes($sv, $myTable, $medan, $fe);
            // tentukan bilangan mukasurat 
            // bilangan jumlah rekod
			//echo '$bilSemua:'.$bilSemua.', $item:'.$item.', $ms:'.$ms.'<br>';
            $jum = pencamSqlLimit($bilSemua, $item, $ms);
            $this->papar->bilSemua[$myTable] = $bilSemua;
            // sql guna limit
            $this->papar->cariApa[$myTable] = $this->tanya->
            kesSemua($sv, $myTable, $medan, $fe, $jum);
            // halaman
            $this->papar->halaman[$myTable] = halaman($jum);
        }// tamat ulang table
        
        # semak pembolehubah $this->papar->cariApa
        //echo '<pre>', print_r($this->papar->cariApa, 1) . '</pre><br>';

        // Set pemboleubah utama
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'semua';
        $this->papar->url = dpt_url();
        // pergi papar kandungan
        $this->papar->baca('kawalan/index', 0);
    }

    public function dah($item = 30, $ms = 1, $fe = null, $bulan = null) 
    {    
        /*
         * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
         * $fe = null // set $fe = pegawai kerja luar tiada
         */

        // setkan pembolehubah untuk $this->tanya
			$medanRangka = $this->medanRangka;
			$medanData = $this->medanData;
			$sv = $this->sv;
        // mula papar semua dalam $myTable
        $bulanan = bulanan('kawalan','14'); # papar bulan dlm tahun semasa
        # semak pembolehubah $bulanan
        //echo '<pre>', print_r($bulanan, 1) . '</pre><br>';
        foreach ($bulanan as $key => $myTable)
        {// mula ulang table
			// setkan $medan
			$medan = ($myTable=='rangka13') ? $medanRangka : $medanData;
            // dapatkan bilangan jumlah rekod
            $bilSemua = $this->tanya->kiraKes($sv, $myTable, $medan, $fe);
            // tentukan bilangan mukasurat 
            // bilangan jumlah rekod
			//echo '$bilSemua:'.$bilSemua.', $item:'.$item.', $ms:'.$ms.'<br>';
            $jum = pencamSqlLimit($bilSemua, $item, $ms);
            $this->papar->bilSemua[$myTable] = $bilSemua;
            // sql guna limit
            $this->papar->cariApa[$myTable] = $this->tanya->
            kesSelesai($sv, $myTable, $medan, $fe, $jum);
            // halaman
            $this->papar->halaman[$myTable] = halaman($jum);
        }// tamat ulang table
        
        # semak pembolehubah $this->papar->cariApa
        //echo '<pre>', print_r($this->papar->cariApa, 1) . '</pre><br>';

        // Set pemboleubah utama
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'siap';
        $this->papar->url = dpt_url();
        // pergi papar kandungan
        $this->papar->baca('kawalan/index', 0);
    }
	
	public function lum($item = 30, $ms = 1, $fe = null, $bulan = null) 
    {    
        /*
         * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
         * $fe = null // set $fe = pegawai kerja luar tiada
         */

        // setkan pembolehubah untuk $this->tanya
			$medanRangka = $this->medanRangka;
			$medanData = $this->medanData;
			$sv = $this->sv;
        // mula papar semua dalam $myTable
        $bulanan = bulanan('kawalan','14'); # papar bulan dlm tahun semasa
        # semak pembolehubah $bulanan
        //echo '<pre>', print_r($bulanan, 1) . '</pre><br>';
        foreach ($bulanan as $key => $myTable)
        {// mula ulang table
			// setkan $medan
			$medan = ($myTable=='rangka13') ? $medanRangka : $medanData;
            // dapatkan bilangan jumlah rekod
            $bilSemua = $this->tanya->kiraKes($sv, $myTable, $medan, $fe);
            // tentukan bilangan mukasurat 
            // bilangan jumlah rekod
			//echo '$bilSemua:'.$bilSemua.', $item:'.$item.', $ms:'.$ms.'<br>';
            $jum = pencamSqlLimit($bilSemua, $item, $ms);
            $this->papar->bilSemua[$myTable] = $bilSemua;
            // sql guna limit
            $this->papar->cariApa[$myTable] = $this->tanya->
            kesBelum($sv, $myTable, $medan, $fe, $jum);
            // halaman
            $this->papar->halaman[$myTable] = halaman($jum);
        }// tamat ulang table
        
        # semak pembolehubah $this->papar->cariApa
        //echo '<pre>', print_r($this->papar->cariApa, 1) . '</pre><br>';

        // Set pemboleubah utama
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'belum';
        $this->papar->url = dpt_url();
        // pergi papar kandungan
        $this->papar->baca('kawalan/index', 0);
    }

}
