<?php

function dpt_url()
{
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);

    return $url;
}

function dpt_url_xfilter()
{
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    //$url = rtrim($url, '/');
    //$url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);

    return $url;
}

function dpt_ip()
{
    $IP = array('192.168.1.', '10.69.112.', 
        '127.0.0.1', '10.72.112.',
        '60.48.66.1','175.140.12',
        '175.141.21');

    return $IP;
}

function pecah_post()
{
    $papar['pilih'] = isset($_POST['pilih']) ? $_POST['pilih'] : null;
    $papar['cari'] = isset($_POST['cari']) ? $_POST['cari'] : null;
    $papar['fix'] = isset($_POST['fix']) ? $_POST['fix'] : null;
    $papar['atau'] = isset($_POST['atau']) ? $_POST['atau'] : null;
    
    $kira['pilih'] = count($papar['pilih']);
    $kira['cari'] = count($papar['cari']);
    $kira['fix'] = count($papar['fix']);
    $kira['atau'] = count($papar['atau']);
    
    return $kira;
    //echo '<pre>'; print_r($kira) . '</pre>';
}

function dpt_senarai($namajadual)
{
    if ($namajadual=='msiclama')
        $jadual = array('msic08','msic2008',
        'msic_v1','msic_bandingan',
        'msic','msic_nota_kaki');
    elseif ($namajadual=='msicbaru')
        $jadual = array('msic2008','msic2008_asas',
        'msic_v1','msic_bandingan',
        'msic2000','msic2000_notakaki');
    elseif ($namajadual=='produk')
        $jadual = array('kodproduk_aup',
        'kodproduk_mei2011',
        'kodproduk_unitkuantiti');
    elseif ($namajadual=='syarikat')
    {
        $t='13'; // tahun rujukan
        $sv='mdt_'; // jenis penyiasatan
        $jadual = array($sv.'rangka'.$t,
        $sv.'jan'.$t, $sv.'feb'.$t, $sv.'mac'.$t, $sv.'apr'.$t, 
        $sv.'mei'.$t, $sv.'jun'.$t, $sv.'jul'.$t, $sv.'ogo'.$t, 
        $sv.'sep'.$t, $sv.'okt'.$t, $sv.'nov'.$t, $sv.'dis'.$t);
    }
    
    return $jadual;
}

function bulanan($jenis, $t)
{    
    if ($jenis=='kawalan') // $t = '12' , tahun
        $bulan = array('rangka'.$t,
        'jan'.$t, 'feb'.$t, 'mac'.$t, 'apr'.$t, 
        'mei'.$t, 'jun'.$t, 'jul'.$t, 'ogo'.$t, 
        'sep'.$t, 'okt'.$t, 'nov'.$t, 'dis'.$t);
    elseif ($jenis=='tambahkes')
    {
        //$t='13'; // tahun rujukan
        $sv='mdt_'; // jenis penyiasatan
        $bulan = array($sv.'rangka'.$t,
        $sv.'jan'.$t, $sv.'feb'.$t, $sv.'mac'.$t, $sv.'apr'.$t, 
        $sv.'mei'.$t, $sv.'jun'.$t, $sv.'jul'.$t, $sv.'ogo'.$t, 
        $sv.'sep'.$t, $sv.'okt'.$t, $sv.'nov'.$t, $sv.'dis'.$t);
    }
    elseif ($jenis=='semakan') // $t = '12' , tahun
        $bulan = array('pom_bln11.mm_rangka',
        'pom_bln12.mm_rangka');
    elseif ($jenis=='data_bulanan') 
        $bulan = array(
        'jan'.$t, 'feb'.$t, 'mac'.$t, 'apr'.$t, 
        'mei'.$t, 'jun'.$t, 'jul'.$t, 'ogo'.$t, 
        'sep'.$t, 'okt'.$t, 'nov'.$t, 'dis'.$t);
    elseif ($jenis=='nama_bulan') 
        $bulan = array(
        'jan', 'feb', 'mac', 'apr', 
        'mei', 'jun', 'jul', 'ogo', 
        'sep', 'okt', 'nov', 'dis');
	elseif ($jenis=='laporan_bulanan')
    {
		$sv='<li><a href="'; 
        $t='13'; // tahun rujukan
        $jadual = array($sv.'rangka'.$t,
        $sv.'jan'.$t, $sv.'feb'.$t, $sv.'mac'.$t, $sv.'apr'.$t, 
        $sv.'mei'.$t, $sv.'jun'.$t, $sv.'jul'.$t, $sv.'ogo'.$t, 
        $sv.'sep'.$t, $sv.'okt'.$t, $sv.'nov'.$t, $sv.'dis'.$t);
    }
    elseif ($jenis=='bulan_penuh')
        $bulan = array('Januari', 'Februari', 'Mac', 'April', 
        'Mei', 'Jun', 'Julai', 'Ogos', 
        'September', 'Oktober', 'November', 'Disember');
    elseif ($jenis=='prosesan')
        $bulan = array('info','jualan','gajistaf');
    
    return $bulan;
}

