<?php 
$nav = 'data-toggle="dropdown" class="dropdown-toggle active"';
$url = URL;
$kini = ( !isset($this->kini) ) ? null : $this->kini;
?>
<ul class="nav">
<li class="dropdown">
	<a <?php echo $nav ?> href="#">1. 
	<i class="icon-user icon-white"></i>
	<?php echo $pengguna ?>
	<b class="caret"></b></a>
	<ul class="dropdown-menu">
	<li><a href="<?php echo $url ?>ruangtamu/logout">Keluar</a></li>
	<li><a href="<?php echo $url ?>ruangtamu">Anjung</a></li>
	<li><a target="_blank" href="<?php echo $url ?>laporan/bulanan/respon">Respon Bulanan</a></li>
	<li><a target="_blank" href="<?php echo $url ?>laporan/rangka">Laporan Rangka MM2012</a></li>
	<li><a href="<?php echo $url ?>semak/alamat/fe/30">Alamat MM2012</a></li>
	<li><a href="<?php echo $url ?>semak/rangka/fe/300">Rangka Ikut FE</a></li>
	<li><a href="<?php echo $url ?>semak/a1/fe/300">Semak A1</a></li>
	<li><a href="<?php echo $url ?>semak/xa1/fe/300">Semak Bukan A1</a></li>
	<li><a href="<?php echo $url ?>semak/label/<?php echo $pengguna ?>/300">Semak Label</a></li>
	</ul>
</li>
<li class="dropdown">
	<a <?php echo $nav ?> href="#">2. Respon Bulanan
	<b class="caret"></b></a>
	<ul class="dropdown-menu">
	<li class="dropdown submenu">
		<a href="#">Rangka Kawalan</a>
		<ul class="dropdown-menu submenu-show submenu-hide">
		<li class="dropdown submenu">
			<a href="<?php echo $url ?>paparan/respon/semua/30">2.1. Kes Semua</a>
			<ul class="dropdown-menu submenu-show submenu-hide"><?php 
			if ($level == 'kawal') lihat("\t\t\t",$kini,'paparan/respon/semua/',$pegawai); ?></ul>
		</li>
		<li class="dropdown submenu">
			<a href="<?php echo $url ?>paparan/respon/selesai/30">2.2. Kes A1</a>
			<ul class="dropdown-menu submenu-show submenu-hide"><?php 
			if ($level == 'kawal') lihat("\t\t\t",$kini,'paparan/respon/selesai/',$pegawai);?></ul>
		</li>
		<li class="dropdown submenu">		
			<a href="<?php echo $url ?>paparan/respon/janji/30">2.3. Kes Janji</a>
			<ul class="dropdown-menu submenu-show submenu-hide"><?php 
			if ($level == 'kawal') lihat("\t\t\t",$kini,'paparan/respon/janji/',$pegawai);?></ul>
		</li>
		<li class="dropdown submenu">		
			<a href="<?php echo $url ?>paparan/respon/belum/30">2.4. Kes Belum A1</a>
			<ul class="dropdown-menu submenu-show submenu-hide"><?php 
			if ($level == 'kawal') lihat("\t\t\t",$kini,'paparan/respon/belum/',$pegawai);?></ul>
		</li>
		<li class="dropdown submenu">		
			<a href="<?php echo $url ?>paparan/respon/tegar/30">2.5. Kes Tegar</a>
			<ul class="dropdown-menu submenu-show submenu-hide"><?php 
			if ($level == 'kawal') lihat("\t\t\t",$kini,'paparan/respon/tegar',$pegawai);?></ul>
		</li>
		<li class="dropdown submenu">
			<a href="<?php echo $url ?>paparan/respon/utama/100/1/bbu/">2.6. Kes BBU</a>
			<ul class="dropdown-menu submenu-show submenu-hide">
			<li><a href="<?php echo $url ?>paparan/respon/utama/100/1/bbu/a1">A1</a></li>
			<li><a href="<?php echo $url ?>paparan/respon/utama/100/1/bbu/xa1">XA1</a></li>
			<li><a href="<?php echo $url ?>paparan/respon/utama/100/1/bbu/tegar">TEGAR</a></li>
			</ul>
		</li>
		<li class="dropdown submenu">
			<a href="<?php echo $url ?>paparan/respon/utama/100/1/sbu/">2.7. Kes SBU</a>
			<ul class="dropdown-menu submenu-show submenu-hide">
			<li><a href="<?php echo $url ?>paparan/respon/utama/100/1/sbu/a1">A1</a></li>
			<li><a href="<?php echo $url ?>paparan/respon/utama/100/1/sbu/xa1">XA1</a></li>
			<li><a href="<?php echo $url ?>paparan/respon/utama/100/1/sbu/tegar">TEGAR</a></li>
			</ul>
		</li>
		</ul>
	</li>
	<li class="dropdown submenu">
		<a href="#">Rangka Prosesan</a>
		<ul class="dropdown-menu submenu-show submenu-hide">
		<li><a href="<?php echo $url ?>prosesan/semua/30">2.1. Kes Semua</a></li>
		</ul>
	</li>
	</ul>
