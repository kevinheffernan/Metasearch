<?php

////////////////////////////
////  GLOBAL VARIABLES  ////
////////////////////////////

$entire_web_results; 			//result data from entire_web
$entire_web_total_num_results; 	//total number of entire_web results
$entireweb_query;

$bing_results;					//result data from bing
$bing_total_num_results;		//total number of Bing results	
$bing_query;

$blekko_results;					//result data from blekko
$blekko_total_num_results;		//total number of blekko results
$blekko_query;

$entire_web_collection_weight;
$bing_collection_weight;
$blekko_collection_weight;

$entire_web_engine_weight;
$bing_engine_weight;
$blekko_engine_weight;

$url_array = array(); 			//array to hold url's of aggregated results 
$entire_web_urls = array();
$bing_urls = array();
$blekko_urls = array();

$processed_query = array();		//array to hold the processed query (will be used by clustering algorithm)

?>