function data_prosesan()
{
    // mula masukkan nama jadual dan medan2
    //$jadual['medan'][]='*';
    /*$jadual['medan'][]='newss,ssm,nama,tel,fax,responden,' .
    '(SELECT keterangan FROM msic WHERE msic=msic2000 LIMIT 1,1) as keterangan,' .
    'msic2000,msicB2000,fe';*/
    $sama='j1.newss,concat(j1.tahun_rujukan,"-",j1.siri_kekerapan) as blnthn, ';
    $sama2='j2.newss,concat(j2.tahun_rujukan,"-",j2.siri_kekerapan) as blnthn, ';
    //$jadual['medan'][]=$sama . 'ssm, nama, operator, sv, kekerapan_sv'; 
    //$jadual['medan'][]=$sama . 'ng, po, data_anggaran, cara_maklum_balas, cara_terima, sumber_pertubuhan, kategori_sample';
    //$jadual['medan'][]=$sama . 'status_operasi, status_lain_pbb, bil_bulan_bco, siasatan_bermula, siasatan_berakhir';
    //$jadual['medan'][]=$sama . 'no_jln_bgn, tmn_kg, bandar_kawasan, poskod, negeri, daerah, catatan';
    //$jadual['medan'][]=$sama . 'responden, jawatan, email, notel, lamanweb, tarikh';
    //$jadual['medan'][]=$sama . 'responden2, jawatan2, email2, notel2, nofax2';
    //$jadual['medan'][]=$sama . '`no_jln_bgn-lokasi`, `tmn_kg-lokasi`, `bandar_kawasan-lokasi`, ' .
    //'`poskod-lokasi`, `negeri-lokasi` ng, `daerah-lokasi` dp';
    $jadual['medan'][]='j1.newss,j1.ssm,j1.nama,concat(j1.tahun_rujukan,"-",j1.siri_kekerapan) as blnthn ';
    $jadual['medan'][]=$sama . "\r" . 'msic_lama, (SELECT keterangan FROM msic WHERE msic=msic_lama LIMIT 1,1) as keterangan,' . 
    'msic_baru, utama '; 
    //$jadual['medan'][]=$sama . "\r\t" . 'F3001, F3002, catatan_soalan3, ' . 'F5001, F5002, F5003, F5104, catatan_soalan5';
    $jadual['medan'][]=$sama . 'F6001, F6002, F6003, F6004, F6005, F6101, F6102, F6103, F6104, F6105, catatan_soalan6';
    $jadual['medan'][]=$sama . 'F7001, F7002, F7003, catatan_soalan7, ' .
                'F8001, catatan_soalan8, F9001, catatan_soalan9';
    //$jadual['medan'][]=$sama . 'cara_anggaran, bulan_terakhir_data_sebenar, bil_bulan_data_telah_dianggar';

    //$jadual['medan'][]=$sama . 'Deskripsi_Produk_Oleh_Responden, Produk_Tetap, Deskripsi_Produk_Tetap,
    //Produk_Tambahan, Deskripsi_Produk_Tambahan, UnitKuantitiAsal, UnitKuantitiLain'; 
    $produk='Deskripsi_Produk_Tetap Nama, Produk_Tetap Kod, ';
    $jadual['medan'][]=$sama . $produk . 'KuantitiPengeluaran, KuantitiJualan, NilaiJualan, ' .
    'format(NilaiJualan/KuantitiJualan,2) as HargaUnit, UnitKuantitiKini';
    //$jadual['medan'][]=$sama . $produk . 'ProsesanKuantitiPengeluaran, ProsesanKuantitiJualan, ProsesanNilaiJualan, AUP';
    $jadual['medan'][]=$sama . $produk . 'F2497, F2498, F2499, Catatan';
    $jadual['medan'][]=$sama . "\r\t" . 
                'F3001, F3002, catatan_soalan3, ' .
                'F5001, F5002, F5003, F5104, catatan_soalan5';

    //$jadual['medan'][]=$sama . 'catatan';
    $jadual['medan'][]=$sama . 'F4001, F4002, F4003, F4004';
    $jadual['medan'][]=$sama . 'F4101, F4102, F4103, F4104';
    $jadual['medan'][]=$sama . 'F4201, F4202, F4203, F4204';
    $jadual['medan'][]=$sama . 'catatan "Nota ", F4302, F4303, F4304';

    // bulanan
    //$bulan=($_GET['bln']==null)? '':'AND siri_kekerapan="'.bersih($_GET['bln']).'"';
    $bulan_penuh = array('Januari', 'Februari', 'Mac', 'April', 
        'Mei', 'Jun', 'Julai', 'Ogos', 
        'September', 'Oktober', 'November', 'Disember');
    //$kerap='"'.$bulan_penuh[0].'","'.$bulan_penuh[1].'","'.$bulan_penuh[2].'"';
    //$bulan=($_GET['bln']==null)? '':'AND j1.siri_kekerapan in ('.$kerap.')';
        //$myJadual=array('mm_rangka','mm_rangka');
        for($z=1;$z <= 4;$z++) { $jadual['nama'][]='prosesmm_info'; } // bil medan = 15
        for($z=1;$z <= 2;$z++) { $jadual['nama'][]='prosesmm_jualan'; }
        $jadual['nama'][]='prosesmm_info';
        for($z=1;$z <= 4;$z++) { $jadual['nama'][]='prosesmm_gajistaf'; }
        $join1='prosesmm_info';
        $join2='prosesmm_jualan';
        $join3='prosesmm_gajistaf';
    
    return $jadual;
}

