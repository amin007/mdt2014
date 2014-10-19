<?php

class Paparan extends Kawal 
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
		$this->medanData = 'newss,utama,msic,nama,fe,'
			. 'concat_ws("|","<input type=\"checkbox\">",'
			. 'concat("hasil=",(hasil + IFNULL(dptLain,0)) ),' // hasil
			. 'concat("staf=",(staf) ),' // staf
			. 'concat("gaji=",(gaji) )' // gaji
			. ' )as `5p`,terima,'
			//. ' format( (hasil + IFNULL(dptLain,0) ), 0 ) as dapat,'
			//. '(hasil+COALESCE(dptLain,0)) as dapat2,'
			. 'format(hasil,0) as hasil,format(dptlain,0) as dptlain,' . "\r"
			. 'format(stok,0) as stok,staf,format(gaji,0) as gaji,' . "\r"
			. 'outlet,sebab';
		$this->sv = 'mdt_';
		$this->_folder = 'kawalan';
		$this->jadualKawalan = bulanan('data_bulanan','14'); # papar bulan dlm tahun semasa
		$this->pengguna = Sesi::get('namaPegawai');
		$this->level = Sesi::get('levelPegawai');
		
    }
    
    public function index() 
	{
		$respon = 'semua'; $item = 30; $ms = 1; $fe = null; $cetak = null;
		echo "$respon | $item | $ms | $fe | $cetak <br>";
	}

    public function respon($respon = 'semua',$item = 30, $ms = 1, $fe = null, $cetak = null) 
    {
        /*
		 * $jenisRespon = semua/selesai/janji/belum/tegar
 		 * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
         * $fe = null // set $fe = pegawai kerja luar tiada
         */
		# set $jenisRespon
		switch ($respon) 
		{
			case "semua" 	: $kesRespon = 'kesSemua'; break;
			case "selesai" 	: $kesRespon = 'kesSelesai'; break;
			case "janji" 	: $kesRespon = 'kesJanji'; break;
			case "belum" 	: $kesRespon = 'kesBelum'; break;
			case "tegar" 	: $kesRespon = 'kesTegar'; break;
			case "utama" 	: $kesRespon = 'kesUtama'; break;
			default 		: $kesRespon = 'paparSemua'; 
		}
		//echo "$respon | $kesRespon | $item | $ms | \$fe=$fe | \$cetak=$cetak <br>";
		
		//$fe = ($this->level == 'kawal') ? $fe : $this->pengguna; # set nama fe
        $bulanan = bulanan('kawalan','14'); # papar bulan dlm tahun semasa
		$cari[] = array('fix'=>'x=','atau'=>'WHERE','medan'=>'fe','apa'=>$fe);
		//$cari[] = array('fix'=>'khas','atau'=>'AND','medan'=>'daerah','apa'=>'dp_baru');

        # semak pembolehubah $bulanan
        //echo '<pre>', print_r($bulanan, 1) . '</pre><br>';
        //echo '<pre>$cari=>', print_r($cari, 1) . '</pre><br>';
		
        # setkan pembolehubah untuk $this->tanya
            $medanRangka = $this->medanRangka;
			$medanData = $this->medanData;
            $sv = $this->sv;
		
        # mula papar semua dalam $myTable
        foreach ($bulanan as $key => $myTable)
        {# mula ulang table
			# setkan $medan
			$medan = ($myTable=='rangka14') ? $medanRangka : $medanData;
            # dapatkan bilangan jumlah rekod
            $bilSemua = $this->tanya->kiraKes($sv . $myTable, $medan, $cari);
            # tentukan bilangan mukasurat & bilangan jumlah rekod
			//echo '$bilSemua:'.$bilSemua.', $item:'.$item.', $ms:'.$ms.'<br>';
            $jum = pencamSqlLimit($bilSemua, $item, $ms);
			$kumpul = array('kumpul'=>null, 'susun'=>'utama,msic,nama');
			$susun[] = array_merge($jum, $kumpul);

            $this->papar->bilSemua[$myTable] = $bilSemua;
            # sql guna limit
            $this->papar->cariApa[$myTable] = $this->tanya->
				$kesRespon($sv . $myTable, $medan, $cari, $jum);
            # halaman
            $this->papar->halaman[$myTable] = halaman($jum);
        }# tamat ulang table
        
        # semak pembolehubah $this->papar->cariApa
        //echo '<pre>', print_r($this->papar->cariApa, 1) . '</pre><br>';
		
        # Set pemboleubah utama
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'semuajadual';
        
		//$cetak = 'papar';
		# pergi papar kandungan | memilih antara papar dan cetak
		if ($cetak == 'cetak') //echo 'cetak';
			$this->papar->baca('kawalan/cetak', 0);
		elseif ($cetak == 'papar') //echo 'papar';
			$this->papar->baca('kawalan/index', 1);
		else //echo 'ubah';
			$this->papar->baca('kawalan/index', 0);
		#*/
    }

    public function utama($item = 30, $ms = 1, $utama = null, $fe = null, $respon = null) 
    {    
        /*
         * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
         * $utama = null // set $utama = BBU/SBU tiada
		 * $respon = null // set $respon = a1/xa1/tegar tiada
         */
		//$fe = ($this->level == 'kawal') ? $fe : $this->pengguna; # set nama fe

        // setkan pembolehubah untuk $this->tanya
            $medanRangka = $this->medanRangka;
			$medanData = 'c.newss,c.msic,c.utama,c.nama,c.fe,'
				. 'concat_ws("","<input type=\"checkbox\">",'
				. 'concat("hasil=",(b.hasil + IFNULL(b.dptLain,0)) ),' // hasil
				. 'concat("|staf=",(b.staf) ),' // staf
				. 'concat("|gaji=",(b.gaji) )' // gaji
				. ' )as `5p`,terima,'			
				. ' format( (b.hasil + IFNULL(b.dptLain,0) ), 0 ) as dapat,'
				//. '(hasil+COALESCE(dptLain,0)) as dapat2,'
				. 'format(b.hasil,0) as hasil,format(b.dptlain,0) as dptlain,' . "\r"
				. 'format(b.stok,0) as stok,staf,format(b.gaji,0) as gaji,' . "\r"
				. 'outlet,sebab';
            $sv = $this->sv;
			$cari['utama'] = $utama;
			//$cari['respon'] = $respon;
			$cari['fe'] = $fe;
			
		// paparkan pembolehubah
			//echo ' item ' . $item . '<br>';
			//echo ' bil muka surat ' . $ms . '<br>';
			echo ' kes utama ' . $utama . '| ';
			echo ($respon==null)? '' : ' respon ' . $respon . '|';
			//echo ($fe==null)? '<br>' : ' fe ' . $fe . '<br>';
        // mula papar semua dalam $myTable
        foreach ($this->jadualKawalan as $key => $myTable)
        {// mula ulang table
			// setkan $medan
			$medan = ($myTable=='rangka14') ? $medanRangka : $medanData;
            // dapatkan bilangan jumlah rekod
            $bilSemua = $this->tanya->kiraKesUtama($sv.$myTable, $medan, $cari);
    		//echo '$bilSemua:'.$bilSemua.', $item:'.$item.', $ms:'.$ms.'<br>';
            $jum = pencamSqlLimit($bilSemua, $item, $ms, null);
            $this->papar->bilSemua[$myTable] = $bilSemua;
            // sql guna limit
            $this->papar->cariApa[$myTable] = $this->tanya->
            kesUtama($sv.$myTable, $medan, $cari, $jum);
            // halaman
            $this->papar->halaman[$myTable] = halaman($jum);
        }// tamat ulang table
        
        # semak pembolehubah $this->papar->cariApa
        //echo '<pre>', print_r($this->papar->cariApa, 1) . '</pre><br>';
        
        # Set pemboleubah utama
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'utama';
        $this->papar->url = dpt_url();
        # pergi papar kandungan
        $this->papar->baca('kawalan/index', 0);
    }
