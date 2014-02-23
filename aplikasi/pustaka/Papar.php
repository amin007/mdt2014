<?php

class Papar 
{

	function __construct() 
	{
		//echo '<br>1. Anda berada di class Papar<br>';
	}
	
	public function bacaan($nama, $noInclude = false)
	{
		//echo 'Anda berada di class Papar<br>' .
		//'fungsi ' . $nama . '()<br>';


		if ($noInclude == true) 
		{
			require PAPAR . $nama . '.php';	
		}
		else 
		{
			require PAPAR . 'diatas.php';
			require PAPAR . 'menu_atas.php';
			require PAPAR . $nama . '.php';
			require PAPAR . 'dibawah.php';	
		}
	}

	public function baca($nama, $noInclude = false)
	{
		//echo '<br>1.Anda berada di class Papar::' . $nama . '()<br>';

		$cariNama = array ('index/index', 'index/login',
		'index/login_automatik', 'index/salah');
			
		if ($noInclude == true) 
		{
			require PAPAR . $nama . '.php';	
		}
		else 
		{
			if( in_array($nama,$cariNama) )
				require PAPAR . $nama . '.php';	
			else
			{				
				require PAPAR . 'diatas.php';
				require PAPAR . 'menu_atas.php';
				require PAPAR . $nama . '.php';
				require PAPAR . 'dibawah.php';	
			
			}
		}
	}

}