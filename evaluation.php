<?php

/////////////////////////////////

$word = 0;

/////////////////////////////////

include('function_definitions.php');

$google_query_array = array('obama_family_tree',
'french_lick_resort_and_casino',
'getting_organized',
'toilet',
'mitchell_college',
'kcs',
'air_travel_information',
'appraisals',
'used_car_parts',
'cheap_internet',
'gmat_prep_classes',
'djs',
'map',
'dinosaurs',
'espn_sports',
'arizona_game_and_fish',
'poker_tournaments',
'wedding_budget_calculator',
'the_current',
'defender',
'volvo',
'rick_warren',
'yahoo',
'diversity',
'euclid',
'lower_heart_rate',
'starbucks',
'inuyasha',
'ps_2_games',
'diabetes_education',
'atari',
'website_design_hosting',
'elliptical_trainer',
'cell_phones',
'hoboken',
'gps',
'pampered_chef',
'dogs_for_adoption',
'disneyland_hotel',
'michworks',
'orange_county_convention_center',
'the_music_man',
'the_secret_garden',
'map_of_the_united_states',
'solar_panels',
'alexian_brothers_hospital',
'indexed_annuity',
'wilson_antenna',
'flame_designs',
'dog_heat');


$query_array = array('obama family tree',
'french lick resort and casino',
'getting organized',
'toilet',
'mitchell college',
'kcs',
'air travel information',
'appraisals',
'used car parts',
'cheap internet',
'gmat prep classes',
'djs',
'map',
'dinosaurs',
'espn sports',
'arizona game and fish',
'poker tournaments',
'wedding budget calculator',
'the current',
'defender',
'volvo',
'rick warren',
'yahoo',
'diversity',
'euclid',
'lower heart rate',
'starbucks',
'inuyasha',
'ps 2 games',
'diabetes education',
'atari',
'website design hosting',
'elliptical trainer',
'cell phones',
'hoboken',
'gps',
'pampered chef',
'dogs for adoption',
'disneyland hotel',
'michworks',
'orange county convention center',
'the music man',
'the secret garden',
'map of the united states',
'solar panels',
'alexian brothers hospital',
'indexed annuity',
'wilson antenna',
'flame designs',
'dog heat');

///////////////////////////////////
/// FOR GOOGLE RESULTS
///////////////////////////////////
$query = $google_query_array[$word];
//////////////////////////////////

///////////////////////////////////
/// FOR META RESULTS
///////////////////////////////////
$query_meta = $query_array[$word];
///////////////////////////////////

echo 'query = '.$google_query_array[$word].'<br>';
echo 'query meta = '.$query_array[$word].'<br>';

function GOOGLE_RESULTS()
{
	global $query;

	$suffix = 'google_results/'.$query.'/'.$query;
	$count = 1;
	$google_results = array();
	$google_url_array = array();

	for($number = 1 ; $number < 11 ; $number++)
	{
		$google_results[$number] = file_get_contents($suffix.'_'.$number.'.txt');
		$google_results[$number] = json_decode($google_results[$number]);

		foreach($google_results[$number]->items as $item)
		{
			echo $count.'<br>';
			echo $item->title.'<br>';
			echo $item->snippet.'<br>';
			echo $item->link.'<br>';
			
			$google_url_array[] = $item->link;

			echo '<br><br>';
			$count++;
		}
	}
	return $google_url_array;
}

