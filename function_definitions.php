<?php

//////////////////////////////
///  FUNCTION DEFINITIONS  ///
//////////////////////////////

function get_initial_results()
{
	include('global_vars_insert.php');

	////////////////////////////////////////////
	/////	BING INITIAL SEARCH DATA 
	////////////////////////////////////////////
	
	include('bing_search_initial.php');

    ////////////////////////////////////////////
	/////	ENTIREWEB INITIAL SEARCH DATA  
	////////////////////////////////////////////
    
	include('entireweb_search_initial.php');

	////////////////////////////////////////////
	/////	BLEKKO INITIAL SEARCH REQUEST  
	////////////////////////////////////////////  
	
	include('blekko_search_initial.php');
	
	////////////////////////////////////////////
	/////	CURL MULTI-SESSION DATA    
	////////////////////////////////////////////

	include('curl.php');
}

function display_bing_results()
{
	include('global_vars_insert.php');
	
	echo($bing_total_num_results.' results<br><br>');
	
	$result_number = 0; //this will reset everytime and keeps count of no. of page results

	for($index=0;$index<3;$index++)
	{
    foreach($bing_results[$index]->d->results[0]->Web as $value)
    {
    	$result_number += 1;
    
    	$bing_page_score = 1 - (($result_number-1)/100);
    	$bing_weighted_page_score = $bing_page_score*$bing_engine_weight;
    	
    	echo('<br><br>INITIAL PAGE SCORE: '.$bing_page_score.'<br>');
    	echo('WEIGHTED PAGE SCORE: '.$bing_weighted_page_score.'<br>');
    	echo('result: '.$result_number);
    	echo ('<div>');
    	echo('<a href="'.$value->Url.'">'.$value->Title.'</a>'.'<br>');
    	echo($value->Description.'<br>');
    	echo('<u>'.$value->Url.'</u><br><br>');
    	echo('</div>');
    	
    	if (array_key_exists($value->Url, $bing_urls)==False) //initialize if new
    	{
       		$bing_urls[$value->Url] = $bing_page_score;
       	}
       	
    }
    
    }
}

function display_entire_web_results()
{
	include('global_vars_insert.php');
	
	echo($entire_web_total_num_results.' results<br><br>');
	
    foreach($entire_web_results->hits as $value)
    {      
    	echo ('<div>');
    	
    	$entire_web_page_score = 1 - (($value->index-1)/100);
    	$entire_web_weighted_page_score = $entire_web_page_score*$entire_web_engine_weight;
    	
    	echo('<br><br>INITIAL PAGE SCORE: '.$entire_web_page_score.'<br>');
    	echo('WEIGHTED PAGE SCORE: '.$entire_web_weighted_page_score.'<br>');
    	echo('result: '.$value->index.'<br>');
        echo('<a href="'.$value->link.'">'.$value->title.'</a><br>');
        
       	if (array_key_exists($value->url, $entire_web_urls)==False) //initialize if new
    	{
       		$entire_web_urls[$value->url] = $entire_web_page_score;
       	}
        		
        if ($value->snippet != "") 		//don't display empty descriptions
        {
        	echo($value->snippet.'<br>');
        }
        		
        echo('<u>'.$value->url.'</u><br><br>');
	
    	echo('</div>');
    }
}

function display_blekko_results()
{
	include('global_vars_insert.php');

	echo($blekko_total_num_results.' results<br><br>');
	$result_number = 0; //this will reset everytime and keeps count of no. of page results
	
foreach($blekko_results as $item)
{
    foreach($item->RESULT as $value)
    {      
    	echo ('<div>');
    	
    	$result_number += 1;
    	$blekko_page_score = 1 - (($result_number-1)/100);
    	
    	$blekko_weighted_page_score = $blekko_page_score*$blekko_engine_weight;
    	
    	echo('<br><br>INITIAL PAGE SCORE: '.$blekko_page_score.'<br>');
    	echo('WEIGHTED PAGE SCORE: '.$blekko_weighted_page_score.'<br>');
    	echo('result: '.$result_number.'<br>');
        echo('<a href="'.$value->url.'">'.$value->url_title.'</a><br>');
        
       	if (array_key_exists($value->url, $blekko_urls)==False) //initialize if new
    	{
       		$blekko_urls[$value->url] = $blekko_page_score;
       	}
        		
        if ($value->snippet != "") 		//don't display empty descriptions
        {
        	echo($value->snippet.'<br>');
        }
        		
        echo('<u>'.$value->url.'</u><br><br>');
	
    	echo('</div>');
    }
}
}

