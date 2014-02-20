<?php
    
/////////////////////////////////////////
////  ENTIREWEB INITIAL SEARCH DATA  ////
/////////////////////////////////////////
    
$entire_web_key = 'e7ff08829fe23da1646bccebbce3a357';
$entire_web_root_url = 'http://www.entireweb.com/xmlquery?ip=127.0.0.1&n=100&of=0&q=';  
$entire_web_query =  urlencode(''.$entireweb_query.'');
$entire_web_curl_request = $entire_web_root_url.$entire_web_query.'&format=json&pz='.$entire_web_key;
    
//cURL session
$entire_web_curl_session = curl_init($entire_web_curl_request);
curl_setopt($entire_web_curl_session, CURLOPT_RETURNTRANSFER, TRUE);
    
?>