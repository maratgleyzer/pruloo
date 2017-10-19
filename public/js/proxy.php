<?php

	$url = 'http://www.proleroinc.com/order';

	$post = "";

	$header = array();

	foreach ($_POST as $key => $value)
		$post .= $key."=".$value."&";

	$response = Curl($url, $post, $header);
	echo $response; exit;
	
		
	function Curl($url, $post, $header) {

		$ch = curl_init ();

		curl_setopt ($ch, CURLOPT_URL, $url);
    	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt ($ch, CURLOPT_POST, 0);
    	curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);

    	if ($header != "") {
    		curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);
	    }

	    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);

	    ob_start ();
	    $result = curl_exec ($ch);
	    ob_end_clean ();

	    curl_close ($ch);

	    return $result;

	}
	
	
?>