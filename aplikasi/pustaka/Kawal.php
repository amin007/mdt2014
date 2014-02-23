<?php

class Kawal 
{

	function __construct() 
	{
		//echo 'Main kawal<br />';
		$this->papar = new Papar();
	}
	
	public function muatTanya($nama) 
	{
		
		$path = TANYA . $nama . '_tanya.php';
		//echo '<br>' . $path . '<br>';
		
		if (file_exists($path)) 
		{
			require $path;
			
			$tanyaNama = $nama . '_Tanya';
			$this->tanya = new $tanyaNama();			
		}
	}

}