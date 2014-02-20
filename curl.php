<?php    
    
$multi_session = curl_multi_init();
	
curl_multi_add_handle($multi_session, $bing_session_1);
curl_multi_add_handle($multi_session, $bing_session_2);
curl_multi_add_handle($multi_session, $bing_session_3);
curl_multi_add_handle($multi_session, $entire_web_curl_session);
curl_multi_add_handle($multi_session, $blekko_curl_session);
/*
curl_multi_add_handle($multi_session, $yahoo_session_1);
curl_multi_add_handle($multi_session, $yahoo_session_2);
curl_multi_add_handle($multi_session, $yahoo_session_3);
*/
$running = null;
do {
    curl_multi_exec($multi_session, $running);
} while ($running);  
    
$bing_data_1 = curl_multi_getcontent($bing_session_1);
$bing_data_2 = curl_multi_getcontent($bing_session_2);
$bing_data_3 = curl_multi_getcontent($bing_session_3);
$entire_web_data = curl_multi_getcontent($entire_web_curl_session);
$blekko_data = curl_multi_getcontent($blekko_curl_session);

curl_multi_remove_handle($multi_session, $bing_session_1);
curl_multi_remove_handle($multi_session, $bing_session_2);
curl_multi_remove_handle($multi_session, $bing_session_3);
curl_multi_remove_handle($multi_session, $entire_web_curl_session);
curl_multi_remove_handle($multi_session, $blekko_curl_session);

curl_multi_close($multi_session); 

/*
$yahoo_data_1 = curl_multi_getcontent($yahoo_session_1);
$yahoo_data_2 = curl_multi_getcontent($yahoo_session_2);
$yahoo_data_3 = curl_multi_getcontent($yahoo_session_3);
*/

$bing_results[0] = json_decode($bing_data_1);  
$bing_results[1] = json_decode($bing_data_2); 
$bing_results[2] = json_decode($bing_data_3);
$entire_web_results = json_decode($entire_web_data);
$blekko_results[0] = json_decode($blekko_data);




$blekko_results_new;
//blekko can sometimes return less than 100 results
//need to do a second request in this case
if($blekko_results[0]->total_num < 100)
{
	global $blekko_query;
	$blekko_key = 'f4c8acf3';
	$blekko_root_url = 'http://blekko.com/ws/?q=';  //to add news: +/news/
	$ps = 100 - $blekko_results[0]->total_num;
	
	//next page of results
	$blekko_curl_request_new = $blekko_root_url.$blekko_query.'+/json+/ps='.$ps.'&auth='.$blekko_key.'&p=1';
	$blekko_curl_session_new = curl_init($blekko_curl_request_new);
	curl_setopt($blekko_curl_session_new, CURLOPT_RETURNTRANSFER, TRUE);
	$temp_data = curl_exec($blekko_curl_session_new);
	$blekko_results[1] = json_decode($temp_data);
}

//$blekko_results[1] = $blekko_results_new;

//print_r($blekko_results);
//print_r($blekko_results_new);

/*
$yahoo_results[0] = json_decode($yahoo_data_1); 
$yahoo_results[1] = json_decode($yahoo_data_2); 
$yahoo_results[2] = json_decode($yahoo_data_3); 
*/
//total number of results for each engine
$entire_web_total_num_results = $entire_web_results->estimate;
$bing_total_num_results = $bing_results[0]->d->results[0]->WebTotal;
$blekko_total_num_results = $blekko_results[0]->universal_total_results;

//the follwing will replace the blekko shorthand of 'M' and 'K' with the 
//appropriate numeric value to be used for calculating LMS

$blekko_total_num_results = str_replace("M",'000000',$blekko_total_num_results);
$blekko_total_num_results = str_replace("K",'000',$blekko_total_num_results);

/*
curl_multi_remove_handle($multi_session, $bing_session_1);
curl_multi_remove_handle($multi_session, $bing_session_2);
curl_multi_remove_handle($multi_session, $bing_session_3);
curl_multi_remove_handle($multi_session, $entire_web_curl_session);
curl_multi_remove_handle($multi_session, $blekko_curl_session);

curl_multi_remove_handle($multi_session, $yahoo_session_1);
curl_multi_remove_handle($multi_session, $yahoo_session_2);
curl_multi_remove_handle($multi_session, $yahoo_session_3);

curl_multi_close($multi_session);  
*/	
?>