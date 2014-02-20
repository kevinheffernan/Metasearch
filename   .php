<?php

include('function_definitions.php');

get_initial_results(); 
calculate_collection_weights();		
calculate_engine_weights();
calculate_merged_pages_scores();

echo '<div style="width:50%; background-color:pink; float:left; text-align:left" >';

//marker to keep count of results		
$t = 1;
			
function cmp($a, $b) 
{
   	return $a["weight"] < $b["weight"];
}

usort($url_array,"cmp"); //sort array from high to low
			
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
		
echo '<br><br>';

echo '</div>';

?>