function tahunan($jenis, $t)
{    
    if ($jenis=='kawalan') 
        $tahunan = array('rangka',
        'jan'.$t, 'feb'.$t, 'mac'.$t, 'apr'.$t, 
        'mei'.$t, 'jun'.$t, 'jul'.$t, 'ogo'.$t, 
        'sep'.$t, 'okt'.$t, 'nov'.$t, 'dis'.$t);
    elseif ($jenis=='prosesan')
        $tahunan = array('info','jualan','gajistaf');
    elseif ($jenis=='semuakawalan')
        $tahunan = array('kawal_ppmas09','kawal_rpe09','kawal_tani09',
        'sse08_rangka','sse09_buat','sse09_ppt','sse10_kawal');
    elseif ($jenis=='bulan_penuh')
        $bulan = array('Januari', 'Februari', 'Mac', 'April', 
        'Mei', 'Jun', 'Julai', 'Ogos', 
        'September', 'Oktober', 'November', 'Disember');
    
    return $tahunan;
}

function senarai_kakitangan()
{
    $pegawai[]='adam';
    //$pegawai[]='ali';
    $pegawai[]='amin';
    $pegawai[]='ariff';
    $pegawai[]='azim';
    $pegawai[]='fendi';
    $pegawai[]='ita';
    $pegawai[]='irwan';
    $pegawai[]='khairi';
    $pegawai[]='murad';
    $pegawai[]='musa';
    $pegawai[]='mustaffa';
    $pegawai[]='razak';
    $pegawai[]='shukor';
    $pegawai[]='suhaida';
    $pegawai[]='sujana';
    
    return $pegawai;
}