</li>
<li class="dropdown">
	<a <?php echo $nav ?> href="#">3. Carian
	<b class="caret"></b></a>
	<ul class="dropdown-menu">
	<li><a href="<?php echo $url ?>cari/syarikat/1">3.1 Cari</a></li>
	<li><a href="<?php echo $url ?>cari/msic/1">3.2 MSIC</a></li>
	<li><a href="<?php echo $url ?>cari/produk/1">3.3 PRODUK</a></li>
	<li><a href="<?php echo $url ?>cari/localiti/2">3.4 LOCALITI</a></li>
	<li><a href="<?php echo $url ?>cari/prosesan/1">3.5 Prosesan</a></li>
	</ul>
</li>
<li class="dropdown">
	<a <?php echo $nav ?> href="#">4. Laporan
	<b class="caret"></b></a>
	<ul class="dropdown-menu">
	<li class="dropdown submenu">
		<a <?php echo $nav ?> href="#">4.1. Laporan Bulanan</a>
		<ul class="dropdown-menu submenu-show submenu-hide">
		<li><a target="_blank" href="<?php echo $url ?>laporan/banding/beza/msic">Bandingan</a></li>
		<li><a href="<?php echo $url ?>laporan/jualan/300">Jualan Tertinggi</a></li>
		<li><a target="_blank" href="<?php echo $url ?>laporan/bulanan">Bulanan</a></li>
		<li><a target="_blank" href="<?php echo $url ?>laporan_f3.php/mm">Laporan F3 - MM</a></li>
		<li><a target="_blank" href="<?php echo $url ?>laporan_f3.php/qss">Laporan F3 - QSS</a></li>
		<li><a target="_blank" href="<?php echo $url ?>laporan/f7">Laporan F7</a></li>
		<li><a target="_blank" href="<?php echo $url ?>laporan/tehar">Bulanan Tegar</a></li>
		<li><a target="_blank" href="<?php echo $url ?>laporan/bulan">Bulan <?=$kini?></a></li>
		</ul>	
	</li>
	<li class="dropdown submenu">
		<a href="<?php echo $url ?>bbu/semua/30">4.2 BBU</a>
		<ul class="dropdown-menu submenu-show submenu-hide">
		<li class="dropdown submenu">
			<a href="<?php echo $url ?>bbu/a1/30">A1</a>
			<ul class="dropdown-menu submenu-show submenu-hide"><?php
			lihat("\t\t\t",$kini,'bbu/a1/',$pegawai); ?></ul>
		</li>
		<li class="dropdown submenu">
			<a href="<?php echo $url ?>bbu/xa1/30">XA1</a>
			<ul class="dropdown-menu submenu-show submenu-hide"><?php
			lihat("\t\t\t",$kini,'bbu/xa1/',$pegawai); ?></ul>
		</li>
		</ul>
	</li>
	<li class="dropdown submenu">
		<a href="<?php echo $url ?>sbu/semua/30">4.3 SBU</a>
		<ul class="dropdown-menu submenu-show submenu-hide">
		<li class="dropdown submenu">
			<a href="<?php echo $url ?>sbu/a1/30">A1</a>
			<ul class="dropdown-menu submenu-show submenu-hide"><?php 
			lihat("\t\t\t",$kini,'sbu/a1/',$pegawai); ?></ul>
		</li>
		<li class="dropdown submenu">
			<a href="<?php echo $url ?>sbu/xa1/30">XA1</a>
			<ul class="dropdown-menu submenu-show submenu-hide"><?php 
			lihat("\t\t\t",$kini,'sbu/xa1/',$pegawai); ?></ul>
		</li>
		</ul>
	</li>
	</ul>
</li>
<li class="dropdown">
	<a <?php echo $nav ?> href="#">5. Bantuan
	<b class="caret"></b></a>
	<ul class="dropdown-menu">
	<li><a href="#">5.1. Sistem</a></li>
	<li><a href="<?php echo $url ?>forum">5.2. Forum</a></li>
	<li><a href="<?php echo $url ?>mesej">5.3. Email/Mesej Peribadi</a></li>
	</ul>
</li>
</ul>