function calculate_collection_weights() //lms (length merge score, part 1)
{
	include('global_vars_insert.php');
	
	$total_collection = $entire_web_total_num_results+$bing_total_num_results+$blekko_total_num_results;
	
	$k = 600; //constant from formula
	
	$entire_web_collection = $entire_web_total_num_results;
	
	$bing_collection = $bing_total_num_results;
	
	$blekko_collection = $blekko_total_num_results;
	
    $entire_web_collection_weight = log(1+(($entire_web_collection*$k)/($total_collection)),10); //needed to set to base 10
    $bing_collection_weight   = log(1+(($bing_collection*$k)/($total_collection)),10);   //needed to set to base 10
    $blekko_collection_weight  = log(1+(($blekko_collection*$k)/($total_collection)),10);  //needed to set to base 10
}

function calculate_engine_weights()
{
	include('global_vars_insert.php');
	
	$mean_collection_weight = ($entire_web_collection_weight+$bing_collection_weight+$blekko_collection_weight)/3;
	
	$entire_web_engine_weight = 1 + (($entire_web_collection_weight-$mean_collection_weight)/$mean_collection_weight);
	$bing_engine_weight       = 1 + (($bing_collection_weight-$mean_collection_weight)/$mean_collection_weight);
	$blekko_engine_weight     = 1 + (($blekko_collection_weight-$mean_collection_weight)/$mean_collection_weight);
}

function calculate_merged_pages_scores()
{
	include('global_vars_insert.php');
	
	////BLEKKO DATA////
foreach($blekko_results as $item)
{
	foreach($item->RESULT as $value)
	{
		$blekko_page_score = 1 - (($value->n_group-1)/100);
    	$blekko_weighted_page_score = $blekko_page_score*$blekko_engine_weight;
    	if (array_key_exists($value->url, $url_array)==False) //initialize if new
    	{
       		$url_array[$value->url] = array();
       		$url_array[$value->url]["url"] = $value->url;
       		$url_array[$value->url]["weight"] = $blekko_weighted_page_score;
       		$url_array[$value->url]["description"] = $value -> snippet;
       		$url_array[$value->url]["title"] = $value -> url_title;
       	}
       	else if (array_key_exists($value->url, $url_array)==True) //else add page score
       	{
       		$url_array[$value->url]["weight"] += $blekko_weighted_page_score;
       	}
	}
}
	////BING DATA////

	$result_number = 0; //reset result number
	for($index=0;$index<3;$index++)
	{
		foreach($bing_results[$index]->d->results[0]->Web as $value)
		{
			$result_number += 1;	
			$bing_page_score = 1 - (($result_number-1)/100);
    		$bing_weighted_page_score = $bing_page_score*$bing_engine_weight;
    		if (array_key_exists($value->Url, $url_array)==False) //initialize if new
    		{
       			$url_array[$value->Url] = array();
       			$url_array[$value->Url]["url"] = $value->Url;
       			$url_array[$value->Url]["weight"] = $bing_weighted_page_score;
       			$url_array[$value->Url]["description"] = $value -> Description;
       			$url_array[$value->Url]["title"] = $value -> Title;
       		}
       		else if (array_key_exists($value->Url, $url_array)==True) //else add page score
       		{
       			$url_array[$value->Url]["weight"] += $bing_weighted_page_score;
       		}
		}
	}
	
	////entire_web DATA////
	
	foreach($entire_web_results->RESULT as $value)
	{
		$entire_web_page_score = 1 - (($value->n_group-1)/100);
    	$entire_web_weighted_page_score = $entire_web_page_score*$entire_web_engine_weight;
    	if (array_key_exists($value->url, $url_array)==False) //initialize if new
    	{
       		$url_array[$value->url] = array();
       		$url_array[$value->url]["url"] = $value->url;
       		$url_array[$value->url]["weight"] = $entire_web_weighted_page_score;
       		$url_array[$value->url]["description"] = $value -> snippet;
       		$url_array[$value->url]["title"] = $value -> url_title;
       	}
       	else if (array_key_exists($value->url, $url_array)==True) //else add page score
       	{
       		$url_array[$value->url]["weight"] += $entire_web_weighted_page_score;
       	}
	}
}

?>