function lihat($tab,$kini,$papar,$pegawai) 
{    
    $selit = null;
    $p = dpt_url(); // sepatutnya paparan/respon/semua/30/2/amin/
    $item = ( !isset($p[3]) ) ? '20/' : $p[3] . '/'; //'30'; 
    $ms = ( !isset($p[4]) ) ? '1/' : $p[4] . '/'; // '2';
    $url = URL . $papar . $item . $ms;
    $i = 1;
    
    foreach ($pegawai as $fe) 
    {
        $selit .= $tab . '<li><a href="' . 
        $url . $fe . '">' . $i++ . ') ' .
		$fe . '</a></li>' . "\r";
    }
    echo "\r" . $selit . $tab;
}

function pencamSqlLimit($bilSemua, $item, $ms)
{
    // Tentukan bilangan jumlah dalam DB:
    $jum['bil_semua'] = $bilSemua; //mysql_num_rows($semua);
    // ambil halaman semasa, jika tiada, cipta satu! 
    $jum['page'] = ( !isset($ms) ) ? 1 : $ms; // mukasurat
    // berapa item dalam satu halaman
    $jum['max'] = ( !isset($item) ) ? 30 : $item; // item
    // Tentukan had query berasaskan nombor halaman semasa.
    $jum['dari'] = (($jum['page'] * $jum['max']) - $jum['max']); 
    // Tentukan bilangan halaman. 
    $jum['muka_surat'] = ceil($jum['bil_semua'] / $jum['max']);
    // nak tentukan berapa bil jumlah dlm satu muka surat
    $jum['bil'] = $jum['dari']+1; 
    
    return $jum;
}

function halaman($jum)
{// function halaman() - mula
    $mula = '<span style="background-color: #fffaf0; color:black">';
    $tamat  = '</span>';
    $page = $jum['page'];
    $muka_surat = $jum['muka_surat'];
    $bil_semua = $jum['bil_semua'];
    $baris_max = $jum['max'];
            
    $url = dpt_url();  // sepatutnya kawalan/semua/30/1/amin/
    $class = ( !isset($url[0]) ) ? null : $url[0]; //'kawalan'; 
    $fungsi = ( !isset($url[1]) ) ? null : $url[1]; //'semua'; 
    $item = ( !isset($url[2]) ) ? null : $url[2]; //'30'; 
    $ms = ( !isset($url[3]) ) ? null : $url[3]; //'ms'; 
    $fe = ( !isset($url[4]) ) ? null : $url[4]; //'fe'; 
    
    $senarai = URL . "$class/$fungsi/$baris_max/";
    $halaman = "\n$mula Bil Kes:($bil_semua)- Papar halaman ".
        '<div class="pagination"><ul>'; 
        
    if($page > 1) // Bina halaman sebelum
        $halaman .= "\r<li><a href='$senarai" . ($page-1) . "/$fe'>Sebelum</a></li>";
    for($i = 1; $i <= $muka_surat; $i++) // Bina halaman terkini
        {$halaman .= ($page==$i)? "<li><a>($i)</a></li>" : "\r<li><a href='$senarai$i/$fe'>$i</a></li>";}
    if($page < $muka_surat) // Bina halaman akhir
        $halaman .= "\r<li><a href='$senarai" . ($page+1) . "/$fe'>Lagi</a></li>";
        
    $halaman .= "\n</ul></div>\n$tamat";

    return $halaman;
}// function halaman() - tamat

