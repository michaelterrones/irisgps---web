<?php

class Tokenizer {

	public static function generate(){
		$length = 100;
	    $token = "";
	    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
	    $codeAlphabet.= "0123456789";
	    for($i=0;$i<$length;$i++){
	        $token .= $codeAlphabet[self::crypto_rand_secure(0,strlen($codeAlphabet))];
	    }
	    //$secureToken = Hash::make($token);
	    //return array('token' => $token, 'secureToken' => $secureToken);
	    return $token;
	}

	public static function validate($token, $mobile)
	{
		return !is_null(Device::where('token', $token)->where('mobile', $mobile)->first());
	}

	protected static function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
	}
}
