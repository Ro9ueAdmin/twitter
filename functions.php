<?php
function query($method, $data, $ssl, $ref, $tm, $cookie_name)
{
	switch($tm){
	    case 0: $url = "https://twitter.com/".$method;          #twitter
	    break;
	    case 1: $url = "https://auth.mail.ru/cgi-bin/".$method; #mail auth
	    break;
	    case 2: $url = "https://e.mail.ru/".$method;            #e.mail
	    break;
	    case 3: $url = "https://m.mail.ru/".$method;            #m.mail
	    break;
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:36.0) Gecko/20100101 Firefox/36.0");
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_name);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_name);
	if($ssl){
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	}
	if($data){
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_POST, true);
	}
	$rez = curl_exec($ch);
	curl_close($ch);
	return $rez;
}
?>