function cariMedanInput($ubah,$f,$row,$nama) 
{/* mula -
    $ubah = nama jadual
    $f = nombor medan
    $row = data medan
    $nama = nama medan
    
    senarai nama medan
    0-nota,1-respon,2-fe,3-tel,4-fax,        
    5-responden,6-email,7-msic,8-msic08,
    9-`id U M`,10-nama,11-sidap,12-status 
 */// papar medan yang terlibat
 
    $cariMedan = array(0,1,2,3,4,5,6,8);
    $cariText = array(0); // papar jika nota ada
    $cariMsic = array(8); // papar input text msic sahaja 
    $namaM = $ubah .'[' . $nama . ']';
        
    // tentukan medan yang ada input
    $input=in_array($f,$cariMedan)? 
    (@in_array($f,$cariMsic)? // tentukan medan yang ada msic
        '<input type="text" name="' . $namaM . '" value="' . $row[$f] . '" size=6>'
        :(@in_array($f,$cariText)? // tentukan medan yang ada input textarea
            '<textarea name="' . $namaM . '" rows=2 cols=23>' . $row[$f] . '</textarea>'
            : // tentukan medan yang bukan input textarea
            '<input type="text" name="' . $namaM . '" value="' . $row[$f] . '" size=30>'
        )
    ):'<label class="papan">' . $row[$f] . '</label>';
    
    return $input;

}
function semakMedanDaftar($myTable, $nama, $jenis, $data) 
{
    return $myTable.'->'.$nama.'->'.$jenis.'='.$data;
}

function paparMedanDaftar($myTable, $nama, $jenis, $data) 
{
    $namaMedan = 'name="' . $myTable . '[' . $nama . ']" '
               . 'id="' . $nama . '"';
    $papar = null;
    
    if ($nama == 'password')
    {
        $papar = '<input type="password" ' . $namaMedan . ' class="span3">';
    }
    elseif ($nama == 'level')
    {
        $papar = '<select ' . $namaMedan . '>';
        $senaraiLevel= array('baru');
        
        foreach ($senaraiLevel as $key => $value)
        {
            $papar .= '<option value="' . $value . '">'
                   . ucfirst(strtolower($value)) 
                   . '</option>';
        }
        $papar .= '</select>';

    }
    elseif ($nama == 'jantina')
    {
        $papar = '<select ' . $namaMedan . '>';
        $senaraiJantina = array('lelaki','perempuan');
        
        foreach ($senaraiJantina as $key => $value)
        {
            $papar .= '<option value="' . $value . '">'
                   . ucfirst(strtolower($value)) 
                   . '</option>';
        }
        $papar .= '</select>';
    }
    else
    {
        $papar = inputDaftar($jenis, $namaMedan, $data);
    }

    return $papar;
}

function inputDaftar($jenis, $namaMedan, $data)
{
        switch ($jenis) 
        {// mula - pilih type - bigint(20) | CHAR3
        case 'char(3)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="input-small">';
            break;
        case 'varchar(3)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="input-small">';
            break;
        case 'char(5)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="input-small">';
            break;
        case 'varchar(5)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="input-small">';
            break;
        case 'varchar(12)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="span2">';
            break;
        case 'varchar(20)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="span3">';
            break;
        case 'varchar(35)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="span4">';
            break;
        case 'varchar(50)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="span5">';
            break;        
        case 'varchar(255)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="span7">';
            break;        
        case 'date':
            $papar = '<input type="text" ' . $namaMedan . ' class="input-small tarikh" readonly">';
            break;
        case 'int(11)':
            $papar = '<input type="text" ' . $namaMedan . ' class="input-small">';
            break;
        case 'bigint(20)':
            $papar = '<input type="text" ' . $namaMedan . ' class="input-small">';
            break;
        case 'double':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="input-small">';
            break;
        case 'text':
            $jenisText = $namaMedan . ' rows="3" cols="30" ';
            $papar = '<textarea ' . $jenisText . '>' . $data . '</textarea>';
            break;
        default: 
            $papar="$namaMedan-$jenis-$data"; 
            break;
        }// tamat - pilih type

        return $papar;
}


