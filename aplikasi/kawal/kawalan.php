<?php

class Kawalan extends Kawal 
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
    
    public function index($item = 30, $ms = 1, $fe = null) 
    {    
		$fe = ($this->level == 'kawal') ? $fe : $this->pengguna; # set nama fe
        $bulanan = bulanan('kawalan','14'); # papar bulan dlm tahun semasa     
        // setkan pembolehubah untuk $this->tanya
            $medanRangka = $this->medanRangka;
			$medanData = $this->medanData;
            $sv = $this->sv;
			
        // mula papar semua dalam $myTable
        foreach ($bulanan as $key => $myTable)
        {// mula ulang table
			// setkan $medan
			$medan = ($myTable=='rangka14') ? $medanRangka : $medanData;
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

		# semak pembolehubah $this->papar->cariApa
        //echo '<pre>', print_r($this->papar->cariApa, 1) . '</pre><br>';
        
        // papar
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'semuajadual';
        $this->papar->baca('kawalan/index', 0);
    }

    public function semua($item = 30, $ms = 1, $fe = null, $bulan = null, $cetak = null) 
    {    
        /*
         * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
         * $fe = null // set $fe = pegawai kerja luar tiada
         */
		$fe = ($this->level == 'kawal') ? $fe : $this->pengguna; # set nama fe
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
			$medan = ($myTable=='rangka14') ? $medanRangka : $medanData;
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
     	// memilih antara papar dan cetak
		if ($cetak == 'cetak') //echo 'cetak';
			$this->papar->baca('kawalan/cetak', 0);
		elseif ($cetak == 'papar') //echo 'papar';
			$this->papar->baca('kawalan/index', 1);
		else //echo 'ubah';
			$this->papar->baca('kawalan/index', 0);
		//*/
	}

    public function selesai($item = 30, $ms = 1, $fe = null, $cetak = null) 
    {    
        /*
         * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
         * $fe = null // set $fe = pegawai kerja luar tiada
         */
		$fe = ($this->level == 'kawal') ? $fe : $this->pengguna; # set nama fe
        $bulanan = bulanan('kawalan','14'); # papar bulan dlm tahun semasa
        # semak pembolehubah $bulanan
        //echo '<pre>', print_r($bulanan, 1) . '</pre><br>';

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
            $bilSemua = $this->tanya->kiraKes($sv, $myTable, $medan, $fe);
            # bilangan jumlah rekod
    		//echo '$bilSemua:'.$bilSemua.', $item:'.$item.', $ms:'.$ms.'<br>';
			# tentukan bilangan mukasurat 
            $jum = pencamSqlLimit($bilSemua, $item, $ms); 
            $this->papar->bilSemua[$myTable] = $bilSemua;
            # sql guna limit
            $this->papar->cariApa[$myTable] = $this->tanya->
            kesSelesai($sv, $myTable, $medan, $fe, $jum);
            # halaman
            $this->papar->halaman[$myTable] = halaman($jum);
        }# tamat ulang table
        
        # semak pembolehubah $this->papar->cariApa
        //echo '<pre>', print_r($this->papar->cariApa, 1) . '</pre><br>';
        
        # Set pemboleubah utama
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'selesai';
        $this->papar->url = dpt_url();
		
        // pergi papar kandungan
		// memilih antara papar dan cetak
		if ($cetak == 'cetak') //echo 'cetak';
			$this->papar->baca('kawalan/cetak', 0);
		elseif ($cetak == 'papar') //echo 'papar';
			$this->papar->baca('kawalan/index', 1);
		else //echo 'ubah';
			$this->papar->baca('kawalan/index', 0);
		//*/
    }
	
    public function janji($item = 30, $ms = 1, $fe = null) 
    {    
        /*
         * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
         * $fe = null // set $fe = pegawai kerja luar tiada
         */
		$fe = ($this->level == 'kawal') ? $fe : $this->pengguna; # set nama fe
        $bulanan = bulanan('kawalan','14'); # papar bulan dlm tahun semasa
        # semak pembolehubah $bulanan
        //echo '<pre>', print_r($bulanan, 1) . '</pre><br>';

        // setkan pembolehubah untuk $this->tanya
            $medanRangka = $this->medanRangka;
			$medanData = $this->medanData;
            $sv = $this->sv;

		// mula papar semua dalam $myTable
        foreach ($bulanan as $key => $myTable)
        {// mula ulang table
			// setkan $medan
			$medan = ($myTable=='rangka14') ? $medanRangka : $medanData;
            // dapatkan bilangan jumlah rekod
            $bilSemua = $this->tanya->kiraKes($sv, $myTable, $medan, $fe);
            // tentukan bilangan mukasurat 
            // bilangan jumlah rekod
    		//echo '$bilSemua:'.$bilSemua.', $item:'.$item.', $ms:'.$ms.'<br>';
            $jum = pencamSqlLimit($bilSemua, $item, $ms);
            $this->papar->bilSemua[$myTable] = $bilSemua;
            // sql guna limit
            $this->papar->cariApa[$myTable] = $this->tanya->
            kesJanji($sv, $myTable, $medan, $fe, $jum);
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
        $this->papar->baca('kawalan/index');
    }
	
    public function belum($item = 30, $ms = 1, $fe = null) 
    {    
        /*
         * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
         * $fe = null // set $fe = pegawai kerja luar tiada
         */
		$fe = ($this->level == 'kawal') ? $fe : $this->pengguna; # set nama fe
        $bulanan = bulanan('kawalan','14'); # papar bulan dlm tahun semasa
        # semak pembolehubah $bulanan
        //echo '<pre>', print_r($bulanan, 1) . '</pre><br>';

        // setkan pembolehubah untuk $this->tanya
            $medanRangka = $this->medanRangka;
			$medanData = $this->medanData;
            $sv = $this->sv;
			
        // mula papar semua dalam $myTable
        foreach ($bulanan as $key => $myTable)
        {// mula ulang table
			// setkan $medan
			$medan = ($myTable=='rangka14') ? $medanRangka : $medanData;
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
        $this->papar->baca('kawalan/index');
    }

    public function tegar($item = 30, $ms = 1, $fe = null) 
    {    
        /*
         * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
         * $fe = null // set $fe = pegawai kerja luar tiada
         */
		$fe = ($this->level == 'kawal') ? $fe : $this->pengguna; # set nama fe
        $bulanan = bulanan('kawalan','14'); # papar bulan dlm tahun semasa
        # semak pembolehubah $bulanan
        //echo '<pre>', print_r($bulanan, 1) . '</pre><br>';

        // setkan pembolehubah untuk $this->tanya
            $medanRangka = $this->medanRangka;
			$medanData = $this->medanData;
            $sv = $this->sv;
			
        // mula papar semua dalam $myTable
        foreach ($bulanan as $key => $myTable)
        {// mula ulang table
			// setkan $medan
			$medan = ($myTable=='rangka14') ? $medanRangka : $medanData;
            // dapatkan bilangan jumlah rekod
            $bilSemua = $this->tanya->kiraKes($sv, $myTable, $medan, $fe);
            // tentukan bilangan mukasurat 
            // bilangan jumlah rekod
    		//echo '$bilSemua:'.$bilSemua.', $item:'.$item.', $ms:'.$ms.'<br>';
            $jum = pencamSqlLimit($bilSemua, $item, $ms);
            $this->papar->bilSemua[$myTable] = $bilSemua;
            // sql guna limit
            $this->papar->cariApa[$myTable] = $this->tanya->
            kesTegar($sv, $myTable, $medan, $fe, $jum);
            // halaman
            $this->papar->halaman[$myTable] = halaman($jum);
        }// tamat ulang table
        
        # semak pembolehubah $this->papar->cariApa
        //echo '<pre>', print_r($this->papar->cariApa, 1) . '</pre><br>';
        
        // Set pemboleubah utama
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'tegar';
        $this->papar->url = dpt_url();
        // pergi papar kandungan
        $this->papar->baca('kawalan/index');
    }

    public function utama($item = 30, $ms = 1, $utama = null, $respon = null, $fe = null) 
    {    
        /*
         * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
         * $utama = null // set $utama = BBU/SBU tiada
		 * $respon = null // set $respon = a1/xa1/tegar tiada
         */
		$fe = ($this->level == 'kawal') ? $fe : $this->pengguna; # set nama fe
        $bulanan = bulanan('data_bulanan','13'); # papar bulan dlm tahun semasa
        # semak pembolehubah $bulanan
        //echo '<pre>', print_r($bulanan, 1) . '</pre><br>';

        // setkan pembolehubah untuk $this->tanya
            $medanRangka = $this->medanRangka;
			$medanData = 'b.newss,b.msic,b.nama,b.utama,b.fe,terima,'
			//. 'hasil,dptLain,web,stok,staf,gaji,outlet,sebab';
			. 'format(hasil,0) as hasil,format(dptlain,0) as dptlain,' . "\r"
			. 'format(stok,0) as stok,staf,format(gaji,0) as gaji,' . "\r"
			. 'outlet,sebab';
            $sv = $this->sv;
			$cari['utama'] = $utama;
			$cari['respon'] = $respon;
			$cari['fe'] = $fe;
			
		// paparkan pembolehubah
			//echo ' item ' . $item . '<br>';
			//echo ' bil muka surat ' . $ms . '<br>';
			echo ' kes utama ' . $utama . '| ';
			echo ($respon==null)? '<br>' : ' respon ' . $respon . '<br>';
        // mula papar semua dalam $myTable
        foreach ($bulanan as $key => $myTable)
        {// mula ulang table
			// setkan $medan
			$medan = ($myTable=='rangka14') ? $medanRangka : $medanData;
            // dapatkan bilangan jumlah rekod
            $bilSemua = $this->tanya->kiraKesUtama($sv.$myTable, $medan, $cari);
            // tentukan bilangan mukasurat 
            // bilangan jumlah rekod
    		//echo '$bilSemua:'.$bilSemua.', $item:'.$item.', $ms:'.$ms.'<br>';
            $jum = pencamSqlLimit($bilSemua, $item, $ms);
            $this->papar->bilSemua[$myTable] = $bilSemua;
            // sql guna limit
            $this->papar->cariApa[$myTable] = $this->tanya->
            kesUtama($sv.$myTable, $medan, $cari, $jum);
            // halaman
            $this->papar->halaman[$myTable] = halaman($jum);
        }// tamat ulang table
        
        # semak pembolehubah $this->papar->cariApa
        //echo '<pre>', print_r($this->papar->cariApa, 1) . '</pre><br>';
        
        // Set pemboleubah utama
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'utama';
        $this->papar->url = dpt_url();
        // pergi papar kandungan
        $this->papar->baca('kawalan/index', 0);
    }
    
    function semak($cariID) 
    {//echo '<br>Anda berada di class Imej extends Kawal:ubah($cari)<br>';
                
        // senaraikan tatasusunan jadual dan setkan pembolehubah
		$id = isset($cariID) ? $cariID : null; // cari id berasaskan sidap
        $bulanan = bulanan('nama_bulan',null); # papar bulan dlm tahun semasa
		/* cantum data tahun 2011-2013 */
		// tahun 2011
		foreach ($bulanan as $kunci => $bln)
		{// mula ulang table
			$bulan='pom_bln11.mdt_' . $bln . '11';
			
			$medan='concat(substring(newss,1,3),\' \',substring(newss,4,3),\' \','.
			'substring(newss,7,3),\' \',substring(newss,10,3), status)'.
			' as sidap,' . "\r" . 'nama,msic08,terima,hasil,dptLain,web,' .
			'stok,staf,gaji,sebab,outlet,\'' . $bln . '11\'' . "\r";

			$sql[]='SELECT ' . $medan . 'FROM ' . $bulan . ' WHERE newss="' . $id . '" ';
		}// tamat ulang table
		$query = implode("\rUNION\r",$sql);
		//echo '<pre>' . $query . '</pre>';
		$paparCantum['2011'] = $this->tanya->cantumsql($query);
		unset($sql, $query);
		// tahun 2012
		foreach ($bulanan as $kunci => $bln)
		{// mula ulang table
			$bulan='pom_bln12.mdt_' . $bln . '12';
			
			$medan='concat(substring(newss,1,3),\' \',substring(newss,4,3),\' \','.
			'substring(newss,7,3),\' \',substring(newss,10,3), status)'.
			' as sidap,' . "\r" . 'nama,msic08,terima,hasil,dptLain,web,' .
			'stok,staf,gaji,sebab,outlet,\'' . $bln . '12\'' . "\r";

			$sql[]='SELECT ' . $medan . 'FROM ' . $bulan . ' WHERE newss="' . $id . '" ';
		}// tamat ulang table
		
		$query = implode("\rUNION\r",$sql);
		//echo '<pre>' . $query . '</pre>';
		$paparCantum['2012'] = $this->tanya->cantumsql($query);
		unset($sql, $query);
		// tahun 2013
		foreach ($bulanan as $kunci => $bln)
		{// mula ulang table
			$bulan='pom_bln13.mdt_' . $bln . '13';
			
			$medan='concat(substring(newss,1,3),\' \',substring(newss,4,3),\' \','.
			'substring(newss,7,3),\' \',substring(newss,10,3), utama)'.
			' as sidap,' . "\r" . 'nama,msic,terima,hasil,dptLain,web,' .
			'stok,staf,gaji,sebab,outlet,\'' . $bln . '13\'' . "\r";

			$sql[]='SELECT ' . $medan . 'FROM ' . $bulan . ' WHERE newss="' . $id . '" ';
		}// tamat ulang table
		
		$query = implode("\rUNION\r",$sql);
		//echo '<pre>' . $query . '</pre>';
		$paparCantum['2013'] = $this->tanya->cantumsql($query);

		//echo '<pre>';  print_r($paparCantum) . '</pre>';

		// set pembolehubah
        $jadualRangka = 'rangka14';
        $medanRangka ='newss,nama,ssm,utama,' .
			'concat_ws("<br>",alamat1,alamat2,poskod,ngdbbp) as alamat,' . "\r" .
			'nota,respon,fe,tel,fax,responden,email,msic,msic08,' .
            'concat(substring(newss,1,3),\' \',substring(newss,4,3),\' \',' .
            'substring(newss,7,3),\' \',substring(newss,10,3),\' | \',' .
            'utama,\' \',msic) as ' . '`id U M`';
        $medanData = 'newss,fe,nama,utama,msic,terima,hasil,dptLain,web,stok,staf,gaji,sebab,outlet';
        $sv = 'mdt_'; // survey apa
        $cari['medan'] = 'newss'; // cari dalam medan apa
        $cari['id'] = $id; // benda yang dicari
        $this->papar->kesID = array();

		
        if (!empty($id)) 
        {
            //echo '$id:' . $id . '<br>';
            $this->papar->carian='newss';	
            // 2. cari $cariID dalam $bulanan
            foreach ($bulanan as $key => $myTable)
            {// mula ulang table
				
				//echo '<br>$key:' . $key;
				for($kira=2011; $kira < 2014; $kira++)
				{
					//echo '<br>hasil ' . $kira . ' bulan ' . $myTable . '=' 
					//	. $paparCantum[$kira][$key]['hasil'];
					$hasil[$kira][$key] = isset($paparCantum[$kira][$key]['hasil']) ?
						$paparCantum[$kira][$key]['hasil'] : 0;
					$dptLain[$kira][$key] = isset($paparCantum[$kira][$key]['dptLain']) ?
						$paparCantum[$kira][$key]['dptLain'] : 0;
					// masukkan $data untuk dapatkan perbezaan data bulanan
					$data['dpt'][] = $hasil[$kira][$key] + $dptLain[$kira][$key];
					$data['hasil'][] = isset($paparCantum[$kira][$key]['hasil']) ?
						$paparCantum[$kira][$key]['hasil'] : 0;
					$data['dptLain'][] = isset($paparCantum[$kira][$key]['dptLain']) ?
						$paparCantum[$kira][$key]['dptLain'] : 0;
					$data['web'][] = isset($paparCantum[$kira][$key]['web']) ?
						$paparCantum[$kira][$key]['web'] : 0;
					$data['stok'][] = isset($paparCantum[$kira][$key]['stok']) ?
						$paparCantum[$kira][$key]['stok'] : 0;
					$data['staf'][] = isset($paparCantum[$kira][$key]['staf']) ?
						$paparCantum[$kira][$key]['staf'] : 0;
					$data['gaji'][] = isset($paparCantum[$kira][$key]['gaji']) ?
						$paparCantum[$kira][$key]['gaji'] : 0;
				}
            }// tamat ulang table
			
			echo '<pre>$data[dpt]::'; print_r($data['dpt']) . '<pre>';
			
			// 3. cari beza antara 2 bulan
			foreach ($data as $medan => $key)
			{// mula ulang data
				foreach ($key as $bln => $data)
				{// mula ulang table
					//echo '$medan:'.$medan.'|$bln:'.$bln.'|$data:'.$data.'<br>';
					// beza 
					$dulu = ($bln==0) ? 0 : $key[$bln-1]; 
					$kini = $key[$bln];
					
					echo '<br>$medan:'.$medan.'|$dulu:'.$dulu.'|$kini:'.$kini.'|' .
					kira2($dulu,$kini);

				}// tamat ulang table
			}// tamat ulang data
			
        }
		else
        {
            $this->papar->carian='[tiada id diisi]';
        }
        
        // isytihar pemboleubah
        //$tajuk2=array('bulan','nama','msic','terima','hasil','dptLain',
        //'web','stok','staf','gaji','sebab','outlet','nota');
        $tajuk2=array('bulan','nama','msic','terima','hasil','dptLain','web','stok','staf','gaji','sebab','outlet');
        $this->papar->paparTajuk = null;
        $s1 = '<span class="label">';
        $s2 = '</span>';
        foreach ($tajuk2 as $tajuk)
        {
            $this->papar->paparTajuk .= "\n" . '<th>' . $s1 . $tajuk . $s2 . '</th>';
        }

        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->lokasi = 'MDT 2012 - Ubah';
		$this->papar->cari = $id;
		
        // semak data
		/*
		echo '<pre>';
		//echo '$kesID:<br>'; print_r($kesID); 
        //echo '$this->papar->kesID:<br>'; print_r($this->papar->kesID); 
		//echo '$beza::'; print_r($beza); 
		//echo '$this->papar->rangka:<br>'; print_r($this->papar->rangka); 
		//echo '$this->papar->cari:<br>'; print_r($this->papar->cari); 
		echo '</pre>';
		*/
		
        // paparkan ke fail kawalan/ubah.php
		//$this->papar->baca('kawalan/ubah', 1);

    }    

	public function semakbatch($item = 30, $ms = 1, $fe = null) 
    {    
        /*
         * $item = 30 // set bil. data dalam 1 muka surat
         * $ms = 1 // set $ms = mula surat bermula dengan 1
         * $fe = null // set $fe = pegawai kerja luar tiada
         */

        // setkan pembolehubah untuk $this->tanya
            $myTable = 'dtsample_takatfeb13';
			$medan = 'newss,msic,sv,ssm,nama,operator, '
				   . 'concat_ws("<br>",alamat1,alamat2,poskod) as alamat,ngdbbp,utama,thn,data12,'
				   . 'Penyiasatan,Indeks,`Respon 2012`,Bil,Kawalan';
				   //`STATE CODE`		F1005 Baru)	STATE_NAME_MS	EB ID PO_ORGUNIT_NAME	DISTRICT_NAME_MS';
			$sv = null;
	
        // mula papar semua dalam $myTable
            // dapatkan bilangan jumlah rekod
            $bilSemua = $this->tanya->kiraProsesan($myTable);
            // tentukan bilangan mukasurat 
            // bilangan jumlah rekod
    		//echo '$bilSemua:'.$bilSemua.', $item:'.$item.', $ms:'.$ms.'<br>';
            $jum = pencamSqlLimit($bilSemua, $item, $ms);
            $this->papar->bilSemua[$myTable] = $bilSemua;
            // sql guna limit
            $this->papar->cariApa[$myTable] = $this->tanya->
			semakRangkaProsesan($myTable, $medan, $cari = null, $jum);
            // halaman
            $this->papar->halaman[$myTable] = halaman($jum);
			
        
        # semak pembolehubah $this->papar->cariApa
        //echo '<pre>', print_r($this->papar->cariApa, 1) . '</pre><br>';
        
        // Set pemboleubah utama
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->carian = 'semakbatch';
        $this->papar->url = dpt_url();
        // pergi papar kandungan
        $this->papar->baca('kawalan/semak', 0);
    }

    function cari() 
    {
        //echo '<br>Anda berada di class Imej extends Kawal:cari()<br>';
        //echo '<pre>'; print_r($_POST) . '</pre>';
        /*     $_POST[id] => Array ( [ssm] => 188561 atau [nama] => sharp manu ) */
        
        // senaraikan tatasusunan jadual
        $myJadual = tahunan('semuakawalan', null);
        $this->papar->cariNama = array();

        // cari id berasaskan newss/ssm/sidap/nama
        $id['ssm'] = isset($_POST['id']['ssm']) ? $_POST['id']['ssm'] : null;
        $id['nama'] = isset($_POST['id']['nama']) ? $_POST['id']['nama'] : null;
            
        
        if (!empty($id['ssm'])) 
        {
            //echo "POST[id][ssm]:" . $_POST['id']['ssm'];
            $cariMedan = 'sidap'; // cari dalam medan apa
            $cariID = $id['ssm']; // benda yang dicari
            $this->papar->carian='ssm';
            
            // mula cari $cariID dalam $myJadual
            foreach ($myJadual as $key => $myTable)
            {// mula ulang table
                // senarai nama medan
                $medan = ($myTable=='sse10_kawal') ? 
                    'sidap,newss,nama' : 'sidap,nama'; 
                $this->papar->cariNama[$myTable] = 
                $this->tanya->cariMedan($myTable, $medan, $cariMedan, $cariID);
            }// tamat ulang table
        }
        elseif (!empty($id['nama']))
        {
            //echo "POST[id][nama]:" . $_POST['id']['nama'];
            $cariMedan = 'nama'; // cari dalam medan apa
            $cariID = $id['nama'];
            $this->papar->carian='nama';
            
            // mula cari $cariID dalam $myJadual
            foreach ($myJadual as $key => $myTable)
            {// mula ulang table
                // senarai nama medan
                $medan = ($myTable=='sse10_kawal') ? 
                    'sidap,newss,nama' : 'sidap,nama'; 
                $this->papar->cariNama[$myTable] = 
                $this->tanya->cariMedan($myTable, $medan, $cariMedan, $cariID);
            }// tamat ulang table

        }
        else
        {
            $this->papar->carian='[id:0]';
        }
        
        // paparkan ke fail cimej/cari.php
        $this->papar->baca('cimej/cari');
        
    }
    
    function ubah($cariID) 
    {//echo '<br>Anda berada di class Imej extends Kawal:ubah($cari)<br>';
                
        // senaraikan tatasusunan jadual dan setkan pembolehubah
        $bulanan = bulanan('data_bulanan','14'); # papar bulan dlm tahun semasa
        $jadualRangka = 'rangka14';
        $medanRangka ='newss,nama,ssm,utama,' .
			'concat_ws("<br>",alamat1,alamat2,poskod,ngdbbp) as alamat,' . "\r" .
			'nota,respon,fe,tel,fax,responden,email,msic,msic08,' .
            'concat(substring(newss,1,3),\' \',substring(newss,4,3),\' \',' .
            'substring(newss,7,3),\' \',substring(newss,10,3),\' | \',' .
            'utama,\' \',msic) as ' . '`id U M`';
        $medanData = 'newss,fe,nama,utama,msic,terima,hasil,dptLain,web,stok,staf,gaji,sebab,outlet';
        $sv = 'mdt_'; // survey apa
        $cari['medan'] = 'newss'; // cari dalam medan apa
        $id = isset($cariID) ? $cariID : null; // cari id berasaskan sidap
        $cari['id'] = $id; // benda yang dicari
        $this->papar->kesID = array();

        if (!empty($id)) 
        {
            //echo '$id:' . $id . '<br>';
            $this->papar->carian='newss';

            // 1. mula semak dalam rangka 
            $this->papar->rangka['kes'] = 
                $this->tanya->cariSemuaMedan($sv . $jadualRangka, 
                $medanRangka, $cari);
			
			// 1.1 ambil nilai msic & msic08
			$msic00 = $this->papar->rangka['kes'][0]['msic'];
			//$msic08 = $this->papar->rangka['kes'][0]['msic08'];
			$cariM6['medan'] = 'msic';
			$cariM6['id'] = $msic00;
			
			// 1.2 cari nilai msic & msic08 dalam jadual msic2008
			$jadualMSIC = dpt_senarai('msiclama');
			// mula cari $cariID dalam $jadual
			foreach ($jadualMSIC as $m6 => $msic)
			{// mula ulang table
				// senarai nama medan
				$medanM6 = ($msic=='msic2008') ? 
					'seksyen S,bahagian B,kumpulan Kpl,kelas Kls,' .
					'msic2000,msic,keterangan,notakaki' 
					: '*'; 
				//echo "cariMSIC($msic, $medanM6, $cariM6)<br>";
				$this->papar->cariIndustri[$msic] = $this->tanya->
				cariIndustri($msic, $medanM6, $cariM6);
			}// tamat ulang table
		
            // 2. cari $cariID dalam $bulanan
            foreach ($bulanan as $key => $myTable)
            {// mula ulang table
                $this->papar->kesID[$myTable] = 
                    $this->tanya->cariSemuaMedan($sv . $myTable, 
                    $medanData, $cari);
				$hasil[$key] = isset($this->papar->kesID[$myTable][0]['hasil']) ?
					$this->papar->kesID[$myTable][0]['hasil'] : 0;
				$dptLain[$key] = isset($this->papar->kesID[$myTable][0]['dptLain']) ?
					$this->papar->kesID[$myTable][0]['dptLain'] : 0;
				// masukkan $data untuk dapatkan perbezaan data bulanan
				$data['dpt'][$key] = $hasil[$key] + $dptLain[$key];
				$data['hasil'][$key] = isset($this->papar->kesID[$myTable][0]['hasil']) ?
					$this->papar->kesID[$myTable][0]['hasil'] : 0;
				$data['dptLain'][$key] = isset($this->papar->kesID[$myTable][0]['dptLain']) ?
					$this->papar->kesID[$myTable][0]['dptLain'] : 0;
				$data['web'][$key] = isset($this->papar->kesID[$myTable][0]['web']) ?
					$this->papar->kesID[$myTable][0]['web'] : 0;
				$data['stok'][$key] = isset($this->papar->kesID[$myTable][0]['stok']) ?
					$this->papar->kesID[$myTable][0]['stok'] : 0;
				$data['staf'][$key] = isset($this->papar->kesID[$myTable][0]['staf']) ?
					$this->papar->kesID[$myTable][0]['staf'] : 0;
				$data['gaji'][$key] = isset($this->papar->kesID[$myTable][0]['gaji']) ?
					$this->papar->kesID[$myTable][0]['gaji'] : 0;
            }// tamat ulang table
			
			//echo '<pre>$data::'; print_r($data) . '<pre>';
			
			// 3. cari beza antara 2 bulan
			foreach ($data as $medan => $kunci)
			{// mula ulang data
				foreach ($kunci as $key => $value)
				{// mula ulang table
					//echo '$medan:'.$medan.'|$key:'.$key.'|$value:'.$value.'<br>';
					$myTable = $bulanan[$key];
					// beza 
					$dulu = ($key==0) ? 0 : $kunci[$key-1]; 
					$kini = $kunci[$key];
					//echo '$medan:'.$medan.'|$dulu:'.$dulu.'|$kini:'.$kini.'<br>';
					$this->papar->beza[$myTable][0][$medan] = ($key==0) ? null : 
						//kira($kini)	. '|' . 
						kira2($dulu,$kini);
				}// tamat ulang table
			}// tamat ulang data
			
			
        }
		else
        {
            $this->papar->carian='[tiada id diisi]';
        }
        
        // isytihar pemboleubah
        //$tajuk2=array('bulan','nama','msic','terima','hasil','dptLain',
        //'web','stok','staf','gaji','sebab','outlet','nota');
        $tajuk2=array('bulan','nama','msic','terima','hasil','dptLain','web','stok','staf','gaji','sebab','outlet');
        $this->papar->paparTajuk = null;
        $s1 = '<span class="label">';
        $s2 = '</span>';
        foreach ($tajuk2 as $tajuk)
        {
            $this->papar->paparTajuk .= "\n" . '<th>' . $s1 . $tajuk . $s2 . '</th>';
        }

        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->lokasi = 'MDT 2012 - Ubah';
		$this->papar->cari = $id;
		
        // semak data
		/*
		echo '<pre>';
		//echo '$kesID:<br>'; print_r($kesID); 
        //echo '$this->papar->kesID:<br>'; print_r($this->papar->kesID); 
		//echo '$beza::'; print_r($beza); 
		//echo '$this->papar->rangka:<br>'; print_r($this->papar->rangka); 
		echo '$this->papar->cariIndustri:<br>'; print_r($this->papar->cariIndustri); 
		//echo '$this->papar->cari:<br>'; print_r($this->papar->cari); 
		echo '</pre>';
		//*/
		
        // paparkan ke fail kawalan/ubah.php
		$this->papar->baca('kawalan/ubah', 0);

    }
    
	public function ubahCari()
	{
		// echo '<pre>$_POST->', print_r($_POST, 1) . '</pre>';
		// bersihkan data $_POST
		$dataID = bersih($_POST['cari']);
		
		// Set pemboleubah utama
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->lokasi = 'MDT 2012 - Ubah';
		
		// paparkan ke fail kawalan/ubah.php
		header('location: ' . URL . 'kawalan/ubah/' . $dataID);

	}

    public function ubahSimpan($dataID)
    {
        $bulanan = bulanan('kawalan','14'); # papar bulan dlm tahun semasa
        $posmen = array();
        $id = 'newss';
		$sv = 'mdt_'; // sv = kod penyiasatan
    
        foreach ($_POST as $key => $value)
        {
            if ( in_array($key,$bulanan) )
            {
                $myTable = $sv . $key;
                foreach ($value as $kekunci => $papar)
                {
                    if ( in_array($kekunci,array('terimax','hantarx')) )
					{
						$posmen[$myTable]['terima'] = null;
						//$posmen[$myTable]['hantar'] = null;
					}
					elseif ( in_array($kekunci,array('fe','email')) )
						$posmen[$myTable][$kekunci]=strtolower(bersih($papar)); // huruf kecil
					elseif ( in_array($kekunci,array('respon')) )
						$posmen[$myTable][$kekunci]=strtoupper(bersih($papar)); // HURUF BESAR
					elseif ( in_array($kekunci,array('responden')) )
						$posmen[$myTable][$kekunci] = // Huruf Besar Pada Depan Sahaja
							mb_convert_case(bersih($papar), MB_CASE_TITLE); 
					else
						$posmen[$myTable][$kekunci] = bersih($papar);
                }
                $posmen[$myTable][$id] = $dataID;
            }
        }
        
			# buat peristiharan
			$rangka = 'mdt_rangka14'; // rangka kawalan kes
			//echo '<br>$dataID=' . $dataID . '<br>';
			//echo '<pre>$_POST='; print_r($_POST) . '</pre>';
			echo '<pre>$posmen='; print_r($posmen) . '</pre>';
        
        // mula ulang $bulanan
        
        foreach ($bulanan as $kunci => $jadual)
        {// mula ulang table
            $myTable = $sv . $jadual;
			$posmen[$myTable]['fe'] = $posmen[$rangka]['fe'];
            $data = $posmen[$myTable];
            $this->tanya->ubahSimpan($data, $myTable);
        }// tamat ulang table
        
        //$this->papar->baca('kawalan/ubah/' . $dataID);
        header('location: ' . URL . 'kawalan/ubah/' . $dataID);
        
    }

	public function tambah($cariID = null) 
	{				
        // senaraikan tatasusunan jadual dan setkan pembolehubah
        $bulanan = bulanan('data_bulanan','13'); # papar bulan dlm tahun semasa
        $jadualRangka = 'rangka14';
        $medanRangka ='newss,nama,ssm,utama,' .
			'nota,respon,fe,tel,fax,responden,email,msic,msic08,' .
            'concat(substring(newss,1,3),\' \',substring(newss,4,3),\' \',' .
            'substring(newss,7,3),\' \',substring(newss,10,3),\' | \',' .
            'utama,\' \',msic) as ' . '`id U M`';
        $medanData = 'newss,fe,nama,utama,msic,terima,hasil,dptLain,web,stok,staf,gaji,sebab,outlet';
        $sv = 'mdt_'; // survey apa
        $cari['medan'] = 'newss'; // cari dalam medan apa
        $id = isset($cariID) ? $cariID : null; // cari id berasaskan sidap
        $cari['id'] = $id; // benda yang dicari

		if (!empty($id)) 
        {
			// set dalam KAWAL sahaja
			$this->papar->carian = $id;
			$dtsample = $sv . $jadualRangka;
			// cari data dalam dtsample
			$jum = pencamSqlLimit($bilSemua = 22, $item = 100, $ms = 1);
			$dataRangka[$dtsample] = $this->tanya->semakRangkaProsesan(
				$myTable = 'dtsample_takatfeb13', $medan = '*', $cari, $jum);
			//echo '<pre>$dataRangka:'; print_r($dataRangka) . '</pre>';
			//semakRangkaProsesan($myTable, $medan, $cari = null, $jum);		
			$dataRangka[0] = $dataRangka[$dtsample][0]['newss'];
			$dataRangka[1] = $dataRangka[$dtsample][0]['msic'];
            $dataRangka[2] = $dataRangka[$dtsample][0]['sv'];
            $dataRangka[3] = $dataRangka[$dtsample][0]['ssm'];
            $dataRangka[4] = $dataRangka[$dtsample][0]['nama'];
            $dataRangka[5] = $dataRangka[$dtsample][0]['operator'];
            $dataRangka[6] = $dataRangka[$dtsample][0]['alamat1'];
            $dataRangka[7] = $dataRangka[$dtsample][0]['alamat2'];
            $dataRangka[8] = $dataRangka[$dtsample][0]['poskod'];
            $dataRangka[9] = $dataRangka[$dtsample][0]['ngdbbp'];
            $dataRangka[10] = ($dataRangka[$dtsample][0]['utama']=='KES BUKAN UTAMA') ? 'SBU' : 'BBU'; //KES BUKAN UTAMA
            $dataRangka[11] = $dataRangka[$dtsample][0]['thn'];
			$dataRangka[12] = null; 
            $dataRangka[13] = $dataRangka[$dtsample][0]['data12'];
			for ($kira=14; $kira < 23; $kira++)
			{
				$dataRangka[$kira] = ($kira==21) ?  
					'Respon MDT2012=' . $dataRangka[$dtsample][0]['Respon 2012'] 
					: null;
			}
						
			//$this->papar->dataRangka[$dtsample] = $this->tanya->semakRangkaProsesan($mytable = 'dtsample_takatfeb13', $medan = '*', $cari);
			// papar rangka mdt 2013 sahaja
			$paparRangka[$dtsample] = $this->tanya->paparMedan($dtsample, $papar = null);
			$this->papar->paparRangka[$dtsample][] = Borang::tambahRangkaBaru($paparRangka, $dataRangka);
				//newss 	msic 	nama 	utama 
				$databln[0] = $dataRangka[$dtsample][0]['newss'];
				$databln[1] = $dataRangka[$dtsample][0]['msic'];
				$databln[2] = $dataRangka[$dtsample][0]['nama'];
				$databln[3] = ($dataRangka[$dtsample][0]['utama']=='KES BUKAN UTAMA') ? 'SBU' : 'BBU'; //KES BUKAN UTAMA
				for ($kira=4; $kira < 15; $kira++)
				{
					$databln[$kira] = null;
				}

			// papar bulan jan - dis
			foreach ($bulanan as $key => $myTable)
			{// mula ulang table
				$papar = 'Field in ("newss","msic","nama","utama")';
				$paparMedan[$sv . $myTable] = $this->tanya->paparMedan($sv . $myTable, $papar);
				//$data[$sv . $myTable] = Borang::tambahBaru($paparMedan);
				$this->papar->paparMedan[$sv . $myTable][] = Borang::tambahBaru($paparMedan, $databln);
			}// tamat ulang table
			
			$tajuk2=array('bulan','newss','msic','nama','utama'); 
			//,'fe','respon','terima','hasil','dptLain','web','stok','staf','gaji','outlet','sebab');
			$this->papar->paparTajuk = null;
			$s1 = '<span class="label">';
			$s2 = '</span>';
			foreach ($tajuk2 as $tajuk)
			{
				$this->papar->paparTajuk .= "\n" . '<th>' . $s1 . $tajuk . $s2 . '</th>';
			}
		}
        else
        {
            $this->papar->carian='[tiada id diisi]';
        }
		/*
		echo '<pre>';
		//echo 'paparMedan:'; print_r($paparMedan) . '';
		echo '$$this->papar->paparRangka:'; print_r($this->papar->paparRangka) . '';
		echo '</pre>';
		//*/

		// Set pemboleubah utama
        $this->papar->pegawai = senarai_kakitangan();
        $this->papar->lokasi = 'MDT 2013:Tambah';
		// paparkan ke fail kawalan/tambah.php
		$this->papar->baca($this->_folder . '/tambah', 0);
	}
	
	public function tambahSimpan($dataID) 
	{	
        $bulanan = bulanan('tambahkes','13'); # papar bulan dlm tahun semasa
        $posmen = array();
        $id = 'newss';
    
        foreach ($_POST as $key => $value)
        {
			//echo '$key:' . $key . '<br>';
            if ( in_array($key,$bulanan) )
            {
				$myTable = $key;
				foreach ($value as $kekunci => $papar)
                {
                    $posmen[$myTable][$kekunci] = bersih($papar);
                }
            }
        }
        			
        //echo '<br>$dataID=' . $dataID . '<br>';
        //echo '<pre>$_POST='; print_r($_POST) . '</pre>';
        //echo '<pre>$posmen='; print_r($posmen) . '</pre>';
        
        // mula ulang $bulanan
		$this->tanya->ubahSimpan(
			$data = array('kawalan'=>'sudah', 'newss'=>$dataID), 
			$myTable = 'dtsample_takatfeb13');
        foreach ($bulanan as $kunci => $jadual)
        {// mula ulang table
            $myTable = $jadual;
            $data = $posmen[$myTable];
            $this->tanya->tambahSimpan($data, $myTable);
        }// tamat ulang table
		
		
		// pergi papar kandungan
		//echo 'location: ' . URL . 'kawalan/ubah/' . $dataID;
		header('location: ' . URL . 'kawalan/ubah/' . $dataID);

	}

	public function cetak($cariID, $cetak = null)
	{
		$this->papar->cariNama = null;
			
		if (empty($cariID)) 
		{
			header('location:' . URL . '');
			exit;	
		}
		elseif (!empty($cariID)) 
		{
			$jadual = dpt_senarai('syarikat');
			// mula cari $cariID dalam $jadual
			$this->papar->cariApa = $this->tanya
				//->cariCantumJadual($jadual, $medan = '*', $kira);
				->cariKawalan($jadual, $cari = 'newss', $cariID);

			//echo '<pre>$cariNama::'; print_r($this->papar->cariApa) . '</pre>';
			
			$this->papar->carian=$cariID;
		}
		else
		{
			$this->papar->carian[]='[id:0]';
		}
	
		
        // pergi papar kandungan
		$this->papar->pegawai = senarai_kakitangan();
		$this->papar->halaman = null;
     	// memilih antara papar dan cetak
		if ($cetak == 'cetak') //echo 'cetak';
			$this->papar->baca('kawalan/cetak', 0);
		elseif ($cetak == 'papar') //echo 'papar';
			$this->papar->baca('kawalan/index', 1);
		else //echo 'ubah';
			$this->papar->baca('kawalan/index', 0);
		//*/
	
	}
}
