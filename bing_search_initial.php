<?php

////////////////////////////////////
////  BING INITIAL SEARCH DATA  ////
////////////////////////////////////
	
$skip=0; //displays only correct totals when $top=10 so need to call 3 times (max is 50)
$top=10;

$bing_key = 'ypo5ky9o1kXbWiigky31jUODpuN2QBfwliQrD9j9Y3E=';
$bing_root_url = 'https://api.datamarket.azure.com/Bing/Search/';  
    
$bing_url_1 = $bing_root_url.'Composite?Sources=\'Web%2bNews\''.'&$top='.$top.'&$skip='.$skip.'&$format=json&Query=';
$bing_request_1 = $bing_url_1 .'\''.urlencode($bing_query).'\'';
$bing_session_1 = curl_init($bing_request_1);
    
$skip = 10;
$top = 40;
    
$bing_url_2 = $bing_root_url.'Composite?Sources=\'Web%2bNews\''.'&$top='.$top.'&$skip='.$skip.'&$format=json&Query=';
$bing_request_2 = $bing_url_2 .'\''.urlencode($bing_query).'\'';
$bing_session_2 = curl_init($bing_request_2);
    
$skip = 50;
$top = 50;
    
$bing_url_3 = $bing_root_url.'Composite?Sources=\'Web%2bNews\''.'&$top='.$top.'&$skip='.$skip.'&$format=json&Query=';
$bing_request_3 = $bing_url_3 .'\''.urlencode($bing_query).'\'';
$bing_session_3 = curl_init($bing_request_3);
    
curl_setopt($bing_session_1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($bing_session_1, CURLOPT_USERPWD, $bing_key.":".$bing_key);
curl_setopt($bing_session_1, CURLOPT_TIMEOUT, 30);
curl_setopt($bing_session_1, CURLOPT_RETURNTRANSFER, TRUE);
    
curl_setopt($bing_session_2, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($bing_session_2, CURLOPT_USERPWD, $bing_key.":".$bing_key);
curl_setopt($bing_session_2, CURLOPT_TIMEOUT, 30);
curl_setopt($bing_session_2, CURLOPT_RETURNTRANSFER, TRUE);
    
curl_setopt($bing_session_3, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($bing_session_3, CURLOPT_USERPWD, $bing_key.":".$bing_key);
curl_setopt($bing_session_3, CURLOPT_TIMEOUT, 30);
curl_setopt($bing_session_3, CURLOPT_RETURNTRANSFER, TRUE);
    
?>