function paparMedanDaftarSesi($myTable, $nama, $jenis, $data, $sesi) 
{
    $namaMedan = 'name="' . $myTable . '[' . $nama . ']" '
               . 'id="' . $nama . '"';
    $papar = null;
        
    if ($nama == 'nama_penuh')
    {
        $papar = '<input type="text" ' . $namaMedan 
               . ' value="' . $sesi['namaPenuh'] . '" class="span4">';
    }
    elseif ($nama == 'namapengguna')
    {
        $papar = '<input type="text" ' . $namaMedan 
               . ' value="' . $sesi['pengguna'] . '" class="span4">';

    }
    elseif ($nama == 'level')
    {
        $papar = '<select ' . $namaMedan . '>';
        $senaraiPengguna= array('baru');
        
        foreach ($senaraiPengguna as $key => $value)
        {
            $papar .= '<option value="' . $value . '"';
            $papar .= ($value == $sesi['level']) ? ' selected >' : '>';
            $papar .= ucfirst(strtolower($value));
            $papar .= '</option>';
        }
        $papar .= '</select>';

    }
    elseif ($nama == 'jantina')
    {
        $papar = '<select ' . $namaMedan . '>';
        $senaraiJantina = array('lelaki','perempuan');
        
        foreach ($senaraiJantina as $key => $value)
        {
            $papar .= '<option value="' . $value . '">'
                   . ucfirst(strtolower($value)) 
                   . '</option>';
        }
        $papar .= '</select>';
    }
    elseif ($nama == 'password')
    {
        $papar = '<input type="password" ' . $namaMedan . ' class="span3">';
    }
    elseif ($nama == 'level')
    {
        $papar = '';
    }
    else
    {
        $papar = inputDaftar($jenis, $namaMedan, $data);
    }

    return $papar;
}

function ubahMedanSesi($myTable, $nama, $jenis, $data) 
{
    $namaMedan = 'name="' . $myTable . '[' . $nama . ']" '
               . 'id="' . $nama . '"';

    //$papar = null;
        
    if ($nama == 'level')
    {
        /*
        $papar = '<select ' . $namaMedan . '>';
        $senaraiPengguna= array('baru');
        
        foreach ($senaraiPengguna as $key => $value)
        {
            $papar .= '<option value="' . $value . '"';
            $papar .= ($value == $data) ? ' selected >' : '>';
            $papar .= ucfirst(strtolower($value));
            $papar .= '</option>';
        }
        $papar .= '</select>';
        */
        $papar = null;

    }
    elseif ($nama == 'jantina')
    {
        $papar = '<select ' . $namaMedan . '>';
        $senaraiJantina = array('lelaki','perempuan');
        
        foreach ($senaraiJantina as $key => $value)
        {
            $papar .= '<option value="' . $value . '"';
            $papar .= ($value == $data) ? ' selected >' : '>';
            $papar .= ucfirst(strtolower($value));
            $papar .= '</option>';
        }
        $papar .= '</select>';
    }
    elseif ($nama == 'password')
    {
        $papar = '<input type="password" ' . $namaMedan . ' value="' . $data . '" class="span3">';
    }
    elseif ($nama == 'idpinjaman')
    {
        $papar = '';
    }
    else
    {
        $papar = ubahInputDaftar($jenis, $namaMedan, $data);
    }

    return $papar;
}

function ubahInputDaftar($jenis, $namaMedan, $data)
{
        switch ($jenis) 
        {// mula - pilih type
        case 'varchar(2)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="input-small">';
            break;
        case 'varchar(10)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="span3">';
            break;
        case 'varchar(20)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="span4">';
            break;
        case 'varchar(50)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="span5">';
            break;
        case 'varchar(255)':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="span6">';
            break;
        case 'date':
            $papar = '<input type="text" ' . $namaMedan . ' value="' . $data . '" class="input-small tarikh" readonly">';
            break;
        case 'text':
            $jenisText = $namaMedan . ' rows="3" cols="30" ';
            $papar = '<textarea ' . $jenisText . '>' . $data . '</textarea>';
            break;
        default: 
            $papar="$namaMedan-$data"; 
            break;
        }// tamat - pilih type

        return $papar;
}

function kira($kiraan)
{
    // pecahan kepada ratus
    return number_format($kiraan,0,'.',',');
} 

function kira2($dulu,$kini)
{
    // buat bandingan dan pecahan kepada ratus
    return @number_format((($kini-$dulu)/$dulu)*100,0,'.',',');
    //@$kiraan=(($kini-$dulu)/$dulu)*100;
}