function EVAL_MAIN()
{

///////////////////////////////////

$bing_1 = fopen("bing_precision.txt","a");
$bing_2 = fopen("bing_recall.txt","a");
$bing_3 = fopen("bing_precision_at_10.txt","a");
$bing_4 = fopen("bing_f_measure.txt","a");
$bing_5 = fopen("bing_rel_average_precision.txt","a");

///////////////////////////////////

///////////////////////////////////

$blekko_1 = fopen("blekko_precision.txt","a");
$blekko_2 = fopen("blekko_recall.txt","a");
$blekko_3 = fopen("blekko_precision_at_10.txt","a");
$blekko_4 = fopen("blekko_f_measure.txt","a");
$blekko_5 = fopen("blekko_rel_average_precision.txt","a");

///////////////////////////////////

	///////////////////
	
	$bing_precision = 0;
	
	$bing_recall = 0;
	
	$bing_precision_at_10 = 0;
	
	$bing_f_measure = 0;
	
	$bing_rel_average_precision = 0;
	
	///////////////////
	
	///////////////////
	
	$blekko_precision = 0;
	
	$blekko_recall = 0;
	
	$blekko_precision_at_10 = 0;
	
	$blekko_f_measure = 0;
	
	$blekko_rel_average_precision = 0;
	
	///////////////////

	global $query_meta;
	
	include('global_vars_insert.php');
	
	$_POST["query"] = $query_meta;
	
	$_POST['Case_Folding'] = 'Case_Folding_On';
	
	$_POST['Synonyms'] ='Synonyms_On';
	
	$_POST['Stemming'] = 'Stemming_On';
	
	include('preprocessor.php'); //run query through preprocessor

	/*
	$bing_query = $query_meta;
	$entireweb_query = $query_meta;
	$blekko_query = $query_meta;
	*/
	
	echo 'QUERY = '.$bing_query.'<br>';

	$google_url_array = GOOGLE_RESULTS();
	
	foreach($google_url_array as $key => $link)
	{
		echo ($key+1).'<br>';
		echo $link.'<br>';
	}
	
	get_initial_results();
	
	GET_BING_URLS();
	
	GET_BLEKKO_URLS();
	
	foreach($bing_urls as $key => $link)
	{
		echo ($key+1).'<br>';
		echo $link.'<br>';
	}
	
	foreach($blekko_urls as $key => $link)
	{
		echo ($key+1).'<br>';
		echo $link.'<br>';
	}
	
	
	
	
	/////////////////////////////////////////////////////
	
	
	
	
	$bing_num_rel_docs = 0;
	$rank = 1;
	
	$bing_total_docs = 0;
	
	//take into account that the results will not always be 100
	//for example, when I stem 'michworks' to 'michwork,' this only
	//ends up with 60 results, not 100
	if(count($bing_urls) < 100)
	{
		$bing_total_docs = count($bing_urls);
	}
	else
	{
		$bing_total_docs = 100;
	}
	
	foreach($bing_urls as $key => $url)
	{
		if(in_array($url,$google_url_array))
		{
			$bing_num_rel_docs++;
			
			$bing_rel_average_precision += ($bing_num_rel_docs / $rank);
			
			echo $bing_num_rel_docs.'/'.$rank.'<br>';
			
		}
		if($rank == 10)
		{
			$bing_precision_at_10 = $bing_num_rel_docs / 10;
		}
		
		$rank++;
	}
	
	$bing_precision = $bing_num_rel_docs / $bing_total_docs;
	
	$bing_recall = $bing_num_rel_docs / 100;
	
	$bing_f_measure = (2 * $bing_precision * $bing_recall) / ($bing_precision + $bing_recall);
	if($bing_precision == 0)
	{
		$bing_f_measure = 0.0;
	}
	
	$bing_rel_average_precision  = $bing_rel_average_precision / 100;
	
	echo '<br>BING TOTAL DOCS = '.$bing_total_docs.'<br>';
	
	echo 'Bing Precision = '.$bing_precision.'<br>';
	fwrite($bing_1,$bing_precision."\n");
	
	echo 'Bing Recall = '.$bing_recall.'<br>';
	fwrite($bing_2,$bing_recall."\n");
	
	echo 'Bing Precision at 10 = '.$bing_precision_at_10.'<br>';
	fwrite($bing_3,$bing_precision_at_10."\n");
	
	echo 'Bing F-Measure = '.$bing_f_measure.'<br>';
	fwrite($bing_4,$bing_f_measure."\n");
	
	echo 'Bing Rel Ave Precision TOTAL = '.$bing_rel_average_precision.'<br>';
	fwrite($bing_5,$bing_rel_average_precision."\n");
	
	
	
	
	////////////////////////////////////////////////////
	
	
	
	
	$blekko_num_rel_docs = 0;
	$rank = 1;
	
	$blekko_total_docs = 0;
	
	//take into account that the results will not always be 100
	//for example, when I stem 'michworks' to 'michwork,' this only
	//ends up with 60 results, not 100
	if(count($blekko_urls) < 100)
	{
		$blekko_total_docs = count($blekko_urls);
	}
	else
	{
		$blekko_total_docs = 100;
	}
	
	foreach($blekko_urls as $key => $url)
	{
		if(in_array($url,$google_url_array))
		{
			$blekko_num_rel_docs++;
			
			$blekko_rel_average_precision += ($blekko_num_rel_docs / $rank);
			
			echo $blekko_num_rel_docs.'/'.$rank.'<br>';
			
		}
		if($rank == 10)
		{
			$blekko_precision_at_10 = $blekko_num_rel_docs / 10;
		}
		
		$rank++;
	}
	
	$blekko_precision = $blekko_num_rel_docs / $blekko_total_docs;
	
	$blekko_recall = $blekko_num_rel_docs / 100;
	
	$blekko_f_measure = (2 * $blekko_precision * $blekko_recall) / ($blekko_precision + $blekko_recall);
	if($blekko_precision == 0)
	{
		$blekko_f_measure = 0.0;
	}
	
	$blekko_rel_average_precision  = $blekko_rel_average_precision / 100;
	
	echo '<br>BLEKKO TOTAL DOCS = '.$blekko_total_docs.'<br>';
	
	echo 'blekko Precision = '.$blekko_precision.'<br>';
	fwrite($blekko_1,$blekko_precision."\n");
	
	echo 'blekko Recall = '.$blekko_recall.'<br>';
	fwrite($blekko_2,$blekko_recall."\n");
	
	echo 'blekko Precision at 10 = '.$blekko_precision_at_10.'<br>';
	fwrite($blekko_3,$blekko_precision_at_10."\n");
	
	echo 'blekko F-Measure = '.$blekko_f_measure.'<br>';
	fwrite($blekko_4,$blekko_f_measure."\n");
	
	echo 'blekko Rel Ave Precision TOTAL = '.$blekko_rel_average_precision.'<br>';
	fwrite($blekko_5,$blekko_rel_average_precision."\n");
	
	
	
	/////////////////////////////////////////////////////
	
	
	
	
}

function GET_BING_URLS()
{
	include('global_vars_insert.php');
	
	$result_number = 0; //this will reset everytime and keeps count of no. of page results

	for($index=0;$index<3;$index++)
	{
    	foreach($bing_results[$index]->d->results[0]->Web as $value)
    	{
       		$bing_urls[] = $value->Url;
   	 	}	
    
    }
}

function GET_BLEKKO_URLS()
{
	include('global_vars_insert.php');
	
	foreach($blekko_results as $item)
	{
    	foreach($item->RESULT as $value)
    	{	      
        	$blekko_urls[] = $value->url;
    	}
	}
}

///////////////////////////////////

EVAL_MAIN(); //call main function

///////////////////////////////////




?>