<?php

function display_yahoo_results()
{
	include('global_vars_insert.php');

	echo($yahoo_total_num_results.' results<br><br>');
	
	$result_number = 0; //this will reset everytime and keeps count of no. of page results
	$index=0;
	
	for(;$index<3;)
	{
    	foreach($yahoo_results[$index]->bossresponse->web->results as $value)
    	{
    	
    		$result_number += 1;
    		$yahoo_page_score = 1 - (($result_number-1)/100);
    		$yahoo_weighted_page_score = $yahoo_page_score*$yahoo_engine_weight;
    		
    		echo('<br><br>INITIAL PAGE SCORE: '.$yahoo_page_score.'<br>');
    		echo('WEIGHTED PAGE SCORE: '.$yahoo_weighted_page_score.'<br>');
    		echo('result: '.$result_number);
    		echo ('<div>');
    		echo('<a href="'.$value->clickurl.'">'.$value->title.'</a>'.'<br>');
    		echo($value->abstract.'<br>');
    		echo('<u>'.$value->clickurl.'</u><br><br>');
    		echo('</div>');
    		
    		if (array_key_exists($value->clickurl, $yahoo_urls)==False) //initialize if new
    		{
       			$yahoo_urls[$value->clickurl] = $yahoo_page_score;
       		}
       		/*
       		if (array_key_exists($value->clickurl, $url_array)==False) //initialize if new
    		{
       			$url_array[$value->clickurl] = 0;
       		}
       		*/
    	}
    	$index += 1;
    }
    
}

?>