function huruf($jenis, $papar)
{
    /*
    $_POST['mdt_rangka']['respon']=strtoupper($_POST['mdt_rangka']['respon']);
    $_POST['mdt_rangka']['fe']=strtolower($_POST['mdt_rangka']['fe']);
    $_POST['mdt_rangka']['responden']=mb_convert_case($_POST['mdt_rangka']['responden'], MB_CASE_TITLE);
    */
    
    switch ($jenis) 
    {// mula - pilih $jenis
    case 'BESAR':
        $papar = strtoupper($papar);
        break;
    case 'kecil':
        $papar = strtolower($papar);
        break;
    case 'Depan':
        $papar = ucfrist($papar);
        break;
    case 'Besar_Depan':
        $papar = mb_convert_case($papar, MB_CASE_TITLE);
        break;
    }// tamat - pilih $jenis
    
    return $papar;

}

function bersih($papar) 
{
    # lepas lari aksara khas dalam SQL
    //$papar = mysql_real_escape_string($papar);
    # buang ruang kosong (atau aksara lain) dari mula & akhir 
    $papar = trim($papar);
    
    return $papar;
}

function gambar_latarbelakang($lokasi)
{
    $tmpt = //$lokasi . 'bg/bg/';
        //$_SERVER['SERVER_NAME'] . '/private_html/bg/bg/';
        '../../bg/bg/' ;
        
    foreach(scandir($tmpt) as $gambar) 
    {
        if (substr($gambar,-3) == 'jpg') 
            $papar[]=$gambar;
    }

    $bg['papar'] = print_r($papar, 1);
    //$bg['papar'] = '<br>count($papar)='.count($papar);
    $bg['count_tolak_1'] = (count($papar)-1);
    $bg['today'] = rand(0, count($papar)-1);
    $bg['gambar'] = $papar[$bg['today']];
    
    return $bg;
}

function cari_imej($ssm,$strDir)
{
    //require_once ('public/skrip/listfiles2/dir_functions.php');

    if ( isset($ssm) && empty($ssm) )
    {
        $cariImej = null;
    }
    else
    {
        // You can modify this in case you need a different extension
        $strExt = "tif";

        // This is the full match pattern based upon your selections above
        $pattern = "*" . $ssm . "*." . $strExt;
        $cariImej = GetMatchingFiles(GetContents($strDir),$pattern);
    }
    
    //print_r($cariImej);
    return $cariImej;
}
// lisfile2 - mula
function GetMatchingFiles($files, $search) 
{
    // Split to name and filetype
    if(strpos($search,".")) 
    {
        $baseexp=substr($search,0,strpos($search,"."));
        $typeexp=substr($search,strpos($search,".")+1,strlen($search));
    } 
    else 
    { 
        $baseexp=$search;
        $typeexp="";
    } 
        
    // Escape all regexp Characters 
    $baseexp=preg_quote($baseexp); 
    $typeexp=preg_quote($typeexp); 
        
    // Allow ? and *
    $baseexp=str_replace(array("\*","\?"), array(".*","."), $baseexp);
    $typeexp=str_replace(array("\*","\?"), array(".*","."), $typeexp);
           
    // Search for Matches
    $i=0;
    $matches=null; // $matches adalah array()
    foreach($files as $file) 
    {
        $filename=basename($file);
              
        if(strpos($filename,".")) 
        {
            $base=substr($filename,0,strpos($filename,"."));
            $type=substr($filename,strpos($filename,".")+1,strlen($filename));
        } 
        else 
        { 
            $base=$filename;
            $type="";
        }

        if(preg_match("/^".$baseexp."$/i",$base) && preg_match("/^".$typeexp."$/i",$type))  
        {
            $matches[$i]=$file;
            $i++;
        }
    }
    
    return $matches;
    //return $matches;
}

// Returns all Files contained in given dir, including subdirs
function GetContents($dir,$files=array()) 
{
    if(!($res=opendir($dir))) exit("$dir doesn't exist!");
        while(($file=readdir($res))==TRUE) 
        if($file!="." && $file!="..")
            if(is_dir("$dir/$file")) $files=GetContents("$dir/$file",$files);
                else array_push($files,"$dir/$file");
         
    closedir($res);
    return $files;
}
// listfile2 - tamat
