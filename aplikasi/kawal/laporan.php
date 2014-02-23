<?php

class Laporan extends Kawal 
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
    
	public function bulanan($jadual = 'jan13') 
	{	
		// set pembolehubah untuk mengumpul respon yang wujud dalam $myTable
		$respon = $this->tanya->kumpul_respon($myTable = 'mdt_rangka13', 
			$myJoin = 'f2', $medan = 'respon', $jum = null);
        # semak pembolehubah $respon
        //echo '<pre>', print_r($respon, 1) . '</pre><br>';

		$r = 'c.respon'; $kumpul = null; $jumlah_kumpul = null;
        // mula papar semua dalam $myTable
        foreach ($respon as $key => $papar)
        {// mula ulang respon
			$kumpul.=",\rcount(if($r='" . $papar[$medan] 
				. "' and b.terima is not null,$r,null)) as `"
				. $papar[$medan] . "`";
			$jumlah_kumpul.="+count(if($r='" . $papar[$medan] 
				. "' and b.terima is not null,$r,null))\r";
		}// tamat ulang respon
        # semak pembolehubah $respon
        //echo '<pre>$kumpul:' . $kumpul . '<br>$jumlah_kumpul:' . $jumlah_kumpul . '</pre><br>';

		$laporan = $this->tanya->laporan_bulanan($r, $kumpul, $jumlah_kumpul, $jadual);
		$this->papar->cariNama[$jadual] = $laporan;
        # semak pembolehubah $laporan
        //echo '<pre>', print_r($laporan, 1) . '</pre><br>';

		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->fungsi = 'bulanan';
		$this->papar->url = dpt_url();
		// pergi papar kandungan
		$this->papar->baca('laporan/index', 0);
	}

	public function utama($jadual = 'jan13') 
	{	
		// set pembolehubah untuk mengumpul respon yang wujud dalam $myTable
		$respon = $this->tanya->kumpul_respon($myTable = 'mdt_rangka13', 
			$myJoin = 'f2', $medan = 'respon', $jum = null);
        # semak pembolehubah $respon
        //echo '<pre>', print_r($respon, 1) . '</pre><br>';

		$r = 'c.respon'; $kumpul = null; $jumlah_kumpul=null;
        // mula papar semua dalam $myTable
        foreach ($respon as $key => $papar)
        {// mula ulang respon
			$kumpul.=",\rcount(if($r='" . $papar[$medan] 
				. "' and b.terima is not null,$r,null)) as `"
				. $papar[$medan] . "`";
			$jumlah_kumpul.="+count(if($r='" . $papar[$medan] 
				. "' and b.terima is not null,$r,null))\r";
		}// tamat ulang respon
        # semak pembolehubah $respon
        //echo '<pre>$kumpul:' . $kumpul . '<br>$jumlah_kumpul:' . $jumlah_kumpul . '</pre><br>';

		$laporan = $this->tanya->laporan_utama($r, $kumpul, $jumlah_kumpul, $jadual);
		$this->papar->cariNama['laporan'] = $laporan;
        # semak pembolehubah $laporan
        //echo '<pre>', print_r($laporan, 1) . '</pre><br>';

		// Set pemboleubah utama
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->fungsi = 'bulanan';
		$this->papar->url = dpt_url();
		// pergi papar kandungan
		$this->papar->baca('laporan/index', 0);
	}
	
    public function index($item = 30, $ms = 1, $fe = null) 
    {    
        $bulanan = bulanan('kawalan','13'); # papar bulan dlm tahun semasa     
        // setkan pembolehubah untuk $this->tanya
            $medanRangka = '*'; 
			$medanData = 'newss,msic,nama,utama,fe,terima,'
					   . 'hasil,dptLain,web,stok,staf,gaji,outlet,sebab';
            $sv='mdt_';
        // mula papar semua dalam $myTable
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
            paparSemua($sv, $myTable, $medan, $fe, $jum);
            // halaman
            $this->papar->halaman[$myTable] = halaman($jum);
        }// tamat ulang table
        
        // papar
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'semuajadual';
        $this->papar->baca('kawalan/index');
    }
    
}
