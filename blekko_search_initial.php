<?php

//////////////////////////////////////
////  BLEKKO INITIAL SEARCH DATA  ////
//////////////////////////////////////
    
$blekko_key = 'f4c8acf3';
$blekko_root_url = 'http://blekko.com/ws/?q=';  //to add news: +/news/

$blekko_query =  urlencode($blekko_query);

//echo 'BLEKKO  QUERY - '.$blekko_query.'<br>';

$blekko_curl_request = $blekko_root_url.$blekko_query.'+/json+/ps=100&auth='.$blekko_key;
    
//cURL session
$blekko_curl_session = curl_init($blekko_curl_request);
curl_setopt($blekko_curl_session, CURLOPT_RETURNTRANSFER, TRUE);
    
?>