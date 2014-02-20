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
/// FOR AGG RESULTS
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

$agg_1 = fopen("agg_precision.txt","a");
$agg_2 = fopen("agg_recall.txt","a");
$agg_3 = fopen("agg_precision_at_10.txt","a");
$agg_4 = fopen("agg_f_measure.txt","a");
$agg_5 = fopen("agg_rel_average_precision.txt","a");

///////////////////////////////////


	///////////////////
	
	$agg_precision = 0;
	
	$agg_recall = 0;
	
	$agg_precision_at_10 = 0;
	
	$agg_f_measure = 0;
	
	$agg_rel_average_precision = 0;
	
	
	///////////////////


	global $query_meta;
	global $url_array;
	
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
	calculate_collection_weights();		
	calculate_engine_weights();
	calculate_merged_pages_scores();
		
	usort($url_array,"cmp"); //sort array from high to low
	
	$t = 1;
	
	
	foreach($url_array as $key=>$val) 
{
	echo $t.'<br>';
	echo 'TOTAL WEIGHT = '.$val["weight"].'<br>';
	echo '<a href="'.$val["url"].'">'.$val["title"].'</a>'.'<br>';
	echo $val["description"].'<br>';
	echo $val["url"].'<br><br>';
			
	$t += 1;
	
	if($t > 100) //only show top 100 results
	{
		break;
	}
}
	
		
	/////////////////////////////////////////////////////
		
	
	$agg_num_rel_docs = 0;
	$rank = 1;
	$total_docs = 0;
	
	//take into account that the results will not always be 100
	//for example, when I stem 'michworks' to 'michwork,' this only
	//ends up with 60 results, not 100
	if(count($url_array) < 100)
	{
		$total_docs = count($url_array);
	}
	else
	{
		$total_docs = 100;
	}
	
	echo '<br>TOTAL DOCS = '.$total_docs.'<br>';
	
	
	foreach($url_array as $key => $val)
	{
		if(in_array($val["url"],$google_url_array))
		{
			$agg_num_rel_docs++;
			
			$agg_rel_average_precision += ($agg_num_rel_docs / $rank);
			
			echo $agg_num_rel_docs.'/'.$rank.'<br>';
			
		}
		if($rank == 10)
		{
			$agg_precision_at_10 = $agg_num_rel_docs / 10;
		}
		else if($rank == 100)
		{
			break;
		}
		
		$rank++;
	}
	
	$agg_precision = $agg_num_rel_docs / $total_docs;
	
	$agg_recall = $agg_num_rel_docs / 100;
	
	$agg_f_measure = (2 * $agg_precision * $agg_recall) / ($agg_precision + $agg_recall);
	if(($agg_precision + $agg_recall)==0)
	{
		$agg_f_measure = 0.0;
	}
	
	$agg_rel_average_precision  = $agg_rel_average_precision / 100;
	
	echo 'agg Precision = '.$agg_precision.'<br>';
	fwrite($agg_1,$agg_precision."\n");
	
	echo 'agg Recall = '.$agg_recall.'<br>';
	fwrite($agg_2,$agg_recall."\n");
	
	echo 'agg Precision at 10 = '.$agg_precision_at_10.'<br>';
	fwrite($agg_3,$agg_precision_at_10."\n");
	
	echo 'agg F-Measure = '.$agg_f_measure.'<br>';
	fwrite($agg_4,$agg_f_measure."\n");
	
	echo 'agg Rel Ave Precision TOTAL = '.$agg_rel_average_precision.'<br>';
	fwrite($agg_5,$agg_rel_average_precision."\n");
	
	
	
	
	////////////////////////////////////////////////////
	
	
}

function cmp($a, $b) 
{
   	return $a["weight"] < $b["weight"];
}


///////////////////////////////////

EVAL_MAIN(); //call main function

///////////////////////////////////




?>