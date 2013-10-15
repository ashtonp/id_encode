<?php
  	//sample character set for base 62 (if using custom charset ensure that every character is unique, it does not have to be ordered)
	$global_charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';	
						 
	//Generate a unique encoded "hash" from an id number to use mask database id numbers in html output 
	//function upper limit = (2.1 * 10^9) - size of charset [32-bit], (9.2 * 10^18) - size of charset [64-bit]
	function encodeID($id, $charset)
	{
		//use a bijective function to hash the id number in using a base the size of the character set
		$base    = strlen($charset); 
		$id     += $base;
		$encoded = '';
		
		//convert id number to calculated base and append to string from front in reverse order
		//to suit calculations using powers of base e.g [62^3,62^2,62^1,62^0]
		while ($id > 0)
		{
  			$encoded = $charset[$id % $base].$encoded;
  			$id = (int) ($id / $base);
		}
		
		return $encoded;
	}
	
	
	//decode a hashed id number to find the id number of the database record
	function decodeID($encoded, $charset)
	{
		// split hash into an array of characters, calculate the power and base
		$decoded       = 0;
		$code          = str_split($encoded);
		$base	       = strlen($charset);
		$highest_power = count($code)-1;

		//traverse through array to reverse calculation from hash to id using the paramerterized charset
		foreach ($code as $value)
		{			
			$decoded += (int)(strpos($charset, $value)) * (pow($base, $highest_power));
			$highest_power--;
		}

		return ($decoded - $base);
	}

	// feel free to test and change id_num
	$id_num = 2147483585;
	$enc    = encodeID($id_num, $global_charset);
	$dec    = decodeID($enc, $global_charset);
	echo "primary key value = ".$id_num."<br />";
	echo "encoded value = ".$enc."<br />";
	echo "decoded value = ".$dec."<br />";
?>
