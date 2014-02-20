<?php

////////////////////////////////////
/// DISPLAY NON-AGGREGATED RESULTS
////////////////////////////////////

include('function_definitions.php');

get_initial_results(); 
calculate_collection_weights();		
calculate_engine_weights();
	
echo '	<div class="bing_results">';
echo '		<h2 align="center">Bing Search Results</h2>';	
echo 'COLLECTION WEIGHT: '.$bing_collection_weight.'<br>';
echo 'ENGINE WEIGHT: '.$bing_engine_weight.'<br><br>';	
display_bing_results();	
echo '	</div>';

echo '	<div class="entireweb_results">';
echo '		<h2 align="center">Entireweb Search Results</h2>';	
echo 'COLLECTION WEIGHT: '.$entire_web_collection_weight.'<br>';
echo 'ENGINE WEIGHT: '.$entire_web_engine_weight.'<br><br>';		
display_entire_web_results();			
echo '	</div>';
		
echo '	<div class="blekko_results">';
echo '		<h2 align="center">Blekko Search Results</h2>';				
echo 'COLLECTION WEIGHT: '.$blekko_collection_weight.'<br>';
echo 'ENGINE WEIGHT: '.$blekko_engine_weight.'<br><br>';				
display_blekko_results();
echo '	</div>';
	
?>