// cetak kes sendiri ikut alamat
    public function alamat($item = 30, $ms = 1, $cariBatch = null, $daerah = null) 
    {    
        /*
         * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
         * $cariBatch = null // set $cariBatch = tiada | atau untuk pegawai kerja luar
         */
        # setkan pembolehubah untuk $this->tanya sql1
            $jadualMedan = 'newss,nossm,concat_ws("<br>",nama,operator) as nama,'
			. 'msic2008 m6,kp,borang,daerah,dp_baru,/*bandar,*/'
			//. 'batchAwal,batchProses,respon R,msic2008,kp,borang,daerah,bandar,dp_baru,'
			. 'concat_ws(" ",alamat1,alamat2,poskod,bandar,negeri) as alamat' . "\r";
			$groupBy1 = null;
			$orderBy1 = 'dp_baru';
			$cari[] = array('fix'=>'x=','atau'=>'WHERE','medan'=>'batchAwal','apa'=>$cariBatch);
			//$cari[] = array('fix'=>'khas','atau'=>'AND','medan'=>'daerah','apa'=>'dp_baru');
			//$cari[] = array('fix'=>'like','atau'=>'AND','medan'=>'dp_baru','apa'=>$daerah);
			//$cari[] = array('fix'=>'xkhas3','atau'=>'AND','medan'=>'bandar','apa'=>'dp_baru');
		# mula papar semua dalam $myTable
        foreach ($this->jadualKawal as $key => $myTable)
        {# mula ulang table
			# setkan $medan = ($myTable=='') ? $medanRangka : $medanData;
			$medan = ($myTable=='cdt_pom_kawalan') ? $jadualMedan : $jadualMedan2;
            # dapatkan bilangan jumlah rekod
            $bilSemua = $this->tanya->kiraKes($myTable, $medan, $cari);
            # tentukan bilangan mukasurat & bilangan jumlah rekod
			 //echo "\$bilSemua:$bilSemua, \$item:$item, \$ms:$ms<br>';
            $jum = pencamSqlLimit($bilSemua, $item, $ms, $orderBy1, $groupBy1);
            $this->papar->bilSemua[$myTable] = $bilSemua;
            # sql guna limit
            $this->papar->cariApa[$myTable] = $this->tanya->
				cariAlamat($myTable, $medan, $cari, $jum);
			# halaman
            $this->papar->halaman[$myTable] = halaman($jum);
        }# tamat ulang table
		# setkan pembolehubah untuk $this->tanya sql2
			//$paparTable = "kawalan/alamat/$item/$ms/$cariBatch/$daerah";
			$paparTable = "$cariBatch-$daerah";
			$jadual = $this->jadualKawal[0];
			$jadualMedan2 = '`dp_baru`, count(*)as jum';
			$groupBy2 = 'dp_baru';
			$orderBy2 = 'dp_baru';
			$cari2[] = array('fix'=>'like','atau'=>'WHERE','medan'=>'batchAwal','apa'=>$cariBatch);
			//$cari2[] = array('fix'=>'like','atau'=>'AND','medan'=>'dp_baru','apa'=>$daerah);
			//$cari2[] = array('fix'=>'khas','atau'=>'AND','medan'=>'daerah','apa'=>'dp_baru');
        # mula cari jadual khas
			$bilSemua = $this->tanya->kiraKes($jadual, $jadualMedan2, $cari2);
            # tentukan bilangan mukasurat & bilangan jumlah rekod
			 //echo "\$bilSemua:$bilSemua, \$item:$item, \$ms:$ms<br>';
            $jum2 = pencamSqlLimit($bilSemua, $item, $ms, $orderBy2, $groupBy2);
            $this->papar->bilSemua[$paparTable] = $bilSemua;
            # sql guna limit
            $this->papar->cariApa[$paparTable] = $this->tanya->
				cariAlamat($jadual, $jadualMedan2, $cari2, $jum2);

        # semak pembolehubah $this->papar->cariApa
		//echo '<pre>$this->papar->cariApa:', print_r($this->papar->cariApa, 1) . '</pre><br>';
        
        # Set pemboleubah utama
		//$this->papar->cariApa[] = array(0=>array('data'=>'kosong'));
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'alamat';
        # pergi papar kandungan
        $this->papar->baca('kawalan/index', 0);
    }
	
    public function semak($item = 30, $ms = 1, $respon, $kp, $borang) 
    {    
        /*
         * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
			WHERE `respon` = 'A1' 
			AND kp = '327'
			AND borang = 'cdt 2'
			AND batchAwal not in ()

         */
        // setkan pembolehubah untuk $this->tanya
            $medan = 'newss,nossm,concat_ws("<br>",nama,operator) as nama,nota,batchAwal,msic2008,kp,borang,'
				   . 'concat_ws(" ",alamat1,alamat2,poskod,bandar,negeri) as alamat' . "\r";
			$carian[] = array('fix'=>'x=','atau'=>'WHERE','medan'=>'respon','apa'=>$respon);
			$carian[] = array('fix'=>'x=','atau'=>'AND','medan'=>'kp','apa'=>$kp);
			$carian[] = array('fix'=>'%like%','atau'=>'AND','medan'=>'borang','apa'=>$borang);
			$carian[] = array('fix'=>'xin','atau'=>'AND','medan'=>'batchAwal','apa'=>"('amin007','mdt-amin007')");

		# setkan pembolehubah untuk $this->tanya sql2
			//$paparTable = "kawalan/alamat/$item/$ms/$cariBatch/$daerah";
			$paparTable = "$cariBatch-$daerah";
			$jadual = $this->jadualKawal[0];
			$jadualMedan2 = '`dp_baru`, count(*)as jum';
			$groupBy2 = 'dp_baru';
			$orderBy2 = 'dp_baru';
			$cari2[] = array('fix'=>'like','atau'=>'WHERE','medan'=>'batchAwal','apa'=>$cariBatch);
			//$cari2[] = array('fix'=>'like','atau'=>'AND','medan'=>'dp_baru','apa'=>$daerah);
			//$cari2[] = array('fix'=>'khas','atau'=>'AND','medan'=>'daerah','apa'=>'dp_baru');
        # mula cari jadual khas
			$bilSemua = $this->tanya->kiraKes($jadual, $jadualMedan2, $cari2);
            # tentukan bilangan mukasurat & bilangan jumlah rekod
			 //echo "\$bilSemua:$bilSemua, \$item:$item, \$ms:$ms<br>';
            $jum2 = pencamSqlLimit($bilSemua, $item, $ms, $orderBy2, $groupBy2);
            $this->papar->bilSemua[$paparTable] = $bilSemua;
            # sql guna limit
            $this->papar->cariApa[$paparTable] = $this->tanya->
				cariAlamat($jadual, $jadualMedan2, $cari2, $jum2);
        
        # semak pembolehubah $this->papar->cariApa
		//echo '<pre>$this->papar->cariApa:', print_r($this->papar->cariApa, 1) . '</pre><br>';
        
        # Set pemboleubah utama
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'alamat';
        # pergi papar kandungan
        $this->papar->baca('kawalan/index', 0);
    }

    public function banding($item = 30, $ms = 1, $tahun = 14, $lepas = 'jan', $kini = 'feb') 
    {
		# setkan array untuk bulan
			$namaBln = bulanan('nama_bulan', $tahun);
        # setkan pembolehubah untuk $this->tanya
			$paparTable = "$lepas-$kini";
			$jadual = "mdt_$lepas$tahun dulu, mdt_$kini$tahun kini, mdt_rangka$tahun c";
            $medan = 'kini.newss,kini.nama,kini.sebab,c.respon, kini.utama,kini.msic,'
				   . 'format(kini.hasil,0) hasilKini,format(dulu.hasil,0) hasilDulu,'
				   . 'format( ((kini.hasil-dulu.hasil)/dulu.hasil) , 2) as kira'
				   . "\r";
			$carian[] = array('fix'=>'z1','atau'=>'WHERE','medan'=>'kini.newss','apa'=>'dulu.newss','akhir'=>null);
			$carian[] = array('fix'=>'z1','atau'=>'AND','medan'=>'kini.newss','apa'=>'c.newss','akhir'=>null);
			$carian[] = array('fix'=>'z2','atau'=>'AND','medan'=>'kini.fe','apa'=>"amin%",'akhir'=>null);
			$groupBy = null;
			$orderBy = 'utama,msic';

		# mula papar semua dalam $myTable      			
			$bilSemua = $this->tanya->kiraKes($jadual, $medan, $carian);
            # tentukan bilangan mukasurat & bilangan jumlah rekod
			//echo "\$bilSemua:$bilSemua, \$item:$item, \$ms:$ms<br>";
            $jum = pencamSqlLimit($bilSemua, $item, $ms);
			$kumpul = array('kumpul'=>$groupBy, 'susun'=>$orderBy);
			$susun[] = array_merge($jum, $kumpul);
			$this->papar->bilSemua[$paparTable] = $bilSemua;
        
		# sql guna limit
			for ($kira = 0; $kira < 9; $kira++) {  $this->papar->cariApa[$namaBln[$kira]] = null; }
            $this->papar->cariApa[$paparTable] = $this->tanya->
				cariSemuaData($jadual, $medan, $carian, $susun);

        # semak pembolehubah $this->papar->cariApa
		//echo '<pre>$this->papar->cariApa:', print_r($this->papar->cariApa, 1) . '</pre><br>';
		
        # Set pemboleubah utama
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'banding';
        $this->papar->halaman['jan14'] = null;
        # pergi papar kandungan
        $this->papar->baca('kawalan/index', 0